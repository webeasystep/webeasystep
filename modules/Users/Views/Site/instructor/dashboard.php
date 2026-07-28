<?= $this->extend('Modules\Users\Views\Site\instructor\layout'); ?>

<?= $this->section('instructor_page_content'); ?>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card" data-page-search="عدد المقررات overview courses">
            <div class="text-muted small mb-2">عدد المقررات</div>
            <div class="stat-value"><?= esc($overview['courses_count'] ?? 0) ?></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" data-page-search="عدد المشتركين subscribers students">
            <div class="text-muted small mb-2">عدد المشتركين</div>
            <div class="stat-value"><?= esc($overview['subscribers_count'] ?? 0) ?></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" data-page-search="إجمالي الأرباح revenue">
            <div class="text-muted small mb-2">إجمالي الأرباح</div>
            <div class="stat-value"><?= esc($overview['revenue_formatted'] ?? '0.00 ر.س') ?></div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-7">
        <div class="instructor-panel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 fw-bold mb-0">آخر المقررات</h2>
                <a href="<?= site_url('instructor/courses') ?>" class="text-decoration-none fw-bold">عرض الكل</a>
            </div>

            <?php if (!empty($courses)) : ?>
                <?php foreach ($courses as $course) : ?>
                    <div class="record-card" data-page-search="<?= esc(($course->course_title ?? '') . ' ' . ($course->course_code ?? '')) ?>">
                        <img src="<?= esc($course->image_url) ?>" alt="<?= esc($course->course_title ?? 'Course') ?>">
                        <div class="flex-grow-1">
                            <h3 class="h6 fw-bold mb-1"><?= esc($course->course_title ?? '-') ?></h3>
                            <div class="record-meta small mb-1">الكود: <?= esc($course->course_code ?? 'N/A') ?></div>
                            <div class="record-meta small">المشتركون: <?= esc($course->subscribers_count ?? 0) ?> | الوحدات: <?= esc($course->units_count ?? 0) ?> | آخر تحديث: <?= esc($course->updated_at_formatted ?? '-') ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="text-muted mb-0" data-page-search="لا توجد مقررات">لا توجد مقررات مرتبطة بحسابك حاليًا.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-xl-5">
        <div class="instructor-panel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 fw-bold mb-0">أحدث الطلبات</h2>
                <a href="<?= site_url('instructor/orders') ?>" class="text-decoration-none fw-bold">عرض الكل</a>
            </div>

            <?php if (!empty($orders)) : ?>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>المقرر</th>
                            <th>الحالة</th>
                            <th>الربح</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($orders as $order) : ?>
                            <tr data-page-search="<?= esc(($order->course_title ?? '') . ' ' . ($order->student_email ?? '')) ?>">
                                <td>#<?= esc($order->id) ?></td>
                                <td><?= esc($order->course_title ?? '-') ?></td>
                                <td><span class="status-badge status-<?= esc($order->status) ?>"><?= esc($order->status_label ?? $order->status) ?></span></td>
                                <td><?= esc($order->net_profit_formatted ?? '0.00 ر.س') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <p class="text-muted mb-0" data-page-search="لا توجد طلبات">لا توجد طلبات مرتبطة بمقرراتك حتى الآن.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-12">
        <div class="instructor-panel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 fw-bold mb-0">الأسئلة الشائعة</h2>
                <a href="<?= site_url('instructor/faq') ?>" class="text-decoration-none fw-bold">عرض جميع الأسئلة</a>
            </div>

            <?php foreach (($faq_items ?? []) as $item) : ?>
                <div class="faq-item" data-page-search="<?= esc(($item['question'] ?? '') . ' ' . ($item['answer'] ?? '')) ?>">
                    <div class="faq-question"><?= esc($item['question'] ?? '') ?></div>
                    <div class="faq-answer"><?= esc($item['answer'] ?? '') ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
