<?php $this->extend('site_layout/template'); ?>
<?php $this->section('content'); ?>

<style>
    /* Material 3 Design System - Course Details */
    :root {
        --primary-color: #136ad5;
        --primary-dark: #0f5bb8;
        --primary-light: #1573e8;
        --secondary-color: #00aeff;
        --secondary-dark: #0099e6;
        --secondary-light: #14b4ff;
        --accent-color: #f59e0b;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --text-primary: #1a202c;
        --text-secondary: #4a5568;
        --text-muted: #718096;
        --text-light: #a0aec0;
        --bg-primary: #ffffff;
        --bg-secondary: #f7fafc;
        --bg-accent: rgba(19, 106, 213, 0.05);
        --bg-secondary-light: rgba(0, 174, 255, 0.05);
        --bg-gradient-primary: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        --bg-gradient-light: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        --border-color: rgba(19, 106, 213, 0.15);
        --border-light: rgba(19, 106, 213, 0.08);
        --shadow-sm: 0 1px 3px rgba(19, 106, 213, 0.1);
        --shadow-md: 0 4px 12px rgba(19, 106, 213, 0.15);
        --shadow-lg: 0 10px 25px rgba(19, 106, 213, 0.2);
        --shadow-xl: 0 20px 40px rgba(19, 106, 213, 0.25);
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 20px;
        --radius-xl: 30px;
    }

    /* Material 3 Course Hero Section */
    .course-hero {
        background: var(--bg-gradient-primary);
        border-radius: var(--radius-lg);
        padding: 3rem 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-lg);
        position: relative;
        overflow: hidden;
    }

    .course-hero::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(50%, -50%);
    }

    .course-hero h1 {
        color: white;
        font-weight: 700;
        margin-bottom: 1rem;
        font-size: 2.5rem;
        line-height: 1.2;
        position: relative;
        z-index: 2;
    }

    .course-hero p {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.1rem;
        line-height: 1.6;
        margin-bottom: 0;
        position: relative;
        z-index: 2;
    }

    /* Material 3 Stats Grid */
    .course-stats {
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        padding: 2rem;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-light);
        margin-bottom: 2rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1.5rem;
    }

    .stat-item {
        text-align: center;
        padding: 1.5rem;
        background: var(--bg-accent);
        border-radius: var(--radius-md);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid var(--border-light);
    }

    .stat-item:hover {
        background: rgba(19, 106, 213, 0.1);
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
    }

    .stat-item i {
        font-size: 2.5rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
        display: block;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.9rem;
        color: var(--text-secondary);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Material 3 Accordion Design */
    .custom-accordion {
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-light);
        margin-bottom: 2rem;
    }

    .custom-accordion .accordion-item {
        border: none;
        border-bottom: 1px solid var(--border-light);
    }

    .custom-accordion .accordion-item:last-child {
        border-bottom: none;
    }

    .custom-accordion .accordion-button {
        background: var(--bg-primary);
        border: none;
        padding: 1.5rem 2rem;
        font-weight: 600;
        color: var(--text-primary);
        border-radius: 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 1.1rem;
    }

    .custom-accordion .accordion-button:not(.collapsed) {
        background: var(--bg-accent);
        color: var(--primary-color);
        box-shadow: none;
    }

    .custom-accordion .accordion-button:focus {
        box-shadow: 0 0 0 3px rgba(19, 106, 213, 0.2);
        border-color: transparent;
        z-index: 3;
    }

    .custom-accordion .accordion-button::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23136ad5'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .custom-accordion .accordion-button:not(.collapsed)::after {
        transform: rotate(180deg);
    }

    .custom-accordion .accordion-body {
        padding: 0 2rem 2rem 2rem;
        background: var(--bg-primary);
    }

    /* Material 3 Video Items */
    .video-item {
        background: var(--bg-secondary);
        border-radius: var(--radius-md);
        padding: 1.25rem;
        margin-bottom: 0.75rem;
        border: 1px solid var(--border-light);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }

    .video-item:hover {
        background: var(--bg-accent);
        transform: translateX(8px);
        box-shadow: var(--shadow-sm);
        border-color: var(--border-color);
    }

    .video-item-content {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
        min-width: 0;
    }

    .video-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-shrink: 0;
        margin-right: 1rem;
    }

    .video-item.video-previewable {
        cursor: pointer;
    }

    .video-item.video-previewable .video-icon {
        background: linear-gradient(135deg, #22c55e, #16a34a);
    }

    .btn-preview {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        min-width: 120px;
        background: var(--primary-color);
        color: #fff;
        box-shadow: var(--shadow-sm);
    }

    .btn-preview:hover {
        background: var(--primary-dark);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .video-icon {
        width: 48px;
        height: 48px;
        background: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        flex-shrink: 0;
        box-shadow: var(--shadow-sm);
    }

    .video-info {
        flex: 1;
        min-width: 0;
    }

    .video-info h6 {
        margin: 0 0 0.5rem 0;
        font-weight: 600;
        color: var(--text-primary);
        font-size: 1rem;
        line-height: 1.4;
    }

    .video-meta {
        display: flex;
        gap: 1rem;
        font-size: 0.85rem;
        color: var(--text-muted);
        align-items: center;
    }

    .video-meta span {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    /* Material 3 Buttons */
    .btn {
        border-radius: var(--radius-md);
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        font-size: 0.95rem;
        position: relative;
        overflow: hidden;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn-success {
        background: var(--primary-color);
        color: white;
        box-shadow: var(--shadow-sm);
    }

    .btn-success:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        color: white;
    }

    .btn-outline-primary {
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        background: transparent;
    }

    .btn-outline-primary:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-secondary {
        background: var(--bg-secondary);
        color: var(--text-secondary);
        border: 1px solid var(--border-color);
    }

    .btn-secondary:hover {
        background: var(--bg-accent);
        color: var(--text-primary);
        border-color: var(--primary-color);
    }

    /* Material 3 Cart Summary - REMOVED */

    /* Floating Cart Summary - قائمة عائمة للوحدات المضافة */
    .floating-cart-summary {
        position: fixed;
        bottom: 20px;
        left: 20px;
        width: 320px;
        max-width: 90vw;
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-xl);
        border: 1px solid var(--border-color);
        z-index: 1050;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        max-height: 80vh;
        overflow-y: auto;
    }

    .floating-cart-summary.collapsed {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        overflow: hidden;
    }

    .floating-cart-summary::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--bg-gradient-primary);
        border-radius: var(--radius-lg) var(--radius-lg) 0 0;
    }

    .floating-cart-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border-light);
        background: var(--bg-accent);
        border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .floating-cart-header:hover {
        background: rgba(19, 106, 213, 0.1);
    }

    .floating-cart-title {
        color: var(--primary-color);
        font-weight: 700;
        font-size: 1.1rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .floating-cart-toggle {
        background: none;
        border: none;
        color: var(--primary-color);
        font-size: 1.2rem;
        cursor: pointer;
        padding: 0.25rem;
        border-radius: 50%;
        transition: all 0.3s ease;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .floating-cart-toggle:hover {
        background: var(--primary-color);
        color: white;
    }

    .floating-cart-body {
        padding: 1.5rem;
        transition: all 0.3s ease;
    }

    .floating-cart-summary.collapsed .floating-cart-body,
    .floating-cart-summary.collapsed .floating-cart-title span,
    .floating-cart-summary.collapsed .floating-cart-header::before {
        display: none;
    }

    .floating-cart-summary.collapsed .floating-cart-header {
        padding: 1rem;
        border-radius: 50%;
        border-bottom: none;
        justify-content: center;
    }

    .floating-cart-summary.collapsed .floating-cart-toggle {
        transform: rotate(180deg);
    }

    .floating-selected-unit {
        background: var(--bg-secondary);
        border-radius: var(--radius-sm);
        padding: 1rem;
        margin-bottom: 0.75rem;
        border: 1px solid var(--border-light);
        transition: all 0.3s ease;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .floating-selected-unit:hover {
        box-shadow: var(--shadow-sm);
        border-color: var(--border-color);
        transform: translateX(-2px);
    }

    .floating-selected-unit:last-child {
        margin-bottom: 1rem;
    }

    .floating-unit-name {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.9rem;
        flex: 1;
        margin-right: 0.5rem;
    }

    .floating-unit-status {
        color: var(--primary-color);
        font-size: 0.8rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .floating-cart-actions {
        display: flex;
        gap: 0.75rem;
        margin-top: 1rem;
    }

    .floating-cart-actions .btn {
        flex: 1;
        font-size: 0.9rem;
        padding: 0.75rem 1rem;
    }

    .floating-cart-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: var(--danger-color);
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        box-shadow: var(--shadow-sm);
    }

    /* Responsive Design for Floating Cart */
    @media (max-width: 768px) {
        .floating-cart-summary {
            right: 10px;
            width: 280px;
            max-width: calc(100vw - 20px);
        }

        .floating-cart-summary.collapsed {
            width: 50px;
            height: 50px;
        }

        .floating-cart-header {
            padding: 1.25rem;
        }

        .floating-cart-body {
            padding: 1.25rem;
        }

        .floating-cart-title {
            font-size: 1rem;
        }

        .floating-cart-actions {
            flex-direction: column;
            gap: 0.5rem;
        }
    }

    @media (max-width: 480px) {
        .floating-cart-summary {
            right: 5px;
            width: 260px;
            max-width: calc(100vw - 10px);
        }

        .floating-selected-unit {
            padding: 0.75rem;
        }

        .floating-unit-name {
            font-size: 0.85rem;
        }

        .floating-unit-status {
            font-size: 0.75rem;
        }
    }


    .selected-unit {
        background: var(--bg-primary);
        border-radius: var(--radius-sm);
        padding: 1rem 1.25rem;
        border: 1px solid var(--border-light);
        margin-bottom: 0.75rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .selected-unit:hover {
        box-shadow: var(--shadow-sm);
        border-color: var(--border-color);
    }

    /* Material 3 Unit Purchase Section */
    .unit-purchase-section {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border-light);
    }

    /* Material 3 Modal */
    .modal-content {
        border-radius: var(--radius-lg);
        border: none;
        box-shadow: var(--shadow-xl);
        overflow: hidden;
    }

    .modal-header {
        background: var(--bg-accent);
        border-bottom: 1px solid var(--border-light);
        padding: 1.5rem 2rem;
    }

    .modal-title {
        color: var(--primary-color);
        font-weight: 700;
        font-size: 1.25rem;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-footer {
        border-top: 1px solid var(--border-light);
        padding: 1.5rem 2rem;
        background: var(--bg-secondary);
    }

    /* Material 3 Loading Animation */
    .loading {
        position: relative;
    }

    .loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 48px;
        height: 48px;
        margin: -24px 0 0 -24px;
        border: 3px solid var(--border-light);
        border-top: 3px solid var(--primary-color);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Material 3 Focus States */
    .btn:focus,
    .accordion-button:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(19, 106, 213, 0.3);
    }

    /* Material 3 Responsive Design */
    @media (max-width: 768px) {
        .course-hero {
            padding: 2rem 1.5rem;
            border-radius: var(--radius-md);
        }

        .course-hero h1 {
            font-size: 2rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .stat-item {
            padding: 1rem;
        }

        .custom-accordion .accordion-button {
            padding: 1rem 1.5rem;
            font-size: 1rem;
        }

        .custom-accordion .accordion-body {
            padding: 0 1.5rem 1.5rem 1.5rem;
        }

        .video-item {
            padding: 1rem;
        }

        .video-icon {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }

    }

    /* Material 3 Elevation System */
    .elevation-1 {
        box-shadow: var(--shadow-sm);
    }

    .elevation-2 {
        box-shadow: var(--shadow-md);
    }

    .elevation-3 {
        box-shadow: var(--shadow-lg);
    }

    .elevation-4 {
        box-shadow: var(--shadow-xl);
    }
    }

    .pricing-block h4 {
        font-size: 1.6rem;
        font-weight: 700;
        line-height: 1.3;
        margin-bottom: 1.5rem;
        color: var(--text-primary);
    }

    .pricing-block .price {
        font-size: 3rem;
        font-weight: 800;
        line-height: 1;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    .pricing-block .price-label {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 2rem;
    }

    /* Button Typography */
    .btn {
        font-weight: 600;
        letter-spacing: 0.025em;
        line-height: 1.4;
    }

    .btn-lg {
        font-size: 1.1rem;
        padding: 1rem 2rem;
    }

    /* Modal Typography */
    .modal-title {
        font-size: 1.4rem;
        font-weight: 700;
        line-height: 1.3;
    }

    /* Improved Spacing Classes */
    .mb-section { margin-bottom: 3rem; }
    .mb-block { margin-bottom: 2rem; }
    .mb-item { margin-bottom: 1.5rem; }
    .mb-text { margin-bottom: 1rem; }

    .mt-section { margin-top: 3rem; }
    .mt-block { margin-top: 2rem; }
    .mt-item { margin-top: 1.5rem; }
    .mt-text { margin-top: 1rem; }

    .p-section { padding: 3rem; }
    .p-block { padding: 2rem; }
    .p-item { padding: 1.5rem; }
    .p-text { padding: 1rem; }

    /* Responsive Design Enhancements */
    @media (max-width: 1200px) {
        .course-title {
            font-size: 2.75rem;
        }

        .section-title {
            font-size: 2.25rem;
        }

        .course-header-wrapper {
            padding: 1.5rem 1.5rem 3rem 1.5rem;
        }
    }

    @media (max-width: 992px) {
        .course-title {
            font-size: 2.5rem;
            text-align: center;
        }

        .course-description {
            font-size: 1.25rem;
            text-align: center;
            margin: 0 auto 2rem;
        }

        .course-stats {
            justify-content: center;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .course-stats .stat-item {
            min-width: 200px;
            text-align: center;
        }

        .course-header-wrapper {
            padding: 1.25rem 1rem 2.5rem 1rem;
            text-align: center;
        }

        .custom-accordion .unit-header {
            padding: 1.5rem !important;
        }

        .custom-accordion .btn-link {
            font-size: 1.2rem;
        }
    }

    @media (max-width: 768px) {
        .course-title {
            font-size: 2rem;
            line-height: 1.2;
        }

        .section-title {
            font-size: 1.75rem;
        }

        .course-description {
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .course-header-wrapper {
            padding: 1rem 1rem 2rem 1rem;
        }

        .course-stats {
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .course-stats .stat-item {
            width: 100%;
            max-width: 300px;
            padding: 1.25rem;
        }

        .course-stats .stat-item .stat-number {
            font-size: 1.75rem;
        }

        .custom-accordion .unit-header {
            padding: 1.25rem !important;
        }

        .custom-accordion .btn-link {
            font-size: 1.1rem;
            text-align: right;
        }

        .video-item {
            padding: 1.25rem;
        }

        .video-item h6 {
            font-size: 1rem;
        }

        .course-sidebar {
            margin-top: 2rem;
        }

        .pricing-block .price {
            font-size: 2.5rem;
        }

        .modal-dialog {
            margin: 1rem;
        }

        .modal-content {
            border-radius: 1rem;
        }

        .p-section { padding: 2rem 1rem; }
        .p-block { padding: 1.5rem 1rem; }
        .p-item { padding: 1.25rem 1rem; }
    }

    @media (max-width: 576px) {
        .course-title {
            font-size: 1.75rem;
        }

        .section-title {
            font-size: 1.5rem;
        }

        .course-description {
            font-size: 1rem;
        }

        .course-header-wrapper {
            padding: 1rem 0.75rem 1.5rem 0.75rem;
        }

        .course-stats .stat-item .stat-number {
            font-size: 1.5rem;
        }

        .course-stats .stat-item .stat-label {
            font-size: 0.9rem;
        }

        .custom-accordion .unit-header {
            padding: 1rem !important;
        }

        .custom-accordion .btn-link {
            font-size: 1rem;
        }

        .video-item {
            padding: 1rem;
        }

        .video-item h6 {
            font-size: 0.95rem;
        }

        .video-item small {
            font-size: 0.8rem;
        }

        .course-sidebar h4,
        .course-features h5,
        .pricing-block h4 {
            font-size: 1.25rem;
        }

        .pricing-block .price {
            font-size: 2rem;
        }

        .btn-lg {
            font-size: 1rem;
            padding: 0.875rem 1.5rem;
        }

        .modal-header .btn-close {
            font-size: 1rem;
        }

        .p-section { padding: 1.5rem 0.75rem; }
        .p-block { padding: 1.25rem 0.75rem; }
        .p-item { padding: 1rem 0.75rem; }
    }

    /* RTL Support Enhancements */
    [dir="rtl"] .custom-accordion .btn-link {
        text-align: right;
    }

    [dir="rtl"] .video-item .btn {
        margin-left: 0;
        margin-right: auto;
    }

    [dir="rtl"] .course-stats {
        direction: rtl;
    }

    [dir="rtl"] .modal-header .btn-close {
        margin-left: auto;
        margin-right: 0;
    }

    /* Touch-Friendly Improvements */
    @media (hover: none) and (pointer: coarse) {
        .btn {
            min-height: 44px;
            padding: 0.75rem 1.5rem;
        }

        .video-item .btn {
            min-height: 40px;
        }

        .custom-accordion .btn-link {
            min-height: 48px;
            padding: 1rem;
        }

        .modal-header .btn-close {
            min-height: 44px;
            min-width: 44px;
        }
    }

    /* Accessibility Enhancements */
    /* Focus States */
    .btn:focus,
    .custom-accordion .btn-link:focus,
    .video-item .btn:focus {
        outline: 3px solid var(--primary-color);
        outline-offset: 2px;
        box-shadow: 0 0 0 3px rgba(var(--primary-color-rgb), 0.2);
    }

    .modal-header .btn-close:focus {
        outline: 3px solid var(--danger-color);
        outline-offset: 2px;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.2);
    }

    /* High Contrast Mode Support */
    @media (prefers-contrast: high) {
        .course-stats .stat-item {
            border: 2px solid var(--text-primary);
        }

        .video-item {
            border: 1px solid var(--text-secondary);
        }

        .custom-accordion .card {
            border: 2px solid var(--text-primary);
        }

        .btn {
            border: 2px solid currentColor;
        }
    }

    /* Reduced Motion Support */
    @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }

        .modal.fade .modal-dialog {
            transition: none;
        }
    }

    /* Screen Reader Only Content */
    .sr-only {
        position: absolute !important;
        width: 1px !important;
        height: 1px !important;
        padding: 0 !important;
        margin: -1px !important;
        overflow: hidden !important;
        clip: rect(0, 0, 0, 0) !important;
        white-space: nowrap !important;
        border: 0 !important;
    }

    .sr-only-focusable:focus {
        position: static !important;
        width: auto !important;
        height: auto !important;
        padding: inherit !important;
        margin: inherit !important;
        overflow: visible !important;
        clip: auto !important;
        white-space: normal !important;
    }

    /* Skip Links */
    .skip-link {
        position: absolute;
        top: -40px;
        left: 6px;
        background: var(--primary-color);
        color: white;
        padding: 8px;
        text-decoration: none;
        border-radius: 4px;
        z-index: 9999;
        font-weight: 600;
    }

    .skip-link:focus {
        top: 6px;
    }

    /* ARIA Live Regions */
    .live-region {
        position: absolute;
        left: -10000px;
        width: 1px;
        height: 1px;
        overflow: hidden;
    }

    /* Enhanced RTL Support */
    [dir="rtl"] {
        text-align: right;
    }

    [dir="rtl"] .course-header-wrapper {
        text-align: right;
    }

    [dir="rtl"] .course-stats .stat-item {
        text-align: center;
    }

    [dir="rtl"] .video-item {
        text-align: right;
    }

    [dir="rtl"] .video-item .btn {
        margin-left: 0;
        margin-right: auto;
        float: left;
    }

    [dir="rtl"] .course-features {
        text-align: right;
    }

    [dir="rtl"] .pricing-block {
        text-align: right;
    }

    [dir="rtl"] .modal-header {
        text-align: right;
    }

    [dir="rtl"] .modal-header .btn-close {
        margin-left: auto;
        margin-right: 0;
    }

    /* Color Contrast Improvements */
    .text-muted {
        color: var(--text-secondary) !important;
        opacity: 0.8;
    }

    .badge {
        font-weight: 600;
        padding: 0.5em 0.75em;
    }

    /* Keyboard Navigation Enhancements */
    .custom-accordion .btn-link[aria-expanded="true"] {
        background-color: rgba(var(--primary-color-rgb), 0.1);
    }

    .video-item:focus-within {
        background-color: rgba(var(--primary-color-rgb), 0.05);
        border-color: var(--primary-color);
    }

    /* Subtle Animations and Transitions */
    /* Page Load Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Apply Animations */
    .course-header-wrapper {
        animation: fadeInUp 0.8s ease-out;
    }

    .course-stats .stat-item {
        animation: scaleIn 0.6s ease-out;
        animation-fill-mode: both;
    }

    .course-stats .stat-item:nth-child(1) { animation-delay: 0.1s; }
    .course-stats .stat-item:nth-child(2) { animation-delay: 0.2s; }
    .course-stats .stat-item:nth-child(3) { animation-delay: 0.3s; }
    .course-stats .stat-item:nth-child(4) { animation-delay: 0.4s; }

    .custom-accordion .card {
        animation: fadeInUp 0.6s ease-out;
        animation-fill-mode: both;
    }

    .custom-accordion .card:nth-child(1) { animation-delay: 0.1s; }
    .custom-accordion .card:nth-child(2) { animation-delay: 0.2s; }
    .custom-accordion .card:nth-child(3) { animation-delay: 0.3s; }
    .custom-accordion .card:nth-child(4) { animation-delay: 0.4s; }

    .course-sidebar {
        animation: slideInRight 0.8s ease-out;
        animation-delay: 0.3s;
        animation-fill-mode: both;
    }

    /* Hover Transitions */
    .btn {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .btn:active {
        transform: translateY(0);
        transition-duration: 0.1s;
    }

    .video-item {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .video-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.1);
    }

    .course-stats .stat-item {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .course-stats .stat-item:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
    }

    .custom-accordion .card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .custom-accordion .card:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }

    .custom-accordion .btn-link {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .custom-accordion .btn-link:hover {
        background-color: rgba(var(--primary-color-rgb), 0.05);
    }

    /* Icon Animations */
    .icon {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn:hover .icon,
    .video-item:hover .icon {
        transform: scale(1.1);
    }

    .custom-accordion .btn-link .icon {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .custom-accordion .btn-link[aria-expanded="true"] .icon {
        transform: rotate(180deg);
    }

    /* Loading States */
    .loading-skeleton {
        background: linear-gradient(90deg,
        rgba(var(--bg-secondary-rgb), 0.3) 25%,
        rgba(var(--bg-secondary-rgb), 0.5) 50%,
        rgba(var(--bg-secondary-rgb), 0.3) 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }

    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* Smooth Scrolling */
    html {
        scroll-behavior: smooth;
    }

    /* Focus Animations */
    .btn:focus,
    .custom-accordion .btn-link:focus,
    .video-item .btn:focus {
        animation: focusPulse 0.6s ease-out;
    }

    @keyframes focusPulse {
        0% { box-shadow: 0 0 0 0 rgba(var(--primary-color-rgb), 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(var(--primary-color-rgb), 0); }
        100% { box-shadow: 0 0 0 0 rgba(var(--primary-color-rgb), 0); }
    }

    /* Stagger Animation for Lists */
    .course-features li {
        animation: fadeInUp 0.5s ease-out;
        animation-fill-mode: both;
    }

    .course-features li:nth-child(1) { animation-delay: 0.1s; }
    .course-features li:nth-child(2) { animation-delay: 0.2s; }
    .course-features li:nth-child(3) { animation-delay: 0.3s; }
    .course-features li:nth-child(4) { animation-delay: 0.4s; }
    .course-features li:nth-child(5) { animation-delay: 0.5s; }

    /* Modal Animations Enhancement */
    .modal.fade .modal-dialog {
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .modal.show .modal-dialog {
        animation: modalSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Pulse Animation for Important Elements */
    .pricing-block .price {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    /* Micro-interactions */
    .badge {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .badge:hover {
        transform: scale(1.05);
    }

    /* Progress Indicators */
    .progress-bar {
        transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Entrance Animation Classes */
    .animate-on-scroll {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .animate-on-scroll.in-view {
        opacity: 1;
        transform: translateY(0);
    }

    /* Main Section with Modern Background */
    .untree_co-section {
        padding-top: 0.5rem;
        padding-bottom: 4rem;
        background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-accent) 100%);
        min-height: 100vh;
    }

    /* Enhanced Modern Course Header */
    .course-header-wrapper {
        background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-accent) 100%);
        border-radius: var(--radius-xl);
        padding: 1.5rem 2rem 4rem 2rem;
        margin-bottom: 3rem;
        box-shadow: var(--shadow-xl);
        border: 1px solid var(--border-light);
        position: relative;
        overflow: hidden;
        text-align: center;
    }

    .course-header-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), var(--accent-color));
        animation: shimmer 3s ease-in-out infinite;
    }

    @keyframes shimmer {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .course-header-wrapper::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(37, 99, 235, 0.05) 0%, transparent 70%);
        pointer-events: none;
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(10px, -10px) rotate(1deg); }
        66% { transform: translate(-5px, 5px) rotate(-1deg); }
    }

    .course-header-wrapper .section-title {
        font-size: 3.25rem;
        font-weight: 900;
        color: var(--text-primary);
        margin-bottom: 2rem;
        line-height: 1.1;
        position: relative;
        z-index: 2;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        background: linear-gradient(135deg, var(--text-primary) 0%, var(--primary-color) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .course-header-wrapper .course-description {
        font-size: 1.35rem;
        line-height: 1.7;
        color: var(--text-secondary);
        margin: 0 auto 3rem;
        max-width: 800px;
        font-weight: 500;
        position: relative;
        z-index: 2;
        padding: 0 1rem;
    }

    /* Enhanced Course Header CTA Section */
    .course-header-cta {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
        position: relative;
        z-index: 2;
    }

    .course-header-cta .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        border: none;
        padding: 1rem 2.5rem;
        font-size: 1.1rem;
        font-weight: 700;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        min-width: 200px;
    }

    .course-header-cta .btn-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.6s ease;
    }

    .course-header-cta .btn-primary:hover::before {
        left: 100%;
    }

    .course-header-cta .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-xl);
    }

    .course-header-cta .btn-outline-primary {
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        background: transparent;
        padding: 1rem 2rem;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: var(--radius-lg);
        transition: all 0.3s ease;
        min-width: 180px;
    }

    .course-header-cta .btn-outline-primary:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    /* Course Stats Bar */
    .course-stats {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin-top: 2rem;
        flex-wrap: wrap;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: var(--bg-accent);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .stat-item:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .stat-item i {
        color: var(--primary-color);
        font-size: 1.25rem;
    }

    .stat-item .stat-value {
        font-weight: 700;
        color: var(--text-primary);
        font-size: 1.1rem;
    }

    .stat-item .stat-label {
        color: var(--text-secondary);
        font-size: 0.9rem;
    }


    .selected-unit {
        background: var(--bg-primary);
        padding: 1rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        margin-bottom: 0.75rem;
        transition: all 0.2s ease;
    }

    .selected-unit:hover {
        box-shadow: var(--shadow-sm);
        transform: translateX(-2px);
    }

    /* Enhanced Course Outline */
    .course-outline {
        margin-top: 2rem;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .course-outline .section-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 2rem;
        position: relative;
        padding-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .course-outline .section-title::before {
        content: "📚";
        font-size: 1.2em;
    }

    .course-outline .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        border-radius: var(--radius-sm);
    }

    /* Enhanced Accordion Design */
    /* Enhanced Unit Block Styling - Website Compatible */
    .custom-accordion {
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-md);
        background: var(--bg-primary);
    }

    .custom-accordion .accordion-item {
        border: none;
        margin-bottom: 1rem;
        background: var(--bg-primary);
        border-radius: var(--radius-md);
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(19, 106, 213, 0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        border: 1px solid var(--border-light);
    }

    .custom-accordion .accordion-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(19, 106, 213, 0.2);
        border-color: var(--primary-color);
    }

    .custom-accordion .accordion-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .custom-accordion .accordion-item:hover::before {
        opacity: 1;
    }

    .custom-accordion .accordion-item:last-child {
        border-bottom: none;
    }

    .custom-accordion .accordion-header {
        background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-accent) 100%);
        border: none;
        padding: 0;
        position: relative;
        overflow: hidden;
    }

    .custom-accordion .accordion-header::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s ease;
    }

    .custom-accordion .accordion-header:hover::after {
        left: 100%;
    }

    .custom-accordion .accordion-button {
        color: var(--text-primary);
        text-decoration: none;
        font-weight: 700;
        padding: 1.25rem 1.75rem;
        width: 100%;
        text-align: right;
        border: none;
        background: var(--bg-gradient-light);
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        z-index: 2;
        transition: all 0.3s ease;
        border-radius: 0;
        min-height: 70px;
    }

    .custom-accordion .accordion-button:hover,
    .custom-accordion .accordion-button:focus {
        color: var(--primary-color);
        text-decoration: none;
        background: linear-gradient(135deg, var(--bg-accent) 0%, var(--bg-secondary-light) 100%);
        outline: none;
        box-shadow: inset 0 0 0 2px var(--primary-light);
        transform: translateY(-1px);
    }

    .custom-accordion .accordion-button:not(.collapsed) {
        background: var(--bg-gradient-primary);
        color: white;
        box-shadow: var(--shadow-md);
    }

    .custom-accordion .accordion-button:focus {
        box-shadow: 0 0 0 3px rgba(19, 106, 213, 0.25);
    }

    .custom-accordion .accordion-button .unit-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .custom-accordion .accordion-button .unit-price {
        background: var(--bg-primary);
        color: var(--primary-color);
        padding: 0.4rem 0.8rem;
        border-radius: var(--radius-md);
        font-size: 0.95rem;
        font-weight: 700;
        border: 1px solid var(--primary-light);
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .custom-accordion .accordion-button:not(.collapsed) .unit-price {
        background: rgba(255, 255, 255, 0.95);
        color: var(--primary-color);
        border-color: white;
    }

    .custom-accordion .accordion-button .expand-icon {
        transition: transform 0.3s ease;
        font-size: 1.3rem;
        color: var(--primary-color);
        margin-left: 1rem;
    }

    .custom-accordion .accordion-button:not(.collapsed) .expand-icon {
        color: white;
    }

    .custom-accordion .accordion-button[aria-expanded="true"] .expand-icon {
        transform: rotate(180deg);
    }

    .custom-accordion .accordion-body {
        background: var(--bg-primary);
        padding: 0;
        border-top: 1px solid var(--border-light);
    }

    .unit-header {
        background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-accent) 100%);
        border-bottom: 1px solid var(--border-color);
        padding: 1.5rem !important;
        transition: all 0.3s ease;
    }

    .unit-header:hover {
        background: linear-gradient(135deg, var(--bg-accent) 0%, var(--border-light) 100%);
    }

    .custom-accordion .btn-link {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        text-decoration: none;
        padding: 0;
        display: block;
        width: 100%;
        text-align: right;
        position: relative;
        transition: all 0.3s ease;
    }

    .custom-accordion .btn-link:hover {
        text-decoration: none;
        color: var(--primary-color);
        transform: translateX(-5px);
    }

    .custom-accordion .btn-link:focus {
        box-shadow: none;
        outline: 2px solid var(--primary-light);
        outline-offset: 2px;
    }

    .video-count {
        font-size: 0.9rem;
        color: var(--text-muted);
        margin-right: 0.5rem;
        background: var(--bg-accent);
        padding: 0.25rem 0.75rem;
        border-radius: var(--radius-sm);
        font-weight: 500;
    }

    /* Unit Purchase Section */
    .unit-purchase {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.75rem;
    }

    .unit-price {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--primary-color);
        text-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }

    .add-to-cart {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: var(--radius-md);
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-sm);
        position: relative;
        overflow: hidden;
    }

    .add-to-cart::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s ease;
    }

    .add-to-cart:hover::before {
        left: 100%;
    }

    .add-to-cart:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .add-to-cart.btn-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    }

    .add-to-cart:disabled {
        opacity: 0.8;
        cursor: not-allowed;
        transform: none;
    }

    /* Enhanced Video List Items - Website Compatible */
    .video-list {
        list-style: none;
        padding-right: 0;
        margin-bottom: 0;
        background: var(--bg-primary);
        border-radius: var(--radius-md);
        overflow: hidden;
        border: 1px solid var(--border-light);
    }

    .video-list li {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border-light);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: var(--bg-primary);
        position: relative;
        min-height: 70px;
    }

    .video-list li::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: var(--primary-color);
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }

    .video-list li:hover {
        background: var(--bg-accent);
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(19, 106, 213, 0.15);
        border-left-color: var(--primary-color);
    }

    .video-list li:hover::before {
        transform: scaleY(1);
    }

    .video-list li:last-child {
        border-bottom: none;
    }

    .video-item-content {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
        min-width: 0; /* Allows text to truncate properly */
    }

    .video-icon {
        width: 40px;
        height: 40px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: white;
        flex-shrink: 0;
        background: var(--bg-gradient-primary);
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
    }

    .video-list li:hover .video-icon {
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(19, 106, 213, 0.4);
    }

    .video-item-info {
        flex: 1;
        min-width: 0;
        overflow: hidden;
    }

    .video-title {
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-size: 1.05rem;
        line-height: 1.4;
        transition: color 0.3s ease;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .video-list li:hover .video-title {
        color: var(--primary-color);
    }

    .video-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.875rem;
        color: var(--text-muted);
        flex-wrap: nowrap;
    }

    .video-time {
        display: flex;
        align-items: center;
        gap: 0.375rem;
        color: var(--text-muted);
        font-size: 0.875rem;
        font-weight: 500;
        white-space: nowrap;
    }

    .video-status {
        font-size: 0.8rem;
        font-weight: 700;
        padding: 0.375rem 0.875rem;
        border-radius: var(--radius-lg);
        text-transform: uppercase;
        letter-spacing: 0.75px;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .video-status.preview {
        background: var(--bg-gradient-primary);
        color: white;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--primary-light);
    }

    .video-status.locked {
        background: linear-gradient(135deg, var(--text-muted) 0%, var(--text-light) 100%);
        color: white;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--text-light);
    }

    .video-status.free {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        box-shadow: var(--shadow-sm);
        border: 1px solid #20c997;
    }

    .item-type {
        background: var(--bg-accent);
        padding: 0.25rem 0.5rem;
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--text-secondary);
        white-space: nowrap;
    }

    /* Enhanced Sidebar Design */
    .sidebar {
        margin-bottom: 2rem;
    }

    /* Enhanced Course Sidebar Block */
    .course-sidebar {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .course-sidebar:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
    }

    .course-sidebar::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6, #8b5cf6, #06b6d4);
        border-radius: 16px 16px 0 0;
    }

    .course-sidebar h4 {
        color: #1e293b;
        font-weight: 700;
        margin-bottom: 1.5rem;
        font-size: 1.4rem;
        text-align: center;
        position: relative;
    }

    .course-sidebar h4::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        width: 40px;
        height: 3px;
        background: linear-gradient(90deg, #3b82f6, #8b5cf6);
        border-radius: 2px;
    }

    /* Enhanced Video Block */
    .video-block {
        margin-bottom: 1.5rem;
    }

    .video-container {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        background: #f1f5f9;
    }

    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
        border-radius: 12px;
    }

    /* Enhanced Course Features */
    .course-features {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 2rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-lg);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .course-features:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-xl);
    }

    .course-features::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        pointer-events: none;
        animation: shimmer 3s ease-in-out infinite;
    }

    .course-features h5 {
        font-size: 1.4rem;
        margin-bottom: 1.5rem;
        font-weight: 700;
        text-align: center;
        position: relative;
        z-index: 1;
        color: #ffffff;
    }

    .course-features h5::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        width: 50px;
        height: 3px;
        background: linear-gradient(90deg, var(--secondary-color), var(--primary-light));
        border-radius: 2px;
    }

    .course-features-list {
        list-style: none;
        padding: 0;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .course-features-list li {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .course-features-list li:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateX(-3px);
        border-color: rgba(255, 255, 255, 0.2);
    }

    .feature-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #3b82f6, #06b6d4);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .feature-icon i {
        color: white;
        font-size: 1.2rem;
    }

    .feature-text {
        color: rgba(255, 255, 255, 0.95);
        font-weight: 500;
        font-size: 1rem;
        line-height: 1.4;
    }

    /* Enhanced Pricing Block */
    .pricing-block {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: white;
        padding: 2.5rem 2rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: 0 10px 25px rgba(5, 150, 105, 0.3);
        text-align: center;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .pricing-block:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(5, 150, 105, 0.4);
    }

    .pricing-block::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 50%);
        animation: rotate 20s linear infinite;
        pointer-events: none;
    }

    .pricing-block h4 {
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        font-weight: 700;
        position: relative;
        z-index: 1;
        color: #ffffff;
    }

    .price-display {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 1;
        color: #ffffff;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .price-currency {
        font-size: 1.2rem;
        font-weight: 600;
        margin-left: 0.5rem;
        opacity: 0.9;
    }

    .pricing-features {
        list-style: none;
        padding: 0;
        margin: 1.5rem 0;
        position: relative;
        z-index: 1;
    }

    .pricing-features li {
        padding: 0.5rem 0;
        color: rgba(255, 255, 255, 0.95);
        font-weight: 500;
        position: relative;
        padding-right: 1.5rem;
    }

    .pricing-features li::before {
        content: '✓';
        position: absolute;
        right: 0;
        color: #10b981;
        font-weight: bold;
        font-size: 1.1rem;
    }

    .pricing-block .btn {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        color: #059669;
        border: none;
        padding: 1rem 2rem;
        font-size: 1.1rem;
        font-weight: 700;
        border-radius: 12px;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
        box-shadow: 0 4px 12px rgba(255, 255, 255, 0.2);
        text-transform: none;
        width: 100%;
    }

    .pricing-block .btn:hover {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        color: #047857;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(255, 255, 255, 0.3);
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: rotate 20s linear infinite;
    pointer-events: none;
    }

    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .pricing-block h3 {
        color: white;
        font-weight: 700;
        margin-bottom: 1rem;
        font-size: 1.5rem;
        position: relative;
        z-index: 1;
    }

    .pricing-block .price {
        font-size: 3rem;
        font-weight: 800;
        color: white;
        margin: 1rem 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        position: relative;
        z-index: 1;
    }

    .pricing-block .offer-price {
        color: rgba(255,255,255,0.9);
        font-weight: bold;
        margin-right: 5px;
        font-size: 1.2rem;
        position: relative;
        z-index: 1;
    }

    .pricing-block .btn {
        background: rgba(255,255,255,0.2);
        border: 2px solid white;
        color: white;
        font-size: 1rem;
        font-weight: 600;
        padding: 0.75rem 2rem;
        border-radius: var(--radius-md);
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
    }

    .pricing-block .btn:hover {
        background: white;
        color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    /* Enhanced Modal Design */
    .modal-backdrop {
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(8px);
    }

    .modal-dialog.modal-lg {
        max-width: 90vw;
        width: 100%;
        margin: 2rem auto;
        transition: all 0.3s ease;
    }

    .modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
        overflow: hidden;
        background: #ffffff;
        animation: modalSlideIn 0.4s ease-out;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-50px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .modal-header {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #ec4899 100%);
        color: white;
        border-bottom: none;
        padding: 1.5rem 2rem;
        position: relative;
        overflow: hidden;
    }

    .modal-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, transparent 100%);
        pointer-events: none;
    }

    .modal-title {
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .modal-title::before {
        content: '▶';
        font-size: 1.2rem;
        opacity: 0.8;
    }

    .modal-header .close {
        color: white;
        opacity: 0.8;
        font-size: 1.5rem;
        font-weight: 300;
        text-shadow: none;
        transition: all 0.2s ease;
        background: rgba(255, 255, 255, 0.1);
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-header .close:hover {
        opacity: 1;
        background: rgba(255, 255, 255, 0.2);
        transform: scale(1.1);
    }

    .modal-body {
        padding: 0;
        background: #f8fafc;
        position: relative;
    }

    .modal-video-container {
        width: 100%;
        position: relative;
        padding-bottom: 56.25%; /* 16:9 aspect ratio */
        height: 0;
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        overflow: hidden;
    }

    .modal-video-container::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .modal-video-container.loading::before {
        opacity: 1;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 0.3; transform: translate(-50%, -50%) scale(1); }
        50% { opacity: 0.6; transform: translate(-50%, -50%) scale(1.1); }
    }

    .modal-video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
        border-radius: 0;
        z-index: 2;
    }

    .modal-footer {
        background: #ffffff;
        border-top: 1px solid #e2e8f0;
        padding: 1.5rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }

    .modal-footer .btn {
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.2s ease;
        border: none;
        position: relative;
        overflow: hidden;
    }

    .modal-footer .btn-secondary {
        background: #64748b;
        color: white;
    }

    .modal-footer .btn-secondary:hover {
        background: #475569;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(100, 116, 139, 0.3);
    }

    .modal-footer .btn-primary {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        position: relative;
    }

    .modal-footer .btn-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s ease;
    }

    .modal-footer .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
    }

    .modal-footer .btn-primary:hover::before {
        left: 100%;
    }

    /* Animation Classes */
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .fade-in {
        animation: fadeIn 0.5s ease-out;
    }

    /* Responsive Design Enhancements */
    @media (max-width: 768px) {
        .course-header-wrapper {
            padding: 1rem 1rem 2rem 1rem;
        }

        .course-stats {
            flex-direction: column;
            gap: 1rem;
        }

        .course-outline .section-title {
            font-size: 1.5rem;
        }

        .custom-accordion .btn-link {
            font-size: 1.1rem;
        }

        .unit-header {
            padding: 1rem !important;
        }

        .video-list li {
            padding: 0.75rem 1rem;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .course-sidebar {
            padding: 1.5rem;
        }

        .pricing-block {
            padding: 1.5rem;
        }

        .pricing-block .price {
            font-size: 2.5rem;
        }
    }

    @media (max-width: 576px) {
        .course-header-wrapper {
            padding: 1rem 0.75rem 1.5rem 0.75rem;
        }

        .course-header-wrapper .section-title {
            font-size: 2rem;
        }

        .course-header-wrapper .course-description {
            font-size: 1rem;
        }

        .unit-purchase {
            align-items: stretch;
        }

        .add-to-cart {
            width: 100%;
            text-align: center;
        }

        .modal-body {
            padding: 1rem;
        }
    }
</style>

<!-- Main Section with Modern Design -->
<div class="untree_co-section">
    <div class="container">
        <!-- Enhanced Course Header -->
        <div class="course-header-wrapper" data-aos="fade-up" data-aos-delay="100">
            <h2 class="section-title text-center"><?= esc($title) ?></h2>
            <p class="course-description text-center"><?= esc($course->course_desc) ?></p>

            <!-- Course Stats Bar -->
            <div class="course-stats">
                <div class="stat-item">
                    <i class="icon-info-circle"></i>
                    <div>
                        <div class="stat-value"><?= esc($course->difficulty_level ?? '1') ?></div>
                        <div class="stat-label">المستوى</div>
                    </div>
                </div>

                <div class="stat-item">
                    <i class="icon-university"></i>
                    <div>
                        <div class="stat-value" style="font-size: 0.95rem;"><?= esc($course->college_name_ar ?? 'الجامعة السعودية الإلكترونية') ?></div>
                        <div class="stat-label">الكلية</div>
                    </div>
                </div>

                <div class="stat-item">
                    <i class="icon-user"></i>
                    <div>
                        <div class="stat-value" style="font-size: 0.95rem;"><?= esc($course->instructor_name ?? 'أحمد فخر الدين') ?></div>
                        <div class="stat-label">المحاضر</div>
                    </div>
                </div>

                <div class="stat-item">
                    <i class="icon-calendar"></i>
                    <div>
                        <div class="stat-value" style="font-size: 0.95rem;">فصل دراسي</div>
                        <div class="stat-label">مدة الاشتراك</div>
                    </div>
                </div>

                <div class="stat-item">
                    <i class="icon-check-circle"></i>
                    <div>
                        <div class="stat-value" style="font-size: 0.95rem; color: <?= (isset($course->is_open) && $course->is_open == 1) ? '#28a745' : '#dc3545' ?>;">
                            <?= (isset($course->is_open) && $course->is_open == 1) ? 'مفتوح' : 'مغلق' ?>
                        </div>
                        <div class="stat-label">حالة الحجز</div>
                    </div>
                </div>

                <?php $unitCount = $course->unit_count ?? count($units); ?>
                <?php if ($unitCount > 0): ?>
                <div class="stat-item">
                    <i class="icon-book"></i>
                    <div>
                        <div class="stat-value"><?= $unitCount ?></div>
                        <div class="stat-label">وحدة</div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($course->video_count)): ?>
                <div class="stat-item">
                    <i class="icon-video"></i>
                    <div>
                        <div class="stat-value"><?= $course->video_count ?></div>
                        <div class="stat-label">فيديو</div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($course->quiz_count)): ?>
                <div class="stat-item">
                    <i class="icon-question-circle"></i>
                    <div>
                        <div class="stat-value"><?= $course->quiz_count ?></div>
                        <div class="stat-label">اختبار</div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($course->page_count)): ?>
                <div class="stat-item">
                    <i class="icon-file-text"></i>
                    <div>
                        <div class="stat-value"><?= $course->page_count ?></div>
                        <div class="stat-label">صفحة</div>
                    </div>
                </div>
                <?php endif; ?>

                <?php 
                $duration = $course->duration ?? '0:00';
                if ($duration !== '0:00' && $duration !== '0 دقيقة' && $duration !== '0'): 
                ?>
                <div class="stat-item">
                    <i class="icon-clock-o"></i>
                    <div>
                        <div class="stat-value"><?= $duration ?></div>
                        <div class="stat-label">دقائق</div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Cart Summary Area - REMOVED -->


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
                                                    $isItemFree = isset($item->is_free) && $item->is_free == 1;
                                                    $previewVideoId = '';
                                                    $previewVideoSource = $metadata['video_source'] ?? 'bunny';
                                                    $previewLibraryId = (!empty($metadata['video_library_id']) && is_numeric($metadata['video_library_id']))
                                                        ? $metadata['video_library_id']
                                                        : '495222';

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
                        <a href="<?= site_url('enrollments/purchase-course/' . $course->id) ?>"
                           class="btn btn-light btn-lg"
                           style="font-weight: 600; border-radius: var(--radius-md); color: var(--primary-color); width: 100%; margin-top: 0.5rem;">
                            <i class="icon-shopping-cart"></i>
                            اشترك الآن
                        </a>
                        <small style="color: rgba(255,255,255,0.8); margin-top: 0.75rem;">احصل على جميع الوحدات والاختبارات</small>
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
                                    : "https://player.mediadelivery.net/embed/" . ($course->collection_id ?? '495222') . "/{$introVideoId}?autoplay=false&preload=false";
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
                            <a href="<?= site_url('enrollments/purchase-course/' . $course->id) ?>"
                               class="btn btn-light btn-lg"
                               style="font-weight: 600; border-radius: var(--radius-md); color: var(--primary-color); width: 100%; margin-top: 0.5rem;">
                                <i class="icon-shopping-cart"></i>
                                اشترك الآن
                            </a>
                            <small style="color: rgba(255,255,255,0.8); margin-top: 0.75rem;">وصول فوري لجميع الدروس والمشاريع</small>
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
                <button type="button" class="btn btn-primary" onclick="window.location.href='<?= site_url('enrollments/purchase-course/' . $course->id) ?>'">شراء الكورس كاملاً</button>
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
            let videoLibraryId = triggerElement.getAttribute("data-video-library-id") || "395633";

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
                    videoUrl = `https://player.mediadelivery.net/embed/${videoLibraryId}/${videoId}?autoplay=true`;
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
