<?php
namespace Modules\Courses\Controllers;
use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;
use Modules\Courses\Models\CoursesModel;

class Courses extends BaseController
{
    public CoursesModel $coursesModel;

    public function __construct()
    {
        $this->coursesModel = new CoursesModel();
    }

    public function index(): string
    {
        $data = [
            'title' => lang('Courses.Courses'),
            'courses' => $this->coursesModel->where('is_free', 1)->paginate(10),
            'pager' => $this->coursesModel->pager,
        ];

        return view('site/index', $data);
    }
    function show_page($slug): string
    {
        // Get the page content from the database
        $page = $this->coursesModel->getPage($slug);

        // If the page does not exist, return a 404 error
        if (!$page) {
            throw PageNotFoundException::forPageNotFound();
        }

        // Determine the language, default to 'ar' if not set
        $lang = lang('Site.lang'); // Assuming `Site.lang` is a helper method to get the current language (ar or en)

        // Dynamically set the title and content based on the language
        $data = [
            'title' => $page['title_' . $lang], // Fetch the title in the correct language
            'desc' => $page['desc_' . $lang],   // Fetch the description in the correct language
            'content' => $page['content_' . $lang], // Fetch the content in the correct language
            'page_info' => $page,  // All other page data if needed
        ];
        return view('site/show', $data);
    }
}
