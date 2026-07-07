<?php
require 'vendor/autoload.php';

// Mock the grading logic from Quizzes.php
function gradeQuestion($question, $userAnswer)
{
    $questionType = $question['question_type'] ?? 'single_choice';
    $correct = $question['correct'] ?? null;
    $correctAnswer = $question['correct_answer'] ?? null;

    if ($questionType === 'true_false') {
        $normCorrect = ($correctAnswer !== null) ? $correctAnswer : $correct;
        if (is_bool($normCorrect)) {
            $normCorrect = $normCorrect ? 'true' : 'false';
        }
        $normUser = is_bool($userAnswer) ? ($userAnswer ? 'true' : 'false') : (string)$userAnswer;
        return strtolower(trim((string)$normUser)) === strtolower(trim((string)$normCorrect));
    }

    if ($questionType === 'single_choice') {
        $normCorrect = ($correct !== null) ? $correct : $correctAnswer;
        if ($normCorrect === null) return false;
        
        if (is_numeric($userAnswer) && is_numeric($normCorrect)) {
            return (string)$userAnswer === (string)$normCorrect;
        }
        
        if (isset($question['options'])) {
            $options = $question['options'];
            if (isset($options[$userAnswer]) && $options[$userAnswer] === $normCorrect) {
                return true;
            }
        }
        return (string)$userAnswer === (string)$normCorrect;
    }
    return false;
}

$q = [
    "question_text" => "MCP اختصار لـ:",
    "question_type" => "single_choice",
    "options" => [
        "Model Context Protocol",
        "Multi Code Platform",
        "Managed Coding Process",
        "Modern Coding Pattern"
    ],
    "correct_answer" => "0"
];

// If shuffleAnswers is OFF
var_dump("Shuffle OFF, Answer 0:", gradeQuestion($q, "0")); // Should be true

// If shuffleAnswers is ON
// the getShuffledQuestions sets "correct" => "2" (if option 0 moved to index 2)
// but correct_answer is still "0"
$qShuffled = $q;
$qShuffled['options'] = [
    "Multi Code Platform",
    "Managed Coding Process",
    "Model Context Protocol",
    "Modern Coding Pattern"
];
$qShuffled['correct'] = "2";

var_dump("Shuffle ON, Answer 2:", gradeQuestion($qShuffled, "2")); // Should be true

// What if the front end actually submitted the TEXT instead of the index?
// Wait, take.php sends the value of the radio button. The radio button value is the index `value="<?= $optIndex ?>"`.

