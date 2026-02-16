<?php

/**
 * Quizzes Module Validation Language File - English
 * 
 * This file contains validation error messages for the Quizzes module.
 */

return [
    // Quiz Validation
    'quiz_title' => [
        'required' => 'Quiz title is required.',
        'min_length' => 'Quiz title must be at least {param} characters long.',
        'max_length' => 'Quiz title cannot exceed {param} characters.',
    ],
    'quiz_description' => [
        'max_length' => 'Quiz description cannot exceed {param} characters.',
    ],
    'course_id' => [
        'required' => 'Course is required.',
        'integer' => 'Course ID must be a valid integer.',
    ],
    'unit_id' => [
        'integer' => 'Unit ID must be a valid integer.',
    ],
    'time_limit_minutes' => [
        'required' => 'Time limit is required.',
        'integer' => 'Time limit must be a valid integer.',
        'greater_than' => 'Time limit must be greater than {param} minutes.',
        'less_than_equal_to' => 'Time limit cannot exceed {param} minutes.',
    ],
    'passing_score' => [
        'required' => 'Passing score is required.',
        'decimal' => 'Passing score must be a valid number.',
        'greater_than_equal_to' => 'Passing score must be at least {param}.',
        'less_than_equal_to' => 'Passing score cannot exceed {param}.',
    ],
    'max_attempts' => [
        'integer' => 'Maximum attempts must be a valid integer.',
        'greater_than' => 'Maximum attempts must be greater than {param}.',
        'less_than_equal_to' => 'Maximum attempts cannot exceed {param}.',
    ],

    
    // Question Validation
    'question_text' => [
        'required' => 'Question text is required.',
        'min_length' => 'Question text must be at least {param} characters long.',
        'max_length' => 'Question text cannot exceed {param} characters.',
    ],
    'question_type' => [
        'required' => 'Question type is required.',
        'in_list' => 'Question type must be one of: {param}.',
    ],
    'question_points' => [
        'required' => 'Question points are required.',
        'decimal' => 'Question points must be a valid number.',
        'greater_than' => 'Question points must be greater than {param}.',
        'less_than_equal_to' => 'Question points cannot exceed {param}.',
    ],
    'options' => [
        'required' => 'Question options are required.',
    ],
    'correct_answer' => [
        'required' => 'Correct answer is required.',
    ],
    
    // Quiz Attempt Validation
    'user_id' => [
        'required' => 'User ID is required.',
        'integer' => 'User ID must be a valid integer.',
    ],
    'quiz_id' => [
        'required' => 'Quiz ID is required.',
        'integer' => 'Quiz ID must be a valid integer.',
    ],
    'answers' => [
        'required' => 'Answers are required.',
    ],
    'score' => [
        'decimal' => 'Score must be a valid number.',
        'greater_than_equal_to' => 'Score must be at least {param}.',
        'less_than_equal_to' => 'Score cannot exceed {param}.',
    ],
    'completion_time' => [
        'integer' => 'Completion time must be a valid integer.',
        'greater_than' => 'Completion time must be greater than {param} seconds.',
    ],
    
    // JSON Import Validation
    'json_file' => [
        'uploaded' => 'JSON file is required.',
        'ext_in' => 'File must be a JSON file.',
        'max_size' => 'File size cannot exceed {param}.',
    ],
    'json_content' => [
        'required' => 'JSON content is required.',
        'valid_json' => 'Invalid JSON format.',
    ],
    
    // General Validation
    'active' => [
        'in_list' => 'Active status must be 0 or 1.',
    ],
    'shuffle_questions' => [
        'in_list' => 'Shuffle questions must be 0 or 1.',
    ],
    'show_results_immediately' => [
        'in_list' => 'Show results immediately must be 0 or 1.',
    ],
];