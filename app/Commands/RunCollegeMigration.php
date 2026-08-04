<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class RunCollegeMigration extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'migrate:college';
    protected $description = 'Run the College Department Support migration';

    public function run(array $params)
    {
        CLI::write('Running AddCollegeDepartmentSupport migration...', 'yellow');
        CLI::newLine();

        try {
            // Manually load the migration file
            require_once APPPATH . 'Database/Migrations/2026-07-28-130000_AddCollegeDepartmentSupport.php';
            
            $migrationClass = 'App\\Database\\Migrations\\AddCollegeDepartmentSupport';
            $migration = new $migrationClass();
            $migration->up();
            
            CLI::write('✓ Migration completed successfully!', 'green');
            CLI::newLine();
            
            // Now verify
            CLI::write('Verifying tables...', 'yellow');
            $this->call('verify:migration');
            
        } catch (\Exception $e) {
            CLI::write('✗ Migration failed: ' . $e->getMessage(), 'red');
            CLI::write($e->getTraceAsString());
        }
    }
}
