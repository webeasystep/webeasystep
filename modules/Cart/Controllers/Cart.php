<?php

namespace Modules\Cart\Controllers;

use App\Controllers\BaseController;
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
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $userId = auth()->user()->id;
        $items = $this->cartModel->getUserCart($userId);
        $total = 0;
        foreach ($items as $item) {
            $total += $item->price;
        }

        $data = [
            'title'      => 'سلة المشتريات',
            'cart_items'  => $items,
            'cart_total'  => $total,
        ];

        return MainView('site_layout/cart', $data);
    }

    /**
     * Add item to cart (AJAX)
     */
    public function addItem()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'يجب تسجيل الدخول أولاً.',
                'csrf_token' => csrf_hash(),
            ]);
        }

        $userId   = auth()->user()->id;
        $itemType = $this->request->getPost('item_type') ?? 'course';
        $itemId   = (int) $this->request->getPost('item_id');

        if (!$itemId || !in_array($itemType, ['course', 'bundle'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'عنصر غير صالح.',
                'csrf_token' => csrf_hash(),
            ]);
        }

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

    /**
     * Remove item from cart (AJAX)
     */
    public function removeItem()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON(['success' => false, 'csrf_token' => csrf_hash()]);
        }

        $userId = auth()->user()->id;
        $cartId = (int) $this->request->getPost('cart_id');

        $this->cartModel->removeItem($userId, $cartId);

        // Recalculate total
        $items = $this->cartModel->getUserCart($userId);
        $total = 0;
        foreach ($items as $item) {
            $total += $item->price;
        }

        return $this->response->setJSON([
            'success'    => true,
            'cart_count' => $this->cartModel->getCartCount($userId),
            'cart_total' => $total,
            'csrf_token' => csrf_hash(),
        ]);
    }

    /**
     * Get cart count (AJAX for navbar badge)
     */
    public function getCount()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON(['count' => 0]);
        }

        return $this->response->setJSON([
            'count' => $this->cartModel->getCartCount(auth()->user()->id),
        ]);
    }

    /**
     * Checkout - GET: show checkout page, POST: process payment
     */
    public function checkout()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $userId = auth()->user()->id;
        $items = $this->cartModel->getUserCart($userId);

        if (empty($items)) {
            return redirect()->to('/cart')->with('error', 'السلة فارغة.');
        }

        // POST - process checkout
        if ($this->request->is('post')) {
            return $this->processCheckout($userId, $items);
        }

        $total = 0;
        foreach ($items as $item) {
            $total += $item->price;
        }

        $data = [
            'title'      => 'إتمام الشراء',
            'cart_items'  => $items,
            'cart_total'  => $total,
        ];

        return MainView('site_layout/cart_checkout', $data);
    }

    /**
     * Process the checkout: create enrollments for all cart items
     */
    private function processCheckout(int $userId, array $items): \CodeIgniter\HTTP\RedirectResponse
    {
        $paymentMethod = $this->request->getPost('payment_method') ?? 'paypal';
        $couponCode    = trim((string) ($this->request->getPost('coupon_code') ?? ''));

        // Calculate total
        $total = 0;
        foreach ($items as $item) {
            $total += $item->price;
        }

        // Handle coupon
        $coupon = null;
        $couponDiscountAmount = 0.0;
        $finalAmount = $total;

        if ($couponCode !== '') {
            // Validate coupon (pass 0 for course_id since it's a cart-wide coupon)
            $coupon = $this->getCouponsModel()->getValidCouponByCode($couponCode, 0);

            if ($coupon) {
                if ($this->getCouponsModel()->isAvailableForUser($coupon, $userId)) {
                    $couponDiscountAmount = $this->getCouponsModel()->calculateDiscountAmount($total, $coupon);
                    $finalAmount = max(0, $total - $couponDiscountAmount);
                }
            }
        }

        // Handle payment proof upload
        $paymentProofPath = null;
        $proofFile = $this->request->getFile('payment_proof');
        if ($proofFile && $proofFile->isValid() && !$proofFile->hasMoved()) {
            $uploadPath = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'enrollments';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0775, true);
            }
            $randomName = $proofFile->getRandomName();
            $proofFile->move($uploadPath, $randomName);
            $paymentProofPath = 'uploads/enrollments/' . $randomName;
        }

        $isFree = $finalAmount <= 0;
        $batchId = bin2hex(random_bytes(16)); // Unique batch ID

        // Collect all course IDs to enroll
        $coursesToEnroll = [];
        foreach ($items as $item) {
            if ($item->item_type === 'course') {
                $coursesToEnroll[] = [
                    'course_id' => (int) $item->item_id,
                    'bundle_id' => null,
                ];
            } else {
                // Bundle: expand to individual courses
                $bundleCourseIds = (new BundlesModel())->getBundleCourseIds((int) $item->item_id);
                foreach ($bundleCourseIds as $courseId) {
                    $coursesToEnroll[] = [
                        'course_id' => (int) $courseId,
                        'bundle_id' => (int) $item->item_id,
                    ];
                }
            }
        }

        // Remove duplicates (same course from both direct and bundle)
        $seen = [];
        $uniqueCourses = [];
        foreach ($coursesToEnroll as $entry) {
            if (!in_array($entry['course_id'], $seen)) {
                $seen[] = $entry['course_id'];
                $uniqueCourses[] = $entry;
            }
        }

        // Create enrollments
        $createdCount = 0;
        foreach ($uniqueCourses as $entry) {
            // Skip if already enrolled
            if ($this->enrollmentsModel->isUserEnrolled($userId, $entry['course_id'], false)) {
                continue;
            }

            $enrollmentData = [
                'paid_amount'            => 0, // Individual amounts are 0; total tracked via batch
                'bundle_id'              => $entry['bundle_id'],
                'batch_id'               => $batchId,
                'coupon_id'              => $coupon->id ?? null,
                'coupon_code'            => $coupon->coupon_code ?? null,
                'coupon_discount_amount' => 0,
                'payment_method'         => $isFree ? 'free' : $paymentMethod,
                'payment_proof'          => $paymentProofPath,
                'auto_approve'           => $isFree,
                'notes'                  => 'Cart checkout | Batch: ' . $batchId . ' | Total: ' . $finalAmount,
            ];

            $this->enrollmentsModel->createEnrollment($userId, $entry['course_id'], $enrollmentData);
            $createdCount++;
        }

        if ($createdCount === 0) {
            return redirect()->to('/cart')->with('error', 'لم يتم إنشاء أي اشتراك. ربما أنت مشترك بالفعل في جميع المواد.');
        }

        // Increment coupon usage
        if ($coupon) {
            $this->getCouponsModel()->incrementUsage((int) $coupon->id);
        }

        // Clear cart
        $this->cartModel->clearCart($userId);

        if ($isFree) {
            return redirect()->to('/enrollments/my-courses')
                ->with('success', 'تم تفعيل اشتراكاتك بنجاح! يمكنك الآن الوصول إلى المحتوى.');
        }

        return redirect()->to('/enrollments/my-courses')
            ->with('success', 'تم إرسال طلب الشراء بنجاح! سيتم مراجعته وتفعيل اشتراكاتك بعد التحقق من الدفع.');
    }
}
