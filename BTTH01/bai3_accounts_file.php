<?php
$filename = __DIR__ . '/data/danhSachDiemDanh.csv';
$rows = [];
$headers = [];

if (($handle = fopen($filename, 'r')) !== false) {
    $headers = fgetcsv($handle);
    while (($data = fgetcsv($handle)) !== false) {
        $rows[] = array_combine($headers, $data);
    }
    fclose($handle);
}
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Danh sách tài khoản(đọc từ CSV)</title>
    <style>
        table { border-collapse: collapse; width: 100%; font-size: 13px; }
        th, td { border: 1px solid #ccc; padding: 4px; }
        th { background: #eee; }
    </style>
</head>
<body>
<h1>Danh sách tài khoản sinh viên</h1>

<table>
    <tr>
        <?php foreach ($headers as $h): ?>
            <th><?= htmlspecialchars($h) ?></th>
        <?php endforeach; ?>
    </tr>
    <?php foreach ($rows as $r): ?>
        <tr>
            <?php foreach ($headers as $h): ?>
                <td><?= htmlspecialchars($r[$h]) ?></td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
