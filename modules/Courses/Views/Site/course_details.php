<?php $this->extend('site_layout/template'); ?>
<?php $this->section('content'); ?>

<link rel="stylesheet" href="<?= base_url('site/css/course_details_styles.css') ?>?v=<?= time() ?>">

<!-- Main Section with Modern Design -->
<div class="untree_co-section">
    <div class="container">
        <!-- Compact & Elegant Course Header -->
        <?php 
            $rawDuration = trim((string)($course->duration ?? '0:00'));
            $cleanDuration = str_replace('دقيقة', '', $rawDuration);
            $cleanDuration = trim($cleanDuration);
            $durationDisplay = (!empty($cleanDuration) && $cleanDuration !== '0:00' && $cleanDuration !== '0') ? $cleanDuration . ' دقيقة' : null;
            $unitCount = $course->unit_count ?? count($units);
            $isOpen = isset($course->is_open) && $course->is_open == 1;
        ?>
        <div class="course-header-wrapper" data-aos="fade-up" data-aos-delay="100">
            
            <!-- Status Badge -->
            <div class="course-status-badge-wrap">
                <span class="course-status-badge <?= $isOpen ? 'status-open' : 'status-closed' ?>">
                    <span class="status-dot"></span>
                    <?= $isOpen ? 'التسجيل مفتوح' : 'التسجيل مغلق' ?>
                </span>
            </div>

            <h1 class="section-title text-center"><?= esc($title) ?></h1>
            
            <?php if (!empty($course->course_desc)): ?>
                <p class="course-description text-center"><?= esc($course->course_desc) ?></p>
            <?php endif; ?>

            <!-- Compact Unified Meta & Stats Strip -->
            <div class="course-header-meta-strip">
                <?php if (!empty($course->instructor_name)): ?>
                    <div class="meta-item">
                        <i class="icon-user meta-icon"></i>
                        <span class="meta-label">المحاضر:</span>
                        <strong class="meta-val"><?= esc($course->instructor_name) ?></strong>
                    </div>
                <?php endif; ?>

                <div class="meta-item">
                    <i class="icon-info-circle meta-icon"></i>
                    <span class="meta-label">المستوى:</span>
                    <strong class="meta-val"><?= esc($course->difficulty_level ?? '1') ?></strong>
                </div>

                <?php if ($durationDisplay): ?>
                    <div class="meta-item">
                        <i class="icon-clock-o meta-icon"></i>
                        <span class="meta-label">المدة:</span>
                        <strong class="meta-val"><?= esc($durationDisplay) ?></strong>
                    </div>
                <?php endif; ?>

                <?php if ($unitCount > 0): ?>
                    <span class="meta-separator"></span>
                    <div class="meta-item">
                        <i class="icon-book meta-icon"></i>
                        <strong class="meta-val"><?= $unitCount ?></strong> وحدات
                    </div>
                <?php endif; ?>

                <?php if (!empty($course->video_count)): ?>
                    <span class="meta-separator"></span>
                    <div class="meta-item">
                        <i class="icon-video meta-icon"></i>
                        <strong class="meta-val"><?= $course->video_count ?></strong> فيديو
                    </div>
                <?php endif; ?>

                <?php if (!empty($course->quiz_count)): ?>
                    <span class="meta-separator"></span>
                    <div class="meta-item">
                        <i class="icon-question-circle meta-icon"></i>
                        <strong class="meta-val"><?= $course->quiz_count ?></strong> اختبار
                    </div>
                <?php endif; ?>

                <?php if (!empty($course->page_count)): ?>
                    <span class="meta-separator"></span>
                    <div class="meta-item">
                        <i class="icon-file-text meta-icon"></i>
                        <strong class="meta-val"><?= $course->page_count ?></strong> صفحة
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
                                <div class="accordion-item" <?= ($unitIndex === 0) ? 'id="first-unit"' : '' ?>>
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
                            <p id="first-unit" class="text-muted py-3">لا يوجد محتوى متاح لهذا الكورس حالياً.</p>
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
                        <div class="d-flex w-100" style="gap: 8px; margin-top: 0.5rem;">
                            <a href="javascript:void(0);"
                               onclick="addToCart('course', <?= $course->id ?>);"
                               class="btn btn-light btn-lg flex-grow-1"
                               style="font-weight: 600; border-radius: var(--radius-md); color: var(--primary-color); display: inline-flex; align-items: center; justify-content: center; gap: 6px;">
                                <i class="icon-shopping-cart"></i>
                                أضف للسلة
                            </a>
                            <a href="#first-unit"
                               onclick="scrollToFirstUnit(event);"
                               class="btn btn-outline-light btn-lg"
                               title="معاينة محتوى الكورس والوحدة الأولى"
                               style="font-weight: 600; border-radius: var(--radius-md); border: 2px solid rgba(255,255,255,0.9); color: #fff; display: inline-flex; align-items: center; justify-content: center; gap: 6px; white-space: nowrap; padding-left: 1.2rem; padding-right: 1.2rem;">
                                <i class="fas fa-eye"></i>
                                معاينة
                            </a>
                        </div>
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
                <!-- Instructor Authority & Bio Card -->
                <?php
                    $instructorName = !empty($course->instructor_name) ? $course->instructor_name : 'المهندس / أحمد فخر الدين';
                    $instructorTitle = !empty($course->instructor_username) ? $course->instructor_username : 'محاضر ومطور مناهج SEU';
                    $instructorBio = !empty($course->instructor_bio) 
                        ? $course->instructor_bio 
                        : 'محاضر متخصص في تبسيط وشرح مقررات الجامعة السعودية الإلكترونية SEU، بخبرة واسعة في إعداد الملخصات وتجميعات الاختبارات والتدريبات العملية لضمان التفوق الأكاديمي والحصول على A+.';
                    
                    $instructorAvatarUrl = '';
                    if (!empty($course->instructor_avatar)) {
                        $instructorAvatarUrl = thumb($course->instructor_avatar, 140, 140);
                    }
                    
                    // Generate initial letter for placeholder
                    $nameWords = preg_split('/\s+/u', trim($instructorName));
                    $initial = mb_substr($nameWords[0] ?? 'ف', 0, 1);
                ?>
                <div class="instructor-authority-card" data-aos="fade-up" data-aos-delay="250">
                    <div class="instructor-authority-header">
                        <h4 class="instructor-section-title">
                            <i class="icon-user" style="color: var(--primary-color);"></i>
                            <span>محاضر المقرر</span>
                        </h4>
                        <span class="instructor-badge">
                            <i class="fas fa-check-circle"></i>
                            معتمد SEU
                        </span>
                    </div>

                    <div class="instructor-profile-row">
                        <div class="instructor-avatar-wrap">
                            <?php if (!empty($instructorAvatarUrl)): ?>
                                <img src="<?= esc($instructorAvatarUrl) ?>" alt="<?= esc($instructorName) ?>" class="instructor-avatar-img" loading="lazy">
                            <?php else: ?>
                                <div class="instructor-avatar-placeholder">
                                    <?= esc($initial) ?>
                                </div>
                            <?php endif; ?>
                            <span class="instructor-verify-check" title="محاضر معتمد"><i class="fas fa-check"></i></span>
                        </div>
                        <div class="instructor-info">
                            <h5 class="instructor-name"><?= esc($instructorName) ?></h5>
                            <p class="instructor-title-sub"><?= esc($instructorTitle) ?></p>
                        </div>
                    </div>

                    <div class="instructor-bio-wrapper">
                        <div class="instructor-bio-text collapsed" id="instructorBioText">
                            <?= nl2br(esc($instructorBio)) ?>
                        </div>
                        <button type="button" class="instructor-bio-toggle-btn" id="instructorBioToggle" onclick="toggleInstructorBio()" aria-expanded="false" aria-controls="instructorBioText">
                            <span class="toggle-text">المزيد</span>
                            <i class="fas fa-chevron-down toggle-icon" aria-hidden="true"></i>
                        </button>
                    </div>

                    <div class="instructor-highlights">
                        <div class="instructor-highlight-item">
                            <i class="fas fa-graduation-cap"></i>
                            <span>منهج SEU المعتمد</span>
                        </div>
                        <div class="instructor-highlight-item">
                            <i class="fas fa-comments"></i>
                            <span>متابعة ودعم مستمر</span>
                        </div>
                    </div>
                </div>

                <!-- Course Introduction Video -->
                <?php
                    $videoEmbed = resolve_video_embed_url($course->intro_video_id ?? null, $course->collection_id ?? null);
                ?>
                <div class="course-sidebar" data-aos="fade-up" data-aos-delay="300">
                    <h4>مقدمة الكورس</h4>
                    <div class="video-block">
                        <?php if ($videoEmbed && !empty($videoEmbed['url'])): ?>
                            <div class="video-container">
                                <iframe
                                        src="<?= esc($videoEmbed['url']) ?>"
                                        loading="lazy"
                                        style="border: none; position: absolute; top: 0; height: 100%; width: 100%;"
                                        allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"
                                        allowfullscreen="true"
                                        title="مقدمة الكورس"
                                ></iframe>
                            </div>
                        <?php else: ?>
                            <div class="video-placeholder-container">
                                <div class="video-placeholder-inner">
                                    <i class="fas fa-play-circle video-placeholder-icon"></i>
                                    <div class="video-placeholder-text">فيديو تعريفي للمقرر</div>
                                    <div class="video-placeholder-sub">سيتم توفير الفيديو قريباً</div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Enhanced Course Features -->
                <div class="course-features">
                    <h5>مميزات الكورس</h5>
                    <ul class="course-features-list">
                        <li>
                            <div class="feature-icon">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <div class="feature-text">شرح السلايدات والتطبيق العملي</div>
                        </li>
                        <li>
                            <div class="feature-icon">
                                <i class="fas fa-clipboard-check"></i>
                            </div>
                            <div class="feature-text">حل التجميعات ونماذج الكويزات</div>
                        </li>
                        <li>
                            <div class="feature-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="feature-text">مراجعات مكثفة للميد والفاينل</div>
                        </li>
                        <li>
                            <div class="feature-icon">
                                <i class="fas fa-comments"></i>
                            </div>
                            <div class="feature-text">قروب دعم ومتابعة دورية مع المحاضر</div>
                        </li>
                    </ul>
                </div>

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
                            <div class="d-flex w-100" style="gap: 8px; margin-top: 0.5rem;">
                                <a href="javascript:void(0);"
                                   onclick="addToCart('course', <?= $course->id ?>);"
                                   class="btn btn-light btn-lg flex-grow-1"
                                   style="font-weight: 600; border-radius: var(--radius-md); color: var(--primary-color); display: inline-flex; align-items: center; justify-content: center; gap: 6px;">
                                    <i class="icon-shopping-cart"></i>
                                    أضف للسلة
                                </a>
                                <a href="#first-unit"
                                   onclick="scrollToFirstUnit(event);"
                                   class="btn btn-outline-light btn-lg"
                                   title="معاينة محتوى الكورس والوحدة الأولى"
                                   style="font-weight: 600; border-radius: var(--radius-md); border: 2px solid rgba(255,255,255,0.9); color: #fff; display: inline-flex; align-items: center; justify-content: center; gap: 6px; white-space: nowrap; padding-left: 1.2rem; padding-right: 1.2rem;">
                                    <i class="fas fa-eye"></i>
                                    معاينة
                                </a>
                            </div>
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
        // Check if instructor bio text is short enough to not need a toggle
        const bioText = document.getElementById("instructorBioText");
        const bioToggle = document.getElementById("instructorBioToggle");
        if (bioText && bioToggle) {
            if (bioText.textContent.trim().length <= 90) {
                bioToggle.style.display = "none";
            }
        }

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

    // Instructor Bio Expand/Collapse Function
    function toggleInstructorBio() {
        const bioText = document.getElementById("instructorBioText");
        const bioToggle = document.getElementById("instructorBioToggle");
        if (!bioText || !bioToggle) return;

        const isCollapsed = bioText.classList.contains("collapsed");
        const toggleText = bioToggle.querySelector(".toggle-text");
        const toggleIcon = bioToggle.querySelector(".toggle-icon");

        if (isCollapsed) {
            bioText.classList.remove("collapsed");
            bioText.classList.add("expanded");
            bioToggle.setAttribute("aria-expanded", "true");
            if (toggleText) toggleText.textContent = "عرض أقل";
            if (toggleIcon) {
                toggleIcon.classList.remove("fa-chevron-down");
                toggleIcon.classList.add("fa-chevron-up");
            }
        } else {
            bioText.classList.remove("expanded");
            bioText.classList.add("collapsed");
            bioToggle.setAttribute("aria-expanded", "false");
            if (toggleText) toggleText.textContent = "المزيد";
            if (toggleIcon) {
                toggleIcon.classList.remove("fa-chevron-up");
                toggleIcon.classList.add("fa-chevron-down");
            }
        }
    }

    // Smooth scroll and highlight first unit preview
    function scrollToFirstUnit(e) {
        if (e && typeof e.preventDefault === 'function') {
            e.preventDefault();
        }
        const target = document.getElementById('first-unit') || document.getElementById('courseOutlineAccordion');
        if (!target) return;

        // Ensure first unit accordion is expanded
        const collapse1 = document.getElementById('collapse1');
        if (collapse1) {
            if (typeof $ !== 'undefined' && typeof $(collapse1).collapse === 'function') {
                $(collapse1).collapse('show');
            } else {
                collapse1.classList.add('show');
            }
        }

        const nav = document.querySelector('.site-nav') || document.querySelector('header') || document.querySelector('.navbar');
        const navHeight = nav ? nav.offsetHeight : 70;
        const targetTop = target.getBoundingClientRect().top + window.pageYOffset - navHeight - 25;

        window.scrollTo({
            top: Math.max(0, targetTop),
            behavior: 'smooth'
        });

        // Trigger subtle pulse glow highlight
        target.classList.remove('highlight-preview-unit');
        void target.offsetWidth;
        target.classList.add('highlight-preview-unit');
        setTimeout(function() {
            target.classList.remove('highlight-preview-unit');
        }, 3500);
    }

    // Automatically scroll to first unit when opened with #first-unit hash or preview flag
    document.addEventListener('DOMContentLoaded', function() {
        if (window.location.hash === '#first-unit' || window.location.hash === '#preview' || window.location.search.indexOf('preview=1') !== -1) {
            setTimeout(function() {
                scrollToFirstUnit();
            }, 350);
        }
    });
</script>

<?php $this->endSection(); ?>
