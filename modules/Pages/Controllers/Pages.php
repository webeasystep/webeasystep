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

        // Get the page content from the database
        $page = $this->pages->getPage($slug);

        // If the page does not exist, return a 404 error
        if (!$page) {
            throw PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => $page['title'],
            'page_info' => $page,
        ];
        //
        //  var_dump($this->modulePath);
        return view('site/show', $data);
        //echo view('Modules\pages\Views\site\view_page', $data);
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
