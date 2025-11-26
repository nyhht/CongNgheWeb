<?php
// bai1_user_db.php
require 'db.php';

$stmt = $pdo->query("SELECT * FROM flowers ORDER BY id");
$flowers = $stmt->fetchAll();
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>14 loài hoa xuân – hè (CSDL) </title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 1000px; margin: 0 auto; }
        .flower { display:flex; margin-bottom:20px; border-bottom:1px solid #ccc; padding-bottom:10px; }
        .flower img { width:220px; height:150px; object-fit:cover; margin-right:15px; }
    </style>
</head>
<body>
<div class="container">
    <h1>14 loài hoa tuyệt đẹp dịp xuân – hè</h1>
    <?php foreach ($flowers as $f): ?>
        <div class="flower">
            <img src="images/<?= htmlspecialchars($f['image']) ?>" alt="<?= htmlspecialchars($f['name']) ?>">
            <div>
                <h2><?= htmlspecialchars($f['name']) ?></h2>
                <p><?= nl2br(htmlspecialchars($f['description'])) ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
