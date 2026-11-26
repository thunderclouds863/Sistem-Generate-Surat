<?php
require_once __DIR__ . '/includes/bootstrap.php';

$demoResetLink = null;
$infoMessage = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hmti_verify_csrf()) {
        hmti_set_flash('error', 'Sesi formulir tidak valid. Silakan coba lagi.');
        header('Location: forgot_password.php');
        exit();
    }

    $username = trim($_POST['username'] ?? '');
    if ($username === '') {
        hmti_set_flash('error', 'Masukkan username akun Anda.');
        header('Location: forgot_password.php');
        exit();
    }

    $conn = hmti_db();
    hmti_ensure_password_resets_table($conn);

    $stmt = $conn->prepare('SELECT id, username FROM users WHERE username = ? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Generic non-enumerating response
    $infoMessage = 'Jika username terdaftar, tautan reset password berlaku selama 30 menit telah dibuat.';

    if ($user) {
        $userId = (int) $user['id'];
        // Invalidate previous unused tokens for this user
        $inv = $conn->prepare('UPDATE password_resets SET used_at = NOW() WHERE user_id = ? AND used_at IS NULL');
        $inv->bind_param('i', $userId);
        $inv->execute();
        $inv->close();

        $rawToken = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $rawToken);

        $ins = $conn->prepare('INSERT INTO password_resets (user_id, token_hash, expires_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 30 MINUTE))');
        $ins->bind_param('is', $userId, $tokenHash);
        if ($ins->execute()) {
            // Since no external SMTP server is configured in local/demo environments,
            // expose the single-use reset URL directly in the UI for verification & recovery.
            $demoResetLink = 'reset_password.php?token=' . urlencode($rawToken);
        }
        $ins->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lupa Password — Sistem Surat HMTI UNDIP</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="assets/css/hmti.css">
</head>

<body class="auth-page">
  <div class="auth-split">
    <aside class="auth-visual" aria-label="Gedung Teknik Industri UNDIP">
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
    </aside>

    <main class="auth-panel">
      <section class="auth-card" aria-label="Form Lupa Password">
        <div class="auth-card-inner">
          <div class="auth-card-brand">
            <img src="logo-undip.png" alt="Logo Universitas Diponegoro dan HMTI">
            <div class="auth-brand-text">
              <strong>HMTI UNDIP</strong>
              <span>Sistem Administrasi Surat</span>
            </div>
          </div>

          <div class="auth-card-header">
            <span class="auth-eyebrow"><i class="bi bi-key"></i> Pemulihan Akun</span>
            <h1>Lupa Password</h1>
            <p>Masukkan username akun Anda untuk membuat tautan reset password sekali pakai.</p>
          </div>

          <?php echo hmti_render_flash(); ?>

          <?php if ($infoMessage): ?>
            <div class="alert-banner alert-success" role="status">
              <i class="bi bi-check-circle-fill"></i>
              <span><?php echo htmlspecialchars($infoMessage, ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
          <?php endif; ?>

          <?php if ($demoResetLink): ?>
            <div class="nomor-preview" style="margin-bottom: 18px; flex-direction: column; align-items: flex-start;">
              <span style="font-size: 0.75rem; font-weight: 700; color: var(--navy-800);">Tautan Reset (Mode Lokal/Demo · Berlaku 30 Menit):</span>
              <a href="<?php echo htmlspecialchars($demoResetLink, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-navy btn-sm" style="margin-top: 6px;">
                <i class="bi bi-shield-lock"></i> Buka Halaman Reset Password
              </a>
            </div>
          <?php endif; ?>

          <form action="forgot_password.php" method="POST">
            <?php echo hmti_csrf_field(); ?>
            <div class="field">
              <label for="username">Username</label>
              <div class="input-wrap">
                <span class="input-icon"><i class="bi bi-person"></i></span>
                <input class="input-control" type="text" id="username" name="username" placeholder="Masukkan username akun Anda" autocomplete="username" required>
              </div>
            </div>

            <button type="submit" class="auth-submit-btn">
              <span>Buat Tautan Reset</span>
              <i class="bi bi-arrow-right"></i>
            </button>
          </form>

          <hr class="auth-divider">

          <div class="auth-footer-links">
            <p>Ingat password Anda? <a href="index.html">Kembali ke Login</a></p>
          </div>
        </div>
      </section>

      <footer class="auth-mobile-footer">
        <a href="https://hmti.kemahasiswaan.undip.ac.id" target="_blank" rel="noopener noreferrer">
          <i class="bi bi-globe2"></i> hmti.kemahasiswaan.undip.ac.id
        </a>
        <a href="mailto:hmtindipsemarang@gmail.com">
          <i class="bi bi-envelope"></i> hmtindipsemarang@gmail.com
        </a>
      </footer>
    </main>
  </div>

  <script src="assets/js/hmti.js"></script>
</body>

</html>
