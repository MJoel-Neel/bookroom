<?php
require_once __DIR__ . '/constants.php';

$seo = array_merge([
    'title'       => 'Bookroom – Librairie en ligne',
    'description' => 'Achetez des livres pas chers en ligne : éducation, fiction, manga. Livraison rapide.',
    'image'       => BASE_URL . '/assets/img/og-default.jpg',
    'type'        => 'website',
    'robots'      => 'index,follow',
    'locale'      => 'fr_FR',
    'site_name'   => 'Bookroom',
    'canonical'   => null,
], $seo ?? []);

if ($seo['canonical'] === null) {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'] ?? 'bookroom.fr';
    $path   = $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
    $seo['canonical'] = $scheme . '://' . $host . $path;
}

$e = fn($s) => htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');


?>


<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="<?= $e($seo['description']) ?>">
<meta name="robots" content="<?= $e($seo['robots']) ?>">
<link rel="canonical" href="<?= $e($seo['canonical']) ?>">

<!-- Icons -->
<link rel="icon" type="image/png" sizes="32x32" href="<?= BASE_URL ?>/favicon32.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?= BASE_URL ?>/favicon16.png">

<!-- Open Graph -->
<meta property="og:type"        content="<?= $e($seo['type']) ?>">
<meta property="og:site_name"   content="<?= $e($seo['site_name']) ?>">
<meta property="og:locale"      content="<?= $e($seo['locale']) ?>">
<meta property="og:title"       content="<?= $e($seo['title']) ?>">
<meta property="og:description" content="<?= $e($seo['description']) ?>">
<meta property="og:url"         content="<?= $e($seo['canonical']) ?>">
<meta property="og:image"       content="<?= $e($seo['image']) ?>">

<!-- Twitter -->
<meta name="twitter:card"        content="summary_large_image" >
<meta name="twitter:title"       content="<?= $e($seo['title']) ?>">
<meta name="twitter:description" content="<?= $e($seo['description']) ?>">
<meta name="twitter:image"       content="<?= $e($seo['image']) ?>">

<!-- Global CSS/JS (charger 1 fois par page) -->
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/base.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/fonts/font.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
      integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
      crossorigin="anonymous" referrerpolicy="no-referrer">
<script src="<?= BASE_URL ?>/assets/js/theme.js" defer></script>


<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/header.css">
<script src="<?= BASE_URL ?>/assets/js/head.js" defer></script>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/footer.css">


<!-- JSON-LD: Organization (site-wide) -->
<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'Organization',
    'name'     => 'Bookroom',
    'url'      => $seo['canonical'],
    'logo'     => BASE_URL . '/favicon32.png',
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
</script>

<title><?= $e($seo['title']) ?></title>