<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CoursesSeeder extends Seeder
{
    public function run()
    {
        $courses = [
            [
                'course_title' => 'البرمجة الكائنية (OOP)',
                'course_desc' => 'البوابة لدخول التخصص. تعلم أساسيات OOP بطريقة عملية تفهمك كيف الكود بيشتغل في الذاكرة.',
                'short_desc' => 'IT232 | CS230 | DS230 - البوابة لدخول التخصص',
                'slug' => 'oop',
                'course_price' => 135,
                'is_free' => 0,
                'active' => 1,
                'sort' => 1,
                'image' => '[]',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'course_title' => 'هياكل البيانات (Data Structure)',
                'course_desc' => 'جوهر المنطق البرمجي وأساس أي مقابلة عمل. فهم عميق لكل الهياكل مع تطبيقات عملية.',
                'short_desc' => 'IT245 | CS240 | DS240 - جوهر المنطق وأساس المقابلات',
                'slug' => 'data-structure',
                'course_price' => 135,
                'is_free' => 0,
                'active' => 1,
                'sort' => 2,
                'image' => '[]',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'course_title' => 'مقدمة في قواعد البيانات (Intro to Database)',
                'course_desc' => 'العمود الفقري لكل التطبيقات الحقيقية. تعلم تصميم وإدارة قواعد البيانات باحترافية.',
                'short_desc' => 'IT244 | CS350 | DS350 - العمود الفقري للتطبيقات',
                'slug' => 'intro-database',
                'course_price' => 135,
                'is_free' => 0,
                'active' => 1,
                'sort' => 3,
                'image' => '[]',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'course_title' => 'شبكات الحاسب (Computer Networks)',
                'course_desc' => 'تبسيط النظريات التقنية المعقدة. فهم كيف تعمل الشبكات من الصفر للاحتراف.',
                'short_desc' => 'IT351 | CS360 | DS360 - تبسيط النظريات المعقدة',
                'slug' => 'computer-networks',
                'course_price' => 135,
                'is_free' => 0,
                'active' => 1,
                'sort' => 4,
                'image' => '[]',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'course_title' => 'نظم التشغيل (Operating Systems)',
                'course_desc' => 'غوص عميق في ميكانيكا الأنظمة. فهم كيف يعمل الحاسب من الداخل.',
                'short_desc' => 'IT241 | CS351 | DS351 - غوص عميق في ميكانيكا الأنظمة',
                'slug' => 'operating-systems',
                'course_price' => 135,
                'is_free' => 0,
                'active' => 1,
                'sort' => 5,
                'image' => '[]',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'course_title' => 'تقنيات الويب (Web Technologies)',
                'course_desc' => 'الأكثر تفاعلاً ونتائج ملموسة. بناء مواقع وتطبيقات ويب من البداية.',
                'short_desc' => 'IT361 | CS361 | DS362 - الأكثر تفاعلاً ونتائج ملموسة',
                'slug' => 'web-technologies',
                'course_price' => 135,
                'is_free' => 0,
                'active' => 1,
                'sort' => 6,
                'image' => '[]',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'course_title' => 'الخوارزميات (Algorithms)',
                'course_desc' => 'المستوى المتقدم - إتقان المنطق البرمجي. حل المشاكل المعقدة بكفاءة عالية.',
                'short_desc' => 'CS353 | DS352 - المستوى المتقدم - إتقان المنطق',
                'slug' => 'algorithms',
                'course_price' => 135,
                'is_free' => 0,
                'active' => 1,
                'sort' => 7,
                'image' => '[]',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert courses
        foreach ($courses as $course) {
            // Check if course already exists by slug
            $existing = $this->db->table('tb_courses')
                ->where('slug', $course['slug'])
                ->get()
                ->getRow();

            if (!$existing) {
                $this->db->table('tb_courses')->insert($course);
            }
        }
    }
}
