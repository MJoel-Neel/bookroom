<!--
<?php
session_start();
include_once("../config.php");
var_dump($_SESSION);
var_dump($_GET);
var_dump($_POST);

if (isset($_SESSION["user_id"])) {
    if (!empty($_SESSION["panier"])) {
        $panier = $_SESSION["panier"];
        foreach ($panier as $key => $value) {

        }

    } else {
        header("location:./boutique.php");
        exit();
    }


} else {
    header("location: ./login.php");
    exit();
}
?>
-->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/fonts/font.css">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon16.png">
    <link rel="stylesheet" href="../assets/css/payer.css">
    <title>Paiement sécurisé – Finaliser votre commande | Bookroom</title>
</head>

<body>

</body>
</html>