<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
require 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $activity_name = trim($_POST['activity_name']);
    $repetitions = (int) $_POST['repetitions'];
    $activity_date = $_POST['activity_date'];
    $category_id = (int) $_POST['category_id'];
    $user_id = $_SESSION['user_id'];

    if ($activity_name && $repetitions && $activity_date && $category_id) {
        $stmt = $pdo->prepare("INSERT INTO activities (user_id, activity_name, repetitions, activity_date, category_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $activity_name, $repetitions, $activity_date, $category_id]);
    }
}

// Aktiviteleri kategorisiyle birlikte çek
$stmt = $pdo->prepare("SELECT a.*, c.name AS category_name FROM activities a LEFT JOIN categories c ON a.category_id = c.id WHERE a.user_id = ? ORDER BY a.activity_date DESC");
$stmt->execute([$_SESSION['user_id']]);
$activities = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Kontrol Paneli</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="#">Spor Salonu Sistemi</a>
    <div class="d-flex">
      <span class="navbar-text text-white me-3">Hoş geldin, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></span>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Çıkış Yap</a>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <h2 class="text-center mb-4">Spor Aktivitesi Ekle</h2>

  <form method="POST" class="row g-3 mb-5">
    <div class="col-md-4">
      <input type="text" name="activity_name" class="form-control" placeholder="Egzersiz Adı" required>
    </div>
    <div class="col-md-2">
      <input type="number" name="repetitions" class="form-control" placeholder="Tekrar Sayısı" required>
    </div>
    <div class="col-md-3">
      <input type="date" name="activity_date" class="form-control" required>
    </div>
    <div class="col-md-3">
      <select name="category_id" class="form-control" required>
        <option value="">Kategori Seçiniz</option>
        <?php
          $stmt = $pdo->query("SELECT * FROM categories");
          while ($cat = $stmt->fetch()):
        ?>
          <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="col-12">
      <button type="submit" class="btn btn-primary w-100">Ekle</button>
    </div>
  </form>

  <h4 class="mb-3">📋 Kayıtlı Aktiviteler</h4>
  <table class="table table-bordered table-hover bg-white shadow-sm">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Egzersiz</th>
        <th>Tekrar</th>
        <th>Tarih</th>
        <th>Kategori</th>
        <th>İşlem</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($activities as $index => $activity): ?>
        <tr>
          <td><?= $index + 1 ?></td>
          <td><?= htmlspecialchars($activity['activity_name']) ?></td>
          <td><?= $activity['repetitions'] ?></td>
          <td><?= $activity['activity_date'] ?></td>
          <td><?= htmlspecialchars($activity['category_name']) ?></td>
          <td>
            <a href="delete.php?id=<?= $activity['id'] ?>" class="btn btn-sm btn-danger">Sil</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

</body>
</html>
