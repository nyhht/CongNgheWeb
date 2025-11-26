<?php
$flowers = require __DIR__ . '/flowers_data.php';
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>14 loài hoa xuân – hè </title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 1000px; margin: 0 auto; }
        .flower {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }
        .flower img {
            width: 220px;
            height: 150px;
            object-fit: cover;
            margin-right: 15px;
        }
        h1 { text-align: center; }
        h2 { margin: 0 0 10px; }
    </style>
</head>
<body>
<div class="container">
    <h1>14 loài hoa tuyệt đẹp dịp xuân – hè</h1>

    <?php foreach ($flowers as $flower): ?>
        <div class="flower">
            <img src="images/<?= htmlspecialchars($flower['image']) ?>"
                 alt="<?= htmlspecialchars($flower['name']) ?>">
            <div>
                <h2><?= htmlspecialchars($flower['name']) ?></h2>
                <p><?= nl2br(htmlspecialchars($flower['description'])) ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
