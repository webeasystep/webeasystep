<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestAttemptCounter extends BaseCommand
{
    protected $group       = 'Testing';
    protected $name        = 'test:attempt-counter';
    protected $description = 'Test the quiz attempt counter functionality';

    public function run(array $params)
    {
        CLI::write('=== Testing Attempt Counter ===', 'yellow');
        
        $userId = 1;
        $quizId = 1;
        
        CLI::write("User ID: $userId");
        CLI::write("Quiz ID: $quizId");
        CLI::write('');

        // Get database connection
        $db = \Config\Database::connect();

        // Direct SQL query
        $query = $db->query("SELECT COUNT(*) as count FROM tb_quiz_attempts WHERE user_id = ? AND quiz_id = ?", [$userId, $quizId]);
        $result = $query->getRow();
        CLI::write("Direct SQL Count: " . $result->count, 'green');

        // Test the model method
        try {
            $model = new \Modules\Quizzes\Models\QuizAttemptsModel();
            $attemptCount = $model->getUserAttemptCount($userId, $quizId);
            CLI::write("Model Method Count: $attemptCount", 'green');
        } catch (\Exception $e) {
            CLI::write("Model Error: " . $e->getMessage(), 'red');
        }

        // Check if quiz exists
        $quizQuery = $db->query("SELECT id, quiz_title FROM tb_quizzes WHERE id = ?", [$quizId]);
        $quiz = $quizQuery->getRow();
        if ($quiz) {
            CLI::write("Quiz exists: ID {$quiz->id}, Title: {$quiz->quiz_title}", 'cyan');
        } else {
            CLI::write("Quiz with ID $quizId does not exist", 'red');
        }

        // Check if user exists
        $userQuery = $db->query("SELECT id, username FROM users WHERE id = ?", [$userId]);
        $user = $userQuery->getRow();
        if ($user) {
            CLI::write("User exists: ID {$user->id}, Username: {$user->username}", 'cyan');
        } else {
            CLI::write("User with ID $userId does not exist", 'red');
        }

        // Test the actual controller method
        CLI::write('');
        CLI::write('=== Testing Controller Method ===', 'yellow');
        
        try {
            // Simulate the controller environment
            $request = \Config\Services::request();
            $response = \Config\Services::response();
            
            $controller = new \Modules\Quizzes\Controllers\Quizzes();
            
            // We can't directly call the take method, but we can test the logic
            CLI::write("Controller class loaded successfully", 'green');
            
        } catch (\Exception $e) {
            CLI::write("Controller Error: " . $e->getMessage(), 'red');
        }
    }
}