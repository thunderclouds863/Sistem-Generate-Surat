<?php
require_once __DIR__ . '/includes/bootstrap.php';
hmti_require_auth(['sekhim']);
$conn = hmti_db();

$sql = "SELECT sp.id, sp.nomor_surat, s.status FROM surat_permohonan sp
        JOIN surat s ON sp.id = s.id
        ORDER BY sp.id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verifikasi Surat Permohonan (Sekhim) — HMTI UNDIP</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="assets/css/hmti.css">
</head>

<body class="app-page">
  <header class="app-header">
    <div class="app-header-inner">
      <div class="header-left">
        <a href="homepage-sekhim.html" class="brand-link">
          <img src="logo-undip.png" alt="Logo UNDIP dan HMTI">
          <div class="brand-meta">
            <strong>HMTI UNDIP</strong>
            <span>Panel Sekretaris Himpunan</span>
          </div>
        </a>
      </div>

      <div class="header-right">
        <span class="role-chip"><i class="bi bi-clipboard-check"></i> Sekretaris Himpunan</span>
        <a href="homepage-sekhim.html" class="btn btn-outline btn-sm">
          <i class="bi bi-arrow-left"></i>
          <span>Kembali</span>
        </a>
      </div>
    </div>
  </header>

  <main class="app-main">
    <section class="hero-banner hero-compact" style="background-image: url('bg-surat-permohonan.png');">
      <div class="hero-inner">
        <span class="hero-eyebrow">Verifikasi Tahap 1 · Sekretaris Himpunan</span>
        <h1>Surat Permohonan</h1>
        <p class="hero-subtitle">Periksa dokumen pengajuan surat permohonan sebelum diteruskan ke Ketua Himpunan.</p>
      </div>
    </section>

    <div class="page-container">
      <?php echo hmti_render_flash(); ?>
      <div class="toolbar">
        <div class="search-box">
          <i class="bi bi-search"></i>
          <input type="text" id="search" placeholder="Cari nomor surat atau status..." aria-label="Cari surat">
        </div>
      </div>

      <div class="table-card">
        <div class="table-responsive">
          <table class="data-table">
            <thead>
              <tr>
                <th>Nomor Surat</th>
                <th>Dokumen</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()):
                  $status = $row['status'];
                  $badgeClass = 'status-pending';
                  $badgeIcon = 'bi-hourglass-split';
                  if ($status === 'Disetujui Ketua Himpunan') {
                    $badgeClass = 'status-approved';
                    $badgeIcon = 'bi-check-circle-fill';
                  } elseif (stripos($status, 'Disetujui Sekre') !== false || $status === 'Menunggu Persetujuan Ketua Himpunan') {
                    $badgeClass = 'status-review';
                    $badgeIcon = 'bi-clock-history';
                  } elseif ($status === 'Rejected') {
                    $badgeClass = 'status-rejected';
                    $badgeIcon = 'bi-x-circle-fill';
                  }
                  $canReview = in_array($status, ['Pending', 'Menunggu Persetujuan Sekrektaris Himpunan', 'Menunggu Persetujuan Sekretaris Himpunan'], true);
                ?>
                  <tr>
                    <td><span class="doc-number"><?php echo htmlspecialchars($row['nomor_surat']); ?></span></td>
                    <td>
                      <a href="generate_permohonan.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-download">
                        <i class="bi bi-download"></i> Unduh DOCX
                      </a>
                    </td>
                    <td>
                      <span class="status-badge <?php echo $badgeClass; ?>">
                        <i class="bi <?php echo $badgeIcon; ?>"></i>
                        <?php echo htmlspecialchars($status); ?>
                      </span>
                    </td>
                    <td>
                      <?php if ($canReview): ?>
                        <div class="action-group">
                          <form action="update-status-sekhim.php" method="POST">
                            <?php echo hmti_csrf_field(); ?>
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                            <button type="submit" name="approve" class="btn btn-approve btn-sm">
                              <i class="bi bi-check-lg"></i> Setujui
                            </button>
                          </form>
                          <form action="update-status-sekhim.php" method="POST">
                            <?php echo hmti_csrf_field(); ?>
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                            <button type="submit" name="reject" class="btn btn-reject btn-sm">
                              <i class="bi bi-x-lg"></i> Tolak
                            </button>
                          </form>
                        </div>
                      <?php elseif ($status === 'Rejected'): ?>
                        <span class="muted-label">Ditolak</span>
                      <?php else: ?>
                        <span class="muted-label">Telah diverifikasi</span>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr class="js-static-empty">
                  <td colspan="4" class="empty-state">
                    <i class="bi bi-inbox"></i>
                    Belum ada data surat permohonan.
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>

  <footer class="app-footer">
    <div class="app-footer-inner">
      <span>Himpunan Mahasiswa Teknik Industri · Universitas Diponegoro</span>
      <div class="app-footer-links">
        <a href="homepage-sekhim.html"><i class="bi bi-house"></i> Beranda Sekhim</a>
      </div>
    </div>
  </footer>

  <script src="assets/js/hmti.js"></script>
</body>

</html>
