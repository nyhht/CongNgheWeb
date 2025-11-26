<?php
$flowers = require __DIR__ . '/flowers_data.php';
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Quản trị danh sách hoa (mảng)</title>
    <style>
        table { border-collapse: collapse; width: 1000px; margin: 20px auto; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        img { width: 80px; height: 60px; object-fit: cover; }
    </style>
</head>
<body>
<h1 style="text-align:center">Quản trị danh sách hoa</h1>

<table>
    <tr>
        <th>#</th>
        <th>Tên hoa</th>
        <th>Mô tả</th>
        <th>Ảnh</th>
        <th>CRUD</th>
    </tr>
    <?php foreach ($flowers as $index => $f): ?>
        <tr>
            <td><?= $index + 1 ?></td>
            <td><?= htmlspecialchars($f['name']) ?></td>
            <td><?= htmlspecialchars($f['description']) ?></td>
            <td><img src="images/<?= htmlspecialchars($f['image']) ?>"></td>
            <td>Thêm / Sửa / Xoá </td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
