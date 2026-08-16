<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class UpdateSeoSettings extends BaseCommand
{
    protected $group       = 'App';
    protected $name        = 'seo:update';
    protected $description = 'Update SEO settings with proper UTF-8 encoding';

    public function run(array $params)
    {
        $title = 'منصة فخر CS | كورسات وشروحات الجامعة السعودية الإلكترونية SEU';
        $description = 'المنصة الأولى المتخصصة لطلاب الجامعة السعودية الإلكترونية SEU. شروحات مقررات كلية الحوسبة والمعلوماتية والسنة الأولى المشتركة، تجميعات اختبارات، ملخصات وحلول واجبات للتفوق بـ A+.';
        $keywords = 'الجامعة السعودية الإلكترونية, SEU, كورسات الجامعة السعودية الإلكترونية, شرح مواد SEU, فخر CS, كلية الحوسبة والمعلوماتية SEU, السنة الأولى المشتركة SEU, تجميعات SEU, حل واجبات SEU, ملخصات SEU, IT232, CS240, IT244, CS350, MATH 001, CS 001, ENG 001, FakhrCS';

        // Update using the setting() helper
        setting('App.title', $title);
        setting('App.site_description_ar', $description);
        setting('App.keywords_ar', $keywords);

        CLI::write('SEO Settings Updated Successfully!', 'green');
        CLI::write('Title: ' . setting('App.title'), 'yellow');
        CLI::write('Description: ' . mb_substr(setting('App.site_description_ar'), 0, 50) . '...', 'yellow');
        CLI::write('Keywords updated.', 'yellow');
    }
}
