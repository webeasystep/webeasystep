<?php

namespace Modules\Progress\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DtTable;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Progress\Models\UserUnitProgressModel;
use Modules\Courses\Models\CoursesModel;
use Modules\Units\Models\UnitsModel;
use Modules\Users\Models\UsersModel;

class AdminProgress extends BaseController
{
    protected $progress;
    protected $courses;
    protected $units;
    protected $users;
    protected $rules;

    public function __construct()
    {
        $this->progress = new UserUnitProgressModel();
        $this->courses = new CoursesModel();
        $this->units = new UnitsModel();
        $this->users = new UsersModel();
        $this->rules = [
            "user_id" => ['label' => lang("Progress.user"), 'rules' => "required|integer"],
            "unit_id" => ['label' => lang("Progress.unit"), 'rules' => "required|integer"],
            "progress_percentage" => ['label' => lang("Progress.progress_percentage"), 'rules' => "required|decimal|greater_than_equal_to[0]|less_than_equal_to[100]"]
        ];
    }

    public function index()
    {
        $data['title'] = lang('Progress.progress_management');

        if ($this->request->isAJAX()) {
            $progressModel = $this->progress
                ->select('tb_user_item_progress.id, tb_user_item_progress.progress_percentage, tb_user_item_progress.watch_time, tb_user_item_progress.is_completed, tb_user_item_progress.created_at, users.username as user_name, tb_units.unit_name as unit_title, tb_courses.course_title as course_title')
                ->join('users', 'users.id = tb_user_item_progress.user_id')
                ->join('tb_units', 'tb_units.id = tb_user_item_progress.unit_id')

                ->join('tb_courses', 'tb_courses.id = tb_units.course_id')
                ->orderBy('tb_user_item_progress.id', 'desc')
                ->builder();

            DtTable::hideColumns(['id']);
            DtTable::searchableColumns(['user_name', 'unit_title', 'course_title']);
            DtTable::orderableColumns(['user_name', 'unit_title', 'course_title', 'progress_percentage', 'watch_time', 'is_completed', 'created_at']);

            DtTable::changeColumn('progress_percentage', function ($data, $row) {
                $percentage = (float) $data;
                $color = $percentage >= 100 ? 'success' : ($percentage >= 50 ? 'warning' : 'danger');
                return "<div class='progress'><div class='progress-bar bg-{$color}' style='width: {$percentage}%'>{$percentage}%</div></div>";
            });

            DtTable::changeColumn('watch_time', function ($data, $row) {
                $hours = floor($data / 3600);
                $minutes = floor(($data % 3600) / 60);
                $seconds = $data % 60;
                return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
            });

            DtTable::changeColumn('is_completed', function ($data, $row) {
                return $data ? '<span class="badge badge-success">'.lang('Progress.completed').'</span>' : '<span class="badge badge-secondary">'.lang('Progress.in_progress').'</span>';
            });

            DtTable::setShowColumns("user_name,unit_title,course_title,progress_percentage,watch_time,is_completed,created_at");

            $output = DtTable::tableRender($progressModel, false);
            return $this->response->setJSON($output);
        } else {
            return view('index', $data);
        }
    }

    public function userAnalytics($userId = null)
    {
        if (!$userId) {
            return redirect()->back()->with('error', lang('Progress.user_id_required'));
        }

        $user = $this->users->find($userId);
        if (!$user) {
            return redirect()->back()->with('error', lang('Progress.user_not_found'));
        }

        $data = [
            'title' => lang('Progress.user_analytics'),
            'user' => $user,
            'stats' => $this->progress->getUserStats($userId)
        ];

        return view('user_analytics', $data);
    }

    public function courseAnalytics($courseId = null)
    {
        if (!$courseId) {
            return redirect()->back()->with('error', lang('Progress.course_id_required'));
        }

        $course = $this->courses->find($courseId);
        if (!$course) {
            return redirect()->back()->with('error', lang('Progress.course_not_found'));
        }

        $data = [
            'title' => lang('Progress.course_analytics'),
            'course' => $course,
            'analytics' => $this->getCompletionStats($courseId)
        ];

        return view('course_analytics', $data);
    }

    public function dashboard()
    {
        $data = [
            'title' => lang('Progress.dashboard'),
            'analytics' => $this->progress->getProgressAnalytics(),
            'recentCompletions' => $this->progress->getRecentCompletions(10),
            'topPerformers' => $this->progress->getTopPerformers(10),
            'courseCompletionRates' => $this->progress->getCourseCompletionRates()
        ];

        return view('dashboard', $data);
    }

    public function add()
    {
        $data['title'] = lang("Admin.add_data");

        if ($this->request->is('post')) {
            if ($this->validate($this->rules)) {
                $userId = $this->request->getPost('user_id');
                $unitId = $this->request->getPost('unit_id');
                $progressPercentage = $this->request->getPost('progress_percentage');
                $watchTime = $this->request->getPost('watch_time') ?? 0;

                $progressData = [
                    'user_id' => $userId,
                    'unit_id' => $unitId,
                    'progress_percentage' => $progressPercentage,
                    'watch_time' => $watchTime,
                    'last_position_seconds' => $this->request->getPost('last_position_seconds') ?? 0
                ];

                if ($this->progress->updateProgress($userId, $unitId, $progressData)) {
                    $this->show_msg('success', lang("Admin.add"), lang("Admin.add_success"));
                    return redirect()->to(ADMIN_URL . "progress");
                } else {
                    $this->show_msg('danger', lang("Admin.error"), lang("Admin.add_error"));
                }
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }

        $data['users'] = $this->users->findAll();
        $data['units'] = $this->units->getUnitsWithCourseInfo();
        return view('form', $data);
    }

    public function edit($id)
    {
        $data['title'] = lang("Admin.edit_data");
        $data['progress'] = $this->progress->find($id);

        if (!$data['progress']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if ($this->request->is('post')) {
            if ($this->validate($this->rules)) {
                $progressData = [
                    'user_id' => $this->request->getPost('user_id'),
                    'unit_id' => $this->request->getPost('unit_id'),
                    'progress_percentage' => $this->request->getPost('progress_percentage'),
                    'watch_time' => $this->request->getPost('watch_time') ?? 0,
                    'last_position_seconds' => $this->request->getPost('last_position_seconds') ?? 0
                ];

                if ($this->progress->update($id, $progressData)) {
                    $this->show_msg('success', lang("Admin.edit"), lang("Admin.edit_success"));
                    return redirect()->to(ADMIN_URL . "progress");
                } else {
                    $this->show_msg('danger', lang("Admin.error"), lang("Admin.edit_error"));
                }
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }

        $data['users'] = $this->users->findAll();
        $data['units'] = $this->units->getUnitsWithCourseInfo();
        return view('form', $data);
    }

    public function show($id): \CodeIgniter\HTTP\ResponseInterface
    {
        $data['title'] = lang("Admin.show_data");
        $data['progress'] = $this->progress->getProgressWithDetails()->where('tb_user_item_progress.id', $id)->first();

        if (!$data['progress']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('show', $data);
    }

    public function delete($id = null): \CodeIgniter\HTTP\ResponseInterface
    {
        if ($this->progress->delete($id)) {
            $this->show_msg('success', lang("Admin.delete"), lang("Admin.delete_success"));
        } else {
            $this->show_msg('danger', lang("Admin.error"), lang("Admin.delete_error"));
        }
        return redirect()->to(ADMIN_URL . "progress");
    }

    public function resetProgress()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $userId = $this->request->getPost('user_id');
        $unitId = $this->request->getPost('unit_id');

        if (!$userId || !$unitId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => lang('Progress.user_unit_required')
            ]);
        }

        $progress = $this->progress->getUserUnitProgress($userId, $unitId);
        if (!$progress) {
            return $this->response->setJSON([
                'success' => false,
                'message' => lang('Progress.progress_not_found')
            ]);
        }

        $resetData = [
            'progress_percentage' => 0,
            'watch_time' => 0,
            'last_position_seconds' => 0,
            'is_completed' => 0,
            'completed_at' => null
        ];

        if ($this->progress->update($progress->id, $resetData)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => lang('Progress.reset_success')
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => lang('Progress.reset_failed')
            ]);
        }
    }

    public function exportProgress()
    {
        $courseId = $this->request->getGet('course_id');
        $userId = $this->request->getGet('user_id');

        $query = $this->progress->getProgressWithDetailsBuilder();

        if ($courseId) {
            $query->where('tb_units.course_id', $courseId);
        }

        if ($userId) {
            $query->where('tb_user_item_progress.user_id', $userId);
        }

        $progressData = $query->get()->getResultArray();

        $filename = 'progress_report_' . date('Y-m-d') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // CSV headers
        fputcsv($output, [
            lang('Progress.csv_user'), lang('Progress.csv_course'), lang('Progress.csv_section'),
            lang('Progress.csv_unit'), lang('Progress.csv_progress_percent'),
            lang('Progress.csv_watch_time'), lang('Progress.csv_completed'), lang('Progress.csv_last_updated')
        ]);

        // CSV data
        foreach ($progressData as $progress) {
            $watchTime = gmdate('H:i:s', $progress['watch_time']);
            fputcsv($output, [
                $progress['username'],
                $progress['course_title'],
                $progress['section_name'],
                $progress['unit_name'],
                round($progress['progress_percentage'], 2) . '%',
                $watchTime,
                $progress['is_completed'] ? lang('Progress.csv_yes') : lang('Progress.csv_no'),
                $progress['updated_at']
            ]);
        }

        fclose($output);
        exit;
    }

    /**
     * Get recent completions for dashboard
     */
    private function getRecentCompletions($limit = 10)
    {
        return $this->progressModel
                   ->select('tb_user_item_progress.*, users.username, tb_units.unit_name as unit_title, tb_courses.course_title')
                   ->join('users', 'users.id = tb_user_item_progress.user_id')
                   ->join('tb_units', 'tb_units.id = tb_user_item_progress.unit_id')

                   ->join('tb_courses', 'tb_courses.id = tb_units.course_id')
                   ->where('tb_user_item_progress.is_completed', 1)
                   ->orderBy('tb_user_item_progress.completed_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get top performing users
     */
    private function getTopPerformers($limit = 10)
    {
        return $this->progressModel
                   ->select('users.username, users.email, COUNT(*) as completed_units, SUM(tb_user_item_progress.watch_time) as total_watch_time')
                   ->join('users', 'users.id = tb_user_item_progress.user_id')
                   ->where('tb_user_item_progress.is_completed', 1)
                   ->groupBy('tb_user_item_progress.user_id')
                   ->orderBy('completed_units', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get course completion rates
     */
    private function getCourseCompletionRates()
    {
        return $this->db->table('tb_courses')
                       ->select('tb_courses.course_title, 
                                COUNT(DISTINCT tb_user_item_progress.user_id) as enrolled_users,
                                COUNT(CASE WHEN tb_user_item_progress.is_completed = 1 THEN 1 END) as completed_units,
                                COUNT(tb_units.id) as total_units')

                       ->join('tb_units', 'tb_units.course_id = tb_courses.id')
                       ->join('tb_user_item_progress', 'tb_user_item_progress.unit_id = tb_units.id', 'left')
                       ->where('tb_courses.active', 1)
                       ->groupBy('tb_courses.id')
                       ->get()
                       ->getResultArray();
    }

    /**
     * Get completion statistics for a course
     */
    private function getCompletionStats($courseId)
    {
        $totalUnits = $this->db->table('tb_units')

                              ->where('tb_units.course_id', $courseId)
                              ->where('tb_units.active', 1)
                              ->countAllResults();

        $completedProgress = $this->db->table('tb_user_item_progress')
                                     ->join('tb_units', 'tb_units.id = tb_user_item_progress.unit_id')

                                     ->where('tb_units.course_id', $courseId)
                                     ->where('tb_user_item_progress.is_completed', 1)
                                     ->countAllResults();

        // Get units for this course
        $courseUnits = $this->db->table('tb_units')
                               ->select('id')
                               ->where('course_id', $courseId)
                               ->get()
                               ->getResultArray();
        $courseUnitIds = array_column($courseUnits, 'id');

        $enrolledUsers = 0;
        $enrolledUsers = 0;
        
        // Count users enrolled in this course via tb_course_enrollments
        $enrolledUsers = $this->db->table('tb_course_enrollments')
            ->where('course_id', $courseId)
            ->where('status', 'approved')
            ->countAllResults();

        return [
            'total_units' => $totalUnits,
            'completed_progress' => $completedProgress,
            'enrolled_users' => $enrolledUsers,
            'completion_rate' => $totalUnits > 0 ? round(($completedProgress / ($totalUnits * $enrolledUsers)) * 100, 2) : 0
        ];
    }






}
