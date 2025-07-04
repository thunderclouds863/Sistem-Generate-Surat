<?php
// Konfigurasi database
$host = "localhost"; // Sesuaikan dengan host database Anda
$username = "root"; // Sesuaikan dengan username database Anda
$password = ""; // Sesuaikan dengan password database Anda
$dbname = "HMTI_System";

// Koneksi ke database
$conn = new mysqli($host, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Cek apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $role = "Anggota";

    // Validasi input
    if (empty($username) || empty($password) || empty($confirm_password) || empty($role)) {
        die("Semua kolom wajib diisi.");
    }

    if ($password !== $confirm_password) {
        die("Password dan konfirmasi password tidak cocok.");
    }

    // Hash password untuk keamanan
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Periksa apakah username sudah digunakan
    $check_user_query = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($check_user_query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("Username sudah terdaftar, silakan gunakan username lain.");
    }
    $stmt->close();

    // Simpan data ke tabel users dengan role default "anggota"
    $role = "anggota";
    $insert_query = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("sss", $username, $hashed_password, $role);

    if ($stmt->execute()) {
        // Redirect ke halaman index.html jika berhasil
        header("Location: index.html");
        exit;
    } else {
        echo "Terjadi kesalahan: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>
