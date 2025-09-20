<?php
/**
 * Quizzes Site Results View
 * 
 * This view displays quiz results for students.
 */
?>

<?= $this->extend('site_layout/template') ?>

<?= $this->section('content') ?>
<div class="site-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Results Header -->
                <div class="feature-1 border mb-4 <?= $attempt->score >= $quiz->passing_score ? 'border-success' : 'border-warning' ?>">
                    <div class="feature-1-content bg-<?= $attempt->score >= $quiz->passing_score ? 'success' : 'warning' ?> text-white">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h3 class="mb-2">
                                    <i class="icon-<?= $attempt->score >= $quiz->passing_score ? 'trophy' : 'exclamation-triangle' ?>"></i>
                                    <?= $attempt->score >= $quiz->passing_score ? lang('Quizzes.quiz_passed') : lang('Quizzes.quiz_failed') ?>
                                </h3>
                                <p class="mb-0 lead"><?= esc($quiz->quiz_title) ?></p>
                            </div>
                            <div class="col-md-4 text-md-right">
                                <div class="score-display">
                                    <h1 class="mb-0 display-4"><?= number_format($attempt->score, 1) ?>%</h1>
                                    <small class="opacity-75"><?= lang('Quizzes.your_score') ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Score Summary -->
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
                                <h3 class="text-success mb-2"><?= $correct_answers ?></h3>
                                <p class="text-muted mb-0"><?= lang('Quizzes.correct_answers') ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="feature-1 border text-center">
                            <div class="feature-1-content">
                                <h3 class="text-danger mb-2"><?= $wrong_answers ?></h3>
                                <p class="text-muted mb-0"><?= lang('Quizzes.wrong_answers') ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="feature-1 border text-center">
                            <div class="feature-1-content">
                                <h3 class="text-info mb-2"><?= gmdate('H:i:s', $attempt->time_taken_seconds ?? 0) ?></h3>
                                <p class="text-muted mb-0"><?= lang('Quizzes.completion_time') ?></p>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Passing Score Info -->
                <div class="feature-1 border mb-4">
                    <div class="feature-1-content">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="mb-2"><?= lang('Quizzes.passing_score_label') ?>: <?= number_format($quiz->passing_score, 1) ?>%</h5>
                                <p class="text-muted mb-0">
                                    <?php if ($attempt->score >= $quiz->passing_score): ?>
                                        <i class="icon-check-circle text-success"></i> <?= lang('Quizzes.congratulations_passed') ?>
                                    <?php else: ?>
                                        <i class="icon-times-circle text-danger"></i> <?= lang('Quizzes.need_to_improve') ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <div class="progress mb-2" style="height: 20px;">
                                    <div class="progress-bar bg-<?= $attempt->score >= $quiz->passing_score ? 'success' : 'warning' ?>" 
                                         role="progressbar" 
                                         style="width: <?= min($attempt->score, 100) ?>%" 
                                         aria-valuenow="<?= $attempt->score ?>" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        <?= number_format($attempt->score, 1) ?>%
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">0%</small>
                                    <small class="text-muted"><?= number_format($quiz->passing_score, 1) ?>% (<?= lang('Quizzes.passing') ?>)</small>
                                    <small class="text-muted">100%</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Question Review -->
                <?php if ($quiz->show_results_immediately && isset($questions_with_answers)): ?>
                <div class="feature-1 border mb-4">
                    <div class="feature-1-content">
                        <h4 class="mb-4"><?= lang('Quizzes.question_review') ?></h4>
                        
                        <?php foreach ($questions_with_answers as $index => $question): ?>
                        <div class="question-review mb-4 pb-4 <?= $index < count($questions_with_answers) - 1 ? 'border-bottom' : '' ?>">
                            <div class="row align-items-center mb-3">
                                <div class="col-md-10">
                                    <h5 class="text-primary mb-0">
                                        <?= lang('Quizzes.question') ?> <?= $index + 1 ?>
                                        <span class="badge badge-<?= $question->is_correct ? 'success' : 'danger' ?> ml-2">
                                            <?= $question->points ?> <?= lang('Admin.points') ?>
                                        </span>
                                    </h5>
                                </div>
                                <div class="col-md-2 text-right">
                                    <i class="icon-<?= $question->is_correct ? 'check-circle text-success' : 'times-circle text-danger' ?> display-6"></i>
                                </div>
                            </div>
                            
                            <p class="mb-3 lead"><?= esc($question->question_text) ?></p>
                        
                        <?php if ($question->question_type === 'single_choice' || $question->question_type === 'multiple_choice'): ?>
                            <?php $options = json_decode($question->options, true); ?>
                            <?php $correctAnswers = json_decode($question->correct_answer, true); ?>
                            <?php $userAnswers = is_array($question->user_answer) ? $question->user_answer : [$question->user_answer]; ?>
                            
                            <?php if ($options): ?>
                                <div class="options-review">
                                    <?php foreach ($options as $optIndex => $option): ?>
                                        <?php 
                                        $isCorrect = in_array($optIndex, (array)$correctAnswers);
                                        $isUserAnswer = in_array($optIndex, $userAnswers);
                                        $optionClass = '';
                                        $iconClass = '';
                                        
                                        if ($isCorrect && $isUserAnswer) {
                                            $optionClass = 'bg-success text-white';
                                            $iconClass = 'fas fa-check-circle';
                                        } elseif ($isCorrect && !$isUserAnswer) {
                                            $optionClass = 'bg-light border-success';
                                            $iconClass = 'fas fa-check-circle text-success';
                                        } elseif (!$isCorrect && $isUserAnswer) {
                                            $optionClass = 'bg-danger text-white';
                                            $iconClass = 'fas fa-times-circle';
                                        } else {
                                            $optionClass = 'bg-light';
                                            $iconClass = 'far fa-circle text-muted';
                                        }
                                        ?>
                                        <div class="p-2 mb-2 rounded <?= $optionClass ?>">
                                            <i class="<?= $iconClass ?>"></i> <?= esc($option) ?>
                                            <?php if ($isUserAnswer && !$isCorrect): ?>
                                                <small class="ml-2">(<?= lang('Quizzes.your_answer') ?>)</small>
                                            <?php elseif ($isCorrect): ?>
                                                <small class="ml-2">(<?= lang('Quizzes.correct_answer') ?>)</small>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                        <?php elseif ($question->question_type === 'true_false'): ?>
                            <div class="options-review">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="p-2 rounded <?= $question->user_answer === 'true' ? ($question->correct_answer === 'true' ? 'bg-success text-white' : 'bg-danger text-white') : ($question->correct_answer === 'true' ? 'bg-light border-success' : 'bg-light') ?>">
                                            <i class="fas fa-<?= $question->correct_answer === 'true' ? 'check' : 'times' ?>"></i> <?= lang('Admin.true') ?>
                                            <?php if ($question->user_answer === 'true'): ?>
                                                <small class="ml-2">(<?= lang('Quizzes.your_answer') ?>)</small>
                                            <?php endif; ?>
                                            <?php if ($question->correct_answer === 'true'): ?>
                                                <small class="ml-2">(<?= lang('Quizzes.correct') ?>)</small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-2 rounded <?= $question->user_answer === 'false' ? ($question->correct_answer === 'false' ? 'bg-success text-white' : 'bg-danger text-white') : ($question->correct_answer === 'false' ? 'bg-light border-success' : 'bg-light') ?>">
                                            <i class="fas fa-<?= $question->correct_answer === 'false' ? 'check' : 'times' ?>"></i> <?= lang('Admin.false') ?>
                                            <?php if ($question->user_answer === 'false'): ?>
                                                <small class="ml-2">(<?= lang('Quizzes.your_answer') ?>)</small>
                                            <?php endif; ?>
                                            <?php if ($question->correct_answer === 'false'): ?>
                                                <small class="ml-2">(<?= lang('Quizzes.correct') ?>)</small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        <?php elseif ($question->question_type === 'essay'): ?>
                            <div class="essay-review">
                                <div class="bg-light p-3 rounded mb-2">
                                    <strong><?= lang('Quizzes.your_answer') ?>:</strong><br>
                                    <?= nl2br(esc($question->user_answer)) ?>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> <?= lang('Quizzes.essay_manual_grading') ?>
                                </small>
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
                            <?php if ($can_retake): ?>
                                <a href="<?= base_url('quizzes/take/' . $quiz->id) ?>" class="btn btn-primary mb-2 mb-sm-0 mr-sm-2">
                                    <i class="icon-refresh"></i> <?= lang('Quizzes.retry_quiz') ?>
                                </a>
                            <?php endif; ?>
                            
                            <a href="<?= base_url('quizzes/attempts/' . $quiz->id) ?>" class="btn btn-info mb-2 mb-sm-0 mr-sm-2">
                                <i class="icon-clock"></i> <?= lang('Quizzes.view_attempts') ?>
                            </a>
                            
                            <a href="<?= base_url('quizzes') ?>" class="btn btn-secondary mb-2 mb-sm-0 mr-sm-2">
                                <i class="icon-list"></i> <?= lang('Quizzes.back_to_quizzes') ?>
                            </a>
                            
                            <?php if (isset($quiz->course_id)): ?>
                                <a href="<?= base_url('courses/course_view/' . $quiz->slug) ?>" class="btn btn-outline-primary">
                                <i class="icon-book"></i> <?= lang('Courses.back_to_course') ?>
                            </a>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (!$can_retake && $quiz->max_attempts): ?>
                            <div class="mt-3">
                                <small class="text-muted">
                                    <i class="icon-exclamation-triangle"></i> 
                                    <?= lang('Quizzes.max_attempts_reached') ?> (<?= $user_attempts ?>/<?= $quiz->max_attempts ?>)
                                </small>
                            </div>
                        <?php endif; ?>
                    </div>
            </div>
            
            <!-- Attempt Information -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0"><?= lang('Quizzes.attempt_information') ?></h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <th><?= lang('Quizzes.attempt_number') ?>:</th>
                                    <td><?= $attempt->attempt_number ?></td>
                                </tr>
                                <tr>
                                    <th><?= lang('Admin.submitted_at') ?>:</th>
                                    <td><?= date('Y-m-d H:i:s', strtotime($attempt->submitted_at)) ?></td>
                                </tr>
                                <tr>
                                    <th><?= lang('Quizzes.time_taken') ?>:</th>
                                    <td><?= gmdate('H:i:s', $attempt->completion_time_seconds) ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <th><?= lang('Quizzes.total_questions') ?>:</th>
                                    <td><?= count($questions_with_answers ?? []) ?></td>
                                </tr>
                                <tr>
                                    <th><?= lang('Quizzes.total_points') ?>:</th>
                                    <td><?= $total_points ?? 0 ?></td>
                                </tr>
                                <tr>
                                    <th><?= lang('Quizzes.points_earned') ?>:</th>
                                    <td><?= $points_earned ?? 0 ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<style>
.site-section {
    padding: 2rem 0;
}

.feature-1 {
    background: var(--bs-white);
    border-radius: var(--bs-border-radius-lg);
    box-shadow: var(--bs-box-shadow-sm);
    transition: all 0.3s ease;
}

.feature-1:hover {
    box-shadow: var(--bs-box-shadow);
    transform: translateY(-2px);
}

.feature-1-content {
    padding: 2rem;
}

.score-display {
    font-size: 3rem;
    font-weight: 700;
    color: var(--bs-success);
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.score-display.failed {
    color: var(--bs-warning);
}

.progress-bar {
    height: 8px;
    border-radius: 4px;
    transition: width 0.6s ease;
}

.btn-group-vertical.btn-group-sm-horizontal {
    flex-direction: column;
}

@media (min-width: 576px) {
    .btn-group-vertical.btn-group-sm-horizontal {
        flex-direction: row;
        justify-content: center;
    }
    
    .btn-group-vertical.btn-group-sm-horizontal .btn {
        margin-bottom: 0 !important;
        margin-right: 0.5rem;
    }
    
    .btn-group-vertical.btn-group-sm-horizontal .btn:last-child {
        margin-right: 0;
    }
}

.question-review {
    animation: fadeInUp 0.5s ease forwards;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.3s ease;
}

.question-review:nth-child(odd) {
    animation-delay: 0.1s;
}

.question-review:nth-child(even) {
    animation-delay: 0.2s;
}

.question-review:hover {
    background-color: #f8f9fa;
    border-radius: 0.5rem;
    padding: 1rem !important;
    margin: -0.5rem;
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.options-review .bg-success {
    animation: correctAnswer 0.5s ease-in-out;
}

.options-review .bg-danger {
    animation: wrongAnswer 0.5s ease-in-out;
}

@keyframes correctAnswer {
    0% { background-color: #f8f9fa; }
    50% { background-color: #d4edda; }
    100% { background-color: #28a745; }
}

@keyframes wrongAnswer {
    0% { background-color: #f8f9fa; }
    50% { background-color: #f8d7da; }
    100% { background-color: #dc3545; }
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: all 0.3s ease;
    border: none;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    transform: translateY(-5px);
}

.celebration {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 9999;
}

.confetti {
    position: absolute;
    width: 10px;
    height: 10px;
    background: var(--bs-primary);
    animation: confetti-fall 3s linear infinite;
}

@keyframes confetti-fall {
    0% {
        transform: translateY(-100vh) rotate(0deg);
        opacity: 1;
    }
    100% {
        transform: translateY(100vh) rotate(720deg);
        opacity: 0;
    }
}
</style>

<script>
$(document).ready(function() {
    // Animate score display
    animateScore();
    
    // Add confetti effect if passed
    <?php if ($attempt->score >= $quiz->passing_score): ?>
    showCelebration();
    <?php endif; ?>
    
    // Smooth scroll to question review
    $('a[href="#question-review"]').click(function(e) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: $('.card:has(.question-review)').offset().top - 100
        }, 800);
    });
});

function animateScore() {
    const scoreElement = $('.score-display h2');
    const finalScore = <?= $attempt->score ?>;
    let currentScore = 0;
    const increment = finalScore / 50; // Animate over 50 steps
    
    const animation = setInterval(function() {
        currentScore += increment;
        if (currentScore >= finalScore) {
            currentScore = finalScore;
            clearInterval(animation);
        }
        scoreElement.text(currentScore.toFixed(1) + '%');
    }, 20);
}

function showCelebration() {
    // Simple celebration effect
    setTimeout(function() {
        // You can add confetti library here
        console.log('Congratulations! 🎉');
    }, 1000);
}

// Print results functionality
function printResults() {
    window.print();
}

// Share results (if social sharing is needed)
function shareResults() {
    if (navigator.share) {
        navigator.share({
            title: '<?= esc($quiz->quiz_title) ?> - <?= lang('Quizzes.quiz_results') ?>',
            text: '<?= lang('Quizzes.i_scored') ?> <?= number_format($attempt->score, 1) ?>% <?= lang('Quizzes.on_quiz') ?> "<?= esc($quiz->quiz_title) ?>"',
            url: window.location.href
        });
    }
}
</script>
<?= $this->endSection() ?>