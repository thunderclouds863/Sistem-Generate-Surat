<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Akun — Sistem Surat HMTI UNDIP</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="assets/css/hmti.css">
</head>

<body class="auth-page">
  <div class="auth-backdrop" aria-hidden="true"></div>

  <main class="auth-shell">
    <section class="auth-context" aria-label="Identitas HMTI UNDIP">
      <div class="auth-brand-mark">
        <img src="logo-undip.png" alt="Logo Universitas Diponegoro dan HMTI">
        <div class="auth-brand-text">
          <strong>HMTI UNDIP</strong>
          <span>Sistem Administrasi Surat</span>
        </div>
      </div>

      <div class="auth-context-footer">
        <a class="auth-contact-item" href="https://hmti.kemahasiswaan.undip.ac.id" target="_blank" rel="noopener noreferrer">
          <span class="auth-contact-icon"><i class="bi bi-globe2"></i></span>
          <span class="auth-contact-meta">
            <small>Website</small>
            <span>hmti.kemahasiswaan.undip.ac.id</span>
          </span>
        </a>
        <a class="auth-contact-item" href="mailto:hmtindipsemarang@gmail.com">
          <span class="auth-contact-icon"><i class="bi bi-envelope"></i></span>
          <span class="auth-contact-meta">
            <small>E-Mail</small>
            <span>hmtindipsemarang@gmail.com</span>
          </span>
        </a>
      </div>
    </section>

    <section class="auth-card" aria-label="Form Registrasi Akun">
      <div class="auth-card-inner">
        <div class="auth-card-mobile-brand">
          <img src="logo-undip.png" alt="Logo UNDIP dan HMTI">
          <div class="auth-brand-text">
            <strong>HMTI UNDIP</strong>
            <span>Sistem Administrasi Surat</span>
          </div>
        </div>

        <div class="auth-card-header">
          <span class="auth-eyebrow"><i class="bi bi-person-plus"></i> Registrasi Anggota</span>
          <h1>Buat Akun Baru</h1>
          <p>Daftarkan akun anggota untuk mulai membuat dokumen administrasi.</p>
        </div>

        <form id="registerForm" action="register_process.php" method="POST">
          <div class="field">
            <label for="username">Username</label>
            <div class="input-wrap">
              <span class="input-icon"><i class="bi bi-person"></i></span>
              <input class="input-control" type="text" id="username" name="username" placeholder="Pilih username" autocomplete="username" required>
            </div>
          </div>

          <div class="field">
            <label for="password">Password</label>
            <div class="input-wrap has-toggle">
              <span class="input-icon"><i class="bi bi-lock"></i></span>
              <input class="input-control" type="password" id="password" name="password" placeholder="Minimal 6 karakter" autocomplete="new-password" required>
              <button type="button" class="password-toggle" data-toggle-password="password" aria-label="Tampilkan password">
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>

          <div class="field">
            <label for="confirm_password">Konfirmasi Password</label>
            <div class="input-wrap has-toggle">
              <span class="input-icon"><i class="bi bi-lock-fill"></i></span>
              <input class="input-control" type="password" id="confirm_password" name="confirm_password" placeholder="Ulangi password" autocomplete="new-password" required>
              <button type="button" class="password-toggle" data-toggle-password="confirm_password" aria-label="Tampilkan konfirmasi password">
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>

          <button type="submit" class="auth-submit-btn">
            <span>Daftar Akun</span>
            <i class="bi bi-arrow-right"></i>
          </button>
        </form>

        <hr class="auth-divider">

        <div class="auth-footer-links">
          <p>Sudah punya akun? <a href="index.html">Masuk di sini</a></p>
        </div>
      </div>
    </section>
  </main>

  <footer class="auth-mobile-footer">
    <a class="auth-contact-item" href="https://hmti.kemahasiswaan.undip.ac.id" target="_blank" rel="noopener noreferrer">
      <i class="bi bi-globe2"></i> hmti.kemahasiswaan.undip.ac.id
    </a>
    <a class="auth-contact-item" href="mailto:hmtindipsemarang@gmail.com">
      <i class="bi bi-envelope"></i> hmtindipsemarang@gmail.com
    </a>
  </footer>

  <script src="assets/js/hmti.js"></script>
  <script>
    document.getElementById('registerForm').addEventListener('submit', function (e) {
      e.preventDefault();

      const username = document.getElementById('username').value.trim();
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirm_password').value;

      if (username === '') {
        alert('Username cannot be empty');
        return;
      }

      if (password.length < 6) {
        alert('Password must be at least 6 characters long');
        return;
      }

      if (password !== confirmPassword) {
        alert('Passwords do not match');
        return;
      }

      this.submit();
    });
  </script>
</body>

</html>
