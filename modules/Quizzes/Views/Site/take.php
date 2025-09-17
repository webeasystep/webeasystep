<?php
/**
 * Quizzes Site Take Quiz View
 * 
 * This view displays the quiz-taking interface for students.
 */
?>

<?= $this->extend('site_layout/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Quiz Header -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0"><?= esc($quiz->quiz_title) ?></h4>
                            <?php if ($quiz->quiz_description): ?>
                                <small><?= esc($quiz->quiz_description) ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 text-md-right">
                            <div class="quiz-timer">
                                <i class="fas fa-clock"></i>
                                <span id="timer-display"><?= sprintf('%02d:%02d', $quiz->time_limit_minutes, 0) ?></span>
                            </div>
                            <div class="mt-1">
                                <small><?= lang('Quizzes.question') ?> <span id="current-question">1</span> <?= lang('Admin.of') ?> <?= count($questions) ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quiz Form -->
            <form id="quiz-form" action="<?= base_url('quizzes/submit/' . $quiz->id) ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="start_time" value="<?= time() ?>">
                <input type="hidden" name="completion_time" id="completion-time" value="0">
                
                <?php foreach ($questions as $index => $question): ?>
                <div class="question-container" data-question="<?= $index + 1 ?>" style="<?= $index === 0 ? '' : 'display: none;' ?>">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h5 class="mb-0">
                                        <?= lang('Quizzes.question') ?> <?= $index + 1 ?>
                                        <span class="badge badge-info ml-2"><?= $question->points ?> <?= lang('Admin.points') ?></span>
                                    </h5>
                                </div>
                                <div class="col-md-4 text-md-right">
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: <?= (($index + 1) / count($questions)) * 100 ?>%"
                                             aria-valuenow="<?= $index + 1 ?>" 
                                             aria-valuemin="0" 
                                             aria-valuemax="<?= count($questions) ?>"></div>
                                    </div>
                                    <small class="text-muted"><?= number_format((($index + 1) / count($questions)) * 100, 1) ?>% <?= lang('Admin.complete') ?></small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="question-text mb-4">
                                <p class="lead"><?= nl2br(esc($question->question_text)) ?></p>
                            </div>
                            
                            <div class="question-options">
                                <?php if ($question->question_type === 'single_choice'): ?>
                                    <?php $options = json_decode($question->options, true); ?>
                                    <?php if ($options): ?>
                                        <?php foreach ($options as $optIndex => $option): ?>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" 
                                                   name="answers[<?= $question->id ?>]" 
                                                   id="q<?= $question->id ?>_opt<?= $optIndex ?>" 
                                                   value="<?= $optIndex ?>">
                                            <label class="form-check-label" for="q<?= $question->id ?>_opt<?= $optIndex ?>">
                                                <?= esc($option) ?>
                                            </label>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    
                                <?php elseif ($question->question_type === 'multiple_choice'): ?>
                                    <?php $options = json_decode($question->options, true); ?>
                                    <?php if ($options): ?>
                                        <?php foreach ($options as $optIndex => $option): ?>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="answers[<?= $question->id ?>][]" 
                                                   id="q<?= $question->id ?>_opt<?= $optIndex ?>" 
                                                   value="<?= $optIndex ?>">
                                            <label class="form-check-label" for="q<?= $question->id ?>_opt<?= $optIndex ?>">
                                                <?= esc($option) ?>
                                            </label>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    
                                <?php elseif ($question->question_type === 'true_false'): ?>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" 
                                               name="answers[<?= $question->id ?>]" 
                                               id="q<?= $question->id ?>_true" 
                                               value="true">
                                        <label class="form-check-label" for="q<?= $question->id ?>_true">
                                            <i class="fas fa-check text-success"></i> <?= lang('Admin.true') ?>
                                        </label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" 
                                               name="answers[<?= $question->id ?>]" 
                                               id="q<?= $question->id ?>_false" 
                                               value="false">
                                        <label class="form-check-label" for="q<?= $question->id ?>_false">
                                            <i class="fas fa-times text-danger"></i> <?= lang('Admin.false') ?>
                                        </label>
                                    </div>
                                    
                                <?php elseif ($question->question_type === 'essay'): ?>
                                    <div class="form-group">
                                        <textarea class="form-control" 
                                                  name="answers[<?= $question->id ?>]" 
                                                  id="q<?= $question->id ?>_essay" 
                                                  rows="6" 
                                                  placeholder="<?= lang('Quizzes.enter_your_answer') ?>"></textarea>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-6">
                                    <?php if ($index > 0): ?>
                                        <button type="button" class="btn btn-secondary" onclick="previousQuestion()">
                                            <i class="fas fa-arrow-left"></i> <?= lang('Quizzes.previous_question') ?>
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <?php if ($index < count($questions) - 1): ?>
                                        <button type="button" class="btn btn-primary" onclick="nextQuestion()">
                                            <?= lang('Quizzes.next_question') ?> <i class="fas fa-arrow-right"></i>
                                        </button>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-success" onclick="submitQuiz()">
                                            <i class="fas fa-check"></i> <?= lang('Quizzes.submit_quiz') ?>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </form>
            
            <!-- Question Navigation -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title"><?= lang('Quizzes.question_navigation') ?></h6>
                    <div class="question-nav">
                        <?php foreach ($questions as $index => $question): ?>
                        <button type="button" class="btn btn-outline-secondary btn-sm mr-2 mb-2 question-nav-btn" 
                                data-question="<?= $index + 1 ?>" onclick="goToQuestion(<?= $index + 1 ?>)">
                            <?= $index + 1 ?>
                        </button>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> 
                            <?= lang('Quizzes.navigation_help') ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Submit Confirmation Modal -->
<div class="modal fade" id="submitModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= lang('Quizzes.submit_quiz') ?></h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?= lang('Quizzes.submit_confirmation') ?></p>
                <div class="alert alert-info">
                    <strong><?= lang('Quizzes.answered_questions') ?>:</strong> <span id="answered-count">0</span> / <?= count($questions) ?><br>
                    <strong><?= lang('Quizzes.unanswered_questions') ?>:</strong> <span id="unanswered-count"><?= count($questions) ?></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <?= lang('Admin.cancel') ?>
                </button>
                <button type="button" class="btn btn-success" onclick="confirmSubmit()">
                    <i class="fas fa-check"></i> <?= lang('Quizzes.submit_quiz') ?>
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<style>
.quiz-timer {
    font-size: 1.2em;
    font-weight: bold;
}

.question-container {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateX(20px); }
    to { opacity: 1; transform: translateX(0); }
}

.question-nav-btn.active {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

.question-nav-btn.answered {
    background-color: #28a745;
    color: white;
    border-color: #28a745;
}

.form-check-label {
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 0.25rem;
    transition: background-color 0.2s;
}

.form-check-label:hover {
    background-color: #f8f9fa;
}

.form-check-input:checked + .form-check-label {
    background-color: #e3f2fd;
    font-weight: 500;
}
</style>

<script>
let currentQuestion = 1;
let totalQuestions = <?= count($questions) ?>;
let timeLimit = <?= $quiz->time_limit_minutes * 60 ?>; // Convert to seconds
let timeRemaining = timeLimit;
let timerInterval;
let startTime = Date.now();

$(document).ready(function() {
    // Start timer
    startTimer();
    
    // Update navigation
    updateNavigation();
    
    // Auto-save answers
    $('input, textarea').on('change', function() {
        updateNavigation();
        autoSaveAnswers();
    });
    
    // Prevent accidental page leave
    window.addEventListener('beforeunload', function(e) {
        e.preventDefault();
        e.returnValue = '<?= lang('Quizzes.leave_confirmation') ?>';
    });
    
    // Update current question indicator
    updateCurrentQuestion();
});

function startTimer() {
    timerInterval = setInterval(function() {
        timeRemaining--;
        
        if (timeRemaining <= 0) {
            clearInterval(timerInterval);
            autoSubmitQuiz();
            return;
        }
        
        updateTimerDisplay();
        
        // Warning when 5 minutes left
        if (timeRemaining === 300) {
            showTimeWarning();
        }
    }, 1000);
}

function updateTimerDisplay() {
    const minutes = Math.floor(timeRemaining / 60);
    const seconds = timeRemaining % 60;
    const display = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
    
    $('#timer-display').text(display);
    
    // Change color when time is running low
    if (timeRemaining <= 300) { // 5 minutes
        $('#timer-display').addClass('text-warning');
    }
    if (timeRemaining <= 60) { // 1 minute
        $('#timer-display').removeClass('text-warning').addClass('text-danger');
    }
}

function showTimeWarning() {
    if (confirm('<?= lang('Quizzes.time_warning') ?>')) {
        // User acknowledged the warning
    }
}

function nextQuestion() {
    if (currentQuestion < totalQuestions) {
        showQuestion(currentQuestion + 1);
    }
}

function previousQuestion() {
    if (currentQuestion > 1) {
        showQuestion(currentQuestion - 1);
    }
}

function goToQuestion(questionNumber) {
    showQuestion(questionNumber);
}

function showQuestion(questionNumber) {
    // Hide current question
    $('.question-container').hide();
    
    // Show target question
    $(`.question-container[data-question="${questionNumber}"]`).show();
    
    // Update current question
    currentQuestion = questionNumber;
    updateCurrentQuestion();
    updateNavigation();
}

function updateCurrentQuestion() {
    $('#current-question').text(currentQuestion);
}

function updateNavigation() {
    // Update navigation buttons
    $('.question-nav-btn').removeClass('active answered');
    
    // Mark current question as active
    $(`.question-nav-btn[data-question="${currentQuestion}"]`).addClass('active');
    
    // Mark answered questions
    $('.question-container').each(function() {
        const questionNum = $(this).data('question');
        const hasAnswer = $(this).find('input:checked, textarea').filter(function() {
            return $(this).val().trim() !== '';
        }).length > 0;
        
        if (hasAnswer && questionNum !== currentQuestion) {
            $(`.question-nav-btn[data-question="${questionNum}"]`).addClass('answered');
        }
    });
}

function submitQuiz() {
    // Count answered questions
    let answeredCount = 0;
    $('.question-container').each(function() {
        const hasAnswer = $(this).find('input:checked, textarea').filter(function() {
            return $(this).val().trim() !== '';
        }).length > 0;
        
        if (hasAnswer) {
            answeredCount++;
        }
    });
    
    $('#answered-count').text(answeredCount);
    $('#unanswered-count').text(totalQuestions - answeredCount);
    
    $('#submitModal').modal('show');
}

function confirmSubmit() {
    // Calculate completion time
    const completionTime = Math.floor((Date.now() - startTime) / 1000);
    $('#completion-time').val(completionTime);
    
    // Clear timer
    clearInterval(timerInterval);
    
    // Remove beforeunload listener
    window.removeEventListener('beforeunload', function() {});
    
    // Submit form
    $('#quiz-form').submit();
}

function autoSubmitQuiz() {
    alert('<?= lang('Quizzes.time_up_auto_submit') ?>');
    
    // Calculate completion time
    const completionTime = timeLimit;
    $('#completion-time').val(completionTime);
    
    // Remove beforeunload listener
    window.removeEventListener('beforeunload', function() {});
    
    // Submit form
    $('#quiz-form').submit();
}

function autoSaveAnswers() {
    // Auto-save functionality (optional)
    const formData = $('#quiz-form').serialize();
    
    // Save to localStorage as backup
    localStorage.setItem('quiz_<?= $quiz->id ?>_answers', formData);
}

// Load saved answers on page load
$(document).ready(function() {
    const savedAnswers = localStorage.getItem('quiz_<?= $quiz->id ?>_answers');
    if (savedAnswers) {
        // Restore answers if needed
    }
});
</script>
<?= $this->endSection() ?>