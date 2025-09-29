<?php $this->extend('site_layout/template'); ?>
<?php $this->section('content'); ?>

<style>

    .untree_co-section {
        padding-top: 80px;
        padding-bottom: 80px;
    }

    /* Centered Header for the course view page */
    .course-header-wrapper {
        text-align: center;
        max-width: 800px;
        margin: 0 auto 50px auto;
    }
    .course-header-wrapper .section-title {
        font-size: 2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
        position: relative;
        display: inline-block;
    }
    .course-header-wrapper .section-title::after {
        content: "";
        position: absolute;
        bottom: -6px;
        right: 50%;
        transform: translateX(50%);
        width: 60px;
        height: 3px;
        background-color: #136ad5;
        border-radius: 2px;
    }
    .course-header-wrapper .course-description {
        font-size: 1.1rem;
        color: #555;
        line-height: 1.7;
        margin: 0 auto;
        max-width: 700px;
    }

    /* Sidebar & user info block */
    .sidebar {
        margin-bottom: 30px;
        width: 320px;
        padding-right: 30px;
    }
    .user-info-block {
        background-color: #fff;
        padding: 10px;
        border-radius: 7px;
        margin-bottom: 20px;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.08);
        border: 1px solid #eee;
        text-align: center; /* Center the content inside the user info block */
    }
    .user-info-block .user-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        display: block;
        margin: 0 auto 15px auto;
        border: 2px solid #fff;
        box-shadow: 0 0 0 3px #e0e7ee;
    }
    .user-info-block h3 {
        font-size: 1.4rem;
        color: #343a40;
        margin-bottom: 10px;
    }

    /* Next/Previous buttons styling */
    .nav-buttons {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 20px;
    }
    .nav-buttons .btn {
        flex: 0 0 auto;
        white-space: nowrap;
        padding: 10px 20px;
        font-size: 0.9rem;
    }

    /* Accordion for the list of videos/sections */
    .videos-accordion {
        margin-top: 20px;
        max-height: 500px;
        overflow-y: auto;
        direction: rtl;
        text-align: right;
    }
    .videos-accordion .accordion-item {
        border: none;
        margin-bottom: 0;
        overflow: hidden;
        background-color: transparent;
        box-shadow: none;
    }
    .videos-accordion .accordion-header {
        margin-bottom: 0 !important;
    }
    .videos-accordion .accordion-button {
        font-size: 1rem;
        font-weight: 500;
        color: #444;
        background-color: transparent;
        border: none;
        border-radius: 0;
        box-shadow: none;
        padding: 10px 15px;
        border-bottom: 1px solid #eee;
        text-align: right;
    }
    .videos-accordion .accordion-button:not(.collapsed) {
        background: linear-gradient(135deg, #f8f9ff, #e8f2ff);
        color: #136ad5;
        box-shadow: none;
    }
    .videos-accordion .accordion-button:hover {
        background: #f8f9fa;
    }
    .videos-accordion .accordion-button.unit-locked {
        background: #fff5f5;
        color: #dc3545;
    }
    .videos-accordion .accordion-collapse {
        border-top: 1px solid #f1f3f4;
    }
    .videos-accordion .accordion-body {
        padding: 0;
        background: #fafbfc;
    }
    .videos-accordion .list-unstyled {
        margin: 0;
    }
    .videos-accordion .list-unstyled li {
        border-bottom: 1px solid #f1f3f4;
    }
    .videos-accordion .list-unstyled li:last-child {
        border-bottom: none;
    }
    .videos-accordion .list-unstyled li a,
    .videos-accordion .list-unstyled li span {
        display: block;
        padding: 15px 25px;
        color: #555;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        position: relative;
    }
    .videos-accordion .list-unstyled li a:hover {
        background: #f0f7ff;
        color: #136ad5;
        padding-right: 35px;
    }
    .videos-accordion .list-unstyled li a.active-video {
        background: linear-gradient(135deg, #136ad5, #00aeff);
        color: white;
        font-weight: 600;
        position: relative;
    }
    .videos-accordion .list-unstyled li a.active-video::after {
        content: '';
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        width: 8px;
        height: 8px;
        background: white;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: translateY(-50%) scale(1); }
        50% { opacity: 0.7; transform: translateY(-50%) scale(1.2); }
    }

    /* Main Content Column: Video & Description */
    .main-content {
        margin-top: 30px;
        padding-left: 20px; /* Added padding for better spacing */
    }
    .course-preview-video .video-container {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
        border-radius: 15px;
        box-shadow: 0 12px 35px rgba(0,0,0,0.15);
        margin-bottom: 25px;
        background: #000;
    }
    .course-preview-video .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
        border-radius: 15px;
    }
    .quiz-content-area{
        direction: ltr;
    }
    .video-title-main-video {
        direction: ltr;
        font-size: 1.8rem;
        color: #333;
        margin-bottom: 15px;
        margin-top: 20px !important;
        font-weight: 700;
        line-height: 1.4;
    }
    .course-description-text {
        color: #666;
        line-height: 1.8;
        margin-bottom: 30px;
        font-size: 1.05rem;
    }
    .btn.btn-primary.btn-block.mark-complete-button {
        padding: 16px 35px;
        font-size: 1.1rem;
        border-radius: 10px;
        margin-top: 30px;
        font-weight: 600;
        background: linear-gradient(135deg, #136ad5, #00aeff);
        border: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn.btn-primary.btn-block.mark-complete-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(19, 106, 213, 0.3);
    }
    .btn.btn-primary.btn-block.mark-complete-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    .btn.btn-primary.btn-block.mark-complete-button:hover::before {
        left: 100%;
    }

    /* Quiz Content Styles */
    .quiz-content-area .card {
        border: none;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        border-radius: 15px;
        overflow: hidden;
        background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
    }

    .quiz-content-area .card-header {
        border-bottom: 1px solid #e9ecef;
        padding: 25px;
        background: linear-gradient(135deg, #136ad5, #00aeff);
        color: white;
    }

    .quiz-content-area .card-body {
        padding: 35px;
    }

    .quiz-stat {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        font-size: 15px;
        color: #555;
    }

    .quiz-stat i {
        width: 22px;
        text-align: center;
        color: #136ad5;
    }

    /* Page Content Styles */
    .page-content-area .card {
        border: none;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        border-radius: 15px;
        overflow: hidden;
        background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
    }

    .page-content-area .card-header {
        border-bottom: 1px solid #e9ecef;
        padding: 25px;
        background: linear-gradient(135deg, #136ad5, #00aeff);
        color: white;
    }

    .page-content-area .card-body {
        padding: 35px;
    }

    .page-content .page-body {
        line-height: 1.9;
        font-size: 16px;
        color: #444;
    }

    .page-content .page-body h1,
    .page-content .page-body h2,
    .page-content .page-body h3,
    .page-content .page-body h4,
    .page-content .page-body h5,
    .page-content .page-body h6 {
        margin-top: 30px;
        margin-bottom: 18px;
        color: #333;
        font-weight: 600;
    }

    .page-content .page-body p {
        margin-bottom: 18px;
    }

    .page-content .page-body ul,
    .page-content .page-body ol {
        margin-bottom: 18px;
        padding-right: 25px;
    }

    /* Default Content Styles */
    .default-content-area .card {
        border: 2px dashed #dee2e6;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
    }

    /* Course Item Blocks - Modern Layout */
    .unit-items-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding: 0;
    }

    .course-item {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
    }

    .course-item:hover {
        border-color: #136ad5;
        box-shadow: 0 4px 15px rgba(19, 106, 213, 0.1);
        transform: translateY(-2px);
    }

    .course-item.active-item {
        border-color: #136ad5;
        background: linear-gradient(135deg, #f8f9ff 0%, #e8f2ff 100%);
        box-shadow: 0 4px 20px rgba(19, 106, 213, 0.15);
    }

    .course-item.locked-item {
        background: #f8f9fa;
        border-color: #dee2e6;
        opacity: 0.7;
    }

    .course-item.locked-item:hover {
        transform: none;
        box-shadow: none;
        border-color: #dee2e6;
    }

    .item-content {
        display: flex;
        align-items: center;
        padding: 16px 20px;
        text-decoration: none;
        color: inherit;
        width: 100%;
        gap: 15px;
    }

    .item-content.locked-content {
        cursor: not-allowed;
    }

    .item-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(19, 106, 213, 0.1);
        flex-shrink: 0;
    }

    .course-item.active-item .item-icon {
        background: rgba(19, 106, 213, 0.2);
    }

    .course-item.locked-item .item-icon {
        background: rgba(173, 181, 189, 0.1);
    }

    .item-icon i {
        font-size: 18px;
    }

    .item-details {
        flex: 1;
        min-width: 0;
    }

    .item-title {
        font-size: 15px;
        font-weight: 600;
        color: #333;
        margin-bottom: 4px;
        line-height: 1.4;
        display: block;
    }

    .course-item.active-item .item-title {
        color: #136ad5;
    }

    .item-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
    }

    .item-duration,
    .item-type {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        font-weight: 500;
    }

    .item-duration i,
    .item-type i {
        font-size: 11px;
    }

    .item-status {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        flex-shrink: 0;
    }

    .status-indicator {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        font-size: 12px;
    }

    .status-indicator.current {
        background: #136ad5;
        color: white;
    }

    .status-indicator.locked {
        background: #dc3545;
        color: white;
    }

    .status-indicator.available {
        background: rgba(108, 117, 125, 0.1);
        color: #6c757d;
    }

    .course-item:hover .status-indicator.available {
        background: rgba(19, 106, 213, 0.1);
        color: #136ad5;
    }

    /* Responsive Design for Item Blocks */
    @media (max-width: 768px) {
        .item-content {
            padding: 14px 16px;
            gap: 12px;
        }

        .item-icon {
            width: 36px;
            height: 36px;
        }

        .item-icon i {
            font-size: 16px;
        }

        .item-title {
            font-size: 14px;
        }

        .item-duration,
        .item-type {
            font-size: 11px;
        }

        .status-indicator {
            width: 24px;
            height: 24px;
            font-size: 11px;
        }
    }

    @media (max-width: 480px) {
        .unit-items-container {
            gap: 8px;
        }

        .item-content {
            padding: 12px 14px;
            gap: 10px;
        }

        .item-icon {
            width: 32px;
            height: 32px;
        }

        .item-icon i {
            font-size: 14px;
        }
    }

    /* Left Space Utilization - Course Info Panel */
    .course-info-panel {
        background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        border: 1px solid #e9ecef;
        position: sticky;
        top: 100px;
    }

    .course-info-panel h5 {
        color: #136ad5;
        font-weight: 700;
        margin-bottom: 20px;
        font-size: 1.1rem;
    }

    .course-info-item {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        padding: 10px;
        background: rgba(19, 106, 213, 0.05);
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .course-info-item:hover {
        background: rgba(19, 106, 213, 0.1);
        transform: translateX(5px);
    }

    .course-info-item i {
        color: #136ad5;
        margin-left: 12px;
        width: 20px;
        text-align: center;
    }

    .course-info-item span {
        color: #555;
        font-size: 0.9rem;
        font-weight: 500;
    }

    /* Enhanced Progress Container Styles */
    .progress-container {
        background: linear-gradient(135deg, #f8f9ff 0%, #e8f2ff 100%);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #e3f2fd;
        box-shadow: 0 2px 10px rgba(19, 106, 213, 0.06);
    }

    .progress-label {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        font-weight: 600;
        color: #333;
        font-size: 1rem;
    }

    .progress-percentage {
        background: linear-gradient(135deg, #136ad5, #00aeff);
        color: white;
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 700;
        box-shadow: 0 2px 6px rgba(19, 106, 213, 0.25);
    }

    .progress {
        height: 10px;
        background-color: #e9ecef;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 15px;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
    }

    .progress-bar {
        background: linear-gradient(135deg, #136ad5 0%, #00aeff 100%);
        border-radius: 8px;
        transition: width 0.6s ease;
        position: relative;
        overflow: hidden;
    }

    .progress-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        background-image: linear-gradient(
            -45deg,
            rgba(255, 255, 255, .2) 25%,
            transparent 25%,
            transparent 50%,
            rgba(255, 255, 255, .2) 50%,
            rgba(255, 255, 255, .2) 75%,
            transparent 75%,
            transparent
        );
        background-size: 25px 25px;
        animation: move 2s linear infinite;
    }

    @keyframes move {
        0% { background-position: 0 0; }
        100% { background-position: 25px 25px; }
    }

    .progress-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-top: 12px;
    }

    .progress-stat {
        text-align: center;
        background: white;
        padding: 12px 8px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .progress-stat:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        border-color: #136ad5;
    }

    .progress-stat-number {
        display: block;
        font-size: 1.3rem;
        font-weight: 700;
        color: #136ad5;
        margin-bottom: 5px;
    }

    .progress-stat-label {
        font-size: 0.85rem;
        color: #666;
        font-weight: 500;
    }

    /* Enhanced Navigation Buttons */
    .nav-buttons {
        display: flex;
        gap: 8px;
        margin-top: 15px;
    }

    .nav-buttons .btn {
        flex: 1;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        font-size: 0.9rem;
    }

    .nav-buttons .prev-btn {
        background: white;
        color: #666;
        border-color: #e9ecef;
    }

    .nav-buttons .prev-btn:hover:not(:disabled) {
        background: #f8f9fa;
        border-color: #136ad5;
        color: #136ad5;
        transform: translateY(-1px);
    }

    .nav-buttons .next-btn {
        background: linear-gradient(135deg, #136ad5, #00aeff);
        color: white;
        border: none;
    }

    .nav-buttons .next-btn:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(19, 106, 213, 0.25);
    }

    .nav-buttons .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Responsive adjustments for progress */
    @media (max-width: 767.98px) {
        .progress-stats {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .progress-stat {
            padding: 12px 8px;
        }

        .progress-stat-number {
            font-size: 1.3rem;
        }

        .nav-buttons {
            flex-direction: column;
        }
    }
    @media (max-width: 1199.98px) {
        .sidebar {
            width: 600px;
            padding-right: 30px;
        }
        .main-content {
            padding-left: 25px;
        }
    }

    @media (max-width: 991.98px) {
        .sidebar {
            margin-bottom: 30px;
            padding-right: 0;
            width: 100%;
        }
        .main-content {
            margin-top: 0;
            padding-left: 0;
        }
        .user-info-block,
        .videos-accordion .accordion-item {
            border-left: none;
            border-right: none;
            border-radius: 0;
            box-shadow: none;
        }
        .videos-accordion {
            max-height: none;
            border-radius: 0;
        }
        .nav-buttons {
            flex-direction: row;
        }
        .nav-buttons .btn {
            margin: 5px 2px;
            font-size: 0.9rem;
        }
        .course-info-panel {
            position: static;
        }
    }

    @media (max-width: 767.98px) {
        .course-header-wrapper .section-title {
            font-size: 1.8rem;
        }
        .course-header-wrapper .course-description {
            font-size: 1rem;
        }
        .video-title-main-video {
            font-size: 1.5rem;
        }
    }
</style>

<div class="untree_co-section bg-light">
    <div class="container-fluid"> <!-- Changed to container-fluid for better space utilization -->

        <!-- Header / Title & Short Description with Buy Units Block -->
        <div class="course-header-wrapper d-flex justify-content-between align-items-start" data-aos="fade-up" data-aos-delay="100">
            <div class="course-title-section flex-grow-1">
                <h2 class="section-title"><?= esc($title) ?></h2>
                <p class="course-description">
                    <?= esc($course->course_desc) ?>
                </p>
            </div>

            <!-- Compact Buy Units Block -->
            <div class="buy-units-compact ml-4">
                <a href="<?= site_url('courses/course_details/' . $course->slug) ?>"
                   class="btn btn-primary"
                   style="background: linear-gradient(135deg, #136ad5 0%, #0d5aa7 100%); border: none;
                   padding: 17px 18px; font-weight: 600; border-radius: 6px; font-size: 0.9rem; white-space: nowrap;">
                    <i class="fas fa-shopping-cart ml-1"></i>
                    شراء وحدات
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Left Space Utilization - Course Info Panel -->

            <!-- Enhanced Sidebar (Progress, Next/Prev, Videos List) -->
            <div class="col-xl-6 col-lg-6 sidebar"> <!-- Further increased width for wider progress bar -->
                <div class="user-info-block">
                    <!-- Enhanced Progress Bar -->
                    <div class="progress-container">
                        <div class="progress-label">
                            <span>تقدم الكورس</span>
                            <span class="progress-percentage"><?= esc($course_progress) ?>%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar"
                                 style="width: <?= esc($course_progress) ?>%;"
                                 aria-valuenow="<?= esc($course_progress) ?>"
                                 aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>

                        <!-- Progress Statistics -->
                        <div class="progress-stats">
                            <div class="progress-stat">
                                <span class="progress-stat-number"><?= count($units) ?></span>
                                <span class="progress-stat-label">إجمالي الوحدات</span>
                            </div>
                            <div class="progress-stat">
                                <span class="progress-stat-number"><?= round($course_progress) ?>%</span>
                                <span class="progress-stat-label">مكتمل</span>
                            </div>
                            <div class="progress-stat">
                                <span class="progress-stat-number"><?= 100 - round($course_progress) ?>%</span>
                                <span class="progress-stat-label">متبقي</span>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Previous / Next Buttons -->
                    <div class="nav-buttons">
                        <!-- Previous Button -->
                        <?php if ($prevLessonUrl): ?>
                            <a class="btn btn-outline-secondary prev-btn" href="<?= $prevLessonUrl ?>">
                                <i class="fas fa-chevron-right ml-2"></i>السابق
                            </a>
                        <?php else: ?>
                            <button class="btn btn-outline-secondary prev-btn" disabled>
                                <i class="fas fa-chevron-right ml-2"></i>السابق
                            </button>
                        <?php endif; ?>

                        <!-- Next Button -->
                        <?php if ($nextLessonUrl): ?>
                            <a class="btn btn-secondary next-btn" href="<?= $nextLessonUrl ?>">
                                التالي<i class="fas fa-chevron-left mr-2"></i>
                            </a>
                        <?php else: ?>
                            <button class="btn btn-secondary next-btn" disabled>
                                التالي<i class="fas fa-chevron-left mr-2"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>


                <!-- Accordion of Units & Items -->
                <div class="videos-accordion accordion" id="videoAccordion">
                    <?php foreach ($units as $unit): ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading<?= esc($unit->id) ?>">
                                <button class="accordion-button <?= isset($unit->is_open) && $unit->is_open ? '' : 'collapsed' ?> <?= !($unit->is_enrolled ?? false) && !($unit->is_free ?? false) ? 'unit-locked' : '' ?>"
                                        type="button"
                                        data-toggle="collapse"
                                        data-target="#collapse<?= esc($unit->id) ?>"
                                        aria-expanded="<?= isset($unit->is_open) && $unit->is_open ? 'true' : 'false' ?>"
                                        aria-controls="collapse<?= esc($unit->id) ?>">
                                    <?= esc($unit->unit_name) ?>
                                    <?php if (!($unit->is_enrolled ?? false) && !($unit->is_free ?? false)): ?>
                                        <i class="fas fa-lock ml-2" style="color: #dc3545;"></i>
                                    <?php elseif (($unit->is_free ?? false) && !($unit->is_enrolled ?? false)): ?>
                                        <span class="badge badge-success ml-2" style="background-color: #28a745; color: white; font-weight: bold;">مجاني</span>
                                    <?php endif; ?>
                                    <span class="badge badge-secondary ml-2"><?= count($unit->items ?? []) ?> عنصر</span>
                                </button>
                            </h2>
                            <div id="collapse<?= esc($unit->id) ?>"
                                 class="collapse <?= isset($unit->is_open) && $unit->is_open ? 'show' : '' ?>"
                                 aria-labelledby="heading<?= esc($unit->id) ?>"
                                 data-parent="#videoAccordion">
                                <div class="accordion-body">
                                    <div class="unit-items-container">
                                        <?php if (isset($unit->items)): ?>
                                            <?php foreach ($unit->items as $item): ?>
                                                <?php
                                                $isUnitLocked = !($unit->is_enrolled ?? false) && !($unit->is_free ?? false);
                                                $itemClass = $item->id == $current_id ? 'active-item' : '';
                                                if ($isUnitLocked) {
                                                    $itemClass .= ' locked-item';
                                                }
                                                ?>

                                                <div class="course-item <?= $itemClass ?>">
                                                    <?php if ($isUnitLocked): ?>
                                                    <div class="item-content locked-content">
                                                        <?php else: ?>
                                                        <?php
                                                        // Generate correct URL parameter based on item type
                                                        $urlParam = '';
                                                        switch ($item->item_type) {
                                                            case 'video':
                                                                $urlParam = 'video=' . $item->id;
                                                                break;
                                                            case 'quiz':
                                                                $urlParam = 'quiz=' . $item->id;
                                                                break;
                                                            case 'page':
                                                                $urlParam = 'page=' . $item->id;
                                                                break;
                                                            default:
                                                                $urlParam = 'video=' . $item->id; // fallback
                                                        }
                                                        ?>
                                                        <a href="<?= site_url('courses/course_view/' . $course->slug . '?' . $urlParam) ?>" class="item-content">
                                                            <?php endif; ?>

                                                            <!-- Item Icon -->
                                                            <div class="item-icon">
                                                                <?php if ($item->item_type === 'video'): ?>
                                                                    <i class="icon-play-circle-o" style="color:<?= $isUnitLocked ? '#adb5bd' : '#136ad5' ?>;"></i>
                                                                <?php elseif ($item->item_type === 'quiz'): ?>
                                                                    <i class="icon-question-circle" style="color:<?= $isUnitLocked ? '#adb5bd' : '#28a745' ?>;"></i>
                                                                <?php elseif ($item->item_type === 'page'): ?>
                                                                    <i class="icon-file-text-o" style="color:<?= $isUnitLocked ? '#adb5bd' : '#17a2b8' ?>;"></i>
                                                                <?php else: ?>
                                                                    <i class="icon-circle" style="color:<?= $isUnitLocked ? '#adb5bd' : '#6c757d' ?>;"></i>
                                                                <?php endif; ?>
                                                            </div>

                                                            <!-- Item Details -->
                                                            <div class="item-details">
                                                                <div class="item-title" style="<?= $isUnitLocked ? 'color: #adb5bd;' : '' ?>">
                                                                    <?= esc($item->title) ?>
                                                                </div>
                                                                <div class="item-meta">
                                                                    <?php
                                                                    $metadata = json_decode($item->metadata ?? '{}', true);

                                                                    // Display metadata based on item type
                                                                    if ($item->item_type === 'video') {
                                                                        $duration = isset($metadata['video_duration']) ? round($metadata['video_duration'] / 60) : null;
                                                                        if ($duration) {
                                                                            echo '<span class="item-duration" style="' . ($isUnitLocked ? 'color: #adb5bd;' : 'color: #6c757d;') . '"><i class="fas fa-clock"></i> ' . esc($duration) . ' دقيقة</span>';
                                                                        } else {
                                                                            echo '<span class="item-type" style="' . ($isUnitLocked ? 'color: #adb5bd;' : 'color: #136ad5;') . '"><i class="fas fa-video"></i> فيديو</span>';
                                                                        }
                                                                    } elseif ($item->item_type === 'quiz') {
                                                                        echo '<span class="item-type" style="' . ($isUnitLocked ? 'color: #adb5bd;' : 'color: #28a745;') . '"><i class="fas fa-question-circle"></i> اختبار تفاعلي</span>';
                                                                    } elseif ($item->item_type === 'page') {
                                                                        echo '<span class="item-type" style="' . ($isUnitLocked ? 'color: #adb5bd;' : 'color: #17a2b8;') . '"><i class="fas fa-file-alt"></i> صفحة إضافية</span>';
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>

                                                            <!-- Item Status -->
                                                            <div class="item-status">
                                                                <?php if ($item->id == $current_id && !$isUnitLocked): ?>
                                                                    <div class="status-indicator current">
                                                                        <i class="fas fa-play-circle"></i>
                                                                    </div>
                                                                <?php elseif ($isUnitLocked): ?>
                                                                    <div class="status-indicator locked">
                                                                        <i class="fas fa-lock"></i>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <div class="status-indicator available">
                                                                        <i class="fas fa-chevron-left"></i>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>

                                                            <?php if ($isUnitLocked): ?>
                                                    </div>
                                                <?php else: ?>
                                                    </a>
                                                <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div><!-- End .videos-accordion -->
            </div>

            <!-- Main Content (Video Player, Quiz, or Page Content) -->
            <div class="col-lg-8 main-content">
                <!-- Content Title -->
                <h2 class="video-title-main-video mb-2">
                    <?= esc($itemTitle ?? $video_title) ?>
                </h2>

                <!-- Dynamic Content Based on Item Type -->
                <?php

                if (isset($current_item_type)): ?>

                    <?php if ($current_item_type === 'video'): ?>
                        <!-- Video Player -->
                        <div class="course-preview-video">
                            <div class="video-container">
                                <?php if ($video_id): ?>
                                    <iframe
                                            src="https://iframe.mediadelivery.net/embed/<?= $video_library_id ?? '495222' ?>/<?= $video_id ?>?autoplay=false"
                                            loading="lazy"
                                            style="border: none; position: absolute; top: 0; left: 0; height: 100%; width: 100%;"
                                            allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"
                                            allowfullscreen="true">
                                    </iframe>
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center h-100 bg-light">
                                        <div class="text-center">
                                            <i class="fas fa-video fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">لا يوجد فيديو متاح</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Video Description -->
                        <p class="course-description-text">
                            <?= esc($itemDesc ?? $video_desc) ?>
                        </p>

                    <?php elseif ($current_item_type === 'quiz'): ?>
                        <!-- Quiz Content -->
                        <div class="quiz-content-area">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h4 class="mb-0">
                                        <i class="fas fa-question-circle mr-2"></i>
                                        Interactive Quiz
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <?php if (isset($quiz_data) && $quiz_data): ?>
                                        <div class="quiz-info mb-4">
                                            <h5><?= esc($quiz_data->quiz_title) ?></h5>
                                            <p class="text-muted"><?= esc($quiz_data->quiz_desc) ?></p>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="quiz-stat">
                                                        <i class="fas fa-clock text-warning"></i>
                                                        <span>Duration: <?= esc($quiz_data->time_limit ?? 'Unlimited') ?> minutes</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="quiz-stat">
                                                        <i class="fas fa-percentage text-info"></i>
                                                        <span>Pass Score: <?= esc($quiz_data->passing_score ?? '70') ?>%</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="quiz-stat">
                                                        <i class="fas fa-redo text-danger"></i>
                                                        <span>Attempts: <?= esc($quiz_data->user_attempt_count ?? 0) ?>/<?= esc($quiz_data->max_attempts ?? 3) ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- User Progress Information -->
                                            <?php if (isset($quiz_data->user_attempt_count) && $quiz_data->user_attempt_count > 0): ?>
                                                <div class="quiz-user-progress mt-3 p-3 bg-light rounded">
                                                    <h6 class="mb-2"><i class="fas fa-chart-line text-primary"></i> Your Progress in This Quiz</h6>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <small class="text-muted">Best Score:</small>
                                                            <strong class="text-success"><?= esc($quiz_data->user_best_score ?? 0) ?>%</strong>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <small class="text-muted">Remaining Attempts:</small>
                                                            <strong class="<?= ($quiz_data->remaining_attempts ?? 0) > 0 ? 'text-info' : 'text-danger' ?>">
                                                                <?= esc($quiz_data->remaining_attempts ?? 0) ?>
                                                            </strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="text-center">
                                            <?php if (isset($quiz_data->has_exceeded_attempts) && $quiz_data->has_exceeded_attempts): ?>
                                                <button class="btn btn-secondary btn-lg" disabled>
                                                    <i class="fas fa-ban mr-2"></i>
                                                    Maximum Attempts Exceeded
                                                </button>
                                                <p class="text-muted mt-2 small">
                                                    You have used all allowed attempts (<?= esc($quiz_data->max_attempts ?? 3) ?>) for this quiz.
                                                    <?php if (isset($quiz_data->user_best_score) && $quiz_data->user_best_score > 0): ?>
                                                        Your best score: <?= esc($quiz_data->user_best_score) ?>%
                                                    <?php endif; ?>
                                                </p>
                                            <?php else: ?>
                                                <button class="btn btn-success btn-lg take-embedded-quiz-btn"
                                                        data-quiz-id="<?= $quiz_data->id ?>"
                                                        data-quiz-title="<?= esc($quiz_data->quiz_title) ?>">
                                                    <i class="fas fa-play mr-2"></i>
                                                    <?php if (isset($quiz_data->user_attempt_count) && $quiz_data->user_attempt_count > 0): ?>
                                                        Retry Quiz
                                                    <?php else: ?>
                                                        Start Quiz
                                                    <?php endif; ?>
                                                </button>
                                                <?php if (isset($quiz_data->remaining_attempts) && $quiz_data->remaining_attempts > 0): ?>
                                                    <p class="text-info mt-2 small">
                                                        <i class="fas fa-info-circle"></i>
                                                        You have <?= esc($quiz_data->remaining_attempts) ?> attempt(s) remaining
                                                    </p>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center">
                                            <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                            <p class="text-muted">Quiz is currently unavailable</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                    <?php
                    elseif ($current_item_type === 'page'): ?>
                        <!-- Page Content -->
                        <div class="page-content-area">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h4 class="mb-0">
                                        <i class="fas fa-file-alt mr-2"></i>
                                        صفحة إضافية
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <?php if (isset($page_data) && $page_data): ?>
                                        <div class="page-content">
                                            <?php if ($page_data->desc): ?>
                                                <div class="page-description mb-3">
                                                    <p class="text-muted"><?= esc($page_data->desc) ?></p>
                                                </div>
                                            <?php endif; ?>

                                            <div class="page-body">
                                                <?= $page_data->content ?? '<p>لا يوجد محتوى متاح</p>' ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center">
                                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">المحتوى غير متاح حالياً</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                    <?php else: ?>
                        <!-- Default/Unknown Content Type -->
                        <div class="default-content-area">
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="fas fa-question fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">نوع المحتوى غير مدعوم</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <!-- Fallback to original video content -->
                    <div class="course-preview-video">
                        <div class="video-container">
                            <iframe
                                    src="https://iframe.mediadelivery.net/embed/<?= $metadata['video_library_id'] ?? '495222' ?>/<?= $video_id ?>?autoplay=false"
                                    loading="lazy"
                                    style="border: none; position: absolute; top: 0; left: 0; height: 100%; width: 100%;"
                                    allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"
                                    allowfullscreen="true">
                            </iframe>
                        </div>
                    </div>
                    <p class="course-description-text">
                        <?= esc($video_desc) ?>
                    </p>
                <?php endif; ?>

                <!-- Mark as Complete Button -->
                <div class="completion-section mt-4">
                    <?php
                    // Debug logging for current_item
                    error_log('COURSE_VIEW DEBUG - current_item: ' . json_encode($current_item ?? 'NOT_SET'));
                    error_log('COURSE_VIEW DEBUG - current_id: ' . ($current_id ?? 'NOT_SET'));
                    error_log('COURSE_VIEW DEBUG - course_id: ' . ($course->id ?? 'NOT_SET'));

                    ?>
                    <?php if (isset($current_item) && !empty($current_item['id'])): ?>
                        <button class="btn btn-success btn-block mark-complete-button"
                                onclick="markItemComplete(<?= $course->id ?>, <?= $current_item['id'] ?>)">
                            <i class="fas fa-check mr-2"></i>
                            تم الإكمال
                        </button>
                        <script>
                            console.log('COURSE_VIEW JS DEBUG - Course ID:', <?= $course->id ?>);
                            console.log('COURSE_VIEW JS DEBUG - Item ID:', <?= $current_item['id'] ?>);
                            console.log('COURSE_VIEW JS DEBUG - Current Item:', <?= json_encode($current_item) ?>);
                        </script>
                    <?php else: ?>
                        <button class="btn btn-secondary btn-block" disabled>
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            لا يمكن تحديد العنصر
                        </button>
                        <script>
                            console.log('COURSE_VIEW JS DEBUG - Current item missing or invalid:', <?= json_encode($current_item ?? 'NOT_SET') ?>);
                        </script>
                    <?php endif; ?>
                </div>

                <script>
                    function markItemComplete(courseId, itemId) {
                        console.log('MARK_COMPLETE DEBUG - Function called with:', {courseId, itemId});

                        if (!courseId || !itemId) {
                            console.error('MARK_COMPLETE ERROR - Missing parameters:', {courseId, itemId});
                            alert('خطأ: Course ID and Item ID required');
                            return;
                        }

                        // Disable button to prevent double clicks
                        const button = event.target;
                        button.disabled = true;
                        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>جاري الحفظ...';

                        console.log('MARK_COMPLETE DEBUG - Sending request to:', '<?= base_url('progress/mark-completed') ?>');
                        console.log('MARK_COMPLETE DEBUG - Request body:', {course_id: courseId, item_id: itemId});

                        fetch('<?= base_url('progress/mark-completed') ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                course_id: courseId,
                                item_id: itemId
                            })
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Update progress bar immediately
                                    const progressBar = document.querySelector('.progress-bar');
                                    if (progressBar && data.course_completion !== undefined) {
                                        progressBar.style.width = data.course_completion + '%';
                                        progressBar.setAttribute('aria-valuenow', data.course_completion);
                                        progressBar.textContent = data.course_completion + '%';
                                    }

                                    // Show success message briefly
                                    button.innerHTML = '<i class="fas fa-check mr-2"></i>مكتمل';
                                    button.classList.remove('btn-success');
                                    button.classList.add('btn-secondary');
                                    button.disabled = true;

                                    // Auto-navigate to next item or unit without confirmation
                                    if (data.next_item) {
                                        console.log(1111111111111111)
                                        setTimeout(() => {
                                            window.location.href = data.next_item.url;
                                        }, 500);
                                    } else if (data.next_unit) {
                                        console.log(22222222222222)
                                        setTimeout(() => {
                                            window.location.href = data.next_unit.url;
                                        }, 500);
                                    } else if (data.course_completed && data.redirect_url) {
                                        console.log(333333333333333333)
                                        // Course is completed, redirect to my_courses
                                        setTimeout(() => {
                                            window.location.href = data.redirect_url;
                                        }, 1000);
                                    }
                                    // No alert for course completion - just stay on current page
                                } else {
                                    alert('خطأ: ' + (data.message || 'فشل في حفظ التقدم'));
                                    button.disabled = false;
                                    button.innerHTML = '<i class="fas fa-check mr-2"></i>تم الإكمال';
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('خطأ في الاتصال');
                                button.disabled = false;
                                button.innerHTML = '<i class="fas fa-check mr-2"></i>تم الإكمال';
                            });
                    }
                </script>
            </div>
        </div>
    </div>
</div>

<!-- Include Video Progress Tracking Script -->
<script src="<?= base_url('assets/js/video-progress.js') ?>"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize video progress tracking if video exists
        <?php if ($current_item_type === 'video' && isset($current_item)): ?>
        const videoElement = document.querySelector('iframe[src*="mediadelivery.net"]');
        if (videoElement && <?= $current_item['id'] ?? 0 ?>) {
            // Initialize progress tracker for the current video using item_id
            const progressTracker = new VideoProgressTracker(videoElement, <?= $current_item['id'] ?? 0 ?>, {
                updateInterval: 10000, // Update every 10 seconds
                completionThreshold: 0.85, // Mark complete at 85%
                autoMarkComplete: true,
                apiEndpoint: '<?= base_url('progress/update') ?>',
                onUnitCompleted: function(result) {
                    // Update progress bar
                    const progressBar = document.querySelector('.progress-bar');
                    if (progressBar && result.course_completion) {
                        progressBar.style.width = result.course_completion + '%';
                        progressBar.setAttribute('aria-valuenow', result.course_completion);
                        progressBar.textContent = result.course_completion + '%';
                    }

                    // Show next unit button if available
                    if (result.next_unit) {
                        const nextBtn = document.querySelector('.btn-next');
                        if (nextBtn) {
                            nextBtn.href = result.next_unit.url;
                            nextBtn.classList.remove('disabled');
                        }
                    }
                }
            });
        }
        <?php endif; ?>

        // Handle navigation item clicks
        const navItems = document.querySelectorAll('.unit-item-link');

        navItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();

                // Remove active class from all items
                navItems.forEach(navItem => {
                    navItem.classList.remove('active');
                });

                // Add active class to clicked item
                this.classList.add('active');

                // Get the item ID from the href
                const href = this.getAttribute('href');
                const urlParams = new URLSearchParams(href.split('?')[1]);
                const itemId = urlParams.get('item_id');

                if (itemId) {
                    // Redirect to the new item
                    window.location.href = href;
                }
            });
        });

        // Handle accordion functionality
        const accordionButtons = document.querySelectorAll('.accordion-button');

        accordionButtons.forEach(button => {
            button.addEventListener('click', function() {
                const target = this.getAttribute('data-bs-target');
                const collapse = document.querySelector(target);

                if (collapse) {
                    // Toggle the collapse
                    if (collapse.classList.contains('show')) {
                        collapse.classList.remove('show');
                        this.classList.add('collapsed');
                        this.setAttribute('aria-expanded', 'false');
                    } else {
                        collapse.classList.add('show');
                        this.classList.remove('collapsed');
                        this.setAttribute('aria-expanded', 'true');
                    }
                }
            });
        });

        // Auto-expand accordion containing active item
        const activeItem = document.querySelector('.unit-item-link.active');
        if (activeItem) {
            const accordionCollapse = activeItem.closest('.accordion-collapse');
            if (accordionCollapse) {
                accordionCollapse.classList.add('show');
                const accordionButton = document.querySelector(`[data-bs-target="#${accordionCollapse.id}"]`);
                if (accordionButton) {
                    accordionButton.classList.remove('collapsed');
                    accordionButton.setAttribute('aria-expanded', 'true');
                }
            }
        }
    });
</script>

<!-- Embedded Quiz Modal -->
<div id="embeddedQuizModal" class="embedded-quiz-modal" style="display: none;">
    <div class="embedded-quiz-container">
        <div class="embedded-quiz-header">
            <div class="quiz-title-section">
                <h4 id="quizTitle">اختبار</h4>
                <button class="close-quiz-btn" onclick="EmbeddedQuiz.close()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="quiz-progress-section">
                <div class="quiz-timer">
                    <i class="fas fa-clock"></i>
                    <span id="quizTimer">00:00</span>
                </div>
                <div class="quiz-progress">
                    <span id="questionCounter">1 من 5</span>
                    <div class="progress-bar-container">
                        <div class="progress-bar" id="quizProgressBar"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="embedded-quiz-content">
            <div id="quizQuestions"></div>
        </div>

        <div class="embedded-quiz-navigation">
            <button id="prevQuestionBtn" class="nav-btn prev-btn" onclick="EmbeddedQuiz.previousQuestion()">
                <i class="fas fa-chevron-right"></i> السابق
            </button>
            <button id="nextQuestionBtn" class="nav-btn next-btn" onclick="EmbeddedQuiz.nextQuestion()">
                التالي <i class="fas fa-chevron-left"></i>
            </button>
            <button id="submitQuizBtn" class="nav-btn submit-btn" onclick="EmbeddedQuiz.submitQuiz()" style="display: none;">
                <i class="fas fa-check"></i> إرسال الإجابات
            </button>
        </div>

        <div id="quizResults" class="quiz-results" style="display: none;">
            <div class="results-content">
                <div class="results-header">
                    <i class="fas fa-trophy results-icon"></i>
                    <h3>نتائج الاختبار</h3>
                </div>
                <div class="results-stats">
                    <div class="stat-item">
                        <span class="stat-label">النتيجة:</span>
                        <span class="stat-value" id="finalScore">0%</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">الإجابات الصحيحة:</span>
                        <span class="stat-value" id="correctAnswers">0/0</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">الوقت المستغرق:</span>
                        <span class="stat-value" id="completionTime">0 دقيقة</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">الحالة:</span>
                        <span class="stat-value" id="passStatus">-</span>
                    </div>
                </div>
                <div class="results-actions">
                    <button class="btn btn-primary" onclick="EmbeddedQuiz.continueToNext()">
                        <i class="fas fa-arrow-left"></i> متابعة للعنصر التالي
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>



<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<!-- Additional JS if needed -->
<!-- Include Embedded Quiz CSS and JS -->
<link rel="stylesheet" href="<?= base_url() ?>site/css/embedded-quiz.css">
<script src="<?= base_url() ?>site/js/embedded-quiz.js"></script>

<?php $this->endSection();

// ...existing code ...
