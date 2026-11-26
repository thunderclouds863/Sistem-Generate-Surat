<?php
require_once __DIR__ . '/includes/bootstrap.php';

$conn = hmti_db();
hmti_ensure_password_resets_table($conn);

$token = trim((string) ($_GET['token'] ?? $_POST['token'] ?? ''));
$tokenRecord = null;

if ($token !== '' && preg_match('/^[a-f0-9]{64}$/i', $token)) {
    $tokenHash = hash('sha256', $token);
    $stmt = $conn->prepare(
        'SELECT pr.id AS reset_id, pr.user_id, u.username, u.role
         FROM password_resets pr
         JOIN users u ON u.id = pr.user_id
         WHERE pr.token_hash = ? AND pr.used_at IS NULL AND pr.expires_at > NOW()
         LIMIT 1'
    );
    $stmt->bind_param('s', $tokenHash);
    $stmt->execute();
    $tokenRecord = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hmti_verify_csrf()) {
        hmti_set_flash('error', 'Sesi formulir tidak valid. Silakan muat ulang halaman.');
        header('Location: reset_password.php?token=' . urlencode($token));
        exit();
    }

    if (!$tokenRecord) {
        hmti_set_flash('error', 'Tautan reset password tidak valid, sudah digunakan, atau telah kedaluwarsa.');
        header('Location: forgot_password.php');
        exit();
    }

    $newPass = (string) ($_POST['password'] ?? '');
    $confirmPass = (string) ($_POST['confirm_password'] ?? '');

    if (strlen($newPass) < 6) {
        hmti_set_flash('error', 'Password baru minimal 6 karakter.');
        header('Location: reset_password.php?token=' . urlencode($token));
        exit();
    }

    if ($newPass !== $confirmPass) {
        hmti_set_flash('error', 'Konfirmasi password tidak cocok.');
        header('Location: reset_password.php?token=' . urlencode($token));
        exit();
    }

    $hashed = password_hash($newPass, PASSWORD_BCRYPT);
    $userId = (int) $tokenRecord['user_id'];
    $resetId = (int) $tokenRecord['reset_id'];

    $conn->begin_transaction();
    try {
        $updUser = $conn->prepare('UPDATE users SET password = ? WHERE id = ?');
        $updUser->bind_param('si', $hashed, $userId);
        if (!$updUser->execute()) {
            throw new RuntimeException($updUser->error);
        }
        $updUser->close();

        $updToken = $conn->prepare('UPDATE password_resets SET used_at = NOW() WHERE id = ?');
        $updToken->bind_param('i', $resetId);
        if (!$updToken->execute()) {
            throw new RuntimeException($updToken->error);
        }
        $updToken->close();

        $conn->commit();
        $targetLogin = (hmti_normalize_role($tokenRecord['role']) === 'anggota') ? 'index.html' : 'login_role.php';
        hmti_set_flash('success', 'Password berhasil diperbarui. Silakan masuk dengan password baru Anda.');
        header("Location: {$targetLogin}");
        exit();
    } catch (Throwable $e) {
        $conn->rollback();
        hmti_set_flash('error', 'Gagal memperbarui password. Silakan coba lagi.');
        header('Location: reset_password.php?token=' . urlencode($token));
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password — Sistem Surat HMTI UNDIP</title>
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
      <section class="auth-card" aria-label="Form Reset Password">
        <div class="auth-card-inner">
          <div class="auth-card-brand">
            <img src="logo-undip.png" alt="Logo Universitas Diponegoro dan HMTI">
            <div class="auth-brand-text">
              <strong>HMTI UNDIP</strong>
              <span>Sistem Administrasi Surat</span>
            </div>
          </div>

          <div class="auth-card-header">
            <span class="auth-eyebrow"><i class="bi bi-shield-lock"></i> Atur Ulang Password</span>
            <h1>Buat Password Baru</h1>
            <?php if ($tokenRecord): ?>
              <p>Atur password baru untuk akun <strong><?php echo htmlspecialchars($tokenRecord['username'], ENT_QUOTES, 'UTF-8'); ?></strong>.</p>
            <?php else: ?>
              <p>Tautan reset password tidak valid atau sudah kedaluwarsa.</p>
            <?php endif; ?>
          </div>

          <?php echo hmti_render_flash(); ?>

          <?php if ($tokenRecord): ?>
            <form action="reset_password.php" method="POST">
              <?php echo hmti_csrf_field(); ?>
              <input type="hidden" name="token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">

              <div class="field">
                <label for="password">Password Baru</label>
                <div class="input-wrap has-toggle">
                  <span class="input-icon"><i class="bi bi-lock"></i></span>
                  <input class="input-control" type="password" id="password" name="password" placeholder="Minimal 6 karakter" autocomplete="new-password" minlength="6" required>
                  <button type="button" class="password-toggle" data-toggle-password="password" aria-label="Tampilkan password">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
              </div>

              <div class="field">
                <label for="confirm_password">Konfirmasi Password Baru</label>
                <div class="input-wrap has-toggle">
                  <span class="input-icon"><i class="bi bi-lock-fill"></i></span>
                  <input class="input-control" type="password" id="confirm_password" name="confirm_password" placeholder="Ulangi password baru" autocomplete="new-password" minlength="6" required>
                  <button type="button" class="password-toggle" data-toggle-password="confirm_password" aria-label="Tampilkan konfirmasi password">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
              </div>

              <button type="submit" class="auth-submit-btn">
                <span>Simpan Password Baru</span>
                <i class="bi bi-check2-circle"></i>
              </button>
            </form>
          <?php else: ?>
            <div class="alert-banner alert-error" role="alert">
              <i class="bi bi-exclamation-triangle-fill"></i>
              <span>Tautan ini tidak valid, sudah digunakan, atau telah kedaluwarsa.</span>
            </div>
            <a href="forgot_password.php" class="auth-submit-btn">
              <span>Minta Tautan Baru</span>
              <i class="bi bi-arrow-right"></i>
            </a>
          <?php endif; ?>

          <hr class="auth-divider">

          <div class="auth-footer-links">
            <p><a href="index.html">Kembali ke halaman masuk</a></p>
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
