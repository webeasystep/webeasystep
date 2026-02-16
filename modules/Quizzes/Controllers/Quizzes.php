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
     * Redirect GET requests to embedded quiz to proper error handling
     */
    public function redirectToEmbeddedQuiz($quizId)
    {
        // Embedded quizzes should only be accessed via POST requests
        // Return JSON error for AJAX requests, redirect for regular requests
        if ($this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Embedded quizzes must be started via POST request'
            ]);
        }

        // For non-AJAX requests, redirect back with error
        return redirect()->back()->with('error', 'Invalid quiz access method');
    }

    /**
     * Test method to check session data and authentication
     */
    public function testSession()
    {
        $response = [
            'session_id' => session_id(),
            'session_data' => $_SESSION ?? [],
            'auth_logged_in' => auth()->loggedIn(),
            'direct_user_id' => session()->get('user')['id'] ?? null,
            'session_keys' => array_keys($_SESSION ?? [])
        ];

        if (auth()->loggedIn()) {
            $user = auth()->user();
            $response['auth_user_id'] = $user->id ?? null;
            $response['auth_user_email'] = $user->email ?? null;
        }

        return $this->response->setJSON($response);
    }

    /**
     * Display quizzes index page
     */
    public function index()
    {
        $data = [
            'title' => 'Quizzes',
            'quizzes' => $this->quizzesModel->select('tb_quizzes.*, tb_courses.course_title, tb_courses.slug')
                                           ->join('tb_courses', 'tb_courses.id = tb_quizzes.course_id')
                                           ->where('tb_quizzes.active', 1)
                                           ->orderBy('tb_quizzes.created_at', 'DESC')
                                           ->findAll()
        ];

        return view('site/index', $data);
    }

    /**
     * View specific quiz attempt details
     */
    public function viewAttempt($attemptId)
    {
        // Get user ID from session (Shield stores it as user.id)
        $sessionUser = session()->get('user');
        $userId = $sessionUser['id'] ?? null;
        
        if (!$userId) {
            return redirect()->to('/users/login')->with('error', 'Please login to view your attempts.');
        }

        $attempt = $this->attemptsModel->find($attemptId);
        if (!$attempt || $attempt->user_id != $userId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Attempt not found or access denied');
        }

        $quiz = $this->quizzesModel->getQuizWithCourse($attempt->quiz_id);
        if (!$quiz) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Quiz not found');
        }

        $data = [
            'title' => 'Quiz Attempt Details - ' . $quiz->quiz_title,
            'attempt' => $attempt,
            'quiz' => $quiz,
            'user_answers' => json_decode($attempt->user_answers, true) ?? [],
            'quiz_questions' => json_decode($attempt->quiz_questions, true) ?? []
        ];

        return view('site/attempt_details', $data);
    }

    /**
     * Redirect GET requests to submit URL to proper quiz workflow
     */
    public function redirectToQuiz($quizId)
    {
        // Get user ID from session (Shield stores it as user.id)
        $sessionUser = session()->get('user');
        $userId = $sessionUser['id'] ?? null;
        
        if (!$userId) {
            return redirect()->to('/users/login')->with('error', 'Please login to take quizzes.');
        }

        // Check if user has an active attempt for this quiz
        $activeAttempt = $this->attemptsModel->getUserLatestAttempt($userId, $quizId);

        if ($activeAttempt && $activeAttempt->score == 0) {
            // User has an incomplete attempt, redirect to continue
            return redirect()->to("/quizzes/continue/{$activeAttempt->id}");
        } elseif ($activeAttempt && $activeAttempt->score > 0) {
            // User has completed attempt, show results
            return redirect()->to("/quizzes/results/{$activeAttempt->id}");
        } else {
            // No attempt found, redirect to quiz start page
            return redirect()->to("/quizzes/take/{$quizId}");
        }
    }

    /**
     * Display quiz for taking
     */
    public function take($quizId)
    {
        $user = session()->get('user');
        $userId = $user['id'] ?? null;
        if (!$userId) {
            return redirect()->to('/users/login')->with('error', 'Please login to take quizzes.');
        }

        $quiz = $this->quizzesModel->find($quizId);
        if (!$quiz || !$quiz->active) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Quiz not found');
        }

        // Check if user has exceeded max attempts
        $attemptCount = $this->attemptsModel->getUserAttemptCount($userId, $quizId);

        // Debug logging for attempt count
        log_message('debug', "QUIZ_TAKE_DEBUG: User ID: {$userId}, Quiz ID: {$quizId}, Attempt Count: {$attemptCount}, Max Attempts: {$quiz->max_attempts}");

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

        return view('site/quiz_start', $data);
    }

    /**
     * Start a new quiz attempt
     */
    public function startAttempt($quizId)
    {
        // Get user ID from session (Shield stores it as user.id)
        $sessionUser = session()->get('user');
        $userId = $sessionUser['id'] ?? null;
        
        if (!$userId) {
            return redirect()->to('/users/login')->with('error', 'Please login to start quiz attempts.');
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
            'user_answers' => json_encode([]),
            'quiz_questions' => $quiz->quiz_questions,
            'attempt_date' => date('Y-m-d H:i:s')
        ];

        $attemptId = $this->attemptsModel->insert($attemptData);

        return redirect()->to("/quizzes/continue/{$attemptId}");
    }

    /**
     * Continue an active quiz attempt
     */
    public function continueAttempt($attemptId)
    {
        // Get user ID from session (Shield stores it as user.id)
        $sessionUser = session()->get('user');
        $userId = $sessionUser['id'] ?? null;
        log_message('debug', "QUIZ_CONTINUE_DEBUG: User ID from session: " . ($userId ?? 'null'));
        if (!$userId) {
            log_message('debug', "QUIZ_CONTINUE_DEBUG: No user ID, redirecting to login");
            return redirect()->to('/users/login');
        }

        $attempt = $this->attemptsModel->find($attemptId);
        if (!$attempt || $attempt->user_id != $userId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Attempt not found');
        }

        // Check if already completed (score > 0 OR time_taken_seconds is set means completed)
        if ($attempt->score > 0 || (!is_null($attempt->time_taken_seconds) && $attempt->time_taken_seconds > 0)) {
            return redirect()->to("/quizzes/results/{$attemptId}");
        }

        $quiz = $this->quizzesModel->getQuizWithCourse($attempt->quiz_id);

        // Check if time limit exceeded (using attempt_date as start time)
        if ($quiz->time_limit_minutes) {
            $startTime = new \DateTime($attempt->attempt_date);
            $now = new \DateTime();
            $elapsedSeconds = $now->getTimestamp() - $startTime->getTimestamp();
            $elapsedMinutes = floor($elapsedSeconds / 60);

            if ($elapsedMinutes >= $quiz->time_limit_minutes) {
                // Auto-submit quiz with 0 score
                $this->attemptsModel->update($attemptId, [
                    'score' => 0,
                    'time_taken_seconds' => $elapsedSeconds
                ]);
                return redirect()->to("/quizzes/results/{$attemptId}")->with('warning', 'Quiz time expired.');
            }
        }

        // Prepare questions (shuffle if enabled)
        $questions = json_decode($attempt->quiz_questions, true);
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

        // Get attempt count for display
        $attemptCount = $this->attemptsModel->getUserAttemptCount($userId, $attempt->quiz_id);
        log_message('debug', "QUIZ_CONTINUE_DEBUG: Attempt count calculated: " . $attemptCount);

        $data = [
            'title' => $quiz->quiz_title,
            'quiz' => $quiz,
            'attempt' => $attempt,
            'questions' => $questions,
            'attempt_count' => $attemptCount,
            'time_remaining' => $quiz->time_limit_minutes ? ($quiz->time_limit_minutes * 60) - $elapsedSeconds : null
        ];

        log_message('debug', "QUIZ_CONTINUE_DEBUG: Data array keys: " . implode(', ', array_keys($data)));
        log_message('debug', "QUIZ_CONTINUE_DEBUG: Returning view 'site/take'");
        return view('site/take', $data);
    }

    /**
     * Submit quiz answers
     */
    public function submitAnswers($attemptId)
    {
        // Get user ID from session (Shield stores it as user.id)
        $sessionUser = session()->get('user');
        $userId = $sessionUser['id'] ?? null;
        
        if (!$userId) {
            return redirect()->to('/users/login')->with('error', 'Please login to submit quiz answers.');
        }

        $attempt = $this->attemptsModel->find($attemptId);
        if (!$attempt || $attempt->user_id != $userId || $attempt->score > 0) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Invalid attempt');
        }

        $quiz = $this->quizzesModel->getQuizWithCourse($attempt->quiz_id);
        $answers = $this->request->getPost('answers');

        // Calculate score
        $questions = json_decode($attempt->quiz_questions, true);
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
        $timeTaken = (new \DateTime())->diff(new \DateTime($attempt->attempt_date))->s;

        // Update attempt
        $updateData = [
            'user_answers' => json_encode($answers),
            'score' => $score,
            'time_taken_seconds' => $timeTaken,
            'is_passed' => $score >= ($quiz->passing_score ?? 70)
        ];

        $this->attemptsModel->update($attemptId, $updateData);

        return redirect()->to("/quizzes/results/{$attemptId}");
    }

    /**
     * Show quiz results
     */
    public function results($attemptId)
    {
        // Get user ID from session (Shield stores it as user.id)
        $sessionUser = session()->get('user');
        $userId = $sessionUser['id'] ?? null;
        
        if (!$userId) {
            return redirect()->to('/users/login')->with('error', 'Please login to view quiz results.');
        }

        $attempt = $this->attemptsModel->find($attemptId);
        if (!$attempt || $attempt->user_id != $userId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Results not found');
        }

        $quiz = $this->quizzesModel->getQuizWithCourse($attempt->quiz_id);

        // Check if results should be shown
        if ($quiz->show_results === 'never') {
            return redirect()->back()->with('info', 'Quiz results are not available.');
        }

        // Calculate correct and wrong answers from user_answers and quiz_questions
        $userAnswers = json_decode($attempt->user_answers, true) ?? [];
        $questions = json_decode($attempt->quiz_questions, true) ?? [];
        $correctAnswers = 0;
        $totalQuestions = count($questions);

        foreach ($questions as $index => $question) {
            $userAnswer = $userAnswers[$index] ?? null;
            $correctAnswer = $question['correct_answer'] ?? null;
            if ($userAnswer == $correctAnswer) {
                $correctAnswers++;
            }
        }

        $wrongAnswers = $totalQuestions - $correctAnswers;

        // Check if user can retake the quiz
        $userAttempts = $this->attemptsModel->getUserAttemptCount($userId, $quiz->id);
        $canRetake = $quiz->max_attempts > 0 && $userAttempts < $quiz->max_attempts;

        // Add attempt number if not present
        if (!isset($attempt->attempt_number)) {
            $attempt->attempt_number = $userAttempts;
        }

        // Add submitted_at if not present (use created_at as fallback)
        if (!isset($attempt->submitted_at)) {
            $attempt->submitted_at = $attempt->created_at;
        }

        // Add completion_time_seconds if not present (use time_taken_seconds as fallback)
        if (!isset($attempt->completion_time_seconds)) {
            $attempt->completion_time_seconds = $attempt->time_taken_seconds ?? 0;
        }

        $data = [
            'title' => 'Quiz Results',
            'quiz' => $quiz,
            'attempt' => $attempt,
            'passed' => $attempt->score >= $quiz->passing_score,
            'correct_answers' => $correctAnswers,
            'wrong_answers' => $wrongAnswers,
            'total_questions' => $totalQuestions,
            'can_retake' => $canRetake,
            'user_attempts' => $userAttempts
        ];

        return view('site/results', $data);
    }

    /**
     * Display user's quiz attempts and history
     */
    public function myAttempts()
    {
        // Get user ID from session (Shield stores it as user.id)
        $sessionUser = session()->get('user');
        $userId = $sessionUser['id'] ?? null;
        
        if (!$userId) {
            return redirect()->to('/users/login')->with('error', 'Please login to view your quiz attempts.');
        }

        // Get user's quiz history
        $quizAttemptsModel = new \Modules\Quizzes\Models\QuizAttemptsModel();
        $attempts = $quizAttemptsModel->getUserQuizHistory($userId);

        $data = [
            'title' => 'My Quiz Attempts',
            'attempts' => $attempts
        ];

        return view('site/my_attempts', $data);
    }

    /**
     * Start embedded quiz for course integration
     * Returns JSON response with quiz data and attempt ID
     */
    public function startEmbedded($quizId)
    {
        // Ensure this is an AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        $userId = auth()->user()->id ?? null;
        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'Please login to take quizzes'
            ]);
        }

        $quiz = $this->quizzesModel->find($quizId);
        if (!$quiz || !$quiz->active) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Quiz not found or inactive'
            ]);
        }

        // Check if user has exceeded max attempts
        $attemptCount = $this->attemptsModel->getUserAttemptCount($userId, $quizId);
        if ($attemptCount >= $quiz->max_attempts) {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'Maximum attempts exceeded'
            ]);
        }

        // Check if user has an active attempt
        $activeAttempt = $this->attemptsModel->getActiveAttempt($userId, $quizId);
        if ($activeAttempt) {
            // Continue existing attempt
            $attemptId = $activeAttempt->id;
        } else {
            // Create new attempt
            $attemptData = [
                'quiz_id' => $quizId,
                'user_id' => $userId,
                'user_answers' => json_encode([]),
                'quiz_questions' => $quiz->quiz_questions,
                'attempt_date' => date('Y-m-d H:i:s')
            ];

            $attemptId = $this->attemptsModel->insert($attemptData);
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

        return $this->response->setJSON([
            'success' => true,
            'quiz' => [
                'id' => $quiz->id,
                'quiz_title' => $quiz->quiz_title,
                'quiz_desc' => $quiz->quiz_desc,
                'time_limit_minutes' => $quiz->time_limit_minutes,
                'passing_score' => $quiz->passing_score
            ],
            'questions' => $questions,
            'attempt_id' => $attemptId
        ]);
    }

    /**
     * Save answer for embedded quiz (auto-save functionality)
     */
    public function saveAnswer($attemptId)
    {
        // Ensure this is an AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        // Try multiple ways to get user ID from session (Shield compatibility)
        $sessionUser = session()->get('user');
        $userId = $sessionUser['id'] ?? null;
        
        if (!$userId) {
            // Fallback to old method for backward compatibility
            $userId = session()->get('user_id');
        }
        
        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        $attempt = $this->attemptsModel->find($attemptId);
        if (!$attempt || $attempt->user_id != $userId || $attempt->score > 0) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Invalid attempt'
            ]);
        }

        $input = $this->request->getJSON(true);
        $questionIndex = $input['question_index'] ?? null;
        $answer = $input['answer'] ?? null;

        if ($questionIndex === null) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Question index required'
            ]);
        }

        // Get current answers
        $currentAnswers = json_decode($attempt->user_answers, true) ?? [];

        // Update the specific answer
        $currentAnswers[$questionIndex] = $answer;

        // Save updated answers
        $this->attemptsModel->update($attemptId, [
            'user_answers' => json_encode($currentAnswers)
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Answer saved'
        ]);
    }

    /**
     * Submit embedded quiz and return results
     */
    public function submitEmbedded($attemptId)
    {
        // Force log to ensure we reach this point

        try {
            // Accept both AJAX and regular POST requests for embedded quizzes
            log_message('debug', 'SUBMIT_EMBEDDED: Starting submission process');
            if (!$this->request->is('post')) {
                log_message('debug', 'SUBMIT_EMBEDDED: Not a POST request');
                return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request method']);
            }

            // Try multiple ways to get user ID from session
            $userId = auth()->user()->id ?? null;

            if (!$userId) {
                $user = session()->get('user');
                $userId = $user['id'] ?? null;
            }
            log_message('debug', 'SUBMIT_EMBEDDED: User ID: ' . $userId);
            if (!$userId) {
                log_message('debug', 'SUBMIT_EMBEDDED: User not logged in');
                return $this->response->setStatusCode(401)->setJSON(['error' => 'User not authenticated']);
            }

            // Validate attempt
            log_message('debug', 'SUBMIT_EMBEDDED: Validating attempt ID: ' . $attemptId);
            $attempt = $this->attemptsModel->where('id', $attemptId)->first();
            // Check if attempt exists, belongs to user, and hasn't been completed (score > 0 means completed)
            if (!$attempt || $attempt->user_id != $userId || $attempt->score > 0) {
                log_message('debug', 'SUBMIT_EMBEDDED: Invalid attempt - attempt exists: ' . ($attempt ? 'yes' : 'no') . ', user match: ' . ($attempt && $attempt->user_id == $userId ? 'yes' : 'no') . ', already scored: ' . ($attempt && $attempt->score > 0 ? 'yes' : 'no'));
                return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid attempt']);
            }

            // Get quiz
            log_message('debug', 'SUBMIT_EMBEDDED: Loading quiz ID: ' . $attempt->quiz_id);
            $quiz = $this->quizzesModel->getQuizWithCourse($attempt->quiz_id);
            log_message('debug', 'SUBMIT_EMBEDDED: Quiz loaded: ' . ($quiz ? 'yes' : 'no'));
            if (!$quiz) {
                log_message('debug', 'SUBMIT_EMBEDDED: Quiz not found');
                return $this->response->setStatusCode(404)->setJSON(['error' => 'Quiz not found']);
            }

            // Get input data (JSON or POST)
            log_message('debug', 'SUBMIT_EMBEDDED: Getting input data');
            $input = $this->request->getJSON(true);
            if (empty($input)) {
                // Try to get from POST data
                $input = $this->request->getPost();
                log_message('debug', 'SUBMIT_EMBEDDED: Using POST data: ' . json_encode($input));
            } else {
                log_message('debug', 'SUBMIT_EMBEDDED: Using JSON data: ' . json_encode($input));
            }

            // If input is directly the answers array (not wrapped in 'answers' key)
            if (isset($input['answers'])) {
                $answers = $input['answers'];
            } else {
                // Assume the input is directly the answers
                $answers = $input;
            }
            $completionTime = $input['completion_time'] ?? 0;

            log_message('debug', 'SUBMIT_EMBEDDED: Parsed answers: ' . json_encode($answers));

        // Calculate score
        $questions = json_decode($attempt->quiz_questions, true);
        $totalQuestions = count($questions);
        $correctAnswers = 0;

        log_message('debug', 'SUBMIT_EMBEDDED: Starting score calculation');
        log_message('debug', 'SUBMIT_EMBEDDED: Total questions: ' . $totalQuestions);
        log_message('debug', 'SUBMIT_EMBEDDED: User answers: ' . json_encode($answers));

        foreach ($questions as $index => $question) {
            $userAnswer = $answers[$index] ?? null;
            $correctAnswer = $question['correct_answer'] ?? null;

            // If no correct_answer field exists, try to determine from options structure
            if ($correctAnswer === null && isset($question['options'])) {
                // For backward compatibility, find correct options from the options array
                $correctAnswer = [];
                foreach ($question['options'] as $optIndex => $option) {
                    if (isset($option['is_correct']) && $option['is_correct']) {
                        $correctAnswer[] = $optIndex;
                    }
                }
                // If no is_correct flags found, assume first option is correct
                if (empty($correctAnswer)) {
                    $correctAnswer = [0];
                }
                log_message('debug', 'SUBMIT_EMBEDDED: Question ' . $index . ' - No correct_answer field, derived from options: ' . json_encode($correctAnswer));
            }

            log_message('debug', "SUBMIT_EMBEDDED: Question $index - Type: {$question['question_type']}, User Answer: " . json_encode($userAnswer) . ", Correct Answer: " . json_encode($correctAnswer));

            // Handle different question types
            if ($question['question_type'] === 'multiple_choice') {
                // For multiple choice, compare arrays
                if (is_array($userAnswer) && is_array($correctAnswer)) {
                    sort($userAnswer);
                    sort($correctAnswer);
                    if ($userAnswer == $correctAnswer) {
                        $correctAnswers++;
                        log_message('debug', "SUBMIT_EMBEDDED: Question $index - CORRECT (multiple choice)");
                    } else {
                        log_message('debug', "SUBMIT_EMBEDDED: Question $index - INCORRECT (multiple choice) - User: " . json_encode($userAnswer) . " vs Correct: " . json_encode($correctAnswer));
                    }
                } else {
                    log_message('debug', "SUBMIT_EMBEDDED: Question $index - INCORRECT (multiple choice) - Invalid format - User is array: " . (is_array($userAnswer) ? 'yes' : 'no') . ", Correct is array: " . (is_array($correctAnswer) ? 'yes' : 'no'));
                }
            } else {
                // For single choice, true/false, essay
                if ($userAnswer == $correctAnswer) {
                    $correctAnswers++;
                    log_message('debug', "SUBMIT_EMBEDDED: Question $index - CORRECT (single choice)");
                } else {
                    log_message('debug', "SUBMIT_EMBEDDED: Question $index - INCORRECT (single choice) - User: " . json_encode($userAnswer) . " vs Correct: " . json_encode($correctAnswer));
                }
            }
        }

        log_message('debug', 'SUBMIT_EMBEDDED: Final correct answers: ' . $correctAnswers);

        $score = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;
        $passed = $score >= ($quiz->passing_score ?? 70);

        // Update attempt
        $updateData = [
            'user_answers' => json_encode($answers),
            'score' => $score,
            'time_taken_seconds' => $completionTime,
            'is_passed' => $passed ? 1 : 0
        ];

        $this->attemptsModel->update($attemptId, $updateData);

        // Update course progress if quiz is part of a course
        $this->updateCourseProgress($quiz, $userId, $passed);

        // Get next item URL for navigation
        $nextItemUrl = $this->getNextItemUrl($quiz, $userId);

        return $this->response->setJSON([
            'success' => true,
            'score' => round($score, 1),
            'passing_score' => $quiz->passing_score ?? 70,
            'passed' => $passed,
            'correct_answers' => $correctAnswers,
            'total_questions' => $totalQuestions,
            'completion_time' => $completionTime,
            'next_item_url' => $nextItemUrl
        ]);

        } catch (\Exception $e) {
            log_message('error', 'SUBMIT_EMBEDDED ERROR: ' . $e->getMessage() . ' - File: ' . $e->getFile() . ' - Line: ' . $e->getLine());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Internal server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update course progress after quiz completion
     */
    private function updateCourseProgress($quiz, $userId, $passed)
    {
        try {
            // Load course progress model
            $progressModel = new \Modules\Progress\Models\UserItemProgressModel();

            // Find the quiz item in tb_unit_items table
            // The quiz_id is stored in metadata as JSON, use JSON_EXTRACT for better compatibility
            $quizItem = $this->db->table('tb_unit_items')
                                ->where('item_type', 'quiz')
                                ->where('JSON_EXTRACT(metadata, "$.quiz_id")', $quiz->id)
                                ->get()
                                ->getRow();

            if ($quizItem) {
                // Get enrollment ID
                $enrollment = $this->db->table('tb_unit_enrollments')
                                      ->where('user_id', $userId)
                                      ->where('course_id', $quiz->course_id)
                                      ->get()
                                      ->getRow();

                if (!$enrollment) {
                    log_message('error', 'No enrollment found for user ' . $userId . ' in course ' . $quiz->course_id);
                    return;
                }

                // Update progress for this item using the correct fields
                $progressData = [
                    'user_id' => $userId,
                    'unit_id' => $quizItem->unit_id,
                    'item_id' => $quizItem->id,
                    'enrollment_id' => $enrollment->id,
                    'progress_percentage' => $passed ? 100.00 : 0.00,
                    'is_completed' => $passed ? 1 : 0,
                    'completed_at' => $passed ? date('Y-m-d H:i:s') : null,
                    'last_accessed_at' => date('Y-m-d H:i:s')
                ];

                // Check if progress record exists
                $existingProgress = $progressModel->where([
                    'user_id' => $userId,
                    'item_id' => $quizItem->id
                ])->first();

                if ($existingProgress) {
                    $progressModel->update($existingProgress->id, $progressData);
                } else {
                    $progressData['first_accessed_at'] = date('Y-m-d H:i:s');
                    $progressModel->insert($progressData);
                }

                log_message('info', 'Quiz progress updated successfully for user ' . $userId . ', quiz ' . $quiz->id);
            } else {
                log_message('error', 'Quiz item not found in tb_unit_items for quiz ID: ' . $quiz->id);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error updating course progress: ' . $e->getMessage());
        }
    }

    /**
     * Get next item URL for course navigation
     */
    private function getNextItemUrl($quiz, $userId)
    {
        // For now, return null since course_structure doesn't exist in the database
        // This can be implemented later when the course structure is properly defined
        log_message('debug', 'getNextItemUrl: Returning null - course structure not implemented');
        return null;
    }

}
