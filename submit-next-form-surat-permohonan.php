<?php
require_once __DIR__ . '/includes/bootstrap.php';
hmti_require_auth(['anggota']);

$surat_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$surat_id || $surat_id <= 0) {
    hmti_set_flash('error', 'ID surat tidak valid.');
    header('Location: surat-permohonan.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !hmti_verify_csrf()) {
    hmti_set_flash('error', 'Sesi formulir tidak valid. Silakan coba lagi.');
    header("Location: next-form-surat-permohonan.php?id={$surat_id}");
    exit();
}

$tempat = trim($_POST['tempat'] ?? '');
$isi_permohonan = trim($_POST['isi'] ?? '');
$bidang = trim($_POST['bidang'] ?? '');
$ketua_panitia = trim($_POST['ketuaPanitia'] ?? '');
$nim = trim($_POST['nim'] ?? '');

if ($tempat === '' || $isi_permohonan === '' || $bidang === '' || $ketua_panitia === '' || $nim === '') {
    hmti_set_flash('error', 'Semua kolom detail wajib diisi.');
    header("Location: next-form-surat-permohonan.php?id={$surat_id}");
    exit();
}

$conn = hmti_db();
$stmt = $conn->prepare(
    'UPDATE surat_permohonan
     SET tempat = ?, isi_permohonan = ?, bidang = ?, nama_penanggung_jawab = ?, nim_penanggung_jawab = ?
     WHERE id = ?'
);
$stmt->bind_param('sssssi', $tempat, $isi_permohonan, $bidang, $ketua_panitia, $nim, $surat_id);

if ($stmt->execute()) {
    $stmt->close();
    hmti_set_flash('success', 'Pengajuan Surat Permohonan berhasil disimpan dan menunggu verifikasi Sekretaris Himpunan.');
    header('Location: surat-permohonan.php');
    exit();
}

$stmt->close();
hmti_set_flash('error', 'Gagal menyimpan detail surat permohonan.');
header("Location: next-form-surat-permohonan.php?id={$surat_id}");
exit();
