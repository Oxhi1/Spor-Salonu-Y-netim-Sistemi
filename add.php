<?php
session_start();
require 'config/db.php';

// Oturum kontrolü
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Form gönderildiyse işle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];

    $stmt = $pdo->prepare("INSERT INTO activities (user_id, title, description, category_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $title, $description, $category_id]);

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Aktivite Ekle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Yeni Aktivite Ekle</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Başlık</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Açıklama</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label>Kategori</label>
            <select name="category_id" class="form-control" required>
                <option value="">Seçiniz</option>
                <?php
                $stmt = $pdo->query("SELECT * FROM categories");
                while ($cat = $stmt->fetch()):
                ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Ekle</button>
        <a href="dashboard.php" class="btn btn-secondary">İptal</a>
    </form>
</div>
</body>
</html>
