<?php
// upload_quiz.php
require 'db.php';

$message = '';

function importQuizFileToDb(PDO $pdo, $filepath) {
    $rawLines = file($filepath, FILE_IGNORE_NEW_LINES);
    $lines = [];

    // loại bỏ dòng trống
    foreach ($rawLines as $line) {
        if (trim($line) !== '') {
            $lines[] = $line;
        }
    }

    $total = count($lines);
    $i = 0;

    while ($i + 5 < $total) {  // mỗi câu 6 dòng
        $q       = trim($lines[$i++]);
        $a       = trim($lines[$i++]);
        $b       = trim($lines[$i++]);
        $c       = trim($lines[$i++]);
        $d       = trim($lines[$i++]);
        $ansLine = trim($lines[$i++]);

        $correct = '';
        if (stripos($ansLine, 'ANSWER:') === 0) {
            $correct = strtoupper(trim(substr($ansLine, 7)));
        }

        $stmt = $pdo->prepare(
            "INSERT INTO quiz_questions(question, option_a, option_b, option_c, option_d, correct_option)
             VALUES (?,?,?,?,?,?)"
        );
        $stmt->execute([
            $q,
            (strlen($a) > 3 ? substr($a, 3) : $a),
            (strlen($b) > 3 ? substr($b, 3) : $b),
            (strlen($c) > 3 ? substr($c, 3) : $c),
            (strlen($d) > 3 ? substr($d, 3) : $d),
            $correct
        ]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['quiz_file']['tmp_name'])) {
        // Xoá dữ liệu cũ nếu muốn
        $pdo->exec("TRUNCATE TABLE quiz_questions");

        $tmpPath = $_FILES['quiz_file']['tmp_name'];
        importQuizFileToDb($pdo, $tmpPath);
        $message = "Đã import file quiz vào CSDL.";
    } else {
        $message = "Vui lòng chọn file .txt chứa câu hỏi.";
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Upload file Quiz</title>
</head>
<body>
<h1>Upload file Quiz (TXT) và lưu vào CSDL</h1>

<?php if ($message): ?>
    <p><strong><?= htmlspecialchars($message) ?></strong></p>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <input type="file" name="quiz_file" accept=".txt">
    <button type="submit">Upload & Import</button>
</form>
</body>
</html>
