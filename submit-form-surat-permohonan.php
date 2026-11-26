<?php
require_once __DIR__ . '/includes/bootstrap.php';
hmti_require_auth(['anggota']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: form-surat-permohonan.php');
    exit();
}

if (!hmti_verify_csrf()) {
    hmti_set_flash('error', 'Sesi formulir tidak valid. Silakan coba lagi.');
    header('Location: form-surat-permohonan.php');
    exit();
}

$tanggal = (int) ($_POST['tanggal'] ?? 0);
$bulan = (int) ($_POST['bulan'] ?? 0);
$tahun = (int) ($_POST['tahun'] ?? 0);
$nomor = trim($_POST['nomor'] ?? '');
$program_kerja = trim($_POST['programKerja'] ?? '');
$bulan_romawi = trim($_POST['bulanRomawi'] ?? '');
$tahun_input = trim($_POST['tahunInput'] ?? '');
$tujuan_permohonan = trim($_POST['tujuan'] ?? '');
$nama_kegiatan = trim($_POST['kegiatan'] ?? '');
$instansi = trim($_POST['instansi'] ?? '');
$waktu_mulai = trim($_POST['waktuMulai'] ?? '');
$waktu_selesai = trim($_POST['waktuSelesai'] ?? '');
$lokasi_instansi = trim($_POST['lokasiTamu'] ?? '');

if (
    !checkdate($bulan, $tanggal, $tahun) || $nomor === '' || $program_kerja === '' || $bulan_romawi === '' || $tahun_input === '' ||
    $tujuan_permohonan === '' || $nama_kegiatan === '' || $instansi === '' || $waktu_mulai === '' || $waktu_selesai === '' || $lokasi_instansi === ''
) {
    hmti_set_flash('error', 'Semua kolom wajib diisi dengan format tanggal yang valid.');
    header('Location: form-surat-permohonan.php');
    exit();
}

$nomor_surat = "{$nomor}/SPerm/{$program_kerja}/HMTI/{$bulan_romawi}/{$tahun_input}";
$tanggal_sql = sprintf('%04d-%02d-%02d', $tahun, $bulan, $tanggal);

$conn = hmti_db();

$dup = $conn->prepare('SELECT id FROM surat_permohonan WHERE nomor_surat = ? LIMIT 1');
$dup->bind_param('s', $nomor_surat);
$dup->execute();
$dup->store_result();
if ($dup->num_rows > 0) {
    $dup->close();
    hmti_set_flash('error', "Nomor surat {$nomor_surat} sudah digunakan. Silakan gunakan nomor lain.");
    header('Location: form-surat-permohonan.php');
    exit();
}
$dup->close();

$conn->begin_transaction();
try {
    $stmt = $conn->prepare('INSERT INTO surat (jenis_surat, status) VALUES (?, ?)');
    $jenis_surat = 'Permohonan';
    $status = 'Pending';
    $stmt->bind_param('ss', $jenis_surat, $status);
    if (!$stmt->execute()) {
        throw new RuntimeException($stmt->error);
    }
    $surat_id = (int) $stmt->insert_id;
    $stmt->close();

    $stmt2 = $conn->prepare(
        'INSERT INTO surat_permohonan (id, nomor_surat, program_kerja, instansi, nama_tujuan_permohonan, lokasi_instansi, nama_kegiatan, tanggal, waktu_mulai, waktu_selesai)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt2->bind_param(
        'isssssssss',
        $surat_id, $nomor_surat, $program_kerja, $instansi, $tujuan_permohonan, $lokasi_instansi, $nama_kegiatan, $tanggal_sql, $waktu_mulai, $waktu_selesai
    );
    if (!$stmt2->execute()) {
        throw new RuntimeException($stmt2->error);
    }
    $stmt2->close();

    $conn->commit();
    header("Location: next-form-surat-permohonan.php?id={$surat_id}");
    exit();
} catch (Throwable $e) {
    $conn->rollback();
    hmti_set_flash('error', 'Gagal menyimpan data tahap 1. Pastikan nomor surat belum digunakan.');
    header('Location: form-surat-permohonan.php');
    exit();
}
