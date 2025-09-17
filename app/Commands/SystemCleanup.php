<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SystemCleanup extends BaseCommand
{
    protected $group       = 'System';
    protected $name        = 'system:cleanup';
    protected $description = 'Clean up old logs, temporary files, and expired data';
    protected $usage       = 'system:cleanup [options]';
    protected $arguments   = [];
    protected $options     = [
        '--days'     => 'Number of days to keep logs and temporary files (default: 30)',
        '--dry-run'  => 'Preview cleanup without actually deleting files',
        '--verbose'  => 'Show detailed output',
        '--logs'     => 'Clean up log files only',
        '--temp'     => 'Clean up temporary files only',
        '--tokens'   => 'Clean up expired tokens only'
    ];

    public function run(array $params)
    {
        CLI::write('Starting System Cleanup Task...', 'green');
        CLI::write('Time: ' . date('Y-m-d H:i:s'));
        
        $days = (int) CLI::getOption('days') ?: 30;
        $dryRun = CLI::getOption('dry-run');
        $verbose = CLI::getOption('verbose');
        $logsOnly = CLI::getOption('logs');
        $tempOnly = CLI::getOption('temp');
        $tokensOnly = CLI::getOption('tokens');
        
        if ($dryRun) {
            CLI::write('DRY RUN MODE - No files will be deleted', 'yellow');
        }
        
        CLI::write(sprintf('Cleaning up files older than %d days', $days), 'cyan');
        
        $totalCleaned = 0;
        $totalSize = 0;
        
        try {
            // Clean up log files
            if (!$tempOnly && !$tokensOnly) {
                $result = $this->cleanupLogs($days, $dryRun, $verbose);
                $totalCleaned += $result['count'];
                $totalSize += $result['size'];
            }
            
            // Clean up temporary files
            if (!$logsOnly && !$tokensOnly) {
                $result = $this->cleanupTempFiles($days, $dryRun, $verbose);
                $totalCleaned += $result['count'];
                $totalSize += $result['size'];
            }
            
            // Clean up expired tokens and sessions
            if (!$logsOnly && !$tempOnly) {
                $result = $this->cleanupExpiredData($dryRun, $verbose);
                $totalCleaned += $result['count'];
            }
            
            // Clean up old email logs
            if (!$logsOnly && !$tempOnly) {
                $result = $this->cleanupEmailLogs($days, $dryRun, $verbose);
                $totalCleaned += $result['count'];
            }
            
            // Clean up old progress tracking data (optional)
            $result = $this->cleanupOldProgressData($days * 2, $dryRun, $verbose); // Keep progress data longer
            $totalCleaned += $result['count'];
            
            CLI::write(sprintf(
                '\nCleanup completed: %d items processed, %s freed',
                $totalCleaned,
                $this->formatBytes($totalSize)
            ), 'green');
            
            $this->logCleanupExecution($totalCleaned, $totalSize);
            
        } catch (\Exception $e) {
            CLI::write('Cleanup error: ' . $e->getMessage(), 'red');
            return EXIT_ERROR;
        }
        
        return EXIT_SUCCESS;
    }
    
    /**
     * Clean up old log files
     */
    private function cleanupLogs(int $days, bool $dryRun, bool $verbose): array
    {
        CLI::write('Cleaning up log files...', 'yellow');
        
        $logPath = WRITEPATH . 'logs';
        $cutoffTime = time() - ($days * 24 * 60 * 60);
        $count = 0;
        $size = 0;
        
        if (!is_dir($logPath)) {
            CLI::write('  Log directory not found', 'yellow');
            return ['count' => 0, 'size' => 0];
        }
        
        $files = glob($logPath . '/*.log');
        
        foreach ($files as $file) {
            if (filemtime($file) < $cutoffTime) {
                $fileSize = filesize($file);
                
                if ($verbose) {
                    CLI::write('  - ' . basename($file) . ' (' . $this->formatBytes($fileSize) . ')', 'white');
                }
                
                if (!$dryRun) {
                    if (unlink($file)) {
                        $count++;
                        $size += $fileSize;
                    }
                } else {
                    $count++;
                    $size += $fileSize;
                }
            }
        }
        
        CLI::write(sprintf('  Processed %d log files (%s)', $count, $this->formatBytes($size)), 'green');
        return ['count' => $count, 'size' => $size];
    }
    
    /**
     * Clean up temporary files
     */
    private function cleanupTempFiles(int $days, bool $dryRun, bool $verbose): array
    {
        CLI::write('Cleaning up temporary files...', 'yellow');
        
        $tempPaths = [
            WRITEPATH . 'uploads',
            WRITEPATH . 'cache',
            WRITEPATH . 'session'
        ];
        
        $cutoffTime = time() - ($days * 24 * 60 * 60);
        $count = 0;
        $size = 0;
        
        foreach ($tempPaths as $tempPath) {
            if (!is_dir($tempPath)) {
                continue;
            }
            
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($tempPath, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );
            
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getMTime() < $cutoffTime) {
                    // Skip certain files
                    if (in_array($file->getFilename(), ['index.html', '.htaccess', '.gitkeep'])) {
                        continue;
                    }
                    
                    $fileSize = $file->getSize();
                    
                    if ($verbose) {
                        CLI::write('  - ' . $file->getPathname() . ' (' . $this->formatBytes($fileSize) . ')', 'white');
                    }
                    
                    if (!$dryRun) {
                        if (unlink($file->getPathname())) {
                            $count++;
                            $size += $fileSize;
                        }
                    } else {
                        $count++;
                        $size += $fileSize;
                    }
                }
            }
        }
        
        CLI::write(sprintf('  Processed %d temporary files (%s)', $count, $this->formatBytes($size)), 'green');
        return ['count' => $count, 'size' => $size];
    }
    
    /**
     * Clean up expired tokens and sessions
     */
    private function cleanupExpiredData(bool $dryRun, bool $verbose): array
    {
        CLI::write('Cleaning up expired data...', 'yellow');
        
        $db = \Config\Database::connect();
        $count = 0;
        
        // Clean up expired auth tokens (if using CodeIgniter Shield)
        if ($db->tableExists('auth_tokens')) {
            $expiredTokens = $db->table('auth_tokens')
                               ->where('expires <', date('Y-m-d H:i:s'))
                               ->countAllResults();
            
            if ($expiredTokens > 0) {
                if ($verbose) {
                    CLI::write('  - ' . $expiredTokens . ' expired auth tokens', 'white');
                }
                
                if (!$dryRun) {
                    $db->table('auth_tokens')
                       ->where('expires <', date('Y-m-d H:i:s'))
                       ->delete();
                }
                
                $count += $expiredTokens;
            }
        }
        
        // Clean up old password reset tokens
        if ($db->tableExists('auth_identities')) {
            $oldResets = $db->table('auth_identities')
                           ->where('type', 'email_password')
                           ->where('expires <', date('Y-m-d H:i:s'))
                           ->countAllResults();
            
            if ($oldResets > 0) {
                if ($verbose) {
                    CLI::write('  - ' . $oldResets . ' expired password reset tokens', 'white');
                }
                
                if (!$dryRun) {
                    $db->table('auth_identities')
                       ->where('type', 'email_password')
                       ->where('expires <', date('Y-m-d H:i:s'))
                       ->delete();
                }
                
                $count += $oldResets;
            }
        }
        
        CLI::write(sprintf('  Processed %d expired data records', $count), 'green');
        return ['count' => $count, 'size' => 0];
    }
    
    /**
     * Clean up old email logs
     */
    private function cleanupEmailLogs(int $days, bool $dryRun, bool $verbose): array
    {
        CLI::write('Cleaning up old email logs...', 'yellow');
        
        $db = \Config\Database::connect();
        $count = 0;
        
        if (!$db->tableExists('tb_email_logs')) {
            CLI::write('  Email logs table not found', 'yellow');
            return ['count' => 0, 'size' => 0];
        }
        
        $cutoffDate = date('Y-m-d H:i:s', time() - ($days * 24 * 60 * 60));
        
        $oldLogs = $db->table('tb_email_logs')
                     ->where('sent_at <', $cutoffDate)
                     ->countAllResults();
        
        if ($oldLogs > 0) {
            if ($verbose) {
                CLI::write('  - ' . $oldLogs . ' old email log entries', 'white');
            }
            
            if (!$dryRun) {
                $db->table('tb_email_logs')
                   ->where('sent_at <', $cutoffDate)
                   ->delete();
            }
            
            $count = $oldLogs;
        }
        
        CLI::write(sprintf('  Processed %d email log entries', $count), 'green');
        return ['count' => $count, 'size' => 0];
    }
    
    /**
     * Clean up very old progress tracking data (keep recent data)
     */
    private function cleanupOldProgressData(int $days, bool $dryRun, bool $verbose): array
    {
        CLI::write('Cleaning up very old progress data...', 'yellow');
        
        $db = \Config\Database::connect();
        $count = 0;
        
        if (!$db->tableExists('tb_user_unit_progress')) {
            CLI::write('  Progress table not found', 'yellow');
            return ['count' => 0, 'size' => 0];
        }
        
        $cutoffDate = date('Y-m-d H:i:s', time() - ($days * 24 * 60 * 60));
        
        // Only clean up progress records that are very old AND not completed
        // Keep all completed records for historical purposes
        $oldProgress = $db->table('tb_user_unit_progress')
                         ->where('updated_at <', $cutoffDate)
                         ->where('is_completed', 0)
                         ->where('progress_percentage <', 10) // Only remove barely started units
                         ->countAllResults();
        
        if ($oldProgress > 0) {
            if ($verbose) {
                CLI::write('  - ' . $oldProgress . ' old incomplete progress records', 'white');
            }
            
            if (!$dryRun) {
                $db->table('tb_user_unit_progress')
                   ->where('updated_at <', $cutoffDate)
                   ->where('is_completed', 0)
                   ->where('progress_percentage <', 10)
                   ->delete();
            }
            
            $count = $oldProgress;
        }
        
        CLI::write(sprintf('  Processed %d old progress records', $count), 'green');
        return ['count' => $count, 'size' => 0];
    }
    
    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
    
    /**
     * Log cleanup execution for monitoring
     */
    private function logCleanupExecution(int $itemsProcessed, int $bytesFreed): void
    {
        $logMessage = sprintf(
            'System Cleanup: %d items processed, %s freed at %s',
            $itemsProcessed,
            $this->formatBytes($bytesFreed),
            date('Y-m-d H:i:s')
        );
        
        log_message('info', $logMessage);
    }
}