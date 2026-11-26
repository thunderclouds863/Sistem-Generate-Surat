<?php
require_once __DIR__ . '/includes/bootstrap.php';
hmti_require_auth(['anggota', 'kahim', 'sekhim']);

$homeUrl = hmti_homepage_for_role($_SESSION['role'] ?? '');
$username = $_SESSION['username'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hmti_verify_csrf()) {
        hmti_set_flash('error', 'Sesi formulir tidak valid. Silakan coba lagi.');
        header('Location: change_password.php');
        exit();
    }

    $currentPass = (string) ($_POST['current_password'] ?? '');
    $newPass = (string) ($_POST['new_password'] ?? '');
    $confirmPass = (string) ($_POST['confirm_password'] ?? '');

    if ($currentPass === '' || strlen($newPass) < 6) {
        hmti_set_flash('error', 'Password saat ini wajib diisi dan password baru minimal 6 karakter.');
        header('Location: change_password.php');
        exit();
    }

    if ($newPass !== $confirmPass) {
        hmti_set_flash('error', 'Konfirmasi password baru tidak cocok.');
        header('Location: change_password.php');
        exit();
    }

    $conn = hmti_db();
    $userId = (int) $_SESSION['user_id'];
    $stmt = $conn->prepare('SELECT password FROM users WHERE id = ? LIMIT 1');
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$user) {
        hmti_set_flash('error', 'Akun tidak ditemukan.');
        header('Location: logout.php');
        exit();
    }

    $matches = password_verify($currentPass, $user['password']) || hash_equals($user['password'], $currentPass);
    if (!$matches) {
        hmti_set_flash('error', 'Password saat ini tidak sesuai.');
        header('Location: change_password.php');
        exit();
    }

    $hashed = password_hash($newPass, PASSWORD_BCRYPT);
    $upd = $conn->prepare('UPDATE users SET password = ? WHERE id = ?');
    $upd->bind_param('si', $hashed, $userId);
    if ($upd->execute()) {
        $upd->close();
        session_regenerate_id(true);
        hmti_set_flash('success', 'Password akun Anda berhasil diperbarui.');
        header('Location: change_password.php');
        exit();
    }
    $upd->close();
    hmti_set_flash('error', 'Gagal memperbarui password.');
    header('Location: change_password.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ubah Password — HMTI UNDIP</title>
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
        <a href="<?php echo htmlspecialchars($homeUrl, ENT_QUOTES, 'UTF-8'); ?>" class="brand-link">
          <img src="logo-undip.png" alt="Logo UNDIP dan HMTI">
          <div class="brand-meta">
            <strong>HMTI UNDIP</strong>
            <span>Pengaturan Keamanan Akun</span>
          </div>
        </a>
      </div>

      <div class="header-right">
        <a href="<?php echo htmlspecialchars($homeUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-outline btn-sm">
          <i class="bi bi-arrow-left"></i>
          <span>Kembali</span>
        </a>
      </div>
    </div>
  </header>

  <main class="app-main">
    <div class="form-page-container" style="max-width: 560px;">
      <section class="form-card">
        <div class="form-card-header">
          <h1>Ubah Password</h1>
          <p>Perbarui password untuk akun <strong><?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></strong>.</p>
        </div>

        <div class="form-card-body">
          <?php echo hmti_render_flash(); ?>

          <form action="change_password.php" method="POST">
            <?php echo hmti_csrf_field(); ?>

            <div class="field">
              <label for="current_password">Password Saat Ini</label>
              <div class="input-wrap has-toggle">
                <span class="input-icon"><i class="bi bi-shield-lock"></i></span>
                <input class="input-control" type="password" id="current_password" name="current_password" placeholder="Masukkan password saat ini" autocomplete="current-password" required>
                <button type="button" class="password-toggle" data-toggle-password="current_password" aria-label="Tampilkan password">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>

            <div class="field">
              <label for="new_password">Password Baru</label>
              <div class="input-wrap has-toggle">
                <span class="input-icon"><i class="bi bi-lock"></i></span>
                <input class="input-control" type="password" id="new_password" name="new_password" placeholder="Minimal 6 karakter" autocomplete="new-password" minlength="6" required>
                <button type="button" class="password-toggle" data-toggle-password="new_password" aria-label="Tampilkan password baru">
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

            <div class="form-actions">
              <a href="<?php echo htmlspecialchars($homeUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-outline">
                Batal
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-check2-circle"></i>
                <span>Simpan Password</span>
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
