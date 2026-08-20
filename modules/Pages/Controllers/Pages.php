<?php

namespace Modules\Pages\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;
use Modules\Pages\Models\PagesModel;

class Pages extends BaseController
{
    public $pages;

    public function __construct()
    {
        $this->pages = new PagesModel();
    }

    public function index(): string
    {
        $data = [
            'title' => "كل الصفحات",
            'pages' => $this->pages->where('active', 1)->paginate(10),
            'pager' => $this->pages->pager,
        ];

        return view('index', $data);
    }

    public function view($slug): string
    {
        $page = $this->pages->getPage($slug);

        if (!$page) {
            throw PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title'     => $page['title'],
            'page_info' => $page,
        ];

        return view('site/show', $data);
    }

    /**
     * Display Terms and Conditions page
     */
    public function termsConditions(): string
    {
        $data = [
            'title' => 'الشروط والأحكام',
        ];
        
        return view('site/terms_conditions', $data);
    }

    /**
     * Display the instructor partnership guide and terms page.
     */
    public function instructorTerms(): string
    {
        $data = [
            'title' => 'دليل وحقوق الشراكة للمحاضر',
        ];

        return view('site/instructor_terms', $data);
    }

    /**
     * Display the instructor acquisition marketing page.
     */
    public function becomeInstructor(): string
    {
        $data = [
            'title' => 'انضم إلى FakhrCS كمحاضر',
        ];

        return view('site/become_instructor', $data);
    }

    /**
     * Display the student subscription benefits page.
     */
    public function studentBenefits(): string
    {
        $data = [
            'title' => 'مميزات الاشتراك للطالب',
        ];

        return view('site/student_benefits', $data);
    }
}
