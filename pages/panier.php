<?php
session_start();
include_once("../config.php");
require_once('../errors/flash.php');
require_once('../includes/cart.php');

// Initialise le panier s'il n'existe pas encore
if (!isset($_SESSION["panier"])) {
    $_SESSION["panier"] = [];
}

$total = 0;

/*trouver la quantité d'un produit, en paramètre l'id à chercher et la table dans laquelle chercher */
function qteOf($produit_id, array $panier)
{
    $qte = 0;
    foreach ($panier as $key => $value) {
        if ($produit_id == $key) {
            $qte = $value;
        }
    }
    return $qte;
}

//PARTIE LESS => réduire la quantité
if (isset($_POST["less"])) {

    $id = intval($_POST["less"]);

    // Garde-fou : le produit doit exister dans le panier
    if ($_SESSION["panier"][$id] > 1 && $_SESSION["panier"][$id] > 1) {
        $is_success = cart_add($id, -1); // -1 : décrémente

        if ($is_success) {
            flash_info("Panier mis à jour ⏲️");
        }
    }

    header("Location: ./panier.php");
    exit();
}

//PARTIE MORE => augmenter la quantité
if (isset($_POST["more"])) {
    $id = intval($_POST["more"]);

    //GESTION DE L'AJOUT DE PRODUIT AU PANIER
    $is_success = cart_add($id, 1);

    if ($is_success) {
        flash_info("Panier mis à jour ⏲️");
    }
    header("Location: ./panier.php");
    exit();
}

//PARTIE SUPPRESSION => delete
if (isset($_POST["delete"])) {

    $id = intval($_POST["delete"]);
    cart_remove($id);
    flash_warning("Produit supprimer 😥");

    header("Location: ./panier.php");
    exit();
}

/*PARTIE REQUETE SQL : récupérer les livres du panier*/
$res = null;
if (!empty($_SESSION["panier"])) {
    $list_articles = $_SESSION["panier"];
    // Construit dynamiquement "?,?,?" pour chaque id du panier
    $ids = array_keys($_SESSION["panier"]);  // [5, 12, 8, ...]
    $placeholders = implode(',', array_fill(0, count($ids), '?')); // "?,?,?..."
    // Types : tout en 'i' (les clés de panier sont des entiers)
    $types = str_repeat('i', count($ids));


    try {
        $sql = "SELECT l.id_livre, l.titre_livre, l.prix, l.image_livre,
                       l.reduction_livre, c.nom_categorie
                FROM livre l
                INNER JOIN categorie c ON c.id_categorie = l.id_categorie
                WHERE l.id_livre IN ($placeholders)";

        $query = $conn->prepare($sql);
        $query->bind_param($types, ...$ids);     // spread : nécessite des variables/array (OK)
        $query->execute();
        $res = $query->get_result();
        $query->close();
    } catch (mysqli_sql_exception $e) {
        error_log("panier.php SELECT: " . $e->getMessage()); // trace côté serveur
        $res = null; // la vue affichera un panier vide/erreur
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon32.png">
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/flash.css">
    <link rel="stylesheet" href="../assets/fonts/font.css">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon16.png">
    <title>Panier – Acheter des livres en ligne | Bookroom</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../assets/css/panier.css">
    <script src="../assets/js/panier.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js" defer></script>
    <script src="../assets/js/dataAOS.js" defer></script>
    <script src="../assets/js/head.js" defer></script>
    <link rel="stylesheet" href="../assets/css/header.css">

    <?php include_once("../includes/header_head.php"); ?>
    <?php include_once("../includes/footer_head.php"); ?>
</head>

<body>
    <!--HEADER-->
    <?php
    include_once("../includes/header.php");
    ?>
    <?php flash_render(); ?>

    <section class="panier">
        <h2 id="cart" data-aos="fade-right">Ton Panier</h2>

        <div class="all" data-aos="zoom-in">
            <div class="articles">
                <!--Article1-->
                <?php
                if ($res && $res->num_rows):
                    while ($produit = $res->fetch_assoc()):
                        $qte = qteOf($produit["id_livre"], $list_articles);
                        $prix = $produit["prix"] * $qte;
                        if ($produit["reduction_livre"] > 0) {
                            $prix = $prix * (1 - $produit["reduction_livre"] / 100);
                        }
                        if ($prix > 0) {
                            $total += $prix;
                        } else {
                            $prix *= -1;
                            $total += $prix;
                        }

                        ?>

                        <form class="article article1" data-aos="fade-right" method="post">
                            <button class="suppr" name="delete" value="<?= $produit["id_livre"] ?>">
                                <i class="fa-solid fa-delete-left"></i>
                            </button>

                            <div class="article-img">
                                <img loading="lazy" src="../assets/images<?= str_contains($produit["image_livre"], 'tmp/') ?
                                "/produits/anonymous_book.webp" : $produit["image_livre"] ?>" alt="">
                            </div>

                            <div class="article-info">
                                <h3 class="nom-produit"><a
                                        href="./produit.php?nom_produit=<?= $produit["titre_livre"] ?>&k=<?= $produit["id_livre"] ?>"><?= $produit["titre_livre"] ?></a>
                                </h3>
                                <h4><span class="cat">Catégorie :</span> <?= $produit["nom_categorie"] ?></h4>

                                <div class="calcul">
                                    <div class="quantite">
                                        <button class="less" name="less" value="<?= $produit["id_livre"] ?>">-</button>
                                        <button class="qte"><?= $qte ?></button>
                                        <button class="more" name="more" value="<?= $produit["id_livre"] ?>">+</button>
                                    </div>

                                    <p class="somme"><span class="sum"><?= $prix ?></span>€</p>
                                </div>
                            </div>
                        </form>

                    <?php endwhile; endif; ?>

                <?php
                if (!isset($res) || empty($_SESSION["panier"])) {
                    echo "
                            <div class='panier-vide'>
                                <div class='message'>
                                    <p>Votre panier est vide</p>
                                    <a class='view-boutique' href='../index.php?#categorie'>Trouver l'inspiration</a>
                                </div>
                                <div class='bg-color'></div>
                            </div>
                        ";
                }
                ?>

            </div>


            <!--TOTAL-->
            <div class="total" data-aos="fade-left">
                <table>
                    <tr>
                        <th id="recap" colspan="2">Récapitulatif</th>
                    </tr>

                    <tr>
                        <td class="td-title">Sous-total : </td>
                        <td class="td-value"><?php echo $total ?> €</td>
                    </tr>
                    <tr>
                        <td class="td-title">livraison : </td>
                        <td class="td-value">0.0 €</td>
                    </tr>

                    <tr>
                        <td class="td-title">total achat : </td>
                        <td class="td-value"><?php echo $total ?>€</td>
                        <?php $_SESSION['total'] = $total; ?>
                    </tr>
                </table>

                <div class="checkout">
                    <div class="payer">
                        <a href="../errors/404.php"><button>Payer</button></a>
                    </div>

                    <div class="continuer">
                        <a href="./boutique.php?search"><button>continuer votre achat</button></a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!--FOOTER-->
    <?php
    include_once("../includes/footer.php");
    ?>

</body>

</html>