<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "HMTI_System";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the database
$surat_id = $_GET['id'];  // ID surat yang dipilih

if ($surat_id) {
    // Menjalankan Python script dengan ID surat yang dipilih
    $command = escapeshellcmd("python generate_peminjaman.py $surat_id");
    $output = shell_exec($command);

    // Cek apakah file berhasil dihasilkan
    if ($output) {
        // Parse output jika perlu (misalnya, ambil file path)
        $output = trim($output);  // Pastikan output tidak mengandung karakter ekstra seperti newlines

        // Redirect ke file hasil surat
        header("Location: $output");
        exit();
    } else {
        echo "Terjadi kesalahan saat menghasilkan surat.";
    }
} else {
    echo "ID surat tidak ditemukan.";
}
?>
