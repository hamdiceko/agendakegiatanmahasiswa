<?php
session_start();
// Hapus semua data session/login
session_destroy();

// Tendang balik ke halaman login
header("location:login.php");
exit;
?>