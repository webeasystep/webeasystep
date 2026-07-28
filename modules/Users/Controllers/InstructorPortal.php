<?php

declare(strict_types=1);

namespace Modules\Users\Controllers;

use App\Controllers\BaseController;
use App\Libraries\UserType;
use CodeIgniter\Shield\Entities\User;

class InstructorPortal extends BaseController
{
    /**
     * Displays the main instructor dashboard with overview metrics.
     */
    public function dashboard(): string
    {
        $instructor = $this->getInstructor();
        $courses = $this->getInstructorCoursesData($instructor->id);
        $orders = $this->getInstructorOrdersData($instructor->id);

        $data = $this->getSharedViewData('لوحة تحكم المحاضر', 'dashboard');
        $data['overview'] = $this->getOverviewStats($instructor->id);
        $data['courses'] = array_slice($courses, 0, 4);
        $data['orders'] = array_slice($orders, 0, 5);
        $data['faq_items'] = array_slice($this->getFaqItems(), 0, 5);

        return view('site/instructor/dashboard', $data);
    }

    /**
     * Displays the instructor course list.
     */
    public function courses(): string
    {
        $instructor = $this->getInstructor();

        $data = $this->getSharedViewData('مقرراتي', 'courses');
        $data['courses'] = $this->getInstructorCoursesData($instructor->id);

        return view('site/instructor/courses', $data);
    }

    /**
     * Displays the instructor order list.
     */
    public function orders(): string
    {
        $instructor = $this->getInstructor();

        $data = $this->getSharedViewData('الطلبات', 'orders');
        $data['orders'] = $this->getInstructorOrdersData($instructor->id);

        return view('site/instructor/orders', $data);
    }

    /**
     * Displays the FAQ knowledge base for instructors.
     */
    public function faq(): string
    {
        $data = $this->getSharedViewData('الأسئلة الشائعة', 'faq');
        $data['faq_items'] = $this->getFaqItems();

        return view('site/instructor/faq', $data);
    }

    /**
     * Returns the authenticated instructor.
     */
    private function getInstructor(): User
    {
        /** @var User $user */
        $user = auth()->user();

        return $user;
    }

    /**
     * Returns the shared view payload used by all instructor pages.
     */
    private function getSharedViewData(string $title, string $activeNav): array
    {
        $instructor = $this->getInstructor();

        return [
            'title' => $title,
            'active_nav' => $activeNav,
            'instructor_name' => $instructor->full_name ?? 'المحاضر',
            'user_type_label' => UserType::getLabel((int) ($instructor->user_type ?? UserType::INSTRUCTOR)),
            'overview' => $this->getOverviewStats($instructor->id),
            'sidebar_links' => [
                [
                    'key' => 'dashboard',
                    'label' => 'النظرة العامة',
                    'url' => site_url('instructor/dashboard'),
                    'icon' => 'fas fa-chart-pie',
                ],
                [
                    'key' => 'courses',
                    'label' => 'المقررات',
                    'url' => site_url('instructor/courses'),
                    'icon' => 'fas fa-book-open',
                ],
                [
                    'key' => 'orders',
                    'label' => 'الطلبات',
                    'url' => site_url('instructor/orders'),
                    'icon' => 'fas fa-receipt',
                ],
                [
                    'key' => 'faq',
                    'label' => 'الأسئلة الشائعة',
                    'url' => site_url('instructor/faq'),
                    'icon' => 'fas fa-circle-question',
                ],
            ],
        ];
    }

    /**
     * Returns the main dashboard counters for the instructor.
     *
     * @return array<string, int|float|string>
     */
    private function getOverviewStats(int $instructorId): array
    {
        $coursesCount = $this->db->table('tb_courses')
            ->where('instructor_id', $instructorId)
            ->countAllResults();

        $subscribersCount = $this->db->table('tb_course_enrollments e')
            ->join('tb_courses c', 'c.id = e.course_id')
            ->where('c.instructor_id', $instructorId)
            ->where('e.status', 'approved')
            ->countAllResults();

        $revenueRow = $this->db->table('tb_course_enrollments e')
            ->selectSum('e.paid_amount', 'total_revenue')
            ->join('tb_courses c', 'c.id = e.course_id')
            ->where('c.instructor_id', $instructorId)
            ->where('e.status', 'approved')
            ->get()
            ->getRow();

        $revenue = (float) ($revenueRow?->total_revenue ?? 0);

        return [
            'courses_count' => $coursesCount,
            'subscribers_count' => $subscribersCount,
            'revenue' => $revenue,
            'revenue_formatted' => number_format($revenue, 2) . ' ر.س',
        ];
    }

    /**
     * Returns all courses assigned to the current instructor.
     *
     * @return array<int, object>
     */
    private function getInstructorCoursesData(int $instructorId): array
    {
        $courses = $this->db->table('tb_courses c')
            ->select('c.id, c.course_title, c.course_code, c.image, c.updated_at, c.slug, COUNT(DISTINCT e.id) as subscribers_count, COUNT(DISTINCT u.id) as units_count')
            ->join('tb_course_enrollments e', 'e.course_id = c.id AND e.status = "approved"', 'left')
            ->join('tb_units u', 'u.course_id = c.id AND u.active = 1', 'left')
            ->where('c.instructor_id', $instructorId)
            ->groupBy('c.id')
            ->orderBy('c.updated_at', 'DESC')
            ->get()
            ->getResult();

        foreach ($courses as $course) {
            $course->image_url = $this->resolveCourseImageUrl($course->image ?? null, $course->course_title ?? 'Course');
            $course->updated_at_formatted = ! empty($course->updated_at)
                ? date('Y-m-d', strtotime((string) $course->updated_at))
                : '-';
            $course->course_code = $course->course_code ?: 'N/A';
        }

        return $courses;
    }

    /**
     * Returns all orders related to the instructor courses.
     *
     * @return array<int, object>
     */
    private function getInstructorOrdersData(int $instructorId): array
    {
        $orders = $this->db->table('tb_course_enrollments e')
            ->select('e.id, e.status, e.paid_amount, e.created_at, c.course_title, i.secret as student_email')
            ->join('tb_courses c', 'c.id = e.course_id')
            ->join('users u', 'u.id = e.user_id', 'left')
            ->join('auth_identities i', 'i.user_id = u.id AND i.type = "email_password"', 'left')
            ->where('c.instructor_id', $instructorId)
            ->orderBy('e.created_at', 'DESC')
            ->get()
            ->getResult();

        foreach ($orders as $order) {
            $order->status_label = $this->translateOrderStatus((string) $order->status);
            $order->net_profit = $order->status === 'approved' ? (float) $order->paid_amount : 0.0;
            $order->net_profit_formatted = number_format((float) $order->net_profit, 2) . ' ر.س';
            $order->student_email = $order->student_email ?: '-';
        }

        return $orders;
    }

    /**
     * Returns the instructor FAQ items.
     *
     * @return array<int, array<string, string>>
     */
    private function getFaqItems(): array
    {
        return [
            [
                'question' => 'كيف تبدأ العمل على المنصة؟',
                'answer' => 'بعد إنشاء حسابك، يمكنك طلب ربط مقرراتك بحسابك لتظهر تلقائيًا داخل لوحة التحكم.',
            ],
            [
                'question' => 'كيف تختار مقررك؟',
                'answer' => 'ركّز على المقرر الذي تملك فيه محتوى واضحًا وقيمة تعليمية حقيقية، ثم حدّد الكود والمخرجات التعليمية بصورة مباشرة للطلاب.',
            ],
            [
                'question' => 'كيف ترتب مصادر المقرر؟',
                'answer' => 'اجمع مراجعك الأساسية، ورتّب الوحدات حسب التسلسل التعليمي، وحدد ما يحتاج إليه الطالب قبل الانتقال إلى كل وحدة.',
            ],
            [
                'question' => 'كيف تسعّر مقررك؟',
                'answer' => 'راجع قيمة المحتوى، مستوى الطلب، وعدد الوحدات، ثم حدّد سعرًا يناسب السوق السعودي ويعكس القيمة التعليمية.',
            ],
            [
                'question' => 'كيف تصنع محتواك؟',
                'answer' => 'قسّم المقرر إلى وحدات قصيرة، وقدّم أمثلة عملية، واحرص على تحديث المقرر كلما طرأت تغييرات على المنهج أو المحتوى.',
            ],
            [
                'question' => 'ماذا ستجد في لوحة التحكم؟',
                'answer' => 'من لوحة التحكم يمكنك متابعة عدد المقررات، وعدد الطلبات، وأعداد المشتركين، والوصول السريع إلى قسم الأسئلة الشائعة.',
            ],
            [
                'question' => 'شروط نشر المحتوى',
                'answer' => 'يجب أن يكون المحتوى أصليًا، منظمًا، خاليًا من المخالفات، ومتوافقًا مع سياسة النشر ومعايير الجودة داخل المنصة.',
            ],
        ];
    }

    /**
     * Returns the public URL for a course image.
     */
    private function resolveCourseImageUrl(?string $rawImage, string $courseTitle): string
    {
        if (! empty($rawImage)) {
            $decoded = json_decode($rawImage, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && $decoded !== []) {
                $files = isset($decoded['files']) && is_array($decoded['files']) ? $decoded['files'] : $decoded;
                $firstEntry = $files[0] ?? null;

                if ($firstEntry !== null) {
                    $firstImage = is_array($firstEntry)
                        ? ($firstEntry['full_path'] ?? $firstEntry['encoded_name'] ?? $firstEntry['file_name'] ?? null)
                        : $firstEntry;

                    if (! empty($firstImage)) {
                        if (preg_match('#^https?://#i', (string) $firstImage) === 1) {
                            return (string) $firstImage;
                        }

                        $imagePath = ltrim((string) $firstImage, '/');

                        if (str_starts_with($imagePath, 'uploads/')) {
                            return base_url($imagePath);
                        }

                        return base_url('uploads/courses/' . $imagePath);
                    }
                }
            }
        }

        $prompt = rawurlencode('professional online course cover, blue academic dashboard style, clean e-learning branding, Arabic education platform, realistic digital illustration');

        return "https://coresg-normal.trae.ai/api/ide/v1/text_to_image?prompt={$prompt}&image_size=landscape_16_9";
    }

    /**
     * Translates enrollment status labels to Arabic.
     */
    private function translateOrderStatus(string $status): string
    {
        return match ($status) {
            'approved' => 'مقبول',
            'pending' => 'قيد المراجعة',
            'rejected' => 'مرفوض',
            'expired' => 'منتهي',
            default => $status,
        };
    }
}
