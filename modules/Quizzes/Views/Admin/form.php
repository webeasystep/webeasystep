<?php
/**
 * Quizzes Admin Form View
 *
 * This view displays the form for creating/editing quizzes.
 */
?>

<?= $this->extend('admin_layout/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= isset($quiz) ? lang('Quizzes.edit_quiz') : lang('Quizzes.create_quiz') ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dt_admin') ?>"><?= lang('Admin.dashboard') ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('dt_admin/quizzes') ?>"><?= lang('Quizzes.quizzes') ?></a></li>
                        <li class="breadcrumb-item active"><?= isset($quiz) ? lang('Admin.edit') : lang('Admin.create') ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <form action="<?= isset($quiz) ? base_url('dt_admin/quizzes/edit/' . $quiz->id) : base_url('dt_admin/quizzes/create') ?>" method="post">
                <?= csrf_field() ?>

                <div class="row">
                    <div class="col-md-8">
                        <!-- Basic Information -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><?= lang('Quizzes.quiz_information') ?></h3>
                            </div>
                            <div class="card-body">
                                <?= $this->include('admin_layout/errors_list') ?>

                                <div class="form-group">
                                    <label for="quiz_title"><?= lang('Quizzes.quiz_title') ?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="quiz_title" name="quiz_title"
                                           value="<?= isset($quiz) ? esc($quiz->quiz_title) : old('quiz_title') ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="quiz_desc"><?= lang('Quizzes.quiz_desc') ?></label>
                                    <textarea class="form-control" id="quiz_desc" name="quiz_desc" rows="3"><?= isset($quiz) ? esc($quiz->quiz_desc) : old('quiz_desc') ?></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="course_id"><?= lang('Courses.course') ?> <span class="text-danger">*</span></label>
                                            <select class="form-control select2" id="course_id" name="course_id" required>
                                                <option value=""><?= lang('Admin.select_course') ?></option>
                                                <?php if (isset($courses)): ?>
                                                    <?php foreach ($courses as $course): ?>
                                                        <option value="<?= $course->id ?>" <?= (isset($quiz) && $quiz->course_id == $course->id) ? 'selected' : '' ?>>
                                                            <?= esc($course->course_title) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Quiz Settings -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><?= lang('Quizzes.quiz_settings') ?></h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="time_limit_minutes"><?= lang('Quizzes.time_limit_minutes') ?> <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="time_limit_minutes" name="time_limit_minutes"
                                                   min="1" max="300" value="<?= isset($quiz) ? $quiz->time_limit_minutes : old('time_limit_minutes', 30) ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="passing_score"><?= lang('Quizzes.passing_score') ?> (%) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="passing_score" name="passing_score"
                                                   min="0" max="100" step="0.01" value="<?= isset($quiz) ? $quiz->passing_score : old('passing_score', 60) ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="max_attempts"><?= lang('Quizzes.max_attempts') ?></label>
                                            <input type="number" class="form-control" id="max_attempts" name="max_attempts"
                                                   min="1" max="10" value="<?= isset($quiz) ? $quiz->max_attempts : old('max_attempts', 3) ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="form-check mt-4">
                                                <input type="checkbox" class="form-check-input" id="shuffle_questions" name="shuffle_questions" value="1"
                                                       <?= (isset($quiz) && $quiz->shuffle_questions) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="shuffle_questions">
                                                    <?= lang('Quizzes.shuffle_questions') ?>
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="show_results" name="show_results" value="1"
                                                       <?= (isset($quiz) && $quiz->show_results) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="show_results">
                                                    <?= lang('Quizzes.show_results') ?>
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="shuffle_answers" name="shuffle_answers" value="1"
                                                       <?= (isset($quiz) && $quiz->shuffle_answers) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="shuffle_answers">
                                                    <?= lang('Quizzes.shuffle_answers') ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Questions Section -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><?= lang('Quizzes.quiz_questions') ?></h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="addQuestion()">
                                        <i class="fas fa-plus"></i> <?= lang('Quizzes.add_question') ?>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="questions-container">
                                    <?php if (isset($quiz) && isset($questions) && !empty($questions)): ?>
                                        <?php foreach ($questions as $index => $question): ?>
                                            <div class="question-item border p-3 mb-3" data-index="<?= $index ?>">
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <h6><?= lang('Quizzes.question') ?> <?= $index + 1 ?></h6>
                                                    </div>
                                                    <div class="col-md-2 text-right">
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeQuestion(<?= $index ?>)">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label><?= lang('Quizzes.question_text') ?></label>
                                                    <textarea class="form-control" name="questions[<?= $index ?>][question_text]" rows="2" required><?= esc($question['question_text'] ?? '') ?></textarea>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><?= lang('Quizzes.question_type') ?></label>
                                                            <select class="form-control" name="questions[<?= $index ?>][question_type]" onchange="updateQuestionOptions(<?= $index ?>, this.value)" required>
                                                                <option value="single_choice" <?= ($question['question_type'] ?? '') === 'single_choice' ? 'selected' : '' ?>><?= lang('Quizzes.single_choice') ?></option>
                                                                <option value="multiple_choice" <?= ($question['question_type'] ?? '') === 'multiple_choice' ? 'selected' : '' ?>><?= lang('Quizzes.multiple_choice') ?></option>
                                                                <option value="true_false" <?= ($question['question_type'] ?? '') === 'true_false' ? 'selected' : '' ?>><?= lang('Quizzes.true_false') ?></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><?= lang('Quizzes.question_points') ?></label>
                                                            <input type="number" class="form-control" name="questions[<?= $index ?>][points]" min="0.1" step="0.1" value="<?= esc($question['points'] ?? 1) ?>" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="question-options-<?= $index ?>">
                                                    <?php
                                                    $questionType = $question['question_type'] ?? 'single_choice';
                                                    if ($questionType === 'single_choice' || $questionType === 'multiple_choice'):
                                                        $options = $question['options'] ?? [];
                                                        $correct = $question['correct'] ?? [];
                                                        if (!is_array($correct)) $correct = [$correct];
                                                    ?>
                                                        <div class="form-group">
                                                            <label><?= lang('Quizzes.options') ?></label>
                                                            <div id="options-<?= $index ?>">
                                                                <?php foreach ($options as $optIndex => $option): ?>
                                                                    <div class="input-group mb-2">
                                                                        <input type="text" class="form-control" name="questions[<?= $index ?>][options][]" value="<?= esc($option) ?>" placeholder="<?= lang('Quizzes.option') ?> <?= $optIndex + 1 ?>" required>
                                                                        <div class="input-group-append">
                                                                            <div class="input-group-text">
                                                                                <input type="<?= $questionType === 'single_choice' ? 'radio' : 'checkbox' ?>" name="questions[<?= $index ?>][correct]<?= $questionType === 'multiple_choice' ? '[]' : '' ?>" value="<?= $optIndex ?>" <?= in_array($optIndex, $correct) ? 'checked' : '' ?>>
                                                                            </div>
                                                                            <?php if ($optIndex > 1): ?>
                                                                                <button type="button" class="btn btn-danger btn-sm" onclick="$(this).closest('.input-group').remove()">
                                                                                    <i class="fas fa-trash"></i>
                                                                                </button>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                            <button type="button" class="btn btn-sm btn-secondary" onclick="addOption(<?= $index ?>, '<?= $questionType ?>')">
                                                                <i class="fas fa-plus"></i> <?= lang('Quizzes.add_option') ?>
                                                            </button>
                                                        </div>
                                                    <?php elseif ($questionType === 'true_false'): ?>
                                                        <div class="form-group">
                                                            <label><?= lang('Quizzes.correct_answer') ?></label>
                                                            <select class="form-control" name="questions[<?= $index ?>][correct_answer]" required>
                                                                <option value="true" <?= ($question['correct_answer'] ?? '') === 'true' ? 'selected' : '' ?>><?= lang('Admin.true') ?></option>
                                                                <option value="false" <?= ($question['correct_answer'] ?? '') === 'false' ? 'selected' : '' ?>><?= lang('Admin.false') ?></option>
                                                            </select>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Actions -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><?= lang('Admin.actions') ?></h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="active"><?= lang('Admin.status') ?></label>
                                    <select class="form-control" id="active" name="active">
                                        <option value="1" <?= (isset($quiz) && $quiz->active == 1) ? 'selected' : '' ?>>
                                            <?= lang('Admin.active') ?>
                                        </option>
                                        <option value="0" <?= (isset($quiz) && $quiz->active == 0) ? 'selected' : '' ?>>
                                            <?= lang('Admin.inactive') ?>
                                        </option>
                                    </select>
                                </div>

                                <div class="btn-group-vertical w-100">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> <?= lang('Admin.save') ?>
                                    </button>
                                    <?php if (isset($quiz)): ?>
                                        <a href="<?= base_url('quizzes/take/' . $quiz->id) ?>" target="_blank" class="btn btn-success mt-2 mb-2">
                                            <i class="fas fa-play"></i> تجربة الاختبار
                                        </a>
                                        <a href="<?= base_url('dt_admin/quizzes/view/' . $quiz->id) ?>" class="btn btn-info mb-2">
                                            <i class="fas fa-eye"></i> <?= lang('Admin.view') ?>
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?= base_url('dt_admin/quizzes') ?>" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> <?= lang('Admin.cancel') ?>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Import Questions -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><?= lang('Quizzes.import_questions') ?></h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="json_file"><?= lang('Admin.json_file') ?></label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="json_file" name="json_file" accept=".json">
                                        <label class="custom-file-label" for="json_file"><?= lang('Admin.choose_file') ?></label>
                                    </div>
                                    <small class="form-text text-muted">
                                        <?= lang('Quizzes.json_import_help') ?>
                                    </small>
                                </div>
                                <button type="button" class="btn btn-info btn-sm" onclick="importQuestions()">
                                    <i class="fas fa-upload"></i> <?= lang('Quizzes.import_questions') ?>
                                </button>
                            </div>
                        </div>

                        <!-- Help -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><?= lang('Admin.help') ?></h3>
                            </div>
                            <div class="card-body">
                                <h6><?= lang('Quizzes.question_types') ?>:</h6>
                                <ul class="list-unstyled text-sm">
                                    <li><strong><?= lang('Quizzes.single_choice') ?>:</strong> <?= lang('Quizzes.single_choice_desc') ?></li>
                                    <li><strong><?= lang('Quizzes.multiple_choice') ?>:</strong> <?= lang('Quizzes.multiple_choice_desc') ?></li>
                                    <li><strong><?= lang('Quizzes.true_false') ?>:</strong> <?= lang('Quizzes.true_false_desc') ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
let questionIndex = <?= isset($questions) && !empty($questions) ? count($questions) : 0 ?>;

$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4'
    });



    // File input label update
    $('.custom-file-input').on('change', function() {
        const fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
    });
});



function addQuestion() {
    questionIndex++;
    const questionHtml = `
        <div class="question-item border p-3 mb-3" data-index="${questionIndex}">
            <div class="row">
                <div class="col-md-10">
                    <h6><?= lang('Quizzes.question') ?> ${questionIndex}</h6>
                </div>
                <div class="col-md-2 text-right">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeQuestion(${questionIndex})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label><?= lang('Quizzes.question_text') ?></label>
                <textarea class="form-control" name="questions[${questionIndex}][question_text]" rows="2" required></textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?= lang('Quizzes.question_type') ?></label>
                        <select class="form-control" name="questions[${questionIndex}][question_type]" onchange="updateQuestionOptions(${questionIndex}, this.value)" required>
                            <option value="single_choice"><?= lang('Quizzes.single_choice') ?></option>
                            <option value="multiple_choice"><?= lang('Quizzes.multiple_choice') ?></option>
                            <option value="true_false"><?= lang('Quizzes.true_false') ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?= lang('Quizzes.question_points') ?></label>
                        <input type="number" class="form-control" name="questions[${questionIndex}][points]" min="0.1" step="0.1" value="1" required>
                    </div>
                </div>
            </div>

            <div id="question-options-${questionIndex}">
                <!-- Options will be loaded here based on question type -->
            </div>
        </div>
    `;

    $('#questions-container').append(questionHtml);
    updateQuestionOptions(questionIndex, 'single_choice');
}

function removeQuestion(index) {
    $(`.question-item[data-index="${index}"]`).remove();
}

function updateQuestionOptions(index, type) {
    let optionsHtml = '';

    if (type === 'single_choice' || type === 'multiple_choice') {
        optionsHtml = `
            <div class="form-group">
                <label><?= lang('Quizzes.options') ?></label>
                <div id="options-${index}">
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" name="questions[${index}][options][]" placeholder="<?= lang('Quizzes.option') ?> 1" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <input type="${type === 'single_choice' ? 'radio' : 'checkbox'}" name="questions[${index}][correct]${type === 'multiple_choice' ? '[]' : ''}" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" name="questions[${index}][options][]" placeholder="<?= lang('Quizzes.option') ?> 2" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <input type="${type === 'single_choice' ? 'radio' : 'checkbox'}" name="questions[${index}][correct]${type === 'multiple_choice' ? '[]' : ''}" value="1">
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-secondary" onclick="addOption(${index}, '${type}')">
                    <i class="fas fa-plus"></i> <?= lang('Quizzes.add_option') ?>
                </button>
            </div>
        `;
    } else if (type === 'true_false') {
        optionsHtml = `
            <div class="form-group">
                <label><?= lang('Quizzes.correct_answer') ?></label>
                <select class="form-control" name="questions[${index}][correct_answer]" required>
                    <option value="true"><?= lang('Admin.true') ?></option>
                    <option value="false"><?= lang('Admin.false') ?></option>
                </select>
            </div>
        `;
    }

    $(`#question-options-${index}`).html(optionsHtml);
}

function addOption(questionIndex, type) {
    const optionsContainer = $(`#options-${questionIndex}`);
    const optionCount = optionsContainer.find('.input-group').length;

    const optionHtml = `
        <div class="input-group mb-2">
            <input type="text" class="form-control" name="questions[${questionIndex}][options][]" placeholder="<?= lang('Quizzes.option') ?> ${optionCount + 1}" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <input type="${type === 'single_choice' ? 'radio' : 'checkbox'}" name="questions[${questionIndex}][correct]${type === 'multiple_choice' ? '[]' : ''}" value="${optionCount}">
                </div>
                <button type="button" class="btn btn-danger btn-sm" onclick="$(this).closest('.input-group').remove()">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;

    optionsContainer.append(optionHtml);
}

function importQuestions() {
    const fileInput = document.getElementById('json_file');
    if (!fileInput.files[0]) {
        alert('<?= lang('Admin.select_file_first') ?>');
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        try {
            const questions = JSON.parse(e.target.result);
            if (questions && questions.questions) {
                questions.questions.forEach(function(question) {
                    // Add question to form
                    addQuestionFromJSON(question);
                });
                toastr.success('<?= lang('Quizzes.questions_imported') ?>');
            }
        } catch (error) {
            alert('<?= lang('Admin.invalid_json') ?>');
        }
    };
    reader.readAsText(fileInput.files[0]);
}

function addQuestionFromJSON(questionData) {
    // Implementation for adding questions from JSON import
    addQuestion();
    // Populate the question fields with data from questionData
}
</script>
<?= $this->endSection() ?>
