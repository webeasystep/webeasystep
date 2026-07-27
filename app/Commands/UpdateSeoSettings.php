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
        $title = 'الجامعة السعودية الإلكترونية SEU | كلية الحوسبة والمعلوماتية | FakhrCS';
        $description = 'المنصة الأولى المتخصصة في هندسة محتوى كلية الحوسبة والمعلوماتية بالجامعة السعودية الإلكترونية SEU. نضمن لك الـ A+ في مواد البرمجة وقواعد البيانات والخوارزميات (IT232, CS240, IT244, CS350) بأسلوب المهندس أحمد فخر الدين.';
        $keywords = 'الجامعة السعودية الإلكترونية, SEU, كلية الحوسبة والمعلوماتية, CCI, مواد SEU, IT232, CS230, DS230, IT245, CS240, DS240, IT244, CS350, DS350, IT351, CS360, DS360, Object Oriented Programming SEU, Data Structure SEU, Introduction to Database SEU, Computer Networks SEU, حل واجبات SEU, ملخصات كلية الحوسبة, تجميعات ميدتيرم SEU, شرح م/ أحمد فخر الدين';

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
