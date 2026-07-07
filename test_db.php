<?php
$pdo = new PDO('mysql:host=localhost;dbname=webeasystep;charset=utf8', 'root', '');
$stmt = $pdo->query('SELECT * FROM tb_quiz_attempts ORDER BY id DESC LIMIT 1');
$attempt = $stmt->fetch(PDO::FETCH_ASSOC);

echo "Score: " . $attempt['score'] . "\n";
echo "User Answers: " . $attempt['user_answers'] . "\n";

$questions = json_decode($attempt['quiz_questions'], true);
$userAnswers = json_decode($attempt['user_answers'], true);

// Let's just print the relevant data
foreach ($questions as $i => $q) {
    echo "Q$i: type=" . $q['question_type'] . "\n";
    echo "  options=" . (isset($q['options']) ? json_encode($q['options']) : 'none') . "\n";
    echo "  correct=" . (isset($q['correct']) ? (is_array($q['correct']) ? implode(',', $q['correct']) : $q['correct']) : 'null') . "\n";
    echo "  correct_answer=" . (isset($q['correct_answer']) ? (is_array($q['correct_answer']) ? implode(',', $q['correct_answer']) : $q['correct_answer']) : 'null') . "\n";
    echo "  user_answer=" . ($userAnswers[$i] ?? 'null') . "\n";
}
