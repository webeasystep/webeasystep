<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Modules\Progress\Models\UserUnitProgressModel;
use Modules\Courses\Models\CoursesModel;
use App\Models\AuthModel;
use Config\Email;

class SendWeeklyProgressEmails extends BaseCommand
{
    protected $group       = 'Notifications';
    protected $name        = 'email:weekly-progress';
    protected $description = 'Send weekly progress reports to parents/guardians';
    protected $usage       = 'email:weekly-progress [options]';
    protected $arguments   = [];
    protected $options     = [
        '--dry-run' => 'Preview emails without sending them',
        '--user-id' => 'Send report for specific user ID only',
        '--force'   => 'Force send even if already sent this week'
    ];

    protected $progressModel;
    protected $coursesModel;
    protected $authModel;
    protected $email;

    public function run(array $params)
    {
        $this->progressModel = new UserUnitProgressModel();
        $this->coursesModel = new CoursesModel();
        $this->authModel = new AuthModel();

        // Initialize email service
        $this->email = \Config\Services::email();

        CLI::write('Starting Weekly Progress Email Task...', 'green');
        CLI::write('Time: ' . date('Y-m-d H:i:s'));

        $dryRun = CLI::getOption('dry-run');
        $specificUserId = CLI::getOption('user-id');
        $force = CLI::getOption('force');

        if ($dryRun) {
            CLI::write('DRY RUN MODE - No emails will be sent', 'yellow');
        }

        try {
            $users = $this->getEligibleUsers($specificUserId, $force);

            if (empty($users)) {
                CLI::write('No eligible users found for weekly progress emails.', 'yellow');
                return;
            }

            CLI::write(sprintf('Found %d eligible users', count($users)), 'cyan');

            $successCount = 0;
            $errorCount = 0;

            foreach ($users as $user) {
                try {
                    CLI::write(sprintf('Processing user: %s (%s)', $user['username'], $user['email']), 'white');

                    $progressData = $this->getUserProgressData($user['id']);

                    if (empty($progressData['enrolled_courses'])) {
                        CLI::write('  - No enrolled courses, skipping', 'yellow');
                        continue;
                    }

                    if ($dryRun) {
                        $this->previewEmail($user, $progressData);
                    } else {
                        $result = $this->sendProgressEmail($user, $progressData);

                        if ($result) {
                            $this->recordEmailSent($user['id']);
                            $successCount++;
                            CLI::write('  - Email sent successfully', 'green');
                        } else {
                            $errorCount++;
                            CLI::write('  - Failed to send email', 'red');
                        }
                    }

                } catch (\Exception $e) {
                    $errorCount++;
                    CLI::write('  - Error: ' . $e->getMessage(), 'red');
                }
            }

            if (!$dryRun) {
                CLI::write(sprintf('\nTask completed: %d sent, %d errors', $successCount, $errorCount), 'green');
                $this->logTaskExecution($successCount, $errorCount);
            } else {
                CLI::write('\nDry run completed - no emails sent', 'yellow');
            }

        } catch (\Exception $e) {
            CLI::write('Fatal error: ' . $e->getMessage(), 'red');
            return EXIT_ERROR;
        }

        return EXIT_SUCCESS;
    }

    /**
     * Get users eligible for weekly progress emails
     */
    private function getEligibleUsers($specificUserId = null, $force = false): array
    {
        $builder = $this->authModel->builder();
        $builder->select('id, username, email, parent_email, created_at');
        $builder->where('active', 1);

        if ($specificUserId) {
            $builder->where('id', $specificUserId);
        } else {
            // Only include users with parent email or regular email
            $builder->groupStart()
                   ->where('parent_email IS NOT NULL')
                   ->where('parent_email !=', '')
                   ->orWhere('email IS NOT NULL')
                   ->where('email !=', '')
                   ->groupEnd();

            if (!$force) {
                // Exclude users who already received email this week
                $weekStart = date('Y-m-d', strtotime('monday this week'));
                $builder->where('id NOT IN (SELECT user_id FROM tb_email_logs WHERE email_type = "weekly_progress" AND sent_at >= "' . $weekStart . '")');
            }
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Get user's progress data for the week
     */
    private function getUserProgressData(int $userId): array
    {
        // Get user's learning stats
        $stats = $this->progressModel->getUserLearningStats($userId);

        // Get enrolled courses with progress
        $enrolledCourses = $this->coursesModel->getAllUserCourses($userId, 'active');

        foreach ($enrolledCourses as &$course) {
            $course->completion_percentage = $this->progressModel->getCourseCompletionPercentage($userId, $course->course_id);
            $course->weekly_progress = $this->getWeeklyProgress($userId, $course->course_id);
        }

        // Get recent activity (last 7 days)
        $recentActivity = $this->progressModel->getRecentActivity($userId, 10);
        $weeklyActivity = array_filter($recentActivity, function($activity) {
            return strtotime($activity['updated_at']) >= strtotime('-7 days');
        });

        // Get units needing attention
        $unitsNeedingAttention = $this->progressModel->getUnitsNeedingAttention($userId, 5);

        return [
            'stats' => $stats,
            'enrolled_courses' => $enrolledCourses,
            'weekly_activity' => $weeklyActivity,
            'units_needing_attention' => $unitsNeedingAttention
        ];
    }

    /**
     * Get weekly progress for a specific course
     */
    private function getWeeklyProgress(int $userId, int $courseId): array
    {
        $weekStart = date('Y-m-d H:i:s', strtotime('monday this week'));

        $builder = $this->progressModel->builder();
        $builder->select('COUNT(*) as units_accessed, SUM(watch_time_seconds) as total_watch_time, COUNT(CASE WHEN is_completed = 1 THEN 1 END) as units_completed');
        $builder->join('tb_units', 'tb_units.id = tb_user_unit_progress.unit_id');
        $builder->join('tb_sections', 'tb_sections.id = tb_units.section_id');
        $builder->where('tb_user_unit_progress.user_id', $userId);
        $builder->where('tb_sections.course_id', $courseId);
        $builder->where('tb_user_unit_progress.updated_at >=', $weekStart);

        $result = $builder->get()->getRowArray();

        return [
            'units_accessed' => $result['units_accessed'] ?? 0,
            'total_watch_time' => $result['total_watch_time'] ?? 0,
            'units_completed' => $result['units_completed'] ?? 0
        ];
    }

    /**
     * Send progress email to user/parent
     */
    private function sendProgressEmail(array $user, array $progressData): bool
    {
        try {
            // Determine recipient email (prefer parent email)
            $recipientEmail = !empty($user['parent_email']) ? $user['parent_email'] : $user['email'];
            $recipientName = !empty($user['parent_email']) ? 'Parent/Guardian' : $user['username'];

            if (empty($recipientEmail)) {
                throw new \Exception('No email address available');
            }

            // Generate email content
            $emailContent = $this->generateEmailContent($user, $progressData);

            // Configure email
            $this->email->setFrom(env('email.fromEmail', 'noreply@msarlink.com'), env('email.fromName', 'MSARLink Learning Platform'));
            $this->email->setTo($recipientEmail);
            $this->email->setSubject(sprintf('Weekly Learning Progress Report - %s', $user['username']));
            $this->email->setMessage($emailContent);
            $this->email->setMailType('html');

            // Send email
            return $this->email->send();

        } catch (\Exception $e) {
            CLI::write('Email error: ' . $e->getMessage(), 'red');
            return false;
        }
    }

    /**
     * Generate HTML email content
     */
    private function generateEmailContent(array $user, array $progressData): string
    {
        $stats = $progressData['stats'];
        $courses = $progressData['enrolled_courses'];
        $weeklyActivity = $progressData['weekly_activity'];
        $unitsNeedingAttention = $progressData['units_needing_attention'];

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Weekly Progress Report</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #4e73df; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
                .content { background: #f8f9fc; padding: 20px; border-radius: 0 0 8px 8px; }
                .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin: 20px 0; }
                .stat-card { background: white; padding: 15px; border-radius: 8px; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
                .stat-number { font-size: 24px; font-weight: bold; color: #4e73df; }
                .stat-label { font-size: 12px; color: #666; text-transform: uppercase; }
                .course-item { background: white; padding: 15px; margin: 10px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
                .progress-bar { background: #e3e6f0; height: 8px; border-radius: 4px; overflow: hidden; margin: 10px 0; }
                .progress-fill { background: #1cc88a; height: 100%; transition: width 0.3s ease; }
                .activity-item { padding: 10px 0; border-bottom: 1px solid #eee; }
                .footer { text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; color: #666; font-size: 14px; }
                .btn { display: inline-block; padding: 12px 24px; background: #4e73df; color: white; text-decoration: none; border-radius: 6px; margin: 10px 5px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Weekly Learning Progress Report</h1>
                <p>Student: ' . esc($user['username']) . '</p>
                <p>Week of ' . date('M j, Y', strtotime('monday this week')) . '</p>
            </div>
            
            <div class="content">
                <h2>Learning Statistics</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number">' . $stats['completed_units'] . '</div>
                        <div class="stat-label">Units Completed</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">' . $stats['in_progress_units'] . '</div>
                        <div class="stat-label">Units in Progress</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">' . $stats['learning_streak_days'] . '</div>
                        <div class="stat-label">Day Streak</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">' . $stats['total_watch_time_formatted'] . '</div>
                        <div class="stat-label">Total Watch Time</div>
                    </div>
                </div>
                
                <h2>Course Progress</h2>';

        if (!empty($courses)) {
            foreach ($courses as $course) {
                $weeklyProgress = $course->weekly_progress;
                $html .= '
                <div class="course-item">
                    <h3>' . esc($course->course_title) . '</h3>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: ' . $course->completion_percentage . '%"></div>
                    </div>
                    <p><strong>Overall Progress:</strong> ' . number_format($course->completion_percentage, 1) . '%</p>
                    <p><strong>This Week:</strong> ' . $weeklyProgress['units_completed'] . ' units completed, ' .
                    floor($weeklyProgress['total_watch_time'] / 60) . ' minutes watched</p>
                </div>';
            }
        } else {
            $html .= '<p>No courses enrolled yet.</p>';
        }

        if (!empty($weeklyActivity)) {
            $html .= '
                <h2>This Week\'s Activity</h2>';

            foreach (array_slice($weeklyActivity, 0, 5) as $activity) {
                $html .= '
                <div class="activity-item">
                    <strong>' . esc($activity['unit_title']) . '</strong> - ' . esc($activity['course_title']) . '<br>
                    <small>Progress: ' . round($activity['progress_percentage']) . '% • ' .
                    date('M j, g:i A', strtotime($activity['updated_at'])) . '</small>
                </div>';
            }
        }

        if (!empty($unitsNeedingAttention)) {
            $html .= '
                <h2>Units Needing Attention</h2>
                <p>These units were started but not yet completed:</p>';

            foreach ($unitsNeedingAttention as $unit) {
                $html .= '
                <div class="activity-item">
                    <strong>' . esc($unit['unit_title']) . '</strong> - ' . esc($unit['course_title']) . '<br>
                    <small>Progress: ' . round($unit['progress_percentage']) . '% • Last accessed: ' .
                    date('M j', strtotime($unit['updated_at'])) . '</small>
                </div>';
            }
        }

        $html .= '
                <div style="text-align: center; margin-top: 30px;">
                    <a href="' . base_url('progress/dashboard') . '" class="btn">View Full Progress Dashboard</a>
                    <a href="' . base_url('courses') . '" class="btn">Browse Courses</a>
                </div>
            </div>
            
            <div class="footer">
                <p>This is an automated weekly progress report from MSARLink Learning Platform.</p>
                <p>If you have any questions, please contact our support team.</p>
                <p><a href="' . base_url() . '">Visit MSARLink</a></p>
            </div>
        </body>
        </html>';

        return $html;
    }

    /**
     * Preview email content without sending
     */
    private function previewEmail(array $user, array $progressData): void
    {
        CLI::write('  - Email Preview:', 'cyan');
        CLI::write('    To: ' . (!empty($user['parent_email']) ? $user['parent_email'] : $user['email']));
        CLI::write('    Subject: Weekly Learning Progress Report - ' . $user['username']);
        CLI::write('    Courses: ' . count($progressData['enrolled_courses']));
        CLI::write('    Weekly Activity: ' . count($progressData['weekly_activity']) . ' items');
        CLI::write('    Units Needing Attention: ' . count($progressData['units_needing_attention']));
    }

    /**
     * Record that email was sent to user
     */
    private function recordEmailSent(int $userId): void
    {
        $db = \Config\Database::connect();

        // Create email logs table if it doesn't exist
        $this->createEmailLogsTable($db);

        $db->table('tb_email_logs')->insert([
            'user_id' => $userId,
            'email_type' => 'weekly_progress',
            'sent_at' => date('Y-m-d H:i:s'),
            'status' => 'sent'
        ]);
    }

    /**
     * Create email logs table if it doesn't exist
     */
    private function createEmailLogsTable($db): void
    {
        if (!$db->tableExists('tb_email_logs')) {
            $forge = \Config\Database::forge();

            $forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'user_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'email_type' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
                'sent_at' => [
                    'type' => 'DATETIME',
                ],
                'status' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'default' => 'sent',
                ],
            ]);

            $forge->addKey('id', true);
            $forge->addKey(['user_id', 'email_type']);
            $forge->addKey('sent_at');

            $forge->createTable('tb_email_logs');
        }
    }

    /**
     * Log task execution for monitoring
     */
    private function logTaskExecution(int $successCount, int $errorCount): void
    {
        $logMessage = sprintf(
            'Weekly Progress Emails: %d sent, %d errors at %s',
            $successCount,
            $errorCount,
            date('Y-m-d H:i:s')
        );

        log_message('info', $logMessage);
    }
}
