# Enrollments Module

This module handles unit-based enrollments for the MSARLink e-learning platform. The system has been redesigned to focus on individual unit purchases rather than full course enrollments.

## Features

### Unit Enrollments (Primary System)
- Individual unit purchase and enrollment system
- Multiple unit selection and purchase in single transaction
- Payment proof upload and verification
- Manual admin activation after payment verification
- Unit-based progress tracking
- Simple payment workflow without credit system

### Legacy Course Support
- Backward compatibility for existing course-based data
- Migration support from course enrollments to unit enrollments

## Architecture

### Controllers
- **Enrollments.php**: Main enrollments controller for student enrollment functionality
- **AdminEnrollments.php**: Administrative enrollment management interface

### Models
- **EnrollmentsModel.php**: Legacy course enrollment data management (deprecated)
- **UnitEnrollmentsModel.php**: Primary unit enrollment data management
- **UnitsModel.php**: Unit information and management

### Views
- **Site/**: Student enrollment interfaces
- **Admin/**: Administrative enrollment management interface

## Key Features

### 1. Unit Enrollment Management
- **Unit Purchase**: Individual unit purchase and enrollment
- **Multiple Unit Selection**: Purchase multiple units in single transaction
- **Enrollment Status**: Pending, approved, rejected, completed statuses
- **Enrollment History**: Complete unit enrollment tracking and history
- **Admin Approval**: Manual verification and activation system

### 2. Payment & Verification
- **Payment Proof Upload**: Image/document upload for payment verification
- **Manual Verification**: Admin review and approval process
- **Unit Access Control**: Access granted only after admin approval
- **Payment Tracking**: Complete payment audit trail

### 3. Analytics & Reporting
- **Unit Enrollment Statistics**: Track unit purchase trends and patterns
- **Revenue Analytics**: Track unit-based revenue and payments
- **Student Analytics**: Individual student unit enrollment patterns
- **Admin Dashboard**: Comprehensive enrollment management interface

## Database Schema

### tb_unit_enrollments Table (Primary)
```sql
CREATE TABLE `tb_unit_enrollments` (
    `id` int NOT NULL AUTO_INCREMENT,
    `user_id` int NOT NULL,
    `unit_id` int NOT NULL,
    `amount` decimal(10,2) NOT NULL,
    `payment_proof` varchar(255) DEFAULT NULL,
    `enrollment_status` enum('pending','approved','rejected','completed') DEFAULT 'pending',
    `enrolled_at` datetime DEFAULT CURRENT_TIMESTAMP,
    `approved_at` datetime DEFAULT NULL,
    `completed_at` datetime DEFAULT NULL,
    `notes` text,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `user_unit_unique` (`user_id`, `unit_id`),
    KEY `idx_user_status` (`user_id`, `enrollment_status`),
    KEY `idx_unit_status` (`unit_id`, `enrollment_status`),
    KEY `idx_enrolled_date` (`enrolled_at`),
    KEY `idx_status_date` (`enrollment_status`, `enrolled_at`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`unit_id`) REFERENCES `tb_units` (`id`) ON DELETE CASCADE
);
```

### tb_enrollments Table (Deprecated)
```sql
CREATE TABLE `tb_enrollments` (
    `id` int NOT NULL AUTO_INCREMENT,
    `user_id` int NOT NULL,
    `course_id` int NOT NULL,
    `enrolled_at` datetime DEFAULT CURRENT_TIMESTAMP,
    `status` enum('active','completed','cancelled','suspended') DEFAULT 'active',
    `completed_at` datetime DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `proof_image` varchar(100) DEFAULT NULL,
    `credits_used` decimal(10,2) DEFAULT NULL,
    `enrollment_method` enum('manual','automatic','bulk','api') DEFAULT 'manual',
    `progress_percentage` decimal(5,2) DEFAULT 0.00,
    `last_accessed_at` datetime DEFAULT NULL,
    `certificate_issued` tinyint(1) DEFAULT 0,
    `certificate_issued_at` datetime DEFAULT NULL,
    `notes` text,
    PRIMARY KEY (`id`),
    UNIQUE KEY `user_course_unique` (`user_id`, `course_id`),
    KEY `idx_user_status` (`user_id`, `status`),
    KEY `idx_course_status` (`course_id`, `status`),
    KEY `idx_enrolled_date` (`enrolled_at`),
    KEY `idx_completion` (`status`, `completed_at`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`course_id`) REFERENCES `tb_courses` (`id`) ON DELETE CASCADE
);
```

## API Endpoints

### Student Unit Enrollment API
```php
// Unit enrollment actions
GET /enrollments/units-shop
POST /enrollments/purchase-units
GET /enrollments/my-purchases
GET /enrollments/unit-status/{enrollment_id}

// Legacy course enrollment (deprecated)
POST /checkout/{course_id}
GET /enrollments/my-enrollments
```

### Admin Unit Enrollment API
```php
// Unit enrollment management
GET /dt_admin/enrollments/units
GET /dt_admin/enrollments/units/show/{id}
POST /dt_admin/enrollments/units/approve/{id}
POST /dt_admin/enrollments/units/reject/{id}
GET /dt_admin/enrollments/units/stats
GET /dt_admin/enrollments/units/pending-count

// Legacy enrollment management (deprecated)
GET /dt_admin/enrollments
POST /dt_admin/enrollments/add
GET /dt_admin/enrollments/edit/{id}
POST /dt_admin/enrollments/update/{id}
POST /dt_admin/enrollments/delete/{id}
```

## Enrollment Process Flow

### Standard Enrollment Flow
```php
1. Student selects course
2. System checks prerequisites
3. System verifies payment/credits
4. System checks course capacity
5. Enrollment created with 'active' status
6. Welcome email sent to student
7. Course access granted
8. Progress tracking initiated
```

### Prerequisites Verification
```php
public function checkPrerequisites($userId, $courseId) {
    $course = $this->coursesModel->find($courseId);
    $prerequisites = json_decode($course->prerequisites, true) ?? [];
    
    foreach ($prerequisites as $prereqCourseId) {
        $enrollment = $this->where('user_id', $userId)
                          ->where('course_id', $prereqCourseId)
                          ->where('status', 'completed')
                          ->first();
        
        if (!$enrollment) {
            return false;
        }
    }
    
    return true;
}
```

### Payment Verification
```php
public function verifyPayment($userId, $courseId) {
    $course = $this->coursesModel->find($courseId);
    
    if ($course->is_free) {
        return true;
    }
    
    // Check user credit balance
    $userBalance = $this->billingModel->getUserBalance($userId);
    
    if ($userBalance >= $course->price) {
        // Deduct credits
        $this->billingModel->deductCredits($userId, $course->price, 
            'Course enrollment: ' . $course->course_title);
        return true;
    }
    
    return false;
}
```

## Features Implementation

### Enrollment Status Management
```php
// Update enrollment status
public function updateStatus($enrollmentId, $status, $notes = null) {
    $data = [
        'status' => $status,
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    if ($status === 'completed') {
        $data['completed_at'] = date('Y-m-d H:i:s');
        $data['progress_percentage'] = 100.00;
    }
    
    if ($notes) {
        $data['notes'] = $notes;
    }
    
    return $this->update($enrollmentId, $data);
}
```

### Bulk Enrollment Operations
```php
// Bulk enroll users in course
public function bulkEnroll($userIds, $courseId, $method = 'bulk') {
    $enrollments = [];
    $timestamp = date('Y-m-d H:i:s');
    
    foreach ($userIds as $userId) {
        // Check if already enrolled
        if (!$this->isEnrolled($userId, $courseId)) {
            $enrollments[] = [
                'user_id' => $userId,
                'course_id' => $courseId,
                'enrolled_at' => $timestamp,
                'status' => 'active',
                'enrollment_method' => $method
            ];
        }
    }
    
    return $this->insertBatch($enrollments);
}
```

### Enrollment Analytics
```php
// Get enrollment statistics
public function getEnrollmentStats($courseId = null, $dateRange = null) {
    $builder = $this->builder();
    
    if ($courseId) {
        $builder->where('course_id', $courseId);
    }
    
    if ($dateRange) {
        $builder->where('enrolled_at >=', $dateRange['start'])
                ->where('enrolled_at <=', $dateRange['end']);
    }
    
    return [
        'total_enrollments' => $builder->countAllResults(false),
        'active_enrollments' => $builder->where('status', 'active')->countAllResults(false),
        'completed_enrollments' => $builder->where('status', 'completed')->countAllResults(false),
        'cancelled_enrollments' => $builder->where('status', 'cancelled')->countAllResults(false)
    ];
}
```

## Security Features

### Access Control
- **User Verification**: Verify user identity before enrollment
- **Course Access**: Ensure only enrolled users can access course content
- **Admin Authorization**: Restrict admin functions to authorized users
- **Data Privacy**: Protect student enrollment information

### Data Protection
- **Input Validation**: Validate all enrollment data
- **SQL Injection Prevention**: Use parameterized queries
- **XSS Protection**: Escape output data
- **Audit Trail**: Log all enrollment changes

## Integration Points

### Course Module Integration
```php
// Check course availability
public function isCourseAvailable($courseId) {
    $course = $this->coursesModel->find($courseId);
    
    return $course && $course->active && 
           !$course->waiting_list && 
           $this->getEnrollmentCount($courseId) < $course->max_students;
}
```

### Billing Module Integration
```php
// Process enrollment payment
public function processEnrollmentPayment($userId, $courseId) {
    $course = $this->coursesModel->find($courseId);
    
    if ($course->is_free) {
        return ['success' => true, 'message' => 'Free course enrollment'];
    }
    
    return $this->billingModel->processPayment($userId, $course->price, 
        'course_enrollment', $courseId);
}
```

### Progress Module Integration
```php
// Initialize progress tracking
public function initializeProgress($enrollmentId) {
    $enrollment = $this->find($enrollmentId);
    
    return $this->progressModel->initializeCourseProgress(
        $enrollment->user_id, 
        $enrollment->course_id
    );
}
```

### Notification System Integration
```php
// Send enrollment notifications
public function sendEnrollmentNotifications($enrollmentId) {
    $enrollment = $this->getEnrollmentWithDetails($enrollmentId);
    
    // Student notification
    $this->emailService->sendEnrollmentConfirmation($enrollment);
    
    // Parent notification (if parent email exists)
    if ($enrollment->parent_email) {
        $this->emailService->sendParentEnrollmentNotification($enrollment);
    }
    
    // Admin notification (for paid courses)
    if (!$enrollment->is_free) {
        $this->emailService->sendAdminEnrollmentNotification($enrollment);
    }
}
```

## Performance Optimization

### Caching Strategy
- **Enrollment Status**: Cache user enrollment status for 10 minutes
- **Course Capacity**: Cache course enrollment counts for 5 minutes
- **User Enrollments**: Cache user's active enrollments for 15 minutes

### Database Optimization
- **Composite Indexes**: Optimize for user-course queries
- **Status Indexes**: Optimize status-based queries
- **Date Indexes**: Optimize date range queries
- **Query Optimization**: Efficient joins with courses and users tables

## Analytics Features

### Enrollment Metrics
- **Enrollment Trends**: Track enrollment patterns over time
- **Course Popularity**: Identify most popular courses
- **Completion Rates**: Monitor course completion statistics
- **Revenue Analysis**: Track enrollment-based revenue

### Student Analytics
- **Enrollment History**: Complete student enrollment timeline
- **Learning Patterns**: Analyze student learning behavior
- **Success Metrics**: Track student success rates
- **Engagement Levels**: Monitor student course engagement

## Configuration

### Environment Variables
```env
# Enrollment Settings
ENROLLMENT_AUTO_APPROVE=true
ENROLLMENT_EMAIL_NOTIFICATIONS=true
ENROLLMENT_WAITING_LIST_ENABLED=true
ENROLLMENT_MAX_CONCURRENT=10

# Payment Settings
ENROLLMENT_REQUIRE_PAYMENT=true
ENROLLMENT_REFUND_PERIOD_DAYS=7
ENROLLMENT_PARTIAL_REFUND=true

# Analytics Settings
ENROLLMENT_TRACK_ANALYTICS=true
ENROLLMENT_ANALYTICS_RETENTION_DAYS=365
```

## Usage Examples

### Student Enrollment
```php
// Enroll student in course
$enrollmentData = [
    'user_id' => $userId,
    'course_id' => $courseId,
    'enrollment_method' => 'manual',
    'status' => 'active'
];

$enrollmentId = $enrollmentsModel->insert($enrollmentData);
```

### Check Enrollment Status
```php
// Check if user is enrolled in course
$isEnrolled = $enrollmentsModel->isEnrolled($userId, $courseId);

// Get enrollment details
$enrollment = $enrollmentsModel->getUserCourseEnrollment($userId, $courseId);
```

### Bulk Operations
```php
// Bulk enroll users
$userIds = [1, 2, 3, 4, 5];
$courseId = 10;
$result = $enrollmentsModel->bulkEnroll($userIds, $courseId);

// Bulk update status
$enrollmentIds = [1, 2, 3];
$newStatus = 'completed';
$result = $enrollmentsModel->bulkUpdateStatus($enrollmentIds, $newStatus);
```

## Testing Strategy

### Unit Tests
- Enrollment creation and validation
- Prerequisites checking logic
- Payment verification
- Status update functionality

### Integration Tests
- Complete enrollment flow
- Payment processing integration
- Notification system integration
- Progress tracking initialization

### Performance Tests
- Bulk enrollment operations
- Database query performance
- Concurrent enrollment handling
- Analytics query optimization

## Monitoring & Logging

### Enrollment Events Logged
- New enrollments and cancellations
- Status changes and updates
- Payment processing events
- Bulk operation results

### Metrics Tracked
- Enrollment conversion rates
- Course completion rates
- Revenue per enrollment
- Student retention rates

## Future Enhancements

### Planned Features
- **Flexible Payment Plans**: Installment payment options
- **Group Enrollments**: Corporate and group enrollment discounts
- **Automatic Enrollment**: Rule-based automatic enrollments
- **Advanced Analytics**: Predictive enrollment analytics

### Integration Enhancements
- **CRM Integration**: Connect with customer relationship management
- **Marketing Automation**: Automated enrollment campaigns
- **Mobile App Support**: Native mobile enrollment features
- **API Enhancements**: RESTful API for third-party integrations

## Dependencies

### Required Libraries
- CodeIgniter 4.x framework
- Shield authentication library
- Email service for notifications
- Billing module for payment processing

### Optional Integrations
- Payment gateway APIs
- CRM system APIs
- Marketing automation tools
- Analytics platforms

## Troubleshooting

### Common Issues
1. **Enrollment Failures**: Check prerequisites and payment status
2. **Duplicate Enrollments**: Verify unique constraints
3. **Payment Issues**: Check billing module integration
4. **Notification Problems**: Verify email configuration

### Debug Tools
- Enrollment process logging
- Payment transaction tracking
- Email delivery monitoring
- Performance profiling tools
