<?php
session_start(); // Memulai session

// Konfigurasi koneksi database
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "HMTI_System";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Periksa koneksi database
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Periksa apakah form login sudah di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validasi input tidak kosong
    if (empty($username) || empty($password)) {
        echo "<script>alert('Username dan Password harus diisi!'); window.location.href='index.html';</script>";
        exit();
    }

    // Query untuk mencari user berdasarkan username
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Periksa apakah user ditemukan
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Validasi password menggunakan password_verify
        if (password_verify($password, $user['password'])) {
            // Simpan data pengguna ke session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

                header("Location: homepage.php");

            exit();
        } else {
            echo "<script>alert('Password salah!'); window.location.href='index.html';</script>";
        }
    } else {
        echo "<script>alert('Username tidak ditemukan!'); window.location.href='index.html';</script>";
    }

    // Tutup statement
    $stmt->close();
}

// Tutup koneksi database
$conn->close();
?>
