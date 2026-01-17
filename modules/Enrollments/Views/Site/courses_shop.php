<?= $this->extend('site_layout/template'); ?>
<?= $this->section('content'); ?>

<style>
    .courses-shop-section {
        padding: 60px 0;
        background-color: #f8f9fa;
    }
    .course-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        margin-bottom: 30px;
    }
    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    .course-image {
        height: 180px;
        object-fit: cover;
        width: 100%;
    }
    .course-body {
        padding: 20px;
    }
    .course-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #343a40;
        margin-bottom: 10px;
    }
    .course-desc {
        color: #6c757d;
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 15px;
    }
    .course-price {
        font-size: 1.3rem;
        font-weight: 700;
        color: #ec661f;
    }
    .course-price.free {
        color: #28a745;
    }
    .btn-enroll {
        background: linear-gradient(135deg, #ec661f 0%, #d4541a 100%);
        border: none;
        padding: 10px 25px;
        font-weight: 600;
        border-radius: 8px;
        color: white;
    }
    .btn-enroll:hover {
        background: linear-gradient(135deg, #d4541a 0%, #c04717 100%);
        color: white;
    }
    .btn-enroll.free {
        background: linear-gradient(135deg, #28a745 0%, #218838 100%);
    }
    .page-header {
        background: linear-gradient(135deg, #ec661f 0%, #d4541a 100%);
        color: white;
        padding: 40px 0;
        margin-bottom: 40px;
    }
</style>

<div class="page-header text-center">
    <div class="container">
        <h1>الدورات المتاحة</h1>
        <p class="mb-0">اختر الدورة التي تناسبك وابدأ رحلة التعلم</p>
    </div>
</div>

<section class="courses-shop-section">
    <div class="container">
        <?= $this->include('site_layout/site_msg'); ?>
        
        <?php if (empty($courses)): ?>
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>
                لا توجد دورات متاحة حالياً
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($courses as $course): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="course-card card">
                            <?php 
                            $image = !empty($course->image) ? json_decode($course->image, true) : null;
                            $imageUrl = $image ? base_url('uploads/courses/' . (is_array($image) ? $image[0] : $image)) : base_url('assets/images/course-placeholder.jpg');
                            ?>
                            <img src="<?= $imageUrl ?>" alt="<?= esc($course->course_title) ?>" class="course-image">
                            <div class="course-body">
                                <h5 class="course-title"><?= esc($course->course_title) ?></h5>
                                <p class="course-desc"><?= esc(mb_substr($course->short_desc ?? $course->course_desc ?? '', 0, 100)) ?>...</p>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <?php if ($course->is_free || $course->course_price <= 0): ?>
                                        <span class="course-price free">مجاني</span>
                                        <a href="<?= site_url('enrollments/purchase-course/' . $course->id) ?>" class="btn btn-enroll free">
                                            <i class="fas fa-gift me-1"></i> اشترك مجاناً
                                        </a>
                                    <?php else: ?>
                                        <span class="course-price"><?= number_format($course->course_price, 2) ?> جنيه</span>
                                        <a href="<?= site_url('enrollments/purchase-course/' . $course->id) ?>" class="btn btn-enroll">
                                            <i class="fas fa-shopping-cart me-1"></i> اشتري الآن
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?= $this->endSection(); ?>
