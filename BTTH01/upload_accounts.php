<?php

require 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['csv_file']['tmp_name'])) {
        $tmpPath = $_FILES['csv_file']['tmp_name'];

        if (($handle = fopen($tmpPath, 'r')) !== false) {
            $headers = fgetcsv($handle); // bỏ dòng tiêu đề

            // Xoá dữ liệu cũ nếu muốn
            $pdo->exec("TRUNCATE TABLE accounts");

            $stmt = $pdo->prepare(
                "INSERT INTO accounts(username,password,lastname,firstname,city,email,course1)
                 VALUES (?,?,?,?,?,?,?)"
            );

            while (($data = fgetcsv($handle)) !== false) {
                // Giả sử file CSV thứ tự cột đúng như trên
                $stmt->execute($data);
            }
            fclose($handle);
            $message = "Đã import danh sách tài khoản vào CSDL.";
        } else {
            $message = "Không thể mở file CSV.";
        }
    } else {
        $message = "Vui lòng chọn file CSV.";
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Upload Accounts CSV</title>
</head>
<body>
<h1>Upload danh sách tài khoản (CSV) và lưu vào CSDL</h1>

<?php if ($message): ?>
    <p><strong><?= htmlspecialchars($message) ?></strong></p>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <input type="file" name="csv_file" accept=".csv">
    <button type="submit">Upload & Import</button>
</form>
</body>
</html>
