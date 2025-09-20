<?php
/**
 * Quizzes Site Attempt Details View
 * 
 * This view displays detailed information about a specific quiz attempt for users.
 */
?>

<?= $this->extend('site_layout/template') ?>

<?= $this->section('content') ?>
<div class="site-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Attempt Header -->
                <div class="feature-1 border mb-4 text-center" style="background: <?= $attempt->score >= $quiz->passing_score ? 'var(--bs-success)' : 'var(--bs-warning)' ?>; color: white;">
                    <div class="feature-1-content">
                        <div class="row align-items-center">
                            <div class="col-md-8 text-md-start">
                                <h3 class="mb-2">
                                    <i class="icon-clipboard"></i>
                                    <?= lang('Quizzes.attempt_details') ?>
                                </h3>
                                <p class="lead mb-0"><?= esc($quiz->quiz_title) ?></p>
                            </div>
                            <div class="col-md-4">
                                <div class="score-display">
                                    <h1 class="display-4 mb-1"><?= number_format($attempt->score, 1) ?>%</h1>
                                    <small class="opacity-75"><?= lang('Quizzes.your_score') ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attempt Summary -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="feature-1 border text-center">
                            <div class="feature-1-content">
                                <h3 class="text-primary mb-2"><?= number_format($attempt->score, 1) ?>%</h3>
                                <p class="text-muted mb-0"><?= lang('Quizzes.final_score') ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="feature-1 border text-center">
                            <div class="feature-1-content">
                                <h3 class="text-<?= $attempt->score >= $quiz->passing_score ? 'success' : 'danger' ?> mb-2">
                                    <?= $attempt->score >= $quiz->passing_score ? lang('Quizzes.passed') : lang('Quizzes.failed') ?>
                                </h3>
                                <p class="text-muted mb-0"><?= lang('Quizzes.status') ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="feature-1 border text-center">
                            <div class="feature-1-content">
                                <h3 class="text-info mb-2"><?= gmdate('H:i:s', $attempt->time_taken_seconds ?? 0) ?></h3>
                                <p class="text-muted mb-0"><?= lang('Quizzes.time_taken') ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="feature-1 border text-center">
                            <div class="feature-1-content">
                                <h3 class="text-secondary mb-2"><?= date('M d, Y', strtotime($attempt->attempt_date)) ?></h3>
                                <p class="text-muted mb-0"><?= lang('Quizzes.attempt_date') ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quiz Information -->
                <div class="feature-1 border mb-4">
                    <div class="feature-1-content">
                        <h4 class="mb-4"><?= lang('Quizzes.quiz_information') ?></h4>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-3"><span class="fw-semibold"><?= lang('Quizzes.quiz_title') ?>:</span> <?= esc($quiz->quiz_title) ?></p>
                                <p class="mb-3"><span class="fw-semibold"><?= lang('Quizzes.passing_score') ?>:</span> <?= number_format($quiz->passing_score, 1) ?>%</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-3"><span class="fw-semibold"><?= lang('Quizzes.total_questions') ?>:</span> <?= count($quiz_questions) ?></p>
                                <p class="mb-3"><span class="fw-semibold"><?= lang('Quizzes.time_limit') ?>:</span> 
                                    <?= $quiz->time_limit_minutes ? $quiz->time_limit_minutes . ' ' . lang('Quizzes.minutes') : lang('Quizzes.unlimited') ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Questions and Answers -->
                <?php if (!empty($quiz_questions) && !empty($user_answers)): ?>
                <div class="feature-1 border mb-4">
                    <div class="feature-1-content">
                        <h4 class="mb-4"><?= lang('Quizzes.questions_and_answers') ?></h4>
                        <?php foreach ($quiz_questions as $index => $question): ?>
                            <div class="border rounded p-4 mb-4">
                                <h5 class="mb-3">
                                    <span class="badge bg-secondary me-2"><?= $index + 1 ?></span>
                                    <?= esc($question['question_text'] ?? 'Question text not available') ?>
                                </h5>
                                
                                <?php if (isset($question['options']) && is_array($question['options'])): ?>
                                    <div class="options">
                                        <?php foreach ($question['options'] as $optionIndex => $option): ?>
                                            <?php 
                                            $isUserAnswer = isset($user_answers[$index]) && $user_answers[$index] == $optionIndex;
                                            $isCorrect = isset($question['correct_answer']) && $question['correct_answer'] == $optionIndex;
                                            $optionClass = '';
                                            if ($isCorrect) {
                                                $optionClass = 'alert-success';
                                            } elseif ($isUserAnswer && !$isCorrect) {
                                                $optionClass = 'alert-danger';
                                            } else {
                                                $optionClass = 'alert-light';
                                            }
                                            ?>
                                            <div class="alert <?= $optionClass ?> py-2 mb-2">
                                                <div class="d-flex align-items-center">
                                                    <span class="fw-semibold me-3"><?= chr(65 + $optionIndex) ?>.</span>
                                                    <span class="flex-grow-1"><?= esc($option) ?></span>
                                                    <div class="option-indicators">
                                                        <?php if ($isCorrect): ?>
                                                            <i class="icon-check-circle text-success" title="<?= lang('Quizzes.correct_answer') ?>"></i>
                                                        <?php endif; ?>
                                                        <?php if ($isUserAnswer): ?>
                                                            <i class="icon-user text-primary ms-2" title="<?= lang('Quizzes.your_answer') ?>"></i>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (isset($question['explanation']) && !empty($question['explanation'])): ?>
                                    <div class="explanation mt-3">
                                        <div class="alert alert-info">
                                            <span class="fw-semibold"><?= lang('Quizzes.explanation') ?>:</span>
                                            <?= esc($question['explanation']) ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Actions -->
                <div class="feature-1 border">
                    <div class="feature-1-content text-center">
                        <div class="btn-group-vertical btn-group-sm-horizontal" role="group">
                            <a href="<?= base_url('quizzes/results/' . $attempt->id) ?>" class="btn btn-primary mb-2 mb-sm-0 me-sm-2">
                                <i class="icon-chart"></i> <?= lang('Quizzes.view_results') ?>
                            </a>
                            
                            <a href="<?= base_url('quizzes') ?>" class="btn btn-secondary mb-2 mb-sm-0 me-sm-2">
                                <i class="icon-list"></i> <?= lang('Quizzes.back_to_quizzes') ?>
                            </a>
                            
                            <?php if (isset($quiz->course_id)): ?>
                                <a href="<?= base_url('courses/course_view/' . $quiz->slug) ?>" class="btn btn-outline-primary">
                                <i class="icon-book"></i> <?= lang('Courses.back_to_course') ?>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>