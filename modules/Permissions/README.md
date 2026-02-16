# Permissions Module Technical Specification

## Overview
The Permissions module provides fine-grained permission management for the MSARLink e-learning platform, working in conjunction with the Groups module and CodeIgniter Shield to implement comprehensive role-based access control (RBAC).

## Architecture

### Controllers
- **Permissions.php**: Main permissions controller for permission-related functionality
- **AdminPermissions.php**: Administrative permission management interface

### Models
- **PermissionsModel.php**: Core permission data management
- **UsersModel.php**: User permission management

### Views
- **Site/**: User permission interfaces (if applicable)
- **Admin/**: Administrative permission management interface

## Key Features

### 1. Permission Management
- **Permission Creation**: Define custom permissions for system features
- **Permission Categories**: Organize permissions into logical categories
- **Permission Hierarchy**: Support for nested permission structures
- **Dynamic Permissions**: Runtime permission creation and modification

### 2. Access Control
- **Fine-grained Control**: Granular permission control for system features
- **Resource-based Permissions**: Permissions tied to specific resources
- **Action-based Permissions**: Permissions for specific actions (CRUD operations)
- **Context-aware Permissions**: Permissions that consider context and conditions

### 3. Shield Integration
- **Seamless Integration**: Full integration with CodeIgniter Shield
- **Permission Mapping**: Map custom permissions to Shield permissions
- **User Permission Assignment**: Direct user permission assignment
- **Group Permission Inheritance**: Inherit permissions from group membership

## Database Schema

### auth_permissions Table (Shield Integration)
```sql
CREATE TABLE `auth_permissions` (
    `id` int NOT NULL AUTO_INCREMENT,
    `permission_name` varchar(100) DEFAULT NULL,
    `title` varchar(100) NOT NULL,
    `updated_at` datetime DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `description` text,
    `category` varchar(50) DEFAULT NULL,
    `resource_type` varchar(50) DEFAULT NULL,
    `action_type` varchar(50) DEFAULT NULL,
    `is_system` tinyint(1) DEFAULT 0,
    `active` tinyint(1) DEFAULT 1,
    PRIMARY KEY (`id`),
    UNIQUE KEY `permission_name` (`permission_name`),
    KEY `idx_category` (`category`),
    KEY `idx_resource_action` (`resource_type`, `action_type`),
    KEY `idx_active` (`active`)
);
```

### auth_permissions_users Table (Shield Integration)
```sql
CREATE TABLE `auth_permissions_users` (
    `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` int UNSIGNED NOT NULL,
    `permission` varchar(255) NOT NULL,
    `created_at` datetime NOT NULL,
    `group_id` int DEFAULT NULL,
    `granted_by` int DEFAULT NULL,
    `expires_at` datetime DEFAULT NULL,
    `context` json DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_user_permission` (`user_id`, `permission`),
    KEY `idx_group_permission` (`group_id`, `permission`),
    KEY `idx_expires` (`expires_at`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`group_id`) REFERENCES `auth_groups` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`granted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
);
```

## API Endpoints

### Admin Permission API
```php
// Permission management
GET /dt_admin/permissions
POST /dt_admin/permissions/create
GET /dt_admin/permissions/edit/{id}
POST /dt_admin/permissions/update/{id}
POST /dt_admin/permissions/delete/{id}

// User permission assignment
GET /dt_admin/permissions/users/{user_id}
POST /dt_admin/permissions/assign-user
POST /dt_admin/permissions/revoke-user

// Group permission assignment
GET /dt_admin/permissions/groups/{group_id}
POST /dt_admin/permissions/assign-group
POST /dt_admin/permissions/revoke-group

// Permission analysis
GET /dt_admin/permissions/analysis
GET /dt_admin/permissions/audit/{user_id}
```

## Permission Categories

### System Permissions
```php
// Core system permissions
$systemPermissions = [
    // User Management
    'users.create' => [
        'title' => 'Create Users',
        'description' => 'Create new user accounts',
        'category' => 'user_management',
        'resource_type' => 'user',
        'action_type' => 'create'
    ],
    'users.read' => [
        'title' => 'View Users',
        'description' => 'View user information',
        'category' => 'user_management',
        'resource_type' => 'user',
        'action_type' => 'read'
    ],
    'users.update' => [
        'title' => 'Update Users',
        'description' => 'Modify user information',
        'category' => 'user_management',
        'resource_type' => 'user',
        'action_type' => 'update'
    ],
    'users.delete' => [
        'title' => 'Delete Users',
        'description' => 'Delete user accounts',
        'category' => 'user_management',
        'resource_type' => 'user',
        'action_type' => 'delete'
    ],
    
    // Course Management
    'courses.create' => [
        'title' => 'Create Courses',
        'description' => 'Create new courses',
        'category' => 'course_management',
        'resource_type' => 'course',
        'action_type' => 'create'
    ],
    'courses.manage' => [
        'title' => 'Manage All Courses',
        'description' => 'Full course management access',
        'category' => 'course_management',
        'resource_type' => 'course',
        'action_type' => 'manage'
    ],
    
    // System Administration
    'system.settings' => [
        'title' => 'System Settings',
        'description' => 'Access system configuration',
        'category' => 'system_admin',
        'resource_type' => 'system',
        'action_type' => 'configure'
    ],
    'system.backup' => [
        'title' => 'System Backup',
        'description' => 'Perform system backups',
        'category' => 'system_admin',
        'resource_type' => 'system',
        'action_type' => 'backup'
    ]
];
```

## Features Implementation

### Permission Creation
```php
// Create new permission
public function createPermission($permissionData) {
    $validation = [
        'permission_name' => 'required|is_unique[auth_permissions.permission_name]|alpha_dash',
        'title' => 'required|min_length[3]|max_length[100]',
        'category' => 'required|alpha_dash',
        'resource_type' => 'alpha_dash',
        'action_type' => 'alpha_dash'
    ];
    
    if (!$this->validate($validation)) {
        return ['success' => false, 'errors' => $this->validator->getErrors()];
    }
    
    // Set default values
    $permissionData['created_at'] = date('Y-m-d H:i:s');
    $permissionData['updated_at'] = date('Y-m-d H:i:s');
    $permissionData['active'] = 1;
    
    $permissionId = $this->insert($permissionData);
    
    if ($permissionId) {
        // Log permission creation
        $this->logActivity('permission_created', $permissionId, $permissionData);
        
        // Clear permission cache
        $this->clearPermissionCache();
        
        return ['success' => true, 'permission_id' => $permissionId];
    }
    
    return ['success' => false, 'message' => 'Failed to create permission'];
}
```

### User Permission Assignment
```php
// Assign permission to user
public function assignPermissionToUser($userId, $permission, $grantedBy = null, $expiresAt = null, $context = null) {
    // Check if user exists
    $user = $this->usersModel->find($userId);
    if (!$user) {
        return ['success' => false, 'message' => 'User not found'];
    }
    
    // Check if permission exists
    if (!$this->permissionExists($permission)) {
        return ['success' => false, 'message' => 'Permission not found'];
    }
    
    // Check if user already has permission
    if ($this->userHasPermission($userId, $permission)) {
        return ['success' => false, 'message' => 'User already has this permission'];
    }
    
    // Create permission assignment
    $assignmentData = [
        'user_id' => $userId,
        'permission' => $permission,
        'created_at' => date('Y-m-d H:i:s'),
        'granted_by' => $grantedBy,
        'expires_at' => $expiresAt,
        'context' => $context ? json_encode($context) : null
    ];
    
    $result = $this->db->table('auth_permissions_users')->insert($assignmentData);
    
    if ($result) {
        // Log permission assignment
        $this->logActivity('permission_assigned', $userId, [
            'permission' => $permission,
            'granted_by' => $grantedBy
        ]);
        
        // Clear user permission cache
        $this->clearUserPermissionCache($userId);
        
        return ['success' => true, 'message' => 'Permission assigned successfully'];
    }
    
    return ['success' => false, 'message' => 'Failed to assign permission'];
}
```

### Permission Checking
```php
// Check if user has permission
public function userHasPermission($userId, $permission, $context = null) {
    // Check direct user permissions
    $query = $this->db->table('auth_permissions_users')
                     ->where('user_id', $userId)
                     ->where('permission', $permission)
                     ->where('(expires_at IS NULL OR expires_at > NOW())');
    
    if ($context) {
        $query->where('(context IS NULL OR JSON_CONTAINS(context, ?))', [json_encode($context)]);
    }
    
    $directPermission = $query->get()->getRow();
    
    if ($directPermission) {
        return true;
    }
    
    // Check group permissions
    $userGroups = $this->groupsModel->getUserGroups($userId);
    
    foreach ($userGroups as $group) {
        $groupPermission = $this->db->table('auth_permissions_users')
                                   ->where('group_id', $group->id)
                                   ->where('permission', $permission)
                                   ->where('(expires_at IS NULL OR expires_at > NOW())')
                                   ->get()
                                   ->getRow();
        
        if ($groupPermission) {
            return true;
        }
    }
    
    // Check wildcard permissions
    return $this->checkWildcardPermissions($userId, $permission);
}
```

### Wildcard Permission Support
```php
// Check wildcard permissions
private function checkWildcardPermissions($userId, $permission) {
    $permissionParts = explode('.', $permission);
    
    // Check for resource.* permissions
    if (count($permissionParts) >= 2) {
        $wildcardPermission = $permissionParts[0] . '.*';
        if ($this->userHasDirectPermission($userId, $wildcardPermission)) {
            return true;
        }
    }
    
    // Check for *.action permissions
    if (count($permissionParts) >= 2) {
        $wildcardPermission = '*.' . $permissionParts[1];
        if ($this->userHasDirectPermission($userId, $wildcardPermission)) {
            return true;
        }
    }
    
    // Check for super admin permission
    return $this->userHasDirectPermission($userId, '*.*');
}
```

### Permission Analysis
```php
// Analyze user permissions
public function analyzeUserPermissions($userId) {
    $analysis = [
        'direct_permissions' => [],
        'group_permissions' => [],
        'effective_permissions' => [],
        'expired_permissions' => [],
        'conflicting_permissions' => []
    ];
    
    // Get direct permissions
    $directPermissions = $this->db->table('auth_permissions_users')
                                 ->where('user_id', $userId)
                                 ->get()
                                 ->getResultArray();
    
    foreach ($directPermissions as $perm) {
        if ($perm['expires_at'] && strtotime($perm['expires_at']) < time()) {
            $analysis['expired_permissions'][] = $perm;
        } else {
            $analysis['direct_permissions'][] = $perm;
        }
    }
    
    // Get group permissions
    $userGroups = $this->groupsModel->getUserGroups($userId);
    
    foreach ($userGroups as $group) {
        $groupPermissions = $this->getGroupPermissions($group->id);
        $analysis['group_permissions'][$group->group_name] = $groupPermissions;
    }
    
    // Calculate effective permissions
    $analysis['effective_permissions'] = $this->getUserEffectivePermissions($userId);
    
    return $analysis;
}
```

## Security Features

### Access Control
- **Admin Authorization**: Permission management restricted to super administrators
- **Permission Validation**: Validate permissions before assignment
- **Audit Trail**: Complete audit trail of permission changes
- **Temporal Permissions**: Support for time-limited permissions

### Data Protection
- **Input Validation**: Validate all permission data
- **SQL Injection Prevention**: Use parameterized queries
- **Permission Escalation Prevention**: Prevent unauthorized privilege escalation
- **Context Validation**: Validate permission context data

## Integration Points

### Shield Authentication Integration
```php
// Initialize Shield permissions
public function initializeShieldPermissions() {
    $shield = service('auth');
    
    // Sync custom permissions with Shield
    $customPermissions = $this->findAll();
    
    foreach ($customPermissions as $permission) {
        if (!$shield->hasPermission($permission->permission_name)) {
            $shield->createPermission($permission->permission_name, $permission->title);
        }
    }
}
```

### Groups Module Integration
```php
// Assign permission to group
public function assignPermissionToGroup($groupId, $permission) {
    $group = $this->groupsModel->find($groupId);
    if (!$group) {
        return ['success' => false, 'message' => 'Group not found'];
    }
    
    // Get all users in group
    $groupUsers = $this->groupsModel->getGroupUsers($groupId);
    
    // Assign permission through group context
    $assignmentData = [
        'permission' => $permission,
        'group_id' => $groupId,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    return $this->db->table('auth_permissions_users')->insert($assignmentData);
}
```

### Course Module Integration
```php
// Check course-specific permissions
public function canAccessCourse($userId, $courseId, $action = 'read') {
    $permission = 'courses.' . $action;
    
    // Check general course permission
    if ($this->userHasPermission($userId, $permission)) {
        return true;
    }
    
    // Check course-specific permission
    $coursePermission = 'courses.' . $action . '.' . $courseId;
    if ($this->userHasPermission($userId, $coursePermission)) {
        return true;
    }
    
    // Check if user is course instructor
    if ($action === 'update' && $this->coursesModel->isInstructor($userId, $courseId)) {
        return true;
    }
    
    return false;
}
```

## Performance Optimization

### Caching Strategy
- **User Permissions**: Cache user permissions for 15 minutes
- **Permission Definitions**: Cache permission definitions for 1 hour
- **Group Permissions**: Cache group permissions for 30 minutes

### Database Optimization
- **Composite Indexes**: Optimize user-permission queries
- **Query Optimization**: Efficient permission checking queries
- **Batch Operations**: Bulk permission assignments

## Configuration

### Environment Variables
```env
# Permission Settings
PERMISSIONS_CACHE_DURATION=900
PERMISSIONS_ENABLE_WILDCARDS=true
PERMISSIONS_ENABLE_CONTEXT=true
PERMISSIONS_ENABLE_EXPIRATION=true

# Security Settings
PERMISSIONS_AUDIT_ENABLED=true
PERMISSIONS_STRICT_MODE=true
PERMISSIONS_PREVENT_ESCALATION=true

# Performance Settings
PERMISSIONS_BATCH_SIZE=100
PERMISSIONS_QUERY_CACHE=true
```

## Usage Examples

### Creating Permissions
```php
// Create course management permission
$permissionData = [
    'permission_name' => 'courses.manage',
    'title' => 'Manage Courses',
    'description' => 'Full course management access',
    'category' => 'course_management',
    'resource_type' => 'course',
    'action_type' => 'manage'
];

$permissionId = $permissionsModel->insert($permissionData);
```

### Assigning Permissions
```php
// Assign permission to user
$result = $permissionsModel->assignPermissionToUser(
    $userId, 
    'courses.create', 
    session()->get('user_id')
);

// Assign temporary permission
$result = $permissionsModel->assignPermissionToUser(
    $userId, 
    'system.maintenance', 
    session()->get('user_id'),
    date('Y-m-d H:i:s', strtotime('+1 hour'))
);
```

### Checking Permissions
```php
// Check basic permission
$canCreateCourse = $permissionsModel->userHasPermission($userId, 'courses.create');

// Check contextual permission
$context = ['course_id' => 123];
$canEditCourse = $permissionsModel->userHasPermission($userId, 'courses.update', $context);

// Check in controller
if (!$permissionsModel->userHasPermission(session()->get('user_id'), 'users.delete')) {
    return redirect()->back()->with('error', 'Access denied');
}
```

## Testing Strategy

### Unit Tests
- Permission CRUD operations
- Permission assignment logic
- Permission checking algorithms
- Wildcard permission matching

### Integration Tests
- Shield integration
- Group permission inheritance
- Context-aware permissions
- Temporal permissions

### Security Tests
- Permission escalation prevention
- Access control bypass attempts
- Audit trail verification
- Context validation

## Monitoring & Logging

### Permission Events Logged
- Permission creation, modification, deletion
- Permission assignments and revocations
- Permission check failures
- Expired permission cleanup

### Security Monitoring
- Failed permission checks
- Unauthorized access attempts
- Permission escalation attempts
- Suspicious permission patterns

## Future Enhancements

### Planned Features
- **Dynamic Permission Creation**: Runtime permission generation
- **Permission Policies**: Complex permission rules and conditions
- **Permission Analytics**: Usage and effectiveness metrics
- **API Permissions**: Fine-grained API access control

### Advanced Features
- **Attribute-based Access Control**: ABAC implementation
- **Machine Learning**: Intelligent permission recommendations
- **External Integration**: LDAP/AD permission synchronization
- **Mobile Permissions**: Mobile-specific permission handling

## Dependencies

### Required Libraries
- CodeIgniter 4.x framework
- CodeIgniter Shield authentication
- Groups module for group management
- Database abstraction layer

### Optional Integrations
- External authentication providers
- LDAP/Active Directory
- Audit logging systems
- Monitoring and alerting tools

## Troubleshooting

### Common Issues
1. **Permission Not Working**: Check permission name and user assignment
2. **Access Denied**: Verify user has required permissions
3. **Performance Issues**: Review permission caching and query optimization
4. **Expired Permissions**: Check permission expiration dates

### Debug Tools
- Permission analysis tool
- User permission audit
- Permission checking debugger
- Performance profiling tools