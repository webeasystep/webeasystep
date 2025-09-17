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
        return view('index');
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
        return View('Site', 'user', $data);
    }
}
