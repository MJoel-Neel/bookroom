<?php
declare(strict_types=1);
require __DIR__ . '/../env.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit;
}

$from = getenv('RESEND_FROM');
$to = getenv('RESEND_TO');
$api = getenv('RESEND_API_KEY');
if (!$from || !$to || !$api) {
    error_log('[Resend] Missing env');
    header('Location: ../?error=1#contact');
    exit;
}

$s = fn($k) => is_string($_POST[$k] ?? null) ? trim($_POST[$k]) : '';

$name = $s('user_contact_name');
$email = $s('user_contact_email');
$message = $s('user_contact_message');
$trap = $s('website');

if ($trap !== '') {
    header('Location: ../?sent=1#contact');
    exit;
}

if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $message === '') {
    header('Location: ../?error=1#contact');
    exit;  
}

try {
    $payload = [
        'from' => $from,
        'to' => [$to],
        'reply_to' => $email,
        'subject' => 'Nouveau message de ' . $name . ' | Bookroom',
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
    $body = json_encode($payload, JSON_THROW_ON_ERROR);
} catch (\JsonException $e) {
    error_log('[Resend] json: ' . $e->getMessage());
    header('Location: ../?error=1#contact');
    exit;
}

$ch = curl_init('https://api.resend.com/emails');
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ["Authorization: Bearer $api", 'Content-Type: application/json'],
    CURLOPT_POSTFIELDS => $body,
    CURLOPT_TIMEOUT => 10,
]);
$response = curl_exec($ch);
$code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
$err = curl_error($ch);


if ($response === false || $err !== '' || $code < 200 || $code >= 300) {
    error_log("[Resend] code=$code err=$err body=$response");
    header('Location: ../?error=1#contact');
    exit;
}

header('Location: ../?sent=1#contact');
exit;