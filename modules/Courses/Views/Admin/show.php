<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $title ?></h3>
                    <div class="card-tools">
                        <a href="<?= base_url(ADMIN_URL . 'courses') ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> العودة للقائمة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Course Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>معلومات الكورس</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <td><strong>اسم الكورس:</strong></td>
                                    <td><?= esc($course->course_title) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>الوصف:</strong></td>
                                    <td><?= esc($course->short_desc) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>السعر:</strong></td>
                                    <td><?= $course->is_free ? 'مجاني' : '$' . number_format($course->price, 2) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>عدد الوحدات:</strong></td>
                                    <td><?= $units_count ?> وحدة</td>
                                </tr>
                                <tr>
                                    <td><strong>الحالة:</strong></td>
                                    <td>
                                        <span class="badge <?= $course->active ? 'badge-success' : 'badge-danger' ?>">
                                            <?= $course->active ? 'نشط' : 'غير نشط' ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <?php if ($course->image): ?>
                                <?php $images = json_decode($course->image, true); ?>
                                <?php if (!empty($images)): ?>
                                    <h5>صورة الكورس</h5>
                                    <img src="<?= base_url('uploads/' . $images[0]) ?>" class="img-fluid rounded" style="max-height: 200px;">
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Quizzes Management -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">إدارة اختبارات الكورس</h5>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addQuizModal">
                                            <i class="fas fa-plus"></i> إضافة اختبار جديد
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($quizzes)): ?>
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i> لا توجد اختبارات مرتبطة بهذا الكورس حتى الآن.
                                        </div>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>عنوان الاختبار</th>
                                                        <th>الوصف</th>
                                                        <th>مدة الاختبار</th>
                                                        <th>عدد المحاولات</th>
                                                        <th>درجة النجاح</th>
                                                        <th>الحالة</th>
                                                        <th>تاريخ الإنشاء</th>
                                                        <th>الإجراءات</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($quizzes as $quiz): ?>
                                                        <tr>
                                                            <td><?= esc($quiz['quiz_title']) ?></td>
                                                            <td><?= esc($quiz['quiz_desc']) ?></td>
                                                            <td><?= $quiz['time_limit'] ?> دقيقة</td>
                                                            <td><?= $quiz['max_attempts'] ?></td>
                                                            <td><?= $quiz['passing_score'] ?>%</td>
                                                            <td>
                                                                <span class="badge <?= $quiz['active'] ? 'badge-success' : 'badge-danger' ?>">
                                                                    <?= $quiz['active'] ? 'نشط' : 'غير نشط' ?>
                                                                </span>
                                                            </td>
                                                            <td><?= date('Y-m-d H:i', strtotime($quiz['created_at'])) ?></td>
                                                            <td>
                                                                <a href="<?= base_url(ADMIN_URL . 'quizzes/edit/' . $quiz['id']) ?>" class="btn btn-sm btn-warning">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <a href="<?= base_url(ADMIN_URL . 'quizzes/show/' . $quiz['id']) ?>" class="btn btn-sm btn-info">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Quiz Modal -->
<div class="modal fade" id="addQuizModal" tabindex="-1" role="dialog" aria-labelledby="addQuizModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addQuizModalLabel">إضافة اختبار جديد</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addQuizForm">
                <div class="modal-body">
                    <input type="hidden" name="course_id" value="<?= $course->id ?>">

                    <div class="form-group">
                        <label for="quiz_title">عنوان الاختبار <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="quiz_title" name="quiz_title" required>
                    </div>

                    <div class="form-group">
                        <label for="quiz_desc">وصف الاختبار</label>
                        <textarea class="form-control" id="quiz_desc" name="quiz_desc" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="time_limit">مدة الاختبار (بالدقائق)</label>
                                <input type="number" class="form-control" id="time_limit" name="time_limit" value="30" min="1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="max_attempts">عدد المحاولات المسموحة</label>
                                <input type="number" class="form-control" id="max_attempts" name="max_attempts" value="3" min="1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="passing_score">درجة النجاح (%)</label>
                                <input type="number" class="form-control" id="passing_score" name="passing_score" value="70" min="0" max="100" step="0.01">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">إضافة الاختبار</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#addQuizForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: '<?= base_url(ADMIN_URL . "courses/addQuizToCourse") ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#addQuizModal').modal('hide');
                    location.reload(); // Reload to show the new quiz
                } else {
                    alert('خطأ: ' + response.message);
                }
            },
            error: function() {
                alert('حدث خطأ أثناء إضافة الاختبار');
            }
        });
    });
});
</script>

<?= $this->endSection() ?>
