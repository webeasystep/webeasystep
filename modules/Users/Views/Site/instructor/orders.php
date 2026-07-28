<?= $this->extend('Modules\Users\Views\Site\instructor\layout'); ?>

<?= $this->section('instructor_page_content'); ?>
<div class="instructor-panel">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h5 fw-bold mb-1">الطلبات</h2>
            <p class="text-muted mb-0">يعرض هذا القسم رقم الطلب، واسم المقرر، والبريد الإلكتروني لصاحب الطلب، والحالة الحالية، وصافي الربح.</p>
        </div>
        <span class="badge text-bg-primary"><?= count($orders ?? []) ?> طلب</span>
    </div>

    <?php if (!empty($orders)) : ?>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>اسم المقرر</th>
                    <th>البريد الإلكتروني</th>
                    <th>الحالة</th>
                    <th>صافي الربح</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($orders as $order) : ?>
                    <tr data-page-search="<?= esc(($order->course_title ?? '') . ' ' . ($order->student_email ?? '') . ' ' . ($order->id ?? '')) ?>">
                        <td>#<?= esc($order->id) ?></td>
                        <td><?= esc($order->course_title ?? '-') ?></td>
                        <td><?= esc($order->student_email ?? '-') ?></td>
                        <td><span class="status-badge status-<?= esc($order->status) ?>"><?= esc($order->status_label ?? $order->status) ?></span></td>
                        <td><?= esc($order->net_profit_formatted ?? '0.00 ر.س') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else : ?>
        <p class="text-muted mb-0" data-page-search="لا توجد طلبات">لا توجد طلبات مسجلة على مقرراتك حتى الآن.</p>
    <?php endif; ?>
</div>
<?= $this->endSection(); ?>
