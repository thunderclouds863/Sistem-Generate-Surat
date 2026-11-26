<?php
// Kode ditulis untuk PHP/XAMPP lama: matikan exception mysqli default PHP 8.1+
mysqli_report(MYSQLI_REPORT_OFF);

// Demo portfolio: sisipkan kotak "Akun Demo" yang rapi & tidak mengganggu form di halaman login.
(function () {
    $cfgFile = '/var/www/html/deploy/demo_banner.php';
    if (getenv('DEMO_BANNER_CONFIG')) $cfgFile = getenv('DEMO_BANNER_CONFIG');
    if (!is_file($cfgFile)) return;
    $cfg = include $cfgFile;
    $page = basename($_SERVER['SCRIPT_NAME'] ?? '');
    if (!in_array($page, $cfg['pages'], true)) return;

    $rows = '';
    foreach ($cfg['accounts'] as [$role, $user, $pass]) {
        $rows .= '<tr><td style="padding:3px 6px 3px 0">' . htmlspecialchars($role) . '</td>'
               . '<td style="padding:3px 6px"><code style="background:#f3f5fb;padding:1px 5px;border-radius:4px;color:#141f46">' . htmlspecialchars($user) . '</code></td>'
               . '<td style="padding:3px 0 3px 6px"><code style="background:#f3f5fb;padding:1px 5px;border-radius:4px;color:#141f46">' . htmlspecialchars($pass) . '</code></td></tr>';
    }
    $note = isset($cfg['note']) ? '<p style="margin:8px 0 0;font-size:11px;color:#667085">' . $cfg['note'] . '</p>' : '';
    $banner = '<details id="demo-akun" style="position:fixed;right:16px;bottom:16px;z-index:99999;max-width:340px;'
        . 'background:rgba(255,255,255,0.96);backdrop-filter:blur(8px);border:1px solid #d5dce8;border-radius:12px;'
        . 'box-shadow:0 8px 24px rgba(12,20,43,.22);padding:10px 14px;font:12.5px/1.45 system-ui,-apple-system,Segoe UI,Roboto,sans-serif;color:#111827;text-align:left">'
        . '<summary style="cursor:pointer;font-weight:700;color:#141f46;list-style:none;display:flex;align-items:center;justify-content:space-between;gap:10px">'
        . '<span>Akun Demo</span><span style="font-size:11px;font-weight:500;color:#667085">Klik untuk lihat</span></summary>'
        . '<table style="margin-top:8px;border-collapse:collapse;width:100%;font-size:12px">'
        . '<tr style="color:#667085;border-bottom:1px solid #e2e7f0"><th style="text-align:left;padding-bottom:4px">Role</th><th style="text-align:left;padding:0 6px 4px">User</th><th style="text-align:left;padding:0 0 4px 6px">Pass</th></tr>' . $rows . '</table>'
        . $note . '</details>';

    ob_start(function ($html) use ($banner) {
        if (stripos($html, '</body>') !== false) {
            return preg_replace('~</body>~i', $banner . '</body>', $html, 1);
        }
        return $html;
    });
})();
