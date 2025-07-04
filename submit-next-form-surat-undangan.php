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
$agenda = $_POST['agenda'] ?? null;
$bidang = $_POST['bidang'] ?? null;
$ketua_panitia = $_POST['ketuaPanitia'] ?? null;

$nim = $_POST['nim'] ?? null;


if (!$tempat || !$agenda || !$bidang || !$ketua_panitia || !$nim) {
    die("Error: All fields are required.");
}

// Update the surat_undangan table with the additional details using prepared statement
$stmt = $conn->prepare(
    "UPDATE surat_undangan
     SET tempat = ?, agenda = ?, bidang_organisasi = ?, nama_penanggung_jawab = ?, nim_penanggung_jawab = ?
     WHERE id = ?"
);

$stmt->bind_param("sssssi", $tempat, $agenda, $bidang, $ketua_panitia, $nim, $surat_id);


if ($stmt->execute()) {
    $stmt->close();
    // Redirect to surat-undangan.php
    header("Location: surat-undangan.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$conn->close();
?>
