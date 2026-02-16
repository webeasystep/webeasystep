<?php

namespace Modules\Articles\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DtTable;
use Modules\Articles\Models\ArticlesModel;
use App\Libraries\FireUploader;

class AdminArticles extends BaseController
{
    protected ArticlesModel $articles;
    protected array $rules;
    protected FireUploader $fireUploader;

    public function __construct()
    {
        $this->fireUploader = new FireUploader();
        $this->articles = new ArticlesModel();
        $this->rules = [
            "title" => ['label' => lang("Articles.title"), 'rules' => "required|min_length[3]"],
            "content_ar" => ['label' => lang("Articles.content"), 'rules' => "required"],
        ];
    }


    public function index()
    {
        $data['title'] = lang('Articles.articles_List');

        if ($this->request->isAJAX()) {
            $articlesModel = $this->articles
                ->select('id, title, image, slug, active, sort, created_at, updated_at')
                ->orderBy('id', 'desc')
                ->builder();

            DtTable::hideColumns(['id']);
            DtTable::setColumnSwitch('active');
            DtTable::searchableColumns(['title']);
            DtTable::orderableColumns(['title', 'slug', 'sort']);
            DtTable::setColumnImage('image');

            // Add a link around the title column using slug
            DtTable::setColumnLink('title', base_url('articles/article_show/{slug}'));

            DtTable::setShowColumns("title,slug,active,sort");

            $output = DtTable::tableRender($articlesModel, false);

            return $this->response->setJSON($output);
        } else {
            return view('index', $data);
        }
    }



    function add()
    {
        $data['title'] = lang("Admin.add_data");
        if ($this->request->is('post')) {
            if ($this->validate($this->rules)) {
                $id = $this->data_arr();
                $this->fireUploader->upload_photos($this->articles, 'image', $id);
                $this->show_msg('success', lang("Admin.add_operation"), lang("Admin.add_success"));
                return redirect()->to(ADMIN_URL . "articles");
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }

        // Initialize empty files array for new articles
        $data['files'] = [];

        return view("form", $data);
    }
    //$validationErrors = $this->validation->getErrors();
    // return redirect()->back()->withInput()->with('errors', $validationErrors);
    public function edit($id)
    {
        $data['title'] = lang("Admin.edit_data");

        // if the profile photo is updated
        if ($this->request->is('post')) {
            if ($this->validate($this->rules)) {
                $this->fireUploader->upload_photos($this->articles, 'image', $id);
                $id = $this->data_arr($id);
                $this->show_msg('success', lang("Admin.edit"), lang("Admin.edit_success"));
                return redirect()->to(ADMIN_URL . "articles");
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }


        // Fetch the categories
        $data['article'] = $this->articles->getArticleById($id);

        // Existing images for FireUploader
        $data['files'] = !empty($data['article']->image) ? json_decode($data['article']->image, true) : [];

        return view('form', $data);
    }


    public function data_arr($id = NULL)
    {

        $builder = $this->db->table('articles');

        // Generate Arabic-friendly slug
        $title = $this->request->getPost('title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $slug = $this->generateArabicSlug($title);

        // Sanitize and prepare data
        $data = [
            'title' => $title,
            'slug' => $slug,
            'meta_description' => $this->request->getPost('meta_description', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'meta_tags' => $this->request->getPost('meta_tags', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'content' => $this->request->getPost('content_ar'),
            'sort' => $this->request->getPost('sort', FILTER_SANITIZE_NUMBER_INT) ?: 1,
            'active' => $this->request->getPost('active') ? '1' : '0',
        ];

        if ($id) {
            // Update existing record
            $builder->where('id', $id);
            $builder->update($data);
        } else {
            // Insert new record
            $builder->insert($data);
            $id = $this->db->insertID();
        }

        // Uncomment for debugging: Output the last executed query
        // echo $this->db->getLastQuery(); exit;

        return $id;
    }

    function bulk_merchant_off_mode()
    {
        // add any data you want in this array
        $off_mode = $this->request->getPost('off_mode');
        $this->db->query("update articles set active = $off_mode ") ;

        $is_done = $this->db->affectedRows();
        if (!empty($is_done)) {
            $results['html'] = lang("Admin.procedure_successfully");
            $results['status'] = 200;
        } else {
            $results['html'] = "عفوا ، هذا الاجراء تم بالفعل";
        }
        return $this->response->setJSON($results);

    }

    /**
     * Generate URL-friendly slug from Arabic text
     * Keeps Arabic characters and replaces spaces with dashes
     */
    private function generateArabicSlug(string $text): string
    {
        // Remove extra whitespace
        $text = trim($text);

        // Replace spaces with dashes
        $text = preg_replace('/\s+/', '-', $text);

        // Remove special characters except Arabic letters, numbers, and dashes
        $text = preg_replace('/[^\p{Arabic}a-zA-Z0-9\-]/u', '', $text);

        // Remove multiple consecutive dashes
        $text = preg_replace('/-+/', '-', $text);

        // Remove leading/trailing dashes
        $text = trim($text, '-');

        // If empty, generate a unique ID
        if (empty($text)) {
            $text = 'article-' . time();
        }

        return $text;
    }

}
