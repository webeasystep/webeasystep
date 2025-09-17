<?php
namespace Modules\Admin\Controllers;

use App\Controllers\BaseController;
use Modules\Courses\Models\CoursesModel;
use Modules\Courses\Models\SectionsModel;
use Modules\Units\Models\UnitsModel;
use Modules\Progress\Models\UserUnitProgressModel;

use Modules\Quizzes\Models\QuizzesModel;
use Modules\Quizzes\Models\QuizAttemptsModel;
use App\Models\AuthModel;

class Admin extends BaseController
{
    protected $coursesModel;
    protected $sectionsModel;
    protected $unitsModel;
    protected $progressModel;
    protected $quizzesModel;
    protected $quizAttemptsModel;
    protected $authModel;

    public function __construct()
    {
        $this->coursesModel = new CoursesModel();
        $this->sectionsModel = new SectionsModel();
        $this->unitsModel = new UnitsModel();
        $this->progressModel = new UserUnitProgressModel();
        $this->quizzesModel = new QuizzesModel();
        $this->quizAttemptsModel = new QuizAttemptsModel();
        $this->authModel = new AuthModel();
        helper(['form', 'url', 'date']);
    }

    /**
     * Admin Dashboard with comprehensive analytics
     */
    public function dashboard()
    {
        $session = session();
        $userRole = $session->get('user_role');
        
        if ($userRole !== 'admin') {
            return redirect()->to('/login')->with('error', 'Access denied. Admin privileges required.');
        }

        // Get overview statistics
        $stats = $this->getDashboardStats();
        
        // Get recent activities
        $recentActivities = $this->getRecentActivities();
        
        // Get course analytics
        $courseAnalytics = $this->getCourseAnalytics();
        
        // Get user analytics
        $userAnalytics = $this->getUserAnalytics();
        
        // Get financial analytics
        $financialAnalytics = $this->getFinancialAnalytics();
        
        // Get learning progress analytics
        $progressAnalytics = $this->progressModel->getProgressAnalytics();
        
        $data = [
            'title' => 'Admin Dashboard',
            'description' => 'Comprehensive analytics and system overview',
            'stats' => $stats,
            'recent_activities' => $recentActivities,
            'course_analytics' => $courseAnalytics,
            'user_analytics' => $userAnalytics,
            'financial_analytics' => $financialAnalytics,
            'progress_analytics' => $progressAnalytics
        ];
        
        return view('dashboard', $data);
    }

    /**
     * Get dashboard overview statistics
     */
    private function getDashboardStats(): array
    {
        // Total counts
        $totalUsers = $this->authModel->countAllResults();
        $totalCourses = $this->coursesModel->where('active', 1)->countAllResults();
        $totalSections = $this->sectionsModel->where('active', 1)->countAllResults();
        $totalUnits = $this->unitsModel->where('active', 1)->countAllResults();
        
        // Unit Enrollments
        $totalEnrollments = $this->db->table('tb_unit_enrollments')
                                    ->where('status', 'approved')
                                    ->countAllResults();
        
        // Quiz attempts
        $totalQuizAttempts = $this->quizAttemptsModel->countAllResults();
        
        // Revenue tracking removed (Billing module discontinued)
        $totalRevenue = 0;
        
        // Payment requests removed (Billing module discontinued)
        $pendingPayments = 0;
        
        // Active learners (users with progress in last 7 days)
        $activeLearners = $this->progressModel
                             ->where('updated_at >=', date('Y-m-d H:i:s', strtotime('-7 days')))
                             ->groupBy('user_id')
                             ->countAllResults();
        
        // Unit enrollment approval rate
        $approvedEnrollments = $this->db->table('tb_unit_enrollments')
                                       ->where('status', 'approved')
                                       ->countAllResults();
        
        $totalUnitEnrollments = $this->db->table('tb_unit_enrollments')
                                         ->countAllResults();
        
        $approvalRate = $totalUnitEnrollments > 0 ? round(($approvedEnrollments / $totalUnitEnrollments) * 100, 2) : 0;
        
        return [
            'total_users' => $totalUsers,
            'total_courses' => $totalCourses,
            'total_sections' => $totalSections,
            'total_units' => $totalUnits,
            'total_enrollments' => $totalEnrollments,
            'total_quiz_attempts' => $totalQuizAttempts,
            'total_revenue' => $totalRevenue,
            'pending_payments' => $pendingPayments,
            'active_learners' => $activeLearners,
            'approval_rate' => $approvalRate
        ];
    }

    /**
     * Get recent system activities
     */
    private function getRecentActivities(): array
    {
        $activities = [];
        
        // Recent unit enrollments
        $recentEnrollments = $this->db->table('tb_unit_enrollments e')
                                     ->select('e.created_at, u.username, e.unit_ids, e.total_amount, e.status')
                                     ->join('tb_users u', 'u.id = e.user_id')
                                     ->orderBy('e.created_at', 'DESC')
                                     ->limit(5)
                                     ->get()
                                     ->getResultArray();
        
        foreach ($recentEnrollments as $enrollment) {
            $unitIds = json_decode($enrollment['unit_ids'], true);
            $unitCount = is_array($unitIds) ? count($unitIds) : 0;
            $statusColor = $enrollment['status'] === 'approved' ? 'success' : ($enrollment['status'] === 'rejected' ? 'danger' : 'warning');
            
            $activities[] = [
                'type' => 'unit_enrollment',
                'message' => $enrollment['username'] . ' requested ' . $unitCount . ' units for $' . $enrollment['total_amount'] . ' - ' . ucfirst($enrollment['status']),
                'timestamp' => $enrollment['created_at'],
                'icon' => 'fas fa-shopping-cart',
                'color' => $statusColor
            ];
        }
        
        // Recent quiz attempts
        $recentQuizzes = $this->db->table('tb_quiz_attempts qa')
                                 ->select('qa.created_at, u.username, q.quiz_title, qa.score')
                                 ->join('tb_users u', 'u.id = qa.user_id')
                                 ->join('tb_quizzes q', 'q.id = qa.quiz_id')
                                 ->orderBy('qa.created_at', 'DESC')
                                 ->limit(5)
                                 ->get()
                                 ->getResultArray();
        
        foreach ($recentQuizzes as $quiz) {
            $activities[] = [
                'type' => 'quiz',
                'message' => $quiz['username'] . ' completed quiz "' . $quiz['quiz_title'] . '" with score ' . $quiz['score'] . '%',
                'timestamp' => $quiz['created_at'],
                'icon' => 'fas fa-question-circle',
                'color' => 'info'
            ];
        }
        
        // Recent payment requests
        $recentPayments = $this->db->table('tb_payment_requests pr')
                                  ->select('pr.created_at, u.username, pr.amount, pr.status')
                                  ->join('tb_users u', 'u.id = pr.user_id')
                                  ->orderBy('pr.created_at', 'DESC')
                                  ->limit(5)
                                  ->get()
                                  ->getResultArray();
        
        foreach ($recentPayments as $payment) {
            $color = $payment['status'] === 'approved' ? 'success' : ($payment['status'] === 'rejected' ? 'danger' : 'warning');
            $activities[] = [
                'type' => 'payment',
                'message' => $payment['username'] . ' requested ' . $payment['amount'] . ' credits - ' . ucfirst($payment['status']),
                'timestamp' => $payment['created_at'],
                'icon' => 'fas fa-credit-card',
                'color' => $color
            ];
        }
        
        // Sort all activities by timestamp
        usort($activities, function($a, $b) {
            return strtotime($b['timestamp']) - strtotime($a['timestamp']);
        });
        
        return array_slice($activities, 0, 15);
    }

    /**
     * Get unit enrollment analytics
     */
    private function getCourseAnalytics(): array
    {
        // Most popular units (based on unit enrollment frequency)
        $popularUnits = $this->db->query("
            SELECT u.unit_title, COUNT(*) as enrollment_count
            FROM tb_unit_enrollments e
            JOIN tb_units u ON JSON_CONTAINS(e.unit_ids, CAST(u.id AS JSON))
            WHERE e.status = 'approved'
            GROUP BY u.id, u.unit_title
            ORDER BY enrollment_count DESC
            LIMIT 10
        ")->getResultArray();
        
        // Unit enrollment approval rates
        $unitApprovalRates = $this->db->table('tb_unit_enrollments')
                                     ->select('status, COUNT(*) as count')
                                     ->groupBy('status')
                                     ->get()
                                     ->getResultArray();
        
        $approvalStats = [];
        foreach ($unitApprovalRates as $stat) {
            $approvalStats[$stat['status']] = $stat['count'];
        }
        
        // Monthly unit enrollment trends
        $enrollmentTrends = $this->db->table('tb_unit_enrollments')
                                    ->select('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as enrollments, SUM(total_amount) as revenue')
                                    ->where('created_at >=', date('Y-m-d', strtotime('-12 months')))
                                    ->groupBy('month')
                                    ->orderBy('month', 'ASC')
                                    ->get()
                                    ->getResultArray();
        
        return [
            'popular_units' => $popularUnits,
            'approval_stats' => $approvalStats,
            'enrollment_trends' => $enrollmentTrends
        ];
    }

    /**
     * Get user analytics
     */
    private function getUserAnalytics(): array
    {
        // User registration trends
        $registrationTrends = $this->db->table('tb_users')
                                      ->select('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as registrations')
                                      ->where('created_at >=', date('Y-m-d', strtotime('-12 months')))
                                      ->groupBy('month')
                                      ->orderBy('month', 'ASC')
                                      ->get()
                                      ->getResultArray();
        
        // Most active users
        $activeUsers = $this->db->table('tb_user_unit_progress p')
                               ->select('u.username, u.email, 
                                        COUNT(p.id) as units_accessed,
                                        SUM(p.watch_time_seconds) as total_watch_time,
                                        COUNT(CASE WHEN p.is_completed = 1 THEN 1 END) as completed_units')
                               ->join('tb_users u', 'u.id = p.user_id')
                               ->groupBy('p.user_id')
                               ->orderBy('total_watch_time', 'DESC')
                               ->limit(10)
                               ->get()
                               ->getResultArray();
        
        // User engagement levels
        $engagementLevels = $this->db->table('tb_users u')
                                    ->select('COUNT(CASE WHEN p.updated_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 1 END) as active_weekly,
                                             COUNT(CASE WHEN p.updated_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 END) as active_monthly,
                                             COUNT(DISTINCT u.id) as total_users')
                                    ->join('tb_user_unit_progress p', 'p.user_id = u.id', 'left')
                                    ->get()
                                    ->getRowArray();
        
        return [
            'registration_trends' => $registrationTrends,
            'active_users' => $activeUsers,
            'engagement_levels' => $engagementLevels
        ];
    }

    /**
     * Get financial analytics
     */
    private function getFinancialAnalytics(): array
    {
        // Revenue trends
        $revenueTrends = $this->db->table('tb_credit_transactions')
                                 ->select('DATE_FORMAT(created_at, "%Y-%m") as month, 
                                          SUM(CASE WHEN transaction_type = "purchase" AND status = "completed" THEN amount ELSE 0 END) as revenue,
                                          SUM(CASE WHEN transaction_type = "spend" THEN amount ELSE 0 END) as spending')
                                 ->where('created_at >=', date('Y-m-d', strtotime('-12 months')))
                                 ->groupBy('month')
                                 ->orderBy('month', 'ASC')
                                 ->get()
                                 ->getResultArray();
        
        // Billing analytics removed (Billing module discontinued)
        $topSpenders = [];
        $paymentStats = [];
        $coursePurchaseAnalytics = [];
        
        return [
            'revenue_trends' => $revenueTrends,
            'top_spenders' => $topSpenders,
            'payment_stats' => $paymentStats,
            'course_purchase_analytics' => $coursePurchaseAnalytics
        ];
    }

    /**
     * Get system health metrics
     */
    public function systemHealth()
    {
        $session = session();
        $userRole = $session->get('user_role');
        
        if ($userRole !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        // Database health
        $dbHealth = $this->checkDatabaseHealth();
        
        // Storage health
        $storageHealth = $this->checkStorageHealth();
        
        // Performance metrics
        $performanceMetrics = $this->getPerformanceMetrics();
        
        return $this->response->setJSON([
            'success' => true,
            'health' => [
                'database' => $dbHealth,
                'storage' => $storageHealth,
                'performance' => $performanceMetrics
            ]
        ]);
    }

    /**
     * Check database health
     */
    private function checkDatabaseHealth(): array
    {
        $health = ['status' => 'healthy', 'issues' => []];
        
        try {
            // Check database connection
            $this->db->query('SELECT 1');
            
            // Check table sizes
            $tables = ['tb_users', 'tb_courses', 'tb_unit_enrollments', 'tb_user_unit_progress'];
            $tableSizes = [];
            
            foreach ($tables as $table) {
                $count = $this->db->table($table)->countAllResults();
                $tableSizes[$table] = $count;
                
                // Check for potential issues
                if ($table === 'tb_user_unit_progress' && $count > 100000) {
                    $health['issues'][] = 'Large progress table may need optimization';
                }
            }
            
            $health['table_sizes'] = $tableSizes;
            
        } catch (\Exception $e) {
            $health['status'] = 'error';
            $health['issues'][] = 'Database connection failed: ' . $e->getMessage();
        }
        
        return $health;
    }

    /**
     * Check storage health
     */
    private function checkStorageHealth(): array
    {
        $health = ['status' => 'healthy', 'issues' => []];
        
        // Check upload directories
        $uploadDirs = [
            FCPATH . 'uploads/courses',
            FCPATH . 'uploads/articles',
            FCPATH . 'uploads/pages'
        ];
        
        foreach ($uploadDirs as $dir) {
            if (!is_dir($dir)) {
                $health['issues'][] = "Upload directory missing: {$dir}";
                $health['status'] = 'warning';
            } elseif (!is_writable($dir)) {
                $health['issues'][] = "Upload directory not writable: {$dir}";
                $health['status'] = 'error';
            }
        }
        
        // Check disk space
        $freeSpace = disk_free_space(FCPATH);
        $totalSpace = disk_total_space(FCPATH);
        $usedPercentage = (($totalSpace - $freeSpace) / $totalSpace) * 100;
        
        $health['disk_usage'] = [
            'free_space' => $this->formatBytes($freeSpace),
            'total_space' => $this->formatBytes($totalSpace),
            'used_percentage' => round($usedPercentage, 2)
        ];
        
        if ($usedPercentage > 90) {
            $health['issues'][] = 'Disk space critically low';
            $health['status'] = 'error';
        } elseif ($usedPercentage > 80) {
            $health['issues'][] = 'Disk space running low';
            $health['status'] = 'warning';
        }
        
        return $health;
    }

    /**
     * Get performance metrics
     */
    private function getPerformanceMetrics(): array
    {
        $startTime = microtime(true);
        
        // Test database query performance
        $dbStart = microtime(true);
        $this->db->table('tb_users')->limit(1)->get();
        $dbTime = (microtime(true) - $dbStart) * 1000;
        
        // Memory usage
        $memoryUsage = memory_get_usage(true);
        $memoryPeak = memory_get_peak_usage(true);
        
        return [
            'database_query_time' => round($dbTime, 2) . 'ms',
            'memory_usage' => $this->formatBytes($memoryUsage),
            'memory_peak' => $this->formatBytes($memoryPeak),
            'php_version' => PHP_VERSION,
            'server_load' => sys_getloadavg()[0] ?? 'N/A'
        ];
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Export analytics data
     */
    public function exportAnalytics()
    {
        $session = session();
        $userRole = $session->get('user_role');
        
        if ($userRole !== 'admin') {
            return redirect()->to('/login')->with('error', 'Access denied');
        }

        $exportData = [
            'export_date' => date('Y-m-d H:i:s'),
            'stats' => $this->getDashboardStats(),
            'course_analytics' => $this->getCourseAnalytics(),
            'user_analytics' => $this->getUserAnalytics(),
            'financial_analytics' => $this->getFinancialAnalytics(),
            'progress_analytics' => $this->progressModel->getProgressAnalytics()
        ];
        
        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="admin_analytics_' . date('Y-m-d') . '.json"');
        
        return $this->response->setJSON($exportData);
    }
}