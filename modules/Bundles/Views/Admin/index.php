<?= $this->extend('admin_layout/main') ?>
<?= $this->section('content') ?>

<div class="row mb-3">
    <div class="col-6">
        <h3 class="mb-0">إدارة الباقات</h3>
    </div>
    <div class="col-6 text-left">
        <a href="<?= site_url('admin/bundles/add') ?>" class="btn btn-primary"><i class="fa fa-plus"></i> إضافة باقة</a>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>صورة</th>
                        <th>العنوان</th>
                        <th>المواد</th>
                        <th>السعر الأصلي</th>
                        <th>سعر الباقة</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bundles as $bundle): ?>
                        <tr>
                            <td><?= $bundle->id ?></td>
                            <td>
                                <?php if ($bundle->image): ?>
                                    <img src="<?= base_url('uploads/courses/' . $bundle->image) ?>" width="50" height="50" style="object-fit:cover; border-radius:5px;">
                                <?php endif; ?>
                            </td>
                            <td><?= esc($bundle->bundle_title) ?></td>
                            <td>
                                <?php 
                                    $coursesModel = new \Modules\Courses\Models\CoursesModel();
                                    $bModel = new \Modules\Bundles\Models\BundlesModel();
                                    $cIds = $bModel->getBundleCourseIds($bundle->id);
                                    echo count($cIds);
                                ?>
                            </td>
                            <td>$<?= $bundle->original_price ?></td>
                            <td>$<?= $bundle->bundle_price ?></td>
                            <td>
                                <?php if ($bundle->is_active): ?>
                                    <span class="badge badge-success">مفعل</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">معطل</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= site_url('admin/bundles/edit/' . $bundle->id) ?>" class="btn btn-sm btn-info" title="تعديل"><i class="fa fa-edit"></i></a>
                                <a href="<?= site_url('admin/bundles/delete/' . $bundle->id) ?>" class="btn btn-sm btn-danger" title="حذف" onclick="return confirm('هل أنت متأكد من الحذف؟');"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
