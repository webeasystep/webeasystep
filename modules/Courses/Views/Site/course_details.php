<?php $this->extend('site_layout/template'); ?>
<?php $this->section('content'); ?>

<link rel="stylesheet" href="<?= base_url('site/css/course_details_styles.css') ?>?v=<?= time() ?>">

<!-- Main Section with Modern Design -->
<div class="untree_co-section">
    <div class="container">
        <!-- Enhanced Course Header -->
        <div class="course-header-wrapper" data-aos="fade-up" data-aos-delay="100" style="text-align: right; padding-top: 3rem; padding-bottom: 3rem;">
            
            <!-- Status Badge -->
            <div style="display: flex; justify-content: center; margin-bottom: 1.5rem;">
                <span style="background-color: <?= (isset($course->is_open) && $course->is_open == 1) ? 'rgba(40, 167, 69, 0.1)' : 'rgba(220, 53, 69, 0.1)' ?>; color: <?= (isset($course->is_open) && $course->is_open == 1) ? '#28a745' : '#dc3545' ?>; padding: 6px 14px; border-radius: 9999px; font-size: 0.85rem; font-weight: 700; border: 1px solid <?= (isset($course->is_open) && $course->is_open == 1) ? 'rgba(40, 167, 69, 0.2)' : 'rgba(220, 53, 69, 0.2)' ?>; display: inline-flex; align-items: center; gap: 8px;">
                    <span style="width: 6px; height: 6px; border-radius: 50%; background-color: <?= (isset($course->is_open) && $course->is_open == 1) ? '#28a745' : '#dc3545' ?>; box-shadow: 0 0 6px <?= (isset($course->is_open) && $course->is_open == 1) ? 'rgba(40, 167, 69, 0.5)' : 'rgba(220, 53, 69, 0.5)' ?>;"></span>
                    <?= (isset($course->is_open) && $course->is_open == 1) ? 'التسجيل مفتوح' : 'مغلق' ?>
                </span>
            </div>

            <h2 class="section-title text-center" style="font-size: 2.5rem; font-weight: 800; margin-bottom: 1rem; color: var(--text-primary);"><?= esc($title) ?></h2>
            <p class="course-description text-center" style="max-width: 750px; margin: 0 auto 2.5rem auto; font-size: 1.15rem; line-height: 1.8; color: var(--text-secondary);"><?= esc($course->course_desc) ?></p>

            <!-- Core Metadata -->
            <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 2rem; padding-bottom: 2rem; border-bottom: 1px solid var(--border-color); margin-bottom: 1.5rem;">
                
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="display: flex; justify-content: center; align-items: center; width: 44px; height: 44px; background: var(--bg-accent); border: 1px solid var(--border-light); border-radius: 12px; color: var(--primary-color); box-shadow: 0 2px 4px rgba(19, 106, 213, 0.05);">
                        <i class="icon-user" style="font-size: 1.2rem;"></i>
                    </div>
                    <div style="display: flex; flex-direction: column; text-align: right;">
                        <span style="color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; margin-bottom: 2px;">المحاضر</span>
                        <span style="color: var(--text-primary); font-size: 1.05rem; font-weight: 700;"><?= esc($course->instructor_name ?? 'غير محدد') ?></span>
                    </div>
                </div>
                
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="display: flex; justify-content: center; align-items: center; width: 44px; height: 44px; background: var(--bg-accent); border: 1px solid var(--border-light); border-radius: 12px; color: var(--primary-color); box-shadow: 0 2px 4px rgba(19, 106, 213, 0.05);">
                        <i class="icon-info-circle" style="font-size: 1.2rem;"></i>
                    </div>
                    <div style="display: flex; flex-direction: column; text-align: right;">
                        <span style="color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; margin-bottom: 2px;">المستوى</span>
                        <span style="color: var(--text-primary); font-size: 1.05rem; font-weight: 700;"><?= esc($course->difficulty_level ?? '1') ?></span>
                    </div>
                </div>
                
                <?php 
                $duration = $course->duration ?? '0:00';
                if ($duration !== '0:00' && $duration !== '0 دقيقة' && $duration !== '0'): 
                ?>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="display: flex; justify-content: center; align-items: center; width: 44px; height: 44px; background: var(--bg-accent); border: 1px solid var(--border-light); border-radius: 12px; color: var(--primary-color); box-shadow: 0 2px 4px rgba(19, 106, 213, 0.05);">
                        <i class="icon-clock-o" style="font-size: 1.2rem;"></i>
                    </div>
                    <div style="display: flex; flex-direction: column; text-align: right;">
                        <span style="color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; margin-bottom: 2px;">المدة الزمنية</span>
                        <span style="color: var(--text-primary); font-size: 1.05rem; font-weight: 700;"><?= esc($duration) ?> دقيقة</span>
                    </div>
                </div>
                <?php endif; ?>
                
            </div>

            <!-- Stats -->
            <div style="display: flex; flex-wrap: wrap; justify-content: center; align-items: center; gap: 1.5rem; color: var(--text-secondary); font-size: 0.95rem; font-weight: 600;">
                <?php $unitCount = $course->unit_count ?? count($units); ?>
                <?php if ($unitCount > 0): ?>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="icon-book" style="color: var(--text-muted); font-size: 1.1rem;"></i> <?= $unitCount ?> وحدة
                </div>
                <?php endif; ?>

                <?php if (!empty($course->video_count)): ?>
                <div style="width: 4px; height: 4px; background-color: var(--border-color); border-radius: 50%;"></div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="icon-video" style="color: var(--text-muted); font-size: 1.1rem;"></i> <?= $course->video_count ?> فيديو
                </div>
                <?php endif; ?>

                <?php if (!empty($course->quiz_count)): ?>
                <div style="width: 4px; height: 4px; background-color: var(--border-color); border-radius: 50%;"></div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="icon-question-circle" style="color: var(--text-muted); font-size: 1.1rem;"></i> <?= $course->quiz_count ?> اختبار
                </div>
                <?php endif; ?>

                <?php if (!empty($course->page_count)): ?>
                <div style="width: 4px; height: 4px; background-color: var(--border-color); border-radius: 50%;"></div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="icon-file-text" style="color: var(--text-muted); font-size: 1.1rem;"></i> <?= $course->page_count ?> صفحة
                </div>
                <?php endif; ?>
            </div>

        </div>

        <div class="row">
            <!-- Left Column: Course Outline -->
            <div class="col-lg-8 mb-5 mb-lg-0">
                <div class="course-outline" data-aos="fade-up" data-aos-delay="200">
                    <h2 class="section-title mb-4" style="font-size:1.5rem;">محتوى الكورس</h2>
                    <div class="custom-accordion" id="courseOutlineAccordion">
                        <?php if (!empty($units)) : ?>
                            <?php foreach ($units as $unitIndex => $unit) : ?>
                                <div class="accordion-item">
                                    <div class="accordion-header" id="heading<?= $unitIndex ?>">
                                        <button class="accordion-button <?= ($unitIndex !== 0) ? 'collapsed' : '' ?>"
                                                type="button"
                                                data-toggle="collapse"
                                                data-target="#collapse<?= $unitIndex + 1 ?>"
                                                aria-expanded="<?= ($unitIndex === 0) ? 'true' : 'false' ?>"
                                                aria-controls="collapse<?= $unitIndex + 1 ?>"
                                                role="button"
                                                tabindex="0">
                                            <div class="unit-info">
                                                <i class="icon-book" aria-hidden="true"></i>
                                                <span>الوحدة <?= $unitIndex + 1 ?>: <?= esc($unit->unit_name ?? 'عنوان الوحدة') ?></span>
                                            </div>
                                            <i class="icon-chevron-down expand-icon" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <div id="collapse<?= $unitIndex + 1 ?>"
                                         class="accordion-collapse collapse <?= ($unitIndex === 0) ? 'show' : '' ?>"
                                         aria-labelledby="heading<?= $unitIndex ?>"
                                         data-parent="#courseOutlineAccordion">
                                        <div class="accordion-body">
                                            <?php if (!empty($unit->items)) : ?>
                                                <?php foreach ($unit->items as $item) : ?>
                                                    <?php
                                                    $metadata = is_array($item->metadata) ? $item->metadata : json_decode($item->metadata ?? '{}', true);
                                                    $isPreview = ($item->item_type === 'video' && isset($metadata['is_preview']) && $metadata['is_preview'] == 1);
                                                    $isItemFree = (isset($item->is_free) && $item->is_free == 1) || (isset($unit->is_free) && $unit->is_free == 1);
                                                    $previewVideoId = '';
                                                    $previewVideoSource = $metadata['video_source'] ?? 'bunny';
                                                    $previewLibraryId = (!empty($metadata['video_library_id']) && is_numeric($metadata['video_library_id']))
                                                        ? $metadata['video_library_id']
                                                        : env('BUNNY_NET_LIBRARY_ID');

                                                    if ($item->item_type === 'video') {
                                                        $previewVideoId = $item->item_id
                                                            ?? ($item->video_id ?? '')
                                                            ?? ($metadata['video_id'] ?? '');

                                                        if (empty($previewVideoId) && !empty($metadata['video_id'])) {
                                                            $previewVideoId = $metadata['video_id'];
                                                        }

                                                        if (empty($previewVideoId) && !empty($item->video_id)) {
                                                            $previewVideoId = $item->video_id;
                                                        }
                                                    }
                                                    ?>
                                                    <div class="video-item <?= ($item->item_type === 'video' && ($isPreview || $isItemFree)) ? 'video-previewable preview-video-link' : '' ?>"
                                                         role="listitem"
                                                         <?php if ($item->item_type === 'video' && ($isPreview || $isItemFree) && !empty($previewVideoId ?? '')): ?>
                                                             data-video-id="<?= esc($previewVideoId) ?>"
                                                             data-video-source="<?= esc($previewVideoSource) ?>"
                                                             data-video-library-id="<?= esc($previewLibraryId) ?>"
                                                             data-video-title="<?= esc($item->title ?? 'عنوان العنصر') ?>"
                                                             tabindex="0"
                                                             aria-label="معاينة الفيديو: <?= esc($item->title ?? 'عنوان العنصر') ?>"
                                                         <?php endif; ?>>
                                                        <div class="video-item-content">
                                                            <div class="video-icon" aria-hidden="true">
                                                                <?php if ($item->item_type === 'video'): ?>
                                                                    <i class="icon-play"></i>
                                                                <?php elseif ($item->item_type === 'quiz'): ?>
                                                                    <i class="icon-question-circle"></i>
                                                                <?php elseif ($item->item_type === 'page'): ?>
                                                                    <i class="icon-file-text"></i>
                                                                <?php else: ?>
                                                                    <i class="icon-circle-o"></i>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="video-item-info">
                                                                <div class="video-title">
                                                                    <?= esc($item->title ?? 'عنوان العنصر') ?>
                                                                    <?php if (!$isPreview): ?>
                                                                        <?php if ($isItemFree): ?>
                                                                            <span class="video-status free">مجاني</span>
                                                                        <?php else: ?>
                                                                            <span class="video-status locked">مغلق</span>
                                                                        <?php endif; ?>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="video-meta">
                                                                    <?php if ($item->item_type === 'video' && isset($item->duration_formatted)): ?>
                                                                        <div class="video-time">
                                                                            <i class="icon-clock-o" aria-hidden="true"></i>
                                                                            <?= esc($item->duration_formatted) ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <?php if($item->item_type): ?>
                                                                        <div class="video-type">
                                                                            <?php
                                                                            switch($item->item_type) {
                                                                                case 'video': echo 'فيديو'; break;
                                                                                case 'quiz': echo 'اختبار'; break;
                                                                                case 'page': echo 'صفحة'; break;
                                                                                default: echo 'عنصر';
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="video-actions">
                                                            <?php if ($item->item_type === 'video' && ($isPreview || $isItemFree) && !empty($previewVideoId)): ?>
                                                                <button type="button"
                                                                        class="btn btn-preview preview-video-link"
                                                                        data-video-id="<?= esc($previewVideoId) ?>"
                                                                        data-video-source="<?= esc($previewVideoSource) ?>"
                                                                        data-video-library-id="<?= esc($previewLibraryId) ?>"
                                                                        data-video-title="<?= esc($item->title ?? 'عنوان العنصر') ?>"
                                                                        aria-label="معاينة الفيديو: <?= esc($item->title ?? 'عنوان العنصر') ?>">
                                                                    <i class="icon-eye" aria-hidden="true"></i> شاهد الآن
                                                                </button>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <p class="text-muted">لا توجد عناصر في هذه الوحدة حالياً.</p>
                                            <?php endif; ?>

                                            <!-- Unit purchase buttons removed - purchase is now at course level -->
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p>لا يوجد محتوى متاح لهذا الكورس.</p>
                        <?php endif; ?>
                    </div> <!-- End custom-accordion -->
                </div>
            </div>
            <!-- Floating Cart Summary - القائمة العائمة -->
            <div class="floating-cart-summary" id="floatingCartSummary" style="display: none;">
                <div class="floating-cart-header" onclick="toggleFloatingCart()">
                    <h6 class="floating-cart-title">
                        <i class="icon-shopping-cart"></i>
                        <span>الوحدات المحددة</span>
                    </h6>
                    <button class="floating-cart-toggle" type="button">
                        <i class="icon-chevron-left"></i>
                    </button>
                    <div class="floating-cart-badge" id="floatingCartBadge" style="display: none;">0</div>
                </div>
                <div class="floating-cart-body">
                    <div id="floatingSelectedUnits"></div>
                    <div class="floating-cart-actions">
                        <button class="btn btn-primary btn-sm" id="floatingProceedToCheckout">
                            ابدأ التعلم
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" id="floatingClearCart">
                            مسح
                        </button>
                    </div>
                </div>
            </div>

            <!-- Enhanced Sidebar -->
            <div class="col-lg-4 sidebar">
                <!-- Top Subscribe Button -->
                <div class="course-purchase-section mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div style="background: var(--bg-gradient-primary); border-radius: var(--radius-lg); box-shadow: var(--shadow-md); padding: 1.5rem; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
                        <div style="font-size: 2.5rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem;" dir="ltr">
                            <?php if ($course->is_free): ?>
                                مجاني
                            <?php else: ?>
                                <?= number_format($course->course_price ?? 0) ?>
                                <svg width="24" height="24" viewBox="0 0 1124.14 1256.39" xmlns="http://www.w3.org/2000/svg" style="fill: currentColor; margin-left: 4px; vertical-align: middle; margin-bottom: 6px;">
                                    <path d="M699.62,1113.02h0c-20.06,44.48-33.32,92.75-38.4,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                                    <path d="M1085.73,895.8c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.33v-135.2l292.27-62.11c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.27V66.13c-50.67,28.45-95.67,66.32-132.25,110.99v403.35l-132.25,28.11V0c-50.67,28.44-95.67,66.32-132.25,110.99v525.69l-295.91,62.88c-20.06,44.47-33.33,92.75-38.42,143.37l334.33-71.05v170.26l-358.3,76.14c-20.06,44.47-33.32,92.75-38.4,143.37l375.04-79.7c30.53-6.35,56.77-24.4,73.83-49.24l68.78-101.97v-.02c7.14-10.55,11.3-23.27,11.3-36.97v-149.98l132.25-28.11v270.4l424.53-90.28Z"/>
                                </svg>
                            <?php endif; ?>
                        </div>
                        <a href="javascript:void(0);"
                           onclick="addToCart('course', <?= $course->id ?>);"
                           class="btn btn-light btn-lg"
                           style="font-weight: 600; border-radius: var(--radius-md); color: var(--primary-color); width: 100%; margin-top: 0.5rem;">
                            <i class="icon-shopping-cart"></i>
                            أضف للسلة
                        </a>
                        <small style="color: rgba(255,255,255,0.8); margin-top: 0.75rem;">دفع آمن عبر بايبال (PayPal)</small>
                        <?php if (!empty($course->telegram_link)): ?>
                            <a href="<?= esc($course->telegram_link) ?>" target="_blank"
                               class="btn mt-3 shadow-sm"
                               style="background-color: #0088cc; color: white; width: 100%; font-weight: 600; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; gap: 8px; padding: 12px; transition: all 0.3s ease;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-telegram" viewBox="0 0 16 16">
                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.287 5.906c-.778.324-2.334.994-4.666 2.01-.378.15-.577.298-.595.442-.03.243.275.339.69.47l.175.055c.408.133.958.288 1.243.292.26.004.545-.106.855-.332 2.073-1.419 3.127-2.146 3.16-2.185.034-.041.079-.092.12-.055.04.037.009.1-.012.126-.145.145-2.09 1.83-2.274 2.017-.184.187-.348.358-.513.525-.336.335-.631.623-.174.925l2.424 1.769c.441.32.784.55.784.55.442.324.962.247 1.11-.271.144-.508.77-4.133.97-5.748.016-.134.015-.26-.008-.344-.023-.084-.075-.157-.175-.184-.108-.03-.255.006-.412.062"/>
                                </svg>
                                قناة دعم الطلبة (تيليجرام)
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Course Introduction Video -->
                <div class="course-sidebar" data-aos="fade-up" data-aos-delay="300">
                    <h4>مقدمة الكورس</h4>
                    <div class="video-block">
                        <div class="video-container">
                            <?php
                                $introVideoId = $course->intro_video_id ?? '';
                                $isYouTube = (strlen($introVideoId) === 11);
                                $embedUrl = $isYouTube
                                    ? "https://www.youtube.com/embed/{$introVideoId}"
                                    : "https://iframe.mediadelivery.net/embed/" . ($course->collection_id ?? env('BUNNY_NET_LIBRARY_ID')) . "/{$introVideoId}?autoplay=false&preload=false";
                            ?>
                            <iframe
                                    src="<?= $embedUrl ?>"
                                    loading="lazy"
                                    style="border: none; position: absolute; top: 0; height: 100%; width: 100%;"
                                    allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"
                                    allowfullscreen="true"
                                    title="مقدمة الكورس"
                            ></iframe>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Course Features -->
                <div class="course-features">
                    <h5>مميزات الكورس</h5>
                    <ul class="course-features-list">
                        <li>
                            <div class="feature-icon">
                                <i class="fas fa-chalkboard"></i> </div>
                            <div class="feature-text">شرح السلايدات</div>
                        </li>
                        <li>
                            <div class="feature-icon">
                                <i class="fas fa-question-circle"></i> </div>
                            <div class="feature-text">شرح ال quizes</div>
                        </li>
                        <li>
                            <div class="feature-icon">
                                <i class="fas fa-clipboard-check"></i> </div>
                            <div class="feature-text">اختبر نفسك كل أسبوع</div>
                        </li>
                        <li>
                            <div class="feature-icon">
                                <i class="fas fa-check-double"></i> </div>
                            <div class="feature-text">حل تجميعات الاختبارات</div>
                        </li>
                        <li>
                            <div class="feature-icon">
                                <i class="fas fa-graduation-cap"></i> </div>
                            <div class="feature-text">مراجعات الميدتيرم والفاينال</div>
                        </li>
                        <li>
                            <div class="feature-icon">
                                <i class="fas fa-users"></i> </div>
                            <div class="feature-text">قروب خاص للدعم والإجابة على الأسئلة</div>
                        </li>
                        <li>
                            <div class="feature-icon">
                                <i class="fas fa-laptop-code"></i> </div>
                            <div class="feature-text">متوافق مع كافة الأجهزة</div>
                        </li>
                    </ul>

                    <div class="course-purchase-section mt-4">
                        <div style="background: var(--bg-gradient-primary); border-radius: var(--radius-lg); box-shadow: var(--shadow-md); padding: 1.5rem; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
                            <div style="font-size: 2.5rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem;" dir="ltr">
                                <?php if ($course->is_free): ?>
                                    مجاني
                                <?php else: ?>
                                    <?= number_format($course->course_price ?? 0) ?>
                                    <svg width="24" height="24" viewBox="0 0 1124.14 1256.39" xmlns="http://www.w3.org/2000/svg" style="fill: currentColor; margin-left: 4px; vertical-align: middle; margin-bottom: 6px;">
                                        <path d="M699.62,1113.02h0c-20.06,44.48-33.32,92.75-38.4,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                                        <path d="M1085.73,895.8c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.33v-135.2l292.27-62.11c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.27V66.13c-50.67,28.45-95.67,66.32-132.25,110.99v403.35l-132.25,28.11V0c-50.67,28.44-95.67,66.32-132.25,110.99v525.69l-295.91,62.88c-20.06,44.47-33.33,92.75-38.42,143.37l334.33-71.05v170.26l-358.3,76.14c-20.06,44.47-33.32,92.75-38.4,143.37l375.04-79.7c30.53-6.35,56.77-24.4,73.83-49.24l68.78-101.97v-.02c7.14-10.55,11.3-23.27,11.3-36.97v-149.98l132.25-28.11v270.4l424.53-90.28Z"/>
                                    </svg>
                                <?php endif; ?>
                            </div>
                            <a href="javascript:void(0);"
                               onclick="addToCart('course', <?= $course->id ?>);"
                               class="btn btn-light btn-lg"
                               style="font-weight: 600; border-radius: var(--radius-md); color: var(--primary-color); width: 100%; margin-top: 0.5rem;">
                                <i class="icon-shopping-cart"></i>
                                أضف للسلة
                            </a>
                            <small style="color: rgba(255,255,255,0.8); margin-top: 0.75rem;">شراء آمن بضمان استرجاع الأموال</small>
                            <?php if (!empty($course->telegram_link)): ?>
                                <a href="<?= esc($course->telegram_link) ?>" target="_blank"
                                   class="btn mt-3 shadow-sm"
                                   style="background-color: #0088cc; color: white; width: 100%; font-weight: 600; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; gap: 8px; padding: 12px; transition: all 0.3s ease;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-telegram" viewBox="0 0 16 16">
                                      <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.287 5.906c-.778.324-2.334.994-4.666 2.01-.378.15-.577.298-.595.442-.03.243.275.339.69.47l.175.055c.408.133.958.288 1.243.292.26.004.545-.106.855-.332 2.073-1.419 3.127-2.146 3.16-2.185.034-.041.079-.092.12-.055.04.037.009.1-.012.126-.145.145-2.09 1.83-2.274 2.017-.184.187-.348.358-.513.525-.336.335-.631.623-.174.925l2.424 1.769c.441.32.784.55.784.55.442.324.962.247 1.11-.271.144-.508.77-4.133.97-5.748.016-.134.015-.26-.008-.344-.023-.084-.075-.157-.175-.184-.108-.03-.255.006-.412.062"/>
                                    </svg>
                                    قناة دعم الطلبة (تيليجرام)
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Preview Modal -->
<div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="videoModalLabel" aria-hidden="true">
    <!-- .modal-dialog.modal-lg => bigger container for video -->
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="videoModalLabel" class="modal-title">معاينة الدرس</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق" style="outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- We removed extra padding in .modal-body to let video fill space -->
            <div class="modal-body p-0">
                <div class="modal-video-container">
                    <iframe
                            id="videoFrame"
                            src=""
                            loading="lazy"
                            title="معاينة الفيديو"
                            allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"
                            allowfullscreen="true"
                    ></iframe>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                <button type="button" class="btn btn-primary" onclick="addToCart('course', <?= $course->id ?>); $('#previewModal').modal('hide');">اشترك وأضف للسلة</button>
            </div>
        </div>
    </div>
</div>

<!-- Script to handle modal & preview logic + Cart functionality -->
<script>
    // Cart functionality
    let cart = [];

    document.addEventListener("DOMContentLoaded", function () {
        const previewLinks = document.querySelectorAll(".preview-video-link");
        const videoFrame   = document.getElementById("videoFrame");
        const modal        = $("#videoModal");
        // Removed cartSummary reference - using floating cart only
        const selectedUnitsDiv = document.getElementById("selectedUnits");
        // Only using floating cart elements since the original cart-summary-area was removed
        // const proceedBtn = document.getElementById("proceedToCheckout"); // Element doesn't exist
        // const clearCartBtn = document.getElementById("clearCart"); // Element doesn't exist

        // Preview video functionality with enhanced loading states
        function openPreview(triggerElement) {
            let videoId = triggerElement.getAttribute("data-video-id");
            let videoSource = triggerElement.getAttribute("data-video-source") || "bunny";
            let videoLibraryId = triggerElement.getAttribute("data-video-library-id") || "<?= env('BUNNY_NET_LIBRARY_ID') ?>";

            if (videoId) {
                const videoContainer = document.querySelector(".modal-video-container");
                videoContainer.classList.add("loading");

                const videoTitle = triggerElement.getAttribute("data-video-title")
                    || triggerElement.closest('.video-item').querySelector('.video-title')?.textContent?.trim()
                    || 'معاينة الدرس';
                document.getElementById("videoModalLabel").textContent = `معاينة: ${videoTitle}`;

                let videoUrl = '';

                if (videoSource === 'youtube') {
                    videoUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
                } else {
                    videoUrl = `https://iframe.mediadelivery.net/embed/${videoLibraryId}/${videoId}?autoplay=true`;
                }

                modal.modal("show");

                setTimeout(() => {
                    videoFrame.setAttribute("src", videoUrl);

                    videoFrame.onload = function() {
                        videoContainer.classList.remove("loading");
                    };
                }, 500);
            }
        }

        previewLinks.forEach(link => {
            link.addEventListener("click", function (event) {
                event.preventDefault();
                event.stopPropagation();
                openPreview(this);
            });

            link.addEventListener("keydown", function (event) {
                if (event.key === "Enter" || event.key === " ") {
                    event.preventDefault();
                    openPreview(this);
                }
            });
        });

        // Enhanced modal event handlers
        modal.on('show.bs.modal', function () {
            document.body.style.overflow = 'hidden';
        });

        // Reset the iframe src when the modal is closed to stop the video
        modal.on('hidden.bs.modal', function () {
            document.body.style.overflow = 'auto';
            videoFrame.setAttribute("src", "");
            document.getElementById("videoModalLabel").textContent = "معاينة الدرس";

            // Remove loading state if still present
            const videoContainer = document.querySelector(".modal-video-container");
            videoContainer.classList.remove("loading");
        });

        // Floating cart elements
        const floatingCart = document.getElementById("floatingCartSummary");
        const floatingCartHeader = floatingCart.querySelector(".floating-cart-header");
        const floatingCartBody = floatingCart.querySelector(".floating-cart-body");
        const floatingSelectedUnits = document.getElementById("floatingSelectedUnits");
        const floatingProceedBtn = document.getElementById("floatingProceedToCheckout");
        const floatingClearCartBtn = document.getElementById("floatingClearCart");

        // Cart functionality
        const addToCartButtons = document.querySelectorAll(".add-to-cart");

        // Floating cart toggle functionality
        floatingCartHeader.addEventListener("click", function() {
            const isCollapsed = floatingCart.classList.contains("collapsed");

            if (isCollapsed) {
                floatingCart.classList.remove("collapsed");
                floatingCartBody.style.display = "block";
            } else {
                floatingCart.classList.add("collapsed");
                floatingCartBody.style.display = "none";
            }
        });

        addToCartButtons.forEach(button => {
            button.addEventListener("click", function() {
                const unitId = this.getAttribute("data-unit-id");
                const unitName = this.getAttribute("data-unit-name");

                // Check if unit already in learning list
                const existingUnit = cart.find(item => item.id === unitId);
                if (existingUnit) {
                    alert("هذه الوحدة موجودة بالفعل في قائمة التعلم");
                    return;
                }

                // Add to learning list
                cart.push({
                    id: unitId,
                    name: unitName
                });

                // Update button state
                this.innerHTML = '<i class="icon-check"></i> تمت الإضافة';
                this.classList.remove('btn-primary');
                this.classList.add('btn-outline-primary');
                this.disabled = true;

                updateCartDisplay();
            });
        });

        // Clear learning list functionality (both original and floating)
        function clearCart() {
            cart = [];
            updateCartDisplay();

            // Reset all buttons
            addToCartButtons.forEach(button => {
                button.innerHTML = '<i class="icon-shopping-cart"></i> شراء الكورس';
                button.classList.remove('btn-outline-primary');
                button.classList.add('btn-primary');
                button.disabled = false;
            });
        }

        // clearCartBtn.addEventListener("click", clearCart); // Element doesn't exist
        floatingClearCartBtn.addEventListener("click", clearCart);

        // Proceed to learning (both original and floating)
        function proceedToCheckout() {
            if (cart.length === 0) {
                alert("يرجى اختيار وحدة واحدة على الأقل");
                return;
            }

            // Store selected units in session storage
            sessionStorage.setItem('selectedUnits', JSON.stringify(cart));

            // Redirect to unit purchase checkout page
            const unitIds = cart.map(unit => unit.id).join(',');
            window.location.href = '<?= site_url("enrollments/purchase-units") ?>?units=' + unitIds;
        }

        // proceedBtn.addEventListener("click", proceedToCheckout); // Element doesn't exist
        floatingProceedBtn.addEventListener("click", proceedToCheckout);

        function updateCartDisplay() {
            // Update floating cart display only
            if (cart.length === 0) {
                floatingCart.style.display = 'none';
                return;
            }

            floatingCart.style.display = 'block';

            // Update selected units display for floating cart
            let unitsHtml = '';

            cart.forEach(unit => {
                unitsHtml += `
                    <div class="selected-unit d-flex justify-content-between align-items-center mb-2">
                        <span>${unit.name}</span>
                        <span class="text-primary"><i class="icon-check"></i> جاهز للتعلم</span>
                    </div>
                `;
            });

            // Only update floating cart units display
            floatingSelectedUnits.innerHTML = unitsHtml;

            // Update floating cart header count
            const countBadge = document.getElementById("floatingCartBadge");
            if (countBadge) {
                countBadge.textContent = cart.length;
                countBadge.style.display = cart.length > 0 ? 'block' : 'none';
            }
        }
    });
</script>

<?php $this->endSection(); ?>
