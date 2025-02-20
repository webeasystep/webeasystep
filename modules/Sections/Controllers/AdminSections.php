<?php
namespace Modules\Sections\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DtTable;
use Modules\Sections\Models\SectionsModel;

class AdminSections extends BaseController
{
    protected $sections;
    protected $rules;

    public function __construct()
    {
        $this->sections = new SectionsModel();
        $this->rules = [
            "section_link" => ['label' => lang("Sections.section_link"), 'rules' => "required"],
            "title" => ['label' => lang("Sections.title"), 'rules' => "required"],
            "icon" => ['label' => lang("Sections.icon"), 'rules' => "required"],
        ];
    }

    /**/
    public function index()
    {
        // i want to detect controller path automatically
        // set edit and view
        $data['title'] = lang('Sections.sections_list');

        if ($this->request->isAJAX()) {
            $sectionsModel = $this->sections
                ->select('s1.id, parent.title as parent_section, s1.section_link, s1.title, s1.sort, s1.active, s1.created_at')
                ->from('sections s1',true)
                ->join('sections parent', 'parent.id = s1.parent_id', 'left')
                ->groupBy(['s1.id'])
                ->builder();

             DtTable::searchableColumns(['parent.title','s1.section_link','s1.title','s1.sort']);
             DtTable::orderableColumns(['section_link', 'title', 'sort', 'active']);
             DtTable::setColumnSwitch('active');
          //   DtTable::setColumnLink('title', base_url('sections'));
            // DtTable::notSearchableColumns(['parent_section']);
            // DtTable::hideColumns(['id']);
            // DtTable::hideActions(['delete', 'show']);
            // DtTable::stateSave('false',120);
            $output = DtTable::tableRender($sectionsModel, false);
            return $this->response->setJSON($output);
        } else {
            return view('index', $data);
        }
    }

    public function add()
    {
        $data['title'] = lang("Admin.add_data");

        if ($this->request->is('post')) {
            // $this->rules['mobile'] = "required|is_unique[sections.mobile]";
            if ($this->validate($this->rules)) {
                $this->data_arr();
                // Print the updated query
                /* echo $this->sections->getLastQuery()->getQuery(); exit;*/
                $this->show_msg('success', lang("Admin.edit"), lang("Admin.edit_success"));
                return redirect()->to(ADMIN_URL . "sections");
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }
        $data['sections'] = $this->sections->get_sections();

        return view('form', $data);
    }

    //$validationErrors = $this->validation->getErrors();
    // return redirect()->back()->withInput()->with('errors', $validationErrors);
    public function edit($id)
    {

        $data['title'] = lang('Sections.edit_data');
        if ($this->request->is('post')) {
            // if the profile photo is updated
            // $this->rules['mobile'] = "required|is_unique[sections.mobile,id,$id]";
            if ($this->validate($this->rules)) {
                $this->data_arr($id);
                $this->show_msg('success', lang("Admin.edit"), lang("Admin.edit_success"));
                return redirect()->to(ADMIN_URL . "sections");
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }

        $data['section'] = $this->sections->find($id); // Fetch the section data by ID

        // Fetch the categories
        $data['sections'] = $this->sections->get_sections();

        // Fetch the user's selected categories
        // $data['current_sections'] = $this->sections->getParentSections($id);
        return view('form', $data);
    }

    function data_arr($id = NULL){
        // add new page data
        $data = [
            'section_link' => $this->request->getPost('section_link', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'parent_id' => $this->request->getPost('parent_id', FILTER_SANITIZE_NUMBER_INT),
            'title' => $this->request->getPost('title', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'icon' => $this->request->getPost('icon'),
            'active' => $this->request->getPost('active') ? 1 : 0,
            'sort' => $this->request->getPost('sort'),
        ];

        // Save the data using the save method
        if ($id) {
            // Update the existing record
            $this->sections->update($id, $data);
        } else {
            // Insert a new record
            $this->sections->insert($data);
        }
        return $id ?? $this->sections->getInsertID();
    }

}
