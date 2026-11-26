<?php
require_once __DIR__ . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit();
}

$username = trim($_POST['username'] ?? '');
$password = (string) ($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    hmti_set_flash('error', 'Username dan password harus diisi.');
    header('Location: index.html');
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

    $normRole = hmti_normalize_role($user['role']);
    if ($normRole !== 'anggota') {
        hmti_set_flash('error', 'Akun pengurus HMTI silakan masuk melalui tab Pengurus HMTI.');
        header('Location: login_role.php');
        exit();
    }

    if (password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        header('Location: homepage.php');
        exit();
    }
} else {
    $stmt->close();
}

hmti_set_flash('error', 'Username atau password salah.');
header('Location: index.html');
exit();
