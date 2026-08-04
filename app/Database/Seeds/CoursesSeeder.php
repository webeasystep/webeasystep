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
                'course_desc' => 'تغطي المادة المفاهيم الأساسية للبرمجة الشيئية كالتغليف والوراثة وتعدد الأشكال بلغة Java. تمكن الطالب من بناء برامج مرنة وقابلة لإعادة الاستخدام وتصميم الفئات والكائنات بأسلوب برمجي احترافي.',
                'short_desc' => 'IT232 | CS230 | DS230 - البرمجة الكائنية',
                'slug' => 'oop',
                'college_id' => 1,
                'department_id' => 1,
                'course_price' => 135,
                'is_free' => 0,
                'active' => 1,
                'is_open' => 0,
                'sort' => 1,
                'image' => '[]',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'course_title' => 'هياكل البيانات (Data Structure)',
                'course_desc' => 'تتناول المادة طرق تنظيم وتخزين البيانات في الذاكرة كالقوائم المترابطة والمكدسات والصفوف والأشجار. تهدف إلى كتابة كود عالي الكفاءة وتحليل تعقيد الذاكرة والوقت لاختيار البنية الأنسب لحل المشكلات.',
                'short_desc' => 'IT245 | CS240 | DS240 - هياكل البيانات',
                'slug' => 'data-structure',
                'college_id' => 1,
                'department_id' => 1,
                'course_price' => 135,
                'is_free' => 0,
                'active' => 1,
                'is_open' => 0,
                'sort' => 2,
                'image' => '[]',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'course_title' => 'مقدمة في قواعد البيانات (Intro to Database)',
                'course_desc' => 'تشمل المادة مفاهيم تصميم قواعد البيانات العلاقية ونمذجة الكيانات (ERD) وتطبيق المعايير القياسية. تدرب الطالب على استعلامات SQL وتصميم الجداول وإدارة البيانات بكفاءة وأمان في الأنظمة البرمجية.',
                'short_desc' => 'IT244 | CS350 | DS350 - قواعد البيانات',
                'slug' => 'intro-database',
                'college_id' => 1,
                'department_id' => 1,
                'course_price' => 135,
                'is_free' => 0,
                'active' => 1,
                'is_open' => 0,
                'sort' => 3,
                'image' => '[]',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'course_title' => 'شبكات الحاسب (Computer Networks)',
                'course_desc' => 'تشرح المادة معمارية شبكات الحاسب وبروتوكولات التوصيل وفق نموذجي OSI و TCP/IP في طبقات الشبكة المختلفة. تمكن الطالب من فهم توجيه البيانات وعناوين IP وتبادل الحزم وأساسيات أمان شبكات الاتصال.',
                'short_desc' => 'IT351 | CS360 | DS360 - شبكات الحاسب',
                'slug' => 'computer-networks',
                'college_id' => 1,
                'department_id' => 2,
                'course_price' => 135,
                'is_free' => 0,
                'active' => 1,
                'is_open' => 0,
                'sort' => 4,
                'image' => '[]',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'course_title' => 'نظم التشغيل (Operating Systems)',
                'course_desc' => 'تتعرض المادة للمكونات الداخلية لنظم التشغيل كإدارة العمليات والجدولة والمزامنة ومنع حالات الاختناق. توضح آلية إدارة الذاكرة الرئيسية والافتراضية وأنظمة الملفات لرفع كفاءة واستقرار الحاسب.',
                'short_desc' => 'IT241 | CS351 | DS351 - نظم التشغيل',
                'slug' => 'operating-systems',
                'college_id' => 1,
                'department_id' => 1,
                'course_price' => 135,
                'is_free' => 0,
                'active' => 1,
                'is_open' => 0,
                'sort' => 5,
                'image' => '[]',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'course_title' => 'تقنيات الويب (Web Technologies)',
                'course_desc' => 'تركز المادة على بناء وتطوير تطبيقات الويب التفاعلية باستخدام لغات HTML و CSS و JavaScript. تكسب الطالب مهارات ربط واجهات المستخدم بالخوادم وقواعد البيانات لإنشاء مواقع حديثة ومتجاوبة.',
                'short_desc' => 'IT361 | CS361 | DS362 - تقنيات الويب',
                'slug' => 'web-technologies',
                'college_id' => 1,
                'department_id' => 2,
                'course_price' => 135,
                'is_free' => 0,
                'active' => 1,
                'is_open' => 0,
                'sort' => 6,
                'image' => '[]',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'course_title' => 'الخوارزميات (Algorithms)',
                'course_desc' => 'تناقش المادة استراتيجيات تصميم الخوارزميات كالبرمجة الديناميكية وتقنيات الجشع والبحث والفرز. تركز على التحليل الرياضي لحساب تعقيد الوقت واختيار الحلول البرمجية الأسرع والأكثر كفاءة.',
                'short_desc' => 'CS353 | DS352 - الخوارزميات',
                'slug' => 'algorithms',
                'college_id' => 1,
                'department_id' => 1,
                'course_price' => 135,
                'is_free' => 0,
                'active' => 1,
                'is_open' => 0,
                'sort' => 7,
                'image' => '[]',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert or update courses
        foreach ($courses as $course) {
            $existing = $this->db->table('tb_courses')
                ->where('slug', $course['slug'])
                ->get()
                ->getRow();

            if (!$existing) {
                $this->db->table('tb_courses')->insert($course);
            } else {
                $this->db->table('tb_courses')
                    ->where('id', $existing->id)
                    ->update([
                        'course_desc'   => $course['course_desc'],
                        'short_desc'    => $course['short_desc'],
                        'college_id'    => $course['college_id'],
                        'department_id' => $course['department_id'],
                    ]);
            }
        }
    }
}
