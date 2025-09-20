<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class GenerateTestContent extends BaseCommand
{
    protected $group = 'Custom';
    protected $name = 'generate:test-content';
    protected $description = 'Generate dummy quizzes and pages for units with null metadata fields';

    public function run(array $params)
    {
        CLI::write('Starting test content generation...', 'green');

        // Load models
        $unitItemsModel = new \Modules\Units\Models\UnitItemsModel();
        $quizzesModel = new \Modules\Quizzes\Models\QuizzesModel();
        $unitsModel = new \Modules\Units\Models\UnitsModel();
        $coursesModel = new \Modules\Courses\Models\CoursesModel();

        // Get all units
        $units = $unitsModel->findAll();
        CLI::write('Found ' . count($units) . ' units to process', 'yellow');

        $createdQuizzes = 0;
        $createdPages = 0;

        foreach ($units as $unit) {
            // Get existing items for this unit
            $existingItems = $unitItemsModel->getUnitItems($unit->id);
            
            // Check if unit has any quiz items
            $hasQuiz = false;
            $hasPage = false;
            
            foreach ($existingItems as $item) {
                if ($item->item_type === 'quiz') {
                    $hasQuiz = true;
                }
                if ($item->item_type === 'page') {
                    $hasPage = true;
                }
            }

            // Create dummy quiz if none exists
            if (!$hasQuiz) {
                $quizData = [
                    'course_id' => $unit->course_id ?? 1,
                    'section_id' => $unit->section_id,
                    'quiz_title' => 'اختبار ' . $unit->unit_name,
                    'quiz_desc' => 'اختبار تقييمي لوحدة ' . $unit->unit_name,
                    'questions_data' => json_encode([
                        [
                            'question' => 'ما هو الموضوع الرئيسي لهذه الوحدة؟',
                            'type' => 'multiple_choice',
                            'options' => [
                                ['text' => $unit->unit_name, 'is_correct' => true],
                                ['text' => 'موضوع آخر', 'is_correct' => false],
                                ['text' => 'لا أعرف', 'is_correct' => false],
                                ['text' => 'جميع ما سبق', 'is_correct' => false]
                            ]
                        ],
                        [
                            'question' => 'هل فهمت محتوى هذه الوحدة؟',
                            'type' => 'multiple_choice',
                            'options' => [
                                ['text' => 'نعم، فهمت تماماً', 'is_correct' => true],
                                ['text' => 'فهمت جزئياً', 'is_correct' => false],
                                ['text' => 'لم أفهم', 'is_correct' => false],
                                ['text' => 'أحتاج مراجعة', 'is_correct' => false]
                            ]
                        ]
                    ]),
                    'time_limit' => 15,
                    'time_limit_minutes' => 15,
                    'max_attempts' => 3,
                    'passing_score' => 70.00,
                    'difficulty_level' => 'easy',
                    'shuffle_questions' => 0,
                    'shuffle_answers' => 1,
                    'show_results' => 1,
                    'show_results_immediately' => 1,
                    'active' => 1
                ];

                $quizId = $quizzesModel->insert($quizData);
                
                if ($quizId) {
                    // Create unit item for the quiz
                    $unitItemsModel->createQuizItem($unit->id, $quizId, 'اختبار ' . $unit->unit_name);
                    $createdQuizzes++;
                    CLI::write("Created quiz for unit: {$unit->unit_name}", 'green');
                }
            }

            // Create dummy page if none exists
            if (!$hasPage) {
                // Create page item directly in unit_items (assuming pages are managed differently)
                $pageData = [
                    'unit_id' => $unit->id,
                    'item_type' => 'page',
                    'item_id' => null, // Will be set after page creation
                    'title' => 'ملاحظات ' . $unit->unit_name,
                    'description' => 'ملاحظات ومراجع إضافية لوحدة ' . $unit->unit_name,
                    'sort_order' => $unitItemsModel->getNextSortOrder($unit->id),
                    'is_active' => 1,
                    'metadata' => json_encode([
                        'page_content' => '<h2>ملاحظات الوحدة</h2><p>هذه صفحة تحتوي على ملاحظات ومراجع إضافية لوحدة ' . $unit->unit_name . '</p><ul><li>نقطة مهمة 1</li><li>نقطة مهمة 2</li><li>نقطة مهمة 3</li></ul>',
                        'page_type' => 'notes',
                        'estimated_reading_time' => '5 دقائق'
                    ])
                ];

                $pageItemId = $unitItemsModel->insert($pageData);
                
                if ($pageItemId) {
                    $createdPages++;
                    CLI::write("Created page for unit: {$unit->unit_name}", 'green');
                }
            }
        }

        CLI::write("Test content generation completed!", 'green');
        CLI::write("Created {$createdQuizzes} quizzes and {$createdPages} pages", 'yellow');
    }
}