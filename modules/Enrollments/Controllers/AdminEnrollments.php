<?php

namespace Modules\Enrollments\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DtTable;
use Modules\Coupons\Models\CouponsModel;
use Modules\Enrollments\Models\CourseEnrollmentsModel;
use Modules\Courses\Models\CoursesModel;
use Modules\Users\Models\UsersModel;

class AdminEnrollments extends BaseController
{
    protected CourseEnrollmentsModel $courseEnrollments;
    protected CouponsModel $couponsModel;
    protected CoursesModel $coursesModel;
    protected UsersModel $usersModel;
    protected string $table = 'tb_course_enrollments';

    public function __construct()
    {
        $this->courseEnrollments = new CourseEnrollmentsModel();
        $this->couponsModel = new CouponsModel();
        $this->coursesModel = new CoursesModel();
        $this->usersModel = new UsersModel();
    }

    /**
     * Show a listing of all course enrollments with DataTables
     */
    public function index()
    {
        $data['title'] = 'طلبات شراء الدورات';

        if ($this->request->isAJAX()) {
            $builder = $this->courseEnrollments->getDataTable()->builder();

            DtTable::hideColumns([
                'id',
                'mobile',
                'user_id',
                'course_id',
                'bundle_id',
                'batch_id',
                'bundle_price',
                'course_price',
                'course_count',
                'coupon_id',
                'coupon_code',
                'coupon_discount_amount',
                'approved_at',
                'approved_by',
                'expires_at',
                'notes',
                'updated_at',
            ]);

            DtTable::searchableColumns(['users.full_name', 'users.mobile', 'auth_identities.secret', 'tb_courses.course_title', 'tb_bundles.bundle_title', 'tb_course_enrollments.payment_method', 'tb_course_enrollments.status']);
            DtTable::orderableColumns(['full_name', 'course_title', 'bundle_title', 'paid_amount', 'status', 'created_at']);
            DtTable::setShowColumns('full_name,course_title,bundle_title,paid_amount,payment_method,payment_proof,status,created_at');

            // Format full_name with WhatsApp chat link
            DtTable::changeColumn('full_name', function ($value, $row) {
                $name = esc($value ?: 'غير محدد');
                $phone = $row['mobile'] ?? '';

                $waUrl = null;
                if (!empty($phone)) {
                    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                    if (str_starts_with($cleanPhone, '00')) {
                        $cleanPhone = substr($cleanPhone, 2);
                    }
                    if (str_starts_with($cleanPhone, '05') && strlen($cleanPhone) === 10) {
                        $cleanPhone = '966' . substr($cleanPhone, 1);
                    } elseif (str_starts_with($cleanPhone, '01') && strlen($cleanPhone) === 11) {
                        $cleanPhone = '20' . substr($cleanPhone, 1);
                    }
                    $waUrl = 'https://wa.me/' . $cleanPhone;
                }

                $html = '<div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 6px;">';
                $html .= '<span class="font-weight-bold text-dark"><i class="fas fa-user-circle text-primary ml-1"></i> ' . $name . '</span>';

                if ($waUrl) {
                    $html .= '<a href="' . esc($waUrl) . '" target="_blank" class="btn btn-sm shadow-sm" style="background-color: #25D366; color: #fff; border-radius: 15px; padding: 2px 8px; font-size: 11px; text-decoration: none;" title="محادثة واتساب: ' . esc($phone) . '">';
                    $html .= '<i class="fab fa-whatsapp ml-1"></i> واتساب';
                    $html .= '</a>';
                }

                $html .= '</div>';
                return $html;
            });

            // Format course_title (displays course badges or bundle courses)
            DtTable::changeColumn('course_title', function ($value, $row) {
                if (empty($value)) {
                    return '<span class="text-muted">-</span>';
                }

                $titles = array_filter(explode(' || ', $value));
                if (count($titles) > 1 || !empty($row['bundle_id'])) {
                    $html = '<div class="d-flex flex-column" style="gap: 4px; max-width: 320px;">';
                    $html .= '<div><span class="badge badge-info px-2 py-1" style="font-size: 11px;"><i class="fas fa-layer-group ml-1"></i> ' . count($titles) . ' مقررات مشمولة:</span></div>';
                    $html .= '<div class="d-flex flex-wrap" style="gap: 3px;">';
                    foreach ($titles as $t) {
                        $html .= '<span class="badge badge-light border text-dark px-2 py-1" style="font-size: 11px; white-space: normal; text-align: right;">' . esc($t) . '</span>';
                    }
                    $html .= '</div></div>';
                    return $html;
                }

                return '<span class="badge badge-light border px-2 py-1 font-weight-bold text-dark" style="font-size: 13px;">' . esc($value) . '</span>';
            });

            // Format bundle_title with price
            DtTable::changeColumn('bundle_title', function ($value, $row) {
                if (!empty($value)) {
                    $priceHtml = '';
                    if (!empty($row['bundle_price']) && (float) $row['bundle_price'] > 0) {
                        $priceHtml = '<span class="badge badge-warning text-dark font-weight-bold ml-1" style="font-size: 11px;"><i class="fas fa-tag ml-1"></i>' . number_format((float) $row['bundle_price'], 2) . ' ر.س</span>';
                    }
                    return '<div class="d-inline-flex align-items-center flex-wrap" style="gap: 4px;">'
                        . '<span class="badge badge-primary px-2 py-1"><i class="fas fa-layer-group ml-1"></i> ' . esc($value) . '</span>'
                        . $priceHtml
                        . '</div>';
                }
                return '<span class="text-muted">-</span>';
            });

            // Format paid_amount
            DtTable::changeColumn('paid_amount', function ($value, $row) {
                $amount = (float) $value;
                $paymentMethod = $row['payment_method'] ?? '';

                if ($paymentMethod === 'free') {
                    return '<span class="badge badge-success px-2 py-1">مجاني</span>';
                }

                if ($amount > 0) {
                    return '<span class="font-weight-bold text-primary">' . number_format($amount, 2) . ' ر.س</span>';
                }

                // If bundle price exists
                if (!empty($row['bundle_id']) && !empty($row['bundle_price']) && (float)$row['bundle_price'] > 0) {
                    return '<span class="font-weight-bold text-primary">' . number_format((float) $row['bundle_price'], 2) . ' ر.س</span> <small class="text-muted d-block" style="font-size:10px;">(إجمالي الباقة)</small>';
                }

                // If course price exists
                if (!empty($row['course_price']) && (float) $row['course_price'] > 0) {
                    return '<span class="font-weight-bold text-primary">' . number_format((float) $row['course_price'], 2) . ' ر.س</span>';
                }

                return '<span class="badge badge-success px-2 py-1">مجاني</span>';
            });

            // Format payment_method with friendly badges
            DtTable::changeColumn('payment_method', function ($value) {
                return match ($value) {
                    'anb'           => '<span class="badge px-2 py-1" style="background:#005baa; color:#fff;"><i class="fas fa-university ml-1"></i> البنك العربي (ANB)</span>',
                    'stc_bank'      => '<span class="badge px-2 py-1" style="background:#4f008c; color:#fff;"><i class="fas fa-university ml-1"></i> بنك STC</span>',
                    'paypal'        => '<span class="badge px-2 py-1" style="background:#0070ba; color:#fff;"><i class="fab fa-paypal ml-1"></i> PayPal</span>',
                    'bank_transfer' => '<span class="badge badge-info px-2 py-1"><i class="fas fa-university ml-1"></i> تحويل بنكي</span>',
                    'instapay'      => '<span class="badge px-2 py-1" style="background:#6e00ff; color:#fff;"><i class="fas fa-bolt ml-1"></i> انستاباي</span>',
                    'vodafone_cash' => '<span class="badge badge-danger px-2 py-1"><i class="fas fa-mobile-alt ml-1"></i> فودافون كاش</span>',
                    'fawry'         => '<span class="badge badge-warning px-2 py-1"><i class="fas fa-receipt ml-1"></i> فوري</span>',
                    'usdt'          => '<span class="badge badge-success px-2 py-1"><i class="fas fa-coins ml-1"></i> USDT</span>',
                    'free'          => '<span class="badge badge-success px-2 py-1"><i class="fas fa-gift ml-1"></i> مجاني</span>',
                    default         => '<span class="badge badge-secondary px-2 py-1">' . esc($value ?: '-') . '</span>',
                };
            });

            // Format payment_proof
            DtTable::changeColumn('payment_proof', function ($value) {
                if ($value) {
                    $url = base_url($value);
                    return '<a href="' . esc($url) . '" target="_blank" class="btn btn-sm btn-info shadow-sm"><i class="fas fa-image ml-1"></i> عرض الإثبات</a>';
                }
                return '<span class="badge badge-secondary px-2 py-1">لا يوجد إثبات</span>';
            });

            // Format status with colored badges
            DtTable::changeColumn('status', function ($value) {
                return match ($value) {
                    'pending'  => '<span class="badge badge-warning px-2 py-1 text-dark" style="font-size:12px;"><i class="fas fa-clock ml-1"></i> قيد المراجعة</span>',
                    'approved' => '<span class="badge badge-success px-2 py-1" style="font-size:12px;"><i class="fas fa-check-circle ml-1"></i> مفعل</span>',
                    'rejected' => '<span class="badge badge-danger px-2 py-1" style="font-size:12px;"><i class="fas fa-times-circle ml-1"></i> مرفوض</span>',
                    'refunded' => '<span class="badge badge-dark px-2 py-1" style="font-size:12px;"><i class="fas fa-undo ml-1"></i> مسترجع</span>',
                    default    => '<span class="badge badge-secondary px-2 py-1">' . esc($value ?: '-') . '</span>',
                };
            });

            // Format created_at
            DtTable::changeColumn('created_at', function ($value) {
                if (empty($value)) return '-';
                return '<span class="small text-muted" dir="ltr">' . esc(date('Y-m-d H:i', strtotime($value))) . '</span>';
            });

            // Set Action URLs
            DtTable::setAction('show', 'eye', ADMIN_URL . 'enrollments/courses/show/');
            DtTable::setAction('edit', 'edit', ADMIN_URL . 'enrollments/edit/');

            $output = DtTable::tableRender($builder, false);
            return $this->response->setJSON($output);
        }

        return view('Modules\Enrollments\Views\Admin\index', $data);
    }

    public function add()
    {
        $data['title'] = lang("Admin.add_data");
        $data['users'] = $this->usersModel->select('id, full_name')->findAll();
        $data['users'] = array_column($data['users'], 'full_name', 'id');
        $data['courses'] = $this->coursesModel->select('id, course_title')->findAll();
        $data['courses'] = array_column($data['courses'], 'course_title', 'id');

        if ($this->request->is('post')) {
            $rules = [
                'user_id' => 'required',
                'course_id' => 'required',
                'status' => 'required',
            ];
            if ($this->validate($rules)) {
                $this->data_arr();
                return redirect()->to(ADMIN_URL . "enrollments")->with('success', lang("Admin.add_success"));
            } else {
                return redirect()->back()->with('error', validation_errors());
            }
        }
        return view("Modules\Enrollments\Views\Admin\form", $data);
    }

    public function edit($id)
    {
        $data['title'] = lang("Admin.edit_data");
        $data['users'] = $this->usersModel->select('id, full_name')->findAll();
        $data['users'] = array_column($data['users'], 'full_name', 'id');
        $data['courses'] = $this->coursesModel->select('id, course_title')->findAll();
        $data['courses'] = array_column($data['courses'], 'course_title', 'id');

        $enrollment = $this->courseEnrollments->find($id);
        if (!$enrollment) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Check if this enrollment is part of a bundle or multi-course batch
        $batchEnrollments = [];
        $bundle = null;
        $totalPaidAmount = (float) $enrollment->paid_amount;

        if (!empty($enrollment->batch_id)) {
            $batchEnrollments = $this->courseEnrollments
                ->select('tb_course_enrollments.*, tb_courses.course_title')
                ->join('tb_courses', 'tb_courses.id = tb_course_enrollments.course_id', 'left')
                ->where('tb_course_enrollments.batch_id', $enrollment->batch_id)
                ->findAll();
        } elseif (!empty($enrollment->bundle_id)) {
            $batchEnrollments = $this->courseEnrollments
                ->select('tb_course_enrollments.*, tb_courses.course_title')
                ->join('tb_courses', 'tb_courses.id = tb_course_enrollments.course_id', 'left')
                ->where('tb_course_enrollments.user_id', $enrollment->user_id)
                ->where('tb_course_enrollments.bundle_id', $enrollment->bundle_id)
                ->where('tb_course_enrollments.created_at', $enrollment->created_at)
                ->findAll();
        }

        if (!empty($enrollment->bundle_id)) {
            $bundle = (new \Modules\Bundles\Models\BundlesModel())->find($enrollment->bundle_id);
        }

        if (count($batchEnrollments) > 1) {
            $sumPaid = array_sum(array_map(fn($e) => (float)$e->paid_amount, $batchEnrollments));
            if ($sumPaid > 0) {
                $totalPaidAmount = $sumPaid;
            } elseif ($bundle && (float)$bundle->bundle_price > 0) {
                $totalPaidAmount = (float)$bundle->bundle_price;
            }
        } elseif ($totalPaidAmount <= 0 && $bundle && (float)$bundle->bundle_price > 0) {
            $totalPaidAmount = (float)$bundle->bundle_price;
        }

        if ($this->request->is('post')) {
            $rules = [
                'user_id' => 'required',
                'status'  => 'required',
            ];
            if (empty($enrollment->bundle_id)) {
                $rules['course_id'] = 'required';
            }
            if ($this->validate($rules)) {
                $this->data_arr($id, $enrollment, $batchEnrollments);
                return redirect()->to(ADMIN_URL . "enrollments")->with('success', lang("Admin.edit_success"));
            } else {
                return redirect()->back()->with('error', validation_errors());
            }
        }

        $data['enrollment'] = $enrollment;
        $data['bundle'] = $bundle;
        $data['batchEnrollments'] = $batchEnrollments;
        $data['totalPaidAmount'] = $totalPaidAmount;

        // Setup files for fireuploader if payment proof exists
        $files = [];
        if (!empty($enrollment->payment_proof)) {
            $files[] = [
                'full_path' => $enrollment->payment_proof,
                'name' => basename($enrollment->payment_proof)
            ];
        }
        $data['files'] = $files;
        $data['refund_files'] = [];
        if (!empty($enrollment->refund_proof)) {
            $data['refund_files'][] = [
                'full_path' => $enrollment->refund_proof,
                'name' => basename($enrollment->refund_proof)
            ];
        }

        return view('Modules\Enrollments\Views\Admin\form', $data);
    }

    private function data_arr($id = null, $currentEnrollment = null, array $batchEnrollments = [])
    {
        $status = $this->request->getPost('status');
        $paymentMethod = $this->request->getPost('payment_method');
        $inputPaidAmount = (float) ($this->request->getPost('paid_amount') ?? 0);
        $notes = $this->request->getPost('notes', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $couponId = $this->request->getPost('coupon_id') ?: null;
        $couponCode = $this->request->getPost('coupon_code') ?: null;
        $couponDiscount = (float) ($this->request->getPost('coupon_discount_amount') ?: 0);
        $userId = $this->request->getPost('user_id');
        $courseId = $this->request->getPost('course_id');

        $paymentProofPath = $this->handleEnrollmentProofUpload('payment_proof');
        $refundProofPath = $this->handleEnrollmentProofUpload('refund_proof');

        if ($id) {
            $current = $currentEnrollment ?: $this->courseEnrollments->find($id);
            $isNewlyApproved = ($status === 'approved' && $current?->status !== 'approved');

            $enrollmentsToUpdate = !empty($batchEnrollments) ? $batchEnrollments : [$current];
            $count = count($enrollmentsToUpdate);
            $perCourseAmount = $count > 0 ? round($inputPaidAmount / $count, 2) : $inputPaidAmount;

            foreach ($enrollmentsToUpdate as $rec) {
                $updateData = [
                    'status'         => $status,
                    'payment_method' => $paymentMethod,
                    'paid_amount'    => $perCourseAmount,
                    'notes'          => $notes,
                    'updated_at'     => date('Y-m-d H:i:s'),
                ];

                if ($userId) {
                    $updateData['user_id'] = $userId;
                }
                if ($courseId && empty($rec->bundle_id)) {
                    $updateData['course_id'] = $courseId;
                }
                if ($couponId) {
                    $updateData['coupon_id'] = $couponId;
                    $updateData['coupon_code'] = $couponCode;
                    $updateData['coupon_discount_amount'] = $couponDiscount;
                }
                if ($paymentProofPath !== null) {
                    $updateData['payment_proof'] = $paymentProofPath;
                }
                if ($refundProofPath !== null) {
                    $updateData['refund_proof'] = $refundProofPath;
                }

                if ($status === 'approved') {
                    if (empty($rec->approved_at)) {
                        $updateData['approved_at'] = date('Y-m-d H:i:s');
                    }
                    if (empty($rec->approved_by)) {
                        $updateData['approved_by'] = auth()->user()->id;
                    }
                } elseif ($status === 'refunded') {
                    if (empty($rec->refunded_at)) {
                        $updateData['refunded_at'] = date('Y-m-d H:i:s');
                    }
                    if ($refundProofPath !== null) {
                        $updateData['refund_proof'] = $refundProofPath;
                    }
                }

                $this->courseEnrollments->update($rec->id, $updateData);

                if ($isNewlyApproved) {
                    $this->sendApprovalEmail($rec->id);
                }
            }

            if ($isNewlyApproved && $couponId) {
                $this->incrementCouponUsageIfNeeded((int) $id);
            }

            return $id;
        } else {
            // New Enrollment creation
            $insertData = [
                'user_id'                => $userId,
                'course_id'              => $courseId,
                'paid_amount'            => $inputPaidAmount,
                'coupon_id'              => $couponId,
                'coupon_code'            => $couponCode,
                'coupon_discount_amount' => $couponDiscount,
                'payment_method'         => $paymentMethod,
                'status'                 => $status,
                'notes'                  => $notes,
                'created_at'             => date('Y-m-d H:i:s'),
                'updated_at'             => date('Y-m-d H:i:s'),
            ];

            if ($paymentProofPath !== null) {
                $insertData['payment_proof'] = $paymentProofPath;
            }
            if ($refundProofPath !== null) {
                $insertData['refund_proof'] = $refundProofPath;
            }

            if ($status === 'approved') {
                $insertData['approved_at'] = date('Y-m-d H:i:s');
                $insertData['approved_by'] = auth()->user()->id;
            }

            $newId = $this->courseEnrollments->insert($insertData);

            if ($status === 'approved' && $newId) {
                if ($couponId) {
                    $this->incrementCouponUsageIfNeeded((int) $newId);
                }
                $this->sendApprovalEmail($newId);
            }

            return $newId;
        }
    }

    public function show($id)
    {
        return $this->showCourseEnrollment($id);
    }

    /**
     * View course enrollment details
     */
    public function showCourseEnrollment($id)
    {
        $enrollment = $this->courseEnrollments
            ->select('tb_course_enrollments.*, tb_courses.course_title, tb_courses.course_price, tb_bundles.bundle_title, tb_bundles.bundle_price, users.full_name, users.email, COALESCE(users.mobile, auth_identities.secret) as mobile')
            ->join('tb_courses', 'tb_courses.id = tb_course_enrollments.course_id', 'left')
            ->join('tb_bundles', 'tb_bundles.id = tb_course_enrollments.bundle_id', 'left')
            ->join('users', 'users.id = tb_course_enrollments.user_id', 'left')
            ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type IN ("mobile_password", "mobile_number")', 'left')
            ->find($id);

        if (!$enrollment) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('طلب الشراء غير موجود');
        }

        // If part of a batch or bundle, fetch all courses in this batch
        $batchEnrollments = [];
        if (!empty($enrollment->batch_id)) {
            $batchEnrollments = $this->courseEnrollments
                ->select('tb_course_enrollments.*, tb_courses.course_title, tb_courses.course_price')
                ->join('tb_courses', 'tb_courses.id = tb_course_enrollments.course_id', 'left')
                ->where('tb_course_enrollments.batch_id', $enrollment->batch_id)
                ->findAll();
        } elseif (!empty($enrollment->bundle_id)) {
            $batchEnrollments = $this->courseEnrollments
                ->select('tb_course_enrollments.*, tb_courses.course_title, tb_courses.course_price')
                ->join('tb_courses', 'tb_courses.id = tb_course_enrollments.course_id', 'left')
                ->where('tb_course_enrollments.user_id', $enrollment->user_id)
                ->where('tb_course_enrollments.bundle_id', $enrollment->bundle_id)
                ->where('tb_course_enrollments.created_at', $enrollment->created_at)
                ->findAll();
        }

        $data = [
            'title'            => 'تفاصيل طلب شراء ' . (!empty($enrollment->bundle_id) ? 'الباقة' : 'الدورة'),
            'enrollment'       => $enrollment,
            'batchEnrollments' => $batchEnrollments,
        ];

        return view('Modules\Enrollments\Views\Admin\course_enrollment_details', $data);
    }

    /**
     * Approve course enrollment request (operates on entire bundle/batch)
     */
    public function approveCourseEnrollment($id)
    {
        $adminId = auth()->user()->id;
        $notes = $this->request->getPost('admin_notes');
        $expiresAt = $this->request->getPost('expires_at');
        $enrollmentBefore = $this->courseEnrollments->find($id);

        if (!$enrollmentBefore) {
            return redirect()->back()->with('error', 'طلب الشراء غير موجود');
        }

        if ($enrollmentBefore->status === 'refunded') {
            return redirect()->back()->with('error', 'لا يمكن إعادة تفعيل اشتراك تم استرجاعه.');
        }

        $enrollmentsToApprove = [$enrollmentBefore];
        if (!empty($enrollmentBefore->batch_id)) {
            $enrollmentsToApprove = $this->courseEnrollments->where('batch_id', $enrollmentBefore->batch_id)->findAll();
        } elseif (!empty($enrollmentBefore->bundle_id)) {
            $enrollmentsToApprove = $this->courseEnrollments
                ->where('user_id', $enrollmentBefore->user_id)
                ->where('bundle_id', $enrollmentBefore->bundle_id)
                ->where('created_at', $enrollmentBefore->created_at)
                ->findAll();
        }

        $allSuccess = true;
        $couponIncremented = false;

        foreach ($enrollmentsToApprove as $enrollment) {
            $isNewlyApproved = $enrollment->status !== 'approved';

            if ($this->courseEnrollments->approveEnrollment($enrollment->id, $adminId, $expiresAt)) {
                if ($isNewlyApproved && !$couponIncremented) {
                    $this->incrementCouponUsageIfNeeded($enrollment->id);
                    $couponIncremented = true;
                }
                $this->sendApprovalEmail($enrollment->id);
            } else {
                $allSuccess = false;
            }
        }

        if ($allSuccess) {
            $msg = count($enrollmentsToApprove) > 1
                ? 'تم الموافقة على طلب الباقة وتفعيل جميع المقررات المشمولة بنجاح (' . count($enrollmentsToApprove) . ' مقررات)'
                : 'تم الموافقة على الطلب وتفعيل الدورة بنجاح';
            return redirect()->back()->with('success', $msg);
        } else {
            return redirect()->back()->with('error', 'تم تفعيل بعض الطلبات، ولكن حدث فشل في تفعيل البعض الآخر.');
        }
    }

    /**
     * Send course approval email
     */
    private function sendApprovalEmail($enrollmentId)
    {
        $enrollment = $this->courseEnrollments
            ->select('tb_course_enrollments.*, tb_courses.course_title, tb_courses.slug, tb_courses.telegram_link, users.full_name, COALESCE(users.email, auth_identities.secret) as email')
            ->join('tb_courses', 'tb_courses.id = tb_course_enrollments.course_id')
            ->join('users', 'users.id = tb_course_enrollments.user_id')
            ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
            ->find($enrollmentId);

        if (!$enrollment || empty($enrollment->email)) {
            log_message('error', 'Could not send approval email for enrollment ID: ' . $enrollmentId . ' - User or email not found.');
            return false;
        }

        $email = \Config\Services::email();
        $email->setTo($enrollment->email);
        $email->setSubject('تم تفعيل اشتراكك في دورة: ' . $enrollment->course_title);

        $courseUrl = base_url('courses/course_view/' . $enrollment->slug);

        $message = MainView('Modules\Enrollments\Views\Site\emails\course_approved', [
            'full_name'     => $enrollment->full_name,
            'course_title'  => $enrollment->course_title,
            'course_url'    => $courseUrl,
            'telegram_link' => $enrollment->telegram_link ?? null,
        ]);

        $email->setMessage($message);
        $email->setMailType('html');

        $success = $email->send();
        if (!$success) {
            log_message('error', 'Failed to send approval email: ' . $email->printDebugger(['headers']));
        }

        return $success;
    }

    /**
     * Increment coupon usage only after the enrollment becomes approved.
     */
    private function incrementCouponUsageIfNeeded(int $enrollmentId): void
    {
        $enrollment = $this->courseEnrollments->find($enrollmentId);

        if (!$enrollment || empty($enrollment->coupon_id)) {
            return;
        }

        $this->couponsModel->incrementUsage((int) $enrollment->coupon_id);
    }

    /**
     * Reject course enrollment request (operates on entire bundle/batch)
     */
    public function rejectCourseEnrollment($id)
    {
        $reason = $this->request->getPost('rejection_reason');
        $enrollment = $this->courseEnrollments->find($id);

        if (!$enrollment) {
            return redirect()->back()->with('error', 'طلب الشراء غير موجود');
        }

        if ($enrollment->status === 'refunded') {
            return redirect()->back()->with('error', 'لا يمكن رفض اشتراك تم استرجاعه.');
        }

        $enrollmentsToReject = [$enrollment];
        if (!empty($enrollment->batch_id)) {
            $enrollmentsToReject = $this->courseEnrollments->where('batch_id', $enrollment->batch_id)->findAll();
        } elseif (!empty($enrollment->bundle_id)) {
            $enrollmentsToReject = $this->courseEnrollments
                ->where('user_id', $enrollment->user_id)
                ->where('bundle_id', $enrollment->bundle_id)
                ->where('created_at', $enrollment->created_at)
                ->findAll();
        }

        $allSuccess = true;
        foreach ($enrollmentsToReject as $item) {
            if (!$this->courseEnrollments->rejectEnrollment($item->id, $reason)) {
                $allSuccess = false;
            }
        }

        if ($allSuccess) {
            $msg = count($enrollmentsToReject) > 1
                ? 'تم رفض طلب الباقة وإيقاف جميع مقرراتها بنجاح (' . count($enrollmentsToReject) . ' مقررات)'
                : 'تم رفض الطلب بنجاح';
            return redirect()->back()->with('success', $msg);
        } else {
            return redirect()->back()->with('error', 'فشل في رفض بعض أو كل المقررات في الطلب');
        }
    }

    /**
     * Refund an approved enrollment and revoke course access (operates on entire bundle/batch).
     */
    public function refundCourseEnrollment($id)
    {
        $enrollment = $this->courseEnrollments->find($id);

        if (!$enrollment) {
            return redirect()->back()->with('error', 'طلب الشراء غير موجود');
        }

        if ($enrollment->status === 'refunded') {
            return redirect()->back()->with('error', 'تم تنفيذ الاسترجاع مسبقاً لهذا الاشتراك.');
        }

        if ($enrollment->status !== 'approved') {
            return redirect()->back()->with('error', 'يمكن تنفيذ الاسترجاع فقط للاشتراكات المفعلة.');
        }

        $refundProofPath = $this->handleEnrollmentProofUpload('refund_proof');
        if ($refundProofPath === null && empty($enrollment->refund_proof)) {
            return redirect()->back()->with('error', 'يرجى رفع صورة إثبات الاسترجاع أولاً.');
        }

        $notes = $this->request->getPost('refund_notes', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: $enrollment->notes;
        $proofToSave = $refundProofPath ?? $enrollment->refund_proof;

        $enrollmentsToRefund = [$enrollment];
        if (!empty($enrollment->batch_id)) {
            $enrollmentsToRefund = $this->courseEnrollments->where('batch_id', $enrollment->batch_id)->findAll();
        } elseif (!empty($enrollment->bundle_id)) {
            $enrollmentsToRefund = $this->courseEnrollments
                ->where('user_id', $enrollment->user_id)
                ->where('bundle_id', $enrollment->bundle_id)
                ->where('created_at', $enrollment->created_at)
                ->findAll();
        }

        $allSuccess = true;
        foreach ($enrollmentsToRefund as $item) {
            if (!$this->courseEnrollments->refundEnrollment((int) $item->id, $proofToSave, $notes)) {
                $allSuccess = false;
            }
        }

        if ($allSuccess) {
            $msg = count($enrollmentsToRefund) > 1
                ? 'تم تنفيذ الاسترجاع للباقة بالكامل وإيقاف وصول العميل إلى جميع مقرراتها (' . count($enrollmentsToRefund) . ' مقررات)'
                : 'تم تنفيذ الاسترجاع وإيقاف وصول العميل إلى الدورة.';
            return redirect()->back()->with('success', $msg);
        }

        return redirect()->back()->with('error', 'فشل في تنفيذ الاسترجاع.');
    }

    /**
     * Bulk / single delete supporting bundle/batch deletion
     */
    public function delete(): \CodeIgniter\HTTP\ResponseInterface
    {
        if ($this->request->isAJAX()) {
            $ids = $this->request->getPost('rows');
            $idsArray = array_filter(explode(',', (string)$ids));

            if (empty($idsArray)) {
                return $this->response->setJSON([
                    'validation' => true,
                    'success'    => false,
                    'message'    => 'لم يتم توفير أي معرف للحذف'
                ]);
            }

            $records = $this->courseEnrollments->whereIn('id', $idsArray)->findAll();
            $allIdsToDelete = $idsArray;
            foreach ($records as $rec) {
                if (!empty($rec->batch_id)) {
                    $batchRecords = $this->courseEnrollments->where('batch_id', $rec->batch_id)->findAll();
                    foreach ($batchRecords as $bRec) {
                        $allIdsToDelete[] = $bRec->id;
                    }
                }
            }
            $allIdsToDelete = array_values(array_unique($allIdsToDelete));

            $this->courseEnrollments->whereIn('id', $allIdsToDelete)->delete();

            return $this->response->setJSON(['validation' => true, 'success' => true, 'message' => 'تم الحذف بنجاح']);
        }
        return $this->response->setJSON(['validation' => true, 'success' => false, 'message' => 'لقد حدث خطأ أثناء الحذف']);
    }

    /**
     * Get course enrollment statistics
     */
    public function getCourseEnrollmentStats()
    {
        $stats = $this->courseEnrollments->getEnrollmentStats();
        return $this->response->setJSON($stats);
    }

    /**
     * Get pending course enrollments count
     */
    public function getPendingCourseEnrollmentsCount()
    {
        $count = $this->courseEnrollments->where('status', 'pending')->countAllResults();
        return $this->response->setJSON(['count' => $count]);
    }

    /**
     * AJAX endpoint: Validate coupon code from admin enrollment form.
     */
    public function validateCouponAdmin()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
        }

        $couponCode = trim($this->request->getPost('coupon_code') ?? '');
        $courseId   = (int) $this->request->getPost('course_id');

        if (empty($couponCode)) {
            return $this->response->setJSON(['valid' => false, 'message' => 'يرجى إدخال كود الكوبون.']);
        }

        if (empty($courseId)) {
            return $this->response->setJSON(['valid' => false, 'message' => 'يرجى اختيار الدورة أولاً.']);
        }

        $coupon = $this->couponsModel->getValidCouponByCode($couponCode, $courseId);

        if (!$coupon) {
            return $this->response->setJSON(['valid' => false, 'message' => 'كود الكوبون غير صالح أو منتهي الصلاحية.']);
        }

        // Get course price to calculate discount
        $course = $this->coursesModel->find($courseId);
        if (!$course) {
            return $this->response->setJSON(['valid' => false, 'message' => 'الدورة غير موجودة.']);
        }

        $coursePrice     = (float) $course->course_price;
        $discountAmount  = $this->couponsModel->calculateDiscountAmount($coursePrice, $coupon);
        $finalPrice      = max(0, $coursePrice - $discountAmount);

        return $this->response->setJSON([
            'valid'            => true,
            'coupon_id'        => (int) $coupon->id,
            'coupon_code'      => $coupon->coupon_code,
            'discount_type'    => $coupon->discount_type,
            'discount_amount'  => $discountAmount,
            'course_price'     => $coursePrice,
            'final_price'      => $finalPrice,
            'message'          => 'تم تطبيق الكوبون بنجاح! الخصم: ' . number_format($discountAmount, 2) . ' - السعر النهائي: ' . number_format($finalPrice, 2),
        ]);
    }

    private function handleEnrollmentProofUpload(string $fieldName): ?string
    {
        $proofFile = $this->request->getFile($fieldName);

        if (!$proofFile || !$proofFile->isValid() || $proofFile->hasMoved()) {
            return null;
        }

        $uploadPath = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'enrollments';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }

        $randomName = $proofFile->getRandomName();
        $proofFile->move($uploadPath, $randomName);

        return 'uploads/enrollments/' . $randomName;
    }
}
