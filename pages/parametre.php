<?php
session_start();
include_once("../config.php"); // $conn mysqli, strict → exceptions
require_once "../errors/flash.php";
require_once "../includes/cart.php";

// Si non connecté → accueil
if (!isset($_SESSION["user_id"])) {
    header("location: ../index.php");
    exit;
}

$user_id = (int) $_SESSION["user_id"];
/* CHARGEMENT DU CLIENT */
$user = null;
try {
    $query1 = $conn->prepare("SELECT * FROM client WHERE id_client = ? LIMIT 1");
    $query1->bind_param("i", $user_id);
    $query1->execute();
    $user = $query1->get_result();
    $user = $user->fetch_assoc();
    $query1->close();
} catch (mysqli_sql_exception $e) {
    error_log("parametre.php load client: " . $e->getMessage());
}
if (!$user) {
    header("location: ../index.php");
    exit;
}

//DECONNEXION
if (isset($_GET['disconnect'])) {
    if (isset($_GET['disconnect'])) {
        unset($_SESSION["user_id"]);
        unset($_SESSION["user_name"]);
        unset($_SESSION["role_client"]);
        unset($_SESSION["user_mail"]);

        flash_info("Nous expérons vous revoir bientôt 😊 !");
        header("location: ../index.php");
        exit;
    }
}

//MODIFICATION
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = (int) $_SESSION["user_id"];
    /* PROFIL (pseudo + email + tel) */
    if (!empty($_POST['nouveau_pseudo']) && !empty($_POST['nouveau_mail']) && !empty($_POST['Nouveau_tel'])) {
        try {
            $pseudo = trim($_POST["nouveau_pseudo"]);
            $email = trim($_POST["nouveau_mail"]);
            $tel = trim($_POST["Nouveau_tel"]);

            // Vérifie si l'email est déjà utilisé PAR UN AUTRE client
            $stmt = $conn->prepare("SELECT id_client FROM client WHERE email_client = ?
            AND id_client <> ? ");
            $stmt->bind_param('si', $email, $user_id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows === 0) {
                $stmt->close();
                $query2 = $conn->prepare("UPDATE client
                SET nom_prenom_client = ?, email_client = ?, tel_client = ?
                WHERE id_client = ?");
                $query2->bind_param('sssi', $pseudo, $email, $tel, $user_id);
                $query2->execute();
                $query2->close();

                flash_success("Informations clients mises à jour");
            } else {
                $stmt->close();
                flash_error("Ce mail existe déjà");
            }
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (mysqli_sql_exception $e) {
            error_log("parametre.php update profil: " . $e->getMessage());
            flash_error("Erreur lors de la mise à jour du profil.");
        }
    }


    /* --- MOT DE PASSE --- */
    if (!empty($_POST['ancien_mdp']) && !empty($_POST['Nouveau_mdp'])) {
        try {
            $old_mdp = $_POST['ancien_mdp'];
            $new_mdp = $_POST['Nouveau_mdp'];

            if (password_verify($old_mdp, $user["mot_de_passe"])) { // $user déjà chargé
                $hashedPassword = password_hash($new_mdp, PASSWORD_DEFAULT);
                $query3 = $conn->prepare("UPDATE client SET mot_de_passe = ? WHERE id_client = ?");
                $query3->bind_param("si", $hashedPassword, $user_id);
                $query3->execute();
                $query3->close();

                unset($_SESSION["user_id"]);
                unset($_SESSION["user_name"]);
                unset($_SESSION["role_client"]);
                unset($_SESSION["user_mail"]);
                session_regenerate_id(true);

                flash_success("Mot de passe mis à jour !");
                header("location: ./login.php");
                exit();
            } else {
                flash_error("Ancien mot de passe incorrect.");
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            }
        } catch (mysqli_sql_exception $e) {
            error_log("parametre.php update password: " . $e->getMessage());
            flash_error("Erreur lors du changement de mot de passe.");
        }
    }

    //ADRESSE
    if (!empty($_POST['adresse']) && !empty($_POST['ville']) && !empty($_POST['postal'])) {
        try {
            $full_address = trim($_POST['adresse']) . ' ' . trim($_POST['ville']) . ' ' . trim($_POST['postal']);
            $query4 = $conn->prepare("UPDATE client
                SET adresse_client = ?
                WHERE id_client = ?");
            $query4->bind_param('si', $full_address, $user_id);
            $query4->execute();
            $query4->close();

            flash_success("Adresse mise à jour !");
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } catch (mysqli_sql_exception $e) {
            error_log("parametre.php update adresse: " . $e->getMessage());
            flash_error("Erreur lors de la mise à jour de l'adresse.");
        }
    }


}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon16.png">
    <link rel="stylesheet" href="../assets/css/base.css">
    <title>Paramètres de profil et de compte | Bookroom</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <script src="../assets/js/parametre.js" defer></script>
    <link rel="stylesheet" href="../assets/css/parametre.css">
    <link rel="stylesheet" href="../assets/css/flash.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once("../includes/header_head.php"); ?>
    <?php include_once("../includes/footer_head.php"); ?>

</head>

<body>
    <?php
    require_once("../includes/header.php");
    ?>
    <?php flash_render(); ?>

    <div class="param-container">
        <!-- Sidebar -->
        <svg xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 -960 960 960" width="50px" fill="#000000"
            id="menubars">
            <path d="M120-240v-66.67h720V-240H120Zm0-206.67v-66.66h720v66.66H120Zm0-206.66V-720h720v66.67H120Z" />
        </svg>

        <div class="sidebar" data-aos="fade-right">
            <h2>Paramètres</h2>
            <button class="param-sidebar-button" onclick="showSection(event,'profile')">
                <i class="fa-solid fa-user"></i> Profil</button>
            <button class="param-sidebar-button" onclick="showSection(event,'security')">
                <i class="fa-solid fa-shield-halved"></i> Sécurité</button>
            <button class="param-sidebar-button" onclick="showSection(event,'address')">
                <i class="fa-solid fa-location-dot"></i> Adresses</button>

            <button class="param-sidebar-button" onclick="showSection(event,'privacy')">
                <i class="fa-solid fa-lock"></i> Confidentialité </button>
            <?php
            $is_admin = isset($_SESSION["role_client"]) && $_SESSION["role_client"] === 'admin';
            echo $is_admin ? "<button><a href='../admin/dashboard.php' class='administrer'>
            <i class='fa-solid fa-user-tie'></i> Administrer</a></button>" : "";

            ?>

            <button><a href="./parametre.php?disconnect" class="deconnexion">
                    <i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a></button>
        </div>

        <!-- Content -->
        <div class="content">

            <!-- Profil -->
            <div id="profile" class="section active">
                <div class="profile prohibit">
                    <h2>Informations personnelles</h2>
                    <?php if ($user): ?>
                        <p>Pseudo : <strong><?= $user['nom_prenom_client'] ?></strong></p><br>
                        <p>Email : <strong><?= $user['email_client'] ?></strong></p><br>
                        <p>Téléphone : <strong><?= $user['tel_client'] ?></strong></p><br>
                        <p id="addresse"><strong>Addresse: </strong> <em><?= $user['adresse_client'] ?></em></p><br>
                        <p id="pass">Mdp : <strong>*******************</strong></p><br>
                    <?php endif; ?>
                    <button id="modif">Modifiez</button>
                </div>

                <form class="profile allowed none" method="post">
                    <h2>Informations personnelles</h2>
                    <strong style="color: red">Tu dois changer pseudo && email && tel</strong> <br><br>
                    <input type="text" placeholder="Nom et prénom" name="nouveau_pseudo" id="pseudo">
                    <input type="email" placeholder="Email" name="nouveau_mail" id="email">
                    <input type="text" placeholder="eg:06 12 52 32 45" name="Nouveau_tel" id="tel">
                    <button id="save-profil">Enregistrer</button>
                    <button id="annul">Retour</button>
                </form>
            </div>

            <!-- Sécurité -->
            <form id="security" class="section" method="post">
                <h2>Changer mot de passe</h2>
                <input type="password" placeholder="Ancien mot de passe" id="ancien_mdp" name="ancien_mdp">
                <input type="password" placeholder="Nouveau mot de passe" id="nouveau_mdp" name="Nouveau_mdp">
                <button class="save" id="save_mdp">Modifier</button>
            </form>

            <!-- Adresses -->
            <form id="address" class="section" method="post">
                <h2>Adresse de livraison</h2>
                <input type="text" placeholder="Adresse" name="adresse" id="adresse">
                <input type="text" placeholder="Ville" name="ville" id="ville">
                <input type="text" placeholder="Code postal" name="postal" id="postal">
                <button class="save" id="save_adresse">Ajouter</button>
            </form>

            <!-- Commandes -->
            <!--
            <div id="orders" class="section">
                <h2>Historique des commandes</h2>
                <p>Commande #1234 - Livrée</p>
                <p>Commande #5678 - En cours</p>
            </div>
            -->

            <!-- Confidentialité -->
            <form id="privacy" class="section" method="post">
                <h2>Confidentialité</h2>
                <p><em>NB: </em> Pas de retour possible ‼️❗</p><br>
                <button class="danger" name="delete_account" disabled>Supprimer mon compte</button>
            </form>

        </div>

    </div>


    <?php
    require_once("../includes/footer.php");
    ?>
</body>

</html>