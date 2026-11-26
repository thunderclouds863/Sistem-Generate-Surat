<?php
require_once __DIR__ . '/includes/bootstrap.php';
hmti_require_auth(['anggota']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: form-surat-undangan.php');
    exit();
}

if (!hmti_verify_csrf()) {
    hmti_set_flash('error', 'Sesi formulir tidak valid. Silakan coba lagi.');
    header('Location: form-surat-undangan.php');
    exit();
}

$tanggal = (int) ($_POST['tanggal'] ?? 0);
$bulan = (int) ($_POST['bulan'] ?? 0);
$tahun = (int) ($_POST['tahun'] ?? 0);
$nomor = trim($_POST['nomor'] ?? '');
$program_kerja = trim($_POST['program_kerja'] ?? '');
$bulan_romawi = trim($_POST['bulan_romawi'] ?? '');
$tahun_input = trim($_POST['tahun_input'] ?? '');
$jabatan_tamu = trim($_POST['jabatan_tamu'] ?? '');
$nama_kegiatan = trim($_POST['nama_kegiatan'] ?? '');
$nama_tamu = trim($_POST['nama_tamu'] ?? '');
$waktu_mulai = trim($_POST['waktu_mulai'] ?? '');
$waktu_selesai = trim($_POST['waktu_selesai'] ?? '');
$lokasi_tamu = trim($_POST['lokasi_tamu'] ?? '');

if (
    !checkdate($bulan, $tanggal, $tahun) || $nomor === '' || $program_kerja === '' || $bulan_romawi === '' || $tahun_input === '' ||
    $jabatan_tamu === '' || $nama_kegiatan === '' || $nama_tamu === '' || $waktu_mulai === '' || $waktu_selesai === '' || $lokasi_tamu === ''
) {
    hmti_set_flash('error', 'Semua kolom wajib diisi dengan format tanggal yang valid.');
    header('Location: form-surat-undangan.php');
    exit();
}

$nomor_surat = "{$nomor}/SUnd/{$program_kerja}/HMTI/{$bulan_romawi}/{$tahun_input}";
$hari_tanggal = sprintf('%04d-%02d-%02d', $tahun, $bulan, $tanggal);

$conn = hmti_db();

// Check duplicate nomor_surat before inserting
$dup = $conn->prepare('SELECT id FROM surat_undangan WHERE nomor_surat = ? LIMIT 1');
$dup->bind_param('s', $nomor_surat);
$dup->execute();
$dup->store_result();
if ($dup->num_rows > 0) {
    $dup->close();
    hmti_set_flash('error', "Nomor surat {$nomor_surat} sudah digunakan. Silakan gunakan nomor lain.");
    header('Location: form-surat-undangan.php');
    exit();
}
$dup->close();

$conn->begin_transaction();
try {
    $stmt = $conn->prepare('INSERT INTO surat (jenis_surat, status) VALUES (?, ?)');
    $jenis_surat = 'Undangan';
    $status = 'Pending';
    $stmt->bind_param('ss', $jenis_surat, $status);
    if (!$stmt->execute()) {
        throw new RuntimeException($stmt->error);
    }
    $surat_id = (int) $stmt->insert_id;
    $stmt->close();

    $stmt2 = $conn->prepare(
        'INSERT INTO surat_undangan (id, nomor_surat, nama_tamu, jabatan_tamu, nama_kegiatan, hari_tanggal, waktu_mulai, waktu_selesai, tempat)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt2->bind_param(
        'issssssss',
        $surat_id, $nomor_surat, $nama_tamu, $jabatan_tamu, $nama_kegiatan, $hari_tanggal, $waktu_mulai, $waktu_selesai, $lokasi_tamu
    );
    if (!$stmt2->execute()) {
        throw new RuntimeException($stmt2->error);
    }
    $stmt2->close();

    $conn->commit();
    header("Location: next-form-surat-undangan.php?id={$surat_id}");
    exit();
} catch (Throwable $e) {
    $conn->rollback();
    hmti_set_flash('error', 'Gagal menyimpan data tahap 1. Pastikan nomor surat belum digunakan.');
    header('Location: form-surat-undangan.php');
    exit();
}
