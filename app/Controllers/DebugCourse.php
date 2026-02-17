<?php

namespace App\Controllers;

use Modules\Courses\Models\CoursesModel;

class DebugCourse extends BaseController
{
    public function index()
    {
        $coursesModel = new CoursesModel();
        // Check for the specific slug
        $slug = 'python-scripting-ai';
        $course = $coursesModel->where('slug', $slug)->first(); // without checking active status

        if ($course) {
            echo "Course Found:\n";
            echo "ID: " . $course->id . "\n";
            echo "Title: " . $course->course_title . "\n";
            echo "Slug: " . $course->slug . "\n";
            echo "Active: " . $course->active . "\n";
            echo "Intro Video ID: " . $course->intro_video_id . "\n";
        } else {
            echo "Course NOT FOUND with slug: $slug\n";
            // Check if there are any courses at all
            $all = $coursesModel->findAll(5);
            echo "First 5 courses in DB:\n";
            foreach($all as $c) {
                 echo "- [{$c->id}] {$c->course_title} (Slug: {$c->slug}, Active: {$c->active})\n";
            }
        }
    }
}
