<?php

/**
 * Progress Module Validation Language File
 * 
 * This file contains validation messages for the Progress module.
 * 
 * @package    MSARLink
 * @subpackage Progress
 * @category   Language
 * @author     MSARLink Team
 * @since      1.0.0
 */

return [
    // General Validation
    'required' => 'The {field} field is required.',
    'valid_id' => 'The {field} must be a valid ID.',
    'numeric' => 'The {field} must be numeric.',
    'integer' => 'The {field} must be an integer.',
    
    // Progress Validation
    'valid_user_id' => 'Please provide a valid user ID.',
    'valid_unit_id' => 'Please provide a valid unit ID.',
    'valid_course_id' => 'Please provide a valid course ID.',
    'valid_progress_percentage' => 'Progress percentage must be between 0 and 100.',
    'valid_watch_time' => 'Watch time must be a positive number.',
    'valid_completion_status' => 'Completion status must be 0 or 1.',
    
    // Time Validation
    'valid_start_time' => 'Please provide a valid start time.',
    'valid_end_time' => 'Please provide a valid end time.',
    'end_after_start' => 'End time must be after start time.',
    'valid_duration' => 'Duration must be a positive number.',
    
    // Status Validation
    'valid_status' => 'Please select a valid status.',
    'status_in_list' => 'Status must be one of: not_started, in_progress, completed, paused.',
    
    // Data Validation
    'progress_exists' => 'Progress record already exists for this user and unit.',
    'user_exists' => 'The specified user does not exist.',
    'unit_exists' => 'The specified unit does not exist.',
    'course_exists' => 'The specified course does not exist.',
    
    // Update Validation
    'progress_not_found' => 'Progress record not found.',
    'cannot_update_completed' => 'Cannot update progress for completed units.',
    'invalid_progress_update' => 'Invalid progress update data.',
    
    // Reset Validation
    'cannot_reset_progress' => 'Cannot reset progress for this unit.',
    'reset_confirmation_required' => 'Progress reset confirmation is required.',
    
    // Analytics Validation
    'valid_date_range' => 'Please provide a valid date range.',
    'start_date_before_end' => 'Start date must be before end date.',
    'max_date_range' => 'Date range cannot exceed 365 days.',
    
    // Export Validation
    'valid_export_format' => 'Please select a valid export format.',
    'export_data_available' => 'No data available for export.',
    'export_permission_denied' => 'You do not have permission to export this data.',
];