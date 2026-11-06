<?php
require __DIR__ . '/../env.php';
require __DIR__ . '/../includes/constants.php';

// Toute autre méthode HTTP est refusée
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header("Location: ../errors/404.php");
    exit;
}

/*FORMATER UNE REDIRECTION PROPRE VERS LA PAGE D'OÙ LA REQUÊTE A ÉTÉ LANCÉ */
$returnTo = $_POST['return_to'] ?? BASE_URL;
if (
    !is_string($returnTo)
    || !str_starts_with($returnTo, '/')          // L'URL doit être relative
    || str_starts_with($returnTo, '//')          // Bloque les redirections vers un domaine externe
    || str_contains($returnTo, '\\')             // Bloque les antislashs pouvant contourner les vérifications
    || preg_match('/[\r\n]/', $returnTo)         // Empêche les injections d'en-têtes HTTP.
) {
    $returnTo = BASE_URL;
}

// Supprime les anciens paramètres error et sent pour éviter leur accumulation
$returnTo = preg_replace('/[?&](error|sent)=[^&#]*/', '', $returnTo);

// Nettoie les éventuels séparateurs restés à la fin de l'URL
$returnTo = rtrim($returnTo, '?&');

// Détermine le séparateur à utiliser pour ajouter un nouveau paramètre à l'URL
$sep = str_contains($returnTo, '?') ? '&' : '?';


// Récupération des informations de configuration de l'API Resend
// depuis les variables d'environnement.
$from = getenv('RESEND_FROM');
$to = getenv('RESEND_TO');
$api = getenv('RESEND_API_KEY');

// Vérifie que toutes les variables nécessaires à l'envoi sont disponibles.
if (!$from || !$to || !$api) {
    error_log('[Resend] Missing env');
    header("Location: " . $returnTo . $sep . 'error=1#contact');
    exit;
}

// Fonction fléchée permettant de récupérer une donnée POST sous forme de chaîne
$s = fn($k) => is_string($_POST[$k] ?? null) ? trim($_POST[$k]) : '';

// Récupération et nettoyage des champs transmis par le formulaire
$name = $s('user_contact_name');
$email = $s('user_contact_email');
$message = $s('user_contact_message');

// Récupération du champ piège destiné à détecter les robots
$trap = $s('website');

if ($trap !== '') {
    header('Location: ' . $returnTo . $sep . 'sent=1#contact');
    exit;
}

if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $message === '') {
    header("Location: " . $returnTo . $sep . 'error=1#contact');
    exit;
}

try {
    // Construction du tableau contenant les données de l'e-mail à envoyer
    $payload = [
        'from' => $from,
        'to' => [$to],
        'reply_to' => $email,
        'subject' => 'Nouveau message de ' . $name . ' | Bookroom',

        // Génération du contenu HTML de l'e-mail à l'aide de sprintf()
        // Les marqueurs %s seront remplacés par les informations du formulaire
        'html' => sprintf(
            '<!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
           <style>
        body {
            background-color: #f2f2f2;
            font-family: monospace, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 40px 10px;
            color: #1f1f23;
        }

        .container {
            max-width: 550px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 4px;
        }

        .header {
            text-align: center;
            padding-bottom: 24px;
        }


        .titre img {
            height: 30px;
            width: auto;
            display: block;
            margin: 0 auto 16px auto;
        }


        .divider {
            height: 2px;
            background-color: #aaa;

            width: 80px;
            margin: 5px auto 0;
        }

        .content {
            padding-top: 32px;
            font-size: 15px;
            line-height: 1.6;
            color: #1f1f23;
        }

        .field {
            margin-bottom: 20px;
        }

        .label {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            color: #70707b;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .value {
            font-size: 13px;
            color: #0e0e10;
        }

        .message-box {
            margin-top: 8px;
            white-space: pre-line;
        }

        .footer {
            max-width: 550px;
            margin: 24px auto 0 auto;
            text-align: center;
            font-size: 12px;
            color: #898993;
            line-height: 1.5;
        }

        a {
            color: #2a4fc9;
            text-decoration: none;
        }
    </style>
        </head>
        <body>
            <div class="container">
        <div class="header">
                <img src="https://raw.githubusercontent.com/MJoel-Neel/bookroom/main/favicon32.png" alt="BOOKRoom"
                    loading="lazy">
            <div class="divider"></div>
        </div>

        <div class="content">
            <div class="field">
                <div class="label">Expéditeur</div>
                <div class="value"><b>%s</b> (<a href="mailto:%s"> %s </a>)</div>
            </div>

            <div class="field">
                <div class="label">Message</div>
                <div class="value message-box">%s</div>
            </div>
        </div>
    </div>

    <div class="footer">
        &copy; Bookroom Neel Mj &bull; Tous droits réservés<br>
        12 rue des Livres, 75000 Paris, France
    </div>
        </body>
        </html>',
            htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($email, ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($email, ENT_QUOTES, 'UTF-8'),
            nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'))
        ),
    ];

    // Conversion du tableau PHP en JSON pour respecter le format attendu par l'API
    // JSON_THROW_ON_ERROR permet de déclencher une exception en cas d'échec
    $body = json_encode($payload, JSON_THROW_ON_ERROR);
} catch (\JsonException $e) {
    error_log('[Resend] json: ' . $e->getMessage());

    // Redirige vers le formulaire avec un indicateur d'échec
    header("Location: " . $returnTo . $sep . 'error=1#contact');
    exit;
}

// Initialisation de la requête cURL vers l'endpoint d'envoi d'e-mails de Resend
$ch = curl_init('https://api.resend.com/emails');

curl_setopt_array($ch, [
    CURLOPT_POST => true,    // Utilise la méthode HTTP POST
    CURLOPT_RETURNTRANSFER => true, // Récupère la réponse sous forme de chaîne
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $api", // Authentification avec la clé API Resend
        'Content-Type: application/json' // Indique que les données envoyées sont au format JSON
        ],
    CURLOPT_POSTFIELDS => $body, // Corps JSON contenant les informations de l'e-mail
    CURLOPT_TIMEOUT => 10, // Limite la durée de la requête à 10 secondes
]);

// Exécution de la requête et récupération de la réponse de l'API
$response = curl_exec($ch);

// Récupération du code de statut HTTP retourné par Resend
$code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
// Récupération d'un éventuel message d'erreur provenant de cURL
$err = curl_error($ch);

// Vérifie si la requête a échoué ou si l'API a retourné un statut HTTP hors de la plage 2xx.
if ($response === false || $err !== '' || $code < 200 || $code >= 300) {
    error_log("[Resend] code=$code err=$err body=$response");
    header("Location: " . $returnTo . $sep . 'error=1#contact');
    exit;
}

header('Location: ' . $returnTo . $sep . 'sent=1#contact');
exit;