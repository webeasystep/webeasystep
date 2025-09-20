# Courses Module Technical Specification

## Overview
The Courses module is the core educational content management system for the MSARLink e-learning platform, handling course creation, content delivery, enrollment management, and learning path progression.

## Architecture

### Controllers
- **Courses.php**: Main courses controller for student-facing functionality
- **AdminCourses.php**: Administrative course management interface

### Models
- **CoursesModel.php**: Core course data management
- **SectionsModel.php**: Course section management
- **UnitsModel.php**: Individual unit/lesson management

### Views
- **Site/**: Student course interfaces (browsing, viewing, learning)
- **Admin/**: Administrative course management interface

## Key Features

### 1. Course Management
- **Hierarchical Structure**: Courses → Sections → Units
- **Rich Content**: Video lessons, quizzes, assignments, resources
- **Flexible Pricing**: Free courses, paid courses, credit-based pricing
- **Course Metadata**: Descriptions, prerequisites, skill levels, duration

### 2. Content Delivery
- **Sequential Learning**: Enforced learning path progression
- **Video Integration**: Bunny.net secure video streaming
- **Progress Tracking**: Real-time learning progress monitoring
- **Completion Certificates**: Automated certificate generation

### 3. Enrollment Management
- **Enrollment Control**: Manual and automatic enrollment
- **Access Control**: Time-based and prerequisite-based access
- **Enrollment Analytics**: Track enrollment patterns and trends
- **Bulk Operations**: Mass enrollment and management

## Database Schema

### tb_courses Table
```sql
CREATE TABLE `tb_courses` (
    `id` int NOT NULL AUTO_INCREMENT,
    `course_name` varchar(255) NOT NULL,
    `slug` varchar(255) DEFAULT NULL,
    `skill_level` varchar(100) DEFAULT NULL,
    `intro_video_id` varchar(100) DEFAULT NULL,
    `price` decimal(10,2) NOT NULL,
    `image` json DEFAULT NULL,
    `course_desc` text,
    `course_structure` json DEFAULT NULL,
    `waiting_list` tinyint(1) NOT NULL DEFAULT '0',
    `is_free` tinyint(1) DEFAULT '0',
    `short_desc` varchar(100) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `sort` int DEFAULT NULL,
    `active` tinyint(1) DEFAULT NULL,
    `prerequisites` json DEFAULT NULL,
    `learning_outcomes` json DEFAULT NULL,
    `duration_hours` int DEFAULT NULL,
    `difficulty_level` enum('beginner','intermediate','advanced') DEFAULT 'beginner',
    `category_id` int DEFAULT NULL,
    `instructor_id` int DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `slug` (`slug`),
    KEY `idx_active_sort` (`active`, `sort`),
    KEY `idx_skill_level` (`skill_level`),
    KEY `idx_category` (`category_id`),
    KEY `idx_instructor` (`instructor_id`)
);
```

### tb_sections Table
```sql
CREATE TABLE `tb_sections` (
    `id` int NOT NULL AUTO_INCREMENT,
    `course_id` int NOT NULL,
    `section_title` varchar(255) NOT NULL,
    `section_desc` text,
    `sort_order` int DEFAULT 0,
    `active` tinyint(1) DEFAULT 1,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_course_sort` (`course_id`, `sort_order`),
    FOREIGN KEY (`course_id`) REFERENCES `tb_courses` (`id`) ON DELETE CASCADE
);
```

### tb_units Table
```sql
CREATE TABLE `tb_units` (
    `id` int NOT NULL AUTO_INCREMENT,
    `section_id` int NOT NULL,
    `unit_title` varchar(255) NOT NULL,
    `unit_desc` text,
    `video_id` varchar(100) DEFAULT NULL,
    `video_duration` int DEFAULT NULL,
    `unit_type` enum('video','quiz','assignment','resource') DEFAULT 'video',
    `content` longtext,
    `sort_order` int DEFAULT 0,
    `is_preview` tinyint(1) DEFAULT 0,
    `active` tinyint(1) DEFAULT 1,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_section_sort` (`section_id`, `sort_order`),
    KEY `idx_type_active` (`unit_type`, `active`),
    FOREIGN KEY (`section_id`) REFERENCES `tb_sections` (`id`) ON DELETE CASCADE
);
```

## API Endpoints

### Student Course API
```php
// Course browsing
GET /courses
GET /courses/category/{category}
GET /courses/search?q={query}
GET /courses/course_details/{slug}

// Course access (authenticated)
GET /courses/my_courses
GET /courses/course_view/{slug}
POST /courses/enroll/{course_id}

// Learning progress
POST /courses/markLessonComplete
GET /courses/progress/{course_id}
```

### Admin Course API
```php
// Course management
GET /dt_admin/courses
POST /dt_admin/courses/add
GET /dt_admin/courses/edit/{id}
POST /dt_admin/courses/update/{id}
POST /dt_admin/courses/delete/{id}

// Content management
GET /dt_admin/courses/sections/{course_id}
POST /dt_admin/courses/add-section
GET /dt_admin/courses/units/{section_id}
POST /dt_admin/courses/add-unit

// Analytics
GET /dt_admin/courses/analytics/{course_id}
GET /dt_admin/courses/enrollment-report
```

## Course Structure

### JSON Course Structure
```json
{
    "course_id": 1,
    "sections": [
        {
            "section_id": 1,
            "section_title": "Introduction to Programming",
            "sort_order": 1,
            "units": [
                {
                    "unit_id": 1,
                    "unit_title": "What is Programming?",
                    "unit_type": "video",
                    "video_id": "bunny_video_guid",
                    "duration": 1800,
                    "is_preview": true,
                    "sort_order": 1
                },
                {
                    "unit_id": 2,
                    "unit_title": "Programming Quiz",
                    "unit_type": "quiz",
                    "quiz_id": 1,
                    "sort_order": 2
                }
            ]
        }
    ]
}
```

## Features Implementation

### Sequential Learning System
```php
// Check if user can access unit
public function canAccessUnit($userId, $unitId) {
    $unit = $this->unitsModel->find($unitId);
    $previousUnits = $this->getPreviousUnits($unitId);
    
    foreach ($previousUnits as $prevUnit) {
        if (!$this->isUnitCompleted($userId, $prevUnit->id)) {
            return false;
        }
    }
    
    return true;
}
```

### Progress Calculation
```php
// Calculate course completion percentage
public function getCourseProgress($userId, $courseId) {
    $totalUnits = $this->getTotalUnits($courseId);
    $completedUnits = $this->getCompletedUnits($userId, $courseId);
    
    return $totalUnits > 0 ? ($completedUnits / $totalUnits) * 100 : 0;
}
```

### Enrollment Management
```php
// Enroll user in course
public function enrollUser($userId, $courseId) {
    // Check prerequisites
    if (!$this->checkPrerequisites($userId, $courseId)) {
        return ['success' => false, 'message' => 'Prerequisites not met'];
    }
    
    // Check payment/credits
    if (!$this->checkPayment($userId, $courseId)) {
        return ['success' => false, 'message' => 'Insufficient credits'];
    }
    
    // Create enrollment
    $enrollment = [
        'user_id' => $userId,
        'course_id' => $courseId,
        'enrolled_at' => date('Y-m-d H:i:s'),
        'status' => 'active'
    ];
    
    return $this->enrollmentsModel->insert($enrollment);
}
```

## Security Features

### Access Control
- **Enrollment Verification**: Verify user enrollment before content access
- **Sequential Access**: Enforce learning path progression
- **Video Security**: Secure video streaming with token authentication
- **Content Protection**: Prevent unauthorized content access

### Data Protection
- **Input Validation**: Comprehensive input validation for all course data
- **XSS Prevention**: Escape all output data
- **SQL Injection Prevention**: Parameterized queries throughout
- **File Upload Security**: Secure handling of course materials

## Integration Points

### Progress Module Integration
```php
// Track unit completion
public function markUnitComplete($userId, $unitId) {
    $progressData = [
        'user_id' => $userId,
        'unit_id' => $unitId,
        'progress_percentage' => 100,
        'is_completed' => 1,
        'completed_at' => date('Y-m-d H:i:s')
    ];
    
    return $this->progressModel->updateProgress($progressData);
}
```

### Billing Module Integration
```php
// Check course payment
public function checkCoursePayment($userId, $courseId) {
    $course = $this->find($courseId);
    
    if ($course->is_free) {
        return true;
    }
    
    $userBalance = $this->billingModel->getUserBalance($userId);
    return $userBalance >= $course->price;
}
```

### Quiz Module Integration
```php
// Get course quizzes
public function getCourseQuizzes($courseId) {
    return $this->quizzesModel->where('course_id', $courseId)
                             ->where('active', 1)
                             ->findAll();
}
```

## Performance Optimization

### Caching Strategy
- **Course Data**: Cache course information for 1 hour
- **Course Structure**: Cache course hierarchy for 30 minutes
- **User Progress**: Cache progress data for 5 minutes
- **Popular Courses**: Cache trending courses for 2 hours

### Database Optimization
- **Indexed Queries**: Optimized indexes for common query patterns
- **Query Optimization**: Efficient joins and aggregations
- **Pagination**: Large course lists paginated for performance
- **Lazy Loading**: Load course content on demand

## Analytics Features

### Course Analytics
- **Enrollment Metrics**: Track course popularity and enrollment trends
- **Completion Rates**: Monitor course completion statistics
- **Engagement Analytics**: Track user engagement with course content
- **Performance Metrics**: Identify high and low-performing courses

### Learning Analytics
- **Progress Tracking**: Monitor individual and group learning progress
- **Time Analytics**: Track time spent on courses and units
- **Drop-off Analysis**: Identify where students typically drop off
- **Success Metrics**: Measure learning outcomes and achievements

## Configuration

### Environment Variables
```env
# Course Settings
COURSES_PER_PAGE=12
COURSE_CACHE_DURATION=3600
COURSE_IMAGE_MAX_SIZE=5MB
COURSE_VIDEO_PROVIDER=bunnynet

# Learning Settings
SEQUENTIAL_LEARNING=true
AUTO_CERTIFICATE=true
PROGRESS_TRACKING=true
COMPLETION_THRESHOLD=80

# Enrollment Settings
AUTO_ENROLLMENT=false
ENROLLMENT_APPROVAL=false
WAITING_LIST_ENABLED=true
```

## Usage Examples

### Creating a Course
```php
$courseData = [
    'course_name' => 'Introduction to Python Programming',
    'slug' => 'intro-python-programming',
    'skill_level' => 'beginner',
    'price' => 299.00,
    'course_desc' => 'Learn Python programming from scratch...',
    'duration_hours' => 40,
    'difficulty_level' => 'beginner',
    'active' => 1
];

$courseId = $coursesModel->insert($courseData);
```

### Adding Course Content
```php
// Add section
$sectionData = [
    'course_id' => $courseId,
    'section_title' => 'Python Basics',
    'section_desc' => 'Learn the fundamentals of Python',
    'sort_order' => 1
];

$sectionId = $sectionsModel->insert($sectionData);

// Add unit
$unitData = [
    'section_id' => $sectionId,
    'unit_title' => 'Variables and Data Types',
    'unit_desc' => 'Understanding Python variables',
    'video_id' => 'bunny_video_guid',
    'unit_type' => 'video',
    'sort_order' => 1
];

$unitId = $unitsModel->insert($unitData);
```

## Testing Strategy

### Unit Tests
- Course CRUD operations
- Enrollment logic
- Progress calculation
- Access control validation

### Integration Tests
- Complete learning flow
- Payment integration
- Progress tracking
- Certificate generation

### Performance Tests
- Course loading performance
- Video streaming performance
- Database query optimization
- Concurrent user handling

## Monitoring & Logging

### Course Events Logged
- Course creation and modifications
- Student enrollments and completions
- Content access and progress
- System errors and performance issues

### Metrics Tracked
- Course popularity and enrollment rates
- Completion rates and drop-off points
- User engagement and time spent
- System performance and response times

## Future Enhancements

### Planned Features
- **Live Sessions**: Integration with video conferencing
- **Collaborative Learning**: Group projects and discussions
- **Adaptive Learning**: Personalized learning paths
- **Mobile Offline**: Offline content download for mobile

### Advanced Features
- **AI Recommendations**: Intelligent course recommendations
- **Peer Learning**: Student-to-student learning features
- **Advanced Analytics**: Machine learning insights
- **Multi-instructor**: Support for multiple course instructors

## Dependencies

### Required Libraries
- CodeIgniter 4.x framework
- Shield authentication library
- BunnyNet library for video streaming
- FireUploader for file management
- DtTable for admin interfaces

### Optional Integrations
- Payment gateway APIs
- Video conferencing services
- Learning analytics platforms
- Certificate generation services

## Troubleshooting

### Common Issues
1. **Video Not Playing**: Check Bunny.net configuration and tokens
2. **Enrollment Issues**: Verify prerequisites and payment status
3. **Progress Not Saving**: Check progress tracking configuration
4. **Performance Issues**: Review database indexes and caching

### Debug Tools
- Course access logging
- Progress tracking debugging
- Video streaming diagnostics
- Performance profiling tools