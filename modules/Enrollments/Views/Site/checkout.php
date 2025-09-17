<?= $this->extend('site_layout/template'); ?>
<?= $this->section('content'); ?>

<style>
    .payment-section {
        padding: 60px 0;
        background-color: #f8f9fa;
    }
    .card {
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    .course-info-block {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 30px;
        text-align: center;
    }
    .course-img {
        width: 100%;
        height: auto;
        border-radius: 8px;
        margin-bottom: 15px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    }
    .course-info-block h4 {
        margin-bottom: 10px;
        font-size: 1.3rem;
        color: #343a40;
    }
    .course-price {
        font-size: 1.2rem;
        color: #ec661f;
        margin-bottom: 0;
        font-weight: 700;
    }
    .btn-complete {
        display: block;
        width: 100%;
        font-size: 1.1rem;
        font-weight: 600;
        padding: 16px 0;
        border-radius: 8px;
        background: linear-gradient(90deg, #136ad5 0%, #1573e8 100%);
        border: none;
        color: #fff;
        transition: all 0.3s ease;
    }
    .btn-complete:hover {
        background: linear-gradient(90deg, #1573e8 0%, #136ad5 100%);
        opacity: 0.9;
    }
</style>

<div class="payment-section">
    <div class="container">
        <?= $this->include('site_layout/site_msg'); ?>
        <!-- عرض معلومات الدورة -->
        <div class="course-info-block">
            <img src="<?= base_url() ?>site/images/img-school-3-min.jpg" alt="صورة الدورة" class="course-img">
            <h4><?= esc($course->course_title ?? 'اسم الدورة') ?></h4>
            <?php if ($course->is_free): ?>
                <p class="course-price">مجاناً</p>
            <?php else: ?>
                <p class="course-price">السعر: $<?= esc($course->price ?? '99') ?></p>
            <?php endif; ?>
        </div>

        <?php if ($course->is_free): ?>
            <!-- سيناريو الدورة المجانية -->
            <div class="card p-4 text-center">
                <h2>الدورة مجانية!</h2>
                <?php if ($isLoggedIn): ?>
                    <p>يمكنك الانضمام فوراً.</p>
                    <!-- Use slug or ID as needed -->
                    <form action="<?= site_url('checkout/'.$course->id) ?>" method="post">
                        <input type="hidden" name="course_id" value="<?= esc($course->id) ?>">
                        <button type="submit" class="btn-complete">انضم الآن</button>
                    </form>
                <?php else: ?>
                    <p>يرجى تسجيل الدخول أو إنشاء حساب للانضمام.</p>
                    <a href="<?= site_url('login') ?>" class="btn btn-primary mb-2">تسجيل الدخول</a>
                    <a href="<?= site_url('register') ?>" class="btn btn-outline-primary">إنشاء حساب</a>
                <?php endif; ?>
            </div>

        <?php elseif (!empty($course->waiting_list) && $course->waiting_list == 1): ?>
            <!-- سيناريو قائمة الانتظار -->
            <div class="card p-4">
                <h2 class="mb-4 text-center">القائمة الانتظارية</h2>
                <p class="text-center">هذه الدورة غير متاحة حالياً. اترك بياناتك لنراسلك عند توفرها.</p>
                <!-- POST to checkout -->
                <form action="<?= site_url('checkout/'.$course->id) ?>" method="post">
                    <input type="hidden" name="course_id" value="<?= esc($course->id) ?>">
                    <div class="form-group">
                        <label for="name">الاسم الكامل</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="أدخل اسمك الكامل">
                    </div>
                    <div class="form-group">
                        <label for="email">البريد الإلكتروني</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="أدخل بريدك الإلكتروني">
                    </div>
                    <button type="submit" class="btn-complete">إرسال</button>
                </form>
            </div>

        <?php else: ?>
            <!-- سيناريو دورة مدفوعة -->
            <?php if ($isLoggedIn): ?>
                <!-- المستخدم مسجل دخول -->
                <div class="card p-4">
                    <h2 class="mb-4 text-center">إتمام الدفع</h2>
                    <!-- Only show proof upload for paid courses -->
                    <form action="<?= site_url('checkout/'.$course->id) ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="course_id" value="<?= esc($course->id) ?>">
                        <div class="mb-4 text-center">
                            <p>يرجى تحويل المبلغ إلى حساب Instapay:</p>
                            <p><strong>fakhr@instapay</strong></p>
                        </div>

                        <div class="form-group row">
                            <label for="dropzone1" class="col-sm-3 col-form-label"><?= lang('Enrollments.attach_payment_proof') ?>:</label>
                            <div class="col-sm-9">
                                <div class="fireupload" id="dropzone1"></div>
                                <small class="invalid-feedback"></small>
                            </div>
                        </div>

                        <button type="submit" class="btn-complete">إتمام الدفع</button>
                    </form>
                </div>
            <?php else: ?>
                <!-- المستخدم غير مسجل => حقول التسجيل + الدفع -->
                <div class="row">
                    <div class="col-md-6 mb-5">
                        <div class="card p-4">
                            <h2 class="mb-4 text-center">تسجيل بياناتك</h2>
                            <div class="form-group">
                                <label for="name">الاسم الكامل</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="أدخل اسمك الكامل">
                            </div>
                            <div class="form-group">
                                <label for="email">البريد الإلكتروني</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="أدخل بريدك الإلكتروني">
                            </div>
                            <div class="form-group">
                                <label for="country">الدولة</label>
                                <input type="text" class="form-control" id="country" name="country" placeholder="أدخل دولتك">
                            </div>
                            <div class="form-group">
                                <label for="phone">رقم الهاتف</label>
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="أدخل رقم هاتفك">
                            </div>
                            <div class="form-group">
                                <label for="password">كلمة المرور</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="أدخل كلمة المرور">
                            </div>
                            <div class="form-group">
                                <label for="confirmPassword">تأكيد كلمة المرور</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="أكد كلمة المرور">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card p-4">
                            <h2 class="mb-4 text-center">الدفع</h2>
                            <form action="<?= site_url('checkout/'.$course->id) ?>" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="course_id" value="<?= esc($course->id) ?>">
                                <div class="text-center mb-4">
                                    <p>يرجى تحويل المبلغ إلى حساب Instapay:</p>
                                    <p><strong>fakhr@instapay</strong></p>
                                </div>
                                <div class="form-group row">
                                    <label for="attachment" class="col-sm-3 col-form-label"><?= lang('Enrollments.payment_proof') ?></label>
                                    <div class="col-sm-9">
                                        <div class="fireupload" id="dropzone1"></div>
                                        <small class="invalid-feedback"></small>
                                    </div>
                                </div>
                                <button type="submit" class="btn-complete">إتمام الدفع</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<script src="https://unpkg.com/sortablejs@1.13.0/Sortable.min.js"></script>
<link rel="stylesheet" href="<?= base_url('admin/plugins/fireuploader/fireupload.css') ?>">
<script type="text/javascript" src="<?= base_url('admin/plugins/fireuploader/fireupload.js') ?>"></script>
<script>
    $(document).ready(function () {
        // Initialize FireUploader only if NOT free
        <?php if (!$course->is_free): ?>
        var uploader1 = new FireUploader({
            dropzoneId: 'dropzone1',
            inputName: "proof_image[]",
            multipleFiles: false,
            allowedExtensions: ["jpg", "pdf", "jpeg", "png"],
            files: <?= json_encode($files ?? '[]') ?>
        });
        <?php endif; ?>
    });
</script>
<?= $this->endSection(); ?>
