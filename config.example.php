<?php
// config.php
$db_host = 'localhost';
$db_user = 'bd_username';
$db_pass = 'db_password';
$db_name = 'db_name';
$db_port = 'port'; //eg: $db_port = 3306;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);
$mysqli->set_charset("utf8mb4");

//DONNER LES DROITS D'ECRITURE A PHP SUR LE DOSSIER ./assets/images/produits
//SINON DANS DASHBOARD, PAS POSSIBLE D'UTILISER mkdir() et move_file_uploaded()
?>