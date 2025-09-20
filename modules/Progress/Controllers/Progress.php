<?php

namespace Modules\Progress\Controllers;

use App\Controllers\BaseController;
use Modules\Progress\Models\UserUnitProgressModel;
use Modules\Courses\Models\CoursesModel;
use Modules\Units\Models\UnitsModel;

class Progress extends BaseController
{
    protected $progressModel;
    protected $coursesModel;
    protected $unitsModel;

    public function __construct()
    {
        $this->progressModel = new UserUnitProgressModel();
        $this->coursesModel = new CoursesModel();
        $this->unitsModel = new UnitsModel();
    }

    public function index()
    {
        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => lang('Progress.my_progress'),
            'user_progress' => $this->progressModel->getUserOverallProgress($userId),
            'recent_activity' => $this->progressModel->getRecentActivity($userId, 10)
        ];

        return view('index', $data);
    }

    public function updateProgress()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Progress.invalid_request')]);
        }

        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Progress.user_not_authenticated')]);
        }

        $unitId = $this->request->getPost('unit_id');
        $progressPercentage = (float) $this->request->getPost('progress_percentage');
        $watchTime = (int) $this->request->getPost('watch_time');
        $lastPositionSeconds = (int) $this->request->getPost('last_position_seconds');

        if (!$unitId || $progressPercentage < 0 || $progressPercentage > 100) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Progress.invalid_data')]);
        }

        $unit = $this->unitsModel->find($unitId);
        if (!$unit) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Progress.unit_not_found')]);
        }

        $course = $this->coursesModel->find($unit->course_id);
        if (!$course) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Progress.course_not_found')]);
        }

        $isEnrolled = $this->coursesModel->isUserEnrolled($userId, $course->id);
        if (!$isEnrolled) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Progress.not_enrolled')]);
        }

        $progressData = [
            'progress_percentage' => $progressPercentage,
            'watch_time' => $watchTime,
            'last_position_seconds' => $lastPositionSeconds
        ];

        $success = $this->progressModel->updateProgress($userId, $unitId, $progressData);

        if ($success) {
            $courseCompletion = $this->progressModel->getCourseCompletionPercentage($userId, $course->id);

            return $this->response->setJSON([
                'success' => true,
                'message' => lang('Progress.update_success'),
                'course_completion' => $courseCompletion,
                'unit_completed' => $progressPercentage >= 100
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => lang('Progress.update_failed')]);
        }
    }

    /**
     * Mark item as completed (AJAX)
     */
    public function markCompleted()
    {
        // Debug logging for AJAX requests
        log_message('debug', 'PROGRESS_CONTROLLER DEBUG - AJAX request received');
        error_log('PROGRESS_CONTROLLER DEBUG - AJAX request received');
        file_put_contents('D:\laragon\www\msarlink\debug.log',
            date('Y-m-d H:i:s') . ' PROGRESS_CONTROLLER DEBUG - AJAX request received' . "\n",
            FILE_APPEND | LOCK_EX);

        // Debug logging for raw input
        $rawInput = $this->request->getBody();
        log_message('debug', 'PROGRESS_CONTROLLER DEBUG - Raw input: ' . $rawInput);
        error_log('PROGRESS_CONTROLLER DEBUG - Raw input: ' . $rawInput);
        file_put_contents('D:\laragon\www\msarlink\debug.log',
            date('Y-m-d H:i:s') . ' PROGRESS_CONTROLLER DEBUG - Raw input: ' . $rawInput . "\n",
            FILE_APPEND | LOCK_EX);

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $session = session();
        $user = $session->get('user');

        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not authenticated']);
        }

        $userId = $user['id'];
        log_message('debug', 'PROGRESS_CONTROLLER DEBUG - User ID from authenticated user: ' . $userId);
        error_log('PROGRESS_CONTROLLER DEBUG - User ID from authenticated user: ' . $userId);
        file_put_contents('D:\laragon\www\msarlink\debug.log',
            date('Y-m-d H:i:s') . ' PROGRESS_CONTROLLER DEBUG - User ID: ' . $userId . "\n",
            FILE_APPEND | LOCK_EX);

        // Parse JSON input
        $input = $this->request->getJSON(true);
        log_message('debug', 'PROGRESS_CONTROLLER DEBUG - Parsed input: ' . json_encode($input));
        error_log('PROGRESS_CONTROLLER DEBUG - Parsed input: ' . json_encode($input));
        file_put_contents('D:\laragon\www\msarlink\debug.log',
            date('Y-m-d H:i:s') . ' PROGRESS_CONTROLLER DEBUG - Parsed input: ' . json_encode($input) . "\n",
            FILE_APPEND | LOCK_EX);

        $unitId = $input['unit_id'] ?? null;
        $courseId = $input['course_id'] ?? null;
        $itemId = $input['item_id'] ?? null;

        log_message('debug', 'PROGRESS_CONTROLLER DEBUG - Unit ID: ' . ($unitId ?? 'NULL'));
        log_message('debug', 'PROGRESS_CONTROLLER DEBUG - Course ID: ' . ($courseId ?? 'NULL'));
        log_message('debug', 'PROGRESS_CONTROLLER DEBUG - Item ID: ' . ($itemId ?? 'NULL'));
        error_log('PROGRESS_CONTROLLER DEBUG - Unit ID: ' . ($unitId ?? 'NULL'));
        error_log('PROGRESS_CONTROLLER DEBUG - Course ID: ' . ($courseId ?? 'NULL'));
        error_log('PROGRESS_CONTROLLER DEBUG - Item ID: ' . ($itemId ?? 'NULL'));
        file_put_contents('D:\laragon\www\msarlink\debug.log',
            date('Y-m-d H:i:s') . ' PROGRESS_CONTROLLER DEBUG - Unit ID: ' . ($unitId ?? 'NULL') . "\n",
            FILE_APPEND | LOCK_EX);
        file_put_contents('D:\laragon\www\msarlink\debug.log',
            date('Y-m-d H:i:s') . ' PROGRESS_CONTROLLER DEBUG - Course ID: ' . ($courseId ?? 'NULL') . "\n",
            FILE_APPEND | LOCK_EX);
        file_put_contents('D:\laragon\www\msarlink\debug.log',
            date('Y-m-d H:i:s') . ' PROGRESS_CONTROLLER DEBUG - Item ID: ' . ($itemId ?? 'NULL') . "\n",
            FILE_APPEND | LOCK_EX);

        if (!$unitId || !$courseId || !$itemId) {
            log_message('debug', 'PROGRESS_CONTROLLER DEBUG - Missing unit_id, course_id, or item_id');
            error_log('PROGRESS_CONTROLLER DEBUG - Missing unit_id, course_id, or item_id');
            file_put_contents('D:\laragon\www\msarlink\debug.log',
                date('Y-m-d H:i:s') . ' PROGRESS_CONTROLLER DEBUG - Missing unit_id, course_id, or item_id' . "\n",
                FILE_APPEND | LOCK_EX);
            return $this->response->setJSON(['success' => false, 'message' => 'خطأ: Unit ID, Course ID, and Item ID required']);
        }

        // Verify user has access to this unit
        $unit = $this->unitsModel->find($unitId);
        if (!$unit) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unit not found']);
        }

        $course = $this->coursesModel->find($unit->course_id);
        if (!$course) {
            return $this->response->setJSON(['success' => false, 'message' => 'Course not found']);
        }

        // Check if user is enrolled in the course
        $isEnrolled = $this->coursesModel->isUserEnrolled($userId, $course->id);
        if (!$isEnrolled) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not enrolled in course']);
        }

        // Get enrollment ID
        $enrollment = $this->db->table('tb_unit_enrollments')
                              ->where('user_id', $userId)
                              ->where('course_id', $course->id)
                              ->get()
                              ->getRow();

        if (!$enrollment) {
            return $this->response->setJSON(['success' => false, 'message' => 'Enrollment not found']);
        }

        // Load item progress model
        $itemProgressModel = new \Modules\Progress\Models\UserItemProgressModel();

        // Debug logging before marking item completed
        log_message('debug', 'PROGRESS_CONTROLLER DEBUG - About to mark item completed: userId=' . $userId . ', unitId=' . $unitId . ', itemId=' . $itemId . ', enrollmentId=' . $enrollment->id);

        // Mark item as completed
        $success = $itemProgressModel->markItemCompleted($userId, $unitId, $itemId, $enrollment->id);

        log_message('debug', 'PROGRESS_CONTROLLER DEBUG - markItemCompleted result: ' . ($success ? 'true' : 'false'));

        if ($success) {
            // Check if unit is now fully completed
            $unitCompleted = $itemProgressModel->isUnitCompleted($userId, $unitId);

            if ($unitCompleted) {
                // Update unit progress table
                $this->progressModel->markUnitCompleted($userId, $unitId);
            }

            // Get next item in the unit
            $nextItem = $itemProgressModel->getNextIncompleteItem($userId, $unitId);

            // Get updated course completion percentage
            $courseCompletion = $this->progressModel->getCourseCompletionPercentage($userId, $course->id);

            $response = [
                'success' => true,
                'message' => 'Item marked as completed',
                'course_completion' => $courseCompletion,
                'unit_completed' => $unitCompleted
            ];

            if ($nextItem) {
                $response['next_item'] = [
                    'id' => $nextItem['id'],
                    'title' => $nextItem['title'],
                    'type' => $nextItem['item_type'],
                    'url' => base_url('courses/item/' . $nextItem['id'])
                ];
            } else {
                // No more items in unit, get next unit
                $nextUnit = $this->unitsModel->getNextUnit($unitId, $course->id);

                // Debug logging for nextUnit
                log_message('debug', 'PROGRESS_CONTROLLER DEBUG - nextUnit type: ' . gettype($nextUnit));
                log_message('debug', 'PROGRESS_CONTROLLER DEBUG - nextUnit data: ' . json_encode($nextUnit));

                if ($nextUnit) {
                    // Check if nextUnit is object or array
                    if (is_object($nextUnit)) {
                        $response['next_unit'] = [
                            'id' => $nextUnit->id,
                            'title' => $nextUnit->unit_name,
                            'url' => base_url('courses/unit/' . $nextUnit->id)
                        ];
                    } else if (is_array($nextUnit)) {
                        $response['next_unit'] = [
                            'id' => $nextUnit['id'],
                            'title' => $nextUnit['unit_name'],
                            'url' => base_url('courses/unit/' . $nextUnit['id'])
                        ];
                    }
                }
            }

            return $this->response->setJSON($response);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to mark item as completed']);
        }
    }

    /**
     * Get user's progress for a specific course
     */
    public function getCourseProgress($courseId = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not authenticated']);
        }

        if (!$courseId) {
            $courseId = $this->request->getGet('course_id');
        }

        if (!$courseId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Course ID required']);
        }

        // Check if user is enrolled in the course
        $isEnrolled = $this->coursesModel->isUserEnrolled($userId, $courseId);
        if (!$isEnrolled) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not enrolled in course']);
        }

        // Get detailed progress
        $progress = $this->progressModel->getCourseProgress($userId, $courseId);
        $completionPercentage = $this->progressModel->getCourseCompletionPercentage($userId, $courseId);

        return $this->response->setJSON([
            'success' => true,
            'completion_percentage' => $completionPercentage,
            'units_progress' => $progress
        ]);
    }

    /**
     * Get user's learning dashboard
     */
    public function dashboard()
    {
        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            return redirect()->to('/login');
        }

        // Get user's learning statistics
        $stats = $this->progressModel->getUserLearningStats($userId);

        // Get recent activity
        $recentActivity = $this->progressModel->getRecentActivity($userId, 10);

        // Get units needing attention
        $unitsNeedingAttention = $this->progressModel->getUnitsNeedingAttention($userId, 5);

        // Get enrolled courses with progress
        $enrolledCourses = $this->coursesModel->getAllUserCourses($userId, 'active');
        foreach ($enrolledCourses as &$course) {
            $course->completion_percentage = $this->progressModel->getCourseCompletionPercentage($userId, $course->course_id);
        }

        $data = [
            'title' => 'Learning Dashboard',
            'description' => 'Track your learning progress and achievements',
            'stats' => $stats,
            'recent_activity' => $recentActivity,
            'units_needing_attention' => $unitsNeedingAttention,
            'enrolled_courses' => $enrolledCourses
        ];

        return View('Site', 'dashboard', $data);
    }

    /**
     * Get unit progress for video player
     */
    public function getUnitProgress($unitId = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not authenticated']);
        }

        if (!$unitId) {
            $unitId = $this->request->getGet('unit_id');
        }

        if (!$unitId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unit ID required']);
        }

        // Get progress
        $progress = $this->progressModel->getUserUnitProgress($userId, $unitId);

        if ($progress) {
            return $this->response->setJSON([
                'success' => true,
                'progress' => [
                    'progress_percentage' => $progress->progress_percentage,
                    'watch_time' => $progress->watch_time,
                    'last_position_seconds' => $progress->last_position_seconds,
                    'is_completed' => $progress->is_completed,
                    'completed_at' => $progress->completed_at
                ]
            ]);
        } else {
            return $this->response->setJSON([
                'success' => true,
                'progress' => [
                    'progress_percentage' => 0,
                    'watch_time' => 0,
                    'last_position_seconds' => 0,
                    'is_completed' => false,
                    'completed_at' => null
                ]
            ]);
        }
    }

    /**
     * Export user progress data
     */
    public function exportProgress()
    {
        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            return redirect()->to('/login');
        }

        // Get all user's progress data
        $progressData = $this->progressModel->where('user_id', $userId)
                                           ->orderBy('updated_at', 'DESC')
                                           ->findAll();

        // Get learning stats
        $stats = $this->progressModel->getUserLearningStats($userId);

        $exportData = [
            'user_id' => $userId,
            'export_date' => date('Y-m-d H:i:s'),
            'learning_statistics' => $stats,
            'progress_data' => $progressData
        ];

        // Set headers for JSON download
        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="learning_progress_' . $userId . '_' . date('Y-m-d') . '.json"');

        return $this->response->setJSON($exportData);
    }

    /**
     * Get progress analytics for admin (requires admin access)
     */
    public function analytics()
    {
        $session = session();
        $userRole = $session->get('user_role');

        if ($userRole !== 'admin') {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $analytics = $this->progressModel->getProgressAnalytics();

        return $this->response->setJSON([
            'success' => true,
            'analytics' => $analytics
        ]);
    }
}
