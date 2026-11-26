<?php
require_once __DIR__ . '/includes/bootstrap.php';
hmti_require_auth(['anggota']);

$surat_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$surat_id || $surat_id <= 0) {
    hmti_set_flash('error', 'ID surat tidak valid.');
    header('Location: surat-peminjaman.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !hmti_verify_csrf()) {
    hmti_set_flash('error', 'Sesi formulir tidak valid. Silakan coba lagi.');
    header("Location: next-form-surat-peminjaman.php?id={$surat_id}");
    exit();
}

$tempat = trim($_POST['tempat'] ?? '');
$isi_peminjaman = trim($_POST['isi'] ?? '');
$bidang = trim($_POST['bidang'] ?? '');
$ketua_panitia = trim($_POST['ketuaPanitia'] ?? '');
$jabatanketua_panitia = trim($_POST['jabatanketuaPanitia'] ?? '');
$nim = trim($_POST['nim'] ?? '');
$nohp = trim($_POST['nohp'] ?? '');

if ($tempat === '' || $isi_peminjaman === '' || $bidang === '' || $ketua_panitia === '' || $jabatanketua_panitia === '' || $nim === '') {
    hmti_set_flash('error', 'Semua kolom detail wajib diisi.');
    header("Location: next-form-surat-peminjaman.php?id={$surat_id}");
    exit();
}

$conn = hmti_db();
$stmt = $conn->prepare(
    'UPDATE surat_peminjaman
     SET tempat = ?, isi_peminjaman = ?, bidang = ?, nama_penanggung_jawab = ?, nim_penanggung_jawab = ?, jabatanketua_panitia = ?, nomor_hp = ?
     WHERE id = ?'
);
$stmt->bind_param('sssssssi', $tempat, $isi_peminjaman, $bidang, $ketua_panitia, $nim, $jabatanketua_panitia, $nohp, $surat_id);

if ($stmt->execute()) {
    $stmt->close();
    hmti_set_flash('success', 'Pengajuan Surat Peminjaman berhasil disimpan dan menunggu verifikasi Sekretaris Himpunan.');
    header('Location: surat-peminjaman.php');
    exit();
}

$stmt->close();
hmti_set_flash('error', 'Gagal menyimpan detail surat peminjaman.');
header("Location: next-form-surat-peminjaman.php?id={$surat_id}");
exit();
