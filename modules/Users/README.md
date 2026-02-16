# Users Module Technical Specification

## Overview
The Users module provides comprehensive user management functionality for the MSARLink e-learning platform, integrating with CodeIgniter Shield authentication system to handle user registration, authentication, profile management, and user administration.

## Architecture

### Controllers
- **Users.php**: Main users controller for user-facing functionality
- **AdminUsers.php**: Administrative user management interface

### Models
- **UsersModel.php**: Core user data management
- **AuthModel.php**: Authentication and authorization management

### Views
- **Site/**: User profile and account management interfaces
- **Admin/**: Administrative user management interface

## Key Features

### 1. User Registration & Authentication
- **Registration Flow**: Multi-step registration with email verification
- **Shield Integration**: Full integration with CodeIgniter Shield
- **Social Login**: Optional social media authentication
- **Two-Factor Authentication**: Enhanced security with 2FA

### 2. Profile Management
- **User Profiles**: Comprehensive user profile management
- **Avatar Upload**: Profile picture upload and management
- **Academic Information**: Student academic year and course preferences
- **Parent Information**: Parent/guardian contact details

### 3. User Administration
- **User Management**: Complete user administration interface
- **Role Assignment**: User role and permission management
- **Bulk Operations**: Mass user operations and imports
- **User Analytics**: User behavior and engagement analytics

## Database Schema

### users Table (Shield Integration)
```sql
CREATE TABLE `users` (
    `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` varchar(30) DEFAULT NULL,
    `status` varchar(255) DEFAULT NULL,
    `status_message` varchar(255) DEFAULT NULL,
    `active` tinyint(1) NOT NULL DEFAULT 0,
    `last_active` datetime DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    `deleted_at` datetime DEFAULT NULL,
    `full_name` varchar(100) DEFAULT NULL,
    `phone` varchar(25) DEFAULT NULL,
    `academic_year` enum('first_secondary','second_secondary','third_secondary') DEFAULT NULL,
    `parent_name` varchar(100) DEFAULT NULL,
    `parent_email` varchar(255) DEFAULT NULL,
    `parent_phone` varchar(25) DEFAULT NULL,
    `avatar` varchar(255) DEFAULT NULL,
    `bio` text,
    `date_of_birth` date DEFAULT NULL,
    `gender` enum('male','female') DEFAULT NULL,
    `city` varchar(100) DEFAULT NULL,
    `country` varchar(100) DEFAULT 'Saudi Arabia',
    `timezone` varchar(50) DEFAULT 'Asia/Riyadh',
    `language` varchar(10) DEFAULT 'ar',
    `email_notifications` tinyint(1) DEFAULT 1,
    `sms_notifications` tinyint(1) DEFAULT 0,
    `marketing_emails` tinyint(1) DEFAULT 1,
    `last_login_at` datetime DEFAULT NULL,
    `last_login_ip` varchar(45) DEFAULT NULL,
    `login_count` int DEFAULT 0,
    `verification_token` varchar(255) DEFAULT NULL,
    `verified_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` (`username`),
    KEY `idx_active` (`active`),
    KEY `idx_academic_year` (`academic_year`),
    KEY `idx_last_active` (`last_active`),
    KEY `idx_created_date` (`created_at`)
);
```

### auth_identities Table (Shield Integration)
```sql
CREATE TABLE `auth_identities` (
    `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` int UNSIGNED NOT NULL,
    `type` varchar(255) NOT NULL,
    `name` varchar(255) DEFAULT NULL,
    `secret` varchar(255) NOT NULL,
    `secret2` varchar(255) DEFAULT NULL,
    `expires` datetime DEFAULT NULL,
    `extra` text,
    `force_reset` tinyint(1) NOT NULL DEFAULT 0,
    `last_used_at` datetime DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `type_secret` (`type`, `secret`),
    KEY `user_id` (`user_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);
```

## API Endpoints

### Public User API
```php
// Authentication
POST /auth/register
POST /auth/login
POST /auth/logout
POST /auth/forgot-password
POST /auth/reset-password
GET /auth/verify/{token}

// Profile management
GET /profile
POST /profile/update
POST /profile/upload-avatar
POST /profile/change-password

// Account settings
GET /account/settings
POST /account/update-preferences
POST /account/deactivate
```

### Admin User API
```php
// User management
GET /dt_admin/users
POST /dt_admin/users/create
GET /dt_admin/users/edit/{id}
POST /dt_admin/users/update/{id}
POST /dt_admin/users/delete/{id}

// User operations
POST /dt_admin/users/activate/{id}
POST /dt_admin/users/deactivate/{id}
POST /dt_admin/users/reset-password/{id}
POST /dt_admin/users/send-verification/{id}

// Bulk operations
POST /dt_admin/users/bulk-import
POST /dt_admin/users/bulk-activate
POST /dt_admin/users/bulk-delete

// Analytics
GET /dt_admin/users/analytics
GET /dt_admin/users/export
```

## User Registration Flow

### Registration Process
```php
// Multi-step registration
1. Basic Information Collection
   - Full name (full_name)
   - Email address
   - Phone number
   - Academic year
   
2. Parent/Guardian Information
   - Parent full name
   - Parent email
   - Parent phone
   
3. Account Security
   - Username selection
   - Password creation
   - Terms acceptance
   
4. Email Verification
   - Send verification email
   - Token-based verification
   - Account activation
   
5. Welcome Process
   - Free trial credits (1000 points)
   - Welcome email
   - Profile completion
```

### Registration Implementation
```php
// Handle user registration
public function register() {
    if ($this->request->getMethod() === 'POST') {
        $rules = [
            'full_name' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email|is_unique[auth_identities.secret]',
            'phone' => 'required|regex_match[/^[0-9+\-\s]+$/]',
            'academic_year' => 'required|in_list[first_secondary,second_secondary,third_secondary]',
            'parent_name' => 'required|min_length[2]|max_length[100]',
            'parent_email' => 'required|valid_email',
            'parent_phone' => 'required|regex_match[/^[0-9+\-\s]+$/]',
            'username' => 'required|min_length[3]|max_length[30]|is_unique[users.username]|alpha_numeric',
            'password' => 'required|min_length[8]|strong_password',
            'password_confirm' => 'required|matches[password]',
            'terms_accepted' => 'required|in_list[1]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $userData = [
            'username' => $this->request->getPost('username'),
            'full_name' => $this->request->getPost('full_name'),
            'phone' => $this->request->getPost('phone'),
            'academic_year' => $this->request->getPost('academic_year'),
            'parent_name' => $this->request->getPost('parent_name'),
            'parent_email' => $this->request->getPost('parent_email'),
            'parent_phone' => $this->request->getPost('parent_phone'),
            'active' => 0, // Requires email verification
            'verification_token' => bin2hex(random_bytes(32))
        ];
        
        // Create user with Shield
        $users = auth()->getProvider();
        $user = new User($userData);
        $users->save($user);
        
        // Create email identity
        $users->createEmailIdentity($user, [
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password')
        ]);
        
        // Send verification email
        $this->sendVerificationEmail($user);
        
        // Add to default user group
        $user->addToGroup('student');
        
        return redirect()->to('/auth/verify-email-sent')
                        ->with('success', 'Registration successful! Please check your email to verify your account.');
    }
    
    return view('Modules\\Users\\Views\\Site\\register');
}
```

## User Profile Management

### Profile Update
```php
// Update user profile
public function updateProfile() {
    $userId = auth()->id();
    $user = auth()->user();
    
    if ($this->request->getMethod() === 'POST') {
        $rules = [
            'full_name' => 'required|min_length[2]|max_length[100]',
            'phone' => 'required|regex_match[/^[0-9+\-\s]+$/]',
            'bio' => 'permit_empty|max_length[500]',
            'date_of_birth' => 'permit_empty|valid_date',
            'city' => 'permit_empty|max_length[100]',
            'timezone' => 'permit_empty|max_length[50]',
            'language' => 'permit_empty|in_list[ar,en]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $updateData = [
            'full_name' => $this->request->getPost('full_name'),
            'phone' => $this->request->getPost('phone'),
            'bio' => $this->request->getPost('bio'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
            'city' => $this->request->getPost('city'),
            'timezone' => $this->request->getPost('timezone'),
            'language' => $this->request->getPost('language'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->usersModel->update($userId, $updateData)) {
            return redirect()->back()->with('success', 'Profile updated successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to update profile');
        }
    }
    
    $data = [
        'title' => 'My Profile',
        'user' => $user
    ];
    
    return view('Modules\\Users\\Views\\Site\\profile', $data);
}
```

### Avatar Upload
```php
// Handle avatar upload
public function uploadAvatar() {
    if (!$this->request->isAJAX()) {
        return redirect()->back();
    }
    
    $userId = auth()->id();
    $file = $this->request->getFile('avatar');
    
    if (!$file || !$file->isValid()) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Please select a valid image file'
        ]);
    }
    
    // Validate file type and size
    if (!in_array($file->getClientExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Only JPG, PNG, and GIF files are allowed'
        ]);
    }
    
    if ($file->getSize() > 2 * 1024 * 1024) { // 2MB limit
        return $this->response->setJSON([
            'success' => false,
            'message' => 'File size must be less than 2MB'
        ]);
    }
    
    // Generate unique filename
    $fileName = 'avatar_' . $userId . '_' . time() . '.' . $file->getClientExtension();
    $uploadPath = WRITEPATH . 'uploads/avatars/';
    
    // Create directory if it doesn't exist
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0755, true);
    }
    
    // Move uploaded file
    if ($file->move($uploadPath, $fileName)) {
        // Update user avatar
        $avatarUrl = '/writable/uploads/avatars/' . $fileName;
        
        if ($this->usersModel->update($userId, ['avatar' => $avatarUrl])) {
            // Delete old avatar if exists
            $user = $this->usersModel->find($userId);
            if ($user->avatar && file_exists(WRITEPATH . 'uploads/avatars/' . basename($user->avatar))) {
                unlink(WRITEPATH . 'uploads/avatars/' . basename($user->avatar));
            }
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Avatar updated successfully',
                'avatar_url' => base_url($avatarUrl)
            ]);
        }
    }
    
    return $this->response->setJSON([
        'success' => false,
        'message' => 'Failed to upload avatar'
    ]);
}
```

## Security Features

### Authentication Security
- **Shield Integration**: Leverages CodeIgniter Shield security features
- **Password Hashing**: Secure password hashing with bcrypt
- **Session Management**: Secure session handling
- **CSRF Protection**: Cross-site request forgery protection

### Account Security
- **Email Verification**: Required email verification for new accounts
- **Password Strength**: Enforced strong password requirements
- **Account Lockout**: Automatic lockout after failed login attempts
- **Two-Factor Authentication**: Optional 2FA for enhanced security

### Data Protection
- **Input Validation**: Comprehensive input validation
- **XSS Prevention**: Output escaping and sanitization
- **SQL Injection Prevention**: Parameterized queries
- **Privacy Controls**: User privacy and data protection settings

## Integration Points

### Course Module Integration
```php
// Get user's enrolled courses
public function getUserCourses($userId) {
    return $this->db->table('tb_unit_enrollments e')
                   ->select('e.*, c.course_title, c.slug, c.image')
                   ->join('tb_courses c', 'c.id = e.course_id')
                   ->where('e.user_id', $userId)
                   ->where('e.status', 'active')
                   ->orderBy('e.enrolled_at', 'DESC')
                   ->get()
                   ->getResultArray();
}
```

### Billing Module Integration
```php
// Get user's credit balance
public function getUserBalance($userId) {
    $totalCredits = $this->db->table('tb_credit_transactions')
                            ->where('user_id', $userId)
                            ->where('transaction_type', 'purchase')
                            ->where('status', 'completed')
                            ->selectSum('amount')
                            ->get()
                            ->getRow()
                            ->amount ?? 0;
    
    $spentCredits = $this->db->table('tb_credit_transactions')
                            ->where('user_id', $userId)
                            ->where('transaction_type', 'spend')
                            ->where('status', 'completed')
                            ->selectSum('amount')
                            ->get()
                            ->getRow()
                            ->amount ?? 0;
    
    return $totalCredits - $spentCredits;
}
```

### Progress Module Integration
```php
// Get user's learning progress
public function getUserProgress($userId) {
    return $this->db->table('tb_user_item_progress p')
                   ->select('p.*, u.unit_title, s.section_title, c.course_title')
                   ->join('tb_units u', 'u.id = p.unit_id')
                   ->join('tb_sections s', 's.id = u.section_id')
                   ->join('tb_courses c', 'c.id = s.course_id')
                   ->where('p.user_id', $userId)
                   ->orderBy('p.updated_at', 'DESC')
                   ->get()
                   ->getResultArray();
}
```

## Performance Optimization

### Caching Strategy
- **User Data**: Cache user profile data for 30 minutes
- **User Permissions**: Cache user permissions for 15 minutes
- **User Statistics**: Cache user statistics for 1 hour

### Database Optimization
- **Indexed Queries**: Optimize user lookup queries
- **Query Optimization**: Efficient user data retrieval
- **Pagination**: Large user lists paginated for performance

## User Analytics

### User Metrics
```php
// Get user analytics
public function getUserAnalytics($dateRange = null) {
    $builder = $this->builder();
    
    if ($dateRange) {
        $builder->where('created_at >=', $dateRange['start'])
                ->where('created_at <=', $dateRange['end']);
    }
    
    return [
        'total_users' => $builder->countAllResults(false),
        'active_users' => $builder->where('active', 1)->countAllResults(false),
        'verified_users' => $builder->where('verified_at IS NOT NULL')->countAllResults(false),
        'users_by_academic_year' => $this->getUsersByAcademicYear($dateRange),
        'recent_registrations' => $this->getRecentRegistrations(30),
        'user_engagement' => $this->getUserEngagementMetrics($dateRange)
    ];
}
```

## Configuration

### Environment Variables
```env
# User Settings
USER_REGISTRATION_ENABLED=true
USER_EMAIL_VERIFICATION_REQUIRED=true
USER_AUTO_ACTIVATE=false
USER_DEFAULT_GROUP=student

# Profile Settings
USER_AVATAR_MAX_SIZE=2MB
USER_AVATAR_ALLOWED_TYPES=jpg,jpeg,png,gif
USER_PROFILE_COMPLETION_REQUIRED=false

# Security Settings
USER_PASSWORD_MIN_LENGTH=8
USER_STRONG_PASSWORD_REQUIRED=true
USER_2FA_ENABLED=false
USER_SESSION_TIMEOUT=3600
```

## Usage Examples

### User Registration
```php
// Register new user
$userData = [
    'username' => 'ahmed_student',
    'full_name' => 'Ahmed Ali',
    'email' => 'ahmed@example.com',
    'phone' => '+966501234567',
    'academic_year' => 'second_secondary',
    'parent_name' => 'Ali Ahmed',
    'parent_email' => 'ali@example.com',
    'parent_phone' => '+966501234568'
];

$userId = $usersModel->createUser($userData, 'password123');
```

### User Authentication
```php
// Login user
$credentials = [
    'email' => 'ahmed@example.com',
    'password' => 'password123'
];

$result = auth()->attempt($credentials);

if ($result->isOK()) {
    return redirect()->to('/dashboard');
} else {
    return redirect()->back()->with('error', 'Invalid credentials');
}
```

### Profile Management
```php
// Get user profile
$user = auth()->user();
$profile = $usersModel->getUserProfile($user->id);

// Update profile
$updateData = [
    'bio' => 'Computer science student',
    'city' => 'Riyadh',
    'timezone' => 'Asia/Riyadh'
];

$usersModel->updateProfile($user->id, $updateData);
```

## Testing Strategy

### Unit Tests
- User CRUD operations
- Authentication flows
- Profile management
- Validation logic

### Integration Tests
- Shield integration
- Module integrations
- Email verification
- Security features

### Security Tests
- Authentication bypass attempts
- Password security
- Session management
- Access control validation

## Monitoring & Logging

### User Events Logged
- User registration and activation
- Login and logout events
- Profile updates and changes
- Security-related events

### Security Monitoring
- Failed login attempts
- Suspicious user activities
- Account lockouts and unlocks
- Password reset requests

## Future Enhancements

### Planned Features
- **Social Login**: Integration with social media platforms
- **Advanced 2FA**: Multiple 2FA options (SMS, authenticator apps)
- **User Preferences**: Advanced user preference management
- **Account Recovery**: Enhanced account recovery options

### Advanced Features
- **Single Sign-On**: SSO integration for enterprise
- **User Analytics**: Advanced user behavior analytics
- **Personalization**: AI-powered user experience personalization
- **Mobile App Integration**: Native mobile app user management

## Dependencies

### Required Libraries
- CodeIgniter 4.x framework
- CodeIgniter Shield authentication
- Email library for notifications
- File upload handling library

### Optional Integrations
- Social media APIs
- SMS gateway services
- Analytics platforms
- CRM systems

## Troubleshooting

### Common Issues
1. **Registration Failures**: Check validation rules and database constraints
2. **Login Issues**: Verify Shield configuration and user status
3. **Email Verification**: Check email configuration and delivery
4. **Profile Updates**: Verify permissions and validation rules

### Debug Tools
- User authentication debugger
- Profile update logging
- Email delivery tracking
- Performance monitoring tools
