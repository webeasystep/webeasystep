<?php
/**
 * Quizzes Admin View
 * 
 * This view displays detailed information about a specific quiz with attempts.
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
                <div class="col-md-12">
                    <!-- Quiz Information -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= isset($quiz) ? esc($quiz->quiz_title) : 'Quiz Details' ?></h3>
                            <div class="card-tools">
                                <?php if (isset($quiz)): ?>
                                <a href="<?= base_url('dt_admin/quizzes/edit/' . $quiz->id) ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> <?= lang('Admin.edit') ?>
                                </a>
                                <?php endif; ?>
                                <a href="<?= base_url('dt_admin/quizzes') ?>" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> <?= lang('Admin.back') ?>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (isset($quiz)): ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong><?= lang('Quizzes.quiz_title') ?>:</strong></td>
                                            <td><?= esc($quiz->quiz_title) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong><?= lang('Quizzes.course_title') ?>:</strong></td>
                                            <td><?= isset($quiz->course_title) ? esc($quiz->course_title) : 'N/A' ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong><?= lang('Quizzes.time_limit') ?>:</strong></td>
                                            <td><?= $quiz->time_limit_minutes ?? 'N/A' ?> <?= lang('Quizzes.minutes') ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong><?= lang('Quizzes.passing_score') ?>:</strong></td>
                                            <td><?= $quiz->passing_score ?? 'N/A' ?>%</td>
                                        </tr>
                                        <tr>
                                            <td><strong><?= lang('Quizzes.max_attempts') ?>:</strong></td>
                                            <td><?= $quiz->max_attempts ?? 'N/A' ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong><?= lang('Admin.status') ?>:</strong></td>
                                            <td>
                                                <?php if ($quiz->active): ?>
                                                    <span class="badge badge-success"><?= lang('Admin.active') ?></span>
                                                <?php else: ?>
                                                    <span class="badge badge-danger"><?= lang('Admin.inactive') ?></span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong><?= lang('Quizzes.shuffle_questions') ?>:</strong></td>
                                            <td><?= $quiz->shuffle_questions ? lang('Admin.yes') : lang('Admin.no') ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong><?= lang('Quizzes.shuffle_answers') ?>:</strong></td>
                                            <td><?= $quiz->shuffle_answers ? lang('Admin.yes') : lang('Admin.no') ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong><?= lang('Admin.created_at') ?>:</strong></td>
                                            <td><?= isset($quiz->created_at) ? date('Y-m-d H:i:s', strtotime($quiz->created_at)) : 'N/A' ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong><?= lang('Admin.updated_at') ?>:</strong></td>
                                            <td><?= isset($quiz->updated_at) ? date('Y-m-d H:i:s', strtotime($quiz->updated_at)) : 'N/A' ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <?php if (isset($quiz->quiz_desc) && !empty($quiz->quiz_desc)): ?>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h5><?= lang('Quizzes.quiz_description') ?></h5>
                                    <p><?= nl2br(esc($quiz->quiz_desc)) ?></p>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php else: ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                Quiz information not available.
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Quiz Attempts -->
                    <?php if (isset($attempts) && !empty($attempts)): ?>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= lang('Quizzes.quiz_attempts') ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th><?= lang('Admin.id') ?></th>
                                            <th><?= lang('Admin.user') ?></th>
                                            <th><?= lang('Quizzes.score') ?></th>
                                            <th><?= lang('Quizzes.status') ?></th>
                                            <th><?= lang('Admin.created_at') ?></th>
                                            <th><?= lang('Admin.actions') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($attempts as $attempt): ?>
                                        <tr>
                                            <td><?= $attempt->id ?></td>
                                            <td><?= esc($attempt->first_name . ' ' . $attempt->last_name) ?></td>
                                            <td><?= $attempt->score ?>%</td>
                                            <td>
                                                <?php if ($attempt->status == 'completed'): ?>
                                                    <span class="badge badge-success"><?= lang('Admin.completed') ?></span>
                                                <?php elseif ($attempt->status == 'in_progress'): ?>
                                                    <span class="badge badge-warning"><?= lang('Admin.in_progress') ?></span>
                                                <?php else: ?>
                                                    <span class="badge badge-danger"><?= esc($attempt->status) ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= date('Y-m-d H:i:s', strtotime($attempt->created_at)) ?></td>
                                            <td>
                                                <a href="<?= base_url('dt_admin/quizzes/attempt/' . $attempt->id) ?>" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> <?= lang('Admin.view') ?>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>