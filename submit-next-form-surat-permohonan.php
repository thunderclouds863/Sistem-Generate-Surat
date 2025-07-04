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

// Retrieve the Surat ID from the URL and validate it
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $surat_id = $_GET['id'];
} else {
    die("Invalid Surat ID.");
}

// Retrieve data from POST (Second form)
$tempat = $_POST['tempat'] ?? null;
$isi_permohonan = $_POST['isi'] ?? null;
$bidang = $_POST['bidang'] ?? null;
$ketua_panitia = $_POST['ketuaPanitia'] ?? null;
$nim = $_POST['nim'] ?? null;


if (!$tempat || !$isi_permohonan || !$bidang || !$ketua_panitia || !$nim) {
    die("Error: All fields are required.");
}

// Update the surat_undangan table with the additional details using prepared statement
$stmt = $conn->prepare(
    "UPDATE surat_permohonan
     SET tempat = ?, isi_permohonan = ?, bidang = ?, nama_penanggung_jawab = ?, nim_penanggung_jawab = ?
     WHERE id = ?"
);

$stmt->bind_param("sssssi", $tempat, $isi_permohonan, $bidang, $ketua_panitia, $nim, $surat_id);


if ($stmt->execute()) {
    $stmt->close();
    // Redirect to surat-undangan.php
    header("Location: surat-permohonan.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$conn->close();
?>
