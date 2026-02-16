<?php

namespace App\Controllers;

use App\Models\BaseModel;
use CodeIgniter\Events\Events;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Session\Session;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Exceptions\ValidationException;
use CodeIgniter\Shield\Models\UserModel;
use Modules\Pages\Controllers\Pages;
use Modules\Pages\Models\PagesModel;

class Site extends BaseController
{
    protected $auth;
    private BaseModel $baseModel;
    protected $config;
    private $rules = [
        //     'name' => ['rules' => 'required'],
        'full_name' => ['rules' => 'required|alpha_numeric_space|is_unique[users.full_name,id,{id}]'],
        // 'email' => ['rules' => 'required|valid_email|is_unique[tb_users.email,id,{id}]'],
        //'mobile' => ['rules' => 'required'],
        //  'role' => ['rules' => 'required']
    ];

    public function __construct()
    {
        $this->config = config('Auth');
        $this->baseModel = new BaseModel();
    }

    public function index()
    {
        $this->home();
    }

    public function home()
    {
        $data['page_name'] = 'home';
        $data['title']     = lang('Site.home');

        // 1) Articles
        $data['articles'] = $this->db
            ->table('articles')
            ->where('active', 1)
            ->get()
            ->getResultArray();

        // 2) Get courses data from Courses controller
        $coursesController = new \Modules\Courses\Controllers\Courses();
        $data['courses'] = $coursesController->getCoursesForHome();

        // 4) Possibly fetch “about us” page or other custom pages
        $data['about_us'] = $this->db
            ->table('pages')
            ->where('active', '1')
            ->where('page_link', 'about_us')
            ->get()
            ->getRowArray();

        // Provide a unified data array if needed
        $data['data'] = $data;

        // 5) Render the main home view
        echo MainView('site_layout/home', $data);
    }

    //--------------------------------------------------------------------
    // Login/out
    //--------------------------------------------------------------------
    // Auth methods moved to Modules\Users\Controllers\Users

}
