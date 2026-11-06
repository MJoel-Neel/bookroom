<?php
session_start();
require_once "../config.php"; // connexion mysqli
require_once('../errors/flash.php');
require_once '../includes/cart.php';


$errors = []; // erreurs de validation affichées dans le formulaire

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    try {
        $email = trim($_POST["mail"]);
        $password = $_POST["mdp"];

        // Validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "Email ou Mot de passe incomplet";
        }

        if (empty($password)) {
            $errors["mdp"] = "Mot de passe requis.";
        }

        if (empty($errors)) {

            $stmt = $conn->prepare("SELECT id_client, nom_prenom_client, mot_de_passe, role_client, email_client FROM client WHERE email_client = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();

            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            $stmt->close();

            if ($user) {

                if (password_verify($password, $user["mot_de_passe"])) {
                    // Création session
                    $_SESSION["user_id"] = $user["id_client"];
                    $_SESSION["user_name"] = $user["nom_prenom_client"];
                    $_SESSION["role_client"] = $user["role_client"];
                    $_SESSION["user_mail"] = $user["email_client"];

                    session_regenerate_id(true);
                    // Fusion session → BDD
                    cart_merge_on_login((int) $user["id_client"]);  // Fusion panier invité → panier BDD

                    flash_success("Connexion réussie 🎉");
                    header("Location: ../index.php");
                    exit();

                } else {
                    $errors["mdp"] = "Mot de passe incorrect.";
                }

            } else {
                $errors["email"] = "Aucun compte associé à cet email.";
            }
        }

    } catch (mysqli_sql_exception $e) {
        error_log("login.php DB error: " . $e->getMessage()); // trace réelle côté serveur
        flash_warning("Erreur de connexion, veuillez réessayer");
    }

}

require_once __DIR__ . '/../includes/constants.php';
$seo = [
    'title' => 'Connexion à votre compte | Bookroom',
    'description' => 'Connectez-vous à votre compte Bookroom pour suivre vos commandes, gérer votre panier et accéder à vos favoris.',
    'robots' => 'noindex,follow',
];

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require __DIR__ . '/../includes/head.php'; ?>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/login.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/flash.css">
    <script src="<?= BASE_URL ?>/assets/js/login.js" defer></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js" defer></script>
    <script src="<?= BASE_URL ?>/assets/js/dataAOS.js" defer></script>
</head>

<body>
    <?php
    include_once("../includes/header.php");
    ?>
    <?php flash_render(); ?>

    <div class="login" data-aos="zoom-in">
        <div class="head">
            <h1>Se connecter à Bookroom </h1>
        </div>

        <div class="form-container">
            <form action="" class="login-form" method="post">
                <div class="input-container">
                    <div class="label">
                        <label for="email">Votre mail <span class="important">*</span></label>
                    </div>

                    <input class="enter" type="email" id="email" name="mail" placeholder="Votre mail *">
                    <i class="ico fa-solid fa-at"></i>
                    <span class="field-error"><?= htmlspecialchars($errors["email"]) ?></span>
                </div>

                <div class="input-container">
                    <div class="label">
                        <label for="mdp">Mot de Passe <span class="important">*</span> (>=10) </label>
                    </div>

                    <input class="enter" type="password" id="mdp" name="mdp" placeholder="Votre Mot de passe *">
                    <i class="ico fa-solid fa-key"></i>
                    <span class="field-error"><?= htmlspecialchars($errors["mdp"]) ?></span>
                </div>


                <div class="valider">
                    <input type="submit" value="Se connecter">
                </div>

                <div class="log">
                    Vous n'avez pas de compte ? <a href="./register.php">Inscrivez-vous</a>
                </div>
            </form>
        </div>
    </div>

    <?php
    include_once("../includes/footer.php");
    ?>
</body>

</html>