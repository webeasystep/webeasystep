<?php

namespace Modules\Courses\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DtTable;
use Modules\Courses\Models\CoursesModel;
use App\Libraries\FireUploader;

class AdminCourses extends BaseController
{
    protected CoursesModel $courses;
    protected array $rules;
    protected FireUploader $fireUploader;

    public function __construct()
    {
        $this->fireUploader = new FireUploader();
        $this->courses = new CoursesModel();
        $this->rules = [
            "course_name" => ['label' => lang("Courses.course_name"), 'rules' => "required"],
            "course_desc" => ['label' => lang("Courses.course_desc"), 'rules' => "required"],
            "price" => ['label' => lang("Courses.price"), 'rules' => "required|decimal"],
            "is_free" => ['label' => lang("Courses.is_free"), 'rules' => "required"],
        ];
    }

    public function index()
    {
        $data['title'] = lang('Courses.courses_List');

        if ($this->request->isAJAX()) {
            $coursesModel = $this->courses
                ->select('id, course_name, course_desc, image, sort, price, is_free, created_at')
                ->orderBy('id', 'desc')
                ->builder();

            DtTable::hideColumns(['id']);
            DtTable::searchableColumns(['course_name', 'course_desc', 'price', 'is_free']);
            DtTable::orderableColumns(['course_name', 'course_desc', 'sort', 'price', 'is_free', 'created_at']);
            DtTable::setColumnImage('image');
            DtTable::setColumnSwitch('is_free'); // Add switch for is_free
            $output = DtTable::tableRender($coursesModel, false);
            DtTable::setShowColumns("course_name,course_desc,sort,price,is_free,created_at");

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
                $id = $this->data_arr();
                $this->fireUploader->upload_photos($this->courses, 'image', $id);
                $this->show_msg('success', lang("Admin.add_operation"), lang("Admin.add_success"));
                return redirect()->to(ADMIN_URL . "courses");
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
                $this->fireUploader->upload_photos($this->courses, 'image', $id);
                $id = $this->data_arr($id);
                $this->show_msg('success', lang("Admin.edit"), lang("Admin.edit_success"));
                return redirect()->to(ADMIN_URL . "courses");
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }

        $data['course'] = $this->courses->find($id); // Fetch the page data by ID
        $data['files'] = json_decode($data['course']->image, true);
        return view('form', $data);
    }

    public function data_arr($id = NULL)
    {
        $builder = $this->db->table('tb_courses');

        $data = [
            'course_name' => $this->request->getPost('course_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'course_desc' => $this->request->getPost('course_desc', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'sort' => $this->request->getPost('sort', FILTER_SANITIZE_NUMBER_INT),
            'price' => $this->request->getPost('price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
            'is_free' => $this->request->getPost('is_free') ? '1' : '0', // Save switch value
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
