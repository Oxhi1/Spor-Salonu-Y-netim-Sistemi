<?php
session_start();
session_unset();     // Tüm oturum değişkenlerini temizle
session_destroy();   // Oturumu tamamen yok et

header("Location: index.php"); // index.php sayfasına yönlendir
exit;