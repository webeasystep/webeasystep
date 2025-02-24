<?php

namespace Modules\Pages\Controllers;
use App\Controllers\BaseController;
use App\Libraries\DtTable;
use App\Libraries\FireUploader;
use Modules\Pages\Models\PagesModel;

class AdminPages extends BaseController
{
    protected PagesModel $pages;
    protected array $rules;
    protected FireUploader $fireUploader;

    public function __construct()
    {
        $this->fireUploader = new FireUploader();
        $this->pages = new PagesModel();
        $this->rules = [
            "page_link" => ['label' => lang("Pages.page_link"), 'rules' => "required"],
            "title" => ['label' => lang("Pages.title"), 'rules' => "required"],
            "desc" => ['label' => lang("Pages.desc"), 'rules' => "required"],
            "content" => ['label' => lang("Pages.content"), 'rules' => "required"],
        ];
    }
/**/
    public function index()
    {
        // i want to detect controller path automatically
        // set edit and view
        $data['title'] = lang('Pages.pages');

        if ($this->request->isAJAX()) {
            $pagesModel = $this->pages
                ->select('s1.id, prn.title as main_parent ,s1.title,  s1.images, s1.sort, s1.active, s1.show_home, s1.created_at,s1.updated_at')
                ->from('pages s1',true)
                ->join('pages prn', 'prn.id = s1.parent_id', 'left')
                ->groupBy(['s1.id'])
                ->builder();


            DtTable::changeColumn('gender', function ($data, $row) {
                return "<strong style='background-color: #0c84ff'>{$data}</strong>";
            });
            DtTable::searchableColumns(['s1.title']);
            DtTable::orderableColumns(['title','desc','images']);
            DtTable::setColumnSwitch('active');
            DtTable::setColumnSwitch('show_home');
            DtTable::setColumnImage('images');

            // DtTable::hideActions(['delete', 'show']);
            //  DtTable::stateSave('false',120);
            $output = DtTable::tableRender($pagesModel, false);

            return $this->response->setJSON($output);
        } else {
            return view('index', $data);
        }
    }

    public function add()
    {
        $data['title'] = lang("Admin.add_data");

        if ($this->request->is('post')) {
            // $this->rules['mobile'] = "required|is_unique[pages.mobile]";

            if ($this->validate($this->rules)) {
                $id = $this->datar();
                $this->fireUploader->upload_photos($this->pages, 'images', $id);
                $this->show_msg('success', lang("Admin.edit"), lang("Admin.edit_success"));
                return redirect()->to(ADMIN_URL . "pages");
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }
        // Fetch the categories
        $data['pages'] = $this->pages->get_pages();

        return view('form', $data);
    }


    //$validationErrors = $this->validation->getErrors();
    // return redirect()->back()->withInput()->with('errors', $validationErrors);
    public function edit($id)
    {

        $data['title'] = lang("Admin.add_data");

        if ($this->request->is('post')) {
            $this->datar($id);
            // if the profile photo is updated
            // $this->rules['mobile'] = "required|is_unique[pages.mobile,id,$id]";
            if ($this->request->getFile('images')) {
                $this->rules['images'] = 'max_size[images,1024]|is_image[images]';
            }

            if ($this->validate($this->rules)) {
                $this->datar();
                $this->fireUploader->upload_photos($this->pages, 'images', $id);
                if (!empty($id)) { // Check if $id is not empty
                    $this->show_msg('success', lang("Admin.edit"), lang("Admin.edit_success"));
                } else {
                    // Handle error when $id is empty
                    $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
                }
                return redirect()->to(ADMIN_URL . "pages");
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }

        $data['page'] = $this->pages->find($id); // Fetch the page data by ID
        $data['pages'] = $this->pages->get_pages();

        $data['files'] = json_decode($data['page']->images, true);
        return view('form', $data);
    }

    function datar($id = NULL){
        // add new page data
        $data = [
            'page_link' => $this->request->getPost('page_link', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'parent_id' => $this->request->getPost('parent_id'),
            'sort' => $this->request->getPost('sort'),
            'show_home' => $this->request->getPost('show_home') ? 1 : 0,
            'active' => $this->request->getPost('active') ? 1 : 0,
        ];

        // Retrieve the supported locales
        $data["title_"] = $this->request->getPost("title");
        $data["desc"] = $this->request->getPost("desc");
        $data["content"] = $this->request->getPost("content");
        // Save the data using the save method
        if ($id) {
            // Update the existing record
            $this->pages->update($id, $data);
        } else {
            // Insert a new record
            $this->pages->insert($data);
        }
        return $id ?? $this->pages->getInsertID();

    }
    public function send()
    {
        $data = $this->request->getPost('data');

        dd($data);
    }

}
