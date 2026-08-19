<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCourseRequestsToSections extends Migration
{
    public function up()
    {
        $exists = $this->db->table('sections')
            ->where('section_link', 'course_requests')
            ->countAllResults();

        if ($exists === 0) {
            $this->db->table('sections')->insert([
                'parent_id'    => 0,
                'section_link' => 'course_requests',
                'title'        => 'طلبات الكورسات',
                'icon'         => 'fas fa-clipboard-list',
                'active'       => 1,
                'sort'         => 8,
            ]);
        }
    }

    public function down()
    {
        $this->db->table('sections')
            ->where('section_link', 'course_requests')
            ->delete();
    }
}
