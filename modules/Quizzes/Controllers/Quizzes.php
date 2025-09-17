<?php

namespace Modules\Quizzes\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use Modules\Quizzes\Models\QuizzesModel;
use Modules\Quizzes\Models\QuizAttemptsModel;
use Modules\Users\Models\UsersModel;

class Quizzes extends BaseController
{
    protected $quizzesModel;
    protected $attemptsModel;
    protected $usersModel;

    public function __construct()
    {
        $this->quizzesModel = new QuizzesModel();
        $this->attemptsModel = new QuizAttemptsModel();
        $this->usersModel = new UsersModel();
        helper(['form', 'function', 'url']);
    }

    /**
     * Display quiz for taking
     */
    public function take($quizId)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/users/login')->with('error', 'Please login to take quizzes.');
        }

        $quiz = $this->quizzesModel->find($quizId);
        if (!$quiz || !$quiz->active) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Quiz not found');
        }

        // Check if user has exceeded max attempts
        $attemptCount = $this->attemptsModel->getUserAttemptCount($userId, $quizId);
        if ($attemptCount >= $quiz->max_attempts) {
            return redirect()->back()->with('error', 'You have exceeded the maximum number of attempts for this quiz.');
        }

        // Check if user has an active attempt
        $activeAttempt = $this->attemptsModel->getActiveAttempt($userId, $quizId);
        if ($activeAttempt) {
            return redirect()->to("/quizzes/continue/{$activeAttempt->id}");
        }

        $data = [
            'title' => $quiz->quiz_title,
            'quiz' => $quiz,
            'attempt_count' => $attemptCount
        ];

        return View('Site', 'quiz_start', $data);
    }

    /**
     * Start a new quiz attempt
     */
    public function startAttempt($quizId)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/users/login');
        }

        $quiz = $this->quizzesModel->find($quizId);
        if (!$quiz) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Quiz not found');
        }

        // Check attempt limits
        $attemptCount = $this->attemptsModel->getUserAttemptCount($userId, $quizId);
        if ($attemptCount >= $quiz->max_attempts) {
            return redirect()->back()->with('error', 'Maximum attempts exceeded.');
        }

        // Create new attempt
        $attemptData = [
            'quiz_id' => $quizId,
            'user_id' => $userId,
            'attempt_number' => $attemptCount + 1,
            'status' => 'in_progress',
            'started_at' => date('Y-m-d H:i:s'),
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString()
        ];

        $attemptId = $this->attemptsModel->insert($attemptData);

        return redirect()->to("/quizzes/continue/{$attemptId}");
    }

    /**
     * Continue an active quiz attempt
     */
    public function continueAttempt($attemptId)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/users/login');
        }

        $attempt = $this->attemptsModel->find($attemptId);
        if (!$attempt || $attempt->user_id != $userId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Attempt not found');
        }

        if ($attempt->status !== 'in_progress') {
            return redirect()->to("/quizzes/results/{$attemptId}");
        }

        $quiz = $this->quizzesModel->find($attempt->quiz_id);

        // Check if time limit exceeded
        if ($quiz->time_limit) {
            $startTime = new \DateTime($attempt->started_at);
            $now = new \DateTime();
            $elapsed = $now->diff($startTime)->i; // minutes

            if ($elapsed >= $quiz->time_limit) {
                // Auto-submit quiz
                $this->attemptsModel->update($attemptId, [
                    'status' => 'expired',
                    'completed_at' => date('Y-m-d H:i:s')
                ]);
                return redirect()->to("/quizzes/results/{$attemptId}")->with('warning', 'Quiz time expired.');
            }
        }

        // Prepare questions (shuffle if enabled)
        $questions = json_decode($quiz->quiz_questions, true);
        if ($quiz->shuffle_questions) {
            shuffle($questions);
        }

        // Shuffle answers if enabled
        if ($quiz->shuffle_answers) {
            foreach ($questions as &$question) {
                if (isset($question['options'])) {
                    shuffle($question['options']);
                }
            }
        }

        $data = [
            'title' => $quiz->quiz_title,
            'quiz' => $quiz,
            'attempt' => $attempt,
            'questions' => $questions,
            'time_remaining' => $quiz->time_limit ? ($quiz->time_limit * 60) - ($elapsed * 60) : null
        ];

        return View('Site', 'quiz_take', $data);
    }

    /**
     * Submit quiz answers
     */
    public function submitAnswers($attemptId)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/users/login');
        }

        $attempt = $this->attemptsModel->find($attemptId);
        if (!$attempt || $attempt->user_id != $userId || $attempt->status !== 'in_progress') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Invalid attempt');
        }

        $quiz = $this->quizzesModel->find($attempt->quiz_id);
        $answers = $this->request->getPost('answers');

        // Calculate score
        $questions = json_decode($quiz->quiz_questions, true);
        $totalQuestions = count($questions);
        $correctAnswers = 0;

        foreach ($questions as $index => $question) {
            $userAnswer = $answers[$index] ?? null;
            $correctAnswer = $question['correct_answer'] ?? null;

            if ($userAnswer == $correctAnswer) {
                $correctAnswers++;
            }
        }

        $score = ($correctAnswers / $totalQuestions) * 100;
        $timeTaken = (new \DateTime())->diff(new \DateTime($attempt->started_at))->s;

        // Update attempt
        $updateData = [
            'answers_data' => json_encode($answers),
            'score' => $score,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers,
            'time_taken' => $timeTaken,
            'status' => 'completed',
            'completed_at' => date('Y-m-d H:i:s')
        ];

        $this->attemptsModel->update($attemptId, $updateData);

        return redirect()->to("/quizzes/results/{$attemptId}");
    }

    /**
     * Show quiz results
     */
    public function results($attemptId)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/users/login');
        }

        $attempt = $this->attemptsModel->find($attemptId);
        if (!$attempt || $attempt->user_id != $userId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Results not found');
        }

        $quiz = $this->quizzesModel->find($attempt->quiz_id);

        // Check if results should be shown
        if ($quiz->show_results === 'never') {
            return redirect()->back()->with('info', 'Quiz results are not available.');
        }

        $data = [
            'title' => 'Quiz Results',
            'quiz' => $quiz,
            'attempt' => $attempt,
            'passed' => $attempt->score >= $quiz->passing_score
        ];

        return View('Site', 'quiz_results', $data);
    }

    /**
     * Import quiz from JSON (Admin only)
     */
    public function importJson()
    {
        // Check admin permissions
        if (session()->get('group_id') != 1) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Access denied');
        }

        if ($this->request->getMethod() === 'POST') {
            $jsonFile = $this->request->getFile('json_file');

            if (!$jsonFile->isValid()) {
                return redirect()->back()->with('error', 'Please select a valid JSON file.');
            }

            $jsonContent = file_get_contents($jsonFile->getTempName());
            $quizData = json_decode($jsonContent, true);

            if (!$quizData) {
                return redirect()->back()->with('error', 'Invalid JSON format.');
            }

            // Validate required fields
            $required = ['quiz_title', 'course_id', 'questions'];
            foreach ($required as $field) {
                if (!isset($quizData[$field])) {
                    return redirect()->back()->with('error', "Missing required field: {$field}");
                }
            }

            // Insert quiz
            $insertData = [
                'course_id' => $quizData['course_id'],

                'quiz_title' => $quizData['quiz_title'],
                'quiz_desc' => $quizData['quiz_desc'] ?? null,
                'quiz_questions' => json_encode($quizData['questions']),
                'time_limit' => $quizData['time_limit'] ?? null,
                'max_attempts' => $quizData['max_attempts'] ?? 3,
                'passing_score' => $quizData['passing_score'] ?? 70.00,
                'shuffle_questions' => $quizData['shuffle_questions'] ?? 1,
                'shuffle_answers' => $quizData['shuffle_answers'] ?? 1,
                'show_results' => $quizData['show_results'] ?? 'after_completion',
                'active' => 1
            ];

            $quizId = $this->quizzesModel->insert($insertData);

            if ($quizId) {
                return redirect()->back()->with('success', 'Quiz imported successfully.');
            }
        }

        $data = ['title' => 'Import Quiz from JSON'];
        return View('Admin', 'import_json', $data);
    }
}
