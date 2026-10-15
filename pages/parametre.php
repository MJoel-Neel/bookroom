<?php
session_start();
include_once("../config.php");
require_once "../errors/flash.php";
require_once "../includes/cart.php";


if (isset($_SESSION["user_id"])) {
    $user_id = (int) $_SESSION["user_id"];
    $query1 = $conn->prepare("SELECT * FROM client WHERE id_client = ? LIMIT 1");
    $query1->execute([$user_id]);
    $user = $query1->get_result();
    $user = $user->fetch_assoc();
    $query1->close();

} else {
    header("location:  ../index.php");
}


//DECONNEXION
if (isset($_GET['disconnect'])) {
    if (isset($_GET['disconnect'])) {
        unset($_SESSION["user_id"]);
        flash_info("Nous expérons vous revoir bientôt 😊 !");
        header("location: ../index.php");
        exit;
    }
}

//MODIFICATION
if (isset($_POST)) {
    if (isset($_SESSION["user_id"])) {
        $user_id = (int) $_SESSION["user_id"];
        //PROFILE
        if (!empty($_POST['nouveau_pseudo']) && !empty($_POST['nouveau_mail']) && !empty($_POST['Nouveau_tel'])) {
            $pseudo = trim($_POST["nouveau_pseudo"]);
            $email = trim($_POST["nouveau_mail"]);
            $tel = trim($_POST["Nouveau_tel"]);

            // Vérifier si email existe déjà
            $stmt = $conn->prepare("SELECT id_client FROM client WHERE email_client = ?");
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result(); //REQUIS
            if ($stmt->num_rows == 0) {
                $query2 = $conn->prepare("UPDATE client
                SET nom_prenom_client = ?, email_client = ?, tel_client = ?
                WHERE id_client = ?");
                $query2->bind_param('sssi', $pseudo, $email, $tel, $user_id);
                $query2->execute();
                $query2->close();
                flash_success("Informations clients mises à jour");
            } else {
                flash_error("Ce mail existe déjà");
            }
            $stmt->close();
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }


        //SECURITY | PASSWORD 
        if (!empty($_POST['ancien_mdp']) && !empty($_POST['Nouveau_mdp'])) {
            $old_mdp = trim($_POST['ancien_mdp']);
            $new_mdp = trim($_POST['Nouveau_mdp']);

            //VERIFIER LE OLD MDP DANS LA BASE 
            $stmt1 = $conn->prepare("SELECT * FROM client WHERE id_client = ?");
            $stmt1->bind_param("i", $user_id);
            $stmt1->execute();
            $result = $stmt1->get_result();
            $user = $result->fetch_assoc();
            if (password_verify($old_mdp, $user["mot_de_passe"])) {
                $hashedPassword = password_hash($new_mdp, PASSWORD_DEFAULT);
                $query3 = $conn->prepare("UPDATE client SET mot_de_passe = ? WHERE id_client = ?");
                $query3->execute([$hashedPassword, $user_id]);

                flash_success("Mot de passe mis à jour !");
                header("location: ./login.php");
                exit();
            }
            $stmt1->close();
        }

        //ADRESSE
        if (!empty($_POST['adresse']) && !empty($_POST['ville']) && !empty($_POST['postal'])) {
            $full_address = htmlspecialchars($_POST['adresse']) . ' ' . htmlspecialchars($_POST['ville']) . ' ' . htmlspecialchars($_POST['postal']);
            $query3 = $conn->prepare("UPDATE client
                SET adresse_client = ?
                WHERE id_client = ?");
            $query3->bind_param('si', $full_address, $user_id);
            $query3->execute();
            $query3->close();
            flash_success("Adresse mise à jour !");
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

    } else {
        header("location: ../index.php");
        exit;
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