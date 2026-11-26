<?php
require_once __DIR__ . '/includes/bootstrap.php';
hmti_require_auth(['anggota']);
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dokumen Administrasi — HMTI UNDIP</title>
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
        <span class="role-chip">
          <i class="bi bi-person-badge"></i>
          Anggota · <?php echo htmlspecialchars($username); ?>
        </span>
        <a href="logout.php" class="btn btn-navy btn-sm">
          <i class="bi bi-box-arrow-right"></i>
          <span>Keluar</span>
        </a>
      </div>
    </div>
  </header>

  <main class="app-main">
    <section class="hero-banner" style="background-image: url('teknik-industri2.png');">
      <div class="hero-inner">
        <span class="hero-eyebrow">Portal Anggota HMTI</span>
        <h1>Dokumen Administrasi</h1>
        <p class="hero-subtitle">Pilih jenis surat untuk membuat pengajuan baru, memantau status persetujuan, dan mengunduh dokumen resmi.</p>
      </div>
    </section>

    <div class="page-container">
      <div class="section-header">
        <div>
          <h2>Pilih Jenis Surat</h2>
          <p>Seluruh dokumen melalui verifikasi Sekretaris dan Ketua Himpunan.</p>
        </div>
      </div>

      <div class="portal-grid">
        <a href="surat-undangan.php" class="portal-card">
          <div class="portal-card-media">
            <span class="portal-card-badge"><i class="bi bi-envelope-paper"></i></span>
            <img src="surat-undangan.png" alt="Surat Undangan">
          </div>
          <div class="portal-card-body">
            <h3>Surat Undangan</h3>
            <p>Undangan resmi kegiatan, rapat, dan acara HMTI bagi tamu maupun instansi.</p>
            <div class="portal-card-action">
              <span>Buka Daftar Surat</span>
              <i class="bi bi-arrow-right"></i>
            </div>
          </div>
        </a>

        <a href="surat-permohonan.php" class="portal-card">
          <div class="portal-card-media">
            <span class="portal-card-badge"><i class="bi bi-file-earmark-text"></i></span>
            <img src="surat-permohonan.png" alt="Surat Permohonan">
          </div>
          <div class="portal-card-body">
            <h3>Surat Permohonan</h3>
            <p>Pengajuan permohonan kerja sama, pembicara, izin kegiatan, dan dukungan program.</p>
            <div class="portal-card-action">
              <span>Buka Daftar Surat</span>
              <i class="bi bi-arrow-right"></i>
            </div>
          </div>
        </a>

        <a href="surat-peminjaman.php" class="portal-card">
          <div class="portal-card-media">
            <span class="portal-card-badge"><i class="bi bi-box-seam"></i></span>
            <img src="surat-peminjaman.png" alt="Surat Peminjaman">
          </div>
          <div class="portal-card-body">
            <h3>Surat Peminjaman</h3>
            <p>Pengajuan peminjaman ruangan, fasilitas, serta perlengkapan kegiatan himpunan.</p>
            <div class="portal-card-action">
              <span>Buka Daftar Surat</span>
              <i class="bi bi-arrow-right"></i>
            </div>
          </div>
        </a>
      </div>
    </div>
  </main>

  <footer class="app-footer">
    <div class="app-footer-inner">
      <span>Himpunan Mahasiswa Teknik Industri · Universitas Diponegoro</span>
      <div class="app-footer-links">
        <a href="https://hmti.kemahasiswaan.undip.ac.id" target="_blank" rel="noopener noreferrer">
          <i class="bi bi-globe2"></i> hmti.kemahasiswaan.undip.ac.id
        </a>
        <a href="mailto:hmtindipsemarang@gmail.com">
          <i class="bi bi-envelope"></i> hmtindipsemarang@gmail.com
        </a>
      </div>
    </div>
  </footer>
</body>

</html>
