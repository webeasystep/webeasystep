# Settings Module Technical Specification

## Overview
The Settings module provides comprehensive system configuration management for the MSARLink e-learning platform, allowing administrators to configure various aspects of the system through a centralized interface.

## Architecture

### Controllers
- **Settings.php**: Main settings controller for configuration management
- **AdminSettings.php**: Administrative settings management interface

### Models
- **SettingsModel.php**: Core settings data management

### Views
- **Site/**: Public settings interfaces (if applicable)
- **Admin/**: Administrative settings management interface

## Key Features

### 1. System Configuration
- **General Settings**: Site name, description, contact information
- **Email Settings**: SMTP configuration and email templates
- **Payment Settings**: Payment gateway configuration
- **Security Settings**: Authentication and security parameters

### 2. Module Settings
- **Course Settings**: Course-related configuration options
- **User Settings**: User registration and profile settings
- **Notification Settings**: Email and SMS notification preferences
- **Analytics Settings**: Tracking and analytics configuration

### 3. Advanced Configuration
- **Environment Settings**: Development/production configurations
- **Cache Settings**: Caching system configuration
- **API Settings**: API keys and external service configuration
- **Backup Settings**: Automated backup configuration

## Database Schema

### settings Table
```sql
CREATE TABLE `settings` (
    `id` int NOT NULL AUTO_INCREMENT,
    `setting_key` varchar(255) NOT NULL,
    `setting_value` longtext,
    `setting_type` enum('string','integer','boolean','json','text','email','url','password') DEFAULT 'string',
    `category` varchar(100) DEFAULT 'general',
    `description` text,
    `is_public` tinyint(1) DEFAULT 0,
    `is_required` tinyint(1) DEFAULT 0,
    `validation_rules` text,
    `default_value` text,
    `options` json DEFAULT NULL,
    `sort_order` int DEFAULT 0,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `updated_by` int DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `setting_key` (`setting_key`),
    KEY `idx_category` (`category`),
    KEY `idx_public` (`is_public`),
    KEY `idx_sort` (`category`, `sort_order`),
    FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
);
```

## API Endpoints

### Public Settings API
```php
// Public settings (limited)
GET /settings/public
GET /settings/contact-info
GET /settings/site-info
```

### Admin Settings API
```php
// Settings management
GET /dt_admin/settings
GET /dt_admin/settings/category/{category}
POST /dt_admin/settings/update
POST /dt_admin/settings/bulk-update

// Setting operations
POST /dt_admin/settings/create
GET /dt_admin/settings/edit/{key}
POST /dt_admin/settings/delete/{key}

// Configuration management
GET /dt_admin/settings/export
POST /dt_admin/settings/import
POST /dt_admin/settings/reset-defaults
GET /dt_admin/settings/backup
```

## Default Settings

### System Settings
```php
// Default system settings
$defaultSettings = [
    // General Settings
    'site_name' => [
        'value' => 'MSARLink E-Learning Platform',
        'type' => 'string',
        'category' => 'general',
        'description' => 'The name of your website',
        'is_public' => 1,
        'is_required' => 1
    ],
    'site_description' => [
        'value' => 'Advanced E-Learning Platform for High School Students',
        'type' => 'text',
        'category' => 'general',
        'description' => 'Brief description of your website',
        'is_public' => 1
    ],
    'site_logo' => [
        'value' => '/assets/images/logo.png',
        'type' => 'url',
        'category' => 'general',
        'description' => 'Site logo URL'
    ],
    
    // Contact Settings
    'contact_email' => [
        'value' => 'info@msarlink.com',
        'type' => 'email',
        'category' => 'contact',
        'description' => 'Main contact email address',
        'is_public' => 1,
        'is_required' => 1
    ],
    'contact_phone' => [
        'value' => '+966501234567',
        'type' => 'string',
        'category' => 'contact',
        'description' => 'Main contact phone number',
        'is_public' => 1
    ],
    
    // Email Settings
    'smtp_host' => [
        'value' => 'smtp.gmail.com',
        'type' => 'string',
        'category' => 'email',
        'description' => 'SMTP server hostname'
    ],
    'smtp_port' => [
        'value' => '587',
        'type' => 'integer',
        'category' => 'email',
        'description' => 'SMTP server port'
    ],
    'smtp_username' => [
        'value' => '',
        'type' => 'string',
        'category' => 'email',
        'description' => 'SMTP username'
    ],
    'smtp_password' => [
        'value' => '',
        'type' => 'password',
        'category' => 'email',
        'description' => 'SMTP password'
    ],
    
    // Course Settings
    'default_course_price' => [
        'value' => '299.00',
        'type' => 'string',
        'category' => 'courses',
        'description' => 'Default price for new courses'
    ],
    'free_trial_credits' => [
        'value' => '1000',
        'type' => 'integer',
        'category' => 'courses',
        'description' => 'Free trial credits for new users'
    ],
    
    // Security Settings
    'enable_registration' => [
        'value' => '1',
        'type' => 'boolean',
        'category' => 'security',
        'description' => 'Allow new user registration'
    ],
    'require_email_verification' => [
        'value' => '1',
        'type' => 'boolean',
        'category' => 'security',
        'description' => 'Require email verification for new accounts'
    ]
];
```

## Features Implementation

### Settings Management
```php
// Get setting value
public function getSetting($key, $default = null) {
    // Check cache first
    $cacheKey = 'setting_' . $key;
    $cached = cache($cacheKey);
    
    if ($cached !== null) {
        return $cached;
    }
    
    // Get from database
    $setting = $this->where('setting_key', $key)->first();
    
    if ($setting) {
        $value = $this->castSettingValue($setting->setting_value, $setting->setting_type);
        
        // Cache for 1 hour
        cache()->save($cacheKey, $value, 3600);
        
        return $value;
    }
    
    return $default;
}

// Set setting value
public function setSetting($key, $value, $userId = null) {
    $setting = $this->where('setting_key', $key)->first();
    
    if (!$setting) {
        return ['success' => false, 'message' => 'Setting not found'];
    }
    
    // Validate setting value
    if (!$this->validateSettingValue($value, $setting)) {
        return ['success' => false, 'message' => 'Invalid setting value'];
    }
    
    // Update setting
    $updateData = [
        'setting_value' => $this->prepareSettingValue($value, $setting->setting_type),
        'updated_at' => date('Y-m-d H:i:s'),
        'updated_by' => $userId
    ];
    
    $result = $this->update($setting->id, $updateData);
    
    if ($result) {
        // Clear cache
        cache()->delete('setting_' . $key);
        
        // Log setting change
        $this->logSettingChange($key, $value, $userId);
        
        return ['success' => true, 'message' => 'Setting updated successfully'];
    }
    
    return ['success' => false, 'message' => 'Failed to update setting'];
}
```

### Setting Type Casting
```php
// Cast setting value to appropriate type
private function castSettingValue($value, $type) {
    switch ($type) {
        case 'boolean':
            return (bool) $value;
            
        case 'integer':
            return (int) $value;
            
        case 'json':
            return json_decode($value, true);
            
        case 'string':
        case 'text':
        case 'email':
        case 'url':
        case 'password':
        default:
            return $value;
    }
}

// Prepare setting value for storage
private function prepareSettingValue($value, $type) {
    switch ($type) {
        case 'boolean':
            return $value ? '1' : '0';
            
        case 'json':
            return json_encode($value);
            
        case 'password':
            // Encrypt password values
            return $this->encryptSetting($value);
            
        default:
            return (string) $value;
    }
}
```

### Settings Validation
```php
// Validate setting value
private function validateSettingValue($value, $setting) {
    // Check required settings
    if ($setting->is_required && empty($value)) {
        return false;
    }
    
    // Type-specific validation
    switch ($setting->setting_type) {
        case 'email':
            return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
            
        case 'url':
            return filter_var($value, FILTER_VALIDATE_URL) !== false;
            
        case 'integer':
            return is_numeric($value);
            
        case 'boolean':
            return in_array($value, [0, 1, '0', '1', true, false], true);
            
        case 'json':
            json_decode($value);
            return json_last_error() === JSON_ERROR_NONE;
    }
    
    // Custom validation rules
    if (!empty($setting->validation_rules)) {
        $rules = json_decode($setting->validation_rules, true);
        return $this->validateWithRules($value, $rules);
    }
    
    return true;
}
```

### Bulk Settings Update
```php
// Update multiple settings
public function bulkUpdateSettings($settings, $userId = null) {
    $db = \Config\Database::connect();
    $db->transStart();
    
    $results = [];
    
    try {
        foreach ($settings as $key => $value) {
            $result = $this->setSetting($key, $value, $userId);
            $results[$key] = $result;
            
            if (!$result['success']) {
                throw new \Exception("Failed to update setting: {$key}");
            }
        }
        
        $db->transComplete();
        
        if ($db->transStatus() === false) {
            throw new \Exception('Transaction failed');
        }
        
        // Clear all settings cache
        $this->clearSettingsCache();
        
        return ['success' => true, 'message' => 'Settings updated successfully', 'results' => $results];
        
    } catch (\Exception $e) {
        $db->transRollback();
        return ['success' => false, 'message' => $e->getMessage(), 'results' => $results];
    }
}
```

### Settings Categories
```php
// Get settings by category
public function getSettingsByCategory($category) {
    $settings = $this->where('category', $category)
                    ->orderBy('sort_order', 'ASC')
                    ->orderBy('setting_key', 'ASC')
                    ->findAll();
    
    $categorySettings = [];
    
    foreach ($settings as $setting) {
        $categorySettings[$setting->setting_key] = [
            'value' => $this->castSettingValue($setting->setting_value, $setting->setting_type),
            'type' => $setting->setting_type,
            'description' => $setting->description,
            'is_required' => $setting->is_required,
            'options' => $setting->options ? json_decode($setting->options, true) : null
        ];
    }
    
    return $categorySettings;
}

// Get all setting categories
public function getCategories() {
    return $this->select('category, COUNT(*) as setting_count')
                ->groupBy('category')
                ->orderBy('category', 'ASC')
                ->findAll();
}
```

## Security Features

### Access Control
- **Admin Authorization**: Settings management restricted to administrators
- **Setting Permissions**: Fine-grained permissions for different setting categories
- **Audit Trail**: Complete audit trail of setting changes
- **Sensitive Data Protection**: Encrypt sensitive settings like passwords

### Data Protection
- **Input Validation**: Comprehensive validation for all setting types
- **XSS Prevention**: Escape setting values in output
- **SQL Injection Prevention**: Use parameterized queries
- **Configuration Security**: Secure storage of sensitive configuration data

## Integration Points

### Email Module Integration
```php
// Get email configuration
public function getEmailConfig() {
    return [
        'protocol' => $this->getSetting('email_protocol', 'smtp'),
        'SMTPHost' => $this->getSetting('smtp_host'),
        'SMTPPort' => $this->getSetting('smtp_port', 587),
        'SMTPUser' => $this->getSetting('smtp_username'),
        'SMTPPass' => $this->decryptSetting($this->getSetting('smtp_password')),
        'SMTPCrypto' => $this->getSetting('smtp_encryption', 'tls'),
        'mailType' => 'html',
        'charset' => 'utf-8'
    ];
}
```

### Course Module Integration
```php
// Get course-related settings
public function getCourseSettings() {
    return [
        'default_price' => $this->getSetting('default_course_price', 299.00),
        'free_trial_credits' => $this->getSetting('free_trial_credits', 1000),
        'max_enrollment' => $this->getSetting('max_course_enrollment', 0),
        'auto_enrollment' => $this->getSetting('auto_enrollment_enabled', false),
        'certificate_enabled' => $this->getSetting('certificates_enabled', true)
    ];
}
```

### Payment Integration
```php
// Get payment gateway settings
public function getPaymentSettings() {
    return [
        'payment_enabled' => $this->getSetting('payment_enabled', true),
        'currency' => $this->getSetting('default_currency', 'SAR'),
        'payment_methods' => $this->getSetting('enabled_payment_methods', ['bank_transfer']),
        'min_payment' => $this->getSetting('minimum_payment_amount', 50),
        'max_payment' => $this->getSetting('maximum_payment_amount', 10000)
    ];
}
```

## Performance Optimization

### Caching Strategy
- **Individual Settings**: Cache each setting for 1 hour
- **Category Settings**: Cache category groups for 30 minutes
- **Public Settings**: Cache public settings for 2 hours

### Database Optimization
- **Indexed Queries**: Optimize setting key and category queries
- **Query Optimization**: Efficient setting retrieval
- **Batch Operations**: Bulk setting updates

## Configuration

### Environment Variables
```env
# Settings Module
SETTINGS_CACHE_DURATION=3600
SETTINGS_ENCRYPT_SENSITIVE=true
SETTINGS_AUDIT_ENABLED=true
SETTINGS_BACKUP_ENABLED=true

# Security
SETTINGS_ENCRYPTION_KEY=your-encryption-key
SETTINGS_ADMIN_ONLY=true
```

## Usage Examples

### Getting Settings
```php
// Get individual setting
$siteName = $settingsModel->getSetting('site_name', 'Default Site Name');

// Get category settings
$emailSettings = $settingsModel->getSettingsByCategory('email');

// Get public settings
$publicSettings = $settingsModel->getPublicSettings();
```

### Updating Settings
```php
// Update single setting
$result = $settingsModel->setSetting('site_name', 'New Site Name', $userId);

// Bulk update
$settings = [
    'site_name' => 'MSARLink Platform',
    'contact_email' => 'contact@msarlink.com',
    'free_trial_credits' => 1500
];

$result = $settingsModel->bulkUpdateSettings($settings, $userId);
```

### Using Settings in Views
```php
// In controller
$data['site_settings'] = [
    'name' => $settingsModel->getSetting('site_name'),
    'logo' => $settingsModel->getSetting('site_logo'),
    'description' => $settingsModel->getSetting('site_description')
];

// In view
echo '<h1>' . esc($site_settings['name']) . '</h1>';
echo '<img src="' . esc($site_settings['logo']) . '" alt="Logo">';
```

## Testing Strategy

### Unit Tests
- Setting CRUD operations
- Type casting and validation
- Encryption/decryption
- Cache functionality

### Integration Tests
- Module integration
- Email configuration
- Payment settings
- Security validation

### Security Tests
- Access control validation
- Sensitive data encryption
- Input validation
- Audit trail verification

## Monitoring & Logging

### Settings Events Logged
- Setting creation, modification, deletion
- Bulk setting updates
- Failed validation attempts
- Security-related setting changes

### Audit Trail
- Who changed what setting when
- Previous and new values
- IP address and user agent
- Reason for change (if provided)

## Future Enhancements

### Planned Features
- **Settings Groups**: Organize settings into logical groups
- **Setting Dependencies**: Settings that depend on other settings
- **Dynamic Settings**: Runtime setting creation
- **Setting Templates**: Pre-configured setting templates

### Advanced Features
- **Setting Validation Rules**: Complex validation rules
- **Setting Workflows**: Approval workflows for critical settings
- **Setting Versioning**: Track setting value history
- **API Configuration**: RESTful API for settings management

## Dependencies

### Required Libraries
- CodeIgniter 4.x framework
- Encryption library for sensitive settings
- Cache library for performance
- Validation library for input validation

### Optional Integrations
- External configuration management
- Environment-specific configurations
- Configuration backup services
- Monitoring and alerting tools

## Troubleshooting

### Common Issues
1. **Setting Not Updating**: Check validation rules and permissions
2. **Cache Issues**: Clear settings cache and verify cache configuration
3. **Email Not Working**: Verify SMTP settings and credentials
4. **Performance Issues**: Review cache strategy and query optimization

### Debug Tools
- Settings validation debugger
- Cache status checker
- Configuration export/import tools
- Audit trail viewer