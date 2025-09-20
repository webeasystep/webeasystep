<?php
namespace Modules\Articles\Controllers;
use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;
use Modules\Articles\Models\ArticlesModel;


class Articles extends BaseController
{
    public ArticlesModel $articlesModel;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->articlesModel = new ArticlesModel();
    }


    function index(): string
    {
        echo "<pre>";
        var_dump(  Password_hash('123456', PASSWORD_DEFAULT));
        echo "</pre>";
        exit;
        $data = [
            'title' =>lang('Exams.Exams'),
            'articles' => $this->articlesModel->where('active', 1)->paginate(10),
            'pager' => $this->articlesModel->pager,
        ];

        return view('site/index', $data);
    }


    public function article_show($slug)
    {
        // decode if necessary
        $slug = urldecode($slug);

        // find article by slug
        $article = $this->articlesModel
            ->where('slug', $slug)
            ->first();

        if (!$article) {
            throw PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title'   => $article->title,
            'article' => $article,
        ];

        return view('site/article_show', $data);
    }




}
