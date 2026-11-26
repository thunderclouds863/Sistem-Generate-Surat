<?php
require_once __DIR__ . '/includes/bootstrap.php';
hmti_require_auth(['anggota']);
$conn = hmti_db();

// Query data dari tabel surat_permohonan dan status dari tabel surat
$sql = "SELECT sp.id, sp.nomor_surat, sp.bidang, s.status
        FROM surat_permohonan sp
        JOIN surat s ON sp.id = s.id
        ORDER BY sp.id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Surat Permohonan — HMTI UNDIP</title>
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
        <a href="homepage.php" class="brand-link">
          <img src="logo-undip.png" alt="Logo UNDIP dan HMTI">
          <div class="brand-meta">
            <strong>HMTI UNDIP</strong>
            <span>Sistem Administrasi Surat</span>
          </div>
        </a>
      </div>

      <div class="header-right">
        <a href="homepage.php" class="btn btn-outline btn-sm">
          <i class="bi bi-arrow-left"></i>
          <span>Kembali</span>
        </a>
      </div>
    </div>
  </header>

  <main class="app-main">
    <section class="hero-banner hero-compact" style="background-image: url('bg-surat-permohonan.png');">
      <div class="hero-inner">
        <span class="hero-eyebrow">Dokumen Administrasi · Anggota</span>
        <h1>Surat Permohonan</h1>
        <p class="hero-subtitle">Buat pengajuan baru, pantau tahapan verifikasi, dan unduh surat yang telah disetujui.</p>
      </div>
    </section>

    <div class="page-container">
      <?php echo hmti_render_flash(); ?>
      <div class="toolbar">
        <div class="search-box">
          <i class="bi bi-search"></i>
          <input type="text" id="search" placeholder="Cari nomor surat, bidang, atau status..." aria-label="Cari surat">
        </div>
        <a href="form-surat-permohonan.php" class="btn btn-primary">
          <i class="bi bi-plus-lg"></i>
          <span>Buat Surat Permohonan</span>
        </a>
      </div>

      <div class="table-card">
        <div class="table-responsive">
          <table class="data-table">
            <thead>
              <tr>
                <th>Nomor Surat</th>
                <th>Bidang</th>
                <th>Tahap Persetujuan</th>
                <th>Status &amp; Dokumen</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()):
                  $status = $row['status'];
                  $step1 = '';
                  $step2 = '';
                  $badgeClass = 'status-pending';
                  $badgeIcon = 'bi-hourglass-split';

                  if (stripos($status, 'Menunggu Persetujuan Sekre') !== false) {
                    $step1 = 'is-active';
                    $badgeClass = 'status-pending';
                  } elseif (stripos($status, 'Disetujui Sekre') !== false || $status === 'Menunggu Persetujuan Ketua Himpunan') {
                    $step1 = 'is-done';
                    $step2 = 'is-active';
                    $badgeClass = 'status-review';
                    $badgeIcon = 'bi-clock-history';
                  } elseif ($status === 'Disetujui Ketua Himpunan') {
                    $step1 = 'is-done';
                    $step2 = 'is-done';
                    $badgeClass = 'status-approved';
                    $badgeIcon = 'bi-check-circle-fill';
                  } elseif ($status === 'Rejected') {
                    $step1 = 'is-rejected';
                    $step2 = 'is-rejected';
                    $badgeClass = 'status-rejected';
                    $badgeIcon = 'bi-x-circle-fill';
                  }
                ?>
                  <tr>
                    <td><span class="doc-number"><?php echo htmlspecialchars($row['nomor_surat']); ?></span></td>
                    <td><span class="bidang-tag"><?php echo htmlspecialchars($row['bidang'] ?: '-'); ?></span></td>
                    <td>
                      <div class="stepper" aria-label="Tahap persetujuan surat">
                        <span class="step-node <?php echo $step1; ?>" title="Tahap 1: Sekretaris Himpunan">
                          <span class="step-dot">1</span> Sekhim
                        </span>
                        <span class="step-line" aria-hidden="true"></span>
                        <span class="step-node <?php echo $step2; ?>" title="Tahap 2: Ketua Himpunan">
                          <span class="step-dot">2</span> Kahim
                        </span>
                      </div>
                    </td>
                    <td>
                      <div class="status-cell">
                        <span class="status-badge <?php echo $badgeClass; ?>">
                          <i class="bi <?php echo $badgeIcon; ?>"></i>
                          <?php echo htmlspecialchars($status); ?>
                        </span>
                        <?php if ($status === 'Disetujui Ketua Himpunan'): ?>
                          <a href="generate_permohonan.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-download">
                            <i class="bi bi-download"></i> Unduh DOCX
                          </a>
                        <?php endif; ?>
                      </div>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr class="js-static-empty">
                  <td colspan="4" class="empty-state">
                    <i class="bi bi-file-earmark-text"></i>
                    Belum ada pengajuan surat permohonan.
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
        <a href="homepage.php"><i class="bi bi-house"></i> Beranda</a>
      </div>
    </div>
  </footer>

  <script src="assets/js/hmti.js"></script>
</body>

</html>
