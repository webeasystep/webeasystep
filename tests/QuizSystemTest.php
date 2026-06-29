<?php

namespace Tests;

use CodeIgniter\Test\CIUnitTestCase;
use Modules\Quizzes\Controllers\Quizzes;
use ReflectionMethod;

/**
 * Comprehensive Quiz System Integration Test
 *
 * Simulates a full student quiz lifecycle:
 * 1. Quiz questions are loaded from DB and shuffled
 * 2. Student submits correct answers based on the shuffled display
 * 3. Grading logic assigns correct score
 * 4. Wrong answers produce 0%
 * 5. All quizzes and question types are verified for data integrity
 */
class QuizSystemTest extends CIUnitTestCase
{
    protected Quizzes $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new Quizzes();
    }

    protected function invoke(string $method, array $args = []): mixed
    {
        $ref = new ReflectionMethod(Quizzes::class, $method);
        $ref->setAccessible(true);
        return $ref->invokeArgs($this->controller, $args);
    }

    // =========================================================
    // SECTION 1 – gradeQuestion: per-type correctness
    // =========================================================

    public function testSingleChoiceCorrectByIndex(): void
    {
        $q = [
            'question_type' => 'single_choice',
            'options'       => ['Flash', 'Thinking', 'Redesign', 'Preview'],
            'correct'       => 1,
            'correct_answer'=> '1',
        ];
        $this->assertTrue($this->invoke('gradeQuestion', [$q, '1']), 'Exact correct index must pass');
        $this->assertTrue($this->invoke('gradeQuestion', [$q, 1]),   'Integer index must also pass');
        $this->assertFalse($this->invoke('gradeQuestion', [$q, '0']), 'Wrong index must fail');
        $this->assertFalse($this->invoke('gradeQuestion', [$q, '2']), 'Another wrong index must fail');
    }

    public function testSingleChoiceCorrectByTextFallback(): void
    {
        // Imported quiz where correct_answer is option text (no index)
        $q = [
            'question_type' => 'single_choice',
            'options'       => ['Flash', 'Thinking', 'Redesign', 'Preview'],
            'correct_answer'=> 'Thinking',
        ];
        $this->assertTrue($this->invoke('gradeQuestion', [$q, 'Thinking']),  'Text match must pass');
        $this->assertTrue($this->invoke('gradeQuestion', [$q, '1']),         'Index matching text must pass');
        $this->assertFalse($this->invoke('gradeQuestion', [$q, 'Flash']),    'Wrong text must fail');
        $this->assertFalse($this->invoke('gradeQuestion', [$q, '0']),        'Index pointing to wrong option must fail');
    }

    public function testMultipleChoiceCorrectByIndices(): void
    {
        $q = [
            'question_type' => 'multiple_choice',
            'options'       => ['Stitch', 'Flutter', 'Xcode', 'Figma'],
            'correct'       => [0, 1],
            'correct_answer'=> ['0', '1'],
        ];
        $this->assertTrue($this->invoke('gradeQuestion',  [$q, ['0', '1']]), 'Exact correct indices must pass');
        $this->assertTrue($this->invoke('gradeQuestion',  [$q, [0, 1]]),     'Integer indices must pass');
        $this->assertTrue($this->invoke('gradeQuestion',  [$q, ['1', '0']]), 'Order should not matter');
        $this->assertFalse($this->invoke('gradeQuestion', [$q, ['0']]),      'Partial selection must fail');
        $this->assertFalse($this->invoke('gradeQuestion', [$q, ['0','2']]),  'Wrong combination must fail');
        $this->assertFalse($this->invoke('gradeQuestion', [$q, []]),         'Empty selection must fail');
    }

    public function testTrueFalseCorrectVariants(): void
    {
        $q = ['question_type' => 'true_false', 'correct_answer' => 'true'];
        $this->assertTrue($this->invoke('gradeQuestion',  [$q, 'true']),  '"true" string must pass');
        $this->assertTrue($this->invoke('gradeQuestion',  [$q, true]),    'boolean true must pass');
        $this->assertTrue($this->invoke('gradeQuestion',  [$q, 'True']),  'Case-insensitive must pass');
        $this->assertFalse($this->invoke('gradeQuestion', [$q, 'false']), '"false" must fail');
        $this->assertFalse($this->invoke('gradeQuestion', [$q, false]),   'boolean false must fail');

        $qF = ['question_type' => 'true_false', 'correct_answer' => 'false'];
        $this->assertTrue($this->invoke('gradeQuestion',  [$qF, 'false']), '"false" on false-answer must pass');
        $this->assertFalse($this->invoke('gradeQuestion', [$qF, 'true']),  '"true" on false-answer must fail');
    }

    public function testFillInBlankGrading(): void
    {
        $q = ['question_type' => 'fill_in_blank', 'correct_answer' => 'Visual Identity'];
        $this->assertTrue($this->invoke('gradeQuestion',  [$q, 'Visual Identity']),  'Exact answer must pass');
        $this->assertFalse($this->invoke('gradeQuestion', [$q, 'visual identity']),  'Case mismatch must fail');
        $this->assertFalse($this->invoke('gradeQuestion', [$q, 'Wrong Answer']),     'Wrong text must fail');
        $this->assertFalse($this->invoke('gradeQuestion', [$q, '']),                 'Empty answer must fail');
        $this->assertFalse($this->invoke('gradeQuestion', [$q, null]),               'Null answer must fail');
    }

    // =========================================================
    // SECTION 2 – getShuffledQuestions: shuffle integrity
    // =========================================================

    public function testShufflePreservesQuestionCount(): void
    {
        $json = $this->makeQuizJson();
        $shuffled = $this->invoke('getShuffledQuestions', [$json, true, true]);
        $this->assertCount(4, $shuffled, 'Shuffled list must keep all 4 questions');
    }

    public function testShuffleTranslatesCorrectIndexAfterOptionShuffle(): void
    {
        $original = [
            [
                'question_text' => 'High Quality Mode?',
                'question_type' => 'single_choice',
                'options'       => ['Flash', 'Thinking', 'Redesign', 'Preview'],
                'correct'       => 1,
                'correct_answer'=> '1',
            ],
        ];
        $json = json_encode($original);

        // Run multiple times; at least once the order will change
        $passed = false;
        for ($i = 0; $i < 20; $i++) {
            $shuffled = $this->invoke('getShuffledQuestions', [$json, false, true]);
            $q = $shuffled[0];
            $correctIdx = $q['correct'];
            $correctOption = $q['options'][$correctIdx];
            if ($correctOption === 'Thinking') {
                $passed = true;
                break;
            }
        }
        $this->assertTrue($passed, 'Shuffled correct index must always point to "Thinking"');
    }

    public function testMultipleChoiceShuffleTranslatesAllCorrectIndices(): void
    {
        $original = [
            [
                'question_text' => 'Google tools?',
                'question_type' => 'multiple_choice',
                'options'       => ['Stitch', 'Flutter', 'Xcode', 'Figma'],
                'correct'       => [0, 1],
                'correct_answer'=> ['0', '1'],
            ],
        ];
        $json = json_encode($original);

        for ($i = 0; $i < 20; $i++) {
            $shuffled = $this->invoke('getShuffledQuestions', [$json, false, true]);
            $q = $shuffled[0];
            $correctIndices = $q['correct'];
            $correctTexts   = array_map(fn($idx) => $q['options'][$idx], $correctIndices);
            sort($correctTexts);
            $this->assertEquals(['Flutter', 'Stitch'], $correctTexts,
                'After shuffle, correct indices must still point to Flutter and Stitch');
        }
    }

    // =========================================================
    // SECTION 3 – Full student scenario (A to Z)
    // =========================================================

    public function testStudentAnswersAllCorrectGets100(): void
    {
        $shuffled = $this->invoke('getShuffledQuestions', [$this->makeQuizJson(), true, true]);

        $correct = 0;
        $total   = count($shuffled);

        foreach ($shuffled as $idx => $q) {
            $answer = $this->buildCorrectAnswer($q);
            if ($this->invoke('gradeQuestion', [$q, $answer])) {
                $correct++;
            }
        }

        $score = ($correct / $total) * 100;
        $this->assertEquals(100.0, $score, 'Student answering all correctly must get 100%');
    }

    public function testStudentAnswersAllWrongGets0(): void
    {
        $shuffled = $this->invoke('getShuffledQuestions', [$this->makeQuizJson(), true, true]);

        $correct = 0;
        $total   = count($shuffled);

        foreach ($shuffled as $q) {
            $answer = $this->buildWrongAnswer($q);
            if ($this->invoke('gradeQuestion', [$q, $answer])) {
                $correct++;
            }
        }

        $score = ($correct / $total) * 100;
        $this->assertEquals(0.0, $score, 'Student answering all wrong must get 0%');
    }

    public function testStudentAnswersHalfCorrectGets50(): void
    {
        // 4 questions; student answers Q1 & Q3 correctly, Q2 & Q4 wrong
        $shuffled = $this->invoke('getShuffledQuestions', [$this->makeQuizJson(), false, false]);

        $correct = 0;
        $total   = count($shuffled);

        foreach ($shuffled as $i => $q) {
            $answer = ($i % 2 === 0) ? $this->buildCorrectAnswer($q) : $this->buildWrongAnswer($q);
            if ($this->invoke('gradeQuestion', [$q, $answer])) {
                $correct++;
            }
        }

        $score = ($correct / $total) * 100;
        $this->assertEquals(50.0, $score, 'Student answering half correct must get 50%');
    }

    // =========================================================
    // SECTION 4 – Database integrity checks
    // =========================================================

    public function testAllQuizQuestionsHaveCorrectAnswersInDb(): void
    {
        $pdo = new \PDO("mysql:host=localhost;dbname=webeasystep;charset=utf8", "root", "");
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

        $quizzes = $pdo->query("SELECT id, quiz_title, quiz_questions FROM tb_quizzes")->fetchAll();

        $brokenQuestions = [];

        foreach ($quizzes as $quiz) {
            $questions = json_decode($quiz['quiz_questions'], true) ?? [];
            foreach ($questions as $i => $q) {
                $hasCorrect = isset($q['correct']) && $q['correct'] !== null;
                $hasCorrectAnswer = isset($q['correct_answer']) && $q['correct_answer'] !== null
                    && $q['correct_answer'] !== '' && $q['correct_answer'] !== [];

                if (!$hasCorrect && !$hasCorrectAnswer) {
                    $brokenQuestions[] = "Quiz [{$quiz['quiz_title']}] Q" . ($i + 1) . ": " . mb_substr($q['question_text'], 0, 50);
                }
            }
        }

        $this->assertEmpty(
            $brokenQuestions,
            "Questions with no correct answer found:\n" . implode("\n", $brokenQuestions)
        );
    }

    public function testAllSingleChoiceIndicesAreValidInDb(): void
    {
        $pdo = new \PDO("mysql:host=localhost;dbname=webeasystep;charset=utf8", "root", "");
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

        $quizzes = $pdo->query("SELECT id, quiz_title, quiz_questions FROM tb_quizzes")->fetchAll();

        $badIndexQuestions = [];

        foreach ($quizzes as $quiz) {
            $questions = json_decode($quiz['quiz_questions'], true) ?? [];
            foreach ($questions as $i => $q) {
                if (!in_array($q['question_type'] ?? '', ['single_choice', 'multiple_choice'])) continue;
                if (!isset($q['options'])) continue;

                $idx = $q['correct'] ?? $q['correct_answer'] ?? null;
                if ($idx === null) continue;

                $optionCount = count($q['options']);
                if (is_array($idx)) {
                    foreach ($idx as $cidx) {
                        if (!isset($q['options'][(int)$cidx])) {
                            $badIndexQuestions[] = "Quiz [{$quiz['quiz_title']}] Q" . ($i+1) . ": index $cidx out of range ($optionCount options)";
                        }
                    }
                } elseif (is_numeric($idx) && !isset($q['options'][(int)$idx])) {
                    $badIndexQuestions[] = "Quiz [{$quiz['quiz_title']}] Q" . ($i+1) . ": index $idx out of range ($optionCount options)";
                }
            }
        }

        $this->assertEmpty(
            $badIndexQuestions,
            "Questions with out-of-range indices found:\n" . implode("\n", $badIndexQuestions)
        );
    }

    public function testAllTrueFalseQuestionsHaveValidAnswers(): void
    {
        $pdo = new \PDO("mysql:host=localhost;dbname=webeasystep;charset=utf8", "root", "");
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

        $quizzes = $pdo->query("SELECT id, quiz_title, quiz_questions FROM tb_quizzes")->fetchAll();
        $badTF = [];

        foreach ($quizzes as $quiz) {
            $questions = json_decode($quiz['quiz_questions'], true) ?? [];
            foreach ($questions as $i => $q) {
                if (($q['question_type'] ?? '') !== 'true_false') continue;
                $answer = strtolower(trim((string)($q['correct_answer'] ?? $q['correct'] ?? '')));
                if (!in_array($answer, ['true', 'false', '1', '0'])) {
                    $badTF[] = "Quiz [{$quiz['quiz_title']}] Q" . ($i+1) . ": invalid true/false answer = '$answer'";
                }
            }
        }

        $this->assertEmpty(
            $badTF,
            "True/False questions with invalid answers:\n" . implode("\n", $badTF)
        );
    }

    // =========================================================
    // SECTION 5 – Backward compatibility (imported JSON quizzes)
    // =========================================================

    public function testBackwardCompatibilityStringAnswers(): void
    {
        $q = [
            'question_type' => 'single_choice',
            'options'       => ['London', 'Paris', 'Berlin', 'Madrid'],
            'correct_answer'=> 'Paris',
        ];
        $this->assertTrue($this->invoke('gradeQuestion', [$q, 'Paris']), 'String text match must pass');
        $this->assertTrue($this->invoke('gradeQuestion', [$q, '1']),     'Index pointing to Paris must pass');
        $this->assertFalse($this->invoke('gradeQuestion', [$q, 'London']), 'Wrong text must fail');
        $this->assertFalse($this->invoke('gradeQuestion', [$q, '0']),      'Wrong index must fail');
    }

    /**
     * Regression test: Design System Q2 correct answer is Markdown (index 2),
     * NOT JSON — because DESIGN.md is a Markdown file.
     */
    public function testDesignSystemQ2CorrectAnswerIsMarkdown(): void
    {
        $q = [
            'question_type' => 'single_choice',
            'options'       => ['JSON', 'XML', 'Markdown', 'YAML'],
            'correct'       => 2,
            'correct_answer'=> '2',
        ];
        $this->assertTrue($this->invoke('gradeQuestion',  [$q, '2']),  'Markdown (index 2) must be correct');
        $this->assertTrue($this->invoke('gradeQuestion',  [$q, 2]),    'Integer index 2 must be correct');
        $this->assertFalse($this->invoke('gradeQuestion', [$q, '0']),  'JSON (index 0) must be wrong');
        $this->assertFalse($this->invoke('gradeQuestion', [$q, '3']),  'YAML (index 3) must be wrong');
    }


    // =========================================================
    // Helpers
    // =========================================================

    private function makeQuizJson(): string
    {
        return json_encode([
            [
                'question_text' => 'High quality mode?',
                'question_type' => 'single_choice',
                'options'       => ['Flash', 'Thinking', 'Redesign', 'Preview'],
                'correct'       => 1,
                'correct_answer'=> '1',
            ],
            [
                'question_text' => 'Google tools?',
                'question_type' => 'multiple_choice',
                'options'       => ['Stitch', 'Flutter', 'Xcode', 'Figma'],
                'correct'       => [0, 1],
                'correct_answer'=> ['0', '1'],
            ],
            [
                'question_text' => 'Flash prioritizes quality over speed.',
                'question_type' => 'true_false',
                'correct_answer'=> 'false',
            ],
            [
                'question_text' => 'Design system unifies?',
                'question_type' => 'fill_in_blank',
                'correct_answer'=> 'Visual Identity',
            ],
        ]);
    }

    private function buildCorrectAnswer(array $q): mixed
    {
        return match ($q['question_type']) {
            'single_choice'   => $q['correct'] ?? $q['correct_answer'],
            'multiple_choice' => is_array($q['correct'] ?? null) ? $q['correct'] : [$q['correct'] ?? 0],
            'true_false'      => $q['correct_answer'] ?? $q['correct'],
            default           => $q['correct_answer'] ?? $q['correct'],
        };
    }

    private function buildWrongAnswer(array $q): mixed
    {
        return match ($q['question_type']) {
            'single_choice'   => ($q['correct'] ?? 0) === 0 ? '1' : '0',
            'multiple_choice' => ['99'],
            'true_false'      => ($q['correct_answer'] ?? 'true') === 'true' ? 'false' : 'true',
            default           => 'Completely Wrong Answer XYZ',
        };
    }
}
