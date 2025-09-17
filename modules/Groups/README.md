# Groups Module Technical Specification

## Overview
The Groups module manages user groups and role-based access control for the MSARLink e-learning platform, integrating with CodeIgniter Shield authentication system to provide comprehensive user permission management.

## Architecture

### Controllers
- **Groups.php**: Main groups controller for group-related functionality
- **AdminGroups.php**: Administrative group management interface

### Models
- **GroupsModel.php**: Core group data management
- **PermissionModel.php**: Group permission management
- **UsersModel.php**: User-group relationship management

### Views
- **Site/**: User group interfaces (if applicable)
- **Admin/**: Administrative group management interface

## Key Features

### 1. Group Management
- **Group Creation**: Create and manage user groups
- **Group Hierarchy**: Support for nested group structures
- **Group Permissions**: Assign permissions to groups
- **Group Membership**: Manage user group assignments

### 2. Role-Based Access Control (RBAC)
- **Permission System**: Fine-grained permission control
- **Role Assignment**: Assign roles to users through groups
- **Access Control**: Control access to system features
- **Permission Inheritance**: Hierarchical permission inheritance

### 3. Shield Integration
- **Authentication Integration**: Seamless integration with CodeIgniter Shield
- **User Groups**: Manage Shield user groups
- **Permission Mapping**: Map custom permissions to Shield permissions
- **Session Management**: Handle group-based session data

## Database Schema

### auth_groups Table (Shield Integration)
```sql
CREATE TABLE `auth_groups` (
    `id` int NOT NULL AUTO_INCREMENT,
    `group_name` varchar(255) NOT NULL,
    `title` varchar(255) NOT NULL,
    `description` mediumtext,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    `deleted_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `group_name` (`group_name`)
);
```

### auth_groups_users Table (Shield Integration)
```sql
CREATE TABLE `auth_groups_users` (
    `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` int UNSIGNED NOT NULL,
    `group` varchar(255) NOT NULL,
    `created_at` datetime NOT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_user_group` (`user_id`, `group`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
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
    PRIMARY KEY (`id`),
    KEY `idx_user_permission` (`user_id`, `permission`),
    KEY `idx_group_permission` (`group_id`, `permission`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`group_id`) REFERENCES `auth_groups` (`id`) ON DELETE CASCADE
);
```

## API Endpoints

### Admin Group API
```php
// Group management
GET /dt_admin/groups
POST /dt_admin/groups/create
GET /dt_admin/groups/edit/{id}
POST /dt_admin/groups/update/{id}
POST /dt_admin/groups/delete/{id}

// Permission management
GET /dt_admin/groups/permissions/{group_id}
POST /dt_admin/groups/assign-permission
POST /dt_admin/groups/remove-permission

// User assignment
GET /dt_admin/groups/users/{group_id}
POST /dt_admin/groups/assign-user
POST /dt_admin/groups/remove-user
```

## Default Groups

### System Groups
```php
// Default system groups
$defaultGroups = [
    [
        'group_name' => 'superadmin',
        'title' => 'Super Administrator',
        'description' => 'Full system access with all permissions'
    ],
    [
        'group_name' => 'admin',
        'title' => 'Administrator',
        'description' => 'Administrative access to most system features'
    ],
    [
        'group_name' => 'instructor',
        'title' => 'Instructor',
        'description' => 'Course creation and management permissions'
    ],
    [
        'group_name' => 'student',
        'title' => 'Student',
        'description' => 'Basic student access to courses and content'
    ],
    [
        'group_name' => 'user',
        'title' => 'User',
        'description' => 'Basic user access with limited permissions'
    ]
];
```

## Permission System

### Permission Categories
```php
// System permissions
$permissions = [
    // User management
    'users.create' => 'Create users',
    'users.read' => 'View users',
    'users.update' => 'Update users',
    'users.delete' => 'Delete users',
    'users.manage' => 'Manage all users',
    
    // Course management
    'courses.create' => 'Create courses',
    'courses.read' => 'View courses',
    'courses.update' => 'Update courses',
    'courses.delete' => 'Delete courses',
    'courses.manage' => 'Manage all courses',
    
    // Content management
    'content.create' => 'Create content',
    'content.read' => 'View content',
    'content.update' => 'Update content',
    'content.delete' => 'Delete content',
    
    // System administration
    'system.settings' => 'Manage system settings',
    'system.analytics' => 'View system analytics',
    'system.backup' => 'Perform system backups',
    'system.maintenance' => 'System maintenance access'
];
```

## Features Implementation

### Group Management
```php
// Create new group
public function createGroup($groupData) {
    $validation = [
        'group_name' => 'required|is_unique[auth_groups.group_name]|alpha_dash',
        'title' => 'required|min_length[3]|max_length[255]',
        'description' => 'max_length[1000]'
    ];
    
    if (!$this->validate($validation)) {
        return ['success' => false, 'errors' => $this->validator->getErrors()];
    }
    
    $groupId = $this->insert($groupData);
    
    if ($groupId) {
        // Log group creation
        $this->logActivity('group_created', $groupId, $groupData);
        return ['success' => true, 'group_id' => $groupId];
    }
    
    return ['success' => false, 'message' => 'Failed to create group'];
}
```

### Permission Assignment
```php
// Assign permission to group
public function assignPermissionToGroup($groupId, $permission) {
    // Check if group exists
    $group = $this->find($groupId);
    if (!$group) {
        return ['success' => false, 'message' => 'Group not found'];
    }
    
    // Check if permission exists
    if (!$this->permissionExists($permission)) {
        return ['success' => false, 'message' => 'Permission not found'];
    }
    
    // Get all users in this group
    $groupUsers = $this->getGroupUsers($groupId);
    
    // Assign permission to all users in group
    foreach ($groupUsers as $user) {
        $this->assignPermissionToUser($user->id, $permission, $groupId);
    }
    
    return ['success' => true, 'message' => 'Permission assigned to group'];
}
```

### User Group Assignment
```php
// Add user to group
public function addUserToGroup($userId, $groupName) {
    // Check if user exists
    $user = $this->usersModel->find($userId);
    if (!$user) {
        return ['success' => false, 'message' => 'User not found'];
    }
    
    // Check if group exists
    $group = $this->where('group_name', $groupName)->first();
    if (!$group) {
        return ['success' => false, 'message' => 'Group not found'];
    }
    
    // Check if user is already in group
    if ($this->isUserInGroup($userId, $groupName)) {
        return ['success' => false, 'message' => 'User already in group'];
    }
    
    // Add user to group
    $groupUserData = [
        'user_id' => $userId,
        'group' => $groupName,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    $result = $this->db->table('auth_groups_users')->insert($groupUserData);
    
    if ($result) {
        // Assign group permissions to user
        $this->assignGroupPermissionsToUser($userId, $group->id);
        
        // Log activity
        $this->logActivity('user_added_to_group', $userId, [
            'group_name' => $groupName,
            'group_id' => $group->id
        ]);
        
        return ['success' => true, 'message' => 'User added to group'];
    }
    
    return ['success' => false, 'message' => 'Failed to add user to group'];
}
```

### Permission Checking
```php
// Check if user has permission
public function userHasPermission($userId, $permission) {
    // Check direct user permissions
    $directPermission = $this->db->table('auth_permissions_users')
                                ->where('user_id', $userId)
                                ->where('permission', $permission)
                                ->get()
                                ->getRow();
    
    if ($directPermission) {
        return true;
    }
    
    // Check group permissions
    $userGroups = $this->getUserGroups($userId);
    
    foreach ($userGroups as $group) {
        $groupPermission = $this->db->table('auth_permissions_users')
                                   ->where('group_id', $group->id)
                                   ->where('permission', $permission)
                                   ->get()
                                   ->getRow();
        
        if ($groupPermission) {
            return true;
        }
    }
    
    return false;
}
```

## Security Features

### Access Control
- **Admin Authorization**: Group management restricted to administrators
- **Permission Validation**: Validate permissions before assignment
- **Group Hierarchy**: Respect group hierarchy in permission inheritance
- **Audit Trail**: Log all group and permission changes

### Data Protection
- **Input Validation**: Validate all group and permission data
- **SQL Injection Prevention**: Use parameterized queries
- **XSS Protection**: Escape output data
- **Session Security**: Secure group-based session management

## Integration Points

### Shield Authentication Integration
```php
// Initialize Shield groups
public function initializeShieldGroups() {
    $shield = service('auth');
    
    // Sync custom groups with Shield
    $customGroups = $this->findAll();
    
    foreach ($customGroups as $group) {
        if (!$shield->getGroup($group->group_name)) {
            $shield->createGroup($group->group_name, $group->title, $group->description);
        }
    }
}
```

### User Module Integration
```php
// Get user's effective permissions
public function getUserEffectivePermissions($userId) {
    $permissions = [];
    
    // Get direct permissions
    $directPermissions = $this->getUserDirectPermissions($userId);
    $permissions = array_merge($permissions, $directPermissions);
    
    // Get group permissions
    $userGroups = $this->getUserGroups($userId);
    
    foreach ($userGroups as $group) {
        $groupPermissions = $this->getGroupPermissions($group->id);
        $permissions = array_merge($permissions, $groupPermissions);
    }
    
    return array_unique($permissions);
}
```

### Course Module Integration
```php
// Check course access permissions
public function canAccessCourse($userId, $courseId) {
    // Check if user has general course access
    if ($this->userHasPermission($userId, 'courses.read')) {
        return true;
    }
    
    // Check if user is enrolled in course
    if ($this->enrollmentsModel->isEnrolled($userId, $courseId)) {
        return true;
    }
    
    // Check if user is instructor of course
    if ($this->coursesModel->isInstructor($userId, $courseId)) {
        return true;
    }
    
    return false;
}
```

## Performance Optimization

### Caching Strategy
- **User Groups**: Cache user group memberships for 30 minutes
- **User Permissions**: Cache user permissions for 15 minutes
- **Group Permissions**: Cache group permissions for 1 hour

### Database Optimization
- **Indexed Queries**: Optimize user-group and permission queries
- **Query Optimization**: Efficient permission checking queries
- **Batch Operations**: Bulk permission assignments

## Configuration

### Environment Variables
```env
# Group Settings
GROUPS_CACHE_DURATION=1800
GROUPS_ENABLE_HIERARCHY=true
GROUPS_AUTO_ASSIGN_DEFAULT=true
GROUPS_DEFAULT_GROUP=user

# Permission Settings
PERMISSIONS_CACHE_DURATION=900
PERMISSIONS_STRICT_MODE=true
PERMISSIONS_INHERITANCE_ENABLED=true

# Security Settings
GROUPS_AUDIT_ENABLED=true
GROUPS_SESSION_VALIDATION=true
```

## Usage Examples

### Creating Groups
```php
// Create instructor group
$groupData = [
    'group_name' => 'instructor',
    'title' => 'Course Instructor',
    'description' => 'Can create and manage courses'
];

$groupId = $groupsModel->insert($groupData);
```

### Assigning Permissions
```php
// Assign course management permissions to instructor group
$permissions = [
    'courses.create',
    'courses.update',
    'courses.read',
    'content.create',
    'content.update'
];

foreach ($permissions as $permission) {
    $groupsModel->assignPermissionToGroup($groupId, $permission);
}
```

### User Group Management
```php
// Add user to instructor group
$result = $groupsModel->addUserToGroup($userId, 'instructor');

// Check user permissions
$canCreateCourse = $groupsModel->userHasPermission($userId, 'courses.create');

// Get user groups
$userGroups = $groupsModel->getUserGroups($userId);
```

## Testing Strategy

### Unit Tests
- Group CRUD operations
- Permission assignment logic
- User group membership
- Permission checking algorithms

### Integration Tests
- Shield authentication integration
- User module integration
- Course access control
- Session management

### Security Tests
- Permission bypass attempts
- Group escalation prevention
- Access control validation
- Audit trail verification

## Monitoring & Logging

### Group Events Logged
- Group creation, modification, deletion
- Permission assignments and removals
- User group membership changes
- Permission check failures

### Security Monitoring
- Failed permission checks
- Unauthorized access attempts
- Group privilege escalations
- Suspicious activity patterns

## Future Enhancements

### Planned Features
- **Dynamic Permissions**: Runtime permission creation
- **Group Templates**: Pre-configured group templates
- **Permission Policies**: Complex permission rules
- **Group Analytics**: Group usage and effectiveness metrics

### Advanced Features
- **Conditional Permissions**: Context-based permissions
- **Time-based Access**: Temporary group memberships
- **API Integration**: External system integration
- **Advanced Audit**: Detailed permission audit trails

## Dependencies

### Required Libraries
- CodeIgniter 4.x framework
- CodeIgniter Shield authentication
- Database abstraction layer
- Session management library

### Optional Integrations
- LDAP/Active Directory integration
- External authentication providers
- Audit logging systems
- Monitoring and alerting tools

## Troubleshooting

### Common Issues
1. **Permission Not Working**: Check group assignments and permission inheritance
2. **User Access Denied**: Verify user group membership and permissions
3. **Group Creation Failed**: Check for duplicate group names and validation
4. **Performance Issues**: Review permission caching and query optimization

### Debug Tools
- Permission checking debugging
- Group membership verification
- Permission inheritance tracing
- Performance profiling tools