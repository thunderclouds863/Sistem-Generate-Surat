<?php
require_once __DIR__ . '/includes/bootstrap.php';
hmti_require_auth(['sekhim']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: homepage-sekhim.html');
    exit();
}

if (!hmti_verify_csrf()) {
    hmti_set_flash('error', 'Sesi formulir tidak valid. Silakan muat ulang halaman.');
    header('Location: homepage-sekhim.html');
    exit();
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id <= 0) {
    hmti_set_flash('error', 'ID surat tidak valid.');
    header('Location: homepage-sekhim.html');
    exit();
}

$newStatus = null;
if (isset($_POST['approve'])) {
    $newStatus = 'Disetujui Sekrektaris Himpunan';
} elseif (isset($_POST['reject'])) {
    $newStatus = 'Rejected';
}

if ($newStatus === null) {
    hmti_set_flash('error', 'Aksi tidak dikenali.');
    header('Location: homepage-sekhim.html');
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
    header('Location: homepage-sekhim.html');
    exit();
}

$redirectPage = 'surat-' . hmti_jenis_slug($row['jenis_surat']) . '-sekhim.php';
$allowedCurrent = ['Pending', 'Menunggu Persetujuan Sekrektaris Himpunan', 'Menunggu Persetujuan Sekretaris Himpunan'];

if (!in_array($row['status'], $allowedCurrent, true)) {
    hmti_set_flash('error', 'Surat ini sudah diproses sebelumnya.');
    header("Location: {$redirectPage}");
    exit();
}

$stmt = $conn->prepare('UPDATE surat SET status = ? WHERE id = ?');
$stmt->bind_param('si', $newStatus, $id);

if ($stmt->execute()) {
    $stmt->close();
    $label = ($newStatus === 'Rejected') ? 'Surat telah ditolak.' : 'Surat diverifikasi dan diteruskan ke Ketua Himpunan.';
    hmti_set_flash('success', $label);
} else {
    $stmt->close();
    hmti_set_flash('error', 'Gagal memperbarui status surat.');
}

header("Location: {$redirectPage}");
exit();
