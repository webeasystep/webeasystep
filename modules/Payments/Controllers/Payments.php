<?php

namespace Modules\Payments\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;
use Modules\Payments\Models\PaymentsModel;
use Modules\Courses\Models\CoursesModel;
use App\Libraries\FireUploader;
use Modules\Users\Models\UsersModel;

class Payments extends BaseController
{
    protected PaymentsModel    $paymentsModel;
    protected CoursesModel     $coursesModel;
    protected PaymentsModel $enrollmentsModel;
    protected UsersModel       $usersModel;
    protected FireUploader     $fireUploader;

    /**
     * قواعد التحقق (Validation Rules)
     * تُستخدم عندما يكون المستخدم غير مسجل ويحتاج لإدخال بياناته في دورة مدفوعة مثلاً.
     */
    protected array $rules = [
        'name' => [
            'label' => 'الاسم الكامل',
            'rules' => 'required|min_length[3]|max_length[100]',
        ],
        'email' => [
            'label' => 'البريد الإلكتروني',
            'rules' => 'required|valid_email|max_length[100]',
        ],
        'country' => [
            'label' => 'الدولة',
            'rules' => 'required|min_length[2]|max_length[100]',
        ],
        'phone' => [
            'label' => 'رقم الهاتف',
            'rules' => 'required|min_length[5]|max_length[20]',
        ],
        'password' => [
            'label' => 'كلمة المرور',
            'rules' => 'required|min_length[6]|max_length[50]',
        ],
        'confirmPassword' => [
            'label' => 'تأكيد كلمة المرور',
            'rules' => 'required|matches[password]',
        ],
        // في حال أردت التحقق من ملف إثبات الدفع proofImage
        // 'proofImage' => [
        //     'label' => 'إثبات الدفع',
        //     'rules' => 'uploaded[proofImage]|is_image[proofImage]|max_size[proofImage,4096]',
        // ],
    ];

    public function __construct()
    {
        $this->paymentsModel    = new PaymentsModel();
        $this->coursesModel     = new CoursesModel();
        $this->enrollmentsModel = new PaymentsModel();
        $this->usersModel       = new UsersModel();
        $this->fireUploader     = new FireUploader();
    }

    /**
     * مثال على index() لعرض بعض بيانات الدفعات المكتملة.
     */
    public function index(): string
    {
        $data = [
            'title'    => lang('Payments.Payments'),
            'payments' => $this->paymentsModel
                ->where('payment_status', 'completed')
                ->paginate(10),
            'pager'    => $this->paymentsModel->pager,
        ];

        // مثال لعرضها في صفحة ما
        return view('site/completed_payments', $data);
    }

    /**
     * شاشة الشراء (أو الدفع) التي تتعامل مع السيناريوهات:
     * 1) دورة مجانية (is_free = 1)
     * 2) دورة قائمة انتظار (waiting_list = 1)
     * 3) دورة مدفوعة
     */
    public function checkout(int $courseId)
    {
        // جلب بيانات الدورة
        $course = $this->coursesModel->find($courseId);
        if (!$course) {
            $this->show_msg('danger', 'خطأ', 'الدورة غير موجودة!');
            return redirect()->back();
        }

        // تحديد هل الدورة مجانية أو قائمة انتظار
        $isFree        = ($course->is_free == 1);
        $isWaitingList = (!empty($course->waiting_list) && $course->waiting_list == 1);

        // التحقق من تسجيل الدخول
        $isLoggedIn = auth()->loggedIn();
        $userId     = $isLoggedIn ? auth()->user()->id : null;

        // تجهيز البيانات للعرض
        $data = [
            'title'         => 'إتمام الدفع / الانضمام للدورة',
            'course'        => $course,
            'isFree'        => $isFree,
            'isWaitingList' => $isWaitingList,
            'isLoggedIn'    => $isLoggedIn,
        ];

        // إذا كان الطلب من نوع POST -> أكمل عملية الدفع أو الانضمام
        if ($this->request->is('POST')) {
            return $this->completePayment($course);
        }

        // خلاف ذلك -> اعرض صفحة الدفع
        return view('site/payment_page', $data);
    }

    /**
     * الدالة التي تعالج إكمال الدفع أو الانضمام وفقًا للحالة:
     * - دورة مجانية
     * - دورة قائمة انتظار
     * - دورة مدفوعة
     */
    private function completePayment(object $course)
    {
        // فحص الدورة
        if (!$course) {
            $this->show_msg('danger', 'خطأ', 'الدورة غير صالحة.');
            return redirect()->back();
        }

        $isFree        = ($course->is_free == 1);
        $isWaitingList = (!empty($course->waiting_list) && $course->waiting_list == 1);

        // التحقق من تسجيل الدخول
        $isLoggedIn = auth()->loggedIn();
        $userId     = $isLoggedIn ? auth()->user()->id : null;

        // إذا لم يكن المستخدم مسجلًا + الدورة مدفوعة
        // يجب التحقق من المدخلات وإنشاء مستخدم جديد
        if (!$isLoggedIn && !$isFree && !$isWaitingList) {
            // تفعيل قواعد التحقق
            if (!$this->validate($this->rules)) {
                $validationErrors = implode('<br>', $this->validator->getErrors());
                $this->show_msg('danger', 'أخطاء التحقق', $validationErrors);
                return redirect()->back()->withInput();
            }

            // تجهيز بيانات المستخدم الجديد
            $userData = [
                'full_name' => $this->request->getPost('name'),
                'email'     => $this->request->getPost('email'),
                'country'   => $this->request->getPost('country'),
                'mobile'    => $this->request->getPost('phone'),
                'password'  => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
                'created_at'=> date('Y-m-d H:i:s'),
            ];

            // إنشاء المستخدم
            $newUserId = $this->usersModel->insert($userData);
            if (!$newUserId) {
                $this->show_msg('danger', 'خطأ', 'تعذّر إنشاء حساب للمستخدم.');
                return redirect()->back();
            }

            // (اختياري) تسجيل دخوله مباشرة
            // auth()->loginById($newUserId);

            $userId = $newUserId;
        }

        // (1) دورة مجانية
        if ($isFree) {
            $this->enrollUser($userId, $course->id);
            $this->show_msg('success', 'تم الانضمام', 'تم انضمامك للدورة المجانية بنجاح!');
            return redirect()->to('/courses/my_courses');
        }

        // (2) قائمة انتظار
        if ($isWaitingList) {
            // حفظ في جدول الانتظار (إن وجد) أو مجرد رسالة
            $this->show_msg('success', 'قائمة الانتظار', 'تمت إضافتك إلى قائمة الانتظار. سنراسلك لاحقًا.');
            return redirect()->back();
        }

        // (3) دورة مدفوعة
        // حفظ سجل الدفع في جدول المدفوعات
        $paymentData = [
            'user_id'        => $userId,
            'course_id'      => $course->id,
            'amount'         => $course->price,
            'payment_method' => 'instapay',
            'payment_status' => 'pending',
            'created_at'     => date('Y-m-d H:i:s'),
        ];
        $paymentId = $this->paymentsModel->insert($paymentData);
        if (!$paymentId) {
            $this->show_msg('danger', 'خطأ', 'تعذّر حفظ عملية الدفع.');
            return redirect()->back();
        }

        // رفع إثبات الدفع (proofImage) إن وجد
        // تأكد أن حقل الملف اسمه proofImage في نموذجك
        $this->fireUploader->upload_photos($this->paymentsModel, 'proofImage', $paymentId);

        // تسجيل المستخدم في الدورة
        $this->enrollUser($userId, $course->id);

        $this->show_msg('success', 'تم الدفع', 'تم استلام عملية الدفع وسيتم مراجعتها قريبًا.');
        return redirect()->to('/courses/my_courses');
    }

    /**
     * تسجيل المستخدم في جدول enrollments.
     */
    private function enrollUser(int $userId, int $courseId): void
    {
        // تحقق إن كان مسجلًا مسبقًا
        $existing = $this->enrollmentsModel
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if ($existing) {
            // لا شيء -> المستخدم مسجل مسبقًا
            return;
        }

        // سجل جديد
        $this->enrollmentsModel->insert([
            'user_id'     => $userId,
            'course_id'   => $courseId,
            'enrolled_at' => date('Y-m-d H:i:s'),
            'status'      => 'active',
        ]);
    }
}
