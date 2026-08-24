<?php

namespace Modules\Cart\Controllers;

use App\Controllers\BaseController;
use App\Libraries\UserType;
use CodeIgniter\Shield\Entities\User;
use Modules\Cart\Models\CartModel;
use Modules\Enrollments\Models\CourseEnrollmentsModel;
use Modules\Bundles\Models\BundlesModel;
use Modules\Coupons\Models\CouponsModel;

class Cart extends BaseController
{
    protected CartModel $cartModel;
    protected CourseEnrollmentsModel $enrollmentsModel;
    protected ?CouponsModel $couponsModel = null;

    public function __construct()
    {
        $this->cartModel = new CartModel();
        $this->enrollmentsModel = new CourseEnrollmentsModel();
    }

    private function getCouponsModel(): CouponsModel
    {
        if ($this->couponsModel === null) {
            $this->couponsModel = new CouponsModel();
        }
        return $this->couponsModel;
    }

    /**
     * View cart page
     */
    public function viewCart()
    {
        if (auth()->loggedIn()) {
            $userId = (int) auth()->user()->id;
            $this->syncGuestCartToUser($userId);
            $items = $this->cartModel->getUserCart($userId);
        } else {
            $items = $this->getGuestCartItems();
        }

        $total = 0;
        foreach ($items as $item) {
            $total += $item->price;
        }

        $data = [
            'title'      => 'سلة المشتريات',
            'cart_items' => $items,
            'cart_total' => $total,
        ];

        return MainView('site_layout/cart', $data);
    }

    /**
     * Add item to cart (AJAX)
     */
    public function addItem()
    {
        $itemType = $this->request->getPost('item_type') ?? 'course';
        $itemId   = (int) $this->request->getPost('item_id');

        if (!$itemId || !in_array($itemType, ['course', 'bundle'], true)) {
            return $this->response->setJSON([
                'success'    => false,
                'message'    => 'عنصر غير صالح.',
                'csrf_token' => csrf_hash(),
            ]);
        }

        if (auth()->loggedIn()) {
            $userId = (int) auth()->user()->id;
            $this->syncGuestCartToUser($userId);
            $result = $this->cartModel->addItem($userId, $itemType, $itemId);

            $messages = [
                'added'    => 'تمت الإضافة إلى السلة بنجاح!',
                'exists'   => 'هذا العنصر موجود بالفعل في السلة.',
                'enrolled' => 'أنت مشترك بالفعل في هذه المادة أو لديك طلب شراء قيد المراجعة.',
                'overlap'  => 'إحدى مواد هذه الباقة موجودة بالفعل في سلتك.',
            ];

            return $this->response->setJSON([
                'success'    => $result === 'added',
                'message'    => $messages[$result] ?? 'حدث خطأ.',
                'cart_count' => $this->cartModel->getCartCount($userId),
                'csrf_token' => csrf_hash(),
            ]);
        }

        // Guest user cart stored in session
        $guestCart = session()->get('guest_cart') ?? [];

        foreach ($guestCart as $gi) {
            if ($gi['item_type'] === $itemType && (int) $gi['item_id'] === $itemId) {
                return $this->response->setJSON([
                    'success'    => false,
                    'message'    => 'هذا العنصر موجود بالفعل في السلة.',
                    'cart_count' => count($guestCart),
                    'csrf_token' => csrf_hash(),
                ]);
            }
        }

        $guestCart[] = [
            'cart_id'   => 'guest_' . time() . '_' . rand(100, 999),
            'item_type' => $itemType,
            'item_id'   => $itemId,
        ];
        session()->set('guest_cart', $guestCart);

        return $this->response->setJSON([
            'success'    => true,
            'message'    => 'تمت الإضافة إلى السلة بنجاح!',
            'cart_count' => count($guestCart),
            'csrf_token' => csrf_hash(),
        ]);
    }

    /**
     * Remove item from cart (AJAX)
     */
    public function removeItem()
    {
        $cartId = $this->request->getPost('cart_id');

        if (auth()->loggedIn()) {
            $userId = (int) auth()->user()->id;
            $this->cartModel->removeItem($userId, (int) $cartId);
            $items = $this->cartModel->getUserCart($userId);
            $cartCount = $this->cartModel->getCartCount($userId);
        } else {
            $guestCart = session()->get('guest_cart') ?? [];
            $newCart = [];
            foreach ($guestCart as $gi) {
                $id = (string) ($gi['cart_id'] ?? ($gi['item_type'] . '_' . $gi['item_id']));
                if ($id !== (string) $cartId && (string) ($gi['item_id'] ?? '') !== (string) $cartId) {
                    $newCart[] = $gi;
                }
            }
            session()->set('guest_cart', $newCart);
            $items = $this->getGuestCartItems();
            $cartCount = count($newCart);
        }

        $total = 0;
        foreach ($items as $item) {
            $total += $item->price;
        }

        return $this->response->setJSON([
            'success'    => true,
            'cart_count' => $cartCount,
            'cart_total' => $total,
            'csrf_token' => csrf_hash(),
        ]);
    }

    /**
     * Get cart count (AJAX for navbar badge)
     */
    public function getCount()
    {
        if (auth()->loggedIn()) {
            $userId = (int) auth()->user()->id;
            $this->syncGuestCartToUser($userId);
            $count = $this->cartModel->getCartCount($userId);
        } else {
            $count = count(session()->get('guest_cart') ?? []);
        }

        return $this->response->setJSON(['count' => $count]);
    }

    /**
     * Sync items from guest session cart into database when user is authenticated
     */
    private function syncGuestCartToUser(int $userId): void
    {
        $guestCart = session()->get('guest_cart');
        if (!empty($guestCart) && is_array($guestCart)) {
            foreach ($guestCart as $item) {
                $this->cartModel->addItem($userId, (string) $item['item_type'], (int) $item['item_id']);
            }
            session()->remove('guest_cart');
        }
    }

    /**
     * Retrieves guest cart items hydrated with full product details
     */
    private function getGuestCartItems(): array
    {
        $guestCart = session()->get('guest_cart') ?? [];
        $result = [];
        foreach ($guestCart as $item) {
            $hydrated = $this->cartModel->hydrateCartItem($item['item_type'], (int) $item['item_id'], $item['cart_id'] ?? null);
            if ($hydrated !== null) {
                $result[] = $hydrated;
            }
        }
        return $result;
    }

    /**
     * Checkout - guests can submit a selected course and create their account
     * in the same request. Existing users keep the current cart checkout flow.
     */
    public function checkout()
    {
        $isGuestCheckout = !auth()->loggedIn();
        $directItem = $this->getDirectCheckoutItem();

        $userId = auth()->loggedIn() ? (int) auth()->user()->id : null;
        if ($userId !== null) {
            $this->syncGuestCartToUser($userId);
        }

        if ($directItem !== null) {
            $items = [$directItem];
        } elseif ($userId !== null) {
            $items = $this->cartModel->getUserCart($userId);
        } else {
            $items = $this->getGuestCartItems();
        }

        if (empty($items)) {
            return redirect()->to('/cart')->with('error', 'السلة فارغة.');
        }

        if ($this->request->is('post')) {
            return $this->processCheckout($userId, $items, $isGuestCheckout, $directItem !== null);
        }

        $data = [
            'title'              => 'إتمام الشراء',
            'cart_items'         => $items,
            'cart_total'         => $this->calculateCartTotal($items),
            'is_guest_checkout'  => $isGuestCheckout,
            'checkout_item_type' => $directItem->item_type ?? null,
            'checkout_item_id'   => $directItem->item_id ?? null,
        ];

        return MainView('site_layout/cart_checkout', $data);
    }

    /**
     * Confirmation page shown after a pending payment request is saved.
     */
    public function checkoutSuccess()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        return MainView('site_layout/checkout_success', ['title' => 'تم استلام طلبك']);
    }

    /**
     * Process the checkout: create enrollments for all cart items.
     */
    private function processCheckout(?int $userId, array $items, bool $isGuestCheckout, bool $isDirectCheckout): \CodeIgniter\HTTP\RedirectResponse
    {
        $total = $this->calculateCartTotal($items);

        if (!$this->validateCheckoutInput($isGuestCheckout)) {
            return redirect()->back()->withInput()->with('error', $this->getCheckoutValidationMessage());
        }

        $paymentMethod = $this->request->getPost('payment_method') ?? 'anb';
        $couponCode = trim((string) ($this->request->getPost('coupon_code') ?? ''));
        $transferSenderName = mb_substr(trim((string) ($this->request->getPost('transfer_sender_name') ?? '')), 0, 150);

        $coupon = null;
        $couponDiscountAmount = 0.0;
        $finalAmount = $total;

        // Guest coupon validation remains deferred until they have an account.
        if ($couponCode !== '' && $userId !== null) {
            $coupon = $this->getCouponsModel()->getValidCouponByCode($couponCode, 0);

            if ($coupon && $this->getCouponsModel()->isAvailableForUser($coupon, $userId)) {
                $couponDiscountAmount = $this->getCouponsModel()->calculateDiscountAmount($total, $coupon);
                $finalAmount = max(0, $total - $couponDiscountAmount);
            }
        }

        if (!$this->validatePaymentProof($finalAmount > 0)) {
            return redirect()->back()->withInput()->with('error', $this->getCheckoutValidationMessage());
        }

        $paymentProofPath = null;
        $movedPaymentProof = null;
        $proofFile = $this->request->getFile('payment_proof');
        if ($proofFile && $proofFile->isValid() && !$proofFile->hasMoved()) {
            $uploadPath = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'enrollments';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0775, true);
            }

            $randomName = $proofFile->getRandomName();
            $proofFile->move($uploadPath, $randomName);
            $paymentProofPath = 'uploads/enrollments/' . $randomName;
            $movedPaymentProof = $uploadPath . DIRECTORY_SEPARATOR . $randomName;
        }

        $isFree = $finalAmount <= 0;
        $batchId = bin2hex(random_bytes(16));
        $uniqueCourses = $this->getUniqueCoursesToEnroll($items, $total, $finalAmount);
        $createdCount = 0;
        $createdUser = null;

        $this->db->transBegin();

        try {
            if ($isGuestCheckout) {
                $createdUser = $this->createCheckoutUser();
                if ($createdUser === null) {
                    throw new \RuntimeException('تعذر إنشاء الحساب. تحقق من البريد الإلكتروني ورقم الواتساب.');
                }

                $userId = (int) $createdUser->id;
            }

            if ($userId === null) {
                throw new \RuntimeException('تعذر تحديد حساب الطالب.');
            }

            foreach ($uniqueCourses as $entry) {
                if ($this->enrollmentsModel->isUserEnrolled($userId, $entry['course_id'], false)) {
                    continue;
                }

                $enrollmentData = [
                    'paid_amount'            => $isFree ? 0 : ($entry['paid_amount'] ?? 0),
                    'bundle_id'              => $entry['bundle_id'],
                    'batch_id'               => $batchId,
                    'coupon_id'              => $coupon->id ?? null,
                    'coupon_code'            => $coupon->coupon_code ?? null,
                    'coupon_discount_amount' => $couponDiscountAmount,
                    'payment_method'         => $isFree ? 'free' : $paymentMethod,
                    'payment_proof'          => $paymentProofPath,
                    'transfer_sender_name'   => $transferSenderName ?: null,
                    'auto_approve'           => $isFree,
                    'notes'                  => 'Cart checkout | Batch: ' . $batchId . ' | Total: ' . $finalAmount,
                ];

                if (!$this->enrollmentsModel->createEnrollment($userId, $entry['course_id'], $enrollmentData)) {
                    throw new \RuntimeException('تعذر حفظ طلب الاشتراك.');
                }

                $createdCount++;
            }

            if ($createdCount === 0) {
                throw new \RuntimeException('لم يتم إنشاء أي اشتراك. ربما أنت مشترك بالفعل في جميع المواد.');
            }

            if ($coupon) {
                $this->getCouponsModel()->incrementUsage((int) $coupon->id);
            }

            if (!$isDirectCheckout) {
                $this->cartModel->clearCart($userId);
            }

            if (!$this->db->transStatus()) {
                throw new \RuntimeException('تعذر حفظ طلب الاشتراك.');
            }

            $this->db->transCommit();
        } catch (\Throwable $e) {
            $this->db->transRollback();

            if ($movedPaymentProof !== null && is_file($movedPaymentProof)) {
                @unlink($movedPaymentProof);
            }

            log_message('error', 'Cart checkout failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        if ($createdUser !== null) {
            $this->logInCheckoutUser($createdUser);
        }

        if ($isFree) {
            return redirect()->to('/enrollments/my-courses')
                ->with('success', 'تم تفعيل اشتراكاتك بنجاح! يمكنك الآن الوصول إلى المحتوى.');
        }

        return redirect()->to('/cart/checkout/success')
            ->with('success', 'تم استلام طلبك بنجاح. جاري مراجعة الإيصال وتفعيل الكورس خلال ساعتين.');
    }

    /**
     * Builds a single checkout item from a public course or bundle link.
     */
    private function getDirectCheckoutItem(): ?object
    {
        $isPost = $this->request->is('post');
        $itemType = $isPost ? $this->request->getPost('checkout_item_type') : $this->request->getGet('item_type');
        $itemId = (int) ($isPost ? $this->request->getPost('checkout_item_id') : $this->request->getGet('item_id'));

        if (!in_array($itemType, ['course', 'bundle'], true) || $itemId <= 0) {
            return null;
        }

        $item = (object) [
            'cart_id'   => null,
            'item_type' => $itemType,
            'item_id'   => $itemId,
            'title'     => '',
            'price'     => 0,
            'image'     => null,
            'courses'   => [],
        ];

        if ($itemType === 'course') {
            $course = $this->db->table('tb_courses')->where('id', $itemId)->get()->getRow();
            if ($course === null) {
                return null;
            }

            $item->title = $course->course_title;
            $item->price = (float) $course->course_price;
            $item->image = $course->image;

            return $item;
        }

        $bundle = $this->db->table('tb_bundles')->where('id', $itemId)->get()->getRow();
        if ($bundle === null) {
            return null;
        }

        $item->title = $bundle->bundle_title;
        $item->price = (float) $bundle->bundle_price;
        $item->image = $bundle->image;
        $item->courses = $this->db->table('tb_bundle_courses')
            ->select('tb_courses.id, tb_courses.course_title, tb_courses.course_price')
            ->join('tb_courses', 'tb_courses.id = tb_bundle_courses.course_id')
            ->where('tb_bundle_courses.bundle_id', $itemId)
            ->get()
            ->getResultArray();

        return $item;
    }

    private function calculateCartTotal(array $items): float
    {
        return array_reduce(
            $items,
            static fn (float $total, object $item): float => $total + (float) $item->price,
            0.0
        );
    }

    private function getUniqueCoursesToEnroll(array $items, float $total, float $finalAmount): array
    {
        $coursesToEnroll = [];
        $discountRatio = $total > 0 ? ($finalAmount / $total) : 1.0;

        foreach ($items as $item) {
            $itemEffectivePrice = (float) $item->price * $discountRatio;

            if ($item->item_type === 'course') {
                $coursesToEnroll[] = [
                    'course_id'   => (int) $item->item_id,
                    'bundle_id'   => null,
                    'paid_amount' => round($itemEffectivePrice, 2),
                ];
                continue;
            }

            $bundleCourseIds = (new BundlesModel())->getBundleCourseIds((int) $item->item_id);
            $courseCount = count($bundleCourseIds);
            $pricePerCourse = $courseCount > 0
                ? round($itemEffectivePrice / $courseCount, 2)
                : round($itemEffectivePrice, 2);

            foreach ($bundleCourseIds as $courseId) {
                $coursesToEnroll[] = [
                    'course_id'   => (int) $courseId,
                    'bundle_id'   => (int) $item->item_id,
                    'paid_amount' => $pricePerCourse,
                ];
            }
        }

        $seen = [];
        $uniqueCourses = [];
        foreach ($coursesToEnroll as $entry) {
            if (!in_array($entry['course_id'], $seen, true)) {
                $seen[] = $entry['course_id'];
                $uniqueCourses[] = $entry;
            }
        }

        return $uniqueCourses;
    }

    /**
     * Validates the account fields before any checkout records are created.
     */
    private function validateCheckoutInput(bool $isGuestCheckout): bool
    {
        $rules = $isGuestCheckout
            ? [
                'full_name' => 'required|min_length[3]|max_length[100]',
                'email'     => 'required|valid_email|max_length[254]|is_unique[auth_identities.secret]',
                'mobile'    => 'required|regex_match[/^(5[0-9]{8}|05[0-9]{8})$/]',
                'password'  => 'required|min_length[6]',
            ]
            : ['payment_method' => 'permit_empty'];

        return $this->validate($rules);
    }

    /**
     * Requires a safe transfer receipt only when the final payable amount is positive.
     */
    private function validatePaymentProof(bool $requiresPaymentProof): bool
    {
        if (!$requiresPaymentProof) {
            return true;
        }

        $proofFile = $this->request->getFile('payment_proof');
        if ($proofFile === null || !$proofFile->isValid() || $proofFile->hasMoved()) {
            $this->validator->setError('payment_proof', 'يرجى إرفاق صورة أو ملف إيصال التحويل.');
            return false;
        }

        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'];
        if (!in_array($proofFile->getMimeType(), $allowedMimeTypes, true)) {
            $this->validator->setError('payment_proof', 'صيغة الإيصال غير مدعومة. استخدم JPG أو PNG أو WEBP أو PDF.');
            return false;
        }

        if ($proofFile->getSizeByUnit('mb') > 5) {
            $this->validator->setError('payment_proof', 'يجب ألا يتجاوز حجم الإيصال 5 ميجابايت.');
            return false;
        }

        return true;
    }

    private function getCheckoutValidationMessage(): string
    {
        $errors = $this->validator->getErrors();

        return implode('<br>', array_map(static fn (string $error): string => esc($error), $errors));
    }

    /**
     * Creates an immediately active student account for one-step checkout.
     */
    private function createCheckoutUser(): ?User
    {
        $email = mb_strtolower(trim((string) $this->request->getPost('email')));
        $mobile = $this->normalizeSaudiMobile((string) $this->request->getPost('mobile'));

        if ($this->db->table('users')->where('mobile', $mobile)->countAllResults() > 0) {
            throw new \RuntimeException('رقم الواتساب مسجل بالفعل. سجّل دخولك لإتمام الاشتراك.');
        }

        $users = auth()->getProvider();
        $user = new User([
            'full_name' => trim((string) $this->request->getPost('full_name')),
            'email'     => $email,
            'mobile'    => $mobile,
            'user_type' => UserType::STUDENT,
            'password'  => (string) $this->request->getPost('password'),
        ]);

        if (!$users->save($user)) {
            log_message('error', 'One-step checkout user creation failed: ' . json_encode($users->errors()));
            return null;
        }

        /** @var User|null $createdUser */
        $createdUser = $users->find($users->getInsertID());
        if ($createdUser === null) {
            return null;
        }

        // The student logs in directly, without a separate email-activation step.
        $createdUser->activate();

        return $createdUser;
    }

    private function normalizeSaudiMobile(string $rawMobile): string
    {
        $digitsOnly = preg_replace('/[^0-9]/', '', trim($rawMobile));

        if (str_starts_with($digitsOnly, '966')) {
            $digitsOnly = substr($digitsOnly, 3);
        }

        return str_starts_with($digitsOnly, '5') && strlen($digitsOnly) === 9
            ? '0' . $digitsOnly
            : $digitsOnly;
    }

    /**
     * Uses the existing Shield session authenticator to sign in the new student.
     */
    private function logInCheckoutUser(User $user): void
    {
        auth()->logout();

        $attempt = auth()->attempt([
            'email'    => mb_strtolower(trim((string) $this->request->getPost('email'))),
            'password' => (string) $this->request->getPost('password'),
        ]);

        if (!$attempt->isOK()) {
            log_message('error', 'One-step checkout login failed for user ID: ' . $user->id);
            auth('session')->getAuthenticator()->startLogin($user);
        }
    }
}
