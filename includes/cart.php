<?php
require_once __DIR__ . '/../errors/flash.php';
require_once __DIR__ . '/../config.php';

/* HELPERS */

function is_logged_in(): bool
{
    return isset($_SESSION["user_id"]) && (int) $_SESSION["user_id"] > 0;
}

function get_client_id(): ?int
{
    return is_logged_in() ? (int) $_SESSION["user_id"] : null;
}


/* Retourne le panier courant : [id_livre => qte] */
function cart_get(): array
{
    // Invité → session
    if (!is_logged_in()) {
        return $_SESSION["panier"] ?? [];
    }

    // Connecté → BDD (source de vérité)
    $id_client = get_client_id();
    global $conn;
    $stmt = $conn->prepare("
        SELECT pp.id_livre, pp.quantite
        FROM produit_panier pp
        JOIN panier p ON p.id_panier = pp.id_panier
        WHERE p.id_client = ? AND p.statut = 'actif'");
    $stmt->bind_param('i', $id_client);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $cart = [];
    foreach ($rows as $r) {
        $cart[(int) $r['id_livre']] = (int) $r['quantite'];
    }
    return $cart;
}

/* Retourne le stock disponible pour un livre, ou null si introuvable */
function cart_get_stock(int $id_livre): ?int
{
    global $conn;
    $stmt = $conn->prepare("SELECT stock FROM livre WHERE id_livre = ?");
    $stmt->bind_param('i', $id_livre);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return $row ? (int) $row['stock'] : null;
}

/* ÉCRITURE */

/*
 * Ajoute $qte au panier, en respectant le stock disponible.
 * Retourne true si ajouté, false si stock max atteint.
 */
function cart_add(int $id_livre, int $qte = 1): bool
{
    if ($id_livre <= 0)
        return false;
    $stock_max = cart_get_stock($id_livre);

    if ($stock_max === null) {
        flash_error("Produit introuvable.");
        return false;
    }

    $actuel = $_SESSION['panier'][$id_livre] ?? 0;
    $nouveau = $actuel + $qte;

    // Vérif stock
    if ($nouveau > $stock_max) {
        flash_warning("Stock maximum atteint ($stock_max disponibles).");
        return false;
    }

    // mise à jour de la session (source immédiate)
    $_SESSION['panier'][$id_livre] = $nouveau;

    // Si connecté, synchroniser en BDD
    if (is_logged_in()) {
        cart_sync_to_db();
    }
    return true;
}

function cart_remove(int $id_livre): void
{
    unset($_SESSION["panier"][$id_livre]);

    if (is_logged_in()) {
        cart_sync_to_db();
    }

}


/*
 * Met à jour la quantité d'un livre dans le panier.
 * Retourne true si modifié, false si stock dépassé.
 */
function cart_update(int $id_livre, int $qte): bool
{
    if ($id_livre <= 0) return false;
    // Quantité <= 0 → suppression
    if ($qte <= 0) {
        cart_remove($id_livre);
        return true;
    }

    $stock_max = cart_get_stock($id_livre);
    if ($stock_max === null) {
        flash_error("Produit introuvable.");
        return false;
    }

    // Vérif stock
    if ($qte > $stock_max) {
        flash_warning("Stock maximum : $stock_max.");
        return false;
    }

    $_SESSION["panier"][$id_livre] = $qte;

    if (is_logged_in()) {
        cart_sync_to_db();
    }
    return true;
}

function cart_clear(): void
{
    $_SESSION["panier"] = [];

    if (is_logged_in()) {
        global $conn;
        $id_client = get_client_id();
        $conn->query("DELETE pp FROM produit_panier pp
                      JOIN panier p ON p.id_panier = pp.id_panier
                      WHERE p.id_client = $id_client AND p.statut = 'actif';");
    }
}

/* SYNCHRONISATION SESSION → BDD */

function cart_sync_to_db(): void
{
    if (!is_logged_in())
        return;

    global $conn;
    $id_client = get_client_id();
    $panier = $_SESSION["panier"] ?? [];

    // trouver ou créer le panier actif
    $stmt = $conn->prepare("SELECT id_panier FROM panier WHERE id_client = ? AND statut = 'actif' LIMIT 1");
    $stmt->bind_param('i', $id_client);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if ($row) {
        $id_panier = (int) $row['id_panier'];
    } else {
        $stmt = $conn->prepare("INSERT INTO panier (id_client, statut, date_creation) VALUES (?, 'actif', NOW())");
        $stmt->bind_param('i', $id_client);
        $stmt->execute();
        $id_panier = $conn->insert_id;
    }

    // vider les lignes existantes
    $stmt = $conn->prepare("DELETE FROM produit_panier WHERE id_panier = ?");
    $stmt->bind_param('i', $id_panier);
    $stmt->execute();

    // réinsérer les lignes actuelles
    if (!empty($panier)) {
        $stmt = $conn->prepare("INSERT INTO produit_panier (id_panier, id_livre, quantite) VALUES (?, ?, ?)");
        foreach ($panier as $id_livre => $qte) {
            $stmt->bind_param('iii', $id_panier, $id_livre, $qte);
            $stmt->execute();
        }
    }
}

/* FUSION SESSION + BDD (à la connexion) */

function cart_merge_on_login(int $id_client): void
{
    global $conn;

    $session_cart = $_SESSION["panier"] ?? [];

    // Charger le panier BDD du client
    $stmt = $conn->prepare("
        SELECT pp.id_livre, pp.quantite
        FROM produit_panier pp
        JOIN panier p ON p.id_panier = pp.id_panier
        WHERE p.id_client = ? AND p.statut = 'actif'
    ");
    $stmt->bind_param('i', $id_client);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $db_cart = [];
    foreach ($rows as $r) {
        $db_cart[(int) $r['id_livre']] = (int) $r['quantite'];
    }

    // fusionner : BDD + session (addition des quantités)
    $merged = $db_cart;
    foreach ($session_cart as $id_livre => $qte) {
        $merged[$id_livre] = ($merged[$id_livre] ?? 0) + $qte;
    }

    // mettre en session
    $_SESSION["panier"] = $merged;

    // Synchroniser en BDD
    cart_sync_to_db();
}
?>