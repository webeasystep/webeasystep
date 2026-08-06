<?= $this->extend('admin_layout/main') ?>
<?= $this->section('content') ?>

<div class="row mb-3">
    <div class="col-12">
        <h3 class="mb-0"><?= esc($title) ?></h3>
    </div>
</div>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="<?= current_url() ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>عنوان الباقة <span class="text-danger">*</span></label>
                    <input type="text" name="bundle_title" class="form-control" value="<?= old('bundle_title', isset($bundle) ? $bundle->bundle_title : '') ?>" required>
                </div>
                
                <div class="col-md-6 form-group">
                    <label>الرابط (Slug) <span class="text-danger">*</span></label>
                    <input type="text" name="slug" class="form-control" value="<?= old('slug', isset($bundle) ? $bundle->slug : '') ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label>وصف الباقة</label>
                <textarea name="description" class="form-control" rows="4"><?= old('description', isset($bundle) ? $bundle->description : '') ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-4 form-group">
                    <label>سعر الباقة <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="bundle_price" class="form-control" value="<?= old('bundle_price', isset($bundle) ? $bundle->bundle_price : '') ?>" required>
                </div>
                
                <div class="col-md-4 form-group">
                    <label>الترتيب</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= old('sort_order', isset($bundle) ? $bundle->sort_order : 0) ?>">
                </div>

                <div class="col-md-4 form-group">
                    <label>الصورة</label>
                    <input type="file" name="image" class="form-control-file" accept="image/*">
                    <?php if (isset($bundle) && $bundle->image): ?>
                        <div class="mt-2">
                            <img src="<?= base_url('uploads/courses/' . $bundle->image) ?>" width="100" style="border-radius:5px;">
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group mt-3 border p-3" style="border-radius: 5px; background: #f9f9f9;">
                <label class="font-weight-bold">المواد المشمولة في الباقة:</label>
                <div class="row mt-2">
                    <?php foreach ($courses as $course): ?>
                        <div class="col-md-6 mb-2">
                            <div class="custom-control custom-checkbox">
                                <?php 
                                    $checked = '';
                                    if (isset($selectedCourses) && in_array($course->id, $selectedCourses)) {
                                        $checked = 'checked';
                                    } elseif (is_array(old('courses')) && in_array($course->id, old('courses'))) {
                                        $checked = 'checked';
                                    }
                                ?>
                                <input type="checkbox" class="custom-control-input" id="course_<?= $course->id ?>" name="courses[]" value="<?= $course->id ?>" <?= $checked ?>>
                                <label class="custom-control-label" for="course_<?= $course->id ?>"><?= esc($course->course_title) ?> - ($<?= $course->course_price ?>)</label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-group">
                <div class="custom-control custom-switch">
                    <?php 
                        $isActive = isset($bundle) ? $bundle->is_active : 1;
                        if (old('is_active') !== null) $isActive = old('is_active');
                    ?>
                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" <?= $isActive ? 'checked' : '' ?>>
                    <label class="custom-control-label" for="is_active">تفعيل الباقة</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> حفظ الباقة</button>
            <a href="<?= site_url('admin/bundles') ?>" class="btn btn-secondary">إلغاء</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
