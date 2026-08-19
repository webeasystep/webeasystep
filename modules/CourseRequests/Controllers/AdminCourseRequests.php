<?php

namespace Modules\CourseRequests\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DtTable;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\CourseRequests\Models\CourseRequestsModel;

class AdminCourseRequests extends BaseController
{
    protected CourseRequestsModel $courseRequests;

    public function __construct()
    {
        $this->courseRequests = new CourseRequestsModel();
    }

    public function index()
    {
        $data['title'] = lang('CourseRequests.course_requests');
        $data['stats'] = $this->courseRequests->getStatistics();

        if ($this->request->isAJAX()) {
            $builder = $this->courseRequests->getDataTable()->builder();

            DtTable::hideColumns(['id']);
            DtTable::searchableColumns([
                'tb_course_requests.course_name_code',
                'tb_course_requests.contact_info',
                'tb_course_requests.status',
                'tb_colleges.college_name_ar',
                'tb_departments.department_name_ar'
            ]);
            DtTable::orderableColumns([
                'course_name_code',
                'college_name',
                'department_name',
                'contact_info',
                'notify_me',
                'status',
                'created_at'
            ]);
            DtTable::hideActions(['edit']);
            DtTable::setShowColumns('course_name_code,college_name,department_name,contact_info,notify_me,status,created_at');

            // Format college_name
            DtTable::changeColumn('college_name', function ($value) {
                return !empty($value) ? esc($value) : '<span class="text-muted">غير محدد</span>';
            });

            // Format department_name
            DtTable::changeColumn('department_name', function ($value) {
                return !empty($value) ? esc($value) : '<span class="text-muted">غير محدد</span>';
            });

            // Format notify_me
            DtTable::changeColumn('notify_me', function ($value) {
                if ($value == 1) {
                    return '<span class="badge badge-success px-2 py-1"><i class="fas fa-bell ml-1"></i> ' . (lang('Admin.yes') ?: 'نعم') . '</span>';
                }
                return '<span class="badge badge-secondary px-2 py-1"><i class="fas fa-bell-slash ml-1"></i> ' . (lang('Admin.no') ?: 'لا') . '</span>';
            });

            // Format contact_info
            DtTable::changeColumn('contact_info', function ($value) {
                if (empty($value)) {
                    return '<span class="text-muted">-</span>';
                }
                if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    return '<a href="mailto:' . esc($value) . '" class="text-primary font-weight-bold"><i class="fas fa-envelope ml-1"></i>' . esc($value) . '</a>';
                }
                return '<span><i class="fas fa-phone ml-1"></i>' . esc($value) . '</span>';
            });

            // Format status with quick update dropdown
            DtTable::changeColumn('status', function ($value, $row) {
                $status = $value ?: 'pending';
                $badgeClasses = [
                    'pending'     => 'badge-warning',
                    'in_progress' => 'badge-info',
                    'completed'   => 'badge-success',
                    'rejected'    => 'badge-danger',
                ];
                $statusLabels = [
                    'pending'     => lang('CourseRequests.pending') ?: 'قيد الانتظار',
                    'in_progress' => lang('CourseRequests.in_progress') ?: 'قيد المراجعة',
                    'completed'   => lang('CourseRequests.completed') ?: 'تم التوفير',
                    'rejected'    => lang('CourseRequests.rejected') ?: 'مرفوض',
                ];

                $currentClass = $badgeClasses[$status] ?? 'badge-secondary';
                $currentLabel = $statusLabels[$status] ?? esc($status);

                $html = '<div class="dropdown d-inline-block">';
                $html .= '<button class="btn btn-sm ' . $currentClass . ' dropdown-toggle px-2 py-1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $html .= $currentLabel;
                $html .= '</button>';
                $html .= '<div class="dropdown-menu dropdown-menu-right shadow">';
                $html .= '<h6 class="dropdown-header">' . (lang('CourseRequests.change_status') ?: 'تغيير الحالة') . '</h6>';
                $html .= '<a class="dropdown-item change-status-btn text-warning" href="javascript:void(0)" data-id="' . $row['id'] . '" data-status="pending"><i class="fas fa-clock ml-1"></i> ' . $statusLabels['pending'] . '</a>';
                $html .= '<a class="dropdown-item change-status-btn text-info" href="javascript:void(0)" data-id="' . $row['id'] . '" data-status="in_progress"><i class="fas fa-spinner ml-1"></i> ' . $statusLabels['in_progress'] . '</a>';
                $html .= '<a class="dropdown-item change-status-btn text-success" href="javascript:void(0)" data-id="' . $row['id'] . '" data-status="completed"><i class="fas fa-check-circle ml-1"></i> ' . $statusLabels['completed'] . '</a>';
                $html .= '<a class="dropdown-item change-status-btn text-danger" href="javascript:void(0)" data-id="' . $row['id'] . '" data-status="rejected"><i class="fas fa-times-circle ml-1"></i> ' . $statusLabels['rejected'] . '</a>';
                $html .= '</div></div>';

                return $html;
            });

            $output = DtTable::tableRender($builder, false);
            return $this->response->setJSON($output);
        }

        return view('Modules\CourseRequests\Views\Admin\index', $data);
    }

    public function show($id): ResponseInterface
    {
        $request = $this->courseRequests->getRequestDetails($id);

        if (!$request) {
            return $this->response->setJSON([
                'data' => [
                    (lang('Admin.error') ?: 'خطأ') => (lang('CourseRequests.not_found') ?: 'الطلب غير موجود')
                ]
            ]);
        }

        $statusLabels = [
            'pending'     => lang('CourseRequests.pending') ?: 'قيد الانتظار',
            'in_progress' => lang('CourseRequests.in_progress') ?: 'قيد المراجعة',
            'completed'   => lang('CourseRequests.completed') ?: 'تم التوفير',
            'rejected'    => lang('CourseRequests.rejected') ?: 'مرفوض',
        ];

        $currentStatus = $request['status'] ?: 'pending';
        $statusText = $statusLabels[$currentStatus] ?? $currentStatus;

        $data = [
            (lang('CourseRequests.course_name_code') ?: 'اسم أو كود المقرر') => $request['course_name_code'] ?? '-',
            (lang('CourseRequests.college_name') ?: 'الكلية')               => $request['college_name'] ?? 'غير محدد',
            (lang('CourseRequests.department_name') ?: 'القسم')             => $request['department_name'] ?? 'غير محدد',
            (lang('CourseRequests.contact_info') ?: 'بيانات التواصل')       => $request['contact_info'] ?? '-',
            (lang('CourseRequests.notify_me') ?: 'إشعار عند التوفر')       => !empty($request['notify_me']) ? 'نعم' : 'لا',
            (lang('CourseRequests.status') ?: 'الحالة')                     => $statusText,
            (lang('CourseRequests.created_at') ?: 'تاريخ الطلب')           => $request['created_at'] ?? '-',
        ];

        return $this->response->setJSON(['data' => $data]);
    }

    public function updateStatus(): ResponseInterface
    {
        if ($this->request->isAJAX()) {
            $id = (int)$this->request->getPost('id');
            $status = $this->request->getPost('status');

            $allowedStatuses = ['pending', 'in_progress', 'completed', 'rejected'];
            if (!in_array($status, $allowedStatuses)) {
                return $this->response->setJSON([
                    'status'  => 400,
                    'message' => 'الحالة المحددة غير صحيحة'
                ]);
            }

            $updated = $this->courseRequests->update($id, [
                'status'     => $status,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            if ($updated) {
                return $this->response->setJSON([
                    'status'  => 200,
                    'html'    => lang('CourseRequests.status_updated_successfully') ?: 'تم تحديث حالة الطلب بنجاح',
                    'message' => lang('CourseRequests.status_updated_successfully') ?: 'تم تحديث حالة الطلب بنجاح'
                ]);
            }
        }

        return $this->response->setJSON([
            'status'  => 500,
            'message' => 'حدث خطأ أثناء تحديث الحالة'
        ]);
    }

    public function delete(): ResponseInterface
    {
        if ($this->request->isAJAX()) {
            $ids = $this->request->getPost('rows');
            if (!empty($ids)) {
                $idsArray = is_array($ids) ? $ids : explode(',', $ids);
                $idsArray = array_filter(array_map('intval', $idsArray));

                if (!empty($idsArray)) {
                    $this->courseRequests->whereIn('id', $idsArray)->delete();
                    return $this->response->setJSON([
                        'validation' => true,
                        'success'    => true,
                        'message'    => lang('CourseRequests.delete_success') ?: 'تم حذف الطلب بنجاح'
                    ]);
                }
            }
        }

        return $this->response->setJSON([
            'validation' => true,
            'success'    => false,
            'message'    => lang('Admin.error') ?: 'لقد حدث خطأ أثناء الحذف'
        ]);
    }
}
