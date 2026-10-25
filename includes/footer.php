<?php
include_once __DIR__ . '/constants.php';
$user_footer_link = (isset($_SESSION["user_id"])) ? ''. BASE_URL. '/pages/parametre.php' : ''. BASE_URL. '/pages/login.php';
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
            <li class="foot-link"><i class="fa-solid fa-house"></i><a href="<?= BASE_URL ?>/index.php"> Accueil </a></li>
            <li class="foot-link"><i class="fa-solid fa-shop"></i><a href="<?= BASE_URL ?>/pages/boutique.php?search">
                    Boutique </a></li>
            <li class="foot-link"><i class="fa-solid fa-ranking-star"></i><a href="<?= BASE_URL ?>/index.php?#best-sellers">
                    Best Sellers </a></li>
            <li class="foot-link"><i class="fa-solid fa-tag"></i><a href="<?= BASE_URL ?>/index.php?#nouveautes"> Nouveautés
                </a></li>
            <li class="foot-link"><i class="fa-solid fa-bullhorn"></i><a href="<?= BASE_URL ?>/index.php?#promotion">
                    Promotions </a></li>
            <li class="foot-link"><i class="fa-solid fa-table-list"></i><a href="<?= BASE_URL ?>/index.php?#categorie">
                    Catégories </a></li>
            <li class="foot-link"><i class="fa-solid fa-user"></i><a href="<?= $user_footer_link ?>"> Compte </a></li>
            <li class="foot-link"><i class="fa-solid fa-cart-shopping"></i><a href="<?= BASE_URL ?>/pages/panier.php"> Panier
                </a></li>
            <li class="foot-link"><i class="fa-regular fa-circle-question"></i><a href="#"> FAQ </a></li>
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

        <div class="social">
            <i class="fa-brands fa-facebook-f" style="color: #1877F2;"></i>
            <i class="fa-brands fa-tiktok" style="color: #000000;"></i>
            <i class="fa-brands fa-whatsapp" style="color: #25D366;"></i>
            <i class="fa-brands fa-instagram" style="background: linear-gradient(45deg, #F58529, #DD2A7B, #8134AF, #515BD4); 
          -webkit-background-clip: text; 
          -webkit-text-fill-color: transparent;">
            </i>
            <i class="fa-brands fa-snapchat" style="color: #9d9b05;"></i>
            <i class="fa-brands fa-paypal" style="color: #003087;"></i>
        </div>
    </div>
</footer>