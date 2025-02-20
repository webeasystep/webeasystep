<?php
namespace Modules\Articles\Controllers;
use App\Controllers\BaseController;
use Modules\Articles\Models\CoursesModel;


class Articles extends BaseController
{
    public CoursesModel $articlesModel;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->articlesModel = new CoursesModel();
    }


    function index(): string
    {
        $data = [
            'title' =>lang('Exams.Exams'),
            'articles' => $this->articlesModel->where('active', 1)->paginate(10),
            'pager' => $this->articlesModel->pager,
        ];

        return view('site/index', $data);
    }

    /*     function show($slug)
        {
            // Get the page content from the database
            $page = $this->articlesModel->getPage($slug);

            // If the page does not exist, return a 404 error
            if (!$page) {
                throw PageNotFoundException::forPageNotFound();
            }

            $data = [
                'title' => $page['title_'.lang('site.lang')],
                'page_info' => $page,
            ];

            return view('site/show', $data);
        }*/



}
