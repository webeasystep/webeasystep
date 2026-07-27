<?php
/**
 * Quizzes Site Take Quiz View
 * Redesigned: centered layout, dark/light mode compatible
 */
?>

<?= $this->extend('site_layout/template') ?>

<?= $this->section('content') ?>

<style>
/* =============================================
   QUIZ TAKE - CSS VARIABLES (LIGHT MODE)
   ============================================= */
:root {
    --qt-primary:       #136ad5;
    --qt-primary-dark:  #0f5bb8;
    --qt-success:       #10b981;
    --qt-danger:        #ef4444;
    --qt-accent:        #f59e0b;

    --qt-bg-page:       #f0f4f8;
    --qt-bg-card:       #ffffff;
    --qt-bg-header:     linear-gradient(135deg, #136ad5 0%, #00aeff 100%);
    --qt-bg-option:     #f8fafc;
    --qt-bg-option-hover: rgba(19, 106, 213, 0.06);
    --qt-bg-option-selected: rgba(19, 106, 213, 0.1);

    --qt-text-primary:  #1a202c;
    --qt-text-secondary:#4a5568;
    --qt-text-muted:    #718096;
    --qt-text-white:    #ffffff;

    --qt-border:        #e2e8f0;
    --qt-border-primary:rgba(19, 106, 213, 0.4);

    --qt-shadow-card:   0 4px 24px rgba(0,0,0,0.08);
    --qt-shadow-hover:  0 8px 32px rgba(0,0,0,0.12);

    --qt-radius:        16px;
    --qt-radius-sm:     10px;
}

/* DARK MODE */
body.dark-mode {
    --qt-bg-page:       #0f172a;
    --qt-bg-card:       #1e293b;
    --qt-bg-header:     linear-gradient(135deg, #1a2540 0%, #0f1e35 100%);
    --qt-bg-option:     #162032;
    --qt-bg-option-hover: rgba(96, 165, 250, 0.08);
    --qt-bg-option-selected: rgba(96, 165, 250, 0.15);

    --qt-text-primary:  #f1f5f9;
    --qt-text-secondary:#94a3b8;
    --qt-text-muted:    #64748b;

    --qt-border:        rgba(255,255,255,0.08);
    --qt-border-primary:rgba(96, 165, 250, 0.4);

    --qt-shadow-card:   0 4px 24px rgba(0,0,0,0.35);
    --qt-shadow-hover:  0 8px 32px rgba(0,0,0,0.5);
}

/* =============================================
   PAGE WRAPPER
   ============================================= */
.quiz-take-page {
    background: var(--qt-bg-page);
    min-height: 100vh;
    padding: 2.5rem 1rem;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    direction: rtl;
}

.quiz-take-wrapper {
    width: 100%;
    max-width: 760px;
}

/* =============================================
   QUIZ CARD
   ============================================= */
.quiz-card {
    background: var(--qt-bg-card);
    border-radius: var(--qt-radius);
    box-shadow: var(--qt-shadow-card);
    overflow: hidden;
    transition: box-shadow 0.3s ease;
    margin-bottom: 1.25rem;
}

.quiz-card:hover {
    box-shadow: var(--qt-shadow-hover);
}

/* =============================================
   QUIZ HEADER
   ============================================= */
.quiz-header-card {
    background: var(--qt-bg-header);
    padding: 1.5rem 2rem;
    color: var(--qt-text-white);
    position: relative;
    overflow: hidden;
}

.quiz-header-card::before {
    content: '';
    position: absolute;
    top: -40%;
    right: -5%;
    width: 200px;
    height: 200px;
    background: rgba(255,255,255,0.06);
    border-radius: 50%;
    pointer-events: none;
}

.quiz-header-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    position: relative;
    z-index: 1;
}

.quiz-header-title {
    font-size: 1.4rem;
    font-weight: 800;
    margin: 0 0 0.25rem;
    line-height: 1.25;
    text-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.quiz-header-desc {
    font-size: 0.88rem;
    opacity: 0.8;
    margin: 0;
}

.quiz-timer-badge {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    background: rgba(255,255,255,0.18);
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 50px;
    padding: 0.45rem 1rem;
    font-size: 0.95rem;
    font-weight: 700;
    white-space: nowrap;
    backdrop-filter: blur(4px);
    flex-shrink: 0;
}

.quiz-header-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid rgba(255,255,255,0.15);
    position: relative;
    z-index: 1;
}

.quiz-question-counter {
    font-size: 0.88rem;
    opacity: 0.85;
}

.quiz-progress-bar-wrap {
    flex: 1;
    max-width: 200px;
    height: 6px;
    background: rgba(255,255,255,0.2);
    border-radius: 10px;
    overflow: hidden;
    margin: 0 1rem;
}

.quiz-progress-bar-fill {
    height: 100%;
    background: rgba(255,255,255,0.9);
    border-radius: 10px;
    transition: width 0.4s ease;
}

/* =============================================
   QUESTION CARD
   ============================================= */
.question-card-body {
    padding: 2rem;
}

.question-label-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.25rem;
}

.question-label {
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: var(--qt-primary);
}

.question-points-badge {
    background: rgba(19, 106, 213, 0.1);
    color: var(--qt-primary);
    border: 1px solid rgba(19, 106, 213, 0.2);
    border-radius: 50px;
    padding: 0.25rem 0.75rem;
    font-size: 0.78rem;
    font-weight: 700;
}

body.dark-mode .question-points-badge {
    background: rgba(96, 165, 250, 0.12);
    color: #60a5fa;
    border-color: rgba(96, 165, 250, 0.25);
}

.question-text {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--qt-text-primary);
    line-height: 1.8;
    margin-bottom: 1.75rem;
    opacity: 1 !important;
}

body:not(.dark-mode) .question-text {
    color: #1a202c;
}

/* =============================================
   OPTIONS
   ============================================= */
.quiz-option {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.25rem;
    border-radius: var(--qt-radius-sm);
    border: 2px solid var(--qt-border);
    background: var(--qt-bg-option);
    cursor: pointer;
    transition: all 0.2s ease;
    margin-bottom: 0.75rem;
    color: var(--qt-text-primary);
}

.quiz-option:hover {
    border-color: var(--qt-border-primary);
    background: var(--qt-bg-option-hover);
    transform: translateX(3px);
}

.quiz-option.selected {
    border-color: var(--qt-primary);
    background: var(--qt-bg-option-selected);
}

body.dark-mode .quiz-option.selected {
    border-color: #60a5fa;
}

.quiz-option input[type="radio"],
.quiz-option input[type="checkbox"] {
    display: none;
}

.option-indicator {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    border: 2px solid var(--qt-border);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all 0.2s ease;
    background: var(--qt-bg-card);
}

.quiz-option.selected .option-indicator {
    border-color: var(--qt-primary);
    background: var(--qt-primary);
}

body.dark-mode .quiz-option.selected .option-indicator {
    border-color: #60a5fa;
    background: #60a5fa;
}

.option-indicator::after {
    content: '';
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: white;
    opacity: 0;
    transition: opacity 0.2s;
}

.quiz-option.selected .option-indicator::after {
    opacity: 1;
}

.option-checkbox .option-indicator {
    border-radius: 6px;
}

.option-checkbox .option-indicator::after {
    content: '✓';
    font-size: 12px;
    font-weight: 700;
    border-radius: 0;
    width: auto;
    height: auto;
}

.option-text {
    font-size: 1rem;
    color: var(--qt-text-primary);
    font-weight: 500;
    line-height: 1.5;
    flex: 1;
}

body:not(.dark-mode) .option-text {
    color: #1a202c;
}

/* fill_in_blank input */
.quiz-fill-input {
    width: 100%;
    padding: 0.85rem 1.25rem;
    border: 2px solid var(--qt-border);
    border-radius: var(--qt-radius-sm);
    background: var(--qt-bg-option);
    color: var(--qt-text-primary);
    font-size: 1rem;
    font-weight: 500;
    transition: border-color 0.2s;
    outline: none;
    text-align: right;
}

.quiz-fill-input:focus {
    border-color: var(--qt-primary);
    box-shadow: 0 0 0 3px rgba(19,106,213,0.1);
}

body:not(.dark-mode) .quiz-fill-input {
    color: #1a202c;
    background: #f8fafc;
}

/* Essay textarea */
.quiz-essay-textarea {
    width: 100%;
    padding: 1rem 1.25rem;
    border: 2px solid var(--qt-border);
    border-radius: var(--qt-radius-sm);
    background: var(--qt-bg-option);
    color: var(--qt-text-primary);
    font-size: 0.97rem;
    line-height: 1.6;
    resize: vertical;
    transition: border-color 0.2s;
    outline: none;
}

.quiz-essay-textarea:focus {
    border-color: var(--qt-primary);
}

/* =============================================
   NAVIGATION
   ============================================= */
.quiz-nav-footer {
    padding: 1.25rem 2rem;
    border-top: 1px solid var(--qt-border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

.btn-quiz-nav {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.65rem 1.5rem;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 700;
    border: none;
    cursor: pointer;
    transition: all 0.25s ease;
}

.btn-quiz-prev {
    background: var(--qt-bg-option);
    color: var(--qt-text-secondary);
    border: 2px solid var(--qt-border);
}

.btn-quiz-prev:hover {
    background: var(--qt-bg-option-hover);
    border-color: var(--qt-border-primary);
    color: var(--qt-primary);
}

.btn-quiz-next {
    background: var(--qt-primary);
    color: white;
    box-shadow: 0 4px 14px rgba(19,106,213,0.3);
}

.btn-quiz-next:hover {
    background: var(--qt-primary-dark);
    transform: translateX(3px);
    box-shadow: 0 6px 20px rgba(19,106,213,0.4);
}

.btn-quiz-submit {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    box-shadow: 0 4px 14px rgba(16,185,129,0.3);
}

.btn-quiz-submit:hover {
    transform: translateX(3px);
    box-shadow: 0 6px 20px rgba(16,185,129,0.4);
}

/* =============================================
   QUESTION NAVIGATION DOTS
   ============================================= */
.quiz-nav-card {
    padding: 1.5rem 2rem;
}

.quiz-nav-label {
    font-size: 0.82rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: var(--qt-text-muted);
    margin-bottom: 1rem;
}

.quiz-nav-dots {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.quiz-nav-dot {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    border: 2px solid var(--qt-border);
    background: var(--qt-bg-option);
    color: var(--qt-text-secondary);
    font-size: 0.85rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.quiz-nav-dot:hover {
    border-color: var(--qt-primary);
    color: var(--qt-primary);
}

.quiz-nav-dot.active {
    background: var(--qt-primary);
    border-color: var(--qt-primary);
    color: white;
    box-shadow: 0 3px 10px rgba(19,106,213,0.35);
}

.quiz-nav-dot.answered {
    background: var(--qt-success);
    border-color: var(--qt-success);
    color: white;
}

/* =============================================
   MODAL
   ============================================= */
.quiz-modal .modal-content {
    background: var(--qt-bg-card);
    border: 1px solid var(--qt-border);
    border-radius: var(--qt-radius);
    color: var(--qt-text-primary);
}

.quiz-modal .modal-header {
    border-bottom: 1px solid var(--qt-border);
    padding: 1.25rem 1.5rem;
}

.quiz-modal .modal-title {
    font-weight: 700;
    color: var(--qt-text-primary);
}

.quiz-modal .modal-body {
    padding: 1.5rem;
    color: var(--qt-text-secondary);
}

.quiz-modal .modal-footer {
    border-top: 1px solid var(--qt-border);
    padding: 1rem 1.5rem;
}

.quiz-modal .close {
    color: var(--qt-text-muted);
}

.quiz-modal .alert-info {
    background: rgba(19,106,213,0.08);
    border: 1px solid rgba(19,106,213,0.2);
    color: var(--qt-text-primary);
    border-radius: var(--qt-radius-sm);
}

body.dark-mode .quiz-modal .alert-info {
    background: rgba(96,165,250,0.1);
    border-color: rgba(96,165,250,0.2);
}

/* =============================================
   ANIMATION
   ============================================= */
.question-container {
    animation: qFadeIn 0.3s ease;
}

@keyframes qFadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* Responsive */
@media (max-width: 576px) {
    .quiz-header-card { padding: 1.25rem 1.25rem; }
    .question-card-body { padding: 1.25rem; }
    .quiz-nav-footer { padding: 1rem 1.25rem; }
    .quiz-header-title { font-size: 1.15rem; }
    .quiz-progress-bar-wrap { display: none; }
}
</style>

<div class="quiz-take-page">
    <div class="quiz-take-wrapper">

        <!-- ===== QUIZ HEADER ===== -->
        <div class="quiz-card">
            <div class="quiz-header-card">
                <div class="quiz-header-top">
                    <div>
                        <h1 class="quiz-header-title"><?= esc($quiz->quiz_title) ?></h1>
                        <?php if ($quiz->quiz_desc): ?>
                            <p class="quiz-header-desc"><?= esc($quiz->quiz_desc) ?></p>
                        <?php endif; ?>
                    </div>
                    <?php if ($quiz->time_limit_minutes): ?>
                    <div class="quiz-timer-badge" id="quiz-timer">
                        <i class="fas fa-clock"></i>
                        <span id="timer-display"><?= $quiz->time_limit_minutes ?>:00</span>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="quiz-header-meta">
                    <span class="quiz-question-counter">
                        <?= lang('Quizzes.question') ?> <strong id="current-question">1</strong> <?= lang('Admin.of') ?> <?= count($questions) ?>
                    </span>
                    <div class="quiz-progress-bar-wrap">
                        <div class="quiz-progress-bar-fill" id="progress-fill" style="width: <?= (1/count($questions))*100 ?>%"></div>
                    </div>
                    <?php if (isset($attempt_count) && $attempt_count > 0): ?>
                    <small style="opacity:0.8;">محاولة <?= $attempt_count ?> / <?= $quiz->max_attempts ?></small>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ===== QUIZ FORM ===== -->
        <form id="quiz-form" action="<?= base_url('quizzes/submit-attempt/' . $attempt->id) ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="start_time" value="<?= time() ?>">
            <input type="hidden" name="completion_time" id="completion-time" value="0">

            <?php foreach ($questions as $index => $question): ?>
            <div class="quiz-card question-container" data-question="<?= $index + 1 ?>" <?= $index === 0 ? '' : 'style="display:none;"' ?>>
                <div class="question-card-body">
                    <!-- Label Row -->
                    <div class="question-label-row">
                        <span class="question-label"><?= lang('Quizzes.question') ?> <?= $index + 1 ?></span>
                        <span class="question-points-badge"><?= $question['points'] ?? 1 ?> <?= lang('Admin.points') ?></span>
                    </div>

                    <!-- Question Text -->
                    <div class="question-text">
                        <?= nl2br(esc($question['question_text'])) ?>
                    </div>

                    <!-- Options -->
                    <div class="question-options">
                        <?php if ($question['question_type'] === 'single_choice'): ?>
                            <?php $options = is_array($question['options']) ? $question['options'] : json_decode($question['options'], true); ?>
                            <?php if ($options): ?>
                                <?php foreach ($options as $optIndex => $option): ?>
                                <label class="quiz-option" for="q<?= $index ?>_opt<?= $optIndex ?>">
                                    <input type="radio" name="answers[<?= $index ?>]" id="q<?= $index ?>_opt<?= $optIndex ?>" value="<?= $optIndex ?>">
                                    <span class="option-indicator"></span>
                                    <span class="option-text"><?= esc($option) ?></span>
                                </label>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        <?php elseif ($question['question_type'] === 'multiple_choice'): ?>
                            <?php $options = is_array($question['options']) ? $question['options'] : json_decode($question['options'], true); ?>
                            <?php if ($options): ?>
                                <?php foreach ($options as $optIndex => $option): ?>
                                <label class="quiz-option option-checkbox" for="q<?= $index ?>_opt<?= $optIndex ?>">
                                    <input type="checkbox" name="answers[<?= $index ?>][]" id="q<?= $index ?>_opt<?= $optIndex ?>" value="<?= $optIndex ?>">
                                    <span class="option-indicator"></span>
                                    <span class="option-text"><?= esc($option) ?></span>
                                </label>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        <?php elseif ($question['question_type'] === 'true_false'): ?>
                            <label class="quiz-option" for="q<?= $index ?>_true">
                                <input type="radio" name="answers[<?= $index ?>]" id="q<?= $index ?>_true" value="true">
                                <span class="option-indicator"></span>
                                <span class="option-text"><i class="fas fa-check text-success mr-2"></i><?= lang('Admin.true') ?></span>
                            </label>
                            <label class="quiz-option" for="q<?= $index ?>_false">
                                <input type="radio" name="answers[<?= $index ?>]" id="q<?= $index ?>_false" value="false">
                                <span class="option-indicator"></span>
                                <span class="option-text"><i class="fas fa-times text-danger mr-2"></i><?= lang('Admin.false') ?></span>
                            </label>

                        <?php elseif ($question['question_type'] === 'fill_in_blank'): ?>
                            <div style="margin-bottom:0.5rem;">
                                <label for="q<?= $index ?>_fill" style="font-size:0.9rem;color:var(--qt-text-muted);margin-bottom:0.5rem;display:block;">
                                    <?= lang('Quizzes.type_your_answer') ?? 'اكتب إجابتك' ?>
                                </label>
                                <input type="text"
                                       class="quiz-fill-input"
                                       name="answers[<?= $index ?>]"
                                       id="q<?= $index ?>_fill"
                                       autocomplete="off"
                                       placeholder="...">
                            </div>

                        <?php elseif ($question['question_type'] === 'essay'): ?>
                            <textarea class="quiz-essay-textarea"
                                      name="answers[<?= $index ?>]"
                                      id="q<?= $index ?>_essay"
                                      rows="6"
                                      style="text-align:right;"
                                      placeholder="<?= lang('Quizzes.enter_your_answer') ?>"></textarea>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Navigation Footer -->
                <div class="quiz-nav-footer">
                    <div>
                        <?php if ($index > 0): ?>
                        <button type="button" class="btn-quiz-nav btn-quiz-prev" onclick="previousQuestion()">
                            <i class="fas fa-arrow-right"></i> <?= lang('Quizzes.previous_question') ?>
                        </button>
                        <?php endif; ?>
                    </div>
                    <div>
                        <?php if ($index < count($questions) - 1): ?>
                        <button type="button" class="btn-quiz-nav btn-quiz-next" onclick="nextQuestion()">
                            <?= lang('Quizzes.next_question') ?> <i class="fas fa-arrow-left"></i>
                        </button>
                        <?php else: ?>
                        <button type="button" class="btn-quiz-nav btn-quiz-submit" onclick="submitQuiz()">
                            <i class="fas fa-paper-plane mr-1"></i> <?= lang('Quizzes.submit_quiz') ?>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </form>

        <!-- ===== QUESTION NAVIGATION DOTS ===== -->
        <div class="quiz-card">
            <div class="quiz-nav-card">
                <div class="quiz-nav-label"><?= lang('Quizzes.question_navigation') ?></div>
                <div class="quiz-nav-dots">
                    <?php foreach ($questions as $index => $question): ?>
                    <button type="button" class="quiz-nav-dot <?= $index === 0 ? 'active' : '' ?>"
                            data-question="<?= $index + 1 ?>"
                            onclick="goToQuestion(<?= $index + 1 ?>)">
                        <?= $index + 1 ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div><!-- .quiz-take-wrapper -->
</div><!-- .quiz-take-page -->

<!-- Submit Confirmation Modal -->
<div class="modal fade quiz-modal" id="submitModal" tabindex="-1" role="dialog" dir="rtl">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="direction:rtl;text-align:right;">
            <div class="modal-header" style="flex-direction:row-reverse;">
                <h5 class="modal-title" style="font-weight:700;">
                    <i class="fas fa-paper-plane ml-2 text-primary"></i><?= lang('Quizzes.submit_quiz') ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" style="margin:0;margin-left:auto;"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p style="font-size:1.05rem;font-weight:600;color:#1a202c;"><?= lang('Quizzes.submit_confirmation') ?></p>
                <div class="alert alert-info" style="border-radius:10px;">
                    <strong><?= lang('Quizzes.answered_questions') ?>:</strong> <span id="answered-count">0</span> / <?= count($questions) ?><br>
                    <strong><?= lang('Quizzes.unanswered_questions') ?>:</strong> <span id="unanswered-count"><?= count($questions) ?></span>
                </div>
            </div>
            <div class="modal-footer" style="justify-content:flex-start;">
                <button type="button" class="btn btn-success" onclick="confirmSubmit()">
                    <i class="fas fa-check ml-1"></i> <?= lang('Quizzes.submit_quiz') ?>
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang('Admin.cancel') ?></button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
let currentQuestion = 1;
let totalQuestions = <?= count($questions) ?>;
let startTime = Date.now();

<?php if ($quiz->time_limit_minutes): ?>
// Timer
let timeLeft = <?= $quiz->time_limit_minutes * 60 ?>;
const timerInterval = setInterval(function() {
    timeLeft--;
    const m = Math.floor(timeLeft / 60);
    const s = timeLeft % 60;
    document.getElementById('timer-display').textContent = m + ':' + (s < 10 ? '0' : '') + s;
    if (timeLeft <= 60) {
        document.getElementById('quiz-timer').style.background = 'rgba(239,68,68,0.3)';
        document.getElementById('quiz-timer').style.borderColor = 'rgba(239,68,68,0.6)';
    }
    if (timeLeft <= 0) {
        clearInterval(timerInterval);
        confirmSubmit();
    }
}, 1000);
<?php endif; ?>

$(document).ready(function() {
    updateNavigation();

    // Option selection styling
    $(document).on('change', '.quiz-option input', function() {
        const container = $(this).closest('.question-container');
        if ($(this).attr('type') === 'radio') {
            container.find('.quiz-option').removeClass('selected');
            $(this).closest('.quiz-option').addClass('selected');
        } else {
            $(this).closest('.quiz-option').toggleClass('selected', $(this).is(':checked'));
        }
        updateNavigation();
        autoSaveAnswers();
    });

    // Click on label
    $(document).on('click', '.quiz-option', function() {
        const input = $(this).find('input');
        if (input.attr('type') === 'radio') {
            $(this).closest('.question-container').find('.quiz-option').removeClass('selected');
            $(this).addClass('selected');
        }
    });

    window.addEventListener('beforeunload', function(e) {
        e.preventDefault();
        e.returnValue = '<?= lang('Quizzes.leave_confirmation') ?>';
    });
});

function nextQuestion() {
    if (currentQuestion < totalQuestions) showQuestion(currentQuestion + 1);
}

function previousQuestion() {
    if (currentQuestion > 1) showQuestion(currentQuestion - 1);
}

function goToQuestion(n) { showQuestion(n); }

function showQuestion(n) {
    $('.question-container').hide();
    $(`.question-container[data-question="${n}"]`).show();
    currentQuestion = n;
    $('#current-question').text(n);
    // Update progress bar
    const pct = (n / totalQuestions) * 100;
    $('#progress-fill').css('width', pct + '%');
    updateNavigation();
}

function updateNavigation() {
    $('.quiz-nav-dot').removeClass('active answered');
    $(`.quiz-nav-dot[data-question="${currentQuestion}"]`).addClass('active');

    $('.question-container').each(function() {
        const qNum = $(this).data('question');
        const hasAnswer = $(this).find('input:checked').length > 0 ||
            $(this).find('textarea').filter(function() { return $(this).val().trim() !== ''; }).length > 0;
        if (hasAnswer && qNum !== currentQuestion) {
            $(`.quiz-nav-dot[data-question="${qNum}"]`).removeClass('active').addClass('answered');
        }
    });
}

function submitQuiz() {
    let answered = 0;
    $('.question-container').each(function() {
        const has = $(this).find('input:checked').length > 0 ||
            $(this).find('textarea').filter(function() { return $(this).val().trim() !== ''; }).length > 0;
        if (has) answered++;
    });
    $('#answered-count').text(answered);
    $('#unanswered-count').text(totalQuestions - answered);
    $('#submitModal').modal('show');
}

function confirmSubmit() {
    const t = Math.floor((Date.now() - startTime) / 1000);
    $('#completion-time').val(t);
    window.removeEventListener('beforeunload', function() {});
    $('#quiz-form').submit();
}

function autoSaveAnswers() {
    localStorage.setItem('quiz_<?= $quiz->id ?>_answers', $('#quiz-form').serialize());
}
</script>
<?= $this->endSection() ?>