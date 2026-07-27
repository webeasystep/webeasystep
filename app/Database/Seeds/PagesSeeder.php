<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PagesSeeder extends Seeder
{
    public function run()
    {
        $termsData = [
            'page_link'  => 'terms-conditions',
            'title_ar'   => 'الشروط والأحكام',
            'title_en'   => 'Terms & Conditions',
            'desc_ar'    => 'الشروط والأحكام الخاصة بمنصة فخر أكاديمي (FakhrCS)',
            'desc_en'    => 'Terms and Conditions for FakhrCS Platform',
            'content_ar' => '<p><strong>أهلاً بك في منصة فخر أكاديمي (FakhrCS)</strong> - المنصة المتخصصة في الشروحات الأكاديمية والمقررات البرمجية لطلاب وطالبات <strong>الجامعة السعودية الإلكترونية (SEU)</strong>.</p>' .
                            '<ol>' .
                            '<li><strong>التفعيل والتواصل عبر الواتساب:</strong> بعد إتمام عملية التسجيل، يُرجى <a href="https://wa.me/201032863861" target="_blank" rel="noopener noreferrer">التواصل عبر الواتساب (اضغط هنا)</a> لتأكيد وتفعيل الاشتراك وتلقي الدعم الفني اللازم.</li>' .
                            '<li><strong>الملكية الفكرية وحقوق النشر:</strong> جميع حقوق الطبع والنشر والتوزيع محفوظة لموقع فخر أكاديمي (FakhrCS) وشكراً مقدماً لاحترامك حقوق الملكية الفكرية. يُمنع منعاً باتاً تحميل الفيديوهات أو تسجيل الشاشة أو إعادة رفع وتوزيع المحتوى على تليجرام أو واتساب أو غيرها.</li>' .
                            '<li><strong>مدة سريان المنتج:</strong> مدة سريان الاشتراك في المقرر هي ترم دراسي واحد فقط تبدأ من تاريخ التفعيل حتى نهاية الاختبارات النهائية للفصل.</li>' .
                            '<li><strong>استخدام جهاز واحد والتعرف الآلي:</strong> يعمل المنتج على جهاز حاسوب واحد فقط لكل طالب. وفي حالة عمل تهيئة (Format) للجهاز فإن المنتج قادر على معرفة الجهاز الذي تم تفعيله عليه تلقائياً.</li>' .
                            '<li><strong>المواصفات الفنية وأنظمة التشغيل:</strong> احضار/استخدام جهاز حاسوب ذو مواصفات معقولة وخالٍ من العيوب الفنية. يعمل المقرر على Windows 7, 8, 8.1, 10, 11 ونظام الماك (macOS).</li>' .
                            '<li><strong>سرية البيانات والدعم الفني:</strong> عند التسجيل يتم طلب الاسم والبريد الإلكتروني ورقم الجوال لأهميتها في عمليات التفعيل والدعم الفني وستكون في سرية تامة.</li>' .
                            '</ol>',
            'content_en' => 'Terms and Conditions details for FakhrCS platform.',
            'active'     => 1,
            'show_home'  => 0,
            'sort'       => 1,
            'parent_id'  => 0,
        ];

        // Check if page already exists
        $existing = $this->db->table('pages')->where('page_link', 'terms-conditions')->get()->getRow();

        if ($existing) {
            $this->db->table('pages')->where('id', $existing->id)->update($termsData);
        } else {
            $this->db->table('pages')->insert($termsData);
        }
    }
}

