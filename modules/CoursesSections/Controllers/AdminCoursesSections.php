<?php

namespace Modules\CoursesSections\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DtTable;
use Modules\CoursesSections\Models\CoursesSectionsModel;

class AdminCoursesSections extends BaseController
{
    protected CoursesSectionsModel $coursesSections;
    protected array $rules;

    public function __construct()
    {
        $this->coursesSections = new CoursesSectionsModel();
        $this->rules = [
            "section_name" => ['label' => lang("CoursesSections.section_name"), 'rules' => "required"],
        ];
    }

    public function index()
    {
        $data['title'] = lang('CoursesSections.sections_List');

        if ($this->request->isAJAX()) {
            $coursesSectionsModel = $this->coursesSections
                ->select('id, section_name, section_desc, sort, created_at')
                ->orderBy('id', 'desc')
                ->builder();

            DtTable::hideColumns(['id']);
            DtTable::searchableColumns(['section_name']);
            DtTable::orderableColumns(['section_name', 'sort']);
            $output = DtTable::tableRender($coursesSectionsModel, false);
            DtTable::setShowColumns("section_name,section_desc,sort");

            return $this->response->setJSON($output);
        } else {
            return view('index', $data);
        }
    }

    public function add()
    {
        $data['title'] = lang("Admin.add_data");
        if ($this->request->is('post')) {
            if ($this->validate($this->rules)) {
                $this->data_arr();
                $this->show_msg('success', lang("Admin.add_operation"), lang("Admin.add_success"));
                return redirect()->to(ADMIN_URL . "courses_sections");
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }
        return view("form", $data);
    }

    public function edit($id)
    {
        $data['title'] = lang("Admin.edit_data");

        if ($this->request->is('post')) {
            if ($this->validate($this->rules)) {
                $this->data_arr($id);
                $this->show_msg('success', lang("Admin.edit"), lang("Admin.edit_success"));
                return redirect()->to(ADMIN_URL . "courses_sections");
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }

        $data['section'] = $this->coursesSections->find($id);
        return view('form', $data);
    }

    public function data_arr($id = NULL)
    {
        $builder = $this->db->table('tb_courses_sections');

        $data = [
            'section_name' => $this->request->getPost('section_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'section_desc' => $this->request->getPost('section_desc', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'sort' => $this->request->getPost('sort', FILTER_SANITIZE_NUMBER_INT),
        ];

        if ($id) {
            $builder->where('id', $id);
            $builder->update($data);
        } else {
            $builder->insert($data);
            $id = $this->db->insertID();
        }

        return $id;
    }
}
