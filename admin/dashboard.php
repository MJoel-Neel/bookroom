<?php
// Démarrage de la session pour accéder aux variables $_SESSION
session_start();
// Inclusion du fichier de configuration et de flash 
include_once("../config.php");
require_once("../errors/flash.php");

// tableau pour stocker les erreurs de validation
$errors = [];

$admin = $_SESSION['user_mail'] ?? 'John Doe, How did you do it';

// Accès réservé aux admins
if (!isset($_SESSION["role_client"]) || $_SESSION["role_client"] !== 'admin') {
    header("location: ../pages/parametre.php");
    exit;
}

try {
    // Produits : on récupère tous les livres
    $query1 = $conn->prepare("SELECT * FROM livre ORDER BY id_livre DESC ");
    $query1->execute();
    $produits = $query1->get_result();
    $query1->close();

    // Catégories : on récupère toutes les catégories
    $query2 = $conn->prepare("SELECT * FROM categorie");
    $query2->execute();
    $categories = $query2->get_result();
    $query2->close();


    // Clients : on récupère tous les clients
    $query3 = $conn->prepare("SELECT * FROM client");
    $query3->execute();
    $clients = $query3->get_result();
    $query3->close();

    // On stocke le nombre de clients et de livres 
    // pour les statistiques
    $nb_client = $clients->num_rows;
    $nb_livre = $produits->num_rows;
} catch (mysqli_sql_exception $e) {
    error_log("dashboard.php load: " . $e->getMessage());
    flash_error("Erreur de chargement du tableau de bord.");

    // valeurs par défaut pour la vue
    $produits = $categories = $clients = null;
}


//SUPPRESSION CLIENT
if (isset($_POST['user_id'])) {
    try {
        $id_client = (int) $_POST['user_id'];
        $requete3 = $conn->prepare("DELETE FROM client WHERE id_client = ?");
        $requete3->bind_param('i', $id_client);
        $requete3->execute();
        $requete3->close();
        flash_warning("Un client de moins !");
    } catch (mysqli_sql_exception $e) {
        error_log("dashboard.php delete client: " . $e->getMessage());
        flash_error("Suppression client impossible.");
    }
    header("location: " . $_SERVER['PHP_SELF']);
    exit;
}

//SUPPRESSION CATEGORIE
if (isset($_POST['cat_id'])) {
    try {
        $id_cat = (int) $_POST['cat_id'];
        $del = $conn->prepare("DELETE FROM categorie WHERE id_categorie = ?");
        $del->bind_param('i', $id_cat);
        $del->execute();
        $del->close();
        flash_warning("Une categorie de moins !");
    } catch (mysqli_sql_exception $e) {
        error_log("dashboard.php delete categorie: " . $e->getMessage());
        flash_error("Suppression catégorie impossible.");
    }
    header("location: " . $_SERVER['PHP_SELF']);
    exit;
}

//SUPPRESSION PROUDITS
if (isset($_POST['produit_id'])) {
    try {
        $id_produit = (int) $_POST['produit_id'];
        $del = $conn->prepare("DELETE FROM livre WHERE id_livre = ?");
        $del->bind_param('i', $id_produit);
        $del->execute();
        $del->close();

        flash_warning("Un produit supprimé !");
    } catch (mysqli_sql_exception $e) {
        error_log("dashboard.php delete produit: " . $e->getMessage());
        flash_error("Suppression produit impossible.");
    }

    header("location: " . $_SERVER['PHP_SELF']);
    exit;
}

//CHANGER LE ROLE D'UN USER
if (!empty($_POST['adminize'])) {
    try {
        $user_id = (int) $_POST["adminize"];

        //OBTENIR LE ROLE ACTUEL DE L'USER
        $role_req = $conn->prepare("SELECT role_client FROM client WHERE id_client = ?;");
        $role_req->bind_param("i", $user_id);
        $role_req->execute();
        $role_result = $role_req->get_result()->fetch_assoc();
        $role_req->close();

        if ($role_result) {
            //PROCEDER A LA MISE A JOUR
            $role_cible = $role_result["role_client"] === 'user' ? 'admin' : 'user';
            $upd = $conn->prepare("UPDATE client SET role_client = ? WHERE id_client = ?");
            $upd->bind_param("si", $role_cible, $user_id);
            $upd->execute();
            $upd->close();
            flash_info("Rôle client modifié");
        }
    } catch (mysqli_sql_exception $e) {
        error_log("dashboard.php adminize: " . $e->getMessage());
        flash_error("Changement de rôle impossible.");
    }
    header("location: " . $_SERVER['PHP_SELF']);
    exit();
}

//AJOUTER CATEGORIE
if (!empty($_POST['add_nom_cat']) && !empty($_POST['add_pref'])) {
    $nom_cat = trim($_POST['add_nom_cat']);
    $pref = trim($_POST['add_pref']);

    if (strlen($pref) <= 10) {
        try {
            $add = $conn->prepare("INSERT INTO categorie(nom_categorie, prefixe_categorie) VALUES (?, ?)");
            $add->bind_param("ss", $nom_cat, $pref);
            $add->execute();
            $add->close();
            flash_success("Nouvelle categorie en vue !");
        } catch (mysqli_sql_exception $e) {
            error_log("dashboard.php add categorie: " . $e->getMessage());
            flash_error("Ajout de catégorie impossible.");
        }
        header("location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

//EDITER CATEGORIE
if (
    !empty($_POST['edit_nom_cat']) &&
    !empty($_POST['edit_pref']) &&
    !empty($_POST['id_category'])
) {
    $edit_nom_cat = trim($_POST['edit_nom_cat']);
    $edit_pref = trim($_POST['edit_pref']);
    $id_edit_cat = (int) $_POST['id_category'];

    if (strlen($edit_pref) <= 10) {
        try {
            $edit = $conn->prepare("UPDATE categorie SET nom_categorie = ?, prefixe_categorie = ?  WHERE id_categorie = ?;");
            $edit->bind_param("ssi", $edit_nom_cat, $edit_pref, $id_edit_cat);
            $edit->execute();
            $edit->close();
            flash_success("Categorie modifiée avec succès !");
        } catch (mysqli_sql_exception $e) {
            error_log("dashboard.php edit categorie: " . $e->getMessage());
            flash_error("Modification de catégorie impossible.");
        }

        header("location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// CATÉGORIES POUR LE MENU DÉROULANT D'AJOUT
$cat_livre = null;
try {
    $cq = $conn->prepare("SELECT * FROM categorie");
    $cq->execute();
    $cat_livre = $cq->get_result();
    $cq->close();
} catch (mysqli_sql_exception $e) {
    error_log("dashboard.php cat_livre: " . $e->getMessage());
}

//AJOUTER/MODIFIER UN PRODUIT 
if (
    isset(
    $_POST['titre_produit'],
    $_POST['resume_produit'],
    $_POST['auteur_produit'],
    $_POST['prix_produit'],
    $_POST['stock_produit'],
    $_POST['cat_produit'],
    $_FILES['image_produit'],
    $_POST['langue_produit'],
    $_POST['public_legal_produit'],
    $_POST['reduction_produit']
)
) {
    // Nettoyage et récupération des données du formulaire
    $id_livre = isset($_POST['id_livre']) ? (int) $_POST['id_livre'] : 0;
    $titre_produit = trim($_POST['titre_produit']);
    $resume_produit = trim($_POST['resume_produit']);
    $auteur_produit = trim($_POST['auteur_produit']);
    $prix_produit = (float) $_POST['prix_produit'];
    $stock_produit = (int) $_POST['stock_produit'];
    $cat_produit_id = (int) $_POST['cat_produit'];
    $image_produit = $_FILES['image_produit'];
    $langue_produit = strtoupper(trim($_POST['langue_produit']));
    $public_legal_produit = trim($_POST['public_legal_produit']);
    $reduction_produit = (int) ($_POST['reduction_produit'] ?? 0);

    // add (id_livre == 0) ou update (id_livre > 0)
    $quest_type = $id_livre === 0 ? 'add' : 'update';


    // Validation du prix (doit être un nombre > 0)
    if ($prix_produit <= 0) {
        $errors["prix_produit"] = 'Vérifier le prix ';
    }
    // Validation du stock (entier >= 0)
    if ($stock_produit < 0) {
        $errors["stock_produit"] = 'Le stock doit être un entier naturel ';
    }
    // Titre : au moins 3 caractères
    if (strlen($titre_produit) < 3) {
        $errors["titre_produit"] = 'Nom du produit (au moins 3 caractères)';
    }
    // Auteur : au moins 3 caractères
    if (strlen($auteur_produit) < 3) {
        $errors["auteur_produit"] = "Nom de l'auteur (au moins 3 caractères)";
    }
    // Résumé : au moins 20 caractères
    if (strlen($resume_produit) < 20) {
        $errors["resume_produit"] = "Resumé du produit (au moins 20 caractères)";
    }
    // Langue : 3 caractères maximum
    if (strlen($langue_produit) > 3) {
        $errors["langue_produit"] = "Langue du produit (3 caractères MAXIMUM eg: fr)";
    }
    // Réduction : entier >= 0
    if ($reduction_produit < 0) {
        $errors["reduction_produit"] = "Reduction du produit doit être un entier naturel";
    }


    // Vérifie qu'une image a bien été fournie
    if (empty($image_produit['tmp_name']) && $quest_type === "add") {
        $errors["image_produit"] = "Veuillez sélectionner l'image du livre";
    }

    // Si aucune erreur, on procède à l'insertion en BDD
    if (empty($errors)) {
        try {
            // Récupération du nom et du préfixe de la catégorie choisie
            $req2 = $conn->prepare("SELECT nom_categorie, prefixe_categorie FROM categorie
                        WHERE id_categorie = ?;");
            $req2->bind_param('i', $cat_produit_id);
            $req2->execute();
            $info = $req2->get_result()->fetch_assoc();
            $req2->close();

            if (!$info) {
                throw new RuntimeException("Catégorie inexistante");
            }

            // UPDATE sans nouvelle image → pas de traitement fichier
            if ($quest_type === "update" && empty($image_produit['tmp_name'])) {
                // update du livre dans la base de données
                $udp = $conn->prepare("UPDATE livre 
                SET titre_livre = ?, resume_livre = ?, auteur_livre = ?,
                    prix = ?, stock = ?, id_categorie = ?, langue = ?,
                    public_legal = ?, reduction_livre = ?
                WHERE id_livre = ?;");
                $udp->bind_param(
                    "sssdiissii",
                    $titre_produit,
                    $resume_produit,
                    $auteur_produit,
                    $prix_produit,
                    $stock_produit,
                    $cat_produit_id,
                    $langue_produit,
                    $public_legal_produit,
                    $reduction_produit,
                    $id_livre
                );
                $udp->execute();
                $udp->close();

                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            }

            // Construction du dossier de destination
            $nom = str_replace(" ", "", $info['nom_categorie']);
            $uploadDirection = "../assets/images/produits/" . $nom . '/';

            // Création du dossier s'il n'existe pas
            if (
                !is_dir($uploadDirection) &&
                !mkdir($uploadDirection, 0755, true) &&
                !is_dir($uploadDirection)
            ) {
                throw new RuntimeException("Création dossier impossible");
            }

            $extentionsAccepter = [
                'image/jpg',
                'image/jpeg',
                'image/webp',
                'image/png'
            ];


            $tmpName = $image_produit['tmp_name'];
            $name = !empty($image_produit['name']) ? $image_produit['name'] : 'anonymous_book.webp';

            // Chemin temporaire pour image_livre (colonne NOT NULL)
            $tmp_image_livre = !empty($tmpName) ? $tmpName : "/tmp/anonymous_book.webp" . uniqid();

            // Vérification du type MIME réel du fichier
            if (!empty($tmpName)) {
                $fileType = mime_content_type($tmpName);
                if (!in_array($fileType, $extentionsAccepter)) {
                    $errors["image_produit"] = "Le format du fichier est incompatible";
                }
            }


            // vérification de la taille du fichier
            if (
                !empty($tmpName) &&
                $image_produit['size'] > 1024 * 1024 //1 Mo
            ) {
                $errors["image_produit"] = "Fichier trop lourd. Max 1 Mo";
            }

            // Deuxième passage de validation
            if (empty($errors)) {
                // --- Construction SQL + types + valeurs --
                if ($quest_type === "update") {
                    $sql = "UPDATE livre 
                SET titre_livre = ?,
                    resume_livre = ?,
                    auteur_livre = ?,
                    prix = ?,
                    stock = ?,
                    image_livre = ?,
                    id_categorie = ?,
                    langue = ?,
                    public_legal = ?,
                    reduction_livre = ?
                WHERE id_livre = ?;";
                    $types = "sssdisissii";
                    $default_bind_param = [
                        $titre_produit,
                        $resume_produit,
                        $auteur_produit,
                        $prix_produit,
                        $stock_produit,
                        $tmp_image_livre,
                        $cat_produit_id,
                        $langue_produit,
                        $public_legal_produit,
                        $reduction_produit,
                        $id_livre
                    ];
                } else {
                    $sql = "INSERT INTO livre (titre_livre,
                    resume_livre, auteur_livre, prix, stock, image_livre,
                    id_categorie, langue, public_legal, reduction_livre) VALUES
                    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
                    $types = "sssdisissi";
                    $default_bind_param = [
                        $titre_produit,
                        $resume_produit,
                        $auteur_produit,
                        $prix_produit,
                        $stock_produit,
                        $tmp_image_livre,
                        $cat_produit_id,
                        $langue_produit,
                        $public_legal_produit,
                        $reduction_produit,
                    ];
                }

                // Ancienne image (pour rollback en cas d'échec du move)
                if ($quest_type == 'update') {
                    $path_req = $conn->prepare("SELECT image_livre FROM livre WHERE id_livre = ?;");
                    $path_req->bind_param("i", $id_livre);
                    $path_req->execute();
                    $row = $path_req->get_result()->fetch_assoc();
                    $old_img = $row['image_livre'] ?? '/tmp/anonymous_book.webp' . uniqid();
                    ;
                    $path_req->close();
                }

                // Insertion du livre dans la base de données
                $req1 = $conn->prepare($sql);
                $req1->bind_param($types, ...$default_bind_param);
                $req1->execute();
                $lastId = $quest_type === 'add' ? $conn->insert_id : $id_livre; // ID du livre qui vient d'être added/updated
                $req1->close();

                // Nom du fichier image : id_livre.extension
                $extension = pathinfo($name, PATHINFO_EXTENSION);
                $fileName = $lastId . '.' . $extension;

                // Chemins web et disque
                $image_livre = "/produits/" . $nom . "/" . $info['prefixe_categorie'] . $fileName;
                $targetFile = $uploadDirection . $info['prefixe_categorie'] . $fileName;

                // Déplacement du fichier uploadé vers sa destination finale
                if (move_uploaded_file($tmpName, $targetFile)) {
                    $stmt = $conn->prepare("UPDATE livre SET image_livre = ? WHERE id_livre = ?");
                    $stmt->bind_param("si", $image_livre, $lastId);
                    $stmt->execute();
                    $stmt->close();
                    header("location: " . $_SERVER['PHP_SELF']);
                    exit;
                } else {
                    // Rollback en cas d'échec du déplacement
                    if ($quest_type === 'add') {
                        $stmt1 = $conn->prepare("DELETE FROM livre WHERE id_livre = ?");
                        $stmt1->bind_param("i", $lastId);
                        $stmt1->execute();
                        $stmt1->close();
                    }

                    if ($quest_type === "update") {
                        $stmt2 = $conn->prepare("UPDATE livre SET image_livre = ? WHERE id_livre = ?");
                        $stmt2->bind_param("si", $old_img, $lastId);
                        $stmt2->execute();
                        $stmt2->close();
                    }
                    header("location: " . $_SERVER['PHP_SELF']);
                    exit;
                }
            }
        } catch (mysqli_sql_exception $e) {
            error_log("dashboard.php produit (DB): " . $e->getMessage());
            flash_error("Erreur base de données lors de l'enregistrement du produit.");
        } catch (RuntimeException $e) {
            error_log("dashboard.php produit: " . $e->getMessage());
            flash_error("Erreur lors de l'enregistrement du produit.");
        }

    }

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/flash.css">
    <script src="../assets/js/dashboard.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js" defer></script>
    <script src="../assets/js/dataAOS.js" defer></script>
    <title>Tableau de bord administrateur | Bookroom</title>
</head>

<body>
    <?php flash_render(); ?>
    <section class="section-dashbord">
        <header class="logo-div" data-aos="fade-right">
            <div class="logo">
                <img loading="lazy" src="../assets/images/ico/favicon/favicon.png" alt="logo">
                <a href="../pages/parametre.php"><strong>Book<span class="room">room</span></strong></a>
            </div>
            <div class="admin-badge">
                <span class="admin-role">Admin</span>
                <span class="admin-email">allohanjoel13@gmail.com</span>
            </div>

        </header>

        <div class="dashboard" data-aos="zoom-in">
            <div class="menu">
                <span class="hamburger"></span>
                <span class="hamburger"></span>
                <span class="hamburger"></span>
            </div>

            <div class="all" data-aos="fade-right">
                <div class="tools mobile">
                    <div class="hover tool" onclick="showSection(event, 'show-produits')"
                        title="Ajouter | Modifier | Supprimer">
                        <div class="content">
                            <i class="fa-brands fa-product-hunt"></i>
                            <p class="tool-name">Produits</p>
                        </div>
                    </div>

                    <div class=" tool" onclick="showSection(event, 'show-categories')" title="Gérer les catégories ">
                        <div class="content">
                            <i class="fa-solid fa-table-list"></i>
                            <p class="tool-name">Catégories</p>
                        </div>
                    </div>

                    <div class=" tool" onclick="showSection(event, 'show-clients')" title="Lister les clients">
                        <div class="content">
                            <i class="fa-solid fa-person"></i>
                            <p class="tool-name">Clients</p>
                        </div>
                    </div>



                    <div class=" tool" onclick="showSection(event, 'show-statistiques')" title="statistiques">
                        <div class="content">
                            <i class="fa-solid fa-chart-pie"></i>
                            <p class="tool-name">Statistiques</p>
                        </div>
                    </div>

                </div>


                <div class="show" data-aos="zoom-in">
                    <!--PRODUITS-->
                    <div class="container active" id="show-produits">
                        <table class="produits">
                            <span class="more">
                                <svg class="produit" xmlns="http://www.w3.org/2000/svg" height="24px"
                                    viewBox="0 -960 960 960" width="24px" fill="#264E5B">
                                    <path d="M440-120v-320H120v-80h320v-320h80v320h320v80H520v320h-80Z" />
                                </svg>
                            </span>
                            <thead>
                                <!--ENTETE-->
                                <tr>
                                    <th>Image</th>
                                    <th>Nom Produits</th>
                                    <th>Auteur</th>
                                    <th>Prix</th>
                                    <th>Stock</th>
                                    <th>Public legal</th>
                                    <th>Reduction</th>
                                    <th>Langue</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <!--CORPS | ZONE PHP-->
                            <tbody>
                                <?php while ($livre = $produits->fetch_assoc()): ?>
                                    <tr>
                                        <td class="produit_img">
                                            <img loading="lazy" src="../assets/images<?= $livre['image_livre'] ?>" alt="">
                                        </td>
                                        <td class="produit-nom">
                                            <?= $livre['titre_livre'] ?>
                                        </td>

                                        <td class="produit-auteur">
                                            <?= $livre['auteur_livre'] ?>
                                        </td>

                                        <td class="produit-prix">
                                            <?= $livre['prix'] ?>
                                        </td>

                                        <td class="produit-stock">
                                            <?= $livre['stock'] ?>
                                        </td>
                                        <td class="produit-public">
                                            <?= $livre['public_legal'] ?>
                                        </td>
                                        <td class="produit-reduction">
                                            <?= $livre['reduction_livre'] ?>
                                        </td>
                                        <td class="produit-langue">
                                            <?= $livre['langue'] ?>
                                        </td>

                                        <td class="produit-action">
                                            <form action="" method="post">
                                                <svg class="edit edit-produit" xmlns="http://www.w3.org/2000/svg"
                                                    data-titre="<?= $livre['titre_livre'] ?>"
                                                    data-resume="<?= $livre['resume_livre'] ?>"
                                                    data-auteur="<?= $livre['auteur_livre'] ?>"
                                                    data-prix="<?= $livre['prix'] ?>" data-stock="<?= $livre['stock'] ?>"
                                                    data-image="<?= $livre['image_livre'] ?>"
                                                    data-langue="<?= $livre['langue'] ?>"
                                                    data-public="<?= $livre['public_legal'] ?>"
                                                    data-categorie="<?= $livre['id_categorie'] ?>"
                                                    data-reduction="<?= $livre['reduction_livre'] ?>" height="24px"
                                                    viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                                                    <path
                                                        d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z" />
                                                </svg>
                                                <input type="hidden" name="produit_id" value="<?= $livre['id_livre'] ?>">
                                                <button style="background-color: transparent; border: none;">
                                                    <svg class="suppr-svg" xmlns="http://www.w3.org/2000/svg" height="24px"
                                                        viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                                                        <path
                                                            d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <!--CATEGORIES-->
                    <div class="container" id="show-categories">
                        <span class="more">
                            <svg class="add-cat" xmlns="http://www.w3.org/2000/svg" height="24px"
                                viewBox="0 -960 960 960" width="24px" fill="#264E5B">
                                <path d="M440-120v-320H120v-80h320v-320h80v320h320v80H520v320h-80Z" />
                            </svg>
                        </span>
                        <table class="clients">
                            <thead>
                                <!--ENTETE-->
                                <tr>
                                    <th>Nom Categorie</th>
                                    <th>Préfixe</th>
                                    <th>Outils</th>
                                </tr>
                            </thead>
                            <!--CORPS | ZONE PHP-->
                            <tbody>
                                <?php while ($categorie = $categories->fetch_assoc()): ?>
                                    <tr>
                                        <td class="cat-nom">
                                            <?= $categorie['nom_categorie'] ?>
                                        </td>

                                        <td class="cat-pre">
                                            <?= $categorie['prefixe_categorie'] ?>
                                        </td>


                                        <td class="suppr-cat">
                                            <form action="" method="post" id='edit-categorie'>
                                                <svg class="edit edit-cat" data-nom="<?= $categorie['nom_categorie'] ?>"
                                                    data-prefixe="<?= $categorie['prefixe_categorie'] ?>"
                                                    xmlns="http://www.w3.org/2000/svg" height="24px"
                                                    viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                                                    <path
                                                        d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z" />
                                                </svg>
                                                <input type="hidden" name="cat_id"
                                                    value="<?= $categorie['id_categorie'] ?>">
                                                <button style="background-color: transparent; border: none;">
                                                    <svg class="suppr-svg" xmlns="http://www.w3.org/2000/svg" height="24px"
                                                        viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                                                        <path
                                                            d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <!--CLIENTS-->
                    <div class="container" id="show-clients">
                        <table class="clients">
                            <thead>
                                <!--ENTETE-->
                                <tr>
                                    <th>Genre</th>
                                    <th>Pseudo</th>
                                    <th>Mail</th>
                                    <th>Tel</th>
                                    <th>Adresse</th>
                                    <th>Supprimer</th>
                                    <th>role</th>
                                </tr>
                            </thead>
                            <!--CORPS | ZONE PHP-->
                            <tbody>
                                <?php while ($client = $clients->fetch_assoc()):
                                    $genre = ($client['genre_client'] == 'M') ? 'man' : "woman";
                                    ?>
                                    <tr>
                                        <td class="user-img">
                                            <img loading="lazy" src="../assets/images/ico/user/<?= $genre ?>.png" alt="">
                                        </td>
                                        <td class="user-pseudo">
                                            <?= $client['nom_prenom_client'] ?>
                                        </td>
                                        <td class="user-mail">
                                            <?= $client['email_client'] ?>
                                        </td>
                                        <td class="user-Tel">
                                            <?= $client['tel_client'] ?>
                                        </td>
                                        <td class="user-Adresse">
                                            <?= $client['adresse_client'] ?>
                                        </td>
                                        <td class="suppr-user">
                                            <form action="" method="post">
                                                <input type="hidden" name="user_id" value="<?= $client['id_client'] ?>">
                                                <button style="background-color: transparent;  border: none;">
                                                    <svg class="suppr-svg" xmlns="http://www.w3.org/2000/svg" height="24px"
                                                        viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                                                        <path
                                                            d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>

                                        <td class="role">
                                            <form action="" method="post">
                                                <input type="hidden" name="adminize" value="<?= $client['id_client'] ?>">
                                                <button class="role-button" type="submit" title="<?= $client['role_client'] === 'admin' ?
                                                    'retirer les droits admin' : 'octroyer les droits admin' ?>">
                                                    <?= $client['role_client'] === 'admin' ?
                                                        "<i class='fa-solid fa-user-secret fa-lg' style='color: #035add;'></i>" :
                                                        "<i class='fa-solid fa-user fa-lg' style='color: #014e01'; ></i>";
                                                    ?>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>


                    <!--STATISTIQUES-->
                    <div class="container" id="show-statistiques">
                        <div class="t-commandes">
                            <div class="start-i">
                                <i class="fa-solid fa-chart-area"></i>
                            </div>
                            <div class="chiffre">75</div>
                            <div class="what">Commandes</div>
                        </div>
                        <div class="t-ventes">
                            <div class="start-i">
                                <i class="fa-brands fa-sellsy"></i>
                            </div>
                            <div class="chiffre">45k</div>
                            <div class="what">Ventes</div>
                        </div>
                        <div class="p-vendus">
                            <div class="start-i">
                                <i class="fa-brands fa-product-hunt"></i>
                            </div>
                            <div class="chiffre"><?= $nb_livre ?></div>
                            <div class="what">Produits</div>
                        </div>
                        <div class="t-clients">
                            <div class="start-i">
                                <i class="fa-regular fa-face-grin"></i>
                            </div>
                            <div class="chiffre"><?= $nb_client ?></div>
                            <div class="what">Clients</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>


    <!--LES BLOCS EDITS | BOITES MODALS-->
    <div class="edit-div">
        <!--PRODUITS-->
        <form class="produit-form" action="" method="post" enctype="multipart/form-data">
            <div>
                <label for="titre_produit">
                    Titre Produit
                    <span class="important">*</span>
                </label>
                <input type="text" placeholder="Titre Livre" value="" name="titre_produit" id="titre_produit">
                <span class="field-error"><?= htmlspecialchars($errors["titre_produit"]) ?></span>
            </div>
            <div>
                <label for="resume_produit">
                    Resume Produit
                    <span class="important">*</span>
                </label>
                <textarea name="resume_produit" id="resume_produit"
                    placeholder="Résumé du livre (20 Caractères minimum)"></textarea>
                <span class="field-error"><?= htmlspecialchars($errors["resume_produit"]) ?></span>
            </div>
            <div>
                <label for="auteur_produit">
                    Auteur Produit
                    <span class="important">*</span>
                </label>
                <input type="text" placeholder="Auteur Livre" name="auteur_produit" id="auteur_produit">
                <span class="field-error"><?= htmlspecialchars($errors["auteur_produit"]) ?></span>
            </div>
            <div>
                <label for="prix_produit">
                    Prix Produit
                    <span class="important">*</span>
                </label>
                <input type="text" width="100" placeholder="Prix" name="prix_produit" id="prix_produit">
                <span class="field-error"><?= htmlspecialchars($errors["prix_produit"]) ?></span>
            </div>
            <div>
                <label for="stock_produit">
                    Stock Produit
                    <span class="important">*</span>
                </label>
                <input type="text" name="stock_produit" placeholder="Stock" name="stock_produit" id="stock_produit">
                <span class="field-error"><?= htmlspecialchars($errors["stock_produit"]) ?></span>
            </div>
            <div class="select-wrapper">
                <label for="cat_produit">
                    Catégorie Produit
                    <span class="important">*</span>
                </label>
                <select name="cat_produit" id="cat_produit" class="cat-select">
                    <?php while ($cat = $cat_livre->fetch_assoc()): ?>
                        <option value="<?= $cat['id_categorie'] ?>"> <?= $cat['nom_categorie'] ?> </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label for="image_produit">
                    Image Produit
                </label>
                <input type="file" name="image_produit" accept="image/png, image/jpeg, image/webp, image/jpg"
                    id="image_produit">
                <span class="field-error"><?= htmlspecialchars($errors["image_produit"]) ?></span>
            </div>
            <div>
                <label for="langue_produit">
                    Langue Produit
                    <span class="important">*</span>
                </label>
                <input type="text" placeholder="Langue eg:FR" value="" maxlength="3" name="langue_produit"
                    id="langue_produit" class="langue-produit">
                <span class="field-error"><?= htmlspecialchars($errors["langue_produit"]) ?></span>
            </div>
            <div>
                <label for="public_legal_produit">
                    Public Legal Produit
                    <span class="important">*</span>
                </label>
                <input type="text" placeholder="Public Légal eg:15+" value="" name="public_legal_produit"
                    id="public_legal_produit">
            </div>
            <div>
                <label for="reduction_produit">
                    Reduction Produit
                    <span class="important">*</span>
                </label>
                <input type="text" placeholder="Reduction Livre" value="" name="reduction_produit"
                    id="reduction_produit">
                <span class="field-error"><?= htmlspecialchars($errors["reduction_produit"]) ?></span>
            </div>
            <div class="get-hidden-input">
                <input type="hidden" name="id_livre" value="" id="id_livre" class="hidden-input">
            </div>
            <div class="edit-actions-div">
                <button class="produit-actions-btn">Ajouter</button>
                <button class="closing-form">
                    <p>Fermer </p>
                </button>
            </div>

        </form>
        <!-- CATEGORIES -->
        <!-- EDITION CATEGORIE -->
        <form class="edit-cat-form" action="" method="post">
            <div><input type="text" placeholder="Nom Catégorie" name="edit_nom_cat" id="edit_nom_cat"
                    class="edit-nom-cat"> </div>
            <div><input type="text" placeholder="préfixe" name="edit_pref" id="edit_pref" class="edit-pref-cat"></div>
            <div><input type="hidden" name="id_category" value="" id='edit_cat_id_input'></div>
            <div class="edit-actions-div">
                <button type='submit'>Modifier</button>
                <button class="closing-form">
                    <p>close</p>
                </button>
            </div>
        </form>
        <!-- AJOUT CATEGORIE -->
        <form class="add-cat-form" action="" method="post">
            </button>
            <div><input type="text" placeholder="Nom Catégorie" name="add_nom_cat" id="add_nom_cat"></div>
            <div><input type="text" placeholder="préfixe" name="add_pref" id="add_pref"></div>
            <div class="edit-actions-div">
                <button>Ajouter</button>
                <button class="closing-form">
                    <p>close</p>
                </button>
            </div>
        </form>
    </div>
</body>

</html>