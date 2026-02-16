<?php
/**
 * Quiz Questions Management View
 *
 * This view displays the questions for a specific quiz.
 */
?>

<?= $this->extend('admin_layout/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= esc($title) ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dt_admin') ?>"><?= lang('Admin.dashboard') ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('dt_admin/quizzes') ?>"><?= lang('Quizzes.quizzes') ?></a></li>
                        <li class="breadcrumb-item active"><?= lang('Quizzes.questions') ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <!-- Quiz Information -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= lang('Quizzes.quiz_information') ?></h3>
                            <div class="card-tools">
                                <a href="<?= base_url('dt_admin/quizzes/edit/' . $quiz->id) ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> <?= lang('Admin.edit_quiz') ?>
                                </a>
                                <a href="<?= base_url('dt_admin/quizzes') ?>" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> <?= lang('Admin.back') ?>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong><?= lang('Quizzes.quiz_title') ?>:</strong> <?= esc($quiz->quiz_title) ?></p>
                                    <p><strong><?= lang('Quizzes.quiz_desc') ?>:</strong> <?= esc($quiz->quiz_desc) ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong><?= lang('Quizzes.time_limit_minutes') ?>:</strong> <?= $quiz->time_limit_minutes ?> <?= lang('Quizzes.minutes') ?></p>
                                    <p><strong><?= lang('Quizzes.passing_score') ?>:</strong> <?= $quiz->passing_score ?>%</p>
                                    <p><strong><?= lang('Quizzes.max_attempts') ?>:</strong> <?= $quiz->max_attempts ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Questions List -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= lang('Quizzes.quiz_questions') ?> (<?= count($questions) ?>)</h3>
                        </div>
                        <div class="card-body">
                            <?php if (empty($questions)): ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> <?= lang('Quizzes.no_questions_found') ?>
                                    <br>
                                    <a href="<?= base_url('dt_admin/quizzes/edit/' . $quiz->id) ?>" class="btn btn-primary btn-sm mt-2">
                                        <i class="fas fa-plus"></i> <?= lang('Quizzes.add_questions') ?>
                                    </a>
                                </div>
                            <?php else: ?>
                                <?php foreach ($questions as $index => $question): ?>
                                    <div class="question-item border p-3 mb-3">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <h5 class="text-primary"><?= lang('Quizzes.question') ?> <?= $index + 1 ?></h5>
                                            </div>
                                            <div class="col-md-2 text-right">
                                                <span class="badge badge-info"><?= esc($question['points'] ?? 1) ?> <?= lang('Quizzes.points') ?></span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-weight-bold"><?= lang('Quizzes.question_text') ?>:</label>
                                            <p class="border p-2 bg-light"><?= esc($question['question_text'] ?? '') ?></p>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="font-weight-bold"><?= lang('Quizzes.question_type') ?>:</label>
                                                <p>
                                                    <?php
                                                    $questionType = $question['question_type'] ?? 'single_choice';
                                                    switch ($questionType) {
                                                        case 'single_choice':
                                                            echo '<span class="badge badge-primary">' . lang('Quizzes.single_choice') . '</span>';
                                                            break;
                                                        case 'multiple_choice':
                                                            echo '<span class="badge badge-success">' . lang('Quizzes.multiple_choice') . '</span>';
                                                            break;
                                                        case 'true_false':
                                                            echo '<span class="badge badge-warning">' . lang('Quizzes.true_false') . '</span>';
                                                            break;
                                                        default:
                                                            echo '<span class="badge badge-secondary">' . esc($questionType) . '</span>';
                                                    }
                                                    ?>
                                                </p>
                                            </div>
                                        </div>

                                        <?php if ($questionType === 'single_choice' || $questionType === 'multiple_choice'): ?>
                                            <?php
                                            $options = $question['options'] ?? [];
                                            $correct = $question['correct'] ?? [];
                                            if (!is_array($correct)) $correct = [$correct];
                                            ?>
                                            <div class="form-group">
                                                <label class="font-weight-bold"><?= lang('Quizzes.options') ?>:</label>
                                                <div class="ml-3">
                                                    <?php foreach ($options as $optIndex => $option): ?>
                                                        <div class="mb-2">
                                                            <span class="mr-2">
                                                                <?php if (in_array($optIndex, $correct)): ?>
                                                                    <i class="fas fa-check-circle text-success"></i>
                                                                <?php else: ?>
                                                                    <i class="far fa-circle text-muted"></i>
                                                                <?php endif; ?>
                                                            </span>
                                                            <span class="<?= in_array($optIndex, $correct) ? 'font-weight-bold text-success' : '' ?>">
                                                                <?= esc($option) ?>
                                                                <?php if (in_array($optIndex, $correct)): ?>
                                                                    <small class="text-success">(<?= lang('Quizzes.correct_answer') ?>)</small>
                                                                <?php endif; ?>
                                                            </span>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php elseif ($questionType === 'true_false'): ?>
                                            <div class="form-group">
                                                <label class="font-weight-bold"><?= lang('Quizzes.correct_answer') ?>:</label>
                                                <p>
                                                    <?php
                                                    $correctAnswer = $question['correct_answer'] ?? 'true';
                                                    if ($correctAnswer === 'true') {
                                                        echo '<span class="badge badge-success"><i class="fas fa-check"></i> ' . lang('Admin.true') . '</span>';
                                                    } else {
                                                        echo '<span class="badge badge-danger"><i class="fas fa-times"></i> ' . lang('Admin.false') . '</span>';
                                                    }
                                                    ?>
                                                </p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
$(document).ready(function() {
    // Any additional JavaScript for the questions view can be added here
});
</script>
<?= $this->endSection() ?>