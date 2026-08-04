<?php
namespace Modules\CourseRequests\Controllers;

use App\Controllers\BaseController;
use Modules\CourseRequests\Models\CourseRequestsModel;
use CodeIgniter\API\ResponseTrait;

class CourseRequestsController extends BaseController
{
    use ResponseTrait;

    public function submitRequest()
    {
        $rules = [
            'course_name_code' => 'required',
            'college_id' => 'required|is_natural_no_zero',
            'department_id' => 'required|is_natural_no_zero',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $model = new CourseRequestsModel();
        
        $notify_me = $this->request->getPost('notify_me') ? 1 : 0;
        $contact_info = $this->request->getPost('contact_info');

        $data = [
            'course_name_code' => $this->request->getPost('course_name_code'),
            'college_id' => $this->request->getPost('college_id'),
            'department_id' => $this->request->getPost('department_id'),
            'notify_me' => $notify_me,
            'contact_info' => $contact_info,
        ];

        if ($model->insert($data)) {
            return $this->respondCreated(['message' => 'تم استلام طلبك بنجاح. سنقوم بمراجعته قريباً.']);
        }

        return $this->failServerError('حدث خطأ أثناء حفظ الطلب. يرجى المحاولة لاحقاً.');
    }

    public function getDepartmentsByCollege($college_id)
    {
        if (!$college_id) {
            return $this->failValidationErrors('College ID is required');
        }

        $db = \Config\Database::connect();
        $departments = $db->table('tb_departments')
            ->where('college_id', $college_id)
            ->where('active', 1)
            ->get()
            ->getResultArray();

        return $this->respond($departments);
    }
}
