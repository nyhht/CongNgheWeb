<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// bai2_quiz_db.php
require 'db.php';

$stmt = $pdo->query("SELECT * FROM quiz_questions ORDER BY id");
$questions = $stmt->fetchAll();

$submitted = ($_SERVER['REQUEST_METHOD'] === 'POST');
$score = 0;
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Bài thi trắc nghiệm (CSDL) </title>
    <style>
        body { font-family: Arial; width: 900px; margin: 0 auto; }
        .question { border:1px solid #ccc; padding:10px; margin-bottom:15px; }
        .correct { color:green; font-weight:bold; }
        .wrong { color:red; }
    </style>
</head>
<body>
<h1>Bài thi trắc nghiệm Android (nguồn: CSDL)</h1>

<?php if (empty($questions)): ?>
    <p><strong>Chưa có câu hỏi nào. Hãy upload file tại upload_quiz.php.</strong></p>
<?php else: ?>

<form method="post">
    <?php foreach ($questions as $i => $q): ?>
        <div class="question">
            <p><strong>Câu <?= $i + 1 ?>:</strong> <?= htmlspecialchars($q['question']) ?></p>

            <?php
            $options = [
                'A' => $q['option_a'],
                'B' => $q['option_b'],
                'C' => $q['option_c'],
                'D' => $q['option_d'],
            ];
            foreach ($options as $key => $text):
                $name = "q{$i}";
                $checked = isset($_POST[$name]) && $_POST[$name] === $key;
            ?>
                <label>
                    <input type="radio" name="<?= $name ?>" value="<?= $key ?>" <?= $checked ? 'checked' : '' ?>>
                    <?= $key ?>. <?= htmlspecialchars($text) ?>
                </label><br>
            <?php endforeach; ?>

            <?php if ($submitted):
                $userAnswer = $_POST["q{$i}"] ?? '';
                if ($userAnswer === $q['correct_option']) {
                    $score++;
                    echo "<p class='correct'>Đúng! Đáp án: {$q['correct_option']}</p>";
                } else {
                    echo "<p class='wrong'>Sai. Bạn chọn: {$userAnswer}. Đáp án đúng: {$q['correct_option']}</p>";
                }
            endif; ?>
        </div>
    <?php endforeach; ?>
    <button type="submit">Nộp bài</button>
</form>

<?php if ($submitted): ?>
    <h2>Kết quả: <?= $score ?>/<?= count($questions) ?> câu đúng</h2>
<?php endif; ?>

<?php endif; ?>
</body>
</html>
