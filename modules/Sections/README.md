# Sections Module Technical Specification

## Overview
The Sections module manages course sections and navigation structure for the MSARLink e-learning platform, providing hierarchical organization of course content and site navigation management.

## Architecture

### Controllers
- **Sections.php**: Main sections controller for section display and navigation
- **AdminSections.php**: Administrative section management interface

### Models
- **SectionsModel.php**: Core section data management

### Views
- **Site/**: Public section display and navigation
- **Admin/**: Administrative section management interface

## Key Features

### 1. Course Section Management
- **Hierarchical Structure**: Organize course content into logical sections
- **Section Ordering**: Custom ordering and sequencing of sections
- **Section Metadata**: Descriptions, prerequisites, and learning objectives
- **Progress Tracking**: Track completion status for each section

### 2. Navigation Management
- **Site Navigation**: Manage main site navigation menus
- **Breadcrumb Navigation**: Automatic breadcrumb generation
- **Section Links**: Dynamic section-based navigation
- **Mobile Navigation**: Responsive navigation for mobile devices

### 3. Content Organization
- **Section Categories**: Categorize sections by type or subject
- **Access Control**: Control section visibility and access
- **Section Templates**: Different display templates for sections
- **Content Aggregation**: Aggregate content from multiple sources

## Database Schema

### tb_sections Table
```sql
CREATE TABLE `tb_sections` (
    `id` int NOT NULL AUTO_INCREMENT,
    `course_id` int DEFAULT NULL,
    `section_link` varchar(255) NOT NULL,
    `section_title` varchar(255) NOT NULL,
    `section_desc` text,
    `icon` varchar(100) DEFAULT NULL,
    `sort_order` int DEFAULT 0,
    `parent_id` int DEFAULT 0,
    `active` tinyint(1) DEFAULT 1,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `section_type` enum('course','navigation','category','custom') DEFAULT 'course',
    `access_level` enum('public','members','premium','admin') DEFAULT 'public',
    `prerequisites` json DEFAULT NULL,
    `learning_objectives` json DEFAULT NULL,
    `estimated_duration` int DEFAULT NULL,
    `completion_criteria` json DEFAULT NULL,
    `template` varchar(100) DEFAULT 'default',
    `meta_data` json DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `section_link` (`section_link`),
    KEY `idx_course_sort` (`course_id`, `sort_order`),
    KEY `idx_parent_sort` (`parent_id`, `sort_order`),
    KEY `idx_type_active` (`section_type`, `active`),
    KEY `idx_access_level` (`access_level`),
    FOREIGN KEY (`course_id`) REFERENCES `tb_courses` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`parent_id`) REFERENCES `tb_sections` (`id`) ON DELETE CASCADE
);
```

## API Endpoints

### Public Section API
```php
// Section browsing
GET /sections
GET /section/{section_link}
GET /sections/course/{course_id}
GET /sections/navigation

// Section content
GET /sections/{section_id}/units
GET /sections/{section_id}/progress
GET /sections/breadcrumb/{section_id}
```

### Admin Section API
```php
// Section management
GET /dt_admin/sections
POST /dt_admin/sections/create
GET /dt_admin/sections/edit/{id}
POST /dt_admin/sections/update/{id}
POST /dt_admin/sections/delete/{id}

// Section organization
POST /dt_admin/sections/reorder
POST /dt_admin/sections/move
GET /dt_admin/sections/tree/{course_id}

// Navigation management
GET /dt_admin/sections/navigation
POST /dt_admin/sections/update-navigation
```

## Section Types

### Course Sections
```php
// Course content sections
$courseSectionTypes = [
    'introduction' => 'Course Introduction',
    'module' => 'Learning Module', 
    'chapter' => 'Course Chapter',
    'unit' => 'Learning Unit',
    'assessment' => 'Assessment Section',
    'resources' => 'Additional Resources',
    'conclusion' => 'Course Conclusion'
];
```

### Navigation Sections
```php
// Site navigation sections
$navigationSectionTypes = [
    'main_menu' => 'Main Navigation Menu',
    'footer_menu' => 'Footer Navigation',
    'sidebar_menu' => 'Sidebar Navigation',
    'breadcrumb' => 'Breadcrumb Navigation',
    'mobile_menu' => 'Mobile Navigation'
];
```

## Features Implementation

### Section Management
```php
// Create new section
public function createSection($sectionData) {
    $validation = [
        'section_link' => 'required|is_unique[tb_sections.section_link]|alpha_dash',
        'section_title' => 'required|min_length[3]|max_length[255]',
        'section_type' => 'required|in_list[course,navigation,category,custom]',
        'course_id' => 'permit_empty|integer'
    ];
    
    if (!$this->validate($validation)) {
        return ['success' => false, 'errors' => $this->validator->getErrors()];
    }
    
    // Generate sort order if not provided
    if (!isset($sectionData['sort_order'])) {
        $sectionData['sort_order'] = $this->getNextSortOrder(
            $sectionData['course_id'] ?? null, 
            $sectionData['parent_id'] ?? 0
        );
    }
    
    // Set default values
    $sectionData['created_at'] = date('Y-m-d H:i:s');
    $sectionData['updated_at'] = date('Y-m-d H:i:s');
    
    $sectionId = $this->insert($sectionData);
    
    if ($sectionId) {
        // Update course structure if course section
        if (!empty($sectionData['course_id'])) {
            $this->updateCourseStructure($sectionData['course_id']);
        }
        
        // Clear navigation cache
        $this->clearNavigationCache();
        
        return ['success' => true, 'section_id' => $sectionId];
    }
    
    return ['success' => false, 'message' => 'Failed to create section'];
}
```

### Section Hierarchy Management
```php
// Get section tree structure
public function getSectionTree($courseId = null, $parentId = 0) {
    $builder = $this->where('parent_id', $parentId)
                   ->where('active', 1)
                   ->orderBy('sort_order', 'ASC');
    
    if ($courseId) {
        $builder->where('course_id', $courseId);
    }
    
    $sections = $builder->findAll();
    
    foreach ($sections as &$section) {
        // Get child sections recursively
        $section->children = $this->getSectionTree($courseId, $section->id);
        
        // Get section units if course section
        if ($section->section_type === 'course') {
            $section->units = $this->unitsModel->where('section_id', $section->id)
                                              ->where('active', 1)
                                              ->orderBy('sort_order', 'ASC')
                                              ->findAll();
        }
    }
    
    return $sections;
}
```

### Navigation Generation
```php
// Generate navigation menu
public function generateNavigation($type = 'main_menu') {
    $sections = $this->where('section_type', 'navigation')
                    ->where('template', $type)
                    ->where('active', 1)
                    ->orderBy('sort_order', 'ASC')
                    ->findAll();
    
    $navigation = [];
    
    foreach ($sections as $section) {
        $navItem = [
            'id' => $section->id,
            'title' => $section->section_title,
            'url' => $this->generateSectionUrl($section),
            'icon' => $section->icon,
            'children' => $this->getNavigationChildren($section->id),
            'active' => $this->isCurrentSection($section),
            'access_level' => $section->access_level
        ];
        
        // Check access permissions
        if ($this->canAccessSection($section)) {
            $navigation[] = $navItem;
        }
    }
    
    return $navigation;
}
```

### Breadcrumb Generation
```php
// Generate breadcrumb navigation
public function generateBreadcrumb($sectionId) {
    $breadcrumb = [];
    $currentSection = $this->find($sectionId);
    
    if (!$currentSection) {
        return $breadcrumb;
    }
    
    // Build breadcrumb path
    $path = $this->getSectionPath($sectionId);
    
    // Add home link
    $breadcrumb[] = [
        'title' => 'Home',
        'url' => base_url(),
        'active' => false
    ];
    
    // Add course link if course section
    if ($currentSection->course_id) {
        $course = $this->coursesModel->find($currentSection->course_id);
        if ($course) {
            $breadcrumb[] = [
                'title' => $course->course_title,
                'url' => base_url('courses/course_view/' . $course->slug),
                'active' => false
            ];
        }
    }
    
    // Add section path
    foreach ($path as $index => $section) {
        $breadcrumb[] = [
            'title' => $section->section_title,
            'url' => $this->generateSectionUrl($section),
            'active' => ($index === count($path) - 1)
        ];
    }
    
    return $breadcrumb;
}
```

### Section Reordering
```php
// Reorder sections
public function reorderSections($sectionIds, $courseId = null, $parentId = 0) {
    $db = \Config\Database::connect();
    $db->transStart();
    
    try {
        foreach ($sectionIds as $index => $sectionId) {
            $updateData = [
                'sort_order' => $index + 1,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $this->update($sectionId, $updateData);
        }
        
        $db->transComplete();
        
        if ($db->transStatus() === false) {
            throw new \Exception('Transaction failed');
        }
        
        // Update course structure if needed
        if ($courseId) {
            $this->updateCourseStructure($courseId);
        }
        
        // Clear cache
        $this->clearNavigationCache();
        
        return ['success' => true, 'message' => 'Sections reordered successfully'];
        
    } catch (\Exception $e) {
        $db->transRollback();
        return ['success' => false, 'message' => 'Failed to reorder sections: ' . $e->getMessage()];
    }
}
```

## Security Features

### Access Control
- **Section Permissions**: Control access based on user roles and permissions
- **Course Enrollment**: Verify course enrollment for course sections
- **Premium Content**: Restrict access to premium sections
- **Admin Authorization**: Restrict admin functions to authorized users

### Data Protection
- **Input Validation**: Validate all section data
- **XSS Prevention**: Escape output data
- **SQL Injection Prevention**: Use parameterized queries
- **Access Logging**: Log section access and modifications

## Integration Points

### Course Module Integration
```php
// Get course sections with progress
public function getCourseSectionsWithProgress($courseId, $userId) {
    $sections = $this->where('course_id', $courseId)
                    ->where('active', 1)
                    ->orderBy('sort_order', 'ASC')
                    ->findAll();
    
    foreach ($sections as &$section) {
        // Get section units
        $section->units = $this->unitsModel->where('section_id', $section->id)
                                          ->where('active', 1)
                                          ->orderBy('sort_order', 'ASC')
                                          ->findAll();
        
        // Calculate section progress
        $section->progress = $this->calculateSectionProgress($section->id, $userId);
        
        // Check section completion
        $section->is_completed = $this->isSectionCompleted($section->id, $userId);
        
        // Check section access
        $section->can_access = $this->canAccessSection($section, $userId);
    }
    
    return $sections;
}
```

### Progress Module Integration
```php
// Calculate section progress
public function calculateSectionProgress($sectionId, $userId) {
    $units = $this->unitsModel->where('section_id', $sectionId)
                             ->where('active', 1)
                             ->findAll();
    
    if (empty($units)) {
        return 0;
    }
    
    $totalUnits = count($units);
    $completedUnits = 0;
    
    foreach ($units as $unit) {
        if ($this->progressModel->isUnitCompleted($userId, $unit->id)) {
            $completedUnits++;
        }
    }
    
    return $totalUnits > 0 ? round(($completedUnits / $totalUnits) * 100, 2) : 0;
}
```

### Navigation Integration
```php
// Update site navigation
public function updateSiteNavigation() {
    // Get main navigation sections
    $mainNav = $this->generateNavigation('main_menu');
    
    // Get footer navigation
    $footerNav = $this->generateNavigation('footer_menu');
    
    // Cache navigation data
    $navigationData = [
        'main_navigation' => $mainNav,
        'footer_navigation' => $footerNav,
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    cache()->save('site_navigation', $navigationData, 3600);
    
    return $navigationData;
}
```

## Performance Optimization

### Caching Strategy
- **Navigation Cache**: Cache navigation menus for 1 hour
- **Section Tree**: Cache section hierarchies for 30 minutes
- **Breadcrumbs**: Cache breadcrumb paths for 15 minutes

### Database Optimization
- **Hierarchical Queries**: Optimize parent-child queries
- **Sort Order Indexes**: Index sort_order columns
- **Composite Indexes**: Optimize course-section queries

## Configuration

### Environment Variables
```env
# Section Settings
SECTIONS_CACHE_DURATION=3600
SECTIONS_MAX_DEPTH=5
SECTIONS_AUTO_SORT=true
SECTIONS_DEFAULT_TEMPLATE=default

# Navigation Settings
NAVIGATION_CACHE_DURATION=3600
NAVIGATION_MAX_ITEMS=50
NAVIGATION_MOBILE_BREAKPOINT=768

# Access Control
SECTIONS_CHECK_ENROLLMENT=true
SECTIONS_ENFORCE_PREREQUISITES=true
```

## Usage Examples

### Creating Course Sections
```php
// Create course introduction section
$sectionData = [
    'course_id' => $courseId,
    'section_link' => 'introduction',
    'section_title' => 'Course Introduction',
    'section_desc' => 'Welcome to the course',
    'section_type' => 'course',
    'sort_order' => 1,
    'active' => 1
];

$sectionId = $sectionsModel->insert($sectionData);
```

### Building Navigation
```php
// Get main navigation
$mainNavigation = $sectionsModel->generateNavigation('main_menu');

// Display navigation
foreach ($mainNavigation as $navItem) {
    echo '<a href="' . $navItem['url'] . '" class="nav-link">';
    if ($navItem['icon']) {
        echo '<i class="' . $navItem['icon'] . '"></i> ';
    }
    echo $navItem['title'] . '</a>';
}
```

### Section Tree Display
```php
// Get course section tree
$sectionTree = $sectionsModel->getSectionTree($courseId);

// Recursive function to display tree
function displaySectionTree($sections, $level = 0) {
    foreach ($sections as $section) {
        echo str_repeat('  ', $level) . '- ' . $section->section_title . "\n";
        
        if (!empty($section->children)) {
            displaySectionTree($section->children, $level + 1);
        }
    }
}

displaySectionTree($sectionTree);
```

## Testing Strategy

### Unit Tests
- Section CRUD operations
- Hierarchy management
- Navigation generation
- Progress calculation

### Integration Tests
- Course integration
- Progress tracking
- Access control
- Cache functionality

### Performance Tests
- Navigation generation performance
- Hierarchical query performance
- Cache effectiveness
- Large dataset handling

## Monitoring & Analytics

### Section Metrics
- **Section Popularity**: Track most accessed sections
- **Completion Rates**: Monitor section completion statistics
- **Navigation Usage**: Track navigation click patterns
- **Performance Metrics**: Monitor section load times

### Learning Analytics
- **Learning Paths**: Track user progression through sections
- **Drop-off Points**: Identify where users typically stop
- **Time Spent**: Monitor time spent in each section
- **Engagement Patterns**: Analyze user interaction patterns

## Future Enhancements

### Planned Features
- **Dynamic Sections**: AI-generated section recommendations
- **Adaptive Navigation**: Personalized navigation based on user behavior
- **Section Templates**: Advanced section layout templates
- **Interactive Sections**: Rich interactive section content

### Advanced Features
- **Section Analytics**: Advanced section performance analytics
- **A/B Testing**: Test different section organizations
- **Personalization**: User-specific section customization
- **Mobile Optimization**: Enhanced mobile section experience

## Dependencies

### Required Libraries
- CodeIgniter 4.x framework
- Course module for course integration
- Units module for content organization
- Progress module for tracking

### Optional Integrations
- Analytics platforms
- A/B testing tools
- Personalization engines
- Mobile app frameworks

## Troubleshooting

### Common Issues
1. **Navigation Not Updating**: Check cache clearing and navigation generation
2. **Section Order Issues**: Verify sort_order values and reordering logic
3. **Access Denied**: Check section access levels and user permissions
4. **Performance Issues**: Review query optimization and caching

### Debug Tools
- Section hierarchy visualizer
- Navigation debugging tools
- Performance profiling
- Access control testing
