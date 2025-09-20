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
                        <li class="breadcrumb-item active">تفاصيل الوحدة</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <?= $this->include('admin_layout/admin_msg'); ?>

            <div class="row">
                <!-- Unit Information -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">معلومات الوحدة</h3>
                            <div class="card-tools">
                                <a href="<?= ADMIN_URL . 'units/edit/' . $unit->id ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> تعديل
                                </a>
                                <a href="<?= ADMIN_URL . 'units' ?>" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> العودة
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">اسم الوحدة:</th>
                                            <td><?= esc($unit->unit_name) ?></td>
                                        </tr>
                                        <tr>
                                            <th>الكورس:</th>
                                            <td>
                                                <a href="<?= ADMIN_URL . 'courses/show/' . $course->id ?>" class="text-primary">
                                                    <?= esc($course->course_title) ?>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>القسم:</th>
                                            <td><?= esc($section->section_name) ?></td>
                                        </tr>
                                        <tr>
                                            <th>ترتيب الوحدة:</th>
                                            <td><span class="badge badge-info"><?= $unit->sort_order ?></span></td>
                                        </tr>
                                        <tr>
                                            <th>الحالة:</th>
                                            <td>
                                                <?php if ($unit->active): ?>
                                                    <span class="badge badge-success">نشط</span>
                                                <?php else: ?>
                                                    <span class="badge badge-danger">غير نشط</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">معرف الفيديو:</th>
                                            <td>
                                                <?php if ($unit->video_id): ?>
                                                    <code><?= esc($unit->video_id) ?></code>
                                                <?php else: ?>
                                                    <span class="text-muted">غير محدد</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>مدة الفيديو:</th>
                                            <td>
                                                <?php if ($unit->video_duration): ?>
                                                    <span class="badge badge-primary"><?= esc($unit->video_duration) ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">غير محدد</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>وحدة معاينة:</th>
                                            <td>
                                                <?php if ($unit->is_free): ?>
                                                    <span class="badge badge-info">نعم</span>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary">لا</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>تاريخ الإنشاء:</th>
                                            <td><?= date('Y-m-d H:i', strtotime($unit->created_at)) ?></td>
                                        </tr>
                                        <tr>
                                            <th>آخر تحديث:</th>
                                            <td><?= date('Y-m-d H:i', strtotime($unit->updated_at)) ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="mt-3">
                                <h6>وصف الوحدة:</h6>
                                <div class="border p-3 bg-light rounded">
                                    <?= nl2br(esc($unit->unit_desc)) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Unit Items Management -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h3 class="card-title">عناصر الوحدة</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addItemModal">
                                    <i class="fas fa-plus"></i> إضافة عنصر جديد
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="unit-items-container">
                                <div class="text-center py-4">
                                    <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                                    <p class="text-muted mt-2">جاري تحميل العناصر...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Associated Quizzes (Legacy) -->
                    <div class="card mt-4" style="display: none;">
                        <div class="card-header">
                            <h3 class="card-title">الاختبارات المرتبطة (<?= count($quizzes) ?>)</h3>
                            <div class="card-tools">
                                <a href="<?= ADMIN_URL . 'units/edit/' . $unit->id ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> إدارة الاختبارات
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($quizzes)): ?>
                                <div class="row">
                                    <?php foreach ($quizzes as $quiz): ?>
                                        <div class="col-md-6 mb-3">
                                            <div class="card border-left-primary">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <h6 class="card-title mb-1">
                                                                <a href="<?= ADMIN_URL . 'quizzes/show/' . $quiz->id ?>" class="text-primary">
                                                                    <?= esc($quiz->quiz_title) ?>
                                                                </a>
                                                            </h6>
                                                            <p class="card-text text-muted small mb-2">
                                                                <?= esc(substr($quiz->quiz_desc, 0, 100)) ?><?= strlen($quiz->quiz_desc) > 100 ? '...' : '' ?>
                                                            </p>
                                                            <div class="d-flex flex-wrap">
                                                                <small class="badge badge-info mr-1">
                                                                    <i class="fas fa-clock"></i> <?= $quiz->time_limit ?> دقيقة
                                                                </small>
                                                                <small class="badge badge-success mr-1">
                                                                    <i class="fas fa-percentage"></i> <?= $quiz->passing_score ?>%
                                                                </small>
                                                                <?php if ($quiz->max_attempts): ?>
                                                                    <small class="badge badge-warning">
                                                                        <i class="fas fa-redo"></i> <?= $quiz->max_attempts ?> محاولات
                                                                    </small>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="<?= ADMIN_URL . 'quizzes/show/' . $quiz->id ?>">
                                                                    <i class="fas fa-eye"></i> عرض التفاصيل
                                                                </a>
                                                                <a class="dropdown-item" href="<?= ADMIN_URL . 'quizzes/edit/' . $quiz->id ?>">
                                                                    <i class="fas fa-edit"></i> تعديل
                                                                </a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item text-danger" href="#" onclick="removeQuizFromUnit(<?= $quiz->id ?>)">
                                                                    <i class="fas fa-unlink"></i> إلغاء الربط
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">لا توجد اختبارات مرتبطة</h5>
                                    <p class="text-muted">لم يتم ربط أي اختبارات بهذه الوحدة بعد</p>
                                    <a href="<?= ADMIN_URL . 'units/edit/' . $unit->id ?>" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> إضافة اختبارات
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-md-4">
                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">الإجراءات السريعة</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="<?= ADMIN_URL . 'units/edit/' . $unit->id ?>" class="btn btn-warning btn-block">
                                    <i class="fas fa-edit"></i> تعديل الوحدة
                                </a>
                                <a href="<?= base_url('courses/unit/' . $unit->id) ?>" target="_blank" class="btn btn-info btn-block">
                                    <i class="fas fa-external-link-alt"></i> معاينة الوحدة
                                </a>
                                <a href="<?= ADMIN_URL . 'quizzes/create?unit_id=' . $unit->id ?>" class="btn btn-success btn-block">
                                    <i class="fas fa-plus"></i> إضافة اختبار جديد
                                </a>
                                <button onclick="duplicateUnit(<?= $unit->id ?>)" class="btn btn-secondary btn-block">
                                    <i class="fas fa-copy"></i> نسخ الوحدة
                                </button>
                                <hr>
                                <button onclick="deleteUnit(<?= $unit->id ?>)" class="btn btn-danger btn-block">
                                    <i class="fas fa-trash"></i> حذف الوحدة
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Unit Statistics -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h3 class="card-title">إحصائيات الوحدة</h3>
                        </div>
                        <div class="card-body">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">المشاهدات</span>
                                    <span class="info-box-number" id="unit-views">-</span>
                                </div>
                            </div>

                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">مكتملة</span>
                                    <span class="info-box-number" id="unit-completions">-</span>
                                </div>
                            </div>

                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-percentage"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">معدل الإكمال</span>
                                    <span class="info-box-number" id="completion-rate">-</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h3 class="card-title">التنقل</h3>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <a href="<?= ADMIN_URL . 'courses/show/' . $course->id ?>" class="list-group-item list-group-item-action">
                                    <i class="fas fa-book"></i> عرض الكورس
                                </a>
                                <a href="<?= ADMIN_URL . 'sections/show/' . $section->id ?>" class="list-group-item list-group-item-action">
                                    <i class="fas fa-folder"></i> عرض القسم
                                </a>
                                <a href="<?= ADMIN_URL . 'units?course=' . $course->id ?>" class="list-group-item list-group-item-action">
                                    <i class="fas fa-list"></i> وحدات الكورس
                                </a>
                                <a href="<?= ADMIN_URL . 'progress/unit-analytics/' . $unit->id ?>" class="list-group-item list-group-item-action">
                                    <i class="fas fa-chart-bar"></i> تحليلات التقدم
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    loadUnitStatistics();
});

function loadUnitStatistics() {
    $.get('<?= ADMIN_URL ?>units/statistics/<?= $unit->id ?>', function(stats) {
        $('#unit-views').text(stats.views || 0);
        $('#unit-completions').text(stats.completions || 0);
        $('#completion-rate').text((stats.completion_rate || 0) + '%');
    }).fail(function() {
        $('#unit-views, #unit-completions').text('N/A');
        $('#completion-rate').text('N/A');
    });
}

function deleteUnit(id) {
    if (confirm('هل أنت متأكد من حذف هذه الوحدة؟ سيتم حذف جميع البيانات المرتبطة بها.')) {
        $.post('<?= ADMIN_URL ?>units/deleteUnit/' + id, {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        }, function(response) {
            if (response.success) {
                window.location.href = '<?= ADMIN_URL ?>units';
            } else {
                toastr.error('حدث خطأ أثناء حذف الوحدة');
            }
        }).fail(function() {
            toastr.error('حدث خطأ في الاتصال');
        });
    }
}

function duplicateUnit(id) {
    if (confirm('هل تريد إنشاء نسخة من هذه الوحدة؟')) {
        $.post('<?= ADMIN_URL ?>units/duplicate/' + id, {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        }, function(response) {
            if (response.success) {
                toastr.success('تم إنشاء نسخة من الوحدة بنجاح');
                setTimeout(function() {
                    window.location.href = '<?= ADMIN_URL ?>units/edit/' + response.new_unit_id;
                }, 1500);
            } else {
                toastr.error('حدث خطأ أثناء نسخ الوحدة');
            }
        }).fail(function() {
            toastr.error('حدث خطأ في الاتصال');
        });
    }
}

function removeQuizFromUnit(quizId) {
    if (confirm('هل تريد إلغاء ربط هذا الاختبار من الوحدة؟')) {
        $.post('<?= ADMIN_URL ?>units/remove-quiz', {
            unit_id: <?= $unit->id ?>,
            quiz_id: quizId,
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        }, function(response) {
            if (response.success) {
                toastr.success('تم إلغاء ربط الاختبار بنجاح');
                location.reload();
            } else {
                toastr.error('حدث خطأ أثناء إلغاء الربط');
            }
        }).fail(function() {
            toastr.error('حدث خطأ في الاتصال');
        });
    }
}
// Unit Items Management Functions
let currentUnitId = <?= $unit->id ?>;

// Load unit items on page load
$(document).ready(function() {
    loadUnitItems();
});

function loadUnitItems() {
    $.get('<?= ADMIN_URL ?>units/get-items/' + currentUnitId, function(response) {
        if (response.success) {
            displayUnitItems(response.items);
        } else {
            $('#unit-items-container').html('<div class="alert alert-warning">لا توجد عناصر في هذه الوحدة</div>');
        }
    }).fail(function() {
        $('#unit-items-container').html('<div class="alert alert-danger">حدث خطأ في تحميل العناصر</div>');
    });
}

function displayUnitItems(items) {
    if (items.length === 0) {
        $('#unit-items-container').html('<div class="alert alert-info">لا توجد عناصر في هذه الوحدة بعد</div>');
        return;
    }

    let html = '<div class="table-responsive"><table class="table table-bordered"><thead><tr><th>الترتيب</th><th>النوع</th><th>العنوان</th><th>الحالة</th><th>الإجراءات</th></tr></thead><tbody>';

    items.forEach(function(item) {
        let typeIcon = '';
        let typeName = '';

        switch(item.item_type) {
            case 'video':
                typeIcon = '<i class="fas fa-video text-primary"></i>';
                typeName = 'فيديو';
                break;
            case 'quiz':
                typeIcon = '<i class="fas fa-question-circle text-warning"></i>';
                typeName = 'كويز';
                break;
            case 'page':
                typeIcon = '<i class="fas fa-file-alt text-info"></i>';
                typeName = 'صفحة';
                break;
        }

        html += '<tr>';
        html += '<td><input type="number" class="form-control form-control-sm" value="' + item.sort_order + '" onchange="updateItemOrder(' + item.id + ', this.value)" style="width: 80px;"></td>';
        html += '<td>' + typeIcon + ' ' + typeName + '</td>';
        html += '<td>' + (item.title || item.video_title || 'بدون عنوان') + '</td>';
        html += '<td><select class="form-control form-control-sm" onchange="toggleItemStatus(' + item.id + ', this.value)"><option value="1"' + (item.is_active == 1 ? ' selected' : '') + '>مفعل</option><option value="0"' + (item.is_active == 0 ? ' selected' : '') + '>غير مفعل</option></select></td>';
        html += '<td><button class="btn btn-danger btn-sm" onclick="deleteItem(' + item.id + ')"><i class="fas fa-trash"></i></button></td>';
        html += '</tr>';
    });

    html += '</tbody></table></div>';
    $('#unit-items-container').html(html);
}

function updateItemOrder(itemId, newOrder) {
    $.post('<?= ADMIN_URL ?>units/update-item-order', {
        item_id: itemId,
        sort_order: newOrder,
        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
    }, function(response) {
        if (response.success) {
            toastr.success('تم تحديث الترتيب بنجاح');
            loadUnitItems();
        } else {
            toastr.error('حدث خطأ في تحديث الترتيب');
        }
    });
}

function toggleItemStatus(itemId, status) {
    $.post('<?= ADMIN_URL ?>units/toggle-item-status', {
        item_id: itemId,
        is_active: status,
        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
    }, function(response) {
        if (response.success) {
            toastr.success('تم تحديث حالة العنصر بنجاح');
        } else {
            toastr.error('حدث خطأ في تحديث الحالة');
        }
    });
}

function deleteItem(itemId) {
    if (confirm('هل تريد حذف هذا العنصر؟')) {
        $.post('<?= ADMIN_URL ?>units/delete-item', {
            item_id: itemId,
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        }, function(response) {
            if (response.success) {
                toastr.success('تم حذف العنصر بنجاح');
                loadUnitItems();
            } else {
                toastr.error('حدث خطأ في حذف العنصر');
            }
        });
    }
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

// Helper function to format duration (in minutes)
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

// Video Functions
function fetchVideoData() {
    const videoId = $('#video_id').val();
    if (!videoId) {
        toastr.error('يرجى إدخال معرف الفيديو');
        return;
    }

    $('#fetch-video-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> جاري الجلب...');

    $.post('<?= ADMIN_URL ?>units/fetch-video-data', {
        video_id: videoId,
        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
    }, function(response) {
        if (response.success) {
            // Store complete video data globally
            currentVideoData = response.data;
            
            $('#video_title').val(response.data.title);
            $('#video_duration').val(response.data.video_duration || formatDuration(response.data.duration));
            $('#video_thumbnail').val(response.data.thumbnail);
            toastr.success('تم جلب بيانات الفيديو بنجاح');
        } else {
            toastr.error('حدث خطأ في جلب بيانات الفيديو');
        }
    }).fail(function() {
        toastr.error('حدث خطأ في الاتصال');
    }).always(function() {
        $('#fetch-video-btn').prop('disabled', false).html('جلب البيانات');
    });
}

function saveVideoItem() {
    if (!currentVideoData) {
        toastr.error('يرجى جلب بيانات الفيديو أولاً');
        return;
    }
    
    const formData = {
        unit_id: currentUnitId,
        item_type: 'video',
        video_id: $('#video_id').val(),
        video_title: $('#video_title').val(),
        title: $('#video_title').val(),
        duration: currentVideoData.duration || 0,
        video_duration: $('#video_duration').val(),
        thumbnail: $('#video_thumbnail').val(),
        video_thumbnail: $('#video_thumbnail').val(),
        collection_id: currentVideoData.collection_id || '',
        video_library_id: currentVideoData.video_library_id || '',
        file_size: currentVideoData.file_size || 0,
        video_quality: currentVideoData.height ? currentVideoData.width + 'x' + currentVideoData.height : null,
        width: currentVideoData.width || 0,
        height: currentVideoData.height || 0,
        framerate: currentVideoData.framerate || 0,
        description: currentVideoData.description || '',
        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
    };

    $.post('<?= ADMIN_URL ?>units/add-item', formData, function(response) {
        if (response.success) {
            toastr.success('تم إضافة الفيديو بنجاح');
            $('#addVideoModal').modal('hide');
            $('#addVideoForm')[0].reset();
            currentVideoData = null; // Clear stored video data
            loadUnitItems();
        } else {
            toastr.error('حدث خطأ في إضافة الفيديو');
        }
    });
}

// Quiz Functions
function loadQuizzes() {
    $.get('<?= ADMIN_URL ?>units/get-available-quizzes/<?= $unit->course_id ?>', function(response) {
        if (response.success) {
            let options = '<option value="">اختر كويز</option>';
            response.quizzes.forEach(function(quiz) {
                options += '<option value="' + quiz.id + '">' + quiz.title + '</option>';
            });
            $('#quiz_id').html(options);
        }
    });
}

function saveQuizItem() {
    const quizId = $('#quiz_id').val();
    if (!quizId) {
        toastr.error('يرجى اختيار كويز');
        return;
    }

    $.post('<?= ADMIN_URL ?>units/add-item', {
        unit_id: currentUnitId,
        item_type: 'quiz',
        quiz_id: quizId,
        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
    }, function(response) {
        if (response.success) {
            toastr.success('تم إضافة الكويز بنجاح');
            $('#addQuizModal').modal('hide');
            loadUnitItems();
        } else {
            toastr.error('حدث خطأ في إضافة الكويز');
        }
    });
}

// Page Functions
function loadPages() {
    $.get('<?= ADMIN_URL ?>units/get-available-pages', function(response) {
        if (response.success) {
            let options = '<option value="">اختر صفحة</option>';
            response.pages.forEach(function(page) {
                options += '<option value="' + page.id + '">' + page.title + '</option>';
            });
            $('#page_id').html(options);
        }
    });
}

function savePageItem() {
    const pageId = $('#page_id').val();
    if (!pageId) {
        toastr.error('يرجى اختيار صفحة');
        return;
    }

    $.post('<?= ADMIN_URL ?>units/add-item', {
        unit_id: currentUnitId,
        item_type: 'page',
        page_id: pageId,
        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
    }, function(response) {
        if (response.success) {
            toastr.success('تم إضافة الصفحة بنجاح');
            $('#addPageModal').modal('hide');
            loadUnitItems();
        } else {
            toastr.error('حدث خطأ في إضافة الصفحة');
        }
    });
}
</script>

<!-- Add Item Type Selection Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">اختر نوع العنصر</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card text-center" style="cursor: pointer;" onclick="selectItemType('video')">
                            <div class="card-body">
                                <i class="fas fa-video fa-3x text-primary mb-3"></i>
                                <h5>فيديو</h5>
                                <p class="text-muted">إضافة فيديو من bunny.net</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center" style="cursor: pointer;" onclick="selectItemType('quiz')">
                            <div class="card-body">
                                <i class="fas fa-question-circle fa-3x text-warning mb-3"></i>
                                <h5>كويز</h5>
                                <p class="text-muted">إضافة اختبار موجود</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center" style="cursor: pointer;" onclick="selectItemType('page')">
                            <div class="card-body">
                                <i class="fas fa-file-alt fa-3x text-info mb-3"></i>
                                <h5>صفحة إضافية</h5>
                                <p class="text-muted">إضافة صفحة موجودة</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Video Modal -->
<div class="modal fade" id="addVideoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">إضافة فيديو</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addVideoForm">
                    <div class="form-group">
                        <label>معرف الفيديو *</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="video_id" placeholder="أدخل معرف الفيديو">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-info" id="fetch-video-btn" onclick="fetchVideoData()">جلب البيانات</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>عنوان الفيديو</label>
                        <input type="text" class="form-control" id="video_title" readonly>
                    </div>
                    <div class="form-group">
                        <label>مدة الفيديو (بالثواني)</label>
                        <input type="number" class="form-control" id="video_duration" readonly>
                    </div>
                    <div class="form-group">
                        <label>صورة الفيديو</label>
                        <input type="text" class="form-control" id="video_thumbnail" readonly>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" onclick="saveVideoItem()">حفظ</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Quiz Modal -->
<div class="modal fade" id="addQuizModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">إضافة كويز</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>اختر الكويز *</label>
                    <select class="form-control" id="quiz_id">
                        <option value="">جاري التحميل...</option>
                    </select>
                </div>
                <div class="text-center mt-3">
                    <a href="<?= ADMIN_URL ?>quizzes/add" class="btn btn-success" target="_blank">
                        <i class="fas fa-plus"></i> إنشاء كويز جديد
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" onclick="saveQuizItem()">حفظ</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Page Modal -->
<div class="modal fade" id="addPageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">إضافة صفحة</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>اختر الصفحة *</label>
                    <select class="form-control" id="page_id">
                        <option value="">جاري التحميل...</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" onclick="savePageItem()">حفظ</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
