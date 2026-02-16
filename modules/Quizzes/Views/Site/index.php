<?php
/**
 * Quizzes Site Index View
 *
 * This view displays available quizzes for students.
 */
?>

<?= $this->extend('site_layout/template') ?>

<?= $this->section('content') ?>
<div class="site-section">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <h2 class="section-title"><?= lang('Quizzes.available_quizzes') ?></h2>
                <p class="lead text-muted"><?= lang('Quizzes.quiz_listing_desc') ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="quiz-listing">
                    <?php if (isset($quizzes) && !empty($quizzes)): ?>
                        <div class="row">
                            <?php foreach ($quizzes as $quiz): ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="feature-1 border h-100">
                                    <div class="feature-1-content">
                                        <h3 class="mb-3"><?= esc($quiz->quiz_title) ?></h3>
                                        <?php if ($quiz->quiz_desc): ?>
                                            <p class="mb-4"><?= esc(substr($quiz->quiz_desc, 0, 120)) ?><?= strlen($quiz->quiz_desc) > 120 ? '...' : '' ?></p>
                                        <?php endif; ?>
                                        
                                        <div class="quiz-meta mb-4">
                                            <div class="row text-center">
                                                <div class="col-4">
                                                    <div class="quiz-stat">
                                                        <div class="quiz-stat-number"><?= $quiz->question_count ?? 0 ?></div>
                                                        <div class="quiz-stat-label"><?= lang('Quizzes.questions') ?></div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="quiz-stat">
                                                        <div class="quiz-stat-number"><?= number_format($quiz->passing_score, 0) ?>%</div>
                                                        <div class="quiz-stat-label"><?= lang('Quizzes.passing_score') ?></div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="quiz-stat">
                                                        <div class="quiz-stat-number">
                                                            <?php if (isset($quiz->user_attempts)): ?>
                                                                <?= $quiz->user_attempts ?>/<?= $quiz->max_attempts ?: '∞' ?>
                                                            <?php else: ?>
                                                                0/<?= $quiz->max_attempts ?: '∞' ?>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="quiz-stat-label"><?= lang('Quizzes.attempts') ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="quiz-info mb-4">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="icon-clock-o mr-2 text-primary"></i>
                                                <span class="small"><?= $quiz->time_limit_minutes ?> <?= lang('Admin.minutes') ?></span>
                                            </div>
                                            <?php if (isset($quiz->course_title)): ?>
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="icon-book mr-2 text-primary"></i>
                                                    <span class="small"><?= esc($quiz->course_title) ?></span>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (isset($quiz->best_score) && $quiz->best_score !== null): ?>
                                                <div class="d-flex align-items-center">
                                                    <i class="icon-trophy mr-2 text-primary"></i>
                                                    <span class="small"><?= lang('Quizzes.best_score') ?>: </span>
                                                    <span class="badge badge-<?= $quiz->best_score >= $quiz->passing_score ? 'success' : 'warning' ?> ml-2">
                                                        <?= number_format($quiz->best_score, 1) ?>%
                                                    </span>
                                                    <?php if ($quiz->best_score >= $quiz->passing_score): ?>
                                                        <i class="icon-check text-success ml-1"></i>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="quiz-actions">
                                            <?php
                                            $canTakeQuiz = true;
                                            $buttonText = lang('Quizzes.start_quiz');
                                            $buttonClass = 'btn-primary';

                                            if (isset($quiz->user_attempts) && $quiz->max_attempts && $quiz->user_attempts >= $quiz->max_attempts) {
                                                $canTakeQuiz = false;
                                                $buttonText = lang('Quizzes.no_attempts_left');
                                                $buttonClass = 'btn-outline-secondary';
                                            } elseif (isset($quiz->best_score) && $quiz->best_score >= $quiz->passing_score) {
                                                $buttonText = lang('Quizzes.retake_quiz');
                                                $buttonClass = 'btn-secondary';
                                            }
                                            ?>

                                            <div class="d-flex">
                                                <?php if ($canTakeQuiz): ?>
                                                    <a href="<?= base_url('quizzes/take/' . $quiz->id) ?>" class="btn <?= $buttonClass ?> btn-sm flex-fill ml-2">
                                                        <?= $buttonText ?>
                                                    </a>
                                                <?php else: ?>
                                                    <button class="btn <?= $buttonClass ?> btn-sm flex-fill ml-2" disabled>
                                                        <?= $buttonText ?>
                                                    </button>
                                                <?php endif; ?>

                                                <?php if (isset($quiz->user_attempts) && $quiz->user_attempts > 0): ?>
                                                    <a href="<?= base_url('quizzes/attempts/' . $quiz->id) ?>" class="btn btn-outline-primary btn-sm" title="<?= lang('Quizzes.view_attempts') ?>">
                                                        <i class="icon-history"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Pagination -->
                        <?php if (isset($pager)): ?>
                            <div class="d-flex justify-content-center mt-5">
                                <?= $pager->links() ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <div class="feature-1 border">
                                <div class="feature-1-content text-center">
                                    <i class="icon-question-circle display-4 text-muted mb-4"></i>
                                    <h3 class="mb-3"><?= lang('Quizzes.no_quizzes_available') ?></h3>
                                    <p class="text-muted mb-4"><?= lang('Quizzes.no_quizzes_desc') ?></p>
                                    <a href="<?= base_url('courses') ?>" class="btn btn-primary">
                                        <i class="icon-book mr-2"></i><?= lang('Courses.browse_courses') ?>
                                    </a>
                                </div>
                            </div>
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
.quiz-stat {
    padding: 0.5rem 0;
}

.quiz-stat-number {
    font-size: 1.25rem;
    font-weight: 700;
    color: #136ad5;
    line-height: 1.2;
}

.quiz-stat-label {
    font-size: 0.75rem;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.05rem;
}

.quiz-meta {
    background-color: rgba(19, 106, 213, 0.05);
    border-radius: 7px;
    padding: 1rem;
}

.quiz-info .small {
    color: #666;
}

.quiz-actions {
    margin-top: auto;
}

.feature-1 {
    padding: 2rem;
    border-radius: 7px;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

.feature-1:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.feature-1-content {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

@media (max-width: 768px) {
    .quiz-stat-number {
        font-size: 1rem;
    }
    
    .feature-1 {
        padding: 1.5rem;
    }
}
</style>

<script>
$(document).ready(function() {
    // Confirm quiz start
    $('a[href*="/quizzes/take/"]').click(function(e) {
        const quizTitle = $(this).closest('.feature-1').find('h3').text();
        const timeLimit = $(this).closest('.feature-1').find('.icon-clock-o').parent().text().trim();

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
