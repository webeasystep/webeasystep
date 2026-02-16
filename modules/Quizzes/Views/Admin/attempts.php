<?php
/**
 * Quizzes Admin Attempts View
 *
 * This view displays quiz attempts for administrators.
 */
?>

<?= $this->extend('admin_layout/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= $title ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dt_admin') ?>"><?= lang('Admin.dashboard') ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('dt_admin/quizzes') ?>"><?= lang('Quizzes.quizzes') ?></a></li>
                        <li class="breadcrumb-item active"><?= lang('Quizzes.quiz_attempts') ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <?php if (isset($quiz)): ?>
            <!-- Quiz Information -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= lang('Quizzes.quiz_information') ?></h3>
                        </div>
                        <div class="card-body">
                            <p><strong><?= lang('Quizzes.quiz_title') ?>:</strong> <?= esc($quiz->quiz_title) ?></p>
                            <p><strong><?= lang('Quizzes.quiz_description') ?>:</strong> <?= esc($quiz->quiz_desc) ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Attempts Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= lang('Quizzes.quiz_attempts') ?></h3>
                        </div>
                        <div class="card-body">
                            <?= $this->include('admin_layout/dt_table') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize DataTable for attempts
    window.dtTable = $('#dtTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: window.location.href,
            type: 'POST'
        },
        columns: [
            { data: 'username', name: 'username' },
            { data: 'quiz_title', name: 'quiz_title' },
            { data: 'course_title', name: 'course_title' },
            { data: 'attempt_date', name: 'attempt_date' },
            { data: 'score', name: 'score' },
            { data: 'time_taken_seconds', name: 'time_taken_seconds' },
            { data: 'is_passed', name: 'is_passed' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[3, 'desc']], // Order by attempt_date descending
        language: {
            url: '<?= base_url('assets/plugins/datatables/i18n/' . service('request')->getLocale() . '.json') ?>'
        }
    });
});
</script>
<?= $this->endSection() ?>