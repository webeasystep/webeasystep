<?php

namespace Modules\Progress\Controllers;

use App\Controllers\BaseController;
use Modules\Progress\Models\UserUnitProgressModel;
use Modules\Courses\Models\CoursesModel;
use Modules\Units\Models\UnitsModel;
use Modules\Courses\Models\SectionsModel;

class Progress extends BaseController
{
    protected $progress;
    protected $courses;
    protected $units;
    protected $sections;

    public function __construct()
    {
        $this->progress = new UserUnitProgressModel();
        $this->courses = new CoursesModel();
        $this->units = new UnitsModel();
        $this->sections = new SectionsModel();
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
            'user_progress' => $this->progress->getUserOverallProgress($userId),
            'recent_activity' => $this->progress->getRecentActivity($userId, 10)
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

        $unit = $this->units->find($unitId);
        if (!$unit) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Progress.unit_not_found')]);
        }

        $course = $this->coursesModel->find($unit->course_id);
        if (!$course) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Progress.course_not_found')]);
        }

        $isEnrolled = $this->courses->isUserEnrolled($userId, $section->course_id);
        if (!$isEnrolled) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Progress.not_enrolled')]);
        }

        $progressData = [
            'progress_percentage' => $progressPercentage,
            'watch_time' => $watchTime,
            'last_position_seconds' => $lastPositionSeconds
        ];

        $success = $this->progress->updateProgress($userId, $unitId, $progressData);
        
        if ($success) {
            $courseCompletion = $this->progress->getCourseCompletionPercentage($userId, $section->course_id);
            
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

    public function markCompleted()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Progress.invalid_request')]);
        }

        $session = session();
        $userId = $session->get('user_id');
        
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not authenticated']);
        }

        $unitId = $this->request->getPost('unit_id');
        
        if (!$unitId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unit ID required']);
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
        $isEnrolled = $this->coursesModel->isUserEnrolled($userId, $section->course_id);
        if (!$isEnrolled) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not enrolled in course']);
        }

        // Mark as completed
        $success = $this->progressModel->markUnitCompleted($userId, $unitId);
        
        if ($success) {
            // Get updated course completion percentage
            $courseCompletion = $this->progressModel->getCourseCompletionPercentage($userId, $section->course_id);
            
            // Get next unit for navigation
            $nextUnit = $this->unitsModel->getNextUnit($unitId);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Unit marked as completed',
                'course_completion' => $courseCompletion,
                'next_unit' => $nextUnit ? [
                    'id' => $nextUnit->id,
                    'title' => $nextUnit->unit_name,
                    'url' => base_url('courses/unit/' . $nextUnit->id)
                ] : null
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to mark unit as completed']);
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