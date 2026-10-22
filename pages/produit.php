<?php
session_start();
include_once("../config.php"); // $conn mysqli, strict → exceptions
require_once("../errors/flash.php");
require_once("../includes/cart.php");


// Initialise le panier (évite les warnings si première visite)
if (!isset($_SESSION["panier"])) {
    $_SESSION["panier"] = [];
}

// Sans ?k=, on renvoie à l'accueil
if (!isset($_GET['k'])) {
    header("location: ../index.php");
    exit;
}


$id_produit = (int) $_GET['k'];

/*PARTIE PRODUIT CLIQUé*/
try {
    $query1 = $conn->prepare("SELECT l.id_livre, l.image_livre, l.reduction_livre, l.titre_livre, c.nom_categorie, l.resume_livre, l.auteur_livre, l.prix, l.langue, l.public_legal FROM livre l
    INNER JOIN categorie c
    ON l.id_categorie = c.id_categorie
    WHERE l.id_livre = ?;");
    $query1->bind_param('i', $id_produit);
    $query1->execute();
    $res1 = $query1->get_result();
    $article = $res1->fetch_assoc();
    $query1->close();
} catch (mysqli_sql_exception $e) {
    error_log("produit.php article: " . $e->getMessage());
}

// Produit introuvable → retour accueil
if (!$article) {
    header("location: ../index.php");
    exit;
}

/*BUTTON AJOUTER AU PANIER (formulaire quantité)*/
if (isset($_POST['qte'])) {
    $qte = (int) $_POST['qte'];
    if ($qte < 1) {
        $qte = 1;
    } // garde-fou : pas de 0 ni négatif

    $id_produit = (int) $article['id_livre'];
    $is_success = cart_add($id_produit, $qte);
    if ($is_success) {
        flash_info("Panier mis à jour ⏲️");
    }

    header("Location: ./produit.php?nom_produit=" . urlencode($article['titre_livre']) . "&k=" . $article['id_livre']);
    exit();
}




/*PARTIE PRODUIT EN LIEN (même catégorie) */
$res2 = null;
try {
    $id_produit = (int) $article['id_livre'];
    $categorie = $article['nom_categorie'];
    $query2 = $conn->prepare("SELECT * FROM livre l
    INNER JOIN categorie c
    ON l.id_categorie = c.id_categorie WHERE nom_categorie = ? AND l.id_livre != ? limit 4;");
    $query2->bind_param("si", $categorie, $id_produit);
    $query2->execute();
    $res2 = $query2->get_result();
    $query2->close();
} catch (mysqli_sql_exception $e) {
    error_log("produit.php suggestions: " . $e->getMessage());
}

//GESTION DES AVIS
//DONNER SON AVIS
if (!empty($_POST) && isset($_POST['note'])) {
    if (!isset($_SESSION["user_id"])) {
        header("Location: ./login.php");
        exit();
    }

    try {
        $id_user = (int) $_SESSION["user_id"];

        $note = (int) $_POST['note'];
        $commentaire = htmlspecialchars($_POST['avis']) ?? '';
        $client_id = (int) $_SESSION["user_id"];
        $email = isset($_SESSION["user_email"]) ? $_SESSION["user_email"] : '';
        $pseudo = (isset($_SESSION["user_name"])) ? htmlspecialchars($_SESSION["user_name"]) : "Anonymous";
        $livre = (int) $article['id_livre'];

        // Si l'email n'est pas en session, on le récupère proprement
        if ($email === '') {
            $cq = $conn->prepare("SELECT email_client FROM client WHERE id_client = ?");
            $cq->bind_param("i", $id_user);
            $cq->execute();
            $crow = $cq->get_result()->fetch_assoc();
            $cq->close();
            $email = $crow['email_client'] ?? '';
        }

        $query3 = $conn->prepare("INSERT INTO avis (note, commentaire, id_livre_concerner, id_client_faire, email_client, pseudo_client) 
                VALUES (?, ?, ?, ?, ?, ?)");
        $query3->bind_param("isiiss", $note, $commentaire, $livre, $client_id, $email, $pseudo);
        $query3->execute();
        $query3->close();

        flash_info("Avis pris en compte !");
        header("Location: ./produit.php?nom_produit=" . urlencode($article['titre_livre']) . "&k=" . $article['id_livre'] . "#feedback");
        exit();
    } catch (mysqli_sql_exception $e) {
        error_log("produit.php INSERT avis: " . $e->getMessage());
        flash_warning("Impossible d'enregistrer votre avis, réessayez.");
    }
}

//AFFICHER LES AVIS

$res4 = null;
$total_avis = 0;
try {
    $id_livre = (int) $article['id_livre'];
    $query4 = $conn->prepare("SELECT * FROM avis WHERE id_livre_concerner = ? ORDER BY id_avis DESC;");
    $query4->bind_param("i", $id_livre);
    $query4->execute();
    $res4 = $query4->get_result();
    $total_avis = $res4->num_rows;
    $query4->close();
} catch (mysqli_sql_exception $e) {
    error_log("produit.php avis: " . $e->getMessage());
}




/* Ajout produit + gestion quantité */
if (isset($_POST["produit_id"])) {
    $id = (int) $_POST["produit_id"];
    $is_success = cart_add($id, 1);
    if ($is_success) {
        flash_info("Panier mis à jour ⏲️");
    }

    // évite la duplication lors du refresh
    header("Location: ./produit.php?nom_produit=" . urlencode($article['titre_livre']) . "&k=" . $article['id_livre']);
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php
    echo htmlspecialchars($article['titre_livre']) . ' de ' .
        htmlspecialchars($article['auteur_livre']) .
        ' disponible à l\'achat. Livraison rapide et prix attractif.';
    ?>">
    <link rel="stylesheet" href="../assets/css/flash.css">
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/fonts/font.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js" defer></script>
    <script src="../assets/js/dataAOS.js" defer></script>
    <link rel="stylesheet" href="../assets/css/produit.css">
    <script src="../assets/js/produit.js" defer></script>
    <title>
        <?php
        echo htmlspecialchars($article['titre_livre']) . " – " .
            htmlspecialchars($article['auteur_livre']) .
            " | Livre, $article[nom_categorie] à acheter | Bookroom";
        ?>
    </title>
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon16.png">

    <?php include_once("../includes/header_head.php"); ?>
    <?php include_once("../includes/footer_head.php"); ?>
</head>

<body>
    <!--HEADER-->
    <?php
    require_once("../includes/header.php");
    ?>
    <?php flash_render(); ?>

    <!--PROUDUIT-->
    <div class="produit-container">
        <div class="produit-img" data-aos="zoom-in">
            <img loading="lazy" src="../assets/images<?= $article['image_livre'] ?>" alt="">
        </div>

        <div class="produit-info" data-aos="fade-left">
            <h2 class="nom-produit">
                <?= $article['titre_livre'] ?>
            </h2>
            <div class="prix">
                <?php
                $prix = 0;
                if ($article['reduction_livre'] > 0) {
                    $prix = $article['prix'] * (1 - $article['reduction_livre'] / 100);
                    echo $prix . " <s style='font-size:0.8rem;'>$article[prix]</s>";
                } else {
                    echo $article['prix'];
                }
                ?>
                €
            </div>
            <div class="resume">
                <?= $article['resume_livre'] ?>
            </div>

            <form action="./produit.php?nom_produit=<?= $article['titre_livre'] ?>&k=<?= $article['id_livre'] ?>"
                method="post" class="form-part">
                <div class="quantite">
                    <input type="submit" name="less" value="-" id="less">
                    <input type="number" name="qte" value="1" id="qte-value">
                    <input type="submit" name="more" value="+" id="more">
                </div>

                <div class="ajouter-panier">
                    <button name="add" id="add">Ajouter au panier</button>
                    <button name="buy" id="buy" type="button"><a href="../errors/404.php">Acheter
                            directement</a></button>
                </div>
            </form>
        </div>
    </div>


    <!--DESCRIPTION PRODUIT-->
    <table class="description-produit" data-aos="zoom-in">
        <thead>
            <tr>
                <th colspan="2">DESCRIPTION</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td class="td-titre">Titre du livre</td>
                <td class="td-content"><?= $article['titre_livre'] ?></td>
            </tr>

            <tr>
                <td class="td-titre">resume</td>
                <td class="td-content">
                    <?= $article['resume_livre'] ?>
                </td>
            </tr>

            <tr>
                <td class="td-titre">auteur</td>
                <td class="td-content"><?= $article['auteur_livre'] ?></td>
            </tr>

            <tr>
                <td class="td-titre">categorie</td>
                <td class="td-content"><?= $article['nom_categorie'] ?></td>
            </tr>

            <tr>
                <td class="td-titre">langue</td>
                <td class="td-content"><?= $article['langue'] ?></td>
            </tr>

            <tr>
                <td class="td-titre">public légal</td>
                <td class="td-content"><?= $article['public_legal'] ?></td>
            </tr>

            <tr>
                <td class="td-titre">reduction</td>
                <td class="td-content"><?= $article['reduction_livre'] ?>%</td>
            </tr>

        </tbody>

        <tfoot>
            <tr></tr>
        </tfoot>
    </table>


    <!--PRODUIT EN LIEN-->
    <div class="produits-similaires">
        <h2 data-aos="flip-left">PRODUIT EN LIEN</h2>
        <div class="similaires-container">
            <?php
            if ($res2 && $res2->num_rows):
                while ($produit_similaire = $res2->fetch_assoc()):

                    ?>
                    <div class="similaire" data-aos="zoom-in">
                        <div class="similaire-img">
                            <img loading="lazy" src="../assets/images<?= $produit_similaire['image_livre'] ?>" alt="">
                        </div>
                        <div class="similaire-nom">
                            <a
                                href="./produit.php?nom_produit=<?= $produit_similaire['titre_livre'] ?>&k=<?= $produit_similaire['id_livre'] ?>"><?= $produit_similaire['titre_livre'] ?></a>
                        </div>
                        <div class="similaire-prix">

                            <?php
                            $prix = 0;
                            if ($produit_similaire['reduction_livre'] > 0) {
                                $prix = $produit_similaire['prix'] * (1 - $produit_similaire['reduction_livre'] / 100);
                                echo $prix . " <s style='font-size:0.8rem;'>$produit_similaire[prix]</s>" . ' €';
                            } else {
                                echo $produit_similaire['prix'] . ' €';
                            }
                            ?>
                        </div>
                        <form action="" method="post">
                            <input type="hidden" name="produit_id" value="<?= $produit_similaire['id_livre'] ?>">
                            <button>Ajouter au panier</button>
                        </form>
                    </div>

                    <?php
                endwhile;
            endif;

            ?>
        </div>

        <a href="./boutique.php?categorie=<?= $categorie ?>&search=" id="tout-voir">Voir tout</a>
    </div>


    <!--AVIS PRODUIT-->
    <h2 class="review">Avis sur ce produit</h2>
    <section class="avis-produit" id="feedback">
        <div class="view-avis" data-aos="fade-right">

            <h3><?= $total_avis ?> avis</h3>
            <div class="avis-container">
                <!--AVIS 1-->
                <?php

                while ($avis = $res4->fetch_assoc()):
                    ?>
                    <div class="avis">
                        <div class="img">
                            <img loading="lazy" src="../assets/images/ico/user/user.png" alt="">
                        </div>
                        <div class="content">
                            <div class="user-note" data-note="<?= $avis['note'] ?>"></div>
                            <div class="user-info">
                                <strong><?= $avis['pseudo_client'] ?></strong>
                                <span>- <?= $avis['date_avis'] ?></span>
                            </div>
                            <div class="text">
                                <?= $avis['commentaire'] ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>

            </div>
        </div>

        <div class="add-avis" data-aos="fade-left">
            <h3>Ajouter un avis</h3>
            <div>Les champs marqués d'un <span class="important"> * </span> sont requis</div>
            <form action="" method="post" class="avis-form">
                <div class="rating">
                    <p>Ta note <span class="important">*</span></p>
                    <div class="note">
                        <input type="radio" name="note" id="star5" value="5">
                        <label for="star5">★</label>

                        <input type="radio" name="note" id="star4" value="4">
                        <label for="star4">★</label>

                        <input type="radio" name="note" id="star3" value="3">
                        <label for="star3">★</label>

                        <input type="radio" name="note" id="star2" value="2">
                        <label for="star2">★</label>

                        <input type="radio" name="note" id="star1" value="1" checked required>
                        <label for="star1">★</label>
                    </div>

                </div>

                <div class="ton-avis">
                    <p>Ton avis<span class="important">*</span></p>
                    <textarea placeholder="Minimum 6 caractères" name="avis" id="avis"></textarea>
                </div>


                <div class="consentement">
                    <input type="checkbox" id="accord" name="accord">
                    <label for="accord">Enregistrer mes informations</label>
                </div>
                <div class="submit">
                    <button type="submit">
                        Envoyer
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!--FOOTER-->
    <?php
    include_once("../includes/footer.php");
    ?>

</body>

</html>