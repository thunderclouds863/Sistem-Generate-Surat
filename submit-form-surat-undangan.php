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

// Retrieve data from POST and validate
$tanggal = $_POST['tanggal'] ?? null;
$bulan = $_POST['bulan'] ?? null;
$tahun = $_POST['tahun'] ?? null;
$nomor = $_POST['nomor'] ?? null;
$program_kerja = $_POST['program_kerja'] ?? null;
$bulan_romawi = $_POST['bulan_romawi'] ?? null;
$tahun_input = $_POST['tahun_input'] ?? null;
$jabatan_tamu = $_POST['jabatan_tamu'] ?? null;
$nama_kegiatan = $_POST['nama_kegiatan'] ?? null;
$nama_tamu = $_POST['nama_tamu'] ?? null;
$waktu_mulai = $_POST['waktu_mulai'] ?? null;
$waktu_selesai = $_POST['waktu_selesai'] ?? null;
$lokasi_tamu = $_POST['lokasi_tamu'] ?? null;
$tempat = $_POST['tempat'] ?? null;
$agenda = $_POST['agenda'] ?? null;
$bidang = $_POST['bidang'] ?? null;
$ketua_panitia = $_POST['ketuaPanitia'] ?? null;
$nim = $_POST['nim'] ?? null;

// Check required fields
if (
    !$tanggal || !$bulan || !$tahun || !$nomor || !$program_kerja || !$bulan_romawi || !$tahun_input ||
    !$jabatan_tamu || !$nama_kegiatan || !$nama_tamu || !$waktu_mulai || !$waktu_selesai || !$lokasi_tamu
) {
    die("Error: All fields are required.");
}

// Build nomor_surat
$nomor_surat = "{$nomor}/SUnd/{$program_kerja}/HMTI/{$bulan_romawi}/{$tahun_input}";

// Use prepared statements for both queries
// Insert into `surat` table
$stmt = $conn->prepare("INSERT INTO surat (jenis_surat) VALUES (?)");
$jenis_surat = "Undangan";
$stmt->bind_param("s", $jenis_surat);

if ($stmt->execute()) {
    $surat_id = $stmt->insert_id; // Get the last inserted ID
    $stmt->close();

    // Insert into `surat_undangan` table
    $stmt2 = $conn->prepare(
        "INSERT INTO surat_undangan
        (nomor_surat, nama_tamu, jabatan_tamu, nama_kegiatan, hari_tanggal, waktu_mulai, waktu_selesai, tempat, agenda, bidang_organisasi, nama_penanggung_jawab, nim_penanggung_jawab)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $hari_tanggal = "$tahun-$bulan-$tanggal";
    $stmt2->bind_param(
        "ssssssssssss",
        $nomor_surat, $nama_tamu, $jabatan_tamu, $nama_kegiatan, $hari_tanggal, $waktu_mulai, $waktu_selesai, $tempat, $agenda, $bidang, $ketua_panitia, $nim
    );

    if ($stmt2->execute()) {
        $stmt2->close();
        // Redirect to the next form page
        header("Location: next-form-surat-undangan.php?id=$surat_id");
        exit();
    } else {
        echo "Error: " . $stmt2->error;
    }
} else {
    echo "Error: " . $stmt->error;
}

$conn->close();
?>
