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

        // Get article image for meta tags
        $metaImage = null;
        if (!empty($article->image)) {
            $images = json_decode($article->image, true);
            if (!empty($images) && is_array($images)) {
                $firstImage = reset($images);
                // Handle if image is stored as array with 'name' key or as string
                if (is_array($firstImage) && isset($firstImage['name'])) {
                    $metaImage = base_url('uploads/' . $firstImage['name']);
                } elseif (is_string($firstImage)) {
                    $metaImage = base_url('uploads/' . $firstImage);
                }
            }
        }

        $data = [
            'title'            => $article->title,
            'article'          => $article,
            // SEO Meta Tags
            'meta_title'       => $article->title,
            'meta_description' => $article->meta_description ?? mb_substr(strip_tags($article->content ?? ''), 0, 160),
            'meta_keywords'    => $article->meta_tags ?? '',
            'meta_image'       => $metaImage,
            'og_type'          => 'article',
        ];

        return view('site/article_show', $data);
    }




}
