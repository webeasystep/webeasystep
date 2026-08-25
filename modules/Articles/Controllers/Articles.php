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
            $pageTitle = 'نتائج البحث عن "' . $search . '" | مدونة فخر CS';
            $metaDesc = 'نتائج البحث عن مقالات وشروحات ' . $search . ' لطلاب الجامعة السعودية الإلكترونية SEU في منصة فخر CS.';
        } else {
            $articles = $this->articlesModel->getActiveArticles(9);
            $pageTitle = 'المدونة الأكاديمية | مقالات وشروحات الجامعة السعودية الإلكترونية SEU';
            $metaDesc = 'دليل ومقالات شاملة لطلاب الجامعة السعودية الإلكترونية SEU: تجميعات ماث 001، حاسب 001، اختبار ستيب STEP، شروحات البلاك بورد، تخصصات كلية الحوسبة والمعلوماتية، وحساب المعدل التراكمي.';
        }

        $data = [
            'title'            => $pageTitle,
            'meta_title'       => $pageTitle,
            'meta_description' => $metaDesc,
            'meta_keywords'    => 'الجامعة السعودية الإلكترونية, مدونة SEU, مقالات الجامعة السعودية الإلكترونية, تجميعات SEU, اختبار STEP, MATH 001, CS 001, بلاك بورد SEU, فخر CS',
            'meta_image'       => base_url('site/images/main_banner.webp'),
            'og_type'          => 'website',
            'articles'         => $articles,
            'pager'            => $this->articlesModel->pager,
            'searchQuery'      => $search,
            'recentArticles'   => $this->articlesModel->getRecentArticles(null, 5),
            'totalCount'       => $this->articlesModel->where('active', 1)->countAllResults(false),
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

        $articleTitle = $article->title;
        $metaTitle = $articleTitle . ' | الجامعة السعودية الإلكترونية SEU - فخر CS';
        $metaDesc = !empty($article->description) 
            ? $article->description 
            : mb_substr(strip_tags($article->content), 0, 160) . '...';
        
        $metaKeywords = 'الجامعة السعودية الإلكترونية, SEU, ' . $articleTitle . ', السنة التحضيرية SEU, اختبارات SEU, تجميعات SEU, فخر CS, بلاك بورد SEU';

        // Extract image
        $articleImage = base_url('site/images/main_banner.webp');
        if (!empty($article->image)) {
            if (is_string($article->image)) {
                $decoded = json_decode($article->image, true);
                if (json_last_error() === JSON_ERROR_NONE && !empty($decoded['files'][0]['full_path'])) {
                    $articleImage = base_url($decoded['files'][0]['full_path']);
                } elseif (!str_contains($article->image, '{') && !str_contains($article->image, '[')) {
                    $articleImage = base_url($article->image);
                }
            }
        }

        $data = [
            'title'            => $metaTitle,
            'meta_title'       => $metaTitle,
            'meta_description' => $metaDesc,
            'meta_keywords'    => $metaKeywords,
            'meta_image'       => $articleImage,
            'og_type'          => 'article',
            'article'          => $article,
            'recentArticles'   => $this->articlesModel->getRecentArticles((int)$article->id, 3),
        ];

        return view('Modules\Articles\Views\Site\article_show', $data);
    }
}
