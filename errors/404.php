<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/constants.php';
$seo = [
    'title' => 'Page d\'erreur 404 | Bookroom',
    'description' => 'La page demandée est introuvable. Retournez à l\'accueil ou parcourez la boutique de livres Bookroom.',
    'robots' => 'noindex,nofollow',
];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require __DIR__ . '/../includes/head.php'; ?>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/404.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js" defer></script>
    <script src="<?= BASE_URL ?>/assets/js/dataAOS.js" defer></script>
</head>

<body>
    <!--HEADER-->
    <?php
    include_once("../includes/header.php");
    ?>

    <main>
        <div class="error">
            <div class="img-container">
                <img src="../assets/images/ico/background/section/404-error-1.svg" loading="lazy" alt="404">
            </div>
            <div class="error-message">
                <p class="principal-mes">
                    Panier perdu dans l’entrepôt 📦
                </p>

                <p class="futher-mes">
                    Pendant que nous le recherchons… pourquoi ne pas explorer nos produits ?
                </p>

            </div>

            <div class="return">
                <a href="../pages/boutique.php?search" class="retour">
                    👉 Découvrir la boutique
                </a>
            </div>
        </div>
    </main>


    <!--FOOTER-->
    <?php
    include_once("../includes/footer.php");
    ?>
</body>

</html>