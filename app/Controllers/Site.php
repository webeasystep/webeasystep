<?php

namespace App\Controllers;

use App\Models\BaseModel;
use CodeIgniter\Events\Events;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Session\Session;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Exceptions\ValidationException;
use CodeIgniter\Shield\Models\UserModel;
use Modules\Courses\Models\CoursesModel;
use Modules\Pages\Controllers\Pages;
use Modules\Pages\Models\PagesModel;

class Site extends BaseController
{
    protected $auth;
    private BaseModel $baseModel;
    private CoursesModel $coursesModel;
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
        $this->coursesModel = new CoursesModel();
    }

    public function index()
    {
        return $this->home();
    }

    public function home(): string
    {
        $data['page_name'] = 'home';
        $data['title']     = lang('Site.home');

        // 1) Articles
        $data['articles'] = $this->db
            ->table('articles')
            ->where('active', 1)
            ->get()
            ->getResultArray();

        // 2) Fetch active roadmap courses ordered by sort ASC.
        $courses = $this->db
            ->table('tb_courses')
            ->where('active', 1)
            ->orderBy('sort', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($courses as &$course) {
            $course['course_desc'] = $course['course_desc'] ?? '';
            $course['short_desc'] = $course['short_desc'] ?? '';
            $course['waiting_list'] = (int) ($course['waiting_list'] ?? 0);
            $course['is_free'] = (int) ($course['is_free'] ?? 0);
            $course['sort'] = (int) ($course['sort'] ?? 0);
            $course['is_available_now'] = $course['waiting_list'] === 0;
            $course['details_url'] = site_url('courses/course_details/' . $course['slug']);
            $course['checkout_url'] = site_url('enrollments/purchase-course/' . $course['id']);
        }
        unset($course);

        $data['courses'] = $courses;

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
        return MainView('site_layout/home', $data);
    }

    //--------------------------------------------------------------------
    // Login/out
    //--------------------------------------------------------------------
    // Auth methods moved to Modules\Users\Controllers\Users

}
