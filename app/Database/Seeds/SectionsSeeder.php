<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SectionsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'parent_id' => 0,
                'section_link' => 'settings',
                'title' => 'الإعدادات',
                'icon' => 'nav-icon fas fa-cog',
                'active' => 1,
                'sort' => 6,
            ],
            [
                'parent_id' => 0,
                'section_link' => 'dashboard',
                'title' => 'الرئيسية',
                'icon' => 'nav-icon fas fa-tachometer-alt',
                'active' => 1,
                'sort' => 1,
            ],
            [
                'parent_id' => 0,
                'section_link' => '#',
                'title' => 'المستخدمين والصلاحيات',
                'icon' => 'nav-icon fas fa-cube',
                'active' => 1,
                'sort' => 2,
            ],
            [
                'parent_id' => 3,
                'section_link' => 'students',
                'title' => 'الطلاب',
                'icon' => 'fas fa-user-graduate',
                'active' => 1,
                'sort' => 1,
            ],
            [
                'parent_id' => 3,
                'section_link' => 'instructors',
                'title' => 'المحاضرين',
                'icon' => 'fas fa-chalkboard-teacher',
                'active' => 1,
                'sort' => 2,
            ],
            [
                'parent_id' => 3,
                'section_link' => 'users',
                'title' => 'جميع المستخدمين',
                'icon' => 'fas fa-users',
                'active' => 1,
                'sort' => 3,
            ],
            [
                'parent_id' => 3,
                'section_link' => 'permissions',
                'title' => 'الصلاحيات',
                'icon' => 'far fa-circle nav-icon',
                'active' => 1,
                'sort' => 4,
            ],
            [
                'parent_id' => 3,
                'section_link' => 'groups',
                'title' => 'المجموعات',
                'icon' => 'far fa-circle nav-icon',
                'active' => 1,
                'sort' => 5,
            ],
            [
                'parent_id' => 0,
                'section_link' => 'sections',
                'title' => 'الأقسام',
                'icon' => 'far fa-circle nav-icon',
                'active' => 1,
                'sort' => 2,
            ],
            [
                'parent_id' => 0,
                'section_link' => 'contact_us',
                'title' => 'رسائل التواصل',
                'icon' => 'far fa-circle nav-icon',
                'active' => 1,
                'sort' => 8,
            ],
            [
                'parent_id' => 0,
                'section_link' => 'pages',
                'title' => 'الصفحات الاضافية',
                'icon' => 'far fa-circle nav-icon',
                'active' => 1,
                'sort' => 7,
            ],
        ];
        // Insert data into the sections table
        $this->db->table('sections')->insertBatch($data);
    }
}
