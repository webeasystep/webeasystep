<?php namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslateLangFiles extends BaseCommand
{
    protected $group       = 'Language';
    protected $name        = 'lang:translate';
    protected $description = 'Translates language files using Google Translate.';

    public function run(array $params)
    {
        $lang_files = [
            'Admin.php',
            'Site.php',
            // Add other language files here
        ];

        foreach ($lang_files as $file) {
            $lang_file = include(APPPATH . 'Language/en/' . $file);
            $this->translate_file($lang_file, $file);
        }

        CLI::write('Language files translated successfully!', 'green');
    }

    public function translate_file($lang_file, $filename)
    {
        $tr = new GoogleTranslate();
        $tr->setSource()->setTarget('ar');
        $arr_values = implode(',', $lang_file);
        $arr_keys = array_keys($lang_file);
        $tr_values = $tr->translate($arr_values);
        $tr_values_array = explode('،', $tr_values);
        $x = 0;
        foreach ($tr_values_array as $key => $value) {
            unset($tr_values_array[$key]);
            $tr_values_array[$arr_keys[$x]] = $value;
            $x++;
        }
        file_put_contents(APPPATH . "Language/ar/" . $filename, '<?php return ' . var_export($tr_values_array, true) . ";\n");
    }
}
