<?php
require_once __DIR__ . '/includes/bootstrap.php';
hmti_require_auth(['kahim']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: homepage-kahim.html');
    exit();
}

if (!hmti_verify_csrf()) {
    hmti_set_flash('error', 'Sesi formulir tidak valid. Silakan muat ulang halaman.');
    header('Location: homepage-kahim.html');
    exit();
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id <= 0) {
    hmti_set_flash('error', 'ID surat tidak valid.');
    header('Location: homepage-kahim.html');
    exit();
}

$newStatus = null;
if (isset($_POST['approve'])) {
    $newStatus = 'Disetujui Ketua Himpunan';
} elseif (isset($_POST['reject'])) {
    $newStatus = 'Rejected';
}

if ($newStatus === null) {
    hmti_set_flash('error', 'Aksi tidak dikenali.');
    header('Location: homepage-kahim.html');
    exit();
}

$conn = hmti_db();
$check = $conn->prepare('SELECT jenis_surat, status FROM surat WHERE id = ? LIMIT 1');
$check->bind_param('i', $id);
$check->execute();
$row = $check->get_result()->fetch_assoc();
$check->close();

if (!$row) {
    hmti_set_flash('error', 'Data surat tidak ditemukan.');
    header('Location: homepage-kahim.html');
    exit();
}

$redirectPage = 'surat-' . hmti_jenis_slug($row['jenis_surat']) . '-kahim.php';
$allowedCurrent = ['Disetujui Sekrektaris Himpunan', 'Disetujui Sekretaris Himpunan', 'Menunggu Persetujuan Ketua Himpunan'];

if (!in_array($row['status'], $allowedCurrent, true)) {
    hmti_set_flash('error', 'Surat belum diverifikasi Sekretaris Himpunan atau sudah diproses.');
    header("Location: {$redirectPage}");
    exit();
}

$stmt = $conn->prepare('UPDATE surat SET status = ? WHERE id = ?');
$stmt->bind_param('si', $newStatus, $id);

if ($stmt->execute()) {
    $stmt->close();
    $label = ($newStatus === 'Rejected') ? 'Surat telah ditolak.' : 'Surat berhasil disahkan oleh Ketua Himpunan.';
    hmti_set_flash('success', $label);
} else {
    $stmt->close();
    hmti_set_flash('error', 'Gagal memperbarui status surat.');
}

header("Location: {$redirectPage}");
exit();
