<?php
session_start(); // Memulai session

// Hapus semua session data
session_unset();

// Hancurkan session
session_destroy();

// Arahkan kembali ke halaman login setelah logout
header("Location: index.html");
exit();
?>
