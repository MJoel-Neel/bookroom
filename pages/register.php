<?php
// Démarre ou récupère la session en cours
session_start();

// Importe le fichier de configuration et de connexion à la base de données (qui fournit l'objet $conn)
require_once "../config.php"; // connexion mysqli
require_once('../errors/flash.php');

// Initialisation d'un tableau vide pour stocker les futurs messages d'erreur
$errors = [];


// Vérifie si le formulaire a bien été envoyé via la méthode POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    try {
        // Récupération et nettoyage des données reçues du formulaire
        // suppression des espaces inutiles
        $genre = trim($_POST["civilite"]);
        $name = trim($_POST["pseudo"]);
        $email = trim($_POST["mail"]);
        $password = $_POST["mdp"];
        $confirmPassword = $_POST["conf"];

        // --- VALIDATIONS ---
        if (empty($name) || strlen($name) < 3) {
            $errors["pseudo"] = "Le nom est requis. Au moins 3 caractères";
        }

        // Vérifie si le format de l'e-mail est valide
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "Email invalide.";
        }

        if (strlen($password) < 10) {
            $errors["mdp"] = "Le mot de passe doit contenir au moins 10 caractères.";
        }

        if ($password !== $confirmPassword) {
            $errors["conf"] = "Les mots de passe ne correspondent pas.";
        }


        // --- VÉRIFICATION DOUBLON EN BDD ---
        // Prépare une requête pour vérifier si l'email existe déjà dans la table CLIENT
        $stmt = $conn->prepare("SELECT id_client FROM client WHERE email_client = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows() > 0) {
            $errors["email"] = "Cet email est déjà utilisé. Connectez-vous plutôt!";
        }

        $stmt->close();

        // --- INSERTION SI AUCUNE ERREUR ---
        if (empty($errors)) {
            // Hashage sécurisé du mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Prépare l'insertion du nouveau client dans la base de données
            $stmt2 = $conn->prepare("INSERT INTO client (nom_prenom_client, email_client, mot_de_passe, genre_client, role_client) VALUES (?, ?, ?, ?, ?)");
            $default_user_role = "user";
            $stmt2->bind_param("sssss", $name, $email, $hashedPassword, $genre, $default_user_role);
            $stmt2->execute();
            $stmt2->close();

            flash_success("Inscription réussie 🎉");
            header("Location: ./login.php");
            exit();
        }


    } catch (mysqli_sql_exception $e) {
        // Trace réelle côté serveur
        error_log("register.php DB error: " . $e->getMessage());
        $errors[] = "Erreur d'enregistrement, Veuillez bien vérifer vos informations";
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
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/register.css">
    <link rel="stylesheet" href="../assets/css/flash.css">
    <link rel="stylesheet" href="../assets/fonts/font.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="../assets/js/register.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js" defer></script>
    <script src="../assets/js/dataAOS.js" defer></script>
    <title>Inscription à votre compte | Bookroom</title>
</head>

<body>
    <?php flash_render(); ?>

    <div class="register" data-aos="zoom-in">
        <div class="head">
            <h1>Créer un compte Bookroom</h1>
        </div>

        <div class="form-container">
            <form action="" class="register-form" method="post">
                <div>
                    <label>Civilité <span class="important">*</span> : </label>
                    <label for="mr">M.</label>
                    <input type="radio" id="mr" name="civilite" value="M" checked>
                    <label for="mme">Mme</label>
                    <input type="radio" id="mme" name="civilite" value="Mme">
                </div>

                <div class="input-container">
                    <div class="label">
                        <label for="pseudo">Votre Nom et prénom <span class="important">*</span></label>
                    </div>

                    <input class="enter" type="text" placeholder="Votre nom et prénom *" id="pseudo" name="pseudo">
                    <i class="ico fa-regular fa-user"></i>
                    <span class="field-error"><?= htmlspecialchars($errors["pseudo"]) ?></span>
                </div>

                <div class="input-container">
                    <div class="label">
                        <label for="mail">Votre mail <span class="important">*</span></label>
                    </div>

                    <input class="enter" type="email" id="email" name="mail" placeholder="Votre mail *">
                    <i class="ico fa-solid fa-at"></i>
                    <span class="field-error"><?= htmlspecialchars($errors["email"]) ?></span>
                </div>


                <div class="input-container">
                    <div class="label">
                        <label for="mdp">Mot de Passe (Au moins 10 caractères)<span class="important">*</span></label>
                    </div>

                    <input class="enter" type="password" id="mdp" name="mdp" placeholder="Mot de passe *">
                    <span class="field-error"><?= htmlspecialchars($errors["mdp"]) ?></span>
                </div>

                <div class="input-container">
                    <div class="label">
                        <label for="conf">Confirmez le mot de passe <span class="important">*</span></label>
                    </div>

                    <input class="enter" type="password" id="conf" name="conf"
                        placeholder="Confirmez le mot de passe *">
                    <i class="ico fa-solid fa-check" id="conf-i"></i>
                    <span class="field-error"><?= htmlspecialchars($errors["conf"]) ?></span>
                </div>

                <div class="valider">
                    <div>
                        <input type="submit" value="S'inscrire">
                    </div>

                    <div>
                        <input type="reset" value="Effacer">
                    </div>
                </div>

                <div class="log">
                    Vous avez un compte ? <a href="./login.php">Connectez-vous</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>