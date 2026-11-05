<?php
// env.php — Lit le fichier .env et injecte ses variables dans getenv()

// Définit le chemin absolu vers le fichier .env et vérifie s'il existe
$file = __DIR__ . '/.env';
if (!is_file($file)) {
    return;
}

// 2. Parcourt le fichier ligne par ligne en ignorant les lignes vides
foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    $line = trim($line);

    if ($line === '' || $line[0] === '#') {
        continue;
    }

    // Sépare la ligne en deux au premier signe '=' (clé et valeur)
    // L'ajout de '+ [1 => '']' évite une erreur si la valeur est totalement vide
    [$k, $v] = explode('=', $line, 2) + [1 => ''];

    // Nettoie les espaces, tabulations et guillemets (simples ou doubles) autour de la valeur
    $v = trim($v, " \t\"'");

    // Enregistre la variable d'environnement seulement si elle n'existe pas déjà
    // (Les vraies variables d'environnement du serveur ont la priorité)
    if (getenv($k) === false) {
        putenv("$k=$v");
    }
}
?>