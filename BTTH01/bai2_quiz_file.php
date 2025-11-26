<?php

function readQuizFile($filename) {
    if (!file_exists($filename)) {
        return [];
    }

    $rawLines = file($filename, FILE_IGNORE_NEW_LINES);

    $lines = [];
    foreach ($rawLines as $line) {
        if (trim($line) !== '') {
            $lines[] = $line;
        }
    }

    $questions = [];
    $i = 0;
    $total = count($lines);

    while ($i + 5 < $total) {   
        $q = trim($lines[$i++]);  
        $a = trim($lines[$i++]);   
        $b = trim($lines[$i++]);  
        $c = trim($lines[$i++]);   
        $d = trim($lines[$i++]);   
        $ansLine = trim($lines[$i++]); 

        $correct = '';
        if (stripos($ansLine, 'ANSWER:') === 0) {
            $correct = strtoupper(trim(substr($ansLine, 7)));
        }

        $questions[] = [
            'question' => $q,
            'options'  => [
                'A' => (strlen($a) > 3 ? substr($a, 3) : $a),
                'B' => (strlen($b) > 3 ? substr($b, 3) : $b),
                'C' => (strlen($c) > 3 ? substr($c, 3) : $c),
                'D' => (strlen($d) > 3 ? substr($d, 3) : $d),
            ],
            'correct'  => $correct,
        ];
    }

    return $questions;
}

$questions = readQuizFile(__DIR__ . '/data/quiz.txt');
$submitted = ($_SERVER['REQUEST_METHOD'] === 'POST');
$score = 0;
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Bài thi trắc nghiệm (đọc từ file) </title>
    <style>
        body { font-family: Arial; width: 900px; margin: 0 auto; }
        .question { border: 1px solid #ccc; padding: 10px; margin-bottom: 15px; }
        .correct { color: green; font-weight: bold; }
        .wrong { color: red; }
    </style>
</head>
<body>
<h1>Bài thi trắc nghiệm Android</h1>

<?php if (empty($questions)): ?>
    <p><strong>Không đọc được câu hỏi nào. Kiểm tra lại file data/quiz.txt nhé.</strong></p>
<?php else: ?>
<form method="post">
    <?php foreach ($questions as $i => $q): ?>
        <div class="question">
            <p><strong>Câu <?= $i + 1 ?>:</strong> <?= htmlspecialchars($q['question']) ?></p>
            <?php foreach ($q['options'] as $key => $text):
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
                if ($userAnswer === $q['correct']) {
                    $score++;
                    echo "<p class='correct'>Đúng! Đáp án: {$q['correct']}</p>";
                } else {
                    echo "<p class='wrong'>Sai. Bạn chọn: {$userAnswer}. Đáp án đúng: {$q['correct']}</p>";
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
