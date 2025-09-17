# Quizzes Module Technical Specification

## Overview
The Quizzes module provides comprehensive assessment functionality for the MSARLink e-learning platform, including quiz creation, management, taking, and detailed analytics.

## Architecture

### Controllers
- **Quizzes.php**: Main quiz controller for student-facing functionality
- **AdminQuizzes.php**: Administrative quiz management and analytics

### Models
- **QuizzesModel.php**: Core quiz management model
- **QuizAttemptsModel.php**: Quiz attempt tracking and analytics

### Views
- **Site/**: Student quiz interfaces (taking, results, history)
- **Admin/**: Administrative quiz management views

## Key Features

### 1. Quiz Management
- **Quiz Creation**: Comprehensive quiz builder with multiple question types
- **Question Types**: Single choice, multiple choice, true/false, fill-in-blank, essay
- **Time Limits**: Configurable quiz time restrictions
- **Attempt Limits**: Control number of allowed attempts
- **Randomization**: Question and answer shuffling options

### 2. Assessment Features
- **Automatic Grading**: Instant scoring for objective questions
- **Manual Grading**: Admin review for subjective questions
- **Passing Scores**: Configurable minimum passing percentages
- **Immediate Feedback**: Optional instant results display
- **Detailed Results**: Comprehensive answer analysis

### 3. Analytics & Reporting
- **Performance Analytics**: Quiz and student performance metrics
- **Attempt Tracking**: Complete attempt history and analysis
- **Statistical Reports**: Pass rates, average scores, time analysis
- **Question Analytics**: Individual question performance data

## Database Schema

### tb_quizzes Table
```sql
CREATE TABLE `tb_quizzes` (
    `id` int NOT NULL AUTO_INCREMENT,
    `course_id` int NOT NULL,
    `quiz_title` varchar(255) NOT NULL,
    `quiz_desc` text,
    `quiz_questions` longtext, -- JSON format
    `time_limit_minutes` int DEFAULT 60,
    `passing_score` decimal(5,2) DEFAULT 70.00,
    `max_attempts` int DEFAULT 3,
    `difficulty_level` enum('easy','medium','hard') DEFAULT 'medium',
    `shuffle_questions` tinyint(1) DEFAULT 0,
    `show_results_immediately` tinyint(1) DEFAULT 1,
    `active` tinyint(1) DEFAULT 1,
    `created_by` int DEFAULT NULL,
    `updated_by` int DEFAULT NULL,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_course_quiz` (`course_id`, `active`),
    KEY `idx_difficulty` (`difficulty_level`),
    KEY `idx_created_date` (`created_at`)
);
```

### tb_quiz_attempts Table
```sql
CREATE TABLE `tb_quiz_attempts` (
    `id` int NOT NULL AUTO_INCREMENT,
    `quiz_id` int NOT NULL,
    `user_id` int NOT NULL,
    `attempt_number` int NOT NULL DEFAULT 1,
    `user_answers` longtext, -- JSON format
    `score` decimal(5,2) DEFAULT 0.00,
    `max_score` decimal(5,2) DEFAULT 100.00,
    `is_passed` tinyint(1) DEFAULT 0,
    `time_taken_seconds` int DEFAULT 0,
    `started_at` datetime DEFAULT NULL,
    `submitted_at` datetime DEFAULT NULL,
    `graded_at` datetime DEFAULT NULL,
    `graded_by` int DEFAULT NULL,
    `feedback` text,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `user_quiz_attempt` (`user_id`, `quiz_id`, `attempt_number`),
    KEY `idx_quiz_attempts` (`quiz_id`, `submitted_at`),
    KEY `idx_user_attempts` (`user_id`, `is_passed`),
    KEY `idx_score_analysis` (`quiz_id`, `score`)
);
```

## Quiz JSON Structure

### Quiz Questions Format
```json
{
    "quiz_title": "Sample Quiz",
    "quiz_desc": "This is a sample quiz",
    "time_limit_minutes": 30,
    "passing_score": 70,
    "max_attempts": 3,
    "questions": [
        {
            "id": 1,
            "question_text": "What is the capital of Saudi Arabia?",
            "question_type": "single_choice",
            "points": 10,
            "options": ["Jeddah", "Riyadh", "Dammam", "Mecca"],
            "correct_answer": "Riyadh",
            "explanation": "Riyadh is the capital and largest city of Saudi Arabia."
        },
        {
            "id": 2,
            "question_text": "Select all programming languages:",
            "question_type": "multiple_choice",
            "points": 15,
            "options": ["Python", "HTML", "JavaScript", "CSS"],
            "correct_answer": ["Python", "JavaScript"],
            "explanation": "Python and JavaScript are programming languages, while HTML and CSS are markup/styling languages."
        },
        {
            "id": 3,
            "question_text": "The Earth is flat.",
            "question_type": "true_false",
            "points": 5,
            "correct_answer": false,
            "explanation": "The Earth is approximately spherical in shape."
        }
    ]
}
```

### User Answers Format
```json
{
    "attempt_id": 123,
    "quiz_id": 45,
    "user_id": 67,
    "started_at": "2024-01-15 10:00:00",
    "submitted_at": "2024-01-15 10:25:30",
    "answers": [
        {
            "question_id": 1,
            "user_answer": "Riyadh",
            "is_correct": true,
            "points_earned": 10,
            "time_spent": 45
        },
        {
            "question_id": 2,
            "user_answer": ["Python"],
            "is_correct": false,
            "points_earned": 0,
            "time_spent": 120
        }
    ],
    "total_score": 10,
    "percentage": 50.0
}
```

## API Endpoints

### Student Quiz API
```php
// Quiz browsing
GET /quizzes
GET /quizzes/course/{course_id}
GET /quizzes/view/{quiz_id}

// Quiz taking
GET /quizzes/take/{quiz_id}
POST /quizzes/submit/{quiz_id}
GET /quizzes/results/{attempt_id}
GET /quizzes/retry/{quiz_id}

// User history
GET /quizzes/my-attempts
GET /quizzes/attempt/{attempt_id}

// AJAX endpoints
POST /quizzes/ajax/save-progress
GET /quizzes/ajax/time-remaining/{attempt_id}
```

### Admin Quiz API
```php
// Quiz management
GET /dt_admin/quizzes
POST /dt_admin/quizzes/create
GET /dt_admin/quizzes/edit/{quiz_id}
GET /dt_admin/quizzes/view/{quiz_id}
POST /dt_admin/quizzes/delete/{quiz_id}

// Question management
GET /dt_admin/quizzes/questions/{quiz_id}
POST /dt_admin/quizzes/import
GET /dt_admin/quizzes/export/{quiz_id}

// Attempt management
GET /dt_admin/quizzes/attempts
GET /dt_admin/quizzes/attempts/{quiz_id}
GET /dt_admin/quizzes/view-attempt/{attempt_id}

// Analytics
GET /dt_admin/quizzes/analytics
```

## Quiz Taking Workflow

### Student Quiz Flow
```php
1. Student selects quiz from course
2. System checks prerequisites and attempt limits
3. Quiz instructions and rules displayed
4. Timer starts when student begins
5. Questions presented (shuffled if enabled)
6. Answers saved automatically (AJAX)
7. Manual submission or auto-submit on timeout
8. Immediate grading for objective questions
9. Results displayed (if enabled)
10. Attempt recorded in database
```

### Admin Grading Flow
```php
1. Admin reviews submitted attempts
2. Automatic grading for objective questions
3. Manual grading for subjective questions
4. Feedback and comments added
5. Final score calculated and recorded
6. Student notified of results
7. Certificate generated if passed
```

## Security Features

### Quiz Security
- **Time Enforcement**: Strict time limit enforcement
- **Attempt Validation**: Prevent multiple simultaneous attempts
- **Answer Encryption**: Secure answer transmission
- **Cheating Prevention**: Tab switching detection, copy-paste blocking

### Data Protection
- **Access Control**: User can only access their own attempts
- **Admin Authorization**: Quiz management restricted to admins
- **Input Validation**: All quiz data validated and sanitized
- **Audit Trail**: Complete attempt and grading history

## Question Types Implementation

### Single Choice Questions
```php
// Validation and scoring
function validateSingleChoice($userAnswer, $correctAnswer) {
    return trim($userAnswer) === trim($correctAnswer);
}

function scoreSingleChoice($isCorrect, $points) {
    return $isCorrect ? $points : 0;
}
```

### Multiple Choice Questions
```php
// Validation and scoring
function validateMultipleChoice($userAnswers, $correctAnswers) {
    sort($userAnswers);
    sort($correctAnswers);
    return $userAnswers === $correctAnswers;
}

function scoreMultipleChoice($userAnswers, $correctAnswers, $points) {
    $correct = array_intersect($userAnswers, $correctAnswers);
    $incorrect = array_diff($userAnswers, $correctAnswers);
    $missed = array_diff($correctAnswers, $userAnswers);
    
    // Partial credit calculation
    $correctCount = count($correct);
    $incorrectCount = count($incorrect);
    $totalCorrect = count($correctAnswers);
    
    $score = ($correctCount - $incorrectCount) / $totalCorrect;
    return max(0, $score * $points);
}
```

### True/False Questions
```php
// Validation and scoring
function validateTrueFalse($userAnswer, $correctAnswer) {
    return (bool)$userAnswer === (bool)$correctAnswer;
}
```

## Analytics Features

### Quiz Performance Metrics
```php
// Key analytics tracked
- Average score per quiz
- Pass/fail rates
- Time analysis (average, min, max)
- Question difficulty analysis
- Student performance trends
- Attempt distribution patterns
```

### Reporting Capabilities
- **Quiz Reports**: Individual quiz performance analysis
- **Student Reports**: Individual student progress tracking
- **Course Reports**: Course-wide assessment analytics
- **Comparative Analysis**: Performance comparison across quizzes

## Integration Points

### Course Module Integration
```php
// Course progression integration
- Quiz completion unlocks next units
- Course completion requires quiz passing
- Quiz scores contribute to course grades
- Sequential learning path enforcement
```

### Progress Module Integration
```php
// Learning progress integration
- Quiz attempts tracked in progress system
- Completion status updated automatically
- Learning analytics include quiz performance
- Time spent on quizzes tracked
```

### Notification System
```php
// Quiz-related notifications
- Quiz availability notifications
- Attempt deadline reminders
- Results and feedback notifications
- Retake opportunity alerts
```

## Performance Optimization

### Database Optimization
- **Indexed Queries**: Optimized for common query patterns
- **JSON Optimization**: Efficient JSON field handling
- **Query Caching**: Frequently accessed quiz data cached
- **Batch Processing**: Bulk grading operations

### Frontend Optimization
- **AJAX Auto-save**: Prevent data loss during quiz taking
- **Progressive Loading**: Large quizzes loaded progressively
- **Client-side Validation**: Immediate feedback on answers
- **Offline Support**: Basic offline quiz taking capability

## Error Handling

### Quiz Taking Errors
- **Network Failures**: Auto-save and recovery mechanisms
- **Time Expiration**: Graceful timeout handling
- **Browser Crashes**: Progress recovery on restart
- **Invalid Submissions**: Validation and error messages

### System Errors
- **Database Failures**: Graceful degradation and retry logic
- **Grading Errors**: Manual intervention and correction
- **Import Errors**: Detailed error reporting for JSON imports

## Configuration

### Environment Variables
```env
# Quiz Settings
QUIZ_DEFAULT_TIME_LIMIT=60
QUIZ_MAX_ATTEMPTS=5
QUIZ_AUTO_SAVE_INTERVAL=30
QUIZ_SESSION_TIMEOUT=3600

# Grading Settings
QUIZ_PASSING_SCORE=70
QUIZ_PARTIAL_CREDIT=true
QUIZ_IMMEDIATE_RESULTS=true

# Security Settings
QUIZ_PREVENT_CHEATING=true
QUIZ_TAB_SWITCHING_DETECTION=true
QUIZ_COPY_PASTE_BLOCKING=true
```

## Testing Strategy

### Unit Tests
- Question validation logic
- Scoring algorithm accuracy
- Time limit enforcement
- Attempt limit validation

### Integration Tests
- Complete quiz taking flow
- Grading and results display
- Admin management functions
- Analytics calculation accuracy

### Security Tests
- Cheating prevention mechanisms
- Access control validation
- Data encryption verification
- Input sanitization testing

## Monitoring & Logging

### Quiz Events Logged
- Quiz creation and modifications
- Student attempt starts and submissions
- Grading actions and score changes
- System errors and exceptions
- Performance metrics and timing

### Analytics Data Collection
- Question response patterns
- Time spent per question
- Common wrong answers
- Student behavior patterns
- System performance metrics

## Accessibility Features

### Inclusive Design
- **Screen Reader Support**: Proper ARIA labels and structure
- **Keyboard Navigation**: Full keyboard accessibility
- **High Contrast**: Support for high contrast themes
- **Font Scaling**: Responsive text sizing
- **Time Extensions**: Accommodations for students with disabilities

## Future Enhancements

### Planned Features
- **AI-powered Question Generation**: Automatic question creation
- **Adaptive Testing**: Dynamic difficulty adjustment
- **Proctoring Integration**: Online proctoring capabilities
- **Advanced Analytics**: Machine learning insights
- **Mobile App**: Dedicated mobile quiz application

### Advanced Question Types
- **Drag and Drop**: Interactive question types
- **Hotspot Questions**: Image-based questions
- **Audio/Video Questions**: Multimedia assessments
- **Code Evaluation**: Programming assessment questions

## Troubleshooting

### Common Issues
1. **Quiz Not Loading**: Check browser compatibility and JavaScript
2. **Answers Not Saving**: Verify network connection and session
3. **Incorrect Scoring**: Review question configuration and grading logic
4. **Time Issues**: Check server time synchronization

### Debug Tools
- Quiz attempt trace logging
- Grading calculation verification
- Performance profiling tools
- Network request monitoring

## Dependencies

### Required Libraries
- CodeIgniter 4.x framework
- Shield authentication library
- JSON handling libraries
- File upload handling (for imports)

### Frontend Dependencies
- JavaScript timer libraries
- AJAX handling libraries
- Form validation libraries
- Chart.js for analytics visualization

### Optional Integrations
- Proctoring service APIs
- Question bank services
- Learning analytics platforms
- Certificate generation services