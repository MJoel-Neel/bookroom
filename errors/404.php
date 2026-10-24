<?php 
if (session_status() === PHP_SESSION_NONE) {
        session_start();
    } 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon32.png">
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/fonts/font.css">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon16.png">
    <link rel="stylesheet" href="../assets/css/404.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js" defer></script>
    <script src="../assets/js/dataAOS.js" defer></script>
    <title>404 error</title>

    <?php include_once("../includes/header_head.php"); ?>
    <?php include_once("../includes/footer_head.php"); ?>
</head>

<body>
    <!--HEADER-->
    <?php
        include_once("../includes/header.php");
    ?>

   <main>
     <div class="error">
        <div class="mes-404" >404 <span>!</span></div>


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