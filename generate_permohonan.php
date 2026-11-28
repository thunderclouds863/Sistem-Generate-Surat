<?php
require_once __DIR__ . '/includes/bootstrap.php';
hmti_require_auth(['anggota', 'kahim', 'sekhim']);

$surat_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$surat_id || $surat_id <= 0) {
    http_response_code(400);
    die('ID surat tidak valid.');
}

$conn = hmti_db();
$stmt = $conn->prepare('SELECT status FROM surat WHERE id = ? AND jenis_surat = "Permohonan" LIMIT 1');
$stmt->bind_param('i', $surat_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    http_response_code(404);
    die('Surat permohonan tidak ditemukan.');
}

$role = hmti_normalize_role($_SESSION['role'] ?? '');
if ($role === 'anggota' && $row['status'] !== 'Disetujui Ketua Himpunan') {
    http_response_code(403);
    die('Dokumen hanya dapat diunduh setelah disetujui Ketua Himpunan.');
}

$cmd = 'python ' . escapeshellarg(__DIR__ . '/generate_permohonan.py') . ' ' . escapeshellarg((string) $surat_id);
$output = shell_exec($cmd);
$rawPath = trim((string) $output);
$candidate = (strpos($rawPath, '/') === 0) ? $rawPath : (__DIR__ . '/' . $rawPath);
$fullPath = realpath($candidate);
$baseDir = realpath(__DIR__);
$tmpBase = realpath('/tmp/hmti_surat');
$allowed = $fullPath && (
    ($baseDir && strpos($fullPath, $baseDir) === 0) ||
    ($tmpBase && strpos($fullPath, $tmpBase) === 0)
);

if (!$allowed || !is_file($fullPath)) {
    http_response_code(500);
    die('Terjadi kesalahan saat menghasilkan dokumen surat permohonan.');
}

if (function_exists('demo_blob_upload')) {
    $blobUrl = demo_blob_upload(
        $fullPath,
        'generate-surat/permohonan/' . basename($fullPath),
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    );
    if ($blobUrl) {
        header('X-Document-Archive-Url: ' . $blobUrl);
    }
}

header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename="' . basename($fullPath) . '"');
header('Content-Length: ' . filesize($fullPath));
readfile($fullPath);
if ($tmpBase && strpos($fullPath, $tmpBase) === 0) {
    @unlink($fullPath);
}
exit();
