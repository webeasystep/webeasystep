<?php
namespace Modules\Courses\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DtTable;
use CodeIgniter\HTTP\ResponseInterface;
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
            "intro_video_id" => ['label' => lang("Courses.intro_video_id"), 'rules' => "required"],
            "course_title" => ['label' => lang("Courses.course_title"), 'rules' => "required"],
            "course_desc" => ['label' => lang("Courses.course_desc"), 'rules' => "required"],
            "short_desc" => ['label' => lang("Courses.short_desc"), 'rules' => "required|max_length[500]"],
            "slug" => ['label' => lang("Courses.slug"), 'rules' => "required|alpha_dash|is_unique[tb_courses.slug]"],
            "active" => ['label' => lang("Courses.active"), 'rules' => "permit_empty|in_list[0,1,on]"],
        ];
    }

    public function index()
    {
        $data['title'] = lang('Courses.courses_List');

        if ($this->request->isAJAX()) {
            $coursesModel = $this->courses
                ->select('id, course_title, slug, image, sort, is_free, active, created_at')
                ->orderBy('id', 'desc')
                ->builder();


            DtTable::hideColumns(['id']);
            DtTable::searchableColumns(['course_title', 'course_desc', 'is_free']);
            DtTable::orderableColumns(['course_title', 'course_desc', 'sort', 'is_free', 'created_at']);
            DtTable::setColumnImage('image');
            DtTable::setColumnSwitch('is_free'); // Add switch for is_free
            DtTable::setColumnSwitch('active'); // Add switch for is_free
            // Add a link around the course_title column using the slug
            DtTable::setColumnLink('course_title', base_url('courses/course_details/{slug}'));

            DtTable::setShowColumns("course_title,course_desc,sort,is_free,created_at");

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
            // Modify validation rules for edit mode
            $this->rules['slug']['rules'] = "required|alpha_dash|is_unique[tb_courses.slug,id,$id]";
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
            'course_title'       => $this->request->getPost('course_title', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'course_desc'       => $this->request->getPost('course_desc', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'short_desc'        => $this->request->getPost('short_desc', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'slug'              => $this->request->getPost('slug', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'intro_video_id'    => $this->request->getPost('intro_video_id'),
            'collection_id'     => $this->request->getPost('collection_id'),
            'sort'              => $this->request->getPost('sort', FILTER_SANITIZE_NUMBER_INT),
            'is_free'           => $this->request->getPost('is_free') ? '1' : '0',
            'active'            => $this->request->getPost('active') ? '1' : '0',
        ];

        if ($id) {
            $builder->where('id', $id)->update($data);
        } else {
            $builder->insert($data);
            $id = $this->db->insertID();
        }

        return $id;
    }

    /**
     * Get all courses for AJAX requests
     */
    public function getAllCourses()
    {
        $courses = $this->courses->select('id, course_title')
                                ->where('active', 1)
                                ->orderBy('course_title', 'ASC')
                                ->findAll();

        return $this->response->setJSON($courses);
    }

    /**
     * Get quizzes by course ID for AJAX requests
     */
    public function getQuizzesByCourse($courseId = null)
    {
        if (!$courseId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Course ID is required']);
        }

        // Get quizzes for this course
        $quizzes = $this->db->table('tb_quizzes')
                           ->select('id, quiz_title, quiz_desc, active, created_at')
                           ->where('course_id', $courseId)
                           ->where('active', 1)
                           ->orderBy('quiz_title', 'ASC')
                           ->get()
                           ->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'data' => $quizzes
        ]);
    }

    /**
     * Add quiz to course
     */
    public function addQuizToCourse()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $courseId = $this->request->getPost('course_id');
        $quizTitle = $this->request->getPost('quiz_title');
        $quizDesc = $this->request->getPost('quiz_desc');
        $timeLimit = $this->request->getPost('time_limit') ?: 30;
        $maxAttempts = $this->request->getPost('max_attempts') ?: 3;
        $passingScore = $this->request->getPost('passing_score') ?: 70.00;

        if (!$courseId || !$quizTitle) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course ID and Quiz Title are required'
            ]);
        }

        // Insert quiz
        $quizData = [
            'course_id' => $courseId,
            'quiz_title' => $quizTitle,
            'quiz_desc' => $quizDesc,
            'time_limit' => $timeLimit,
            'time_limit_minutes' => $timeLimit,
            'max_attempts' => $maxAttempts,
            'passing_score' => $passingScore,
            'active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $quizId = $this->db->table('tb_quizzes')->insert($quizData);

        if ($quizId) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Quiz added successfully',
                'quiz_id' => $this->db->insertID()
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to add quiz'
            ]);
        }
    }

    /**
     * Show course details with quizzes management
     */
    public function show($id): ResponseInterface
    {
        $course = $this->courses->find($id);
        if (!$course) {
            return redirect()->back()->with('error', 'Course not found');
        }

        // Get course quizzes
        $quizzes = $this->db->table('tb_quizzes')
                           ->select('id, quiz_title, quiz_desc, time_limit, max_attempts, passing_score, active, created_at')
                           ->where('course_id', $id)
                           ->orderBy('created_at', 'DESC')
                           ->get()
                           ->getResultArray();

        // Get course units count
        $unitsCount = $this->db->table('tb_units')
                              ->where('course_id', $id)
                              ->where('active', 1)
                              ->countAllResults();

        $data = [
            'title' => 'Course Details: ' . $course->course_title,
            'course' => $course,
            'quizzes' => $quizzes,
            'units_count' => $unitsCount
        ];

        return view('show', $data);
    }

}
