<?php

namespace Modules\Quizzes\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DtTable;
use Modules\Quizzes\Models\QuizzesModel;
use Modules\Quizzes\Models\QuizAttemptsModel;
use Modules\Courses\Models\CoursesModel;
use Modules\Users\Models\UsersModel;

class AdminQuizzes extends BaseController
{
    protected $quizzesModel;
    protected $attemptsModel;
    protected $coursesModel;
    protected $usersModel;
    protected $rules;

    public function __construct()
    {
        $this->quizzesModel = new QuizzesModel();
        $this->attemptsModel = new QuizAttemptsModel();
        $this->coursesModel = new CoursesModel();
        $this->usersModel = new UsersModel();
        $this->rules = [
            'quiz_title' => ['label' => lang('Quizzes.quiz_title_label'), 'rules' => 'required|min_length[3]|max_length[255]'],
            'course_id' => ['label' => lang('Quizzes.course_label'), 'rules' => 'required|integer'],
            'time_limit_minutes' => ['label' => lang('Quizzes.time_limit_label'), 'rules' => 'required|integer|greater_than[0]'],
            'passing_score' => ['label' => lang('Quizzes.passing_score_label'), 'rules' => 'required|decimal|greater_than[0]|less_than_equal_to[100]'],
            'max_attempts' => ['label' => lang('Quizzes.max_attempts_label'), 'rules' => 'required|integer|greater_than[0]']
        ];
        helper(['form', 'url']);
    }

    /**
     * Display quizzes list
     */
    public function index()
    {
        $data['title'] = lang('Quizzes.quizzes_management');
        $data['description'] = lang('Quizzes.manage_course_quizzes_and_assessments');

        if ($this->request->isAJAX()) {
            $query = $this->quizzesModel->getAllQuizzesWithCourse();

            DtTable::hideColumns(['id', 'quiz_questions']);
            DtTable::searchableColumns(['quiz_title', 'course_title', 'quiz_desc']);
            DtTable::orderableColumns(['created_at', 'time_limit_minutes', 'passing_score', 'max_attempts']);
            DtTable::setColumnSwitch('active', 'tb_quizzes');
            DtTable::changeColumn('time_limit_minutes', function($data, $row) {
                return $data . ' ' . lang('Quizzes.minutes');
            });
            DtTable::changeColumn('passing_score', function($data, $row) {
                return $data . '%';
            });

            DtTable::setAction('attempts', 'fas fa-list', '/dt_admin/quizzes/attempts/');
            DtTable::setAction('questions', 'fas fa-question', '/dt_admin/quizzes/questions/');

            return $this->response->setJSON(json_decode(DtTable::tableRender($query), true));
        }

        return view('index', $data);
    }

    /**
     * Create new quiz
     */
    public function create()
    {
        if ($this->request->is('post')) {
            if (!$this->validate($this->rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Process questions data
            $questionsData = $this->request->getPost('questions');
            $questionsJson = '[]';
            if (!empty($questionsData) && is_array($questionsData)) {
                $questionsJson = json_encode($questionsData);
            }

            $data = [
                'quiz_title' => $this->request->getPost('quiz_title'),
                'quiz_desc' => $this->request->getPost('quiz_desc'),
                'course_id' => $this->request->getPost('course_id'),
                'time_limit_minutes' => $this->request->getPost('time_limit_minutes'),
                'passing_score' => $this->request->getPost('passing_score'),
                'max_attempts' => $this->request->getPost('max_attempts'),
                'quiz_questions' => $questionsJson,
                'shuffle_questions' => $this->request->getPost('shuffle_questions') ? 1 : 0,
                'shuffle_answers' => $this->request->getPost('shuffle_answers') ? 1 : 0,
                'show_results' => $this->request->getPost('show_results') ? 1 : 0,
                'active' => $this->request->getPost('active') ? 1 : 0,
                'created_by' => session()->get('user')['id'] ?? null
            ];

            if ($this->quizzesModel->insert($data)) {
                return redirect()->to('/dt_admin/quizzes')->with('success', lang('Quizzes.quiz_created_successfully'));
            } else {
                return redirect()->back()->withInput()->with('error', lang('Quizzes.failed_to_create_quiz'));
            }
        }

        $data = [
            'title' => lang('Quizzes.create_new_quiz'),
            'courses' => $this->coursesModel->where('active', 1)->findAll()
        ];

        return view('form', $data);
    }

    /**
     * Edit existing quiz
     */
    public function edit($id)
    {
        $quiz = $this->quizzesModel->find($id);
        if (!$quiz) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if ($this->request->is('post')) {
            if (!$this->validate($this->rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Process questions data
            $questionsData = $this->request->getPost('questions');
            $questionsJson = '[]';
            if (!empty($questionsData) && is_array($questionsData)) {
                $questionsJson = json_encode($questionsData);
            }

            $data = [
                'quiz_title' => $this->request->getPost('quiz_title'),
                'quiz_desc' => $this->request->getPost('quiz_desc'),
                'course_id' => $this->request->getPost('course_id'),
                'time_limit_minutes' => $this->request->getPost('time_limit_minutes'),
                'passing_score' => $this->request->getPost('passing_score'),
                'max_attempts' => $this->request->getPost('max_attempts'),
                'quiz_questions' => $questionsJson,
                'shuffle_questions' => $this->request->getPost('shuffle_questions') ? 1 : 0,
                'shuffle_answers' => $this->request->getPost('shuffle_answers') ? 1 : 0,
                'show_results' => $this->request->getPost('show_results') ? 1 : 0,
                'active' => $this->request->getPost('active') ? 1 : 0,
                'updated_by' => session()->get('user')['id'] ?? null
            ];

            if ($this->quizzesModel->update($id, $data)) {
                return redirect()->to('/dt_admin/quizzes')->with('success', lang('Quizzes.quiz_updated_successfully'));
            } else {
                return redirect()->back()->withInput()->with('error', lang('Quizzes.failed_to_update_quiz'));
            }
        }

        // Decode questions data if it exists
        $questions = [];
        if (!empty($quiz->quiz_questions)) {
            $questions = json_decode($quiz->quiz_questions, true);
            if (!is_array($questions)) {
                $questions = [];
            }
        }

        $data = [
            'title' => str_replace('{title}', $quiz->quiz_title, lang('Quizzes.edit_quiz_title')),
            'quiz' => $quiz,
            'questions' => $questions,
            'courses' => $this->coursesModel->where('active', 1)->findAll()
        ];

        return view('form', $data);
    }

    /**
     * View quiz details
     */
    public function view($id)
    {
        $quiz = $this->quizzesModel->getQuizWithCourse($id);
        if (!$quiz) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title'      => str_replace('{title}', $quiz->quiz_title, lang('Quizzes.quiz_details_title')),
            'quiz'       => $quiz,
            'statistics' => $this->getQuizStatistics($id),
            'attempts'   => $this->attemptsModel->getQuizAttemptsWithUsers($id, 20)
        ];

        return view('view', $data);
    }

    /**
     * Manage quiz questions
     */
    public function questions($quizId)
    {
        $quiz = $this->quizzesModel->find($quizId);
        if (!$quiz) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => str_replace('{title}', $quiz->quiz_title, lang('Quizzes.manage_questions_title')),
            'quiz' => $quiz,
            'questions' => json_decode($quiz->quiz_questions, true) ?? []
        ];

        return view('questions', $data);
    }

    /**
     * Import quiz from JSON
     */
    public function importQuiz()
    {
        // Log method entry and request details
        log_message('info', '[QUIZ_IMPORT] Method importQuiz() called');
        log_message('info', '[QUIZ_IMPORT] Request method: ' . $this->request->getMethod());
        log_message('info', '[QUIZ_IMPORT] Request URI: ' . $this->request->getUri());
        log_message('info', '[QUIZ_IMPORT] POST data: ' . json_encode($this->request->getPost()));
        log_message('info', '[QUIZ_IMPORT] FILES data: ' . json_encode($_FILES));
        log_message('info', '[QUIZ_IMPORT] Content Type: ' . $this->request->getHeaderLine('Content-Type'));
        
        // Additional method checking
        log_message('info', '[QUIZ_IMPORT] Method check - getMethod(): ' . $this->request->getMethod());
        log_message('info', '[QUIZ_IMPORT] Method check - strtolower(getMethod()): ' . strtolower($this->request->getMethod()));
        log_message('info', '[QUIZ_IMPORT] Method check - comparison result: ' . ($this->request->getMethod() === 'post' ? 'true' : 'false'));
        log_message('info', '[QUIZ_IMPORT] Method check - case insensitive comparison: ' . (strtolower($this->request->getMethod()) === 'post' ? 'true' : 'false'));
        
        if ($this->request->is('post')) {
            log_message('info', '[QUIZ_IMPORT] Starting quiz import process');
            
            $file = $this->request->getFile('quiz_file');
            log_message('info', '[QUIZ_IMPORT] File received: ' . ($file ? $file->getName() : 'null'));
            
            if (!$file || !$file->isValid()) {
                log_message('error', '[QUIZ_IMPORT] Invalid file: ' . ($file ? $file->getErrorString() : 'no file'));
                return redirect()->back()->with('error', lang('Quizzes.invalid_file'));
            }

            $jsonContent = file_get_contents($file->getTempName());
            log_message('info', '[QUIZ_IMPORT] JSON content length: ' . strlen($jsonContent));
            log_message('info', '[QUIZ_IMPORT] JSON content preview: ' . substr($jsonContent, 0, 200) . '...');
            
            $quizData = json_decode($jsonContent, true);
            log_message('info', '[QUIZ_IMPORT] JSON decoded successfully: ' . (is_array($quizData) ? 'yes' : 'no'));
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                log_message('error', '[QUIZ_IMPORT] JSON decode error: ' . json_last_error_msg());
                return redirect()->back()->with('error', lang('Quizzes.invalid_json_format'));
            }

            log_message('info', '[QUIZ_IMPORT] Quiz data structure: ' . json_encode(array_keys($quizData)));
            if (isset($quizData['questions'])) {
                log_message('info', '[QUIZ_IMPORT] Number of questions: ' . count($quizData['questions']));
                foreach ($quizData['questions'] as $index => $question) {
                    log_message('info', '[QUIZ_IMPORT] Question ' . ($index + 1) . ' keys: ' . json_encode(array_keys($question)));
                    log_message('info', '[QUIZ_IMPORT] Question ' . ($index + 1) . ' text: ' . (isset($question['text']) ? substr($question['text'], 0, 100) : 'NOT SET'));
                }
            }

            if (!$this->quizzesModel->validateQuizJSON($quizData)) {
                log_message('error', '[QUIZ_IMPORT] Validation failed');
                return redirect()->back()->with('error', lang('Quizzes.invalid_quiz_json_structure'));
            }

            log_message('info', '[QUIZ_IMPORT] Validation passed, attempting import');
            $quizId = $this->quizzesModel->importFromJSON($quizData);
            if ($quizId) {
                log_message('info', '[QUIZ_IMPORT] Import successful, quiz ID: ' . $quizId);
                // Redirect to edit page instead of quiz list to allow user to review/edit the imported quiz
                return redirect()->to('/dt_admin/quizzes/edit/' . $quizId)->with('success', lang('Quizzes.quiz_imported_successfully') . ' - يمكنك الآن مراجعة وتعديل الاختبار');
            } else {
                log_message('error', '[QUIZ_IMPORT] Import failed');
                return redirect()->back()->with('error', lang('Quizzes.failed_to_import_quiz'));
            }
        }

        $data = [
            'title' => lang('Quizzes.import_quiz_from_json'),
            'courses' => $this->coursesModel->where('active', 1)->findAll()
        ];

        return view('import', $data);
    }

    /**
     * View quiz attempts
     */
    public function attempts($quizId = null)
    {
        $data['title'] = lang('Quizzes.quiz_attempts');

        if ($quizId) {
            $quiz = $this->quizzesModel->find($quizId);
            if (!$quiz) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
            $data['title'] .= ' - ' . $quiz->quiz_title;
            $data['quiz'] = $quiz;
        }

        if ($this->request->isAJAX()) {
            $query = $this->attemptsModel->getAttemptsWithDetails($quizId);

            DtTable::hideColumns(['id', 'user_answers']);
            DtTable::searchableColumns(['username', 'quiz_title', 'course_title']);
            DtTable::orderableColumns(['attempt_date', 'score', 'time_taken_seconds']);
            DtTable::changeColumn('score', function($data, $row) {
                $color = $data >= 70 ? 'success' : ($data >= 50 ? 'warning' : 'danger');
                return "<span class='badge badge-{$color}'>" . round($data, 1) . '%</span>';
            });
            DtTable::changeColumn('time_taken_seconds', function($data, $row) {
                $minutes = floor($data / 60);
                $seconds = $data % 60;
                return sprintf('%02d:%02d', $minutes, $seconds);
            });
            DtTable::changeColumn('is_passed', function($data, $row) {
                return $data ? '<span class="badge badge-success">' . lang('Quizzes.passed') . '</span>' : '<span class="badge badge-danger">' . lang('Quizzes.failed') . '</span>';
            });
            DtTable::setAction('view_answers', 'fas fa-eye', '/dt_admin/quizzes/view-attempt/');

            return $this->response->setJSON(json_decode(DtTable::tableRender($query), true));
        }

        return view('attempts', $data);
    }

    /**
     * View specific quiz attempt
     */
    public function viewAttempt($attemptId)
    {
        $attempt = $this->attemptsModel->getAttemptWithDetails($attemptId);
        if (!$attempt) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => lang('Quizzes.quiz_attempt_details'),
            'attempt' => $attempt,
            'user_answers' => json_decode($attempt->user_answers, true) ?? [],
            'quiz_questions' => json_decode($attempt->quiz_questions, true) ?? []
        ];

        return view('view_attempt', $data);
    }

    /**
     * Delete quiz
     */
    public function delete($id = null): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $ids = $id !== null ? $id : $this->request->getPost('rows');
        if (empty($ids)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => lang('Quizzes.quiz_not_found')
            ]);
        }

        $idsArray = is_string($ids) ? explode(',', $ids) : (is_array($ids) ? $ids : [$ids]);
        $db = \Config\Database::connect();
        $deletedCount = 0;

        foreach ($idsArray as $singleId) {
            $singleId = (int) $singleId;
            if ($singleId <= 0) continue;

            $quiz = $this->quizzesModel->find($singleId);
            if (!$quiz) continue;

            // 1. Find all unit_items of type 'quiz' linked to this quiz
            $linkedUnitItems = $db->table('tb_unit_items')
                ->where('item_type', 'quiz')
                ->where('item_id', $singleId)
                ->get()
                ->getResultArray();

            $metadataLinkedItems = $db->table('tb_unit_items')
                ->where('item_type', 'quiz')
                ->like('metadata', '"quiz_id":' . $singleId)
                ->get()
                ->getResultArray();

            $allLinkedItems = array_merge($linkedUnitItems, $metadataLinkedItems);
            $uniqueItemIds = array_unique(array_column($allLinkedItems, 'id'));

            if (!empty($uniqueItemIds)) {
                // Delete progress records for these unit items
                $db->table('tb_user_item_progress')
                   ->whereIn('item_id', $uniqueItemIds)
                   ->delete();

                // Delete the unit items themselves
                $db->table('tb_unit_items')
                   ->whereIn('id', $uniqueItemIds)
                   ->delete();
            }

            // 2. Delete quiz attempts using raw query (most reliable approach)
            $db->query('DELETE FROM tb_quiz_attempts WHERE quiz_id = ' . $singleId);

            // 3. Delete the quiz
            if ($this->quizzesModel->delete($singleId)) {
                $deletedCount++;
            }
        }

        if ($deletedCount > 0) {
            return $this->response->setJSON([
                'success' => true,
                'message' => lang('Quizzes.quiz_deleted_successfully')
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => lang('Quizzes.failed_to_delete_quiz')
            ]);
        }
    }

    /**
     * Toggle quiz status (active/inactive)
     */
    public function toggleStatus($id = null): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $quiz = $this->quizzesModel->find($id);
        if (!$quiz) {
            return $this->response->setJSON([
                'success' => false,
                'message' => lang('Quizzes.quiz_not_found')
            ]);
        }

        $newStatus = $this->request->getPost('status');
        $data = ['active' => $newStatus];

        if ($this->quizzesModel->update($id, $data)) {
            $message = $newStatus == 1 ? lang('Quizzes.quiz_activated_successfully') : lang('Quizzes.quiz_deactivated_successfully');
            return $this->response->setJSON([
                'success' => true,
                'message' => $message
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => lang('Quizzes.failed_to_update_quiz_status')
            ]);
        }
    }

    /**
     * Get quiz statistics
     */
    private function getQuizStatistics($quizId)
    {
        $totalAttempts = $this->attemptsModel->where('quiz_id', $quizId)->countAllResults();
        $passedAttempts = $this->attemptsModel->where('quiz_id', $quizId)->where('is_passed', 1)->countAllResults();
        $averageScore = $this->attemptsModel->where('quiz_id', $quizId)->selectAvg('score')->first()->score ?? 0;
        $averageTime = $this->attemptsModel->where('quiz_id', $quizId)->selectAvg('time_taken_seconds')->first()->time_taken_seconds ?? 0;

        return [
            'total_attempts' => $totalAttempts,
            'passed_attempts' => $passedAttempts,
            'pass_rate' => $totalAttempts > 0 ? round(($passedAttempts / $totalAttempts) * 100, 2) : 0,
            'average_score' => round($averageScore, 2),
            'average_time' => gmdate('H:i:s', $averageTime)
        ];
    }

    /**
     * Export quiz data
     */
    public function exportQuiz($id)
    {
        $quiz = $this->quizzesModel->find($id);
        if (!$quiz) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $exportData = [
            'quiz_title' => $quiz->quiz_title,
            'quiz_desc' => $quiz->quiz_desc,
            'time_limit_minutes' => $quiz->time_limit_minutes,
            'passing_score' => $quiz->passing_score,
            'max_attempts' => $quiz->max_attempts,

            'shuffle_questions' => $quiz->shuffle_questions,
            'show_results_immediately' => $quiz->show_results_immediately,
            'questions' => json_decode($quiz->quiz_questions, true) ?? []
        ];

        $filename = 'quiz_' . $quiz->id . '_' . date('Y-m-d') . '.json';

        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        echo json_encode($exportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Quiz analytics dashboard
     */
    public function analytics()
    {
        $data = [
            'title' => lang('Quizzes.quiz_analytics_dashboard'),
            'total_quizzes' => $this->quizzesModel->where('active', 1)->countAllResults(),
            'total_attempts' => $this->attemptsModel->countAllResults(),
            'average_pass_rate' => $this->getAveragePassRate(),
            'popular_quizzes' => $this->getPopularQuizzes(),

            'recent_attempts' => $this->attemptsModel->getRecentAttempts(10)
        ];

        return view('analytics', $data);
    }

    /**
     * Get quiz statistics for API/AJAX requests
     */
    public function getStatistics()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => lang('Quizzes.invalid_request_method')
            ]);
        }

        $data = [
            'success' => true,
            'data' => [
                'total_quizzes' => $this->quizzesModel->where('active', 1)->countAllResults(),
                'total_attempts' => $this->attemptsModel->countAllResults(),
                'average_pass_rate' => $this->getAveragePassRate(),
                'popular_quizzes' => $this->getPopularQuizzes(),
                'difficulty_distribution' => $this->getDifficultyDistribution(),
                'recent_attempts' => $this->attemptsModel->getRecentAttempts(10)
            ]
        ];

        return $this->response->setJSON($data);
    }

    /**
     * Get average pass rate across all quizzes
     */
    private function getAveragePassRate()
    {
        $totalAttempts = $this->attemptsModel->countAllResults();
        $passedAttempts = $this->attemptsModel->where('is_passed', 1)->countAllResults();

        return $totalAttempts > 0 ? round(($passedAttempts / $totalAttempts) * 100, 2) : 0;
    }

    /**
     * Get most popular quizzes by attempt count
     */
    private function getPopularQuizzes($limit = 5)
    {
        return $this->db->table('tb_quizzes')
                       ->select('tb_quizzes.quiz_title, tb_courses.course_title, COUNT(tb_quiz_attempts.id) as attempt_count')
                       ->join('tb_courses', 'tb_courses.id = tb_quizzes.course_id')
                       ->join('tb_quiz_attempts', 'tb_quiz_attempts.quiz_id = tb_quizzes.id', 'left')
                       ->where('tb_quizzes.active', 1)
                       ->groupBy('tb_quizzes.id')
                       ->orderBy('attempt_count', 'DESC')
                       ->limit($limit)
                       ->get()
                       ->getResultArray();
    }


}
