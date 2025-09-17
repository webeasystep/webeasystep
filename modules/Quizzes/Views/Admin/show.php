<?php
/**
 * Quizzes Admin Show View
 *
 * This view displays detailed information about a specific quiz.
 */
?>

<?= $this->extend('admin_layout/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= lang('Quizzes.quiz_details') ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dt_admin') ?>"><?= lang('Admin.dashboard') ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('dt_admin/quizzes') ?>"><?= lang('Quizzes.quizzes') ?></a></li>
                        <li class="breadcrumb-item active"><?= lang('Admin.view') ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <!-- Quiz Information -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= esc($quiz->quiz_title) ?></h3>
                            <div class="card-tools">
                                <a href="<?= base_url('dt_admin/quizzes/edit/' . $quiz->id) ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> <?= lang('Admin.edit') ?>
                                </a>
                                <a href="<?= base_url('dt_admin/quizzes') ?>" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> <?= lang('Admin.back') ?>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%"><?= lang('Quizzes.quiz_id') ?>:</th>
                                            <td><?= esc($quiz->id) ?></td>
                                        </tr>
                                        <tr>
                                            <th><?= lang('Courses.course') ?>:</th>
                                            <td>
                                                <?php if (isset($quiz->course_title)): ?>
                                                    <a href="<?= base_url('dt_admin/courses/view/' . $quiz->course_id) ?>">
                                                        <?= esc($quiz->course_title) ?>
                                                    </a>
                                                <?php else: ?>
                                                    <?= lang('Admin.not_assigned') ?>
                                                <?php endif; ?>
                                            </td>
                                        </tr>


                                        <tr>
                                            <th><?= lang('Admin.status') ?>:</th>
                                            <td>
                                                <span class="badge badge-<?= $quiz->active ? 'success' : 'secondary' ?>">
                                                    <?= $quiz->active ? lang('Admin.active') : lang('Admin.inactive') ?>
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%"><?= lang('Quizzes.time_limit') ?>:</th>
                                            <td><?= $quiz->time_limit_minutes ?> <?= lang('Admin.minutes') ?></td>
                                        </tr>
                                        <tr>
                                            <th><?= lang('Quizzes.passing_score') ?>:</th>
                                            <td><?= number_format($quiz->passing_score, 1) ?>%</td>
                                        </tr>
                                        <tr>
                                            <th><?= lang('Quizzes.max_attempts') ?>:</th>
                                            <td><?= $quiz->max_attempts ?: lang('Admin.unlimited') ?></td>
                                        </tr>
                                        <tr>
                                            <th><?= lang('Quizzes.shuffle_questions') ?>:</th>
                                            <td>
                                                <i class="fas fa-<?= $quiz->shuffle_questions ? 'check text-success' : 'times text-danger' ?>"></i>
                                                <?= $quiz->shuffle_questions ? lang('Admin.yes') : lang('Admin.no') ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?= lang('Quizzes.show_results_immediately') ?>:</th>
                                            <td>
                                                <i class="fas fa-<?= $quiz->show_results_immediately ? 'check text-success' : 'times text-danger' ?>"></i>
                                                <?= $quiz->show_results_immediately ? lang('Admin.yes') : lang('Admin.no') ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <?php if ($quiz->quiz_description): ?>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6><?= lang('Quizzes.quiz_description') ?>:</h6>
                                    <div class="bg-light p-3 rounded">
                                        <?= nl2br(esc($quiz->quiz_description)) ?>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Quiz Questions -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= lang('Quizzes.quiz_questions') ?> (<?= count($questions ?? []) ?>)</h3>
                            <div class="card-tools">
                                <a href="<?= base_url('dt_admin/quizzes/edit/' . $quiz->id) ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> <?= lang('Quizzes.add_question') ?>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (isset($questions) && !empty($questions)): ?>
                                <?php foreach ($questions as $index => $question): ?>
                                <div class="question-item border-left border-primary pl-3 mb-4">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <h6 class="text-primary"><?= lang('Quizzes.question') ?> <?= $index + 1 ?></h6>
                                        </div>
                                        <div class="col-md-2 text-right">
                                            <span class="badge badge-info"><?= $question->points ?> <?= lang('Admin.points') ?></span>
                                        </div>
                                    </div>

                                    <p class="mb-2"><strong><?= esc($question->question_text) ?></strong></p>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <small class="text-muted">
                                                <?= lang('Quizzes.question_type') ?>:
                                                <span class="badge badge-secondary"><?= lang('Quizzes.' . $question->question_type) ?></span>
                                            </small>
                                        </div>
                                    </div>

                                    <?php if ($question->question_type === 'single_choice' || $question->question_type === 'multiple_choice'): ?>
                                        <?php $options = json_decode($question->options, true); ?>
                                        <?php $correctAnswers = json_decode($question->correct_answer, true); ?>
                                        <?php if ($options): ?>
                                            <div class="mt-2">
                                                <strong><?= lang('Quizzes.options') ?>:</strong>
                                                <ul class="list-unstyled ml-3">
                                                    <?php foreach ($options as $optIndex => $option): ?>
                                                        <li class="<?= in_array($optIndex, (array)$correctAnswers) ? 'text-success font-weight-bold' : '' ?>">
                                                            <?php if (in_array($optIndex, (array)$correctAnswers)): ?>
                                                                <i class="fas fa-check-circle text-success"></i>
                                                            <?php else: ?>
                                                                <i class="far fa-circle text-muted"></i>
                                                            <?php endif; ?>
                                                            <?= esc($option) ?>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    <?php elseif ($question->question_type === 'true_false'): ?>
                                        <div class="mt-2">
                                            <strong><?= lang('Quizzes.correct_answer') ?>:</strong>
                                            <span class="badge badge-<?= $question->correct_answer === 'true' ? 'success' : 'danger' ?>">
                                                <?= $question->correct_answer === 'true' ? lang('Admin.true') : lang('Admin.false') ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted"><?= lang('Quizzes.no_questions') ?></h5>
                                    <p class="text-muted"><?= lang('Quizzes.no_questions_desc') ?></p>
                                    <a href="<?= base_url('dt_admin/quizzes/edit/' . $quiz->id) ?>" class="btn btn-primary">
                                        <?= lang('Quizzes.add_questions') ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Quiz Statistics -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= lang('Quizzes.quiz_statistics') ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6 text-center">
                                    <div class="border-right">
                                        <h4 class="text-primary"><?= $quiz_stats['total_attempts'] ?? 0 ?></h4>
                                        <small class="text-muted"><?= lang('Quizzes.total_attempts') ?></small>
                                    </div>
                                </div>
                                <div class="col-6 text-center">
                                    <h4 class="text-success"><?= number_format($quiz_stats['average_score'] ?? 0, 1) ?>%</h4>
                                    <small class="text-muted"><?= lang('Quizzes.average_score') ?></small>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6 text-center">
                                    <div class="border-right">
                                        <h4 class="text-warning"><?= number_format($quiz_stats['pass_rate'] ?? 0, 1) ?>%</h4>
                                        <small class="text-muted"><?= lang('Quizzes.pass_rate') ?></small>
                                    </div>
                                </div>
                                <div class="col-6 text-center">
                                    <h4 class="text-info"><?= $quiz_stats['unique_students'] ?? 0 ?></h4>
                                    <small class="text-muted"><?= lang('Admin.students') ?></small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Attempts -->
                    <?php if (isset($recent_attempts) && !empty($recent_attempts)): ?>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= lang('Quizzes.recent_attempts') ?></h3>
                            <div class="card-tools">
                                <a href="<?= base_url('dt_admin/quizzes/attempts/' . $quiz->id) ?>" class="btn btn-sm btn-primary">
                                    <?= lang('Admin.view_all') ?>
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                <?php foreach ($recent_attempts as $attempt): ?>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">
                                            <?= esc($attempt->username ?? 'Unknown User') ?>
                                        </h6>
                                        <small><?= date('M d, H:i', strtotime($attempt->submitted_at)) ?></small>
                                    </div>
                                    <div class="d-flex w-100 justify-content-between">
                                        <span class="badge badge-<?= $attempt->score >= $quiz->passing_score ? 'success' : 'danger' ?>">
                                            <?= number_format($attempt->score, 1) ?>%
                                        </span>
                                        <small class="text-muted">
                                            <?= gmdate('H:i:s', $attempt->completion_time_seconds ?? 0) ?>
                                        </small>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= lang('Admin.actions') ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="btn-group-vertical w-100">
                                <a href="<?= base_url('dt_admin/quizzes/edit/' . $quiz->id) ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> <?= lang('Admin.edit') ?>
                                </a>
                                <a href="<?= base_url('dt_admin/quizzes/attempts/' . $quiz->id) ?>" class="btn btn-info btn-sm">
                                    <i class="fas fa-list"></i> <?= lang('Quizzes.view_attempts') ?>
                                </a>
                                <a href="<?= base_url('dt_admin/quizzes/export/' . $quiz->id) ?>" class="btn btn-success btn-sm">
                                    <i class="fas fa-download"></i> <?= lang('Quizzes.export_quiz') ?>
                                </a>
                                <button type="button" class="btn btn-<?= $quiz->active ? 'warning' : 'success' ?> btn-sm"
                                        onclick="toggleQuizStatus(<?= $quiz->id ?>, <?= $quiz->active ?>)">
                                    <i class="fas fa-<?= $quiz->active ? 'pause' : 'play' ?>"></i>
                                    <?= $quiz->active ? lang('Admin.deactivate') : lang('Admin.activate') ?>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteQuiz(<?= $quiz->id ?>)">
                                    <i class="fas fa-trash"></i> <?= lang('Admin.delete') ?>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Quiz Info -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= lang('Admin.information') ?></h3>
                        </div>
                        <div class="card-body">
                            <small class="text-muted">
                                <strong><?= lang('Admin.created_at') ?>:</strong><br>
                                <?= date('Y-m-d H:i:s', strtotime($quiz->created_at)) ?>
                            </small><br>
                            <small class="text-muted">
                                <strong><?= lang('Admin.updated_at') ?>:</strong><br>
                                <?= date('Y-m-d H:i:s', strtotime($quiz->updated_at)) ?>
                            </small>
                            <?php if (isset($quiz->created_by_name)): ?>
                            <br><small class="text-muted">
                                <strong><?= lang('Admin.created_by') ?>:</strong><br>
                                <?= esc($quiz->created_by_name) ?>
                            </small>
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
function toggleQuizStatus(quizId, currentStatus) {
    const newStatus = currentStatus === 1 ? 0 : 1;
    const action = newStatus === 1 ? '<?= lang('Admin.activate') ?>' : '<?= lang('Admin.deactivate') ?>';

    if (confirm('<?= lang('Admin.confirm_action') ?>')) {
        $.post('<?= base_url('dt_admin/quizzes/toggle-status') ?>/' + quizId, {
            status: newStatus,
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        }, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert(response.message || '<?= lang('Admin.error_occurred') ?>');
            }
        });
    }
}

function deleteQuiz(quizId) {
    if (confirm('<?= lang('Admin.confirm_delete') ?>')) {
        $.post('<?= base_url('dt_admin/quizzes/delete') ?>/' + quizId, {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        }, function(response) {
            if (response.success) {
                window.location.href = '<?= base_url('dt_admin/quizzes') ?>';
            } else {
                alert(response.message || '<?= lang('Admin.error_occurred') ?>');
            }
        });
    }
}
</script>
<?= $this->endSection() ?>
