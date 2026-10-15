<?php
session_start();
include_once("../config.php");
include_once("../includes/cart.php");
require_once("../errors/flash.php");


//FONCTION EXPLOSER $_GET['AUTEUR']
function exploser_auteur($auteur)
{
    $req = '';
    $k = 0;
    $params = [];
    $req .= " AND (";
    foreach ($auteur as $key => $value) {
        if ($k == 0) {
            $req .= " l.auteur_livre LIKE ? ";
        } else {
            $req .= " OR l.auteur_livre LIKE ? ";
        }
        $params[] = $value;
        $k++;
    }
    $req .= ")";

    $r = [
        'param' => $params,
        'requete' => $req
    ];
    return $r;
}

/* Ajout produit + gestion quantité */
if (isset($_POST["produit_id"])) {

    $id = (int) $_POST["produit_id"];

    $is_success = cart_add($id, 1);

    if ($is_success) {
        flash_info("Panier mis à jour ⏲️");
    }

    // évite la duplication lors du refresh
    header("Location: ./boutique.php?search");
    exit();
}

//PREPARATION SQL
$search = $_GET['search'] ?? '*';
$sql = "SELECT * FROM livre l
    INNER JOIN categorie c
    ON l.id_categorie = c.id_categorie WHERE 1=1";

if (!empty($_GET)) {
    $params = [];
    $types = '';
    //SI RECHERCHE
    if (!empty($_GET['search'])) {
        $sql .= " AND l.titre_livre LIKE ? ";
        $params[] = '%' . $_GET['search'] . '%';
    }
    //prix min
    if (isset($_GET['min'])) {
        $min = (int) $_GET['min'];
        $sql .= ' AND l.prix >= ?';
        $params[] = $min;
    }
    //prix max
    if (isset($_GET['max'])) {
        $max = (int) $_GET['max'];
        $sql .= ' AND l.prix <= ?';
        $params[] = $max;
    }
    //auteur
    if (isset($_GET['auteur'])) {
        $auteur = exploser_auteur($_GET['auteur']);
        $sql .= $auteur['requete'];
        foreach ($auteur['param'] as $key => $value) {
            $params[] = $value;
        }
    }
    //langue
    if (isset($_GET['langue'])) {
        $langue = $_GET['langue'];
        $sql .= ' AND l.langue LIKE ?';
        $params[] = $langue;
    }
    //categorie
    if (isset($_GET['categorie'])) {
        $categorie = $_GET['categorie'];
        $sql .= ' AND c.nom_categorie LIKE ?';
        $params[] = $categorie;
    }

    //tri
    $tri_autorise = [
        'pertinence' => ' ORDER BY l.id_livre',
        'moins' => ' ORDER BY l.prix ASC',
        'plus' => ' ORDER BY l.prix DESC',
        'vente' => '',
        'avis' => ''
    ];
    if (isset($_GET['trier'])) {
        $tri = $tri_autorise[$_GET['trier']];
        $sql .= $tri;
    }
} else {
    header("location: ../index.php");
    exit;
}

/*REQUETE FINAL*/
$query1 = $conn->prepare($sql);
if (!empty($params)) {
    $types = '';
    foreach ($params as $param) {
        $types .= is_int($param) ? 'i' : 's';
    }
    $query1->bind_param($types, ...$params);
}
$query1->execute();
$res1 = $query1->get_result();


/*FILTRE AUTEURS*/
$query2 = $conn->prepare("SELECT auteur_livre FROM `livre` GROUP BY auteur_livre;");
$query2->execute();
$res2 = $query2->get_result();
$auteurs = $res2->fetch_all();
$query2->close();

/*FILTRE LANGUES*/
$query3 = $conn->prepare("SELECT langue FROM `livre` GROUP BY langue;");
$query3->execute();
$res3 = $query3->get_result();
$langues = $res3->fetch_all();
$query3->close();
/*FILTRE CATEGORIES*/
$query4 = $conn->prepare("SELECT nom_categorie FROM categorie;");
$query4->execute();
$res4 = $query4->get_result();
$categories = $res4->fetch_all();
$query4->close();

/*FILTRE PROMO*/
/*INITIALISATION DE PANIER*/
if (!isset($_SESSION["panier"])) {
    $_SESSION["panier"] = [];
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon16.png">
    <link rel="stylesheet" href="../assets/css/boutique.css">
    <link rel="stylesheet" href="../assets/fonts/font.css">
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/flash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js" defer></script>
    <script src="../assets/js/dataAOS.js" defer></script>
    <title>Boutique de livres en ligne – education, fiction, Manga | Bookroom</title>
    <script src="../assets/js/boutique.js" defer></script>

    <?php include_once("../includes/header_head.php"); ?>
    <?php include_once("../includes/footer_head.php"); ?>

</head>

<body>
    <?php include_once("../includes/header.php"); ?>
    <?php flash_render(); ?>

    <section class="produit-rechercher">
        <div class="recherche-utilisateur" data-aos="fade-down">
            <p class="nom">
                <span class="glasses">👓</span>
                <span class="nombre"> Résultat pour : </span>
                <?= trim($search, '%') ?? '' ?>
            </p>
        </div>

        <div class="ensemble" data-aos="zoom-in">
            <i class="menu fa-solid fa-bars"></i>
            <form action="./boutique.php" method="get" class="filtres" data-aos="fade-right">
                <div class="trier-div">
                    <select name="trier" id="trier">
                        <option value="pertinence" <?= (isset($_GET['trier']) && ($_GET['trier'] == 'pertinence')) ? 'selected' : '' ?>>
                            Pertinence
                        </option>
                        <option value="moins" <?= (isset($_GET['trier']) && ($_GET['trier'] == 'moins')) ? 'selected' : '' ?>>
                            Moins chers
                        </option>
                        <option value="plus" <?= (isset($_GET['trier']) && ($_GET['trier'] == 'plus')) ? 'selected' : '' ?>>
                            Plus cher
                        </option>
                        <option value="vente" <?= (isset($_GET['trier']) && ($_GET['trier'] == 'vente')) ? 'selected' : '' ?>>
                            Meilleures Ventes
                        </option>
                        <option value="avis" <?= (isset($_GET['trier']) && ($_GET['trier'] == 'avis')) ? 'selected' : '' ?>>
                            Avis
                        </option>
                    </select>
                </div>


                <!--prix-->
                <div class="filtre more">
                    <h2 class="filtre-name">Prix</h2>
                    <div>
                        <input type="number" id="min" name="min" placeholder="min"
                            value="<?= (isset($_GET['min']) ? $_GET['min'] : '0') ?>">
                        <input type="number" id="max" name="max" placeholder="max"
                            value="<?= (isset($_GET['max']) ? $_GET['max'] : '1000') ?>">
                    </div>
                </div>

                <!--auteurs-->
                <div class="filtre more">
                    <h2 class="filtre-name">Auteur</h2>
                    <div>

                        <?php foreach ($auteurs as $auteur): ?>
                            <p>
                                <input type="checkbox" name="auteur[]" value="<?= $auteur[0] ?>" id="<?= $auteur[0] ?>"
                                    <?= isset($_GET["auteur"]) && in_array($auteur[0], $_GET["auteur"]) ? 'checked' : '' ?>>
                                <label for="<?= $auteur[0] ?>"><?= $auteur[0] ?></label>
                            </p>
                        <?php endforeach; ?>
                    </div>
                </div>


                <!--langues-->
                <div class="filtre more">
                    <h2 class="filtre-name">Langue</h2>
                    <div>
                        <?php foreach ($langues as $langue): ?>
                            <p>
                                <input type='radio' name='langue' value='<?= $langue[0] ?>' id='<?= $langue[0] ?>'
                                    <?= (isset($_GET["langue"]) && ($_GET["langue"] == $langue[0])) ? "checked" : "" ?>>
                                <label for="<?= $langue[0] ?>"><?= $langue[0] ?></label>
                            </p>
                        <?php endforeach; ?>

                    </div>
                </div>

                <!--categorie-->
                <div class="filtre more">
                    <h2 class="filtre-name">Categorie</h2>
                    <div>
                        <?php foreach ($categories as $categorie): ?>
                            <p>
                                <input type='radio' name='categorie' value='<?= $categorie[0] ?>' id='<?= $categorie[0] ?>'
                                    <?= (isset($_GET["categorie"]) && $_GET["categorie"] == $categorie[0]) ? "checked" : "" ?>>
                                <label for="<?= $categorie[0] ?>"><?= $categorie[0] ?></label>
                            </p>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- search caché -->
                <input type="hidden" name="search" value="<?= trim($search, '%') ?>">

            </form>

            <div class="articles">
                <!--Article 1-->
                <?php while ($produit = $res1->fetch_assoc()): ?>

                    <div class="article not-show" data-aos="zoom-in">
                        <?php
                        $prix = 0;
                        if ($produit['reduction_livre'] > 0) {
                            echo "<span class='promo'>-$produit[reduction_livre]%</span>";
                            $prix = $produit['prix'] * (1 - $produit['reduction_livre'] / 100);
                        }

                        //AFFICHER TOTAL AVIS
                        $id_livre = (int) $produit['id_livre'];
                        $combien_d_avis = $conn->prepare("SELECT * FROM avis WHERE id_livre_concerner = ?;");
                        $combien_d_avis->execute([$id_livre]);
                        $feedback = $combien_d_avis->get_result();
                        $total_avis = $feedback->num_rows;
                        $combien_d_avis->close();
                        ?>
                        <div class="article-img">
                            <img loading="lazy" src="../assets/images<?= $produit['image_livre']; ?>" alt="">
                        </div>

                        <div class="libelle-article">
                            <a
                                href="./produit.php?nom_produit=<?= $produit['titre_livre']; ?>&k=<?= $produit['id_livre']; ?>">
                                <?= $produit['titre_livre']; ?>
                            </a>
                            <p class="categorie"><?= $produit['nom_categorie']; ?></p>
                            <div class="resume">
                                <?= $produit['resume_livre']; ?>

                            </div>
                            <div class="avis">
                                <p><span>★</span> <a
                                        href="./produit.php?nom_produit=<?= $produit['titre_livre'] ?>&k=<?= $produit['id_livre'] ?>#feedback">
                                        <?= $total_avis ?>
                                        Avis</a></p>
                                <em class="auteur"><a href=""><?= $produit['auteur_livre']; ?></a></em>
                            </div>
                        </div>

                        <div class="price">
                            <span class="prix">
                                <?php
                                if ($prix > 0) {
                                    echo " $prix  <s style='color: #333; font-size: 18px;'>$produit[prix]€</s>";
                                } else {
                                    echo "$produit[prix]€";
                                }
                                ?>
                            </span>

                            <form action="./boutique.php" method="post">
                                <input type="hidden" name="produit_id" value="<?= $produit['id_livre'] ?>">
                                <button class="ajouter" type="submit">Ajouter au panier</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

        </div>

        <div class="pagination">
        </div>

    </section>


    <!--FOOTER-->
    <?php
    include_once("../includes/footer.php");
    ?>
</body>

</html>