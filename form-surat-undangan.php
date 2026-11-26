<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buat Surat Undangan (Tahap 1) — HMTI UNDIP</title>
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
        <a href="surat-undangan.php" class="btn btn-outline btn-sm">
          <i class="bi bi-arrow-left"></i>
          <span>Kembali</span>
        </a>
      </div>
    </div>
  </header>

  <main class="app-main">
    <div class="form-page-container">
      <div class="form-stepper-bar" aria-label="Progres pengisian formulir">
        <div class="form-step-pill is-current">
          <span class="num">1</span>
          <span class="step-text">Informasi Utama</span>
        </div>
        <span class="form-step-connector" aria-hidden="true"></span>
        <div class="form-step-pill">
          <span class="num">2</span>
          <span class="step-text">Detail &amp; Penanggung Jawab</span>
        </div>
      </div>

      <section class="form-card">
        <div class="form-card-header">
          <h1>Buat Surat Undangan</h1>
          <p>Lengkapi tanggal kegiatan, penomoran surat, serta informasi tamu undangan.</p>
        </div>

        <div class="form-card-body">
          <form id="dataForm" action="submit-form-surat-undangan.php" method="POST">
            <div class="form-section">
              <div class="form-section-title">Tanggal Kegiatan</div>
              <div class="form-grid-3">
                <div class="field">
                  <label for="tanggal">Tanggal</label>
                  <select class="select-control" id="tanggal" name="tanggal" required>
                    <option value="">Pilih tanggal</option>
                  </select>
                </div>
                <div class="field">
                  <label for="bulan">Bulan</label>
                  <select class="select-control" id="bulan" name="bulan" required>
                    <option value="">Pilih bulan</option>
                  </select>
                </div>
                <div class="field">
                  <label for="tahun">Tahun</label>
                  <select class="select-control" id="tahun" name="tahun" required>
                    <option value="">Pilih tahun</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="form-section">
              <div class="form-section-title">Nomor Surat</div>
              <div class="form-grid-4">
                <div class="field">
                  <label for="nomor">Nomor</label>
                  <input class="input-control" type="text" id="nomor" name="nomor" placeholder="001" required>
                </div>
                <div class="field">
                  <label for="programKerja">Program Kerja</label>
                  <input class="input-control" type="text" id="programKerja" name="program_kerja" placeholder="SEMNAS" required>
                </div>
                <div class="field">
                  <label for="bulanRomawi">Bulan Romawi</label>
                  <input class="input-control" type="text" id="bulanRomawi" name="bulan_romawi" placeholder="XI" required>
                </div>
                <div class="field">
                  <label for="tahunInput">Tahun</label>
                  <input class="input-control" type="text" id="tahunInput" name="tahun_input" placeholder="2026" required>
                </div>
              </div>
              <div class="nomor-preview">
                <span>Pratinjau format nomor:</span>
                <code id="nomorPreviewCode">001/PROKER/HMTI/XI/2026</code>
              </div>
            </div>

            <div class="form-section">
              <div class="form-section-title">Detail Tamu &amp; Waktu Kegiatan</div>
              <div class="form-grid-2">
                <div class="field">
                  <label for="namaTamu">Nama Tamu Undangan</label>
                  <input class="input-control" type="text" id="namaTamu" name="nama_tamu" placeholder="Nama lengkap tamu" required>
                </div>
                <div class="field">
                  <label for="jabatanTamu">Jabatan Tamu Undangan</label>
                  <input class="input-control" type="text" id="jabatanTamu" name="jabatan_tamu" placeholder="Contoh: Ketua Departemen" required>
                </div>
                <div class="field">
                  <label for="lokasiTamu">Lokasi / Instansi Tamu</label>
                  <input class="input-control" type="text" id="lokasiTamu" name="lokasi_tamu" placeholder="Contoh: Tembalang, Semarang" required>
                </div>
                <div class="field">
                  <label for="namaKegiatan">Nama Kegiatan</label>
                  <input class="input-control" type="text" id="namaKegiatan" name="nama_kegiatan" placeholder="Nama acara atau rapat" required>
                </div>
                <div class="field">
                  <label for="waktuMulai">Waktu Mulai</label>
                  <input class="input-control" type="time" id="waktuMulai" name="waktu_mulai" required>
                </div>
                <div class="field">
                  <label for="waktuSelesai">Waktu Selesai</label>
                  <input class="input-control" type="time" id="waktuSelesai" name="waktu_selesai" required>
                </div>
              </div>
            </div>

            <div class="form-actions">
              <a href="surat-undangan.php" class="btn btn-outline">
                <i class="bi bi-arrow-left"></i> Batal
              </a>
              <button type="submit" class="btn btn-primary">
                <span>Lanjut ke Tahap 2</span>
                <i class="bi bi-arrow-right"></i>
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
