<?php
/**
 * Quizzes Site My Attempts View
 * 
 * This view displays the user's quiz attempts history with modern design.
 */
?>

<?= $this->extend('site_layout/template') ?>

<?= $this->section('content') ?>

<!-- Custom Styles for Quiz Attempts -->
<style>
.quiz-attempts-section {
    padding: 60px 0;
    background-color: #f8f9fa;
    min-height: 70vh;
}

.page-header {
    text-align: center;
    margin-bottom: 50px;
}

.page-header h1 {
    font-family: "Display Playfair", serif;
    font-size: 2.5rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 15px;
}

.page-header p {
    font-size: 1.1rem;
    color: #666;
    max-width: 600px;
    margin: 0 auto;
}

.attempts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
}

.attempt-card {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.attempt-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.attempt-card-header {
    padding: 20px 25px 15px;
    border-bottom: 1px solid #f0f0f0;
    position: relative;
}

.attempt-card-header h5 {
    font-family: "Display Playfair", serif;
    font-size: 1.3rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    line-height: 1.4;
}

.course-badge {
    display: inline-block;
    background: linear-gradient(135deg, #136ad5, #1573e8);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    margin-bottom: 10px;
}

.attempt-date {
    font-size: 0.9rem;
    color: #888;
    display: flex;
    align-items: center;
    gap: 5px;
}

.attempt-card-body {
    padding: 20px 25px;
}

.attempt-stats {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.score-display {
    text-align: center;
}

.score-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.1rem;
    margin: 0 auto 5px;
}

.score-circle.excellent {
    background: linear-gradient(135deg, #28a745, #34ce57);
    color: white;
}

.score-circle.good {
    background: linear-gradient(135deg, #17a2b8, #20c997);
    color: white;
}

.score-circle.average {
    background: linear-gradient(135deg, #ffc107, #ffca2c);
    color: #333;
}

.score-circle.poor {
    background: linear-gradient(135deg, #dc3545, #e74c3c);
    color: white;
}

.score-circle.incomplete {
    background: #f8f9fa;
    border: 2px dashed #dee2e6;
    color: #6c757d;
}

.score-label {
    font-size: 0.8rem;
    color: #666;
    font-weight: 500;
}

.status-badge {
    padding: 8px 16px;
    border-radius: 25px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-completed {
    background: linear-gradient(135deg, #28a745, #34ce57);
    color: white;
}

.status-in-progress {
    background: linear-gradient(135deg, #ffc107, #ffca2c);
    color: #333;
}

.status-abandoned {
    background: linear-gradient(135deg, #dc3545, #e74c3c);
    color: white;
}

.attempt-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-action {
    flex: 1;
    min-width: 120px;
    padding: 10px 20px;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-primary-custom {
    background: linear-gradient(135deg, #136ad5, #1573e8);
    color: white;
}

.btn-primary-custom:hover {
    background: linear-gradient(135deg, #1573e8, #136ad5);
    transform: translateY(-2px);
    color: white;
}

.btn-secondary-custom {
    background: linear-gradient(135deg, #00aeff, #14b4ff);
    color: white;
}

.btn-secondary-custom:hover {
    background: linear-gradient(135deg, #14b4ff, #00aeff);
    transform: translateY(-2px);
    color: white;
}

.btn-outline-custom {
    background: transparent;
    border: 2px solid #136ad5;
    color: #136ad5;
}

.btn-outline-custom:hover {
    background: #136ad5;
    color: white;
    transform: translateY(-2px);
}

.empty-state {
    text-align: center;
    padding: 80px 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.empty-state-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 30px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: #6c757d;
}

.empty-state h3 {
    font-family: "Display Playfair", serif;
    font-size: 1.8rem;
    color: #333;
    margin-bottom: 15px;
}

.empty-state p {
    font-size: 1.1rem;
    color: #666;
    margin-bottom: 30px;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

@media (max-width: 768px) {
    .attempts-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .page-header h1 {
        font-size: 2rem;
    }
    
    .attempt-stats {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .attempt-actions {
        flex-direction: column;
    }
    
    .btn-action {
        min-width: auto;
    }
}
</style>

<div class="quiz-attempts-section">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1><?= lang('Quizzes.my_quiz_attempts') ?></h1>
            <p><?= lang('Quizzes.view_quiz_history') ?></p>
        </div>

        <!-- Quiz Attempts Grid -->
        <?php if (!empty($attempts)): ?>
            <div class="attempts-grid">
                <?php foreach ($attempts as $attempt): ?>
                    <div class="attempt-card">
                        <div class="attempt-card-header">
                            <?php if ($attempt->course_title): ?>
                                <div class="course-badge">
                                    <i class="fas fa-book"></i> <?= esc($attempt->course_title) ?>
                                </div>
                            <?php endif; ?>
                            
                            <h5><?= esc($attempt->quiz_title) ?></h5>
                            
                            <div class="attempt-date">
                                <i class="fas fa-calendar-alt"></i>
                                <?= date('M j, Y \a\t g:i A', strtotime($attempt->created_at)) ?>
                            </div>
                        </div>
                        
                        <div class="attempt-card-body">
                            <div class="attempt-stats">
                                <div class="score-display">
                                    <?php
                                    $score = $attempt->score ?? 0;
                                    $scoreClass = 'incomplete';
                                    $status = $score > 0 ? 'completed' : 'in_progress';
                                    if ($status === 'completed') {
                                        if ($score >= 90) $scoreClass = 'excellent';
                                        elseif ($score >= 75) $scoreClass = 'good';
                                        elseif ($score >= 60) $scoreClass = 'average';
                                        else $scoreClass = 'poor';
                                    }
                                    ?>
                                    <div class="score-circle <?= $scoreClass ?>">
                                        <?php if ($status === 'completed'): ?>
                                            <?= number_format($score, 1) ?>%
                                        <?php else: ?>
                                            <i class="fas fa-minus"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="score-label">
                                        <?= $status === 'completed' ? lang('Quizzes.score') : lang('Quizzes.incomplete') ?>
                                    </div>
                                </div>
                                
                                <div class="status-display">
                                    <?php
                                    $statusClass = '';
                                    $statusText = '';
                                    $statusIcon = '';
                                    switch ($status) {
                                        case 'completed':
                                            $statusClass = 'status-completed';
                                            $statusText = $score >= ($attempt->passing_score ?? 70) ? lang('Quizzes.passed') : lang('Quizzes.failed');
                                            $statusIcon = 'fas fa-check-circle';
                                            break;
                                        case 'in_progress':
                                            $statusClass = 'status-in-progress';
                                            $statusText = lang('Quizzes.in_progress');
                                            $statusIcon = 'fas fa-clock';
                                            break;
                                        case 'abandoned':
                                            $statusClass = 'status-abandoned';
                                            $statusText = lang('Quizzes.abandoned');
                                            $statusIcon = 'fas fa-times-circle';
                                            break;
                                        default:
                                            $statusClass = 'status-in-progress';
                                            $statusText = ucfirst($status);
                                            $statusIcon = 'fas fa-question-circle';
                                    }
                                    ?>
                                    <div class="status-badge <?= $statusClass ?>">
                                        <i class="<?= $statusIcon ?>"></i> <?= $statusText ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="attempt-actions">
                                <?php if ($status === 'completed'): ?>
                                    <a href="<?= base_url('quizzes/results/' . $attempt->id) ?>" 
                                       class="btn btn-action btn-outline-custom">
                                        <i class="fas fa-eye"></i> <?= lang('Quizzes.view_results') ?>
                                    </a>
                                <?php elseif ($status === 'in_progress'): ?>
                                    <a href="<?= base_url('quizzes/continue/' . $attempt->id) ?>" 
                                       class="btn btn-action btn-primary-custom">
                                        <i class="fas fa-play"></i> <?= lang('Quizzes.continue') ?>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($status === 'completed'): ?>
                                    <a href="<?= base_url('quizzes/take/' . $attempt->quiz_id) ?>" 
                                       class="btn btn-action btn-secondary-custom">
                                        <i class="fas fa-redo"></i> <?= lang('Quizzes.retry') ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3><?= lang('Quizzes.no_quiz_attempts') ?></h3>
                <p><?= lang('Quizzes.no_quizzes_taken') ?></p>
                <a href="<?= base_url('quizzes') ?>" class="btn btn-action btn-primary-custom" style="max-width: 200px;">
                    <i class="fas fa-book"></i> <?= lang('Quizzes.browse_quizzes') ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
