<?php
require_once __DIR__ . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login_role.php');
    exit();
}

$selectedRole = hmti_normalize_role($_POST['role'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = (string) ($_POST['password'] ?? '');

if ($username === '' || $password === '' || !in_array($selectedRole, ['kahim', 'sekhim'], true)) {
    hmti_set_flash('error', 'Jabatan, username, dan password wajib diisi.');
    header('Location: login_role.php');
    exit();
}

$conn = hmti_db();
$stmt = $conn->prepare('SELECT id, username, password, role FROM users WHERE username = ? LIMIT 1');
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $stmt->close();

    $dbRole = hmti_normalize_role($user['role']);
    // Support hashed passwords as well as legacy seeded plaintext passwords in hmti_system.sql
    $passwordMatches = password_verify($password, $user['password']) || hash_equals($user['password'], $password);

    if ($passwordMatches && $dbRole === $selectedRole) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        header('Location: ' . hmti_homepage_for_role($user['role']));
        exit();
    }
} else {
    $stmt->close();
}

hmti_set_flash('error', 'Kombinasi jabatan, username, atau password tidak sesuai.');
header('Location: login_role.php');
exit();
