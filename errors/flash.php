<?php
function flash(string $type, string $message): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION["flash"][] = [
        'type' => $type,     // success, error, warning, info
        'message' => $message,
    ];
}

// Raccourcis
function flash_success(string $m): void
{
    flash('success', $m);
}
function flash_error(string $m): void
{
    flash('error', $m);
}
function flash_warning(string $m): void
{
    flash('warning', $m);
}
function flash_info(string $m): void
{
    flash('info', $m);
}

/**
 * Récupère et vide les messages
 */
function flash_get(): array
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $messages;
}

/**
 * Affiche les messages en HTML
 */
function flash_render(): void
{
    $messages = flash_get();
    if (empty($messages))
        return;

    echo '<div class="flash-container">';
    foreach ($messages as $msg) {
        $type = htmlspecialchars($msg['type']);
        $text = htmlspecialchars($msg['message']);
        echo "<div class=\"flash flash-{$type}\">{$text}</div>";
    }
    echo '</div>';
}

?>