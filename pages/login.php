<?php
session_start();
require_once "../config.php"; // connexion mysqli
require_once('../errors/flash.php');
require_once '../includes/cart.php';




$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    try {
        $email = trim($_POST["mail"]);
        $password = trim($_POST["mdp"]);

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
                    cart_merge_on_login((int) $user["id_client"]);

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
        flash_warning("Erreur de connexion, veuillez réessayer");
    }

}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon16.png">
    <link rel="stylesheet" href="../assets/css/login.css">
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/fonts/font.css">
    <link rel="stylesheet" href="../assets/css/flash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="../assets/js/login.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js" defer></script>
    <script src="../assets/js/dataAOS.js" defer></script>
    <title>Connexion à votre compte | Bookroom</title>
</head>

<body>
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
</body>

</html>