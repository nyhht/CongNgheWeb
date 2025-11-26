<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// bai1_admin_db.php
require 'db.php';

$message = "";

// Xử lý thêm 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = trim($_POST['name']);
    $desc = trim($_POST['description']);
    $image = trim($_POST['image']);

    if ($name !== '' && $image !== '') {
        $stmt = $pdo->prepare("INSERT INTO flowers(name, description, image) VALUES (?,?,?)");
        $stmt->execute([$name, $desc, $image]);
        $message = "Đã thêm hoa mới.";
    } else {
        $message = "Tên hoa và tên file ảnh không được để trống.";
    }
}

// Xử lý xoá
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM flowers WHERE id = ?");
    $stmt->execute([$id]);
    $message = "Đã xoá hoa ID = $id.";
}

// Lấy dữ liệu để sửa (nếu có ?edit=id)
$editFlower = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM flowers WHERE id = ?");
    $stmt->execute([$id]);
    $editFlower = $stmt->fetch();
}

// Xử lý cập nhật (Sửa)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id    = (int)$_POST['id'];
    $name  = trim($_POST['name']);
    $desc  = trim($_POST['description']);
    $image = trim($_POST['image']);

    if ($name !== '' && $image !== '') {
        $stmt = $pdo->prepare("UPDATE flowers SET name=?, description=?, image=? WHERE id=?");
        $stmt->execute([$name, $desc, $image, $id]);
        $message = "Đã cập nhật thông tin hoa ID = $id.";
        $editFlower = null; // không hiển thị form sửa nữa
    } else {
        $message = "Tên hoa và tên file ảnh không được để trống.";
    }
}

// Lấy danh sách tất cả hoa
$stmt = $pdo->query("SELECT * FROM flowers ORDER BY id");
$flowers = $stmt->fetchAll();
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Quản trị danh sách hoa (CSDL)</title>
    <style>
        body { font-family: Arial; }
        table { border-collapse:collapse; width:1000px; margin:20px auto; }
        th, td { border:1px solid #ccc; padding:6px; vertical-align:top; }
        th { background:#f2f2f2; }
        img { width:80px; height:60px; object-fit:cover; }
        .container { width:1000px; margin:0 auto; }
        .message { color:green; font-weight:bold; }
        .error { color:red; font-weight:bold; }
    </style>
</head>
<body>
<div class="container">
    <h1 style="text-align:center">Quản trị danh sách hoa</h1>

    <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <!-- Form thêm mới -->
    <h3>Thêm hoa mới</h3>
    <form method="post">
        <input type="hidden" name="action" value="add">
        Tên hoa: <input type="text" name="name" style="width:250px" required>
        &nbsp; Ảnh (tên file trong thư mục images/):
        <input type="text" name="image" style="width:250px" required><br><br>
        Mô tả:<br>
        <textarea name="description" rows="3" style="width:100%;"></textarea><br>
        <button type="submit">Thêm</button>
    </form>

    <hr>

    <!-- Bảng danh sách -->
    <table>
        <tr>
            <th>#</th>
            <th>Tên hoa</th>
            <th>Mô tả</th>
            <th>Ảnh</th>
            <th>CRUD</th>
        </tr>
        <?php foreach ($flowers as $f): ?>
            <tr>
                <td><?= $f['id'] ?></td>
                <td><?= htmlspecialchars($f['name']) ?></td>
                <td><?= nl2br(htmlspecialchars($f['description'])) ?></td>
                <td><img src="images/<?= htmlspecialchars($f['image']) ?>"></td>
                <td>
                    <a href="?edit=<?= $f['id'] ?>">Sửa</a> |
                    <a href="?delete=<?= $f['id'] ?>" onclick="return confirm('Xoá hoa này?');">Xoá</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php if ($editFlower): ?>
        <hr>
        <h3>Sửa hoa ID = <?= $editFlower['id'] ?></h3>
        <form method="post">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?= $editFlower['id'] ?>">
            Tên hoa: <input type="text" name="name" style="width:250px"
                           value="<?= htmlspecialchars($editFlower['name']) ?>" required>
            &nbsp; Ảnh:
            <input type="text" name="image" style="width:250px"
                   value="<?= htmlspecialchars($editFlower['image']) ?>" required><br><br>
            Mô tả:<br>
            <textarea name="description" rows="3" style="width:100%;"><?= htmlspecialchars($editFlower['description']) ?></textarea><br>
            <button type="submit">Cập nhật</button>
        </form>
    <?php endif; ?>

</div>
</body>
</html>
