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

    function index(): string
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
        // Define the slugs that should have a specific course view
        $courseSlugs = ['scratch_track', 'python_track', 'web_track', 'mobile_track'];
        $page = $this->pages->getPage($slug);

        // If the page does not exist for the given slug, return a 404 error
        if (!$page) {
            throw PageNotFoundException::forPageNotFound();
        }
        // Prepare the data array, common for all views
        $data = [
            'title'     => $page['title'], // Assuming 'title' column exists
            'page_info' => $page,         // Pass the full page data
        ];

        if (in_array($slug, $courseSlugs)) {
            // Load the specific course view
            return view('site/' . $slug, $data);
        } else {
            // If the slug is not a course slug, load the default page view
            return view('site/show', $data);
        }
    }


    /**
     * Get page by ID.
     *
     * @param int $id
     * @return mixed|null
     */
    public function getPageById($id)
    {
        $query = $this->db->table($this->table)
            ->where($this->primaryKey, $id)
            ->get();

        return $query->getRow();
    }


}
