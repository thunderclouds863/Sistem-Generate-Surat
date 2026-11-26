<?php
require_once __DIR__ . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit();
}

$username = trim($_POST['username'] ?? '');
$password = (string) ($_POST['password'] ?? '');
$confirm_password = (string) ($_POST['confirm_password'] ?? '');

if ($username === '' || $password === '' || $confirm_password === '') {
    hmti_set_flash('error', 'Semua kolom wajib diisi.');
    header('Location: register.php');
    exit();
}

if (strlen($username) < 3 || strlen($username) > 50) {
    hmti_set_flash('error', 'Username harus terdiri dari 3 hingga 50 karakter.');
    header('Location: register.php');
    exit();
}

if (strlen($password) < 6) {
    hmti_set_flash('error', 'Password minimal 6 karakter.');
    header('Location: register.php');
    exit();
}

if ($password !== $confirm_password) {
    hmti_set_flash('error', 'Password dan konfirmasi password tidak cocok.');
    header('Location: register.php');
    exit();
}

$conn = hmti_db();

$check = $conn->prepare('SELECT id FROM users WHERE username = ? LIMIT 1');
$check->bind_param('s', $username);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    $check->close();
    hmti_set_flash('error', 'Username sudah terdaftar, silakan gunakan username lain.');
    header('Location: register.php');
    exit();
}
$check->close();

$hashed_password = password_hash($password, PASSWORD_BCRYPT);
$role = 'Anggota';

$insert = $conn->prepare('INSERT INTO users (username, password, role) VALUES (?, ?, ?)');
$insert->bind_param('sss', $username, $hashed_password, $role);

if ($insert->execute()) {
    $insert->close();
    hmti_set_flash('success', 'Registrasi berhasil! Silakan masuk dengan akun baru Anda.');
    header('Location: index.html');
    exit();
}

$insert->close();
hmti_set_flash('error', 'Gagal mendaftarkan akun. Silakan coba lagi.');
header('Location: register.php');
exit();
