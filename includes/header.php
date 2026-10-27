<?php
if (isset($_SESSION["panier"])) {
    $_SESSION["nombre_total_produit"] = count($_SESSION["panier"]);
} else {
    $_SESSION["nombre_total_produit"] = 0;
}

$search = (isset($_GET["search"])) ? trim($_GET["search"], '%') : '';
?>

<?php
include_once __DIR__ . '/constants.php';
?>


<header>
    <div class="top">
        <div class="menu">
            <span class="hamburger"></span>
            <span class="hamburger"></span>
            <span class="hamburger"></span>
        </div>

        <div class="logo">
            <a href="<?= BASE_URL ?>/index.php"><strong>Book<span class="room">room</span></strong></a>
        </div>

        <ul class="list-link">
            <li class="li-list"><a href="<?= BASE_URL ?>/index.php">Accueil</a></li>
            <li class="li-list"><a href="<?= BASE_URL ?>/pages/boutique.php?search">Boutique</a></li>
            <li class="li-list"><a href="<?= BASE_URL ?>/index.php?#best-sellers">Best Sellers</a></li>
            <li class="li-list"><a href="<?= BASE_URL ?>/index.php?#nouveautes">Nouveautés</a></li>
            <li class="li-list"><a href="<?= BASE_URL ?>/index.php?#promotion">Promotions</a></li>
            <li class="li-list"><a href="<?= BASE_URL ?>/index.php?#categorie">Catégories</a></li>
        </ul>

        <div class="search">
            <form method="get" action="<?= BASE_URL ?>/pages/boutique.php" id="search-form" class="search-form">
                <div class="search-input">
                    <input autofocus type="search" value="<?=trim($search) ?>" placeholder="Recherchez un livre ..."
                        id="search-product" name="search">
                </div>

                <div class="search-submit">
                    <button type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                            fill="#000000">
                            <path
                                d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <ul class="list-users">
            <?php
            if (!isset($_SESSION["user_id"])) {
                echo " <li class='users' title='user' >
                    <svg xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 -960 960 960' width='24px'
                        fill='#000000'>
                        <path
                            d='M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm146.5-204.5Q340-521 340-580t40.5-99.5Q421-720 480-720t99.5 40.5Q620-639 620-580t-40.5 99.5Q539-440 480-440t-99.5-40.5ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm100-95.5q47-15.5 86-44.5-39-29-86-44.5T480-280q-53 0-100 15.5T294-220q39 29 86 44.5T480-160q53 0 100-15.5ZM523-537q17-17 17-43t-17-43q-17-17-43-17t-43 17q-17 17-17 43t17 43q17 17 43 17t43-17Zm-43-43Zm0 360Z' />
                    </svg>

                    
                         <div class='subscribe'>
                            <h4>Mon compte</h4>
                            <hr>
                            <div class='connect'>
                                <a href='" . BASE_URL . "/pages/login.php'><button>Se connecter</button></a>
                            </div>
                            <div class='firstvisit'>
                                Première visite ? <a href='" . BASE_URL . "/pages/register.php'>S'inscire</a>
                            </div>
                        </div>
                    </li>";
            } else {
                echo "<li class='users' title='paramètre' >
                                <a href='" . BASE_URL . "/pages/parametre.php'>
                                <svg xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 -960 960 960' width='24px' 
                                    fill='#000000'>
                                    <path 
                                        d='m370-80-16-128q-13-5-24.5-12T307-235l-119 50L78-375l103-78q-1-7-1-13.5v-27q0-6.5 1-13.5L78-585l110-190 119 50q11-8 23-15t24-12l16-128h220l16 128q13 5 24.5 12t22.5 15l119-50 110 190-103 78q1 7 1 13.5v27q0 6.5-2 13.5l103 78-110 190-118-50q-11 8-23 15t-24 12L590-80H370Zm70-80h79l14-106q31-8 57.5-23.5T639-327l99 41 39-68-86-65q5-14 7-29.5t2-31.5q0-16-2-31.5t-7-29.5l86-65-39-68-99 42q-22-23-48.5-38.5T533-694l-13-106h-79l-14 106q-31 8-57.5 23.5T321-633l-99-41-39 68 86 64q-5 15-7 30t-2 32q0 16 2 31t7 30l-86 65 39 68 99-42q22 23 48.5 38.5T427-266l13 106Zm42-180q58 0 99-41t41-99q0-58-41-99t-99-41q-59 0-99.5 41T342-480q0 58 40.5 99t99.5 41Zm-2-140Z'/>
                                </svg>
                                </a>
                        </li>
                        ";
            }
            ?>


            <li class="cart" title="Cart">
                <a href="<?= BASE_URL ?>/pages/panier.php">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                        fill="#e3e3e3">
                        <path
                            d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z" />
                    </svg>
                </a>

                <?php
                if ($_SESSION["nombre_total_produit"]) {
                    echo "<span class='nombre-produit'>$_SESSION[nombre_total_produit]</span>";
                }
                ?>

            </li>

            <li class="theme" title="theme" id="theme-select" >
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                    fill="#e3e3e3">
                    <path
                        d="M280-160q-100 0-170-70T40-400q0-100 70-170t170-70q100 0 170 70t70 170q0 100-70 170t-170 70Zm0-80q66 0 113-47t47-113q0-66-47-113t-113-47q-66 0-113 47t-47 113q0 66 47 113t113 47Zm440-200v-80h80v80h-80Zm-80-240-56-56 56-56 56 56-56 56Zm200 240v-80h80v80h-80Zm-200 240-56-56 56-56 56 56-56 56ZM640-120l-56-56 56-56 56 56-56 56ZM160-640l-56-56 56-56 56 56-56 56Z" />
                </svg>
            </li>



            <li class="search-raccourci">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                    fill="#e3e3e3">
                    <path
                        d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z" />
                </svg>
            </li>
        </ul>
    </div>
</header>