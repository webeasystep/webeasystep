<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class AddInstructorBioColumn extends BaseCommand
{
    protected $group       = 'App';
    protected $name        = 'users:add-bio-column';
    protected $description = 'Add instructor_bio and avatar columns to users table';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $fields = $db->getFieldNames('users');

        if (!in_array('instructor_bio', $fields)) {
            $db->query("ALTER TABLE users ADD COLUMN instructor_bio TEXT NULL AFTER full_name");
            CLI::write('Added instructor_bio column successfully!', 'green');
        } else {
            CLI::write('instructor_bio column already exists.', 'yellow');
        }

        if (!in_array('avatar', $fields)) {
            $db->query("ALTER TABLE users ADD COLUMN avatar TEXT NULL AFTER instructor_bio");
            CLI::write('Added avatar column successfully!', 'green');
        } else {
            CLI::write('avatar column already exists.', 'yellow');
        }
    }
}
