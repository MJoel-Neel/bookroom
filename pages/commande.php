<?php
session_start();
require_once('../config.php');
require_once('../errors/flash.php');
require_once('../includes/cart.php');

// client doit être connecté pour pouvoir effectuer un achat.
if (!is_logged_in()) {
    header("Location: ./login.php");
    exit();
}

$id_client = get_client_id();

// addresse du client
$stmt_client = $conn->prepare("SELECT adresse_client FROM client WHERE id_client = ?");
$stmt_client->bind_param('i', $id_client);
$stmt_client->execute();
$client_info = $stmt_client->get_result()->fetch_assoc();
$stmt_client->close();
$adresse_client = $client_info['adresse_client'] ?? 'Adresse non renseignée';

// Fetch all orders and their items in ONE query
$sql = "
    SELECT c.id_commande, c.date_commande, c.prix_total, c.statut,
           cp.quantite_commandee, cp.prix_unitaire,
           l.titre_livre, l.image_livre
    FROM commande c
    LEFT JOIN commande_produit cp ON c.id_commande = cp.id_commande
    LEFT JOIN livre l ON cp.id_livre = l.id_livre
    WHERE c.id_client_Effectuer = ?
    ORDER BY c.date_commande DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_client);
$stmt->execute();
$res = $stmt->get_result();

$commandes = [];
while ($row = $res->fetch_assoc()) {
    $id_cmd = $row['id_commande'];
    if (!isset($commandes[$id_cmd])) {
        $commandes[$id_cmd] = [
            'id' => $id_cmd,
            'date' => $row['date_commande'],
            'total' => $row['prix_total'],
            'statut' => $row['statut'],
            'items' => []
        ];
    }
    if ($row['titre_livre']) {
        $commandes[$id_cmd]['items'][] = $row;
    }
}
$stmt->close();

$seo = [
    'title' => 'Mes Commandes – Historique d\'achats | Bookroom',
    'description' => 'Consultez l\'historique de vos commandes Bookroom et suivez l\'état de vos achats de livres.',
    'robots' => 'noindex,follow',
];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require __DIR__ . '/../includes/head.php'; ?>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/flash.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/commande.css">
    <script src="<?= BASE_URL ?>/assets/js/commande.js" defer></script>
</head>

<body>
    <?php include_once("../includes/header.php"); ?>
    <?php flash_render(); ?>

    <section class="commandes">
        <h2 id="cart">Mes Commandes</h2>

        <?php if (empty($commandes)): ?>
            <div class="panier-vide">
                <div class="message">
                    <p>Aucune commande pour le moment</p>
                    <a class="view-boutique" href="../index.php?#categorie">Trouver l'inspiration</a>
                </div>
                <div class="bg-color"></div>
            </div>
        <?php else: ?>
            <?php foreach ($commandes as $cmd):
                $date = date('d M Y', strtotime($cmd['date']));
                $statut_class = strtolower($cmd['statut']);
                ?>
                <div class="commande-block">
                    <!-- EN-TÊTE (Accordéon) -->
                    <div class="commande-header">
                        <div class="header-info">
                            <span class="cmd-id">Commande #<?= $cmd['id'] ?></span>
                            <span class="cmd-date"><?= $date ?></span>
                        </div>
                        <div class="header-right">
                            <span
                                class="badge <?= $statut_class ?>"><?= ucfirst(str_replace('_', ' ', $cmd['statut'])) ?></span>
                            <span class="cmd-total"><?= number_format($cmd['total'], 2, ',', ' ') ?> €</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                    </div>

                    <!-- CONTENU (Caché par défaut) -->
                    <div class="commande-content">

                        <!-- Résumé + Adresse -->
                        <div class="commande-summary-address">
                            <div class="summary-box">
                                <h3>RÉSUMÉ :</h3>
                                <p><strong>Commande :</strong> #<?= $cmd['id'] ?></p>
                                <p><strong>Date :</strong> <?= $date ?></p>
                                <p><strong>Total :</strong> <?= number_format($cmd['total'], 2, ',', ' ') ?> €</p>
                            </div>
                            <div class="address-box">
                                <h3>ADRESSE DE LIVRAISON :</h3>
                                <p><?= htmlspecialchars($_SESSION['user_name'] ?? 'Client') ?></p>
                                <p><?= htmlspecialchars($adresse_client ?? 'Adresse non renseignée') ?></p>
                            </div>
                        </div>

                        <!-- Liste des livres (comme ton image) -->
                        <div class="commande-items">
                            <div class="items-header">
                                <span class="col-title">ARTICLES EXPÉDIÉS</span>
                                <span class="col-qty">QTÉ</span>
                                <span class="col-price">PRIX</span>
                            </div>
                            <?php
                            $sous_total = 0;
                            foreach ($cmd['items'] as $item):
                                $sous_total += ($item['prix_unitaire'] * $item['quantite_commandee']);
                                $img_src = str_contains($item["image_livre"], 'tmp/') ? "/produits/anonymous_book.webp" : $item["image_livre"];
                                ?>
                                <div class="item-row">
                                    <div class="item-info">
                                        <img src="../assets/images<?= $img_src ?>" alt="Livre">
                                        <span class="item-name"><?= htmlspecialchars($item['titre_livre']) ?></span>
                                    </div>
                                    <span class="item-qty"><?= $item['quantite_commandee'] ?></span>
                                    <span class="item-price"><?= number_format($item['prix_unitaire'], 2, ',', ' ') ?> €</span>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Totaux -->
                        <div class="commande-totals">
                            <div class="total-row">
                                <span>Sous-total :</span>
                                <span><?= number_format($sous_total, 2, ',', ' ') ?> €</span>
                            </div>
                            <div class="total-row">
                                <span>Livraison :</span>
                                <span>0.00 €</span>
                            </div>
                            <div class="total-row grand-total">
                                <span>Total :</span>
                                <span><?= number_format($cmd['total'], 2, ',', ' ') ?> €</span>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <?php include_once("../includes/footer.php"); ?>
</body>

</html>