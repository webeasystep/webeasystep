<?php
/**
 * Quizzes Site Index View
 *
 * This view displays available quizzes for students.
 */
?>

<?= $this->extend('site_layout/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= lang('Quizzes.available_quizzes') ?></h3>
                </div>
                <div class="card-body">
                    <?php if (isset($quizzes) && !empty($quizzes)): ?>
                        <div class="row">
                            <?php foreach ($quizzes as $quiz): ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 quiz-card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0"><?= esc($quiz->quiz_title) ?></h5>
                                        <div class="d-flex justify-content-end align-items-center mt-2">
                                            <small class="text-muted">
                                                <i class="fas fa-clock"></i> <?= $quiz->time_limit_minutes ?> <?= lang('Admin.minutes') ?>
                                            </small>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <?php if ($quiz->quiz_description): ?>
                                            <p class="card-text"><?= esc(substr($quiz->quiz_description, 0, 100)) ?><?= strlen($quiz->quiz_description) > 100 ? '...' : '' ?></p>
                                        <?php endif; ?>

                                        <div class="quiz-info">
                                            <div class="row text-center">
                                                <div class="col-4">
                                                    <small class="text-muted"><?= lang('Quizzes.questions') ?></small>
                                                    <div class="font-weight-bold"><?= $quiz->question_count ?? 0 ?></div>
                                                </div>
                                                <div class="col-4">
                                                    <small class="text-muted"><?= lang('Quizzes.passing_score') ?></small>
                                                    <div class="font-weight-bold"><?= number_format($quiz->passing_score, 0) ?>%</div>
                                                </div>
                                                <div class="col-4">
                                                    <small class="text-muted"><?= lang('Quizzes.attempts') ?></small>
                                                    <div class="font-weight-bold">
                                                        <?php if (isset($quiz->user_attempts)): ?>
                                                            <?= $quiz->user_attempts ?>/<?= $quiz->max_attempts ?: '∞' ?>
                                                        <?php else: ?>
                                                            0/<?= $quiz->max_attempts ?: '∞' ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if (isset($quiz->course_title)): ?>
                                            <div class="mt-3">
                                                <small class="text-muted">
                                                    <i class="fas fa-book"></i> <?= esc($quiz->course_title) ?>
                                                </small>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (isset($quiz->best_score) && $quiz->best_score !== null): ?>
                                            <div class="mt-2">
                                                <small class="text-muted"><?= lang('Quizzes.best_score') ?>:</small>
                                                <span class="badge badge-<?= $quiz->best_score >= $quiz->passing_score ? 'success' : 'warning' ?>">
                                                    <?= number_format($quiz->best_score, 1) ?>%
                                                </span>
                                                <?php if ($quiz->best_score >= $quiz->passing_score): ?>
                                                    <i class="fas fa-check-circle text-success ml-1" title="<?= lang('Quizzes.passed') ?>"></i>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-footer">
                                        <?php
                                        $canTakeQuiz = true;
                                        $buttonText = lang('Quizzes.start_quiz');
                                        $buttonClass = 'btn-primary';
                                        $buttonIcon = 'fas fa-play';

                                        if (isset($quiz->user_attempts) && $quiz->max_attempts && $quiz->user_attempts >= $quiz->max_attempts) {
                                            $canTakeQuiz = false;
                                            $buttonText = lang('Quizzes.no_attempts_left');
                                            $buttonClass = 'btn-secondary';
                                            $buttonIcon = 'fas fa-ban';
                                        } elseif (isset($quiz->best_score) && $quiz->best_score >= $quiz->passing_score) {
                                            $buttonText = lang('Quizzes.retake_quiz');
                                            $buttonClass = 'btn-success';
                                            $buttonIcon = 'fas fa-redo';
                                        }
                                        ?>

                                        <div class="d-flex justify-content-between">
                                            <?php if ($canTakeQuiz): ?>
                                                <a href="<?= base_url('quizzes/take/' . $quiz->id) ?>" class="btn <?= $buttonClass ?> btn-sm flex-fill mr-2">
                                                    <i class="<?= $buttonIcon ?>"></i> <?= $buttonText ?>
                                                </a>
                                            <?php else: ?>
                                                <button class="btn <?= $buttonClass ?> btn-sm flex-fill mr-2" disabled>
                                                    <i class="<?= $buttonIcon ?>"></i> <?= $buttonText ?>
                                                </button>
                                            <?php endif; ?>

                                            <?php if (isset($quiz->user_attempts) && $quiz->user_attempts > 0): ?>
                                                <a href="<?= base_url('quizzes/attempts/' . $quiz->id) ?>" class="btn btn-outline-info btn-sm" title="<?= lang('Quizzes.view_attempts') ?>">
                                                    <i class="fas fa-history"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Pagination -->
                        <?php if (isset($pager)): ?>
                            <div class="d-flex justify-content-center mt-4">
                                <?= $pager->links() ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-question-circle fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted"><?= lang('Quizzes.no_quizzes_available') ?></h4>
                            <p class="text-muted"><?= lang('Quizzes.no_quizzes_desc') ?></p>
                            <a href="<?= base_url('courses') ?>" class="btn btn-primary">
                                <i class="fas fa-book"></i> <?= lang('Courses.browse_courses') ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<style>
.quiz-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border: 1px solid #dee2e6;
}

.quiz-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.quiz-info {
    background-color: #f8f9fa;
    border-radius: 0.25rem;
    padding: 0.75rem;
    margin: 1rem 0;
}

.badge {
    font-size: 0.75em;
}

.card-footer {
    background-color: #fff;
    border-top: 1px solid #dee2e6;
}
</style>

<script>
$(document).ready(function() {
    // Add hover effects and animations
    $('.quiz-card').hover(
        function() {
            $(this).addClass('shadow-sm');
        },
        function() {
            $(this).removeClass('shadow-sm');
        }
    );

    // Confirm quiz start
    $('a[href*="/quizzes/take/"]').click(function(e) {
        const quizTitle = $(this).closest('.quiz-card').find('.card-title').text();
        const timeLimit = $(this).closest('.quiz-card').find('.fa-clock').parent().text().trim();

        const message = `<?= lang('Quizzes.confirm_start_quiz') ?>\n\n` +
                       `<?= lang('Quizzes.quiz') ?>: ${quizTitle}\n` +
                       `<?= lang('Quizzes.time_limit') ?>: ${timeLimit}`;

        if (!confirm(message)) {
            e.preventDefault();
        }
    });
});
</script>
<?= $this->endSection() ?>
