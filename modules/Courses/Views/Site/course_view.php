<?php $this->extend('site_layout/template'); ?>
<?php $this->section('content'); ?>

<style>
    /* =============================================
       COURSE VIEW - CSS VARIABLES (LIGHT MODE)
       ============================================= */
    :root {
        --cv-primary: #136ad5;
        --cv-primary-dark: #0f5bb8;
        --cv-primary-light: #1573e8;
        --cv-secondary: #00aeff;
        --cv-accent: #f59e0b;
        --cv-success: #10b981;
        --cv-danger: #ef4444;

        --cv-bg-page: #f0f4f8;
        --cv-bg-card: #ffffff;
        --cv-bg-sidebar: #ffffff;
        --cv-bg-accent: rgba(19, 106, 213, 0.05);
        --cv-bg-accent-hover: rgba(19, 106, 213, 0.1);
        --cv-bg-item: #ffffff;
        --cv-bg-item-active: linear-gradient(135deg, #f0f7ff 0%, #e0eeff 100%);
        --cv-bg-item-locked: #f8f9fa;
        --cv-bg-accordion-body: #fafbfc;
        --cv-bg-progress: linear-gradient(135deg, #f0f7ff 0%, #e0eeff 100%);
        --cv-bg-stat: #ffffff;
        --cv-bg-nav-prev: #ffffff;

        --cv-text-primary: #1a202c;
        --cv-text-secondary: #4a5568;
        --cv-text-muted: #718096;
        --cv-text-light: #a0aec0;
        --cv-text-title: #1a202c;

        --cv-border: rgba(19, 106, 213, 0.12);
        --cv-border-light: #e9ecef;
        --cv-border-item-active: #136ad5;

        --cv-shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.06);
        --cv-shadow-md: 0 4px 16px rgba(0, 0, 0, 0.08);
        --cv-shadow-lg: 0 8px 30px rgba(0, 0, 0, 0.1);
        --cv-shadow-primary: 0 4px 15px rgba(19, 106, 213, 0.2);

        --cv-radius-sm: 8px;
        --cv-radius-md: 12px;
        --cv-radius-lg: 16px;
        --cv-radius-xl: 20px;

        --cv-gradient-primary: linear-gradient(135deg, #136ad5 0%, #00aeff 100%);
        --cv-gradient-card: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    }

    /* =============================================
       DARK MODE VARIABLES
       ============================================= */
    body.dark-mode {
        --cv-bg-page: #0f172a;
        --cv-bg-card: #1e293b;
        --cv-bg-sidebar: #1e293b;
        --cv-bg-accent: rgba(96, 165, 250, 0.08);
        --cv-bg-accent-hover: rgba(96, 165, 250, 0.14);
        --cv-bg-item: #1e293b;
        --cv-bg-item-active: linear-gradient(135deg, #162032 0%, #1a2a42 100%);
        --cv-bg-item-locked: #162032;
        --cv-bg-accordion-body: #162032;
        --cv-bg-progress: linear-gradient(135deg, #162032 0%, #1a2a42 100%);
        --cv-bg-stat: #162032;
        --cv-bg-nav-prev: #1e293b;

        --cv-text-primary: #f1f5f9;
        --cv-text-secondary: #94a3b8;
        --cv-text-muted: #64748b;
        --cv-text-light: #475569;
        --cv-text-title: #f8fafc;

        --cv-border: rgba(255, 255, 255, 0.08);
        --cv-border-light: rgba(255, 255, 255, 0.06);
        --cv-border-item-active: #60a5fa;

        --cv-shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.3);
        --cv-shadow-md: 0 4px 16px rgba(0, 0, 0, 0.35);
        --cv-shadow-lg: 0 8px 30px rgba(0, 0, 0, 0.4);
        --cv-shadow-primary: 0 4px 15px rgba(96, 165, 250, 0.2);

        --cv-gradient-card: linear-gradient(135deg, #1e293b 0%, #162032 100%);
        --cv-gradient-topbar: linear-gradient(135deg, #1a2540 0%, #0f1e35 100%);
    }

    body.dark-mode .course-topbar {
        background: var(--cv-gradient-topbar);
        border-bottom: 1px solid rgba(96, 165, 250, 0.12);
    }

    /* =============================================
       PAGE LAYOUT
       ============================================= */
    .untree_co-section {
        padding-top: 0;
        padding-bottom: 0;
        background: var(--cv-bg-page);
        min-height: 100vh;
    }

    /* =============================================
       COURSE TOP BAR (Title + Description)
       ============================================= */
    .course-topbar {
        background: var(--cv-gradient-primary);
        padding: 1.5rem 2rem;
        position: relative;
        overflow: hidden;
    }

    .course-topbar::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.06);
        border-radius: 50%;
        pointer-events: none;
    }

    .course-topbar-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        position: relative;
        z-index: 1;
    }

    .course-topbar .course-topbar-title {
        font-family: 'Alexandria-Medium', system-ui, sans-serif;
        font-size: 1.8rem;
        font-weight: normal;
        color: #ffffff !important;
        margin: 0;
        line-height: 1.4;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .course-topbar .course-topbar-desc {
        font-family: 'Alexandria-Regular', system-ui, sans-serif;
        font-size: 1.05rem;
        color: #ffffff !important;
        margin: 0.5rem 0 0;
        line-height: 1.7;
        max-width: 700px;
    }

    .course-topbar-progress {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-shrink: 0;
    }

    .topbar-progress-circle {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.15);
        border: 3px solid rgba(255, 255, 255, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        color: white;
        font-weight: 700;
        font-size: 0.85rem;
        line-height: 1;
        backdrop-filter: blur(4px);
    }

    .topbar-progress-circle small {
        font-size: 0.6rem;
        opacity: 0.8;
        font-weight: 500;
    }

    /* =============================================
       MAIN LAYOUT - TWO COLUMN
       ============================================= */
    .course-view-layout {
        display: grid;
        grid-template-columns: 340px 1fr;
        min-height: calc(100vh - 120px);
    }

    /* =============================================
       SIDEBAR (LEFT PANEL - Course Outline)
       ============================================= */
    .course-sidebar-panel {
        background: var(--cv-bg-sidebar);
        border-left: 1px solid var(--cv-border);
        display: flex;
        flex-direction: column;
        height: calc(100vh - 120px);
        position: sticky;
        top: 70px;
        overflow: hidden;
    }

    .sidebar-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--cv-border);
        background: var(--cv-bg-accent);
        flex-shrink: 0;
    }

    .sidebar-header h6 {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--cv-primary);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin: 0;
    }

    .sidebar-scroll {
        flex: 1;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: var(--cv-border) transparent;
    }

    .sidebar-scroll::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar-scroll::-webkit-scrollbar-track {
        background: transparent;
    }

    .sidebar-scroll::-webkit-scrollbar-thumb {
        background: var(--cv-border);
        border-radius: 4px;
    }

    /* =============================================
       ACCORDION (Units & Items)
       ============================================= */
    .videos-accordion {
        direction: rtl;
    }

    .videos-accordion .accordion-item {
        border: none;
        border-bottom: 1px solid var(--cv-border-light);
        background: transparent;
    }

    .videos-accordion .accordion-item:last-child {
        border-bottom: none;
    }

    .videos-accordion .accordion-header {
        margin: 0;
    }

    .videos-accordion .accordion-button {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--cv-text-primary);
        background: var(--cv-bg-card);
        border: none;
        border-radius: 0;
        box-shadow: none;
        padding: 0.875rem 1.25rem;
        text-align: right;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .videos-accordion .accordion-button:not(.collapsed) {
        background: var(--cv-bg-accent);
        color: var(--cv-primary);
        box-shadow: none;
    }

    .videos-accordion .accordion-button:hover {
        background: var(--cv-bg-accent-hover);
    }

    .videos-accordion .accordion-button.unit-locked {
        color: var(--cv-danger);
        opacity: 0.7;
    }

    .videos-accordion .accordion-button::after {
        filter: none;
        opacity: 0.5;
        margin-right: auto;
        margin-left: 0;
    }

    .videos-accordion .accordion-collapse {
        border-top: 1px solid var(--cv-border-light);
    }

    .videos-accordion .accordion-body {
        padding: 0;
        background: var(--cv-bg-accordion-body);
    }

    /* =============================================
       COURSE ITEMS (in accordion)
       ============================================= */
    .unit-items-container {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .course-item {
        background: var(--cv-bg-item);
        border-bottom: 1px solid var(--cv-border-light);
        transition: all 0.2s ease;
        position: relative;
    }

    .course-item:last-child {
        border-bottom: none;
    }

    .course-item:hover:not(.locked-item) {
        background: var(--cv-bg-accent);
    }

    .course-item.active-item {
        background: var(--cv-bg-item-active);
        border-right: 3px solid var(--cv-primary);
    }

    body.dark-mode .course-item.active-item {
        border-right-color: #60a5fa;
    }

    .course-item.locked-item {
        background: var(--cv-bg-item-locked);
        opacity: 0.65;
    }

    .item-content {
        display: flex;
        align-items: center;
        padding: 0.75rem 1.25rem;
        text-decoration: none;
        color: inherit;
        width: 100%;
        gap: 0.75rem;
    }

    .item-content.locked-content {
        cursor: not-allowed;
    }

    .item-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: var(--cv-radius-sm);
        background: var(--cv-bg-accent);
        flex-shrink: 0;
        transition: all 0.2s ease;
    }

    .course-item.active-item .item-icon {
        background: rgba(19, 106, 213, 0.15);
    }

    body.dark-mode .course-item.active-item .item-icon {
        background: rgba(96, 165, 250, 0.15);
    }

    .item-icon i {
        font-size: 15px;
    }

    .item-details {
        flex: 1;
        min-width: 0;
    }

    .item-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--cv-text-primary);
        margin-bottom: 2px;
        line-height: 1.4;
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .course-item.active-item .item-title {
        color: var(--cv-primary);
    }

    body.dark-mode .course-item.active-item .item-title {
        color: #60a5fa;
    }

    .item-meta {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.75rem;
        color: var(--cv-text-muted);
    }

    .item-duration,
    .item-type {
        display: flex;
        align-items: center;
        gap: 3px;
        font-size: 0.72rem;
        font-weight: 500;
    }

    .item-status {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        flex-shrink: 0;
    }

    .status-indicator {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        font-size: 10px;
        transition: all 0.2s ease;
    }

    .status-indicator.current {
        background: var(--cv-gradient-primary);
        color: white;
        box-shadow: var(--cv-shadow-primary);
    }

    .status-indicator.locked {
        background: rgba(239, 68, 68, 0.1);
        color: var(--cv-danger);
    }

    .status-indicator.available {
        background: var(--cv-bg-accent);
        color: var(--cv-text-muted);
    }

    .course-item:hover .status-indicator.available {
        background: var(--cv-bg-accent-hover);
        color: var(--cv-primary);
    }

    /* =============================================
       MAIN CONTENT AREA (Right Panel)
       ============================================= */
    .course-main-content {
        background: var(--cv-bg-page);
        padding: 1.75rem;
        overflow-y: auto;
    }

    /* =============================================
       CONTENT TITLE
       ============================================= */
    .content-title-bar {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .video-title-main-video {
        font-size: 1.55rem;
        color: var(--cv-text-title);
        margin: 0 0 1.25rem 0;
        font-weight: 800;
        line-height: 1.3;
        flex: 1;
        padding-bottom: 0.85rem;
        border-bottom: 3px solid transparent;
        border-image: var(--cv-gradient-primary) 1;
        position: relative;
        letter-bottom: -0.3px;
    }

    .video-title-main-video::after {
        content: '';
        position: absolute;
        bottom: -3px;
        right: 0;
        width: 60px;
        height: 3px;
        background: var(--cv-accent);
        border-radius: 2px;
    }

    /* =============================================
       VIDEO PLAYER
       ============================================= */
    .course-preview-video {
        margin-bottom: 1.5rem;
    }

    .course-preview-video .video-container {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
        border-radius: var(--cv-radius-lg);
        box-shadow: var(--cv-shadow-lg);
        background: #000;
    }

    .course-preview-video .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
        border-radius: var(--cv-radius-lg);
    }

    /* =============================================
       VIDEO DESCRIPTION
       ============================================= */
    .course-description-text {
        color: var(--cv-text-secondary);
        line-height: 1.8;
        margin-bottom: 1.5rem;
        font-size: 1rem;
        background: var(--cv-bg-card);
        padding: 1.25rem 1.5rem;
        border-radius: var(--cv-radius-md);
        border: 1px solid var(--cv-border);
        box-shadow: var(--cv-shadow-sm);
    }

    /* =============================================
       QUIZ CONTENT
       ============================================= */
    .quiz-content-area {
        /* Removed enforced ltr to respect native RTL flow */
    }

    .quiz-content-area .card {
        border: none;
        box-shadow: var(--cv-shadow-md);
        border-radius: var(--cv-radius-lg);
        overflow: hidden;
        background: var(--cv-bg-card);
        border: 1px solid var(--cv-border);
    }

    .quiz-content-area .card-header {
        border-bottom: 1px solid var(--cv-border);
        padding: 1.5rem;
        background: var(--cv-gradient-primary);
        color: white;
    }

    .quiz-content-area .card-body {
        padding: 2rem;
        background: var(--cv-bg-card);
        color: var(--cv-text-primary);
    }

    .quiz-stat {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        font-size: 15px;
        color: var(--cv-text-secondary);
    }

    .quiz-stat i {
        width: 22px;
        text-align: center;
        color: var(--cv-primary);
    }

    .quiz-user-progress {
        background: var(--cv-bg-accent) !important;
        border-radius: var(--cv-radius-md);
        border: 1px solid var(--cv-border);
    }

    /* =============================================
       PAGE CONTENT
       ============================================= */
    .page-content-area .card {
        border: none;
        box-shadow: var(--cv-shadow-md);
        border-radius: var(--cv-radius-lg);
        overflow: hidden;
        background: var(--cv-bg-card);
        border: 1px solid var(--cv-border);
    }

    .page-content-area .card-header {
        border-bottom: 1px solid var(--cv-border);
        padding: 1.5rem;
        background: var(--cv-gradient-primary);
        color: white;
    }

    .page-content-area .card-body {
        padding: 2rem;
        background: var(--cv-bg-card);
    }

    .page-content .page-body {
        line-height: 1.9;
        font-size: 16px;
        color: var(--cv-text-secondary);
    }

    .page-content .page-body h1,
    .page-content .page-body h2,
    .page-content .page-body h3,
    .page-content .page-body h4,
    .page-content .page-body h5,
    .page-content .page-body h6 {
        margin-top: 1.75rem;
        margin-bottom: 1rem;
        color: var(--cv-text-title);
        font-weight: 600;
    }

    .page-content .page-body p {
        margin-bottom: 1rem;
    }

    .page-content .page-body ul,
    .page-content .page-body ol {
        margin-bottom: 1rem;
        padding-right: 1.5rem;
    }

    /* =============================================
       DEFAULT CONTENT
       ============================================= */
    .default-content-area .card {
        border: 2px dashed var(--cv-border);
        background: var(--cv-bg-accent);
        border-radius: var(--cv-radius-lg);
    }

    .default-content-area .card-body {
        color: var(--cv-text-muted);
    }

    /* =============================================
       COMPLETION SECTION
       ============================================= */
    .completion-section {
        margin-top: 1.5rem;
    }

    .btn.btn-success.btn-block.mark-complete-button {
        padding: 0.875rem 2rem;
        font-size: 1rem;
        border-radius: var(--cv-radius-md);
        font-weight: 700;
        background: var(--cv-gradient-primary);
        border: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        width: 100%;
        box-shadow: var(--cv-shadow-primary);
        letter-spacing: 0.3px;
    }

    .btn.btn-success.btn-block.mark-complete-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(19, 106, 213, 0.35);
    }

    .btn.btn-success.btn-block.mark-complete-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn.btn-success.btn-block.mark-complete-button:hover::before {
        left: 100%;
    }

    /* =============================================
       PROGRESS SECTION (in sidebar bottom)
       ============================================= */
    .sidebar-progress-section {
        padding: 1.25rem 1.5rem;
        border-top: 1px solid var(--cv-border);
        background: var(--cv-bg-progress);
        flex-shrink: 0;
    }

    .progress-label {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.625rem;
        font-weight: 600;
        color: var(--cv-text-primary);
        font-size: 0.85rem;
    }

    .progress-percentage {
        background: var(--cv-gradient-primary);
        color: white;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
        box-shadow: var(--cv-shadow-primary);
    }

    .progress {
        height: 8px;
        background-color: var(--cv-border-light);
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 1rem;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    body.dark-mode .progress {
        background-color: rgba(255, 255, 255, 0.08);
    }

    .progress-bar {
        background: var(--cv-gradient-primary);
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
        background-image: linear-gradient(-45deg,
                rgba(255, 255, 255, .2) 25%, transparent 25%,
                transparent 50%, rgba(255, 255, 255, .2) 50%,
                rgba(255, 255, 255, .2) 75%, transparent 75%, transparent);
        background-size: 20px 20px;
        animation: progress-move 2s linear infinite;
    }

    @keyframes progress-move {
        0% {
            background-position: 0 0;
        }

        100% {
            background-position: 20px 20px;
        }
    }

    .progress-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.625rem;
    }

    .progress-stat {
        text-align: center;
        background: var(--cv-bg-stat);
        padding: 0.625rem 0.5rem;
        border-radius: var(--cv-radius-sm);
        border: 1px solid var(--cv-border);
        transition: all 0.2s ease;
    }

    .progress-stat:hover {
        transform: translateY(-1px);
        box-shadow: var(--cv-shadow-sm);
        border-color: var(--cv-primary);
    }

    .progress-stat-number {
        display: block;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--cv-primary);
        margin-bottom: 2px;
        line-height: 1;
    }

    body.dark-mode .progress-stat-number {
        color: #60a5fa;
    }

    .progress-stat-label {
        font-size: 0.72rem;
        color: var(--cv-text-muted);
        font-weight: 500;
    }

    /* =============================================
       NAVIGATION BUTTONS (in sidebar)
       ============================================= */
    .sidebar-nav-buttons {
        padding: 1rem 1.25rem;
        border-top: 1px solid var(--cv-border);
        background: var(--cv-bg-sidebar);
        flex-shrink: 0;
        display: flex;
        gap: 0.625rem;
    }

    .sidebar-nav-buttons .btn {
        flex: 1;
        padding: 0.625rem 1rem;
        border-radius: var(--cv-radius-sm);
        font-weight: 600;
        transition: all 0.25s ease;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.375rem;
    }

    .sidebar-nav-buttons .prev-btn {
        background: var(--cv-bg-nav-prev);
        color: var(--cv-text-secondary);
        border: 1.5px solid var(--cv-border-light);
    }

    .sidebar-nav-buttons .prev-btn:hover:not(:disabled) {
        background: var(--cv-bg-accent);
        border-color: var(--cv-primary);
        color: var(--cv-primary);
        transform: translateY(-1px);
    }

    .sidebar-nav-buttons .next-btn {
        background: var(--cv-gradient-primary);
        color: white;
        border: none;
        box-shadow: var(--cv-shadow-primary);
    }

    .sidebar-nav-buttons .next-btn:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(19, 106, 213, 0.3);
    }

    .sidebar-nav-buttons .btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
        transform: none !important;
        box-shadow: none !important;
    }

    /* =============================================
       BADGE STYLES
       ============================================= */
    .badge-unit-count {
        background: var(--cv-bg-accent);
        color: var(--cv-primary);
        font-size: 0.7rem;
        padding: 2px 7px;
        border-radius: 20px;
        font-weight: 600;
        border: 1px solid var(--cv-border);
        margin-right: auto;
    }

    body.dark-mode .badge-unit-count {
        color: #60a5fa;
    }

    .badge-free {
        background: rgba(16, 185, 129, 0.12);
        color: var(--cv-success);
        font-size: 0.7rem;
        padding: 2px 7px;
        border-radius: 20px;
        font-weight: 600;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    /* =============================================
       RESPONSIVE DESIGN
       ============================================= */
    @media (max-width: 1199.98px) {
        .course-view-layout {
            grid-template-columns: 300px 1fr;
        }
    }

    @media (max-width: 991.98px) {
        .course-view-layout {
            grid-template-columns: 1fr;
            grid-template-rows: auto 1fr;
        }

        .course-sidebar-panel {
            height: auto;
            position: static;
            border-left: none;
            border-bottom: 1px solid var(--cv-border);
        }

        .sidebar-scroll {
            max-height: 350px;
        }

        .course-main-content {
            padding: 1.25rem;
        }

        .course-topbar {
            padding: 1rem 1.25rem;
        }

        .course-topbar-title {
            font-size: 1.1rem;
        }
    }

    @media (max-width: 767.98px) {
        .course-topbar-inner {
            flex-direction: column;
            align-items: flex-start;
        }

        .course-topbar-progress {
            align-self: flex-end;
        }

        .video-title-main-video {
            font-size: 1.25rem;
        }

        .progress-stats {
            grid-template-columns: repeat(3, 1fr);
        }

        .course-main-content {
            padding: 1rem;
        }
    }

    @media (max-width: 575.98px) {
        .sidebar-nav-buttons {
            flex-direction: column;
        }

        .progress-stats {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }
    }

    /* =============================================
       ANIMATIONS
       ============================================= */
    @keyframes pulse-dot {

        0%,
        100% {
            opacity: 1;
            transform: translateY(-50%) scale(1);
        }

        50% {
            opacity: 0.7;
            transform: translateY(-50%) scale(1.2);
        }
    }

    .active-item .status-indicator.current {
        animation: pulse-glow 2s ease-in-out infinite;
    }

    @keyframes pulse-glow {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(19, 106, 213, 0.4);
        }

        50% {
            box-shadow: 0 0 0 6px rgba(19, 106, 213, 0);
        }
    }

    body.dark-mode .active-item .status-indicator.current {
        animation: pulse-glow-dark 2s ease-in-out infinite;
    }

    @keyframes pulse-glow-dark {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(96, 165, 250, 0.4);
        }

        50% {
            box-shadow: 0 0 0 6px rgba(96, 165, 250, 0);
        }
    }

    /* =============================================
       EMPTY / NO VIDEO STATE
       ============================================= */
    .no-content-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem 2rem;
        background: var(--cv-bg-card);
        border-radius: var(--cv-radius-lg);
        border: 2px dashed var(--cv-border);
        color: var(--cv-text-muted);
        text-align: center;
        min-height: 200px;
    }

    .no-content-placeholder i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.4;
    }

    .no-content-placeholder p {
        margin: 0;
        font-size: 0.95rem;
    }
</style>

<!-- ============================================
     COURSE VIEW PAGE - NEW LAYOUT
     ============================================ -->

<!-- Top Bar: Course Title + Progress Circle -->
<div class="course-topbar">
    <div class="course-topbar-inner">
        <div>
            <h1 class="course-topbar-title"><?= esc($title) ?></h1>
            <?php if (!empty($course->course_desc)): ?>
                <p class="course-topbar-desc"><?= esc($course->course_desc) ?></p>
            <?php endif; ?>
        </div>
        <div class="course-topbar-progress">
            <div class="topbar-progress-circle">
                <?= round($course_progress) ?>%
                <small>مكتمل</small>
            </div>
        </div>
    </div>
</div>

<!-- Main Layout: Sidebar + Content -->
<div class="course-view-layout">

    <!-- ==================== SIDEBAR ==================== -->
    <div class="course-sidebar-panel">

        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <h6><i class="icon-list-ul" style="margin-left:6px;"></i> محتوى الكورس</h6>
        </div>

        <!-- Scrollable Accordion -->
        <div class="sidebar-scroll">
            <div class="videos-accordion accordion" id="videoAccordion">
                <?php foreach ($units as $unit): ?>
                    <?php
                    $hasFreeItems = false;
                    if (isset($unit->items)) {
                        foreach ($unit->items as $itm) {
                            if (isset($itm->is_free) && $itm->is_free == 1) {
                                $hasFreeItems = true;
                                break;
                            }
                        }
                    }
                    $isUnitLocked = !($unit->is_enrolled ?? false) && !$hasFreeItems;
                    ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?= esc($unit->id) ?>">
                            <button
                                class="accordion-button <?= isset($unit->is_open) && $unit->is_open ? '' : 'collapsed' ?> <?= $isUnitLocked ? 'unit-locked' : '' ?>"
                                type="button" data-toggle="collapse" data-target="#collapse<?= esc($unit->id) ?>"
                                aria-expanded="<?= isset($unit->is_open) && $unit->is_open ? 'true' : 'false' ?>"
                                aria-controls="collapse<?= esc($unit->id) ?>">
                                <?= esc($unit->unit_name) ?>
                                <?php if ($isUnitLocked): ?>
                                    <i class="fas fa-lock"
                                        style="font-size:11px; margin-right:4px; color:var(--cv-danger);"></i>
                                <?php endif; ?>
                                <span class="badge-unit-count"><?= count($unit->items ?? []) ?></span>
                            </button>
                        </h2>
                        <div id="collapse<?= esc($unit->id) ?>"
                            class="collapse <?= isset($unit->is_open) && $unit->is_open ? 'show' : '' ?>"
                            aria-labelledby="heading<?= esc($unit->id) ?>" data-parent="#videoAccordion">
                            <div class="accordion-body">
                                <div class="unit-items-container">
                                    <?php if (isset($unit->items)): ?>
                                        <?php foreach ($unit->items as $item): ?>
                                            <?php
                                            $isItemFree = isset($item->is_free) && $item->is_free == 1;
                                            $isItemLocked = !($unit->is_enrolled ?? false) && !$isItemFree;
                                            $itemClass = $item->id == $current_id ? 'active-item' : '';
                                            if ($isItemLocked) {
                                                $itemClass .= ' locked-item';
                                            }
                                            ?>

                                            <div class="course-item <?= $itemClass ?>">
                                                <?php if ($isItemLocked): ?>
                                                    <div class="item-content locked-content">
                                                    <?php else: ?>
                                                        <?php $urlParam = 'item=' . $item->id; ?>
                                                        <a href="<?= site_url('courses/course_view/' . $course->slug . '?' . $urlParam) ?>"
                                                            class="item-content unit-item-link" data-item-id="<?= $item->id ?>"
                                                            data-item-type="<?= $item->item_type ?>">
                                                        <?php endif; ?>

                                                        <?php $isCompleted = isset($completedItemIds) && in_array($item->id, $completedItemIds); ?>
                                                        <!-- Item Icon -->
                                                        <div class="item-icon">
                                                            <?php if ($isCompleted): ?>
                                                                <i class="fas fa-check-circle" style="color:var(--cv-success);"></i>
                                                            <?php elseif ($item->item_type === 'video'): ?>
                                                                <i class="icon-play-circle-o"
                                                                    style="color:<?= $isItemLocked ? 'var(--cv-text-light)' : 'var(--cv-primary)' ?>;"></i>
                                                            <?php elseif ($item->item_type === 'quiz'): ?>
                                                                <i class="icon-question-circle"
                                                                    style="color:<?= $isItemLocked ? 'var(--cv-text-light)' : 'var(--cv-success)' ?>;"></i>
                                                            <?php elseif ($item->item_type === 'page'): ?>
                                                                <i class="icon-file-text-o"
                                                                    style="color:<?= $isItemLocked ? 'var(--cv-text-light)' : '#17a2b8' ?>;"></i>
                                                            <?php else: ?>
                                                                <i class="icon-circle" style="color:var(--cv-text-muted);"></i>
                                                            <?php endif; ?>
                                                        </div>

                                                        <!-- Item Details -->
                                                        <div class="item-details">
                                                            <div class="item-title"><?= esc($item->title) ?></div>
                                                            <div class="item-meta">
                                                                <?php
                                                                $metadata = json_decode($item->metadata ?? '{}', true);
                                                                if ($item->item_type === 'video') {
                                                                    $duration = isset($metadata['video_duration']) ? round((int) $metadata['video_duration'] / 60) : null;
                                                                    if ($duration) {
                                                                        echo '<span class="item-duration"><i class="fas fa-clock"></i> ' . esc($duration) . ' د</span>';
                                                                    } else {
                                                                        echo '<span class="item-type"><i class="fas fa-video"></i> فيديو</span>';
                                                                    }
                                                                } elseif ($item->item_type === 'quiz') {
                                                                    echo '<span class="item-type"><i class="fas fa-question-circle"></i> اختبار</span>';
                                                                } elseif ($item->item_type === 'page') {
                                                                    echo '<span class="item-type"><i class="fas fa-file-alt"></i> صفحة</span>';
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>

                                                        <!-- Item Status -->
                                                        <div class="item-status">
                                                            <?php if ($item->id == $current_id && !$isItemLocked): ?>
                                                                <div class="status-indicator current">
                                                                    <i class="fas fa-play"></i>
                                                                </div>
                                                            <?php elseif ($isItemLocked): ?>
                                                                <div class="status-indicator locked">
                                                                    <i class="fas fa-lock"></i>
                                                                </div>
                                                            <?php else: ?>
                                                                <div class="status-indicator available">
                                                                    <i class="fas fa-chevron-left"></i>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>

                                                        <?php if ($isItemLocked): ?>
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
        </div><!-- End .sidebar-scroll -->

        <!-- Progress Section (bottom of sidebar) -->
        <div class="sidebar-progress-section">
            <div class="progress-label">
                <span>تقدم الكورس</span>
                <span class="progress-percentage"><?= esc($course_progress) ?>%</span>
            </div>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: <?= esc($course_progress) ?>%;"
                    aria-valuenow="<?= esc($course_progress) ?>" aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
            <div class="progress-stats">
                <div class="progress-stat">
                    <span class="progress-stat-number"><?= count($units) ?></span>
                    <span class="progress-stat-label">الوحدات</span>
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

        <!-- Navigation Buttons -->
        <div class="sidebar-nav-buttons">
            <?php if ($prevLessonUrl): ?>
                <a class="btn prev-btn" href="<?= $prevLessonUrl ?>">
                    <i class="fas fa-chevron-right"></i> السابق
                </a>
            <?php else: ?>
                <button class="btn prev-btn" disabled>
                    <i class="fas fa-chevron-right"></i> السابق
                </button>
            <?php endif; ?>

            <?php if ($nextLessonUrl): ?>
                <a class="btn next-btn" href="<?= $nextLessonUrl ?>">
                    التالي <i class="fas fa-chevron-left"></i>
                </a>
            <?php else: ?>
                <button class="btn next-btn" disabled>
                    التالي <i class="fas fa-chevron-left"></i>
                </button>
            <?php endif; ?>
        </div>

    </div><!-- End .course-sidebar-panel -->


    <!-- ==================== MAIN CONTENT ==================== -->
    <div class="course-main-content">
        <!-- Content Title -->
        <h2 class="video-title-main-video">
            <?= esc($itemTitle ?? $video_title) ?>
        </h2>

        <!-- Dynamic Content Based on Item Type -->
        <?php if (isset($current_item_type)): ?>

            <?php if ($current_item_type === 'video'): ?>
                <!-- Video Player -->
                <div class="course-preview-video">
                    <div class="video-container">
                        <?php if ($video_id): ?>
                            <iframe
                                src="https://player.mediadelivery.net/embed/<?= $video_library_id ?? '495222' ?>/<?= $video_id ?>?autoplay=false"
                                loading="lazy" style="border: none; position: absolute; top: 0; left: 0; height: 100%; width: 100%;"
                                allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"
                                allowfullscreen="true">
                            </iframe>
                        <?php else: ?>
                            <div class="no-content-placeholder">
                                <i class="fas fa-video"></i>
                                <p>لا يوجد فيديو متاح</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Video Description -->
                <?php if (!empty($itemDesc ?? $video_desc)): ?>
                    <p class="course-description-text"><?= esc($itemDesc ?? $video_desc) ?></p>
                <?php endif; ?>

            <?php elseif ($current_item_type === 'quiz'): ?>
                <!-- Quiz Content -->
                <div class="quiz-content-area">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">
                                <i class="fas fa-question-circle mr-2"></i>
                                اختبار تفاعلي
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
                                                <i class="fas fa-clock"></i>
                                                <span>المدة: <?= esc($quiz_data->time_limit ?? 'غير محدود') ?> دقيقة</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="quiz-stat">
                                                <i class="fas fa-percentage"></i>
                                                <span>درجة النجاح: <span
                                                        dir="ltr"><?= esc($quiz_data->passing_score ?? '70') ?>%</span></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="quiz-stat">
                                                <i class="fas fa-redo"></i>
                                                <span dir="rtl">المحاولات:
                                                    <?= esc($quiz_data->user_attempt_count ?? 0) ?>/<?= esc($quiz_data->max_attempts ?? 3) ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if (isset($quiz_data->user_attempt_count) && $quiz_data->user_attempt_count > 0): ?>
                                        <div class="quiz-user-progress mt-3 p-3 bg-light rounded">
                                            <h6 class="mb-2"><i class="fas fa-chart-line text-primary"></i> تقدمك في هذا الاختبار</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <small class="text-muted">أفضل نتيجة:</small>
                                                    <strong class="text-success"
                                                        dir="ltr"><?= esc($quiz_data->user_best_score ?? 0) ?>%</strong>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="text-muted">المحاولات المتبقية:</small>
                                                    <strong
                                                        class="<?= ($quiz_data->remaining_attempts ?? 0) > 0 ? 'text-info' : 'text-danger' ?>">
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
                                            تجاوزت الحد الأقصى للمحاولات
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-success btn-lg take-embedded-quiz-btn"
                                            data-quiz-id="<?= $quiz_data->id ?>" data-quiz-title="<?= esc($quiz_data->quiz_title) ?>">
                                            <i class="fas fa-play mr-2"></i>
                                            <?= (isset($quiz_data->user_attempt_count) && $quiz_data->user_attempt_count > 0) ? 'إعادة الاختبار' : 'بدء الاختبار' ?>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="no-content-placeholder">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <p>الاختبار غير متاح حالياً</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            <?php elseif ($current_item_type === 'page'): ?>
                <!-- Page Content -->
                <div class="page-content-area">
                    <div class="card">
                        <div class="card-header">
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
                                <div class="no-content-placeholder">
                                    <i class="fas fa-file-alt"></i>
                                    <p>المحتوى غير متاح حالياً</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <!-- Default/Unknown Content Type -->
                <div class="no-content-placeholder">
                    <i class="fas fa-question"></i>
                    <p>نوع المحتوى غير مدعوم</p>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- Fallback to original video content -->
            <div class="course-preview-video">
                <div class="video-container">
                    <iframe
                        src="https://player.mediadelivery.net/embed/<?= $metadata['video_library_id'] ?? '495222' ?>/<?= $video_id ?>?autoplay=false"
                        loading="lazy" style="border: none; position: absolute; top: 0; left: 0; height: 100%; width: 100%;"
                        allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"
                        allowfullscreen="true">
                    </iframe>
                </div>
            </div>
            <p class="course-description-text">
                <?= esc($video_desc) ?>
            </p>
        <?php endif; ?>

        <?php if (!isset($isEnrolled) || !$isEnrolled): ?>
            <div class="alert alert-info mt-4">
                <h5><i class="fas fa-info-circle mr-2"></i>هذا العنصر مجاني كمعاينة</h5>
                <p>قم بالاشتراك في الكورس لمشاهدة باقي العناصر والمحتوى بالكامل.</p>
                <a href="javascript:void(0);" onclick="addToCart('course', <?= $course->id ?>);" class="btn btn-primary">
                    <i class="fas fa-shopping-cart mr-2"></i>أضف للسلة
                </a>
            </div>
        <?php else: ?>
            <!-- Mark as Complete Button -->
            <div class="completion-section mt-4">

                <?php if (isset($current_item) && !empty($current_item['id'])): ?>
                    <button class="btn btn-success btn-block mark-complete-button"
                        onclick="markItemComplete(<?= $course->id ?>, <?= $current_item['id'] ?>)">
                        <i class="fas fa-check mr-2"></i>
                        تم الإكمال
                    </button>

                <?php else: ?>
                    <button class="btn btn-secondary btn-block" disabled>
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        لا يمكن تحديد العنصر
                    </button>

                <?php endif; ?>
            </div>
        <?php endif; ?>


        <script>
            function markItemComplete(courseId, itemId) {
                console.log('MARK_COMPLETE DEBUG - Function called with:', { courseId, itemId });

                if (!courseId || !itemId) {
                    console.error('MARK_COMPLETE ERROR - Missing parameters:', { courseId, itemId });
                    alert('خطأ: Course ID and Item ID required');
                    return;
                }

                // Disable button to prevent double clicks
                const button = event.target;
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>جاري الحفظ...';

                console.log('MARK_COMPLETE DEBUG - Sending request to:', '<?= base_url('progress/mark-completed') ?>');
                console.log('MARK_COMPLETE DEBUG - Request body:', { course_id: courseId, item_id: itemId });

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

                                // Store the next item as the last selected item
                                const courseSlug = '<?= $course->slug ?>';
                                const nextItemId = data.next_item.url.match(/item=(\d+)/);
                                if (nextItemId && nextItemId[1]) {
                                    localStorage.setItem(`course_${courseSlug}_last_item`, nextItemId[1]);
                                }
                                setTimeout(() => {
                                    window.location.href = data.next_item.url;
                                }, 500);
                            } else if (data.next_unit) {

                                // Store the next unit's first item as the last selected item
                                const courseSlug = '<?= $course->slug ?>';
                                const nextItemId = data.next_unit.url.match(/item=(\d+)/);
                                if (nextItemId && nextItemId[1]) {
                                    localStorage.setItem(`course_${courseSlug}_last_item`, nextItemId[1]);
                                }
                                setTimeout(() => {
                                    window.location.href = data.next_unit.url;
                                }, 500);
                            } else if (data.course_completed && data.redirect_url) {

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
    document.addEventListener('DOMContentLoaded', function () {
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
                    onUnitCompleted: function (result) {
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
            item.addEventListener('click', function (e) {
                e.preventDefault();

                // Store the selected item in localStorage for persistence
                const itemId = this.getAttribute('data-item-id');
                const courseSlug = '<?= $course->slug ?>';

                if (itemId && courseSlug) {
                    localStorage.setItem(`course_${courseSlug}_last_item`, itemId);
                }

                // Remove active class from all items
                navItems.forEach(navItem => {
                    navItem.closest('.course-item').classList.remove('active-item');
                });

                // Add active class to clicked item
                this.closest('.course-item').classList.add('active-item');

                // Navigate to the selected item
                window.location.href = this.getAttribute('href');
            });
        });

        const courseSlug = '<?= $course->slug ?>';
        const currentItemId = '<?= $current_id ?? '' ?>';

        // Add global function to get last item for external navigation
        window.getCourseLastItem = function (courseSlug) {
            return localStorage.getItem(`course_${courseSlug}_last_item`);
        };

        // Add global function to construct course URL with last item
        window.getCourseUrlWithLastItem = function (courseSlug, baseUrl) {
            const lastItem = localStorage.getItem(`course_${courseSlug}_last_item`);
            if (lastItem) {
                return `${baseUrl}?last_item=${lastItem}`;
            }
            return baseUrl;
        };

        // Handle accordion functionality
        const accordionButtons = document.querySelectorAll('.accordion-button');

        accordionButtons.forEach(button => {
            button.addEventListener('click', function () {
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
        const activeItem = document.querySelector('.course-item.active-item .unit-item-link');
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

        // Store current item in localStorage when page loads (using already declared currentItemId)
        if (currentItemId && courseSlug) {
            localStorage.setItem(`course_${courseSlug}_last_item`, currentItemId);
        }
    });
</script>

<!-- Embedded Quiz Modal -->
<div id="embeddedQuizModal" class="embedded-quiz-modal" style="display: none;">
    <div class="embedded-quiz-container">
        <!-- Mobile-optimized header -->
        <div class="embedded-quiz-header">
            <div class="quiz-title-section">
                <h4 id="quizTitle" class="quiz-title-responsive">اختبار</h4>
                <button class="close-quiz-btn mobile-friendly-btn" onclick="EmbeddedQuiz.close()"
                    aria-label="إغلاق الاختبار">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="quiz-progress-section">
                <div class="quiz-timer mobile-timer">
                    <i class="fas fa-clock"></i>
                    <span id="quizTimer">00:00</span>
                </div>
                <div class="quiz-progress mobile-progress">
                    <span id="questionCounter" class="question-counter-mobile">1 من 5</span>
                    <div class="progress-bar-container mobile-progress-container">
                        <div class="progress-bar" id="quizProgressBar"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile swipe indicator -->
        <div class="mobile-swipe-indicator" style="display: none;">
            <div class="swipe-hint">
                <i class="fas fa-hand-point-left"></i>
                <span>اسحب يميناً أو يساراً للتنقل</span>
            </div>
        </div>

        <!-- Quiz content with touch-friendly design -->
        <div class="embedded-quiz-content mobile-quiz-content">
            <div id="quizQuestions" class="quiz-questions-container"></div>
        </div>

        <!-- Mobile-optimized navigation -->
        <div class="embedded-quiz-navigation mobile-navigation">
            <div class="nav-buttons-container">
                <button id="prevQuestionBtn" class="nav-btn prev-btn mobile-nav-btn"
                    onclick="EmbeddedQuiz.previousQuestion()" aria-label="السؤال السابق">
                    <i class="fas fa-chevron-right"></i>
                    <span class="btn-text">السابق</span>
                </button>

                <!-- Mobile question indicator dots -->
                <div class="mobile-question-dots" id="mobileQuestionDots" style="display: none;">
                    <!-- Dots will be generated dynamically -->
                </div>

                <button id="nextQuestionBtn" class="nav-btn next-btn mobile-nav-btn"
                    onclick="EmbeddedQuiz.nextQuestion()" aria-label="السؤال التالي">
                    <span class="btn-text">التالي</span>
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>

            <button id="submitQuizBtn" class="nav-btn submit-btn mobile-submit-btn" onclick="EmbeddedQuiz.submitQuiz()"
                style="display: none;" aria-label="إرسال الإجابات">
                <i class="fas fa-check"></i>
                <span class="btn-text">إرسال الإجابات</span>
            </button>
        </div>

        <!-- Enhanced results section for mobile -->
        <div id="quizResults" class="quiz-results mobile-results" style="display: none;">
            <div class="results-content mobile-results-content">
                <div class="results-header mobile-results-header">
                    <i class="fas fa-trophy results-icon"></i>
                    <h3 class="results-title">نتائج الاختبار</h3>
                </div>
                <div class="results-stats mobile-results-stats">
                    <div class="stat-item mobile-stat-item">
                        <span class="stat-label">النتيجة:</span>
                        <span class="stat-value score-value" id="finalScore">0%</span>
                    </div>
                    <div class="stat-item mobile-stat-item">
                        <span class="stat-label">الإجابات الصحيحة:</span>
                        <span class="stat-value" id="correctAnswers">0/0</span>
                    </div>
                    <div class="stat-item mobile-stat-item">
                        <span class="stat-label">الوقت المستغرق:</span>
                        <span class="stat-value" id="completionTime">0 دقيقة</span>
                    </div>
                    <div class="stat-item mobile-stat-item">
                        <span class="stat-label">الحالة:</span>
                        <span class="stat-value pass-status" id="passStatus">-</span>
                    </div>
                </div>
                <div class="results-actions mobile-results-actions">
                    <button class="btn btn-primary mobile-continue-btn" onclick="EmbeddedQuiz.continueToNext()">
                        <i class="fas fa-arrow-left"></i>
                        <span>متابعة للعنصر التالي</span>
                    </button>
                    <button class="btn btn-secondary mobile-retry-btn" onclick="EmbeddedQuiz.retryQuiz()"
                        style="display: none;">
                        <i class="fas fa-redo"></i>
                        <span>إعادة المحاولة</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile loading indicator -->
        <div class="mobile-loading-overlay" id="mobileLoadingOverlay" style="display: none;">
            <div class="loading-spinner">
                <div class="spinner-border" role="status">
                    <span class="sr-only">جاري التحميل...</span>
                </div>
                <p class="loading-text">جاري تحميل الاختبار...</p>
            </div>
        </div>
    </div>
</div>

<!-- Mobile-specific quiz styles -->
<style>
    /* Mobile-first responsive enhancements */
    @media (max-width: 768px) {
        .embedded-quiz-modal {
            padding: 0;
        }

        .embedded-quiz-container {
            margin: 0;
            border-radius: 0;
            height: 100vh;
            max-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .quiz-title-responsive {
            font-size: 1.1rem !important;
            margin: 0;
        }

        .mobile-friendly-btn {
            padding: 12px;
            min-width: 44px;
            min-height: 44px;
        }

        .mobile-timer {
            font-size: 0.9rem;
        }

        .question-counter-mobile {
            font-size: 0.85rem;
        }

        .mobile-progress-container {
            margin-top: 8px;
        }

        .mobile-swipe-indicator {
            display: block !important;
            text-align: center;
            padding: 8px;
            background: rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid #eee;
        }

        .swipe-hint {
            font-size: 0.8rem;
            color: #666;
        }

        .swipe-hint i {
            margin-right: 5px;
            animation: swipeAnimation 2s infinite;
        }

        @keyframes swipeAnimation {

            0%,
            100% {
                transform: translateX(0);
            }

            50% {
                transform: translateX(-10px);
            }
        }

        .mobile-quiz-content {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
        }

        .nav-buttons-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
        }

        .mobile-nav-btn {
            min-width: 80px;
            padding: 12px 16px;
            font-size: 0.9rem;
        }

        .mobile-question-dots {
            display: flex !important;
            gap: 6px;
        }

        .question-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #ddd;
            transition: background 0.3s;
        }

        .question-dot.active {
            background: #007bff;
        }

        .question-dot.answered {
            background: #28a745;
        }

        .mobile-submit-btn {
            width: calc(100% - 30px);
            margin: 10px 15px;
            padding: 15px;
            font-size: 1rem;
            font-weight: 600;
        }

        .mobile-results-content {
            padding: 20px 15px;
        }

        .mobile-results-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .results-title {
            font-size: 1.3rem;
            margin-top: 10px;
        }

        .mobile-stat-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }

        .score-value {
            font-weight: bold;
            font-size: 1.1rem;
        }

        .pass-status {
            font-weight: 600;
        }

        .mobile-results-actions {
            margin-top: 25px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .mobile-continue-btn,
        .mobile-retry-btn {
            width: 100%;
            padding: 15px;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .mobile-loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .loading-text {
            margin-top: 15px;
            color: #666;
            font-size: 0.9rem;
        }
    }

    /* Tablet optimizations */
    @media (min-width: 769px) and (max-width: 1024px) {
        .embedded-quiz-container {
            max-width: 90%;
            margin: 2vh auto;
        }

        .mobile-swipe-indicator {
            display: none !important;
        }
    }

    /* Touch-friendly improvements for all devices */
    @media (hover: none) and (pointer: coarse) {

        .nav-btn,
        .close-quiz-btn,
        .mobile-friendly-btn {
            min-width: 44px;
            min-height: 44px;
            padding: 12px;
        }

        .quiz-option {
            padding: 15px;
            margin: 8px 0;
        }
    }
</style>



<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<!-- Additional JS if needed -->
<!-- Include Embedded Quiz CSS and JS -->
<link rel="stylesheet" href="<?= base_url() ?>assets/css/embedded-quiz.css">
<script src="<?= base_url() ?>assets/js/embedded-quiz.js"></script>

<?php $this->endSection();

// ...existing code ...
