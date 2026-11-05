<?php
declare(strict_types=1);
require __DIR__ . '/../env.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit;
}

$from = getenv('RESEND_FROM');
$to   = getenv('RESEND_TO');
$api  = getenv('RESEND_API_KEY');
if (!$from || !$to || !$api) {
    error_log('[Resend] Missing env'); header('Location: ../?error=1'); exit;
}

$s = fn($k) => is_string($_POST[$k] ?? null) ? trim($_POST[$k]) : '';

$name = $s('user_contact_name');
$email = $s('user_contact_email');
$message = $s('user_contact_message');
$trap = $s('website');

if ($trap !== '') { header('Location: ../?sent=1'); exit; }

if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $message === '') {
    header('Location: ../?error=1'); exit;   // ← FIXED: ../ like the others
}

try {
    $payload = [
        'from'     => $from,
        'to'       => [$to],
        'reply_to' => $email,
        'subject'  => 'Nouveau message de ' . $name . ' | Bookroom',
        'html'     => sprintf('<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; background-color: #0f172a; margin: 0; padding: 0; color: #333333; }
                .email-wrapper { max-width: 600px; margin: 30px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
                .email-header { background-color: #0d131f; color: #ffffff; padding: 25px; text-align: center; border-bottom: 4px solid #c92a2a; }
                .email-header img { width: 36px; height: 36px; vertical-align: middle; margin-right: 10px; border-radius: 6px; }
                .email-header h2 { margin: 0; font-size: 22px; display: inline-block; vertical-align: middle; color: #ffffff; letter-spacing: 0.5px; }
                .email-body { padding: 30px; background-color: #ffffff; }
                .email-body h3 { color: #0d131f; margin-top: 0; font-size: 16px; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
                .field-group { margin-bottom: 20px; }
                .field-label { font-weight: bold; color: #c92a2a; font-size: 12px; text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px; }
                .field-value { background-color: #f8fafc; padding: 12px 15px; border-radius: 6px; border-left: 3px solid #0d131f; font-size: 14px; color: #1e293b; }
                .field-value a { color: #c92a2a; text-decoration: none; font-weight: bold; }
                .email-footer { background-color: #0d131f; color: #94a3b8; text-align: center; padding: 20px; font-size: 12px; border-top: 1px solid #1e293b; }
            </style>
        </head>
        <body>
            <div class="email-wrapper">
                <!-- En-tête avec votre Favicon/Logo et le nom de la marque -->
                <div class="email-header">
                    <img src="/favicon32.png" alt="Bookroom Logo">
                    <h2>BOOKRoom</h2>
                </div>

                <!-- Corps du message -->
                <div class="email-body">
                    <h3>Nouveau message de contact</h3>
                    
                    <div class="field-group">
                        <div class="field-label">Auteur / Pseudo</div>
                        <div class="field-value">%s</div>
                    </div>

                    <div class="field-group">
                        <div class="field-label">Adresse Email</div>
                        <div class="field-value"><a href="mailto:%s">%s</a></div>
                    </div>

                    <div class="field-group">
                        <div class="field-label">Contenu du message</div>
                        <div class="field-value" style="border-left-color: #c92a2a; line-height: 1.6;">%s</div>
                    </div>
                </div>

                <!-- Pied de page corporate -->
                <div class="email-footer">
                    Tout droits réservés. &copy; Copyright BOOKRoom Neel Mj
                </div>
            </div>
        </body>
        </html>',
            htmlspecialchars($name,    ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($email,   ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($email,   ENT_QUOTES, 'UTF-8'), 
            nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'))
        ),
    ];
    $body = json_encode($payload, JSON_THROW_ON_ERROR);
} catch (\JsonException $e) {
    error_log('[Resend] json: ' . $e->getMessage());
    header('Location: ../?error=1'); exit;
}

$ch = curl_init('https://api.resend.com/emails');
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => ["Authorization: Bearer $api", 'Content-Type: application/json'],
    CURLOPT_POSTFIELDS     => $body,
    CURLOPT_TIMEOUT        => 10,
]);
$response = curl_exec($ch);
$code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
$err  = curl_error($ch);


if ($response === false || $err !== '' || $code < 200 || $code >= 300) {
    error_log("[Resend] code=$code err=$err body=$response");
    header('Location: ../?error=1'); exit;
}

header('Location: ../?sent=1');
exit;