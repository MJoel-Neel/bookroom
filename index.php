<?php
session_start();
include_once("./config.php");
require_once("./errors/flash.php");
require_once("./includes/cart.php");

/* Initialisation du panier */
if (!isset($_SESSION["panier"])) {
    $_SESSION["panier"] = []; // tableau vide par défaut
}

/* Ajout produit + gestion quantité */
if (isset($_POST["produit_id"])) {

    $id = (int) $_POST["produit_id"]; // pour éviter les valeurs non numériques
    $current_section = (!empty($_POST["current_section"])) ? $_POST["current_section"] : 'nouveautes' ;

    $ancre_autorises = ["promotion", "nouveautes", "best-sellers"];
    $target_section = in_array($current_section, $ancre_autorises) ? $current_section : "nouveautes";


    $is_success = cart_add($id, 1);
    if ($is_success) {
        flash_info("Panier mis à jour ⏲️"); // message flash pour l'utilisateur
    }

    // évite la duplication lors du refresh (POST -> GET)
    header("Location: " . $_SERVER["PHP_SELF"] . "#" . urlencode($target_section));
    exit();
}

/* compteur total panier */
$_SESSION["nombre_total_produit"] = count($_SESSION["panier"]); // nombre total d'articles


/* best sellers */
$bests = null; // null = requête échouée ;
//  la vue teste `if ($bests)`
try {
    $query1 = $conn->prepare("SELECT * FROM livre LIMIT 8");
    $query1->execute();
    $bests = $query1->get_result();
    $query1->close();
} catch (mysqli_sql_exception $e) {
    error_log("index.php best sellers: " . $e->getMessage());
}

/* nouveautés */
$news = null;
try {
    $query2 = $conn->prepare("SELECT * FROM livre ORDER BY id_livre DESC limit 8");
    $query2->execute();
    $news = $query2->get_result();
    $query2->close();
} catch (mysqli_sql_exception $e) {
    error_log("index.php nouveautés: " . $e->getMessage());
}

/* promotions */
$promos = null;
try {
    $query3 = $conn->prepare("SELECT * FROM livre where reduction_livre>0 limit 8");
    $query3->execute();
    $promos = $query3->get_result();
    $query3->close();
} catch (mysqli_sql_exception $e) {
    error_log("index.php promotions: " . $e->getMessage());
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon16.png">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/base.css">
    <link rel="stylesheet" href="./assets/css/flash.css">
    <link rel="stylesheet" href="./assets/fonts/font.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="./assets/js/script.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js" defer></script>
    <script src="./assets/js/dataAOS.js" defer></script>
    <title>Librairie en ligne – Acheter des livres pas chers | Bookroom</title>

    <?php include_once("./includes/header_head.php"); ?>
    <?php include_once("./includes/footer_head.php"); ?>

</head>


<body>
    <!--HEADER-->
    <?php
    include_once("./includes/header.php");
    ?>
    <?php flash_render(); ?>

    <div class="main">
        <!--SECTION HERO-->
        <section class="heros">
            <div class="presentation">
                <h2 class="section-title">Bienvenue dans notre librairie</h2>
                <p>Découvrez les meilleurs livres du moment</p>
                <a href="./pages/boutique.php?search=">Voir Notre Boutique</a>
            </div>

            <div class="hero-container">
                <div class="hero hero1">
                    <img loading="lazy" src="./assets/images/ico/background/section/heros/hero1.jpg" alt="">
                </div>
                <div class="hero hero2">
                    <img loading="lazy" src="./assets/images/ico/background/section/heros/hero2.png" alt="">
                </div>
                <div class="hero hero3">
                    <img loading="lazy" src="./assets/images/ico/background/section/heros/hero3.png" alt="">
                </div>
                <div class="hero hero4">
                    <img loading="lazy" src="./assets/images/ico/background/section/heros/hero4.jpg" alt="">
                </div>
            </div>
        </section>

        <!--BLOC PUB-->
        <div class="pub" data-aos="fade-up">
            <div class="pub-bloc">
                <i class="fa-solid fa-box-open"></i>
                <div>
                    <strong>Reduction</strong>
                    <p>Des réductions chaque semaine</p>
                </div>
            </div>

            <div class="pub-bloc">
                <i class="fa-solid fa-truck-moving"></i>
                <div>
                    <strong>Livraison gratuite</strong>
                    <p>100% gratuit pour toutes les commandes</p>
                </div>
            </div>

            <div class="pub-bloc">
                <i class="fa-solid fa-face-smile"></i>
                <div>
                    <strong>Excellent soutien</strong>
                    <p>nous nous soucions de votre expérience</p>
                </div>
            </div>

            <div class="pub-bloc">
                <i class="fa-solid fa-shield"></i>
                <div>
                    <strong>paiement sécurisé</strong>
                    <p>méthode de paiement 100% sécurisée</p>
                </div>
            </div>
        </div>

        <!--Nos meilleures ventes-->
        <section class="best-sellers container" id="best-sellers">
            <h2 class="section-title" data-aos="fade-right">meilleures Ventes</h2>

            <div class="best-sellers-box box">
                <!--Article -->
                <?php if ($bests): ?>
                    <?php while ($best = $bests->fetch_assoc()):
                        $prix = $best["prix"];
                        if ($best["reduction_livre"] > 0) {
                            $prix = $prix * (1 - $best["reduction_livre"] / 100);
                        }

                        //AFFICHER TOTAL AVIS
                        $id_livre = (int) $best['id_livre'];
                        $combien_d_avis = $conn->prepare("SELECT * FROM avis WHERE id_livre_concerner = ?;");
                        $combien_d_avis->execute([$id_livre]);
                        $feedback = $combien_d_avis->get_result();
                        $total_avis = $feedback->num_rows;
                        $combien_d_avis->close();
                        ?>

                        <div class="best-seller" data-aos="zoom-in">
                            <a href="./pages/produit.php?nom_produit=<?= $best['titre_livre'] ?>&k=<?= $best['id_livre'] ?>"
                                class="product-link-img">
                                <img loading="lazy" src="./assets/images<?= str_contains($best["image_livre"], 'tmp/') ?
                                "/produits/anonymous_book.webp" : $best["image_livre"] ?>"
                                    alt=""> </a>
                            <a href="./pages/produit.php?nom_produit=<?= $best['titre_livre'] ?>&k=<?= $best['id_livre'] ?>"
                                class="book-name"><?= $best["titre_livre"] ?></a>

                            <div class="avis">
                                <p><span>★</span> <a
                                        href="./pages/produit.php?nom_produit=<?= $best['titre_livre'] ?>&k=<?= $best['id_livre'] ?>#feedback">
                                        <?= $total_avis ?> Avis</a></p>
                                <em class="auteur"><a href=""><?= $best["auteur_livre"] ?></a></em>
                            </div>
                            <div class="prix-box">
                                <span class="prix"><?php
                                if ($best["prix"] == $prix) {
                                    echo $prix . " $";
                                } else {
                                    echo "$prix$ <s style='color:#555; font-size:1rem;'>$best[prix]</s>";
                                }
                                ?></span>
                                <form action="" method="post">
                                    <input type="hidden" name="produit_id" value="<?= $best["id_livre"] ?>">
                                    <input type="hidden" name="current_section" value="best-sellers">
                                    <button class="ajouter" type="submit">Ajouter au panier</button>
                                </form>
                            </div>
                            <span class="best">Best</span>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </section>


        <!--SECTION OFFRES-->

        <section class="pub offres">
            <h2>Nos Offres</h2>
            <div class="offres-container">
                <div class="uni-box" data-aos="fade-left">
                    <img loading="lazy" src="./assets/images/ico/background/section/offres/offre1.png" alt="">
                </div>

                <div class="minis-box">
                    <article class="mini" data-aos="zoom-in">
                        <img loading="lazy" src="./assets/images/ico/background/section/offres/offre2.png" alt="">
                    </article>
                    <article class="mini" data-aos="fade-left">
                        <img loading="lazy" src="./assets/images/ico/background/section/offres/offre3.png" alt="">
                    </article>
                </div>
            </div>
        </section>

        <!--BLOC SPECIAL-->
        <div class="special" data-aos="fade-up">
            <div class="special-bloc">
                <i class="fa-solid fa-box-open"></i>
                <div>
                    <strong>Reduction</strong>
                    <p>Des réductions chaque semaine</p>
                </div>
            </div>

            <div class="special-bloc">
                <i class="fa-solid fa-truck-moving"></i>
                <div>
                    <strong>Livraison gratuite</strong>
                    <p>100% gratuit pour toutes les commandes</p>
                </div>
            </div>

            <div class="special-bloc">
                <i class="fa-solid fa-face-smile"></i>
                <div>
                    <strong>Excellent soutien</strong>
                    <p>nous nous soucions de votre expérience</p>
                </div>
            </div>

            <div class="special-bloc">
                <i class="fa-solid fa-shield"></i>
                <div>
                    <strong>paiement sécurisé</strong>
                    <p>méthode de paiement 100% sécurisée</p>
                </div>
            </div>
        </div>



        <!--Nouveau sur Bookroom-->
        <section class="nouveautes container" id="nouveautes">
            <h2 class="section-title">Nouveautés</h2>

            <div class="nouveautes-box box">
                <!--Article -->
                <?php if ($news): ?>
                    <?php while ($new = $news->fetch_assoc()):
                        $prix = $new["prix"];
                        if ($new["reduction_livre"] > 0) {
                            $prix = $prix * (1 - $new["reduction_livre"] / 100);
                        }

                        //AFFICHER TOTAL AVIS
                        $id_livre = (int) $new['id_livre'];
                        $combien_d_avis = $conn->prepare("SELECT * FROM avis WHERE id_livre_concerner = ?;");
                        $combien_d_avis->execute([$id_livre]);
                        $feedback = $combien_d_avis->get_result();
                        $total_avis = $feedback->num_rows;
                        $combien_d_avis->close();
                        ?>
                        <div class="nouveaute" data-aos="zoom-in">
                            <a href="./pages/produit.php?nom_produit=<?= $new['titre_livre'] ?>&k=<?= $new['id_livre'] ?>"
                                class="product-link-img"><img loading="lazy" src="./assets/images<?= str_contains($new["image_livre"], 'tmp/') ?
                                "/produits/anonymous_book.webp" : $new["image_livre"] ?>"
                                    alt="">
                            </a>
                            <a href="./pages/produit.php?nom_produit=<?= $new['titre_livre'] ?>&k=<?= $new['id_livre'] ?>"
                                class="book-name"><?= $new["titre_livre"] ?></a>

                            <div class="avis">
                                <p><span>★</span> <a
                                        href="./pages/produit.php?nom_produit=<?= $new['titre_livre'] ?>&k=<?= $new['id_livre'] ?>#feedback">
                                        <?= $total_avis ?> Avis</a></p>
                                <em class="auteur"><a href=""><?= $new["auteur_livre"] ?></a></em>
                            </div>
                            <div class="prix-box">
                                <span class="prix">
                                    <?php
                                    if ($new["prix"] == $prix) {
                                        echo $prix . " $";
                                    } else {
                                        echo "$prix$ <s style='color:#555; font-size:1rem;'>$new[prix]</s>";
                                    }
                                    ?>
                                </span>
                                <form action="" method="post">
                                    <input type="hidden" name="produit_id" value="<?= $new["id_livre"] ?>">
                                    <input type="hidden" name="current_section" value="nouveautes">
                                    <button class="ajouter" type="submit">Ajouter au panier</button>
                                </form>
                            </div>
                            <span class="new">New</span>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </section>


        <!--SECTION à propos de nous-->
        <section class="pub propos">
            <h2>à propos de nous</h2>
            <div class="propos-container" data-aos="zoom-down">
                <div class="co-founder" data-aos="fade-right">
                    <p class="history">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Ratione quos molestias nostrum, impedit
                        officia distinctio tempore blanditiis asperiores nam inventore laborum labore atque sint
                        assumenda
                        est illo, nobis quibusdam? Quis.
                    </p>

                    <div class="profil">
                        <img loading="lazy" src="./assets/images/ico/founder/founder1.webp" alt="">
                        <strong>Neel MJ</strong>
                        <em>Co-fondateur</em>
                    </div>
                    <span class="quote">’’</span>
                </div>

                <div class="co-founder" data-aos="fade-left">
                    <p class="history">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Ratione quos molestias nostrum, impedit
                        officia distinctio tempore blanditiis asperiores nam inventore laborum labore atque sint
                        assumenda
                        est illo, nobis quibusdam? Quis.
                    </p>

                    <div class="profil">
                        <img loading="lazy" src="./assets/images/ico/founder/founder2.jpg" alt="">
                        <strong>John DOE</strong>
                        <em>Co-fondateur</em>
                    </div>
                    <span class="quote">’’</span>
                </div>
            </div>
        </section>

        <!--promotions-->
        <section class="promotion container" id="promotion">
            <h2 class="section-title" data-aos="fade-right">Promotions</h2>

            <div class="promotions-box box">
                <?php if ($promos): ?>
                    <?php while ($promo = $promos->fetch_assoc()):
                        $prix = $promo["prix"];
                        if ($promo["reduction_livre"] > 0) {
                            $prix = $prix * (1 - $promo["reduction_livre"] / 100);
                        }

                        //AFFICHER TOTAL AVIS
                        $id_livre = (int) $promo['id_livre'];
                        $combien_d_avis = $conn->prepare("SELECT * FROM avis WHERE id_livre_concerner = ?;");
                        $combien_d_avis->execute([$id_livre]);
                        $feedback = $combien_d_avis->get_result();
                        $total_avis = $feedback->num_rows;
                        $combien_d_avis->close();
                        ?>
                        <div class="promotion" data-aos="zoom-in">
                            <a href="./pages/produit.php?nom_produit=<?= $promo['titre_livre'] ?>&k=<?= $promo['id_livre'] ?>"
                                class="product-link-img"><img loading="lazy" src="./assets/images<?= str_contains($promo["image_livre"], 'tmp/') ?
                                "/produits/anonymous_book.webp" : $promo["image_livre"] ?>"
                                    alt=""> </a>
                            <a href="./pages/produit.php?nom_produit=<?= $promo['titre_livre'] ?>&k=<?= $promo['id_livre'] ?>"
                                class="book-name"><?= $promo["titre_livre"] ?></a>

                            <div class="avis">
                                <p><span>★</span> <a
                                        href="./pages/produit.php?nom_produit=<?= $promo['titre_livre'] ?>&k=<?= $promo['id_livre'] ?>#feedback">
                                        <?= $total_avis ?> Avis</a></p>
                                <em class="auteur"><a href=""><?= $promo["auteur_livre"] ?></a></em>
                            </div>
                            <div class="prix-box">
                                <span class="prix">
                                    <?php
                                    if ($promo["prix"] == $prix) {
                                        echo $prix . " $";
                                    } else {
                                        echo "$prix$ <s style='color:#555; font-size:1rem;'>$promo[prix]</s>";
                                    }
                                    ?>
                                </span>
                                <form action="" method="post">
                                    <input type="hidden" name="produit_id" value="<?= $promo["id_livre"] ?>">
                                    <input type="hidden" name="current_section" value="promotion">
                                    <button class="ajouter" type="submit">Ajouter au panier</button>
                                </form>
                            </div>
                            <span class="reduction">Promo</span>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </section>


        <!--SECTION à propos de nous-->
        <section class="pub pourquoi-nous" data-aos="fade-up">
            <h2 data-aos="fade-right">Pourquoi nous choisir</h2>
            <p class="raison" data-aos="fade-left">Lorem ipsum dolor sit amet consectetur adipisicing elit. Possimus
                voluptas expedita ad dolorem
                consequuntur deleniti, quam magnam iure pariatur omnis molestias, voluptate dignissimos unde? Molestiae
                tempora amet quis tempore vel.</p>

            <!--Nos qualités-->
            <div class="qualites" data-aos="fade-right">
                <!--qualité 1-->
                <div class="qualite">
                    <div><i class="fa-solid fa-money-check-dollar"></i></div>
                    <div class="parceque">transactions sécurisées</div>
                </div>
                <!--qualité 2-->
                <div class="qualite">
                    <div><i class="fa-solid fa-headset"></i></div>
                    <div class="parceque">24/7 Meilleur soutien</div>
                </div>
                <!--qualité 3-->
                <div class="qualite">
                    <div><i class="fa-solid fa-award"></i></div>
                    <div class="parceque">garantie de qualité</div>
                </div>
            </div>
        </section>

        <!--catégories-->
        <section class="categories" id="categorie">
            <h2 class="section-title" data-aos="fade-right">Catégories</h2>

            <div class="cat-flex">
                <a href="./pages/boutique.php?categorie=developpement++personnel" data-aos="zoom-in">
                    <i class="fa-solid fa-book-open" style="color: #FFC400;"></i>
                    <em class="cat-name">éducation</em>
                </a>

                <a href="./pages/boutique.php?categorie=fiction" data-aos="zoom-in">
                    <i class="fa-solid fa-robot" style="color: #2979FF;"></i>
                    <em class="cat-name">fiction</em>
                </a>

                <a href="./pages/boutique.php?categorie=jeunesse" data-aos="zoom-in">
                    <i class="fa-solid fa-child" style="color: #FF4081;"></i>
                    <em class="cat-name">jeunesse</em>
                </a>

                <a href="./pages/boutique.php?categorie=loisir" data-aos="zoom-in">
                    <i class="fa-brands fa-angellist" style="color: #00E676;"></i>
                    <em class="cat-name">loisir</em>
                </a>

                <a href="./pages/boutique.php?categorie=mangas" data-aos="zoom-in">
                    <i class="fa-brands fa-bilibili" style="color: #FF9100;"></i>
                    <em class="cat-name">mangas</em>
                </a>
            </div>
        </section>
    </div>

    <!--FOOTER-->
    <?php
    include_once("./includes/footer.php");
    ?>

</body>

</html>