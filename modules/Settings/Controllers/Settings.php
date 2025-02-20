<?php

namespace Modules\Settings\Controllers;

use App\Controllers\BaseController;
use Modules\Settings\Models\SettingsModel;

class Settings extends BaseController
{
    protected SettingsModel $settings;
    public function __construct()
    {
        $this->settings = new SettingsModel();
    }
    public function index()
    {
        return view('\Modules\Customers\Views\Admin\index');
    }


    public function send()
    {
        $data = $this->request->getPost('data');

        dd($data);
    }

    public function news()
    {
        $news = $this->settings->findAll();

        $data = [
            'news' => $news
        ];
        return view('Modules\Users\Views\user', $data);
    }
}
