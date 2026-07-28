<?= $this->extend('Modules\Users\Views\Site\instructor\layout'); ?>

<?= $this->section('instructor_page_content'); ?>
<div class="instructor-panel">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h5 fw-bold mb-1">مقرراتي</h2>
            <p class="text-muted mb-0">يعرض كل مقرر الاسم، والكود، والصورة، وعدد المشتركين، وعدد الوحدات، وآخر تحديث.</p>
        </div>
        <span class="badge text-bg-primary"><?= count($courses ?? []) ?> مقرر</span>
    </div>

    <?php if (!empty($courses)) : ?>
        <?php foreach ($courses as $course) : ?>
            <div class="record-card" data-page-search="<?= esc(($course->course_title ?? '') . ' ' . ($course->course_code ?? '')) ?>">
                <img src="<?= esc($course->image_url) ?>" alt="<?= esc($course->course_title ?? 'Course') ?>">
                <div class="flex-grow-1">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                        <div>
                            <h3 class="h6 fw-bold mb-1"><?= esc($course->course_title ?? '-') ?></h3>
                            <div class="record-meta small">كود المقرر: <?= esc($course->course_code ?? 'N/A') ?></div>
                        </div>
                        <a href="<?= site_url('courses/course_details/' . ($course->slug ?? '')) ?>" class="btn btn-sm btn-outline-primary">عرض المقرر</a>
                    </div>
                    <div class="row row-cols-1 row-cols-md-3 g-2 mt-2">
                        <div class="col">
                            <div class="record-meta small">عدد المشتركين: <?= esc($course->subscribers_count ?? 0) ?></div>
                        </div>
                        <div class="col">
                            <div class="record-meta small">عدد الوحدات: <?= esc($course->units_count ?? 0) ?></div>
                        </div>
                        <div class="col">
                            <div class="record-meta small">آخر تحديث: <?= esc($course->updated_at_formatted ?? '-') ?></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p class="text-muted mb-0" data-page-search="لا توجد مقررات">لا توجد مقررات مرتبطة بحسابك حتى الآن. ويمكن للإدارة ربط المقررات من لوحة التحكم الخاصة بها.</p>
    <?php endif; ?>
</div>
<?= $this->endSection(); ?>
