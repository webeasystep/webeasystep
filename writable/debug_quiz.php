<?php
$db = new mysqli('localhost', 'root', '', 'webeasystep');
$json = file_get_contents('d:/laragon/www/webeasystep/modules/Quizzes/scope_methodology_quiz.json');
$data = json_decode($json, true);
$questions = $data['quiz_questions'];
$questionsJson = json_encode($questions, JSON_UNESCAPED_UNICODE);

// Update the quiz (find by title)
$stmt = $db->prepare("UPDATE tb_quizzes SET quiz_questions = ? WHERE quiz_title LIKE '%S.C.O.P.E%'");
$stmt->bind_param("s", $questionsJson);
$stmt->execute();
echo "Affected rows: " . $stmt->affected_rows . PHP_EOL;

// Verify Q9
$r = $db->query("SELECT quiz_questions FROM tb_quizzes WHERE quiz_title LIKE '%S.C.O.P.E%'");
$q = $r->fetch_assoc();
$qs = json_decode($q['quiz_questions'], true);
echo "Q9 type: " . $qs[8]['question_type'] . PHP_EOL;
echo "Q9 text: " . mb_substr($qs[8]['question_text'], 0, 50) . PHP_EOL;
