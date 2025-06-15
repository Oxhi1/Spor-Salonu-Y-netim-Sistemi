<?php
$host = 'localhost';      // phpMyAdmin'e göre değişebilir
$db   = 'spor_sistemi';
$user = 'root';           // Kullanıcı adın (genellikle root)
$pass = '';               // Şifren (phpMyAdmin'de çoğu zaman boştur)
$charset = 'utf8mb4';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Veritabanı bağlantı hatası: " . $e->getMessage();
    exit;
}
?>
