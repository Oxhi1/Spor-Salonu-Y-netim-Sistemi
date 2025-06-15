<?php
session_start();
require 'config/db.php';

$hata = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username && $password) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Oturum başlat
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $hata = "Kullanıcı adı veya şifre hatalı.";
        }
    } else {
        $hata = "Tüm alanları doldurun.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Giriş Yap</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow rounded-4">
        <div class="card-body">
          <h4 class="card-title text-center mb-4">Giriş Yap</h4>

          <?php if ($hata): ?>
            <div class="alert alert-danger"><?= $hata ?></div>
          <?php endif; ?>

          <form method="POST" action="">
            <div class="mb-3">
              <label for="username" class="form-label">Kullanıcı Adı</label>
              <input type="text" class="form-control" id="username" name="username" required>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Şifre</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-success w-100">Giriş Yap</button>
          </form>

          <div class="text-center mt-3">
            <small>Hesabınız yok mu? <a href="register.php">Kayıt ol</a></small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
