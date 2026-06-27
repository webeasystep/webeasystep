<?= $this->extend('admin_layout/template'); ?>
<?= $this->section('content'); ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= $title ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dt_admin') ?>">لوحة التحكم</a></li>
                        <li class="breadcrumb-item"><a href="<?= ADMIN_URL . 'units' ?>">إدارة الوحدات</a></li>
                        <li class="breadcrumb-item active">إضافة وحدة جديدة</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <?= $this->include('admin_layout/admin_msg'); ?>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">إضافة وحدة جديدة</h3>
                </div>

                <?php
                $isEdit = isset($unit) && $unit;
                $formAction = $isEdit ? ADMIN_URL . 'units/edit/' . $unit->id : ADMIN_URL . 'units/add';
                $selectedCourseId = old('course_id', $isEdit ? $unit->course_id : ($selected_course_id ?? null));
                ?>
                <?= form_open($formAction, ['id' => 'unit-form']) ?>
                <div class="card-body">
                    <div class="row">
                        <!-- Course Selection -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="course_id">الكورس <span class="text-danger">*</span></label>
                                <select name="course_id" id="course_id" class="form-control" required>
                                    <option value="">اختر الكورس</option>
                                    <?php foreach ($courses as $course): ?>
                                        <option value="<?= $course->id ?>" <?= ((string) $selectedCourseId === (string) $course->id) ? 'selected' : '' ?>>
                                            <?= esc($course->course_title) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Unit Basic Information -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="unit_name">اسم الوحدة <span class="text-danger">*</span></label>
                                <input type="text" name="unit_name" id="unit_name" class="form-control"
                                       value="<?= set_value('unit_name', $isEdit ? $unit->unit_name : '') ?>"
                                       placeholder="أدخل اسم الوحدة" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sort_order">ترتيب الوحدة <span class="text-danger">*</span></label>
                                <input type="number" name="sort_order" id="sort_order" class="form-control"
                                       value="<?= set_value('sort_order', $isEdit ? $unit->sort_order : 1) ?>"
                                       min="1" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="unit_desc">وصف الوحدة <span class="text-danger">*</span></label>
                        <textarea name="unit_desc" id="unit_desc" class="form-control" rows="4"
                                  placeholder="أدخل وصف الوحدة" required><?= set_value('unit_desc', $isEdit ? $unit->unit_desc : '') ?></textarea>
                        <div class="invalid-feedback"></div>
                    </div>

                    <!-- Unit Items Management -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">عناصر الوحدة</h5>
                            <div class="card-tools">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addItemModal">
                                    <i class="fas fa-plus"></i> إضافة عنصر جديد
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="unit-items-container">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> لا توجد عناصر مضافة بعد. اضغط على "إضافة عنصر جديد" لبدء إضافة العناصر.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Unit Settings -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">إعدادات الوحدة</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input type="checkbox" name="active" id="active" class="form-check-input" value="1" <?= ($isEdit && $unit->active) ? 'checked' : 'checked' ?>>
                                        <label class="form-check-label" for="active">
                                            وحدة نشطة
                                        </label>
                                        <small class="form-text text-muted">الوحدات النشطة فقط تظهر للطلاب</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?= $isEdit ? 'تحديث الوحدة' : 'إضافة الوحدة' ?>
                    </button>
                    <a href="<?= ADMIN_URL . 'units' ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> إلغاء
                    </a>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </section>
</div>

<!-- Add Item Type Selection Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addItemModalLabel">
                    <i class="fas fa-plus-circle mr-2"></i>اختيار نوع العنصر الجديد
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card item-type-card h-100 shadow-sm" onclick="selectItemType('video')" style="cursor: pointer; transition: all 0.3s;">
                            <div class="card-body text-center d-flex flex-column justify-content-center">
                                <i class="fas fa-video fa-4x mb-3 text-danger"></i>
                                <h5 class="card-title font-weight-bold">فيديو تعليمي</h5>
                                <p class="text-muted small">إضافة فيديو من Bunny.net مع جلب البيانات تلقائياً</p>
                                <div class="mt-auto">
                                    <span class="badge badge-danger">Video</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card item-type-card h-100 shadow-sm" onclick="selectItemType('quiz')" style="cursor: pointer; transition: all 0.3s;">
                            <div class="card-body text-center d-flex flex-column justify-content-center">
                                <i class="fas fa-question-circle fa-4x mb-3 text-success"></i>
                                <h5 class="card-title font-weight-bold">اختبار تفاعلي</h5>
                                <p class="text-muted small">إضافة اختبار مرتبط بالكورس الحالي</p>
                                <div class="mt-auto">
                                    <span class="badge badge-success">Quiz</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card item-type-card h-100 shadow-sm" onclick="selectItemType('page')" style="cursor: pointer; transition: all 0.3s;">
                            <div class="card-body text-center d-flex flex-column justify-content-center">
                                <i class="fas fa-file-alt fa-4x mb-3 text-info"></i>
                                <h5 class="card-title font-weight-bold">صفحة إضافية</h5>
                                <p class="text-muted small">ربط صفحة محتوى موجودة بالوحدة</p>
                                <div class="mt-auto">
                                    <span class="badge badge-info">Page</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>ملاحظة:</strong> يمكنك إضافة عدة عناصر من نفس النوع أو أنواع مختلفة، وسيتم ترتيبها حسب الترتيب المحدد.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Video Modal -->
<div class="modal fade" id="addVideoModal" tabindex="-1" role="dialog" aria-labelledby="addVideoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="addVideoModalLabel">
                    <i class="fas fa-video mr-2"></i>إضافة فيديو تعليمي
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addVideoForm">
                    <!-- Video Source Selection -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bold">
                                    <i class="fas fa-globe mr-1"></i>مصدر الفيديو
                                </label>
                                <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                    <label class="btn btn-outline-danger active" onclick="selectVideoSource('bunny')">
                                        <input type="radio" name="video_source" id="source_bunny" value="bunny" checked>
                                        <i class="fas fa-cloud mr-1"></i> Bunny.net
                                    </label>
                                    <label class="btn btn-outline-danger" onclick="selectVideoSource('youtube')">
                                        <input type="radio" name="video_source" id="source_youtube" value="youtube">
                                        <i class="fab fa-youtube mr-1"></i> YouTube
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bunny.net Video Input -->
                    <div id="bunnyVideoSection" class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="video_id" class="font-weight-bold">
                                    <i class="fas fa-hashtag mr-1"></i>معرف الفيديو من Bunny.net
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-video"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="video_id" name="video_id"
                                           placeholder="أدخل معرف الفيديو (مثال: abc123-def456)">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary" onclick="fetchVideoData()" id="fetchBtn">
                                            <i class="fas fa-download mr-1"></i>جلب البيانات
                                        </button>
                                    </div>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    يمكنك العثور على معرف الفيديو في لوحة تحكم Bunny.net
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- YouTube Video Input -->
                    <div id="youtubeVideoSection" class="row" style="display: none;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="youtube_url" class="font-weight-bold">
                                    <i class="fab fa-youtube mr-1"></i>رابط فيديو YouTube
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fab fa-youtube text-danger"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="youtube_url" name="youtube_url"
                                           placeholder="الصق رابط الفيديو من YouTube">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-danger" onclick="parseYoutubeUrl()" id="parseYoutubeBtn">
                                            <i class="fas fa-magic mr-1"></i>استخراج البيانات
                                        </button>
                                    </div>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    أدخل الرابط الكامل للفيديو (مثال: https://www.youtube.com/watch?v=XXXXX أو https://youtu.be/XXXXX)
                                </small>
                            </div>
                            
                            <!-- YouTube Video Title (manual input) -->
                            <div class="form-group" id="youtube_manual_fields" style="display: none;">
                                <label for="youtube_video_title" class="font-weight-bold">
                                    <i class="fas fa-heading mr-1"></i>عنوان الفيديو
                                </label>
                                <input type="text" class="form-control" id="youtube_video_title" name="youtube_video_title"
                                       placeholder="أدخل عنوان الفيديو">
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    سيتم استخدام هذا العنوان لعرض الفيديو في الوحدة
                                </small>
                            </div>
                        </div>
                    </div>

                    <div id="videoDataSection" style="display: none;">
                        <hr>
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-info-circle mr-1"></i>بيانات الفيديو المجلبة
                        </h6>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="video_title" class="font-weight-bold">
                                        <i class="fas fa-heading mr-1"></i>عنوان الفيديو
                                    </label>
                                    <input type="text" class="form-control" id="video_title" name="video_title" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="video_duration" class="font-weight-bold">
                                        <i class="fas fa-clock mr-1"></i>مدة الفيديو
                                    </label>
                                    <input type="text" class="form-control" id="video_duration" name="video_duration" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="video_size" class="font-weight-bold">
                                        <i class="fas fa-hdd mr-1"></i>حجم الملف
                                    </label>
                                    <input type="text" class="form-control" id="video_size" name="video_size" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        <i class="fas fa-image mr-1"></i>صورة الفيديو
                                    </label>
                                    <div class="text-center">
                                        <img id="video_thumbnail_preview" src="" alt="صورة الفيديو"
                                             class="img-fluid rounded shadow-sm"
                                             style="max-width: 100%; max-height: 150px; display: none;">
                                        <div id="no_thumbnail" class="text-muted" style="display: none;">
                                            <i class="fas fa-image fa-3x mb-2"></i>
                                            <p>لا توجد صورة متاحة</p>
                                        </div>
                                    </div>
                                    <input type="hidden" id="video_thumbnail" name="video_thumbnail">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="video_sort_order" class="font-weight-bold">
                                <i class="fas fa-sort-numeric-up mr-1"></i>ترتيب العرض
                            </label>
                            <input type="number" class="form-control" id="video_sort_order" name="video_sort_order"
                                   value="1" min="1" placeholder="ترتيب الفيديو في الوحدة">
                            <small class="form-text text-muted">حدد ترتيب ظهور هذا الفيديو في الوحدة</small>
                        </div>
                    </div>

                    <div id="loadingIndicator" style="display: none;" class="text-center py-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">جاري التحميل...</span>
                        </div>
                        <p class="mt-2 text-muted">جاري جلب بيانات الفيديو من Bunny.net...</p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>إلغاء
                </button>
                <button type="button" class="btn btn-success" onclick="saveVideoItem()" id="saveVideoBtn" disabled>
                    <i class="fas fa-save mr-1"></i>حفظ الفيديو
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Quiz Modal -->
<div class="modal fade" id="addQuizModal" tabindex="-1" role="dialog" aria-labelledby="addQuizModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="addQuizModalLabel">
                    <i class="fas fa-question-circle mr-2"></i>إضافة اختبار تفاعلي
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="quizForm">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>ملاحظة:</strong> سيتم عرض الاختبارات المرتبطة بالكورس المحدد فقط.
                    </div>

                    <div class="form-group">
                        <label for="quiz_select" class="font-weight-bold">
                            <i class="fas fa-list mr-1"></i>اختر الاختبار المطلوب
                        </label>
                        <select class="form-control" id="quiz_id" name="quiz_id" required>
                            <option value="">-- جاري تحميل الاختبارات المتاحة --</option>
                        </select>
                        <small class="form-text text-muted">
                            يتم عرض الاختبارات النشطة المرتبطة بالكورس الحالي فقط
                        </small>
                    </div>

                    <div id="selectedQuizInfo" style="display: none;" class="mt-3">
                        <div class="card border-success">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-info-circle mr-1"></i>معلومات الاختبار المحدد
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>العنوان:</strong> <span id="selected_quiz_title">-</span></p>
                                        <p><strong>الوصف:</strong> <span id="selected_quiz_desc">-</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>مدة الاختبار:</strong> <span id="selected_quiz_duration">-</span> دقيقة</p>
                                        <p><strong>درجة النجاح:</strong> <span id="selected_quiz_passing_score">-</span>%</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label for="quiz_sort_order" class="font-weight-bold">
                            <i class="fas fa-sort-numeric-up mr-1"></i>ترتيب العرض
                        </label>
                        <input type="number" class="form-control" id="quiz_sort_order" name="quiz_sort_order"
                               value="1" min="1" placeholder="ترتيب الاختبار في الوحدة">
                        <small class="form-text text-muted">حدد ترتيب ظهور هذا الاختبار في الوحدة</small>
                    </div>

                    <div id="noQuizzesMessage" style="display: none;" class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>تنبيه:</strong> لا توجد اختبارات متاحة لهذا الكورس.
                        <div class="mt-2">
                            <button type="button" class="btn btn-warning btn-sm" onclick="openNewQuizForm()">
                                <i class="fas fa-plus mr-1"></i>إنشاء اختبار جديد
                            </button>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-outline-success" onclick="openNewQuizForm()">
                            <i class="fas fa-plus mr-1"></i>إنشاء اختبار جديد للكورس
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>إلغاء
                </button>
                <button type="button" class="btn btn-success" onclick="saveQuizItem()" id="saveQuizBtn" disabled>
                    <i class="fas fa-save mr-1"></i>حفظ الاختبار
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Page Modal -->
<div class="modal fade" id="addPageModal" tabindex="-1" role="dialog" aria-labelledby="addPageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="addPageModalLabel">
                    <i class="fas fa-file-alt mr-2"></i>إضافة صفحة إضافية
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="pageForm">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>ملاحظة:</strong> يمكنك ربط صفحة محتوى موجودة بهذه الوحدة لإثراء المحتوى التعليمي.
                    </div>

                    <div class="form-group">
                        <label for="page_select" class="font-weight-bold">
                            <i class="fas fa-list mr-1"></i>اختر الصفحة المطلوبة
                        </label>
                        <select class="form-control" id="page_id" name="page_id" required>
                            <option value="">-- جاري تحميل الصفحات المتاحة --</option>
                        </select>
                        <small class="form-text text-muted">
                            يتم عرض الصفحات النشطة فقط
                        </small>
                    </div>

                    <div id="selectedPageInfo" style="display: none;" class="mt-3">
                        <div class="card border-info">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-info-circle mr-1"></i>معلومات الصفحة المحددة
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p><strong>عنوان الصفحة:</strong> <span id="selected_page_title">-</span></p>
                                        <p><strong>الوصف:</strong> <span id="selected_page_desc">-</span></p>
                                        <p><strong>تاريخ الإنشاء:</strong> <span id="selected_page_created">-</span></p>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <i class="fas fa-file-alt fa-4x text-info mb-2"></i>
                                            <p class="small text-muted">صفحة محتوى</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <a href="#" id="preview_page_link" target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye mr-1"></i>معاينة الصفحة
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label for="page_sort_order" class="font-weight-bold">
                            <i class="fas fa-sort-numeric-up mr-1"></i>ترتيب العرض
                        </label>
                        <input type="number" class="form-control" id="page_sort_order" name="page_sort_order"
                               value="1" min="1" placeholder="ترتيب الصفحة في الوحدة">
                        <small class="form-text text-muted">حدد ترتيب ظهور هذه الصفحة في الوحدة</small>
                    </div>

                    <div id="noPagesMessage" style="display: none;" class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>تنبيه:</strong> لا توجد صفحات متاحة حالياً.
                        <div class="mt-2">
                            <a href="<?= ADMIN_URL ?>pages/add" class="btn btn-warning btn-sm" target="_blank">
                                <i class="fas fa-plus mr-1"></i>إنشاء صفحة جديدة
                            </a>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="<?= ADMIN_URL ?>pages/add" class="btn btn-outline-info" target="_blank">
                            <i class="fas fa-plus mr-1"></i>إنشاء صفحة جديدة
                        </a>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>إلغاء
                </button>
                <button type="button" class="btn btn-info" onclick="savePageItem()" id="savePageBtn" disabled>
                    <i class="fas fa-save mr-1"></i>حفظ الصفحة
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.item-type-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    border-color: #007bff;
}
.item-type-card:hover .fas {
    transform: scale(1.1);
}
.modal-header.bg-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
}
.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}
</style>

<script>
// Global variables
let unitItems = [];
let currentCourseId = null;

// Initialize with existing data when editing
<?php if ($isEdit && isset($existing_unit_items)): ?>
unitItems = <?= $existing_unit_items ?>;
// Parse metadata JSON string into flat fields for each item so saveUnitItems() can re-save correctly
unitItems = unitItems.map(function(item) {
    if (item.metadata && typeof item.metadata === 'string') {
        try {
            const meta = JSON.parse(item.metadata);
            // Merge metadata fields into the item object
            item = Object.assign({}, item, meta);
        } catch(e) {
            console.warn('Failed to parse metadata for item:', item.id, e);
        }
    }
    return item;
});
console.log('Loaded existing unit items for editing:', unitItems);
<?php else: ?>
console.log('New unit form - unitItems initialized as empty array');
<?php endif; ?>

$(document).ready(function() {
    // Initialize currentCourseId with selected course in edit mode
    <?php if ($isEdit && $unit): ?>
    currentCourseId = '<?= $unit->course_id ?>';
    <?php endif; ?>

    // Display existing unit items if editing
    <?php if ($isEdit && isset($existing_unit_items)): ?>
    displayUnitItems();
    <?php endif; ?>

    // Course selection handler
    $('#course_id').on('change', function() {
        currentCourseId = $(this).val();
        if (currentCourseId) {
            fetchNextSortOrder(currentCourseId);
        }
    });

    // Auto-fetch sort order on page load for ADD mode when course is pre-selected
    <?php if (!$isEdit && !empty($selected_course_id)): ?>
    currentCourseId = '<?= $selected_course_id ?>';
    fetchNextSortOrder(currentCourseId);
    <?php endif; ?>

    // Form validation
    $('#unit-form').on('submit', function(e) {
        let isValid = true;

        // Validate required fields
        $(this).find('[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            toastr.error('يرجى ملء جميع الحقول المطلوبة');
        } else {
            // Remove any existing unit_items input first
            $(this).find('input[name="unit_items"]').remove();

            // Always submit unit_items in edit mode (even if unchanged) to preserve existing items
            // In add mode, only submit if there are items to save
            <?php if ($isEdit): ?>
            const unitItemsJson = JSON.stringify(unitItems);
            const hiddenInput = $('<input type="hidden" name="unit_items">');
            hiddenInput.val(unitItemsJson);
            $(this).append(hiddenInput);
            console.log('Edit mode - submitting unit items:', unitItems.length, 'items');
            <?php else: ?>
            if (unitItems.length > 0) {
                const unitItemsJson = JSON.stringify(unitItems);
                const hiddenInput = $('<input type="hidden" name="unit_items">');
                hiddenInput.val(unitItemsJson);
                $(this).append(hiddenInput);
                console.log('Add mode - submitting unit items:', unitItems.length, 'items');
            }
            <?php endif; ?>
        }
    });
});

/**
 * Fetch the next available sort order for a given course from the backend
 */
function fetchNextSortOrder(courseId) {
    if (!courseId) return;

    $.get('<?= ADMIN_URL ?>units/get-next-sort-order/' + courseId, function(response) {
        if (response.success && response.next_sort_order) {
            <?php if (!$isEdit): ?>
            // In add mode, always auto-set the sort order
            $('#sort_order').val(response.next_sort_order);
            <?php else: ?>
            // In edit mode, only update if the course has changed from original
            var originalCourseId = '<?= $unit->course_id ?? '' ?>';
            if (String(courseId) !== String(originalCourseId)) {
                $('#sort_order').val(response.next_sort_order);
            }
            <?php endif; ?>
        }
    }).fail(function() {
        console.warn('Failed to fetch next sort order for course:', courseId);
    });
}

// Item Type Selection
function selectItemType(type) {
    $('#addItemModal').modal('hide');

    switch(type) {
        case 'video':
            $('#addVideoModal').modal('show');
            break;
        case 'quiz':
            loadQuizzes();
            $('#addQuizModal').modal('show');
            break;
        case 'page':
            loadPages();
            $('#addPageModal').modal('show');
            break;
    }
}

// Global variable to store fetched video data
let currentVideoData = null;

// Video Functions
function fetchVideoData() {
    const videoId = $('#video_id').val().trim();
    if (!videoId) {
        toastr.error('يرجى إدخال معرف الفيديو');
        return;
    }

    // Show loading indicator
    $('#loadingIndicator').show();
    $('#fetchBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>جاري الجلب...');
    $('#videoDataSection').hide();
    $('#saveVideoBtn').prop('disabled', true);

    $.ajax({
        url: '<?= ADMIN_URL ?>units/fetch-video-data',
        method: 'POST',
        data: {
            video_id: videoId,
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        },
        dataType: 'json',
        success: function(response) {
            $('#loadingIndicator').hide();
            $('#fetchBtn').prop('disabled', false).html('<i class="fas fa-download mr-1"></i>جلب البيانات');

            if (response.success) {
                // Store complete video data globally
                currentVideoData = response.data;

                // Fill video data
                $('#video_title').val(response.data.title || 'غير محدد');
                $('#video_duration').val(response.data.video_duration || formatDuration(response.data.duration) || 'غير محدد');
                $('#video_size').val(formatFileSize(response.data.file_size) || 'غير محدد');
                $('#video_thumbnail').val(response.data.thumbnail || '');

                // Show thumbnail or placeholder
                if (response.data.thumbnail) {
                    $('#video_thumbnail_preview').attr('src', response.data.thumbnail).show();
                    $('#no_thumbnail').hide();
                } else {
                    $('#video_thumbnail_preview').hide();
                    $('#no_thumbnail').show();
                }

                // Show video data section and enable save button
                $('#videoDataSection').show();
                $('#saveVideoBtn').prop('disabled', false);

                // Set next sort order
                $('#video_sort_order').val(getNextSortOrder());

                toastr.success('تم جلب بيانات الفيديو بنجاح');
            } else {
                toastr.error('فشل في جلب بيانات الفيديو: ' + (response.message || 'خطأ غير معروف'));
            }
        },
        error: function(xhr, status, error) {
            $('#loadingIndicator').hide();
            $('#fetchBtn').prop('disabled', false).html('<i class="fas fa-download mr-1"></i>جلب البيانات');
            toastr.error('حدث خطأ أثناء جلب بيانات الفيديو: ' + error);
        }
    });
}

function saveVideoItem() {
    const videoId = $('#video_id').val().trim();
    const videoTitle = $('#video_title').val();
    const videoDuration = $('#video_duration').val();
    const videoThumbnail = $('#video_thumbnail').val();
    const sortOrder = parseInt($('#video_sort_order').val()) || getNextSortOrder();
    const videoSource = $('input[name="video_source"]:checked').val() || 'bunny';

    // Handle based on video source
    if (videoSource === 'youtube') {
        const youtubeVideoId = currentVideoData ? currentVideoData.video_id : null;
        const youtubeTitle = $('#youtube_video_title').val().trim();
        
        if (!youtubeVideoId || !youtubeTitle) {
            toastr.error('يرجى استخراج بيانات فيديو YouTube وإدخال العنوان');
            return;
        }
        
        const item = {
            item_type: 'video',
            video_source: 'youtube',
            video_id: youtubeVideoId,
            video_title: youtubeTitle,
            title: youtubeTitle,
            duration: 0,
            video_duration: 'غير محدد',
            thumbnail: 'https://img.youtube.com/vi/' + youtubeVideoId + '/hqdefault.jpg',
            video_thumbnail: 'https://img.youtube.com/vi/' + youtubeVideoId + '/hqdefault.jpg',
            youtube_url: $('#youtube_url').val(),
            embed_url: 'https://www.youtube.com/embed/' + youtubeVideoId,
            sort_order: sortOrder,
            is_active: 1,
            is_free: 0,
            id: 'video_' + Date.now()
        };

        unitItems.push(item);
        displayUnitItems();

        $('#addVideoModal').modal('hide');
        resetVideoForm();

        toastr.success('تم إضافة فيديو YouTube بنجاح');
        return;
    }

    // Bunny.net video handling (original)
    if (!videoId || !videoTitle || !currentVideoData) {
        toastr.error('يرجى جلب بيانات الفيديو أولاً');
        return;
    }

    const item = {
        item_type: 'video',
        video_source: 'bunny',
        video_id: videoId,
        video_title: videoTitle,
        title: videoTitle,
        duration: currentVideoData.duration || 0,
        video_duration: videoDuration,
        thumbnail: videoThumbnail,
        video_thumbnail: videoThumbnail,
        collection_id: currentVideoData.collection_id || '',
        file_size: currentVideoData.file_size || 0,
        video_quality: currentVideoData.height ? currentVideoData.width + 'x' + currentVideoData.height : null,
        width: currentVideoData.width || 0,
        height: currentVideoData.height || 0,
        framerate: currentVideoData.framerate || 0,
        description: currentVideoData.description || '',
        sort_order: sortOrder,
        is_active: 1,
        is_free: 0,
        id: 'video_' + Date.now()
    };

    unitItems.push(item);
    displayUnitItems();

    $('#addVideoModal').modal('hide');
    resetVideoForm();

    toastr.success('تم إضافة الفيديو بنجاح');
}

function resetVideoForm() {
    $('#addVideoForm')[0].reset();
    $('#video_thumbnail_preview').hide();
    $('#no_thumbnail').hide();
    $('#videoDataSection').hide();
    $('#saveVideoBtn').prop('disabled', true);
    $('#video_sort_order').val('1');
    currentVideoData = null;
    
    // Reset source selection
    selectVideoSource('bunny');
    $('#youtube_manual_fields').hide();
    $('#youtube_url').val('');
    $('#youtube_video_title').val('');
}

// Video Source Selection
let currentVideoSource = 'bunny';

function selectVideoSource(source) {
    currentVideoSource = source;
    
    if (source === 'bunny') {
        $('#bunnyVideoSection').show();
        $('#youtubeVideoSection').hide();
        $('#videoDataSection').hide();
        $('label[for="source_bunny"]').addClass('active');
        $('label[for="source_youtube"]').removeClass('active');
        $('#source_bunny').prop('checked', true);
    } else {
        $('#bunnyVideoSection').hide();
        $('#youtubeVideoSection').show();
        $('#videoDataSection').hide();
        $('label[for="source_youtube"]').addClass('active');
        $('label[for="source_bunny"]').removeClass('active');
        $('#source_youtube').prop('checked', true);
    }
    
    $('#saveVideoBtn').prop('disabled', true);
    currentVideoData = null;
}

// YouTube URL Parsing
function parseYoutubeUrl() {
    const url = $('#youtube_url').val().trim();
    
    if (!url) {
        toastr.error('يرجى إدخال رابط فيديو YouTube');
        return;
    }
    
    const videoId = extractYoutubeVideoId(url);
    
    if (!videoId) {
        toastr.error('رابط YouTube غير صالح. يرجى التحقق من الرابط');
        return;
    }
    
    // Store YouTube video data
    currentVideoData = {
        video_id: videoId,
        video_source: 'youtube',
        thumbnail: 'https://img.youtube.com/vi/' + videoId + '/hqdefault.jpg',
        embed_url: 'https://www.youtube.com/embed/' + videoId
    };
    
    // Show title input and thumbnail preview
    $('#youtube_manual_fields').show();
    $('#video_thumbnail_preview').attr('src', currentVideoData.thumbnail).show();
    $('#no_thumbnail').hide();
    $('#videoDataSection').hide(); // We don't use the Bunny data section for YouTube
    
    // Enable save button
    $('#saveVideoBtn').prop('disabled', false);
    $('#video_sort_order').val(getNextSortOrder());
    
    toastr.success('تم استخراج معرف الفيديو: ' + videoId);
}

function extractYoutubeVideoId(url) {
    // Regular expressions for different YouTube URL formats
    const patterns = [
        /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/|youtube\.com\/v\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/,
        /^([a-zA-Z0-9_-]{11})$/ // Direct video ID
    ];
    
    for (const pattern of patterns) {
        const match = url.match(pattern);
        if (match) {
            return match[1];
        }
    }
    
    return null;
}

// Quiz Functions
function loadQuizzes() {
    if (!currentCourseId) {
        toastr.error('يرجى اختيار الكورس أولاً');
        return;
    }

    $('#quiz_id').html('<option value="">-- جاري تحميل الاختبارات المتاحة --</option>');
    $('#saveQuizBtn').prop('disabled', true);

    $.get('<?= ADMIN_URL ?>units/get-available-quizzes/' + currentCourseId, function(response) {
        if (response.success && response.quizzes.length > 0) {
            let options = '<option value="">اختر اختبار</option>';
            response.quizzes.forEach(function(quiz) {
                options += '<option value="' + quiz.id + '" data-title="' + quiz.title + '" data-desc="' + (quiz.description || 'لا يوجد وصف') + '" data-duration="' + (quiz.duration || 0) + '" data-passing="' + (quiz.passing_score || 0) + '">' + quiz.title + '</option>';
            });
            $('#quiz_id').html(options);
            $('#noQuizzesMessage').hide();
        } else {
            $('#quiz_id').html('<option value="">لا توجد اختبارات متاحة</option>');
            $('#noQuizzesMessage').show();
        }
    }).fail(function() {
        $('#quiz_id').html('<option value="">خطأ في تحميل الاختبارات</option>');
        toastr.error('حدث خطأ في تحميل الاختبارات');
    });
}

// Quiz selection handler
$(document).on('change', '#quiz_id', function() {
    const selectedOption = $(this).find('option:selected');
    const quizId = selectedOption.val();

    if (quizId) {
        $('#selected_quiz_title').text(selectedOption.data('title') || selectedOption.text());
        $('#selected_quiz_desc').text(selectedOption.data('desc') || 'لا يوجد وصف');
        $('#selected_quiz_duration').text(selectedOption.data('duration') || '0');
        $('#selected_quiz_passing_score').text(selectedOption.data('passing') || '0');
        $('#selectedQuizInfo').show();
        $('#saveQuizBtn').prop('disabled', false);
        $('#quiz_sort_order').val(getNextSortOrder());
    } else {
        $('#selectedQuizInfo').hide();
        $('#saveQuizBtn').prop('disabled', true);
    }
});

function saveQuizItem() {
    const quizId = $('#quiz_id').val();
    const selectedOption = $('#quiz_id option:selected');
    const quizTitle = selectedOption.text();
    const sortOrder = parseInt($('#quiz_sort_order').val()) || getNextSortOrder();

    if (!quizId) {
        toastr.error('يرجى اختيار اختبار');
        return;
    }

    const item = {
        item_type: 'quiz',
        quiz_id: quizId,
        title: quizTitle,
        description: selectedOption.data('desc') || '',
        duration: selectedOption.data('duration') || 0,
        passing_score: selectedOption.data('passing') || 0,
        sort_order: sortOrder,
        is_active: 1,
        is_free: 0,
        id: 'quiz_' + Date.now()
    };

    unitItems.push(item);
    displayUnitItems();

    $('#addQuizModal').modal('hide');
    resetQuizForm();

    toastr.success('تم إضافة الاختبار بنجاح');
}

function resetQuizForm() {
    $('#quiz_id').val('');
    $('#selectedQuizInfo').hide();
    $('#saveQuizBtn').prop('disabled', true);
    $('#quiz_sort_order').val('1');
}

function openNewQuizForm() {
    window.open('<?= ADMIN_URL ?>quizzes/add?course_id=' + currentCourseId, '_blank');
}

// Page Functions
function loadPages() {
    $('#page_id').html('<option value="">-- جاري تحميل الصفحات المتاحة --</option>');
    $('#savePageBtn').prop('disabled', true);

    $.get('<?= ADMIN_URL ?>units/get-available-pages', function(response) {
        if (response.success && response.pages.length > 0) {
            let options = '<option value="">اختر صفحة</option>';
            response.pages.forEach(function(page) {
                options += '<option value="' + page.id + '" data-title="' + page.title + '" data-desc="' + (page.description || 'لا يوجد وصف') + '" data-created="' + (page.created_at || '') + '" data-url="' + (page.url || '') + '">' + page.title + '</option>';
            });
            $('#page_id').html(options);
            $('#noPagesMessage').hide();
        } else {
            $('#page_id').html('<option value="">لا توجد صفحات متاحة</option>');
            $('#noPagesMessage').show();
        }
    }).fail(function() {
        $('#page_id').html('<option value="">خطأ في تحميل الصفحات</option>');
        toastr.error('حدث خطأ في تحميل الصفحات');
    });
}

// Page selection handler
$(document).on('change', '#page_id', function() {
    const selectedOption = $(this).find('option:selected');
    const pageId = selectedOption.val();

    if (pageId) {
        $('#selected_page_title').text(selectedOption.data('title') || selectedOption.text());
        $('#selected_page_desc').text(selectedOption.data('desc') || 'لا يوجد وصف');
        $('#selected_page_created').text(formatDate(selectedOption.data('created')) || 'غير محدد');

        const pageUrl = selectedOption.data('url');
        if (pageUrl) {
            $('#preview_page_link').attr('href', pageUrl).show();
        } else {
            $('#preview_page_link').hide();
        }

        $('#selectedPageInfo').show();
        $('#savePageBtn').prop('disabled', false);
        $('#page_sort_order').val(getNextSortOrder());
    } else {
        $('#selectedPageInfo').hide();
        $('#savePageBtn').prop('disabled', true);
    }
});

function savePageItem() {
    const pageId = $('#page_id').val();
    const selectedOption = $('#page_id option:selected');
    const pageTitle = selectedOption.text();
    const sortOrder = parseInt($('#page_sort_order').val()) || getNextSortOrder();

    if (!pageId) {
        toastr.error('يرجى اختيار صفحة');
        return;
    }

    const item = {
        item_type: 'page',
        page_id: pageId,
        title: pageTitle,
        description: selectedOption.data('desc') || '',
        url: selectedOption.data('url') || '',
        sort_order: sortOrder,
        is_active: 1,
        is_free: 0,
        id: 'page_' + Date.now()
    };

    unitItems.push(item);
    displayUnitItems();

    $('#addPageModal').modal('hide');
    resetPageForm();

    toastr.success('تم إضافة الصفحة بنجاح');
}

function resetPageForm() {
    $('#page_id').val('');
    $('#selectedPageInfo').hide();
    $('#savePageBtn').prop('disabled', true);
    $('#page_sort_order').val('1');
}

// Display Unit Items
function displayUnitItems() {
    if (unitItems.length === 0) {
        $('#unit-items-container').html('<div class="alert alert-info"><i class="fas fa-info-circle"></i> لا توجد عناصر مضافة بعد. اضغط على "إضافة عنصر جديد" لبدء إضافة العناصر.</div>');
        return;
    }

    let html = '<div class="table-responsive"><table class="table table-bordered"><thead><tr><th>الترتيب</th><th>النوع</th><th>العنوان</th><th>مجاني</th><th>الحالة</th><th>الإجراءات</th></tr></thead><tbody>';

    unitItems.forEach(function(item, index) {
        let typeIcon = '';
        let typeName = '';

        switch(item.item_type) {
            case 'video':
                typeIcon = '<i class="fas fa-video text-primary"></i>';
                typeName = 'فيديو';
                break;
            case 'quiz':
                typeIcon = '<i class="fas fa-question-circle text-warning"></i>';
                typeName = 'اختبار';
                break;
            case 'page':
                typeIcon = '<i class="fas fa-file-alt text-info"></i>';
                typeName = 'صفحة';
                break;
        }

        html += '<tr>';
        html += '<td><input type="number" class="form-control form-control-sm" value="' + item.sort_order + '" onchange="updateItemOrder(' + index + ', this.value)" style="width: 80px;" min="1"></td>';
        html += '<td>' + typeIcon + ' ' + typeName + '</td>';
        html += '<td>' + (item.title || 'بدون عنوان') + '</td>';
        html += '<td><div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="freeSwitch' + index + '" onclick="toggleItemFree(' + index + ', this.checked)" ' + (item.is_free == 1 ? 'checked' : '') + '><label class="custom-control-label" for="freeSwitch' + index + '"></label></div></td>';
        html += '<td><select class="form-control form-control-sm" onchange="toggleItemStatus(' + index + ', this.value)" style="width: 100px;"><option value="1"' + (item.is_active == 1 ? ' selected' : '') + '>مفعل</option><option value="0"' + (item.is_active == 0 ? ' selected' : '') + '>غير مفعل</option></select></td>';
        html += '<td><button class="btn btn-danger btn-sm" onclick="deleteItem(' + index + ')" title="حذف العنصر"><i class="fas fa-trash"></i></button> <button class="btn btn-info btn-sm" onclick="moveItemUp(' + index + ')" title="تحريك لأعلى" ' + (index === 0 ? 'disabled' : '') + '><i class="fas fa-arrow-up"></i></button> <button class="btn btn-info btn-sm" onclick="moveItemDown(' + index + ')" title="تحريك لأسفل" ' + (index === unitItems.length - 1 ? 'disabled' : '') + '><i class="fas fa-arrow-down"></i></button></td>';
        html += '</tr>';
    });

    html += '</tbody></table></div>';
    $('#unit-items-container').html(html);
}

function updateItemOrder(index, newOrder) {
    const order = parseInt(newOrder);
    if (order < 1) {
        toastr.error('الترتيب يجب أن يكون أكبر من 0');
        return;
    }
    unitItems[index].sort_order = order;
    toastr.success('تم تحديث الترتيب');
}

function toggleItemFree(index, isFree) {
    // Update the item in the array, making sure it's an integer
    unitItems[index].is_free = isFree ? 1 : 0;
    
    // Also update metadata if it exists to ensure consistency
    if (!unitItems[index].metadata) {
        unitItems[index].metadata = {};
    } else if (typeof unitItems[index].metadata === 'string') {
        try {
            unitItems[index].metadata = JSON.parse(unitItems[index].metadata);
        } catch(e) {}
    }
    
    // Optional: Log to verify it's updating
    console.log('Item updated:', index, 'is_free:', unitItems[index].is_free);
    
    const statusText = isFree ? 'مجاني' : 'مدفوع';
    toastr.success('تم تحديث العنصر ليصبح: ' + statusText);
}

function toggleItemStatus(index, status) {
    unitItems[index].is_active = parseInt(status);
    const statusText = status == 1 ? 'مفعل' : 'غير مفعل';
    toastr.success('تم تحديث حالة العنصر إلى: ' + statusText);
}

function moveItemUp(index) {
    if (index > 0) {
        const temp = unitItems[index];
        unitItems[index] = unitItems[index - 1];
        unitItems[index - 1] = temp;

        // Update sort orders
        unitItems[index].sort_order = index + 1;
        unitItems[index - 1].sort_order = index;

        displayUnitItems();
        toastr.success('تم تحريك العنصر لأعلى');
    }
}

function moveItemDown(index) {
    if (index < unitItems.length - 1) {
        const temp = unitItems[index];
        unitItems[index] = unitItems[index + 1];
        unitItems[index + 1] = temp;

        // Update sort orders
        unitItems[index].sort_order = index + 1;
        unitItems[index + 1].sort_order = index + 2;

        displayUnitItems();
        toastr.success('تم تحريك العنصر لأسفل');
    }
}

function deleteItem(index) {
    if (confirm('هل تريد حذف هذا العنصر؟')) {
        unitItems.splice(index, 1);
        // Renormalize sort orders after deletion
        unitItems.forEach(function(item, i) {
            item.sort_order = i + 1;
        });
        displayUnitItems();
        toastr.success('تم حذف العنصر');
    }
}

// Helper Functions
function getNextSortOrder() {
    return unitItems.length + 1;
}

function formatDuration(seconds) {
    if (!seconds) return 'غير محدد';
    const totalMinutes = Math.floor(seconds / 60);
    const remainingSeconds = seconds % 60;

    if (remainingSeconds > 0) {
        return totalMinutes + ':' + String(remainingSeconds).padStart(2, '0');
    } else {
        return totalMinutes + ':00';
    }
}

function formatFileSize(bytes) {
    if (!bytes) return 'غير محدد';
    const sizes = ['بايت', 'كيلوبايت', 'ميجابايت', 'جيجابايت'];
    const i = Math.floor(Math.log(bytes) / Math.log(1024));
    return Math.round(bytes / Math.pow(1024, i) * 100) / 100 + ' ' + sizes[i];
}

function formatDate(dateString) {
    if (!dateString) return 'غير محدد';
    const date = new Date(dateString);
    return date.toLocaleDateString('ar-SA');
}

function showSuccessMessage(message) {
    toastr.success(message);
}

function showErrorMessage(message) {
    toastr.error(message);
}
</script>

<?= $this->endSection(); ?>
