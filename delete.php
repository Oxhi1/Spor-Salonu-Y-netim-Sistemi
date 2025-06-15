<?php
session_start();
require 'config/db.php';

// Giriş yapılmamışsa yönlendir
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $activity_id = (int) $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Silme işlemi (güvenlik: sadece kullanıcıya ait kayıt silinebilir)
    $stmt = $pdo->prepare("DELETE FROM activities WHERE id = ? AND user_id = ?");
    $stmt->execute([$activity_id, $user_id]);
}

// Silme sonrası panele dön
header("Location: dashboard.php");
exit;
?>
