# ContactUs Module Technical Specification

## Overview
The ContactUs module provides contact form functionality for the MSARLink e-learning platform, allowing visitors and users to send inquiries, feedback, and support requests to administrators.

## Architecture

### Controllers
- **ContactUs.php**: Main contact form controller for public contact functionality
- **AdminContactUs.php**: Administrative contact message management interface

### Models
- **ContactUsModel.php**: Contact message data management

### Views
- **Site/**: Public contact form templates
- **Admin/**: Administrative message management interface

## Key Features

### 1. Contact Form Management
- **Multi-purpose Forms**: General inquiries, course subscriptions, support requests
- **Form Validation**: Comprehensive input validation and sanitization
- **Spam Protection**: CAPTCHA and rate limiting
- **File Attachments**: Optional file upload for support requests

### 2. Message Processing
- **Automatic Routing**: Route messages to appropriate departments
- **Email Notifications**: Instant email alerts to administrators
- **Auto-responses**: Automated confirmation emails to users
- **Message Threading**: Group related messages together

### 3. Administrative Interface
- **Message Management**: View, respond to, and organize messages
- **Status Tracking**: Mark messages as read, pending, or resolved
- **Response Templates**: Pre-written response templates
- **Bulk Operations**: Handle multiple messages simultaneously

## Database Schema

### contact_us Table
```sql
CREATE TABLE `contact_us` (
    `id` int NOT NULL AUTO_INCREMENT,
    `module_name` varchar(255) DEFAULT NULL,
    `contact_name` varchar(255) DEFAULT NULL,
    `contact_email` varchar(255) DEFAULT NULL,
    `contact_mobile` varchar(25) NOT NULL,
    `send_to` varchar(255) DEFAULT NULL,
    `contact_subject` varchar(255) DEFAULT NULL,
    `contact_message` mediumtext,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `is_read` tinyint(1) NOT NULL DEFAULT '0',
    `study_year` tinyint(1) DEFAULT NULL,
    `selected_course` varchar(50) DEFAULT NULL,
    `status` enum('pending','in_progress','resolved','closed') DEFAULT 'pending',
    `priority` enum('low','medium','high','urgent') DEFAULT 'medium',
    `assigned_to` int DEFAULT NULL,
    `response_count` int DEFAULT 0,
    `last_response_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_status_priority` (`status`, `priority`),
    KEY `idx_created_date` (`created_at`),
    KEY `idx_assigned` (`assigned_to`),
    KEY `idx_module_type` (`module_name`, `selected_course`)
);
```

## API Endpoints

### Public Contact API
```php
// Contact form
GET /contact
POST /contact/submit
GET /contact/success

// Specialized forms
GET /contact/course-inquiry
POST /contact/course-subscription
GET /contact/support

// AJAX endpoints
POST /contact/ajax/validate
GET /contact/ajax/courses
```

### Admin Contact API
```php
// Message management
GET /dt_admin/contact-us
GET /dt_admin/contact-us/view/{id}
POST /dt_admin/contact-us/respond/{id}
POST /dt_admin/contact-us/update-status/{id}

// Bulk operations
POST /dt_admin/contact-us/bulk-read
POST /dt_admin/contact-us/bulk-assign
POST /dt_admin/contact-us/bulk-delete

// Analytics
GET /dt_admin/contact-us/analytics
GET /dt_admin/contact-us/export
```

## Form Types

### 1. General Contact Form
```php
// Basic contact fields
- Name (required)
- Email (required, validated)
- Phone (required)
- Subject (required)
- Message (required)
- Preferred contact method
```

### 2. Course Subscription Form
```php
// Course-specific fields
- Student name (required)
- Parent email (required)
- Phone number (required)
- Study year (required)
- Selected course (required)
- Additional requirements
```

### 3. Support Request Form
```php
// Support-specific fields
- Issue category (required)
- Priority level
- Detailed description (required)
- Screenshots/attachments
- System information
```

## Features Implementation

### Form Validation
```php
// Validation rules
$rules = [
    'contact_name' => 'required|min_length[2]|max_length[100]',
    'contact_email' => 'required|valid_email',
    'contact_mobile' => 'required|regex_match[/^[0-9+\-\s]+$/]',
    'contact_subject' => 'required|min_length[5]|max_length[200]',
    'contact_message' => 'required|min_length[10]|max_length[2000]'
];
```

### Spam Protection
- **Rate Limiting**: Limit submissions per IP address
- **Honeypot Fields**: Hidden fields to catch bots
- **CAPTCHA Integration**: Google reCAPTCHA v3
- **Content Filtering**: Block common spam patterns

### Email Notifications
```php
// Admin notification
$adminEmail = [
    'to' => 'admin@msarlink.com',
    'subject' => 'New Contact Form Submission',
    'template' => 'contact_admin_notification',
    'data' => $contactData
];

// User confirmation
$userEmail = [
    'to' => $contactData['contact_email'],
    'subject' => 'Thank you for contacting us',
    'template' => 'contact_user_confirmation',
    'data' => $contactData
];
```

## Security Features

### Input Security
- **Data Sanitization**: Clean all input data
- **XSS Prevention**: Escape output data
- **SQL Injection Prevention**: Parameterized queries
- **File Upload Security**: Validate file types and sizes

### Privacy Protection
- **Data Encryption**: Encrypt sensitive contact information
- **GDPR Compliance**: Data retention and deletion policies
- **Access Logging**: Log all admin access to contact data
- **Anonymization**: Option to anonymize old contact data

## Integration Points

### Course Module Integration
- **Course Inquiries**: Link contact forms to specific courses
- **Enrollment Assistance**: Help with course enrollment process
- **Course Recommendations**: Suggest appropriate courses

### User Module Integration
- **User Context**: Pre-fill forms for logged-in users
- **Account Issues**: Handle account-related support requests
- **Profile Updates**: Assist with profile modifications

### Notification System
- **Real-time Alerts**: Instant notifications for urgent messages
- **Email Queuing**: Queue email notifications for delivery
- **SMS Integration**: Optional SMS notifications for critical issues

## Administrative Features

### Message Management Dashboard
- **Message Overview**: Summary of all contact messages
- **Status Tracking**: Visual status indicators
- **Priority Sorting**: Sort by priority and urgency
- **Search and Filter**: Find specific messages quickly

### Response Management
- **Quick Responses**: Pre-written response templates
- **Rich Text Editor**: Formatted email responses
- **Attachment Support**: Include files in responses
- **Response Tracking**: Track response times and effectiveness

### Analytics and Reporting
- **Message Volume**: Track contact form usage
- **Response Times**: Monitor support response efficiency
- **Common Issues**: Identify frequently asked questions
- **Satisfaction Metrics**: Track user satisfaction with responses

## Performance Optimization

### Caching Strategy
- **Form Templates**: Cache contact form templates
- **Course Lists**: Cache course options for forms
- **Response Templates**: Cache admin response templates

### Database Optimization
- **Indexed Queries**: Optimize common query patterns
- **Archive Strategy**: Archive old resolved messages
- **Query Optimization**: Efficient message retrieval

## Configuration

### Environment Variables
```env
# Contact Form Settings
CONTACT_ADMIN_EMAIL=admin@msarlink.com
CONTACT_MAX_SUBMISSIONS_PER_HOUR=5
CONTACT_ENABLE_CAPTCHA=true
CONTACT_FILE_UPLOAD_MAX_SIZE=10MB

# Email Settings
CONTACT_AUTO_RESPONSE=true
CONTACT_ADMIN_NOTIFICATION=true
CONTACT_EMAIL_TEMPLATE_PATH=emails/contact

# Security Settings
CONTACT_HONEYPOT_ENABLED=true
CONTACT_RATE_LIMIT_ENABLED=true
CONTACT_SPAM_FILTER_ENABLED=true
```

## Usage Examples

### Submitting a Contact Form
```php
// Form submission
$contactData = [
    'contact_name' => 'Ahmed Ali',
    'contact_email' => 'ahmed@example.com',
    'contact_mobile' => '+966501234567',
    'contact_subject' => 'Course Inquiry',
    'contact_message' => 'I would like to know more about...',
    'module_name' => 'course_inquiry',
    'selected_course' => 'python_basics'
];

$messageId = $contactModel->insert($contactData);
```

### Admin Response
```php
// Send response to contact message
$response = [
    'message_id' => $messageId,
    'response_text' => 'Thank you for your inquiry...',
    'admin_id' => session()->get('user_id'),
    'status' => 'resolved'
];

$contactModel->addResponse($response);
```

## Testing Strategy

### Unit Tests
- Form validation logic
- Email notification sending
- Spam protection mechanisms
- Data sanitization functions

### Integration Tests
- Complete form submission flow
- Admin response functionality
- Email delivery verification
- File upload handling

### Security Tests
- XSS prevention testing
- SQL injection prevention
- Rate limiting effectiveness
- File upload security

## Monitoring & Analytics

### Contact Metrics
- **Submission Volume**: Daily/weekly/monthly submissions
- **Response Times**: Average time to first response
- **Resolution Rates**: Percentage of resolved issues
- **User Satisfaction**: Feedback on support quality

### Performance Monitoring
- **Form Load Times**: Contact form performance
- **Email Delivery**: Email notification success rates
- **Database Performance**: Query execution times

## Future Enhancements

### Planned Features
- **Live Chat Integration**: Real-time chat support
- **Knowledge Base**: Self-service help articles
- **Ticket System**: Advanced support ticket management
- **Multi-language Support**: Forms in multiple languages

### Advanced Features
- **AI-powered Responses**: Automated response suggestions
- **Sentiment Analysis**: Analyze message sentiment
- **Integration APIs**: Connect with external CRM systems
- **Mobile App Support**: Native mobile contact forms

## Dependencies

### Required Libraries
- CodeIgniter 4.x framework
- Shield authentication library
- Email library for notifications
- File upload handling library

### Optional Integrations
- Google reCAPTCHA
- SMS gateway services
- CRM system APIs
- Help desk software

## Troubleshooting

### Common Issues
1. **Emails Not Sending**: Check SMTP configuration and email queue
2. **Form Validation Errors**: Verify validation rules and input data
3. **File Upload Issues**: Check file permissions and size limits
4. **Spam Issues**: Adjust spam protection settings

### Debug Tools
- Contact form submission logging
- Email delivery tracking
- Spam detection logging
- Performance monitoring tools