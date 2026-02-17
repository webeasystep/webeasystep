<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Modules\Courses\Models\CoursesModel;

class DebugCourseCommand extends BaseCommand
{
    protected $group       = 'App';
    protected $name        = 'debug:course';
    protected $description = 'Debug course status by slug.';

    public function run(array $params)
    {
        $slug = 'python-scripting-ai';
        $coursesModel = new CoursesModel();
        
        CLI::write("Testing Slug: [$slug]", 'yellow');

        // 1. Test raw find
        $course = $coursesModel->where('slug', $slug)->first();
        if ($course) {
            CLI::write("RAW MATCH: Found!", 'green');
            CLI::write("ID: [{$course->id}]");
            CLI::write("Slug: [{$course->slug}]");
            CLI::write("Active: [{$course->active}] (Type: " . gettype($course->active) . ")");
        } else {
            CLI::write("RAW MATCH: Not Found", 'red');
        }

        // 2. Test getCourseBySlug method
        $courseViaMethod = $coursesModel->getCourseBySlug($slug);
        if ($courseViaMethod) {
            CLI::write("METHOD MATCH (getCourseBySlug): Found!", 'green');
        } else {
            CLI::write("METHOD MATCH (getCourseBySlug): FAILED", 'red');
            // Diagnosis
            if ($course) {
                if ($course->active != 1) {
                     CLI::write("Reason: Active status is '{$course->active}', expected '1'", 'yellow');
                } else {
                     CLI::write("Reason: Unknown. Maybe active type mismatch?", 'yellow');
                }
            }
        }
    }
}
