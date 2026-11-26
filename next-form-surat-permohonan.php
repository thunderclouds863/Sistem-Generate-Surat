<?php
require_once __DIR__ . '/includes/bootstrap.php';
hmti_require_auth(['anggota']);
$surat_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$surat_id || $surat_id <= 0) {
  hmti_set_flash('error', 'ID surat tidak valid.');
  header('Location: surat-permohonan.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Surat Permohonan (Tahap 2) — HMTI UNDIP</title>
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
            <span>Formulir Surat Permohonan</span>
          </div>
        </a>
      </div>

      <div class="header-right">
        <a href="form-surat-permohonan.php" class="btn btn-outline btn-sm">
          <i class="bi bi-arrow-left"></i>
          <span>Kembali</span>
        </a>
      </div>
    </div>
  </header>

  <main class="app-main">
    <div class="form-page-container">
      <div class="form-stepper-bar" aria-label="Progres pengisian formulir">
        <div class="form-step-pill is-complete">
          <span class="num"><i class="bi bi-check"></i></span>
          <span class="step-text">Informasi Utama</span>
        </div>
        <span class="form-step-connector" aria-hidden="true"></span>
        <div class="form-step-pill is-current">
          <span class="num">2</span>
          <span class="step-text">Detail &amp; Penanggung Jawab</span>
        </div>
      </div>

      <section class="form-card">
        <div class="form-card-header">
          <h1>Isi Permohonan &amp; Penanggung Jawab</h1>
          <p>Lengkapi lokasi kegiatan, isi permohonan, serta data ketua panitia.</p>
        </div>

        <div class="form-card-body">
          <?php echo hmti_render_flash(); ?>
          <form id="dataForm" action="submit-next-form-surat-permohonan.php?id=<?php echo htmlspecialchars((string) $surat_id); ?>" method="POST">
            <?php echo hmti_csrf_field(); ?>
            <div class="form-section">
              <div class="form-section-title">Lokasi &amp; Isi Permohonan</div>
              <div class="form-grid-2">
                <div class="field">
                  <label for="tempat">Tempat Kegiatan</label>
                  <input class="input-control" type="text" id="tempat" name="tempat" placeholder="Contoh: Auditorium Teknik Industri">
                </div>
                <div class="field">
                  <label for="bidang">Bidang</label>
                  <input class="input-control" type="text" id="bidang" name="bidang" placeholder="Contoh: Keilmuan / Humas">
                </div>
                <div class="field form-span-2">
                  <label for="isi">Isi Permohonan</label>
                  <input class="input-control" type="text" id="isi" name="isi" placeholder="Uraian singkat permohonan">
                </div>
              </div>
            </div>

            <div class="form-section">
              <div class="form-section-title">Penanggung Jawab</div>
              <div class="form-grid-2">
                <div class="field">
                  <label for="ketuaPanitia">Ketua Panitia</label>
                  <input class="input-control" type="text" id="ketuaPanitia" name="ketuaPanitia" placeholder="Nama lengkap ketua panitia">
                </div>
                <div class="field">
                  <label for="nim">NIM</label>
                  <input class="input-control" type="text" id="nim" name="nim" placeholder="Nomor Induk Mahasiswa">
                </div>
              </div>
            </div>

            <div class="form-actions">
              <a href="form-surat-permohonan.php" class="btn btn-outline">
                <i class="bi bi-arrow-left"></i> Kembali
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-check2-circle"></i>
                <span>Simpan Pengajuan</span>
              </button>
            </div>
          </form>
        </div>
      </section>
    </div>
  </main>

  <script src="assets/js/hmti.js"></script>
</body>

</html>
