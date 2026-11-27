<?php
session_start();

require_once('../config.php');
require_once('../errors/flash.php');
require_once('../includes/cart.php');


// 1. On accepte uniquement les requêtes POST pour valider une commande.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ./panier.php");
    exit();
}


// 2. Le client doit être connecté pour pouvoir effectuer un achat.
if (!is_logged_in()) {
    flash_warning("Veuillez vous connecter pour payer.");
    header("Location: ./login.php");
    exit();
}


// Récupération de l'identifiant du client connecté et de son panier.
$id_client = get_client_id();
$panier = cart_get();


// Vérification que le panier contient au moins un article.
if (empty($panier)) {
    flash_warning("Votre panier est vide.");
    header("Location: ./panier.php");
    exit();
}


try {
    // Démarrage d'une transaction SQL.
    // Toutes les modifications seront validées ensemble avec commit().
    // En cas d'erreur, rollback() permettra de toutes les annuler.
    $conn->begin_transaction();

    $total_general = 0;
    $lignes_commande = [];


    // 3. Vérification des stocks et recalcul des prix côté serveur
    $placeholders = implode(',', array_fill(0, count($panier), '?'));

    $types = str_repeat('i', count($panier));

    // Récupération des identifiants des livres présents dans le panier.
    $ids = array_keys($panier);


    // FOR UPDATE verrouille les lignes sélectionnées jusqu'à la fin
    // de la transaction afin d'éviter les modifications concurrentes du stock.
    $stmt = $conn->prepare("
        SELECT id_livre, prix, reduction_livre, stock
        FROM livre
        WHERE id_livre IN ($placeholders)
        FOR UPDATE
    ");
    $stmt->bind_param($types, ...$ids);
    $stmt->execute();
    $res = $stmt->get_result();

    while ($row = $res->fetch_assoc()) {

        $id_livre = $row['id_livre'];
        $qte = $panier[$id_livre];

        // Vérification que le stock disponible est suffisant.
        if ($row['stock'] < $qte) {
            throw new Exception("Stock insuffisant pour le livre ID $id_livre.");
        }

        // Récupération du prix initial et du pourcentage de réduction.
        $prix_brut = (float) $row['prix'];
        $reduction = (int) $row['reduction_livre'];

        // Calcul du prix unitaire après application de la réduction.
        $prix_unitaire = $prix_brut * (1 - ($reduction / 100));

        // Calcul du montant total pour ce livre.
        $sous_total = $prix_unitaire * $qte;

        // Ajout du sous-total au montant global de la commande.
        $total_general += $sous_total;


        $lignes_commande[] = [
            'id_livre' => $id_livre,
            'qte' => $qte,
            'prix_unitaire' => $prix_unitaire
        ];
    }

    $stmt->close();

    if (count($lignes_commande) !== count($panier)) {
        throw new Exception("Un ou plusieurs livres du panier sont introuvables.");
    }
    // 4. Création de la commande
    // Ici, le statut "payee" simule un paiement réussi.
    $statut = 'payee';

    // Préparation de la requête d'insertion de la commande.
    $stmt = $conn->prepare("
        INSERT INTO commande
        (id_client_Effectuer, prix_total, statut)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param('ids', $id_client, $total_general, $statut);
    $stmt->execute();

    $id_commande = $conn->insert_id;
    $stmt->close();


    // 5. Enregistrement des produits et mise à jour des stocks
    // Préparation de la requête d'insertion des produits dans la commande.
    $stmt_ligne = $conn->prepare("
        INSERT INTO commande_produit
        (id_commande, id_livre, quantite_commandee, prix_unitaire)
        VALUES (?, ?, ?, ?)
    ");

    // Préparation de la requête permettant de diminuer le stock.
    $stmt_stock = $conn->prepare("
        UPDATE livre
        SET stock = stock - ?
        WHERE id_livre = ?
    ");

    foreach ($lignes_commande as $ligne) {
        $stmt_ligne->bind_param(
            'iiid',
            $id_commande,
            $ligne['id_livre'],
            $ligne['qte'],
            $ligne['prix_unitaire']
        );
        $stmt_ligne->execute();


        // Déduction de la quantité commandée du stock disponible.
        $stmt_stock->bind_param(
            'ii',
            $ligne['qte'],
            $ligne['id_livre']
        );

        $stmt_stock->execute();
    }

    $stmt_ligne->close();
    $stmt_stock->close();


    // 6. Archivage du panier
    // Passage du panier actif au statut "valide".
    // Enregistrement également de la date de validation.
    $stmt = $conn->prepare("
        UPDATE panier
        SET statut = 'valide', date_validation = NOW()
        WHERE id_client = ? AND statut = 'actif'
    ");

    $stmt->bind_param('i', $id_client);
    $stmt->execute();
    $stmt->close();


    // Validation définitive de toutes les opérations SQL.
    $conn->commit();


    // Vidage du panier conservé dans la session PHP.
    $_SESSION['panier'] = [];
    unset($_SESSION['total']);


    // Affichage d'un message de confirmation et redirection.
    flash_success("Commande validée avec succès !");
    header("Location: ./commande.php");
    exit();


} catch (Exception $e) {
    // Annulation de toutes les modifications SQL de la transaction.
    $conn->rollback();
    // Enregistrement du détail de l'erreur dans les journaux du serveur.
    error_log("Erreur payer.php : " . $e->getMessage());
    // Affichage d'un message générique au client.
    flash_error("Une erreur est survenue lors de la validation de la commande.");
    header("Location: ./panier.php");
    exit();
}
?>