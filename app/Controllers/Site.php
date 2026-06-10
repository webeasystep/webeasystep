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

    /**
     * Preview and send special Telegram invite email
     */
    public function sendSpecialInvite()
    {
        $emailParam = $this->request->getVar('email');
        $successMsg = null;
        $errorMsg = null;

        if ($this->request->getMethod() === 'post' || !empty($emailParam)) {
            $emailAddress = trim($emailParam ?? $this->request->getPost('email') ?? '');
            if (!empty($emailAddress) && filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
                $emailService = \Config\Services::email();
                $emailService->setTo($emailAddress);
                $emailService->setSubject('دعوة خاصة للانضمام إلى جروب الدعم الفني والمتابعة');
                
                $message = MainView('Modules\Enrollments\Views\Site\emails\special_telegram_invite');
                
                $emailService->setMessage($message);
                $emailService->setMailType('html');
                
                if ($emailService->send()) {
                    $successMsg = "تم إرسال إيميل الدعوة بنجاح إلى: " . esc($emailAddress);
                } else {
                    $errorMsg = "فشل في إرسال الإيميل. يرجى التحقق من الإعدادات.";
                    log_message('error', 'Failed to send special invite email: ' . $emailService->printDebugger(['headers']));
                }
            } else {
                $errorMsg = "يرجى إدخال بريد إلكتروني صحيح.";
            }
        }

        // Generate the preview message content
        $previewContent = MainView('Modules\Enrollments\Views\Site\emails\special_telegram_invite');

        // Render a preview page with a send form at the top
        $html = '
        <!DOCTYPE html>
        <html lang="ar" dir="rtl">
        <head>
            <meta charset="UTF-8">
            <title>معاينة وإرسال إيميل الدعوة الاستثنائي</title>
            <style>
                body {
                    margin: 0;
                    padding: 0;
                    font-family: Arial, sans-serif;
                    background-color: #f1f3f5;
                }
                .control-bar {
                    background-color: #1a202c;
                    color: white;
                    padding: 15px 20px;
                    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                    position: sticky;
                    top: 0;
                    z-index: 1000;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }
                .control-bar h2 {
                    margin: 0;
                    font-size: 18px;
                }
                .send-form {
                    display: flex;
                    gap: 10px;
                    align-items: center;
                }
                .send-form input[type="email"] {
                    padding: 8px 12px;
                    font-size: 14px;
                    border: 1px solid #cbd5e0;
                    border-radius: 6px;
                    width: 250px;
                    background-color: white;
                    color: black;
                }
                .send-form button {
                    background-color: #136ad5;
                    color: white;
                    border: none;
                    padding: 8px 16px;
                    font-size: 14px;
                    font-weight: bold;
                    border-radius: 6px;
                    cursor: pointer;
                    transition: background-color 0.2s;
                }
                .send-form button:hover {
                    background-color: #0b5cbf;
                }
                .alert {
                    padding: 10px 15px;
                    border-radius: 6px;
                    font-size: 14px;
                    margin-left: 15px;
                }
                .alert-success {
                    background-color: #c6f6d5;
                    color: #22543d;
                    border: 1px solid #9ae6b4;
                }
                .alert-danger {
                    background-color: #fed7d7;
                    color: #742a2a;
                    border: 1px solid #feb2b2;
                }
                .preview-container {
                    padding: 20px;
                }
            </style>
        </head>
        <body>
            <div class="control-bar">
                <div>
                    <h2>معاينة وإرسال إيميل الدعوة الخاص بالتيليجرام 📢</h2>
                </div>
                <div style="display: flex; align-items: center;">
                    ' . ($successMsg ? '<div class="alert alert-success">' . $successMsg . '</div>' : '') . '
                    ' . ($errorMsg ? '<div class="alert alert-danger">' . $errorMsg . '</div>' : '') . '
                    <form method="post" action="" class="send-form" style="margin-right: 15px;">
                        ' . csrf_field() . '
                        <label for="email" style="font-size: 14px; margin-left: 5px;">البريد الإلكتروني للارسال:</label>
                        <input type="email" name="email" id="email" placeholder="example@domain.com" required value="' . esc($emailParam ?? '') . '">
                        <button type="submit">إرسال الآن 🚀</button>
                    </form>
                </div>
            </div>
            <div class="preview-container">
                ' . $previewContent . '
            </div>
        </body>
        </html>
        ';

        return $html;
    }
}
