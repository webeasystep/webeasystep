<?php

namespace Modules\Payments\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;
use Modules\Payments\Models\PaymentsModel;
use Modules\Courses\Models\CoursesModel;
use Modules\Courses\Models\PaymentsModel;
use App\Models\UsersModel;
use App\Libraries\FireUploader;

class AdminPayments extends BaseController
{
    protected PaymentsModel $paymentsModel;
    protected CoursesModel $coursesModel;
    protected PaymentsModel $enrollmentsModel;
    protected UsersModel $usersModel;
    protected FireUploader $fireUploader;

    /**
     * قواعد التحقق الأساسية.
     * - ملاحظات:
     *   1) إذا كان المستخدم غير مسجل والدورة مدفوعة: نتحقق من المدخلات (الاسم، الإيميل، إلخ).
     *   2) إذا كان الدفع يتطلب رفع إثبات: يمكن إضافة قاعدة لرفع الملف.
     */
    protected array $rules = [
        // قواعد عامة يمكن تطبيقها عند الحاجة
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
        // يمكن إضافة قواعد لرفع الملف إذا أردت:
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
     * شاشة الدفع (السيناريوهات الثلاثة):
     * 1) دورة مجانية
     * 2) دورة قائمة انتظار
     * 3) دورة مدفوعة
     */
    public function purchase(int $courseId)
    {
        // جلب الدورة
        $course = $this->coursesModel->find($courseId);
        if (!$course) {
            $this->show_msg('danger', 'خطأ', 'الدورة غير موجودة!');
            return redirect()->back();
        }

        // فحص الحالة
        $isFree        = ($course->is_free == 1);
        $isWaitingList = (!empty($course->waiting_list) && $course->waiting_list == 1);

        // تحقق هل المستخدم مسجل دخول
        $isLoggedIn = auth()->loggedIn();
        $userId     = $isLoggedIn ? auth()->user()->id : null;

        // تجهيز بيانات للعرض
        $data = [
            'title'         => 'إتمام الدفع / الانضمام للدورة',
            'course'        => $course,
            'isFree'        => $isFree,
            'isWaitingList' => $isWaitingList,
            'isLoggedIn'    => $isLoggedIn,
        ];

        // لو كان الطلب POST
        if ($this->request->getMethod() === 'post') {
            // معالجة الدفع
            return $this->completePayment($course);
        }

        // وإلا اعرض صفحة الدفع
        return view('site/payment_page', $data);
    }

    /**
     * الدالة الخاصة بإكمال الدفع أو الانضمام.
     * تشمل:
     *  - دورة مجانية
     *  - دورة قائمة انتظار
     *  - دورة مدفوعة
     */
    private function completePayment(object $course)
    {
        // تحقّق
        if (!$course) {
            $this->show_msg('danger', 'خطأ', 'الدورة غير صالحة.');
            return redirect()->back();
        }

        // فحص الحالة
        $isFree        = ($course->is_free == 1);
        $isWaitingList = (!empty($course->waiting_list) && $course->waiting_list == 1);

        $isLoggedIn = auth()->loggedIn();
        $userId     = $isLoggedIn ? auth()->user()->id : null;

        // سيناريو: المستخدم غير مسجل + الدورة مدفوعة
        // => التحقق من المدخلات وتسجيل مستخدم جديد
        if (!$isLoggedIn && !$isFree && !$isWaitingList) {
            // نفعّل قواعد التحقق
            if (!$this->validate($this->rules)) {
                // أخطاء التحقق
                $validationErrors = implode('<br>', $this->validator->getErrors());
                $this->show_msg('danger', 'أخطاء التحقق', $validationErrors);
                return redirect()->back()->withInput();
            }

            // بناء بيانات المستخدم
            $userData = [
                'full_name' => $this->request->getPost('name'),
                'email'     => $this->request->getPost('email'),
                'country'   => $this->request->getPost('country'),
                'mobile'    => $this->request->getPost('phone'),
                'password'  => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
                'created_at'=> date('Y-m-d H:i:s'),
            ];

            // أنشئ المستخدم
            $newUserId = $this->usersModel->insert($userData);
            if (!$newUserId) {
                $this->show_msg('danger', 'خطأ', 'تعذّر إنشاء حساب للمستخدم.');
                return redirect()->back();
            }

            // سجل دخوله إذا أردت:
            // auth()->loginById($newUserId);

            $userId = $newUserId;
        }

        // (1) دورة مجانية
        if ($isFree) {
            $this->enrollUser($userId, $course->id);
            $this->show_msg('success', 'تهانينا', 'تم انضمامك للدورة المجانية بنجاح!');
            return redirect()->to('/courses/my_courses');
        }

        // (2) قائمة انتظار
        if ($isWaitingList) {
            // احفظ المستخدم في جدول الانتظار إن وُجد
            // أو أرسل إشعار...
            $this->show_msg('success', 'قائمة الانتظار', 'تمت إضافتك إلى قائمة الانتظار. سنراسلك لاحقًا.');
            return redirect()->back();
        }

        // (3) دورة مدفوعة
        // حفظ سجل الدفع
        $paymentData = [
            'user_id'        => $userId,
            'course_id'      => $course->id,
            'amount'         => $course->price,
            'payment_method' => 'instapay', // مثلاً
            'payment_status' => 'pending',
            'created_at'     => date('Y-m-d H:i:s'),
        ];
        $paymentId = $this->paymentsModel->insert($paymentData);
        if (!$paymentId) {
            $this->show_msg('danger', 'خطأ', 'تعذّر حفظ عملية الدفع.');
            return redirect()->back();
        }

        // رفع إثبات الدفع إن وجد (proofImage)
        // ملاحظة: تأكد أن حقل الـ file اسمه proofImage في النموذج
        $this->fireUploader->upload_photos($this->paymentsModel, 'proofImage', $paymentId);

        // سجل المستخدم في الدورة
        $this->enrollUser($userId, $course->id);

        $this->show_msg('success', 'تم الدفع', 'تم استلام عملية الدفع وسيتم مراجعتها قريبًا.');
        return redirect()->to('/courses/my_courses');
    }

    /**
     * تسجيل المستخدم في الدورة (جدول enrollments).
     */
    private function enrollUser(int $userId, int $courseId): void
    {
        // تحقق إذا كان المستخدم مسجلاً مسبقًا
        $existing = $this->enrollmentsModel
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();
        if ($existing) {
            // لا تفعل شيئًا، المستخدم مسجل مسبقًا
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
