<?= $this->extend('site_layout/template'); ?>

<?= $this->section('content'); ?>
<style>
    .instructor-shell {
        background: #f5f7fb;
        min-height: calc(100vh - 140px);
        padding: 32px 0 48px;
    }

    .instructor-hero {
        background: linear-gradient(135deg, #136ad5 0%, #5c8ff0 100%);
        border-radius: 24px;
        color: #fff;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 18px 40px rgba(19, 106, 213, 0.18);
    }

    .instructor-search {
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 16px;
        padding: 12px 16px;
        color: #fff;
    }

    .instructor-search::placeholder {
        color: rgba(255, 255, 255, 0.9);
    }

    .instructor-hero-badge {
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 700;
        padding: 8px 14px;
    }

    .instructor-sidebar,
    .instructor-panel,
    .stat-card {
        background: #fff;
        border: 1px solid #ebeff6;
        border-radius: 22px;
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
    }

    .instructor-sidebar {
        padding: 20px;
        position: sticky;
        top: 24px;
    }

    .instructor-nav-link {
        align-items: center;
        border-radius: 16px;
        color: #233252;
        display: flex;
        font-weight: 600;
        gap: 12px;
        margin-bottom: 10px;
        padding: 13px 14px;
        text-decoration: none;
        transition: 0.2s ease;
    }

    .instructor-nav-link:hover,
    .instructor-nav-link.is-active {
        background: #eef4ff;
        color: #136ad5;
    }

    .instructor-panel {
        padding: 22px;
        height: 100%;
    }

    .stat-card {
        padding: 20px;
        height: 100%;
    }

    .stat-card .stat-value {
        color: #0f172a;
        font-size: 1.75rem;
        font-weight: 700;
    }

    .record-card {
        align-items: center;
        border: 1px solid #edf1f7;
        border-radius: 18px;
        display: flex;
        gap: 18px;
        margin-bottom: 16px;
        padding: 16px;
    }

    .record-card img {
        border-radius: 16px;
        flex-shrink: 0;
        height: 82px;
        object-fit: cover;
        width: 112px;
    }

    .record-card:last-child,
    .table tbody tr:last-child td,
    .faq-item:last-child {
        margin-bottom: 0;
    }

    .record-meta,
    .table td,
    .table th,
    .faq-answer {
        color: #52607a;
    }

    .table thead th {
        border-bottom: 0;
        color: #1e293b;
        font-size: 0.92rem;
        font-weight: 700;
    }

    .table tbody td {
        border-color: #edf1f7;
        vertical-align: middle;
    }

    .status-badge {
        border-radius: 999px;
        display: inline-flex;
        font-size: 0.85rem;
        font-weight: 700;
        padding: 7px 12px;
    }

    .status-approved {
        background: #e8f8ef;
        color: #15803d;
    }

    .status-pending {
        background: #fff4db;
        color: #b45309;
    }

    .status-rejected,
    .status-expired {
        background: #feeaea;
        color: #dc2626;
    }

    .faq-item {
        border-bottom: 1px solid #edf1f7;
        padding: 18px 0;
    }

    .faq-question {
        color: #0f172a;
        font-size: 1.05rem;
        font-weight: 700;
        margin-bottom: 8px;
    }

    @media (max-width: 991.98px) {
        .instructor-sidebar {
            margin-bottom: 20px;
            position: static;
        }

        .record-card {
            align-items: flex-start;
            flex-direction: column;
        }

        .record-card img {
            width: 100%;
            height: 180px;
        }
    }
</style>

<section class="instructor-shell">
    <div class="container">
        <div class="instructor-hero">
            <div class="row align-items-center g-3">
                <div class="col-lg-6">
                    <span class="badge text-bg-light instructor-hero-badge mb-3"><?= esc($user_type_label ?? 'محاضر') ?></span>
                    <h1 class="h3 fw-bold mb-2"><?= esc($title ?? 'لوحة تحكم المحاضر') ?></h1>
                    <p class="mb-0 opacity-75">مرحبًا <?= esc($instructor_name ?? '') ?>، تابع مقرراتك وطلبات الشراء وإجمالي أرباحك من مكان واحد.</p>
                </div>
                <div class="col-lg-6">
                    <div class="position-relative">
                        <i class="fas fa-search position-absolute top-50 translate-middle-y text-white-50" style="right: 16px;"></i>
                        <input type="search" id="instructorPageSearch" class="form-control instructor-search ps-5" placeholder="ابحث عن المحتوى أو أقسام الصفحة..." autocomplete="off">
                    </div>
                </div>
            </div>
        </div>

        <?= $this->include('site_layout/site_msg'); ?>

        <div class="row g-4">
            <div class="col-lg-3">
                <aside class="instructor-sidebar">
                    <h2 class="h6 fw-bold mb-3">التنقل السريع</h2>
                    <div id="instructorSidebarNav">
                        <?php foreach (($sidebar_links ?? []) as $link) : ?>
                            <a href="<?= esc($link['url']) ?>" class="instructor-nav-link <?= ($active_nav ?? '') === $link['key'] ? 'is-active' : '' ?>" data-search="<?= esc($link['label']) ?>">
                                <i class="<?= esc($link['icon']) ?>"></i>
                                <span><?= esc($link['label']) ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </aside>
            </div>

            <div class="col-lg-9">
                <?= $this->renderSection('instructor_page_content'); ?>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var globalSearchInput = document.getElementById('instructorPageSearch');
        var navLinks = document.querySelectorAll('#instructorSidebarNav .instructor-nav-link');
        var searchableNodes = document.querySelectorAll('[data-page-search]');

        if (!globalSearchInput) {
            return;
        }

        globalSearchInput.addEventListener('input', function () {
            var query = this.value.trim().toLowerCase();

            navLinks.forEach(function (link) {
                var text = (link.dataset.search || link.textContent || '').toLowerCase();
                link.style.display = text.indexOf(query) !== -1 || query === '' ? 'flex' : 'none';
            });

            searchableNodes.forEach(function (node) {
                var text = (node.dataset.pageSearch || node.textContent || '').toLowerCase();
                node.style.display = text.indexOf(query) !== -1 || query === '' ? '' : 'none';
            });
        });
    });
</script>
<?= $this->endSection(); ?>
