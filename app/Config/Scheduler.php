<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\CLI\CLI;

class Scheduler extends BaseConfig
{
    /**
     * Scheduled tasks configuration
     * 
     * This file defines all scheduled tasks that should run automatically.
     * Tasks are executed via cron jobs or task scheduler.
     */
    
    /**
     * Weekly Progress Email Task
     * Runs every Monday at 9:00 AM
     */
    public static function weeklyProgressEmails(): array
    {
        return [
            'command' => 'email:weekly-progress',
            'schedule' => '0 9 * * 1', // Every Monday at 9:00 AM (cron format)
            'description' => 'Send weekly progress reports to parents/guardians',
            'enabled' => true,
            'timeout' => 3600, // 1 hour timeout
            'options' => [
                // Add any default options here
            ]
        ];
    }
    
    /**
     * Daily Progress Reminder Task (Optional)
     * Runs every day at 6:00 PM for users with incomplete units
     */
    public static function dailyProgressReminders(): array
    {
        return [
            'command' => 'email:daily-reminders',
            'schedule' => '0 18 * * *', // Every day at 6:00 PM
            'description' => 'Send daily reminders for incomplete units',
            'enabled' => false, // Disabled by default
            'timeout' => 1800, // 30 minutes timeout
            'options' => [
                '--max-reminders' => '50' // Limit daily reminders
            ]
        ];
    }
    
    /**
     * Monthly Progress Summary Task (Optional)
     * Runs on the 1st of each month at 10:00 AM
     */
    public static function monthlyProgressSummary(): array
    {
        return [
            'command' => 'email:monthly-summary',
            'schedule' => '0 10 1 * *', // 1st of each month at 10:00 AM
            'description' => 'Send monthly progress summary reports',
            'enabled' => false, // Disabled by default
            'timeout' => 7200, // 2 hours timeout
            'options' => []
        ];
    }
    
    /**
     * System Cleanup Task
     * Runs every Sunday at 2:00 AM
     */
    public static function systemCleanup(): array
    {
        return [
            'command' => 'system:cleanup',
            'schedule' => '0 2 * * 0', // Every Sunday at 2:00 AM
            'description' => 'Clean up old logs, temporary files, and expired tokens',
            'enabled' => true,
            'timeout' => 1800, // 30 minutes timeout
            'options' => [
                '--days' => '30' // Keep logs for 30 days
            ]
        ];
    }
    
    /**
     * Get all scheduled tasks
     */
    public static function getAllTasks(): array
    {
        return [
            'weekly_progress_emails' => self::weeklyProgressEmails(),
            'daily_progress_reminders' => self::dailyProgressReminders(),
            'monthly_progress_summary' => self::monthlyProgressSummary(),
            'system_cleanup' => self::systemCleanup()
        ];
    }
    
    /**
     * Get enabled tasks only
     */
    public static function getEnabledTasks(): array
    {
        $allTasks = self::getAllTasks();
        return array_filter($allTasks, function($task) {
            return $task['enabled'] ?? false;
        });
    }
    
    /**
     * Generate cron job entries for all enabled tasks
     */
    public static function generateCronEntries(): string
    {
        $enabledTasks = self::getEnabledTasks();
        $cronEntries = [];
        
        $cronEntries[] = '# MSARLink Scheduled Tasks';
        $cronEntries[] = '# Generated on ' . date('Y-m-d H:i:s');
        $cronEntries[] = '';
        
        foreach ($enabledTasks as $taskName => $task) {
            $cronEntries[] = '# ' . $task['description'];
            
            $command = 'cd ' . ROOTPATH . ' && php spark ' . $task['command'];
            
            // Add options if specified
            if (!empty($task['options'])) {
                foreach ($task['options'] as $option => $value) {
                    $command .= ' ' . $option . '=' . $value;
                }
            }
            
            // Add timeout if specified
            if (!empty($task['timeout'])) {
                $command = 'timeout ' . $task['timeout'] . ' ' . $command;
            }
            
            // Add logging
            $logFile = WRITEPATH . 'logs/scheduler_' . $taskName . '.log';
            $command .= ' >> ' . $logFile . ' 2>&1';
            
            $cronEntries[] = $task['schedule'] . ' ' . $command;
            $cronEntries[] = '';
        }
        
        return implode("\n", $cronEntries);
    }
    
    /**
     * Validate cron schedule format
     */
    public static function validateCronSchedule(string $schedule): bool
    {
        $parts = explode(' ', trim($schedule));
        
        if (count($parts) !== 5) {
            return false;
        }
        
        // Basic validation for cron format
        $patterns = [
            '/^(\*|[0-5]?[0-9]|\*\/[0-9]+)$/', // minute
            '/^(\*|[01]?[0-9]|2[0-3]|\*\/[0-9]+)$/', // hour
            '/^(\*|[0-2]?[0-9]|3[01]|\*\/[0-9]+)$/', // day
            '/^(\*|[0-9]|1[0-2]|\*\/[0-9]+)$/', // month
            '/^(\*|[0-6]|\*\/[0-9]+)$/' // day of week
        ];
        
        foreach ($parts as $index => $part) {
            if (!preg_match($patterns[$index], $part)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Get next run time for a cron schedule
     */
    public static function getNextRunTime(string $schedule): ?string
    {
        if (!self::validateCronSchedule($schedule)) {
            return null;
        }
        
        // This is a simplified implementation
        // For production, consider using a proper cron parser library
        
        $parts = explode(' ', $schedule);
        $minute = $parts[0];
        $hour = $parts[1];
        $day = $parts[2];
        $month = $parts[3];
        $dayOfWeek = $parts[4];
        
        // Simple case: daily at specific time
        if ($minute !== '*' && $hour !== '*' && $day === '*' && $month === '*' && $dayOfWeek === '*') {
            $nextRun = mktime((int)$hour, (int)$minute, 0);
            if ($nextRun <= time()) {
                $nextRun += 86400; // Add one day
            }
            return date('Y-m-d H:i:s', $nextRun);
        }
        
        // For complex schedules, return approximate next run
        return date('Y-m-d H:i:s', strtotime('+1 day'));
    }
}