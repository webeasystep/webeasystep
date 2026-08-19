<?php

namespace Modules\Articles\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;
use Modules\Articles\Models\ArticlesModel;

class Articles extends BaseController
{
    public ArticlesModel $articlesModel;

    public function __construct()
    {
        $this->articlesModel = new ArticlesModel();
    }

    public function index(): string
    {
        $search = trim((string)($this->request->getGet('q') ?? ''));

        if (!empty($search)) {
            $articles = $this->articlesModel->searchArticles($search, 9);
        } else {
            $articles = $this->articlesModel->getActiveArticles(9);
        }

        $data = [
            'title'          => 'المدونة | مقالات وشروحات الجامعة السعودية الإلكترونية',
            'articles'       => $articles,
            'pager'          => $this->articlesModel->pager,
            'searchQuery'    => $search,
            'recentArticles' => $this->articlesModel->getRecentArticles(null, 5),
            'totalCount'     => $this->articlesModel->where('active', 1)->countAllResults(false),
        ];

        return view('Modules\Articles\Views\Site\index', $data);
    }

    public function article_show($slug)
    {
        $slug = urldecode((string)$slug);

        $article = $this->articlesModel->getArticleBySlug($slug);

        if (!$article) {
            throw PageNotFoundException::forPageNotFound('المقال المطلوب غير موجود.');
        }

        $data = [
            'title'          => $article->title . ' | مدونة فخر CS',
            'article'        => $article,
            'recentArticles' => $this->articlesModel->getRecentArticles((int)$article->id, 3),
        ];

        return view('Modules\Articles\Views\Site\article_show', $data);
    }
}
