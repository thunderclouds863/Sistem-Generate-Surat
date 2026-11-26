<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "HMTI_System";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
  $surat_id = $_GET['id'];
} else {
  die("Invalid Surat ID.");
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Surat Undangan (Tahap 2) — HMTI UNDIP</title>
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
            <span>Formulir Surat Undangan</span>
          </div>
        </a>
      </div>

      <div class="header-right">
        <a href="form-surat-undangan.php" class="btn btn-outline btn-sm">
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
          <h1>Detail Kegiatan &amp; Penanggung Jawab</h1>
          <p>Lengkapi lokasi acara, agenda kegiatan, serta identitas ketua panitia.</p>
        </div>

        <div class="form-card-body">
          <form id="dataForm" action="submit-next-form-surat-undangan.php?id=<?php echo htmlspecialchars($surat_id); ?>" method="POST">
            <div class="form-section">
              <div class="form-section-title">Lokasi &amp; Agenda</div>
              <div class="form-grid-2">
                <div class="field">
                  <label for="tempat">Tempat Kegiatan</label>
                  <input class="input-control" type="text" id="tempat" name="tempat" placeholder="Contoh: Ruang Seminar Lt. 3">
                </div>
                <div class="field">
                  <label for="bidang">Bidang</label>
                  <input class="input-control" type="text" id="bidang" name="bidang" placeholder="Contoh: Keilmuan / PSDM">
                </div>
                <div class="field form-span-2">
                  <label for="agenda">Agenda</label>
                  <input class="input-control" type="text" id="agenda" name="agenda" class="agenda-input" placeholder="Ringkasan agenda kegiatan">
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
              <a href="form-surat-undangan.php" class="btn btn-outline">
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
