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
            "course_structure" => ['label' => 'Course Structure', 'rules' => "permit_empty|valid_json"],
            "price" => ['label' => lang("Courses.price"), 'rules' => "required|decimal"],
        ];
    }

    public function index()
    {
        $data['title'] = lang('Courses.courses_List');

        if ($this->request->isAJAX()) {
            $coursesModel = $this->courses
                ->select('id, course_name, slug, image, sort, price, is_free, created_at')
                ->orderBy('id', 'desc')
                ->builder();


            DtTable::hideColumns(['id']);
            DtTable::searchableColumns(['course_name', 'course_desc', 'price', 'is_free']);
            DtTable::orderableColumns(['course_name', 'course_desc', 'sort', 'price', 'is_free', 'created_at']);
            DtTable::setColumnImage('image');
            DtTable::setColumnSwitch('is_free'); // Add switch for is_free
            // Add a link around the course_name column using the slug
            DtTable::setColumnLink('course_name', base_url('courses/course_details/{slug}'));

            DtTable::setShowColumns("course_name,course_desc,sort,price,is_free,created_at");

            $output = DtTable::tableRender($coursesModel, false);

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
                $id = $this->data_arr(); // Insert
                // Handle file upload(s)
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
                $this->data_arr($id);
                $this->show_msg('success', lang("Admin.edit"), lang("Admin.edit_success"));
                return redirect()->to(ADMIN_URL . "courses");
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }

        // Fetch existing course
        $data['course'] = $this->courses->find($id);
        if ($data['course'] && !empty($data['course']->course_structure)) {
            // Decode as array
            $data['course']->course_structure = json_decode(
                $data['course']->course_structure,
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } else {
            // If empty or not set, init with an empty sections array
            $data['course']->course_structure = ['sections' => []];
        }

        // Existing images
        $data['files'] = json_decode($data['course']->image ?? '[]', true);

        return view('form', $data);
    }

    /**
     * Insert/Update data in tb_courses
     */
    private function data_arr($id = null)
    {
        $builder = $this->db->table('tb_courses');

        $data = [
            'course_name'       => $this->request->getPost('course_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'course_desc'       => $this->request->getPost('course_desc', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'course_structure'  => $this->request->getPost('course_structure') ?? null, // JSON structure
            'sort'              => $this->request->getPost('sort', FILTER_SANITIZE_NUMBER_INT),
            'price'             => $this->request->getPost('price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
            'is_free'           => $this->request->getPost('is_free') ? '1' : '0',
        ];

        if ($id) {
            $builder->where('id', $id)->update($data);
        } else {
            $builder->insert($data);
            $id = $this->db->insertID();
        }

        return $id;
    }
}
