<?php
/**
 * HMTI UNDIP — Shared Application Bootstrap
 * Provides centralized DB connection, session/RBAC guards, CSRF protection, and flash messages.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Returns a configured mysqli connection using environment variables with local XAMPP/Docker defaults.
 */
function hmti_db(): mysqli
{
    static $conn = null;
    if ($conn instanceof mysqli) {
        return $conn;
    }

    $host = getenv('DB_HOST') ?: 'localhost';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASS');
    if ($pass === false) {
        $pass = '';
    }
    $name = getenv('DB_NAME') ?: 'HMTI_System';
    $port = getenv('DB_PORT') ? (int) getenv('DB_PORT') : 3306;

    $conn = new mysqli($host, $user, $pass, $name, $port);
    if ($conn->connect_error) {
        http_response_code(500);
        die('Koneksi database gagal. Silakan periksa layanan database.');
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}

/**
 * Normalizes role strings (handles legacy 'Sekrektaris Himpunan' DB ENUM spelling).
 */
function hmti_normalize_role(?string $role): string
{
    $r = strtolower(trim((string) $role));
    if ($r === 'ketua himpunan' || $r === 'kahim') {
        return 'kahim';
    }
    if (strpos($r, 'sekre') !== false || $r === 'sekhim') {
        return 'sekhim';
    }
    if ($r === 'anggota') {
        return 'anggota';
    }
    return '';
}

function hmti_homepage_for_role(?string $role): string
{
    $norm = hmti_normalize_role($role);
    if ($norm === 'kahim') {
        return 'homepage-kahim.html';
    }
    if ($norm === 'sekhim') {
        return 'homepage-sekhim.html';
    }
    return 'homepage.php';
}

/**
 * Ensures the user is logged in and optionally holds one of the allowed normalized roles.
 * Allowed role values: 'anggota', 'kahim', 'sekhim'.
 */
function hmti_require_auth(array $allowedRoles = []): void
{
    if (!isset($_SESSION['user_id'])) {
        hmti_set_flash('error', 'Silakan masuk terlebih dahulu untuk mengakses halaman tersebut.');
        header('Location: index.html');
        exit();
    }

    if (!empty($allowedRoles)) {
        $userRole = hmti_normalize_role($_SESSION['role'] ?? '');
        if (!in_array($userRole, $allowedRoles, true)) {
            hmti_set_flash('error', 'Anda tidak memiliki izin untuk mengakses halaman tersebut.');
            header('Location: ' . hmti_homepage_for_role($_SESSION['role'] ?? ''));
            exit();
        }
    }
}

/**
 * CSRF Token Helpers
 */
function hmti_csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function hmti_csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(hmti_csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

function hmti_verify_csrf(): bool
{
    $token = $_POST['csrf_token'] ?? '';
    return is_string($token) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Flash Message Helpers
 */
function hmti_set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function hmti_render_flash(): string
{
    if (empty($_SESSION['flash'])) {
        return '';
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    $type = ($flash['type'] === 'success') ? 'alert-success' : 'alert-error';
    $icon = ($flash['type'] === 'success') ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';
    $msg = htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8');

    return '<div class="alert-banner ' . $type . '" role="alert">'
        . '<i class="bi ' . $icon . '"></i>'
        . '<span>' . $msg . '</span>'
        . '</div>';
}

/**
 * Maps DB `surat.jenis_surat` to the corresponding page slug.
 */
function hmti_jenis_slug(string $jenis): string
{
    $j = strtolower(trim($jenis));
    if ($j === 'peminjaman') return 'peminjaman';
    if ($j === 'permohonan') return 'permohonan';
    return 'undangan';
}
