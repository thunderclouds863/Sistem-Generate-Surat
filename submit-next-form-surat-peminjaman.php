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

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $surat_id = $_GET['id'];
} else {
    die("Invalid Surat ID.");
}


// Retrieve data from POST (Second form)
$tempat = $_POST['tempat'] ?? null;
$isi_peminjaman = $_POST['isi'] ?? null;
$bidang = $_POST['bidang'] ?? null;
$ketua_panitia = $_POST['ketuaPanitia'] ?? null;
$jabatanketua_panitia = $_POST['jabatanketuaPanitia'] ?? null;
$nim = $_POST['nim'] ?? null;
$nohp = $_POST['nohp'] ?? null;


if (!$tempat || !$isi_peminjaman || !$bidang || !$ketua_panitia || !$jabatanketua_panitia || !$nim) {
    die("Error: All fields are required.");
}

// Update the surat_undangan table with the additional details using prepared statement
$stmt = $conn->prepare(
    "UPDATE surat_peminjaman
     SET tempat = ?, isi_peminjaman = ?, bidang = ?, nama_penanggung_jawab = ?, nim_penanggung_jawab = ?, jabatanketua_panitia=?
     WHERE id = ?"
);

$stmt->bind_param("ssssssi", $tempat, $isi_peminjaman, $bidang, $ketua_panitia, $nim, $jabatanketua_panitia, $surat_id);


if ($stmt->execute()) {
    $stmt->close();
    // Redirect to surat-undangan.php
    header("Location: surat-peminjaman.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$conn->close();
?>
