<?php

namespace Tests;

use CodeIgniter\Test\CIUnitTestCase;
use Modules\Quizzes\Controllers\Quizzes;
use ReflectionMethod;

class QuizSystemTest extends CIUnitTestCase
{
    protected $quizzesController;

    protected function setUp(): void
    {
        parent::setUp();
        // Instantiate the controller for testing
        $this->quizzesController = new Quizzes();
    }

    /**
     * Helper to invoke private methods in the Quizzes controller using Reflection
     */
    protected function invokeControllerMethod(string $methodName, array $parameters = [])
    {
        $method = new ReflectionMethod(Quizzes::class, $methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($this->quizzesController, $parameters);
    }

    /**
     * Scenario: Test the end-to-end quiz operations including question/option shuffling,
     * correct answer index translation, and grading accuracy for all question types.
     */
    public function testEndToEndQuizShufflingAndGradingFlow()
    {
        // 1. Setup mock quiz questions database template
        $originalQuestions = [
            [
                'question_text' => 'Which mode is suitable for highest quality in Google Stitch?',
                'question_type' => 'single_choice',
                'points' => 10,
                'options' => ['Flash', 'Thinking', 'Redesign', 'Preview'],
                'correct' => 1, // 'Thinking' (index 1) is correct
                'correct_answer' => '1'
            ],
            [
                'question_text' => 'Select all UI tools developed by Google:',
                'question_type' => 'multiple_choice',
                'points' => 15,
                'options' => ['Stitch', 'Flutter', 'Xcode', 'Figma'],
                'correct' => [0, 1], // 'Stitch' (index 0) and 'Flutter' (index 1) are correct
                'correct_answer' => ['0', '1']
            ],
            [
                'question_text' => 'Flash Mode prioritizes quality over speed.',
                'question_type' => 'true_false',
                'points' => 5,
                'correct_answer' => 'false' // false is correct
            ],
            [
                'question_text' => 'What does design system unify?',
                'question_type' => 'fill_in_blank',
                'points' => 10,
                'correct_answer' => 'Visual Identity'
            ]
        ];

        $quizQuestionsJson = json_encode($originalQuestions);

        // 2. Start quiz attempt: Shuffle questions and options
        $shuffledQuestions = $this->invokeControllerMethod('getShuffledQuestions', [
            $quizQuestionsJson,
            true, // shuffleQuestions = true
            true  // shuffleAnswers = true
        ]);

        // Assertions for Shuffling Structure
        $this->assertCount(4, $shuffledQuestions, 'Shuffled questions must preserve the original questions count');

        // Locate each shuffled question by its original text to inspect translations
        $qSingleChoice = null;
        $qMultipleChoice = null;
        $qTrueFalse = null;
        $qFillBlank = null;

        foreach ($shuffledQuestions as $q) {
            if (strpos($q['question_text'], 'highest quality') !== false) {
                $qSingleChoice = $q;
            } elseif (strpos($q['question_text'], 'UI tools') !== false) {
                $qMultipleChoice = $q;
            } elseif (strpos($q['question_text'], 'Flash Mode') !== false) {
                $qTrueFalse = $q;
            } elseif (strpos($q['question_text'], 'design system') !== false) {
                $qFillBlank = $q;
            }
        }

        $this->assertNotNull($qSingleChoice, 'Single choice question must exist in shuffled list');
        $this->assertNotNull($qMultipleChoice, 'Multiple choice question must exist in shuffled list');
        $this->assertNotNull($qTrueFalse, 'True/False question must exist in shuffled list');
        $this->assertNotNull($qFillBlank, 'Fill in blank question must exist in shuffled list');

        // 3. Verify Single Choice Option Translation
        // Original: ['Flash', 'Thinking', 'Redesign', 'Preview'], Correct: 'Thinking'
        $optionsSingle = $qSingleChoice['options'];
        $correctIndexSingle = $qSingleChoice['correct'];
        $correctAnswerValueSingle = $qSingleChoice['correct_answer'];

        $this->assertEquals('Thinking', $optionsSingle[$correctIndexSingle], 'Translated correct index must point to "Thinking" in the shuffled options');
        $this->assertEquals((string)$correctIndexSingle, $correctAnswerValueSingle, 'correct_answer field must match the string of correct index');

        // 4. Verify Multiple Choice Options Translation
        // Original: ['Stitch', 'Flutter', 'Xcode', 'Figma'], Correct: 'Stitch' (0) and 'Flutter' (1)
        $optionsMultiple = $qMultipleChoice['options'];
        $correctIndicesMultiple = $qMultipleChoice['correct'];
        $correctAnswersValuesMultiple = $qMultipleChoice['correct_answer'];

        $this->assertIsArray($correctIndicesMultiple, 'Multiple choice correct field must be an array');
        $this->assertCount(2, $correctIndicesMultiple, 'Multiple choice must have exactly 2 correct indices');

        foreach ($correctIndicesMultiple as $idx) {
            $optText = $optionsMultiple[$idx];
            $this->assertTrue(in_array($optText, ['Stitch', 'Flutter']), "Correct index $idx must map to either 'Stitch' or 'Flutter' in shuffled options");
        }
        $this->assertEquals(array_map('strval', $correctIndicesMultiple), $correctAnswersValuesMultiple, 'correct_answer array must align with correct index array');

        // 5. Test Grading Scenario 1: Student answers all questions CORRECTLY based on display order
        $correctAnswers = [];
        foreach ($shuffledQuestions as $shuffledIdx => $q) {
            if ($q['question_type'] === 'single_choice') {
                // Submit the translated correct index
                $correctAnswers[$shuffledIdx] = $q['correct'];
            } elseif ($q['question_type'] === 'multiple_choice') {
                // Submit the translated correct indices
                $correctAnswers[$shuffledIdx] = $q['correct'];
            } elseif ($q['question_type'] === 'true_false') {
                $correctAnswers[$shuffledIdx] = $q['correct_answer'];
            } else {
                // fill_in_blank
                $correctAnswers[$shuffledIdx] = $q['correct_answer'];
            }
        }

        // Evaluate grading for correct answers
        $gradedCount = 0;
        foreach ($shuffledQuestions as $shuffledIdx => $q) {
            $userAns = $correctAnswers[$shuffledIdx];
            $isCorrect = $this->invokeControllerMethod('gradeQuestion', [$q, $userAns]);
            $this->assertTrue($isCorrect, "Question of type '{$q['question_type']}' must be graded CORRECT");
            if ($isCorrect) $gradedCount++;
        }
        $this->assertEquals(4, $gradedCount, 'Student must get 4/4 correct answers');

        // 6. Test Grading Scenario 2: Student answers all questions INCORRECTLY
        $incorrectAnswers = [];
        foreach ($shuffledQuestions as $shuffledIdx => $q) {
            if ($q['question_type'] === 'single_choice') {
                // Submit an incorrect index
                $incorrectAnswers[$shuffledIdx] = ($q['correct'] + 1) % 4;
            } elseif ($q['question_type'] === 'multiple_choice') {
                // Submit incorrect indices (empty or wrong ones)
                $incorrectAnswers[$shuffledIdx] = [($q['correct'][0] + 2) % 4];
            } elseif ($q['question_type'] === 'true_false') {
                $incorrectAnswers[$shuffledIdx] = $q['correct_answer'] === 'true' ? 'false' : 'true';
            } else {
                $incorrectAnswers[$shuffledIdx] = 'Wrong Answer Text';
            }
        }

        // Evaluate grading for incorrect answers
        $incorrectGradedCount = 0;
        foreach ($shuffledQuestions as $shuffledIdx => $q) {
            $userAns = $incorrectAnswers[$shuffledIdx];
            $isCorrect = $this->invokeControllerMethod('gradeQuestion', [$q, $userAns]);
            $this->assertFalse($isCorrect, "Question of type '{$q['question_type']}' must be graded INCORRECT");
            if ($isCorrect) $incorrectGradedCount++;
        }
        $this->assertEquals(0, $incorrectGradedCount, 'Student must get 0/4 correct answers');
    }

    /**
     * Test backward compatibility: ensure that imported JSONs with string correct answers (not indices)
     * are still graded correctly by the system before they are updated or edited by the admin.
     */
    public function testGradingBackwardCompatibilityWithStringCorrectAnswers()
    {
        $importedQuestion = [
            'question_text' => 'What is the capital of France?',
            'question_type' => 'single_choice',
            'options' => ['London', 'Paris', 'Berlin', 'Madrid'],
            'correct_answer' => 'Paris' // text string, no index
        ];

        // If the user answers '1' (Paris is option index 1)
        $isCorrectIndex = $this->invokeControllerMethod('gradeQuestion', [$importedQuestion, '1']);
        $this->assertTrue($isCorrectIndex, 'Grading should support index matching even if correct_answer is option text');

        // If the user answers 'Paris' directly
        $isCorrectText = $this->invokeControllerMethod('gradeQuestion', [$importedQuestion, 'Paris']);
        $this->assertTrue($isCorrectText, 'Grading should support text matching if correct_answer is option text');

        // If the user answers incorrectly '0'
        $isIncorrectIndex = $this->invokeControllerMethod('gradeQuestion', [$importedQuestion, '0']);
        $this->assertFalse($isIncorrectIndex, 'Incorrect index must be graded false');

        // If the user answers incorrectly 'Berlin'
        $isIncorrectText = $this->invokeControllerMethod('gradeQuestion', [$importedQuestion, 'Berlin']);
        $this->assertFalse($isIncorrectText, 'Incorrect text must be graded false');
    }
}
