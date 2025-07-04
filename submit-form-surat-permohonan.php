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
$program_kerja = $_POST['programKerja'] ?? null;
$bulan_romawi = $_POST['bulanRomawi'] ?? null;
$tahun_input = $_POST['tahunInput'] ?? null;
$tujuan_permohonan = $_POST['tujuan'] ?? null;
$nama_kegiatan = $_POST['kegiatan'] ?? null;
$instansi = $_POST['instansi'] ?? null;
$waktu_mulai = $_POST['waktuMulai'] ?? null;
$waktu_selesai = $_POST['waktuSelesai'] ?? null;
$lokasi_instansi = $_POST['lokasiTamu'] ?? null;
$tempat = $_POST['tempat'] ?? null;
$isi_permohonan = $_POST['isi'] ?? null;
$bidang = $_POST['bidang'] ?? null;
$ketua_panitia = $_POST['ketuaPanitia'] ?? null;
$nim = $_POST['nim'] ?? null;

// Check required fields
if (
  !$tanggal || !$bulan || !$tahun || !$nomor || !$program_kerja || !$bulan_romawi || !$tahun_input ||
  !$tujuan_permohonan || !$nama_kegiatan || !$instansi || !$waktu_mulai || !$waktu_selesai || !$lokasi_instansi
) {
  die("Error: All fields are required.");
}

// Build nomor_surat
$nomor_surat = "{$nomor}/SUnd/{$program_kerja}/HMTI/{$bulan_romawi}/{$tahun_input}";

// Use prepared statements for both queries
// Insert into `surat` table
$stmt = $conn->prepare("INSERT INTO surat (jenis_surat) VALUES (?)");
$jenis_surat = "Permohonan";
$stmt->bind_param("s", $jenis_surat);

if ($stmt->execute()) {
    $surat_id = $stmt->insert_id; // Get the last inserted ID
    $stmt->close();

    // Insert into `Permohonan` table
    $stmt2 = $conn->prepare(
        "INSERT INTO surat_permohonan
        (nomor_surat, program_kerja, instansi, nama_tujuan_permohonan, lokasi_instansi, nama_kegiatan, tanggal, waktu_mulai, waktu_selesai, tempat, isi_permohonan, bidang, nama_penanggung_jawab, nim_penanggung_jawab)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)"
    );
    $tanggal = "$tahun-$bulan-$tanggal";
    $stmt2->bind_param(
        "ssssssssssssss",
        $nomor_surat, $program_kerja, $instansi, $tujuan_permohonan, $lokasi_instansi, $nama_kegiatan, $tanggal, $waktu_mulai, $waktu_selesai, $tempat, $isi_permohonan, $bidang, $ketua_panitia, $nim
    );

    if ($stmt2->execute()) {
        $stmt2->close();
        // Redirect to the next form page
        header("Location: next-form-surat-permohonan.php?id=$surat_id");
        exit();
    } else {
        echo "Error: " . $stmt2->error;
    }
} else {
    echo "Error: " . $stmt->error;
}

$conn->close();
?>
