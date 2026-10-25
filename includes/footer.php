<?php
include_once __DIR__ . '/constants.php';
$user_footer_link = (isset($_SESSION["user_id"])) ? '' . BASE_URL . '/pages/parametre.php' : '' . BASE_URL . '/pages/login.php';
?>

<!--FOOTER-->
<footer>
    <div class="page-foot">
        <div class="copyright">
            <h2>Book<span>room</span></h2>
            <img loading="lazy" src="<?= BASE_URL ?>/assets/images/ico/background/section/footer.webp" alt="">
            <div class="adresse">
                <address><i class="fa-solid fa-address-card"></i> 228 rte de la Bazoque</address>
                <address><i class="fa-solid fa-mobile"></i> <a href="tel:+330633573557">06 33 57 35 57</a></address>
                <address><i class="fa-solid fa-envelope-open"></i> <a
                        href="mailto:allohanjoel1@gmail">allohanjoel1@gmail</a></address>
            </div>
        </div>


        <ul class="foot-ul">
            <li class="foot-links-title">🛒 PAGES</li>
            <hr class="foot-hr">
            <li class="foot-link"><i class="fa-solid fa-house"></i><a href="<?= BASE_URL ?>/index.php"> Accueil </a>
            </li>
            <li class="foot-link"><i class="fa-solid fa-shop"></i><a href="<?= BASE_URL ?>/pages/boutique.php?search">
                    Boutique </a></li>
            <li class="foot-link"><i class="fa-solid fa-ranking-star"></i><a
                    href="<?= BASE_URL ?>/index.php?#best-sellers">
                    Best Sellers </a></li>
            <li class="foot-link"><i class="fa-solid fa-tag"></i><a href="<?= BASE_URL ?>/index.php?#nouveautes">
                    Nouveautés
                </a></li>
            <li class="foot-link"><i class="fa-solid fa-bullhorn"></i><a href="<?= BASE_URL ?>/index.php?#promotion">
                    Promotions </a></li>
            <li class="foot-link"><i class="fa-solid fa-table-list"></i><a href="<?= BASE_URL ?>/index.php?#categorie">
                    Catégories </a></li>
            <li class="foot-link"><i class="fa-solid fa-user"></i><a href="<?= $user_footer_link ?>"> Compte </a></li>
            <li class="foot-link"><i class="fa-solid fa-cart-shopping"></i><a href="<?= BASE_URL ?>/pages/panier.php">
                    Panier
                </a></li>
        </ul>

        <form class="foot-form" method="post" action="<?= BASE_URL ?>/errors/404.php">

            <h3>Me contacter</h3>

            <div><input type="text" name="user_contact_name" placeholder="Entrez votre Pseudo" required
                    id="user_contact_name"></div>
            <div><input type="email" name="user_contact_email" placeholder="Entrez votre Email" required
                    id="user_contact_email"></div>
            <div><textarea name="user_contact_message" id="user_contact_message"
                    placeholder="Quel est le contenu de votre message ?" required></textarea></div>
            <div><button class="send-mes">Envoyer</button></div>
        </form>
    </div>

    <div class="copy">
        <div class="droits">
            Tout droits réservés. &copy; Copyright <strong>BOOK</strong>room Neel Mj
        </div>

        <div class="cgu-ml">
            <p class="cgu-ml-link">
                <i class="fa-solid fa-address-book"></i>
                <a href="<?= BASE_URL ?>/pages/cgu.php">
                    CGU
                </a>
            </p>
            <p class="cgu-ml-link">
                <i class="fa-solid fa-gavel"></i>
                <a href="<?= BASE_URL ?>/pages/mentions-legales.php">
                    Mentions légales
                </a>
            </p>
        </div>
    </div>
</footer>