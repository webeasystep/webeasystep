<?php $this->extend('site_layout/template'); ?>
<?php $this->section('content'); ?>

<div class="site-section bg-light">
    <div class="container">
        <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="100">
            <div class="col-lg-10">
                
                <!-- Quiz Header -->
                <div class="feature-1 border mb-4 text-center" style="background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark, #0056b3) 100%); color: white;">
                    <div class="feature-1-content">
                        <h1 class="display-4 mb-3"><?= esc($quiz->quiz_title) ?></h1>
                        <?php if ($quiz->quiz_desc): ?>
                            <p class="lead mb-0" style="opacity: 0.9;"><?= esc($quiz->quiz_desc) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quiz Information Card -->
                <div class="feature-1 border">
                    <div class="feature-1-content">
                        
                        <!-- Quiz Statistics -->
                        <div class="row mb-4">
                            <div class="col-md-3 col-6 mb-3">
                                <div class="text-center p-3 bg-light border rounded">
                                    <i class="icon-help display-6 text-primary mb-2"></i>
                                    <div class="h4 mb-1"><?= count(json_decode($quiz->quiz_questions ?? '[]', true)) ?></div>
                                    <small class="text-muted">عدد الأسئلة</small>
                                </div>
                            </div>
                            
                            <div class="col-md-3 col-6 mb-3">
                                <div class="text-center p-3 bg-light border rounded">
                                    <i class="icon-clock display-6 text-warning mb-2"></i>
                                    <div class="h4 mb-1">
                                        <?= $quiz->time_limit ? $quiz->time_limit . ' دقيقة' : 'غير محدود' ?>
                                    </div>
                                    <small class="text-muted">الوقت المحدد</small>
                                </div>
                            </div>
                            
                            <div class="col-md-3 col-6 mb-3">
                                <div class="text-center p-3 bg-light border rounded">
                                    <i class="icon-percent display-6 text-success mb-2"></i>
                                    <div class="h4 mb-1"><?= $quiz->passing_score ?>%</div>
                                    <small class="text-muted">درجة النجاح</small>
                                </div>
                            </div>
                            
                            <div class="col-md-3 col-6 mb-3">
                                <div class="text-center p-3 bg-light border rounded">
                                    <i class="icon-refresh display-6 text-info mb-2"></i>
                                    <div class="h4 mb-1">
                                        <?= $quiz->max_attempts ? $quiz->max_attempts : 'غير محدود' ?>
                                    </div>
                                    <small class="text-muted">عدد المحاولات</small>
                                </div>
                            </div>
                        </div>

                        <!-- Attempt Information -->
                        <?php if (isset($attempt_count) && $attempt_count > 0): ?>
                            <div class="alert alert-info text-center mb-4">
                                <i class="icon-info-circle"></i>
                                <span class="fw-semibold">
                                    هذه هي محاولتك رقم <?= $attempt_count + 1 ?> من أصل <?= $quiz->max_attempts ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <!-- Quiz Instructions -->
                        <div class="alert alert-warning mb-4">
                            <h5 class="alert-heading mb-3">
                                <i class="icon-exclamation-triangle"></i> تعليمات مهمة
                            </h5>
                            <ul class="mb-0 text-end">
                                <li class="mb-2">اقرأ كل سؤال بعناية قبل الإجابة</li>
                                <?php if ($quiz->time_limit): ?>
                                    <li class="mb-2">لديك <?= $quiz->time_limit ?> دقيقة لإكمال الاختبار</li>
                                <?php endif; ?>
                                <li class="mb-2">تأكد من إجابتك قبل الانتقال للسؤال التالي</li>
                                <li class="mb-2">يمكنك مراجعة إجاباتك قبل التسليم النهائي</li>
                                <?php if ($quiz->max_attempts > 1): ?>
                                    <li class="mb-2">يمكنك إعادة المحاولة <?= $quiz->max_attempts ?> مرات كحد أقصى</li>
                                <?php endif; ?>
                                <li class="mb-0">تحتاج إلى <?= $quiz->passing_score ?>% للنجاح في الاختبار</li>
                            </ul>
                        </div>

                        <!-- Start Quiz Button -->
                        <div class="text-center">
                            <?php if ($attempt_count < $quiz->max_attempts): ?>
                                <form method="POST" action="<?= site_url('quizzes/start/' . $quiz->id) ?>">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-success btn-lg px-5 py-3">
                                        <i class="icon-play"></i> ابدأ الاختبار
                                    </button>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <i class="icon-exclamation-triangle"></i>
                                    لقد استنفدت جميع المحاولات المسموحة لهذا الاختبار
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>