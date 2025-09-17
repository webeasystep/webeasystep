<?php
/**
 * Quizzes Admin Import View
 *
 * This view displays the form for importing quizzes from JSON files.
 */
?>

<?= $this->extend('admin_layout/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= lang('Quizzes.import_quiz_from_json') ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dt_admin') ?>"><?= lang('Admin.dashboard') ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('dt_admin/quizzes') ?>"><?= lang('Quizzes.quizzes') ?></a></li>
                        <li class="breadcrumb-item active"><?= lang('Quizzes.import_quiz') ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= lang('Quizzes.import_quiz_from_json') ?></h3>
                        </div>
                        <div class="card-body">
                            <?= $this->include('admin_layout/errors_list') ?>

                            <?php if (session()->getFlashdata('success')): ?>
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <?= session()->getFlashdata('success') ?>
                                </div>
                            <?php endif; ?>

                            <?php if (session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <?= session()->getFlashdata('error') ?>
                                </div>
                            <?php endif; ?>

                            <form action="<?= base_url('dt_admin/quizzes/import') ?>" method="post" enctype="multipart/form-data">
                                <?= csrf_field() ?>

                                <div class="form-group">
                                    <label for="quiz_file"><?= lang('Admin.select_file') ?> <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="quiz_file" name="quiz_file" accept=".json" required>
                                            <label class="custom-file-label" for="quiz_file"><?= lang('Admin.choose_file') ?></label>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted"><?= lang('Quizzes.only_json_files_allowed') ?></small>
                                </div>

                                <div class="form-group">
                                    <label for="course_id"><?= lang('Courses.course') ?> <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="course_id" name="course_id" required>
                                        <option value=""><?= lang('Admin.select_course') ?></option>
                                        <?php if (isset($courses)): ?>
                                            <?php foreach ($courses as $course): ?>
                                                <option value="<?= $course->id ?>">
                                                    <?= esc($course->course_title) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload"></i> <?= lang('Quizzes.import_quiz') ?>
                                    </button>
                                    <a href="<?= base_url('dt_admin/quizzes') ?>" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> <?= lang('Admin.back') ?>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= lang('Admin.help') ?></h3>
                        </div>
                        <div class="card-body">
                            <h5><?= lang('Admin.json_format_requirements') ?></h5>
                            <p class="text-muted"><?= lang('Quizzes.json_import_help_text') ?></p>

                            <h6><?= lang('Admin.required_fields') ?>:</h6>
                            <ul class="text-sm">
                                <li>quiz_title</li>
                                <li>quiz_desc</li>
                                <li>time_limit_minutes</li>
                                <li>passing_score</li>
                                <li>max_attempts</li>
                                <li>quiz_questions (array)</li>
                            </ul>

                            <h6><?= lang('Admin.example_structure') ?>:</h6>
                            <pre class="text-xs bg-light p-2">{
  "quiz_title": "Sample Quiz",
  "quiz_desc": "Description",
  "time_limit_minutes": 30,
  "passing_score": 70,
  "max_attempts": 3,

  "shuffle_questions": true,
  "show_results_immediately": false,
  "quiz_questions": [
    {
      "question_text": "Question?",
      "question_type": "single_choice",
      "points": 1,
      "options": ["A", "B", "C", "D"],
      "correct_answer": "A"
    }
  ]
}</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    // Custom file input label update
    $('.custom-file-input').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
    });
});
</script>

<?= $this->endSection() ?>
