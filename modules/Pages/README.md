# Pages Module Technical Specification

## Overview
The Pages module provides static page management functionality for the MSARLink e-learning platform, allowing administrators to create and manage static content pages such as About Us, Terms of Service, Privacy Policy, and other informational pages.

## Architecture

### Controllers
- **Pages.php**: Main pages controller for public page display
- **AdminPages.php**: Administrative page management interface

### Models
- **PagesModel.php**: Core page data management

### Views
- **Site/**: Public page display templates
- **Admin/**: Administrative page management interface

## Key Features

### 1. Static Page Management
- **CRUD Operations**: Create, read, update, delete static pages
- **Rich Content Editor**: Full-featured content editing with WYSIWYG editor
- **SEO Optimization**: Meta descriptions, keywords, and SEO-friendly URLs
- **Page Hierarchy**: Support for parent-child page relationships

### 2. Content Organization
- **Page Categories**: Organize pages into categories
- **Navigation Integration**: Automatic navigation menu integration
- **Page Templates**: Multiple page layout templates
- **Content Blocks**: Reusable content components

### 3. Multilingual Support
- **Arabic Content**: Native Arabic language support
- **English Content**: English language support
- **RTL Layout**: Right-to-left layout support for Arabic content
- **Language Switching**: Dynamic language switching

## Database Schema

### pages Table
```sql
CREATE TABLE `pages` (
    `id` int NOT NULL AUTO_INCREMENT,
    `page_link` varchar(255) NOT NULL,
    `title` varchar(255) NOT NULL,
    `desc` mediumtext,
    `content` mediumtext,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `active` tinyint(1) NOT NULL DEFAULT '0',
    `show_home` tinyint(1) NOT NULL DEFAULT '0',
    `images` longtext,
    `sort` int NOT NULL DEFAULT '0',
    `parent_id` int NOT NULL DEFAULT '0',
    `meta_title` varchar(255) DEFAULT NULL,
    `meta_description` text,
    `meta_keywords` text,
    `template` varchar(100) DEFAULT 'default',
    `featured_image` varchar(255) DEFAULT NULL,
    `page_type` enum('page','landing','popup','modal') DEFAULT 'page',
    `access_level` enum('public','members','admin') DEFAULT 'public',
    PRIMARY KEY (`id`),
    UNIQUE KEY `page_link` (`page_link`),
    KEY `idx_active_sort` (`active`, `sort`),
    KEY `idx_parent` (`parent_id`),
    KEY `idx_show_home` (`show_home`),
    KEY `idx_page_type` (`page_type`),
    KEY `idx_access_level` (`access_level`)
);
```

## API Endpoints

### Public Page API
```php
// Page browsing
GET /pages
GET /page/{page_link}
GET /pages/category/{category}
GET /pages/sitemap

// Special pages
GET /about
GET /terms-and-conditions
GET /privacy-policy
GET /contact
```

### Admin Page API
```php
// Page management
GET /dt_admin/pages
POST /dt_admin/pages/create
GET /dt_admin/pages/edit/{id}
POST /dt_admin/pages/update/{id}
POST /dt_admin/pages/delete/{id}

// Content management
POST /dt_admin/pages/upload-image
POST /dt_admin/pages/bulk-update
GET /dt_admin/pages/preview/{id}

// SEO management
GET /dt_admin/pages/seo/{id}
POST /dt_admin/pages/update-seo/{id}
```

## Default Pages

### System Pages
```php
// Default system pages
$defaultPages = [
    [
        'page_link' => 'about',
        'title' => 'About Us',
        'desc' => 'Learn more about MSARLink',
        'content' => '<p>About MSARLink content...</p>',
        'active' => 1,
        'show_home' => 1,
        'sort' => 1
    ],
    [
        'page_link' => 'terms-and-conditions',
        'title' => 'Terms and Conditions',
        'desc' => 'Terms of service and usage conditions',
        'content' => '<p>Terms and conditions content...</p>',
        'active' => 1,
        'sort' => 2
    ],
    [
        'page_link' => 'privacy-policy',
        'title' => 'Privacy Policy',
        'desc' => 'Privacy policy and data protection',
        'content' => '<p>Privacy policy content...</p>',
        'active' => 1,
        'sort' => 3
    ]
];
```

## Features Implementation

### Page Management
```php
// Create new page
public function createPage($pageData) {
    $validation = [
        'page_link' => 'required|is_unique[pages.page_link]|alpha_dash',
        'title' => 'required|min_length[3]|max_length[255]',
        'content' => 'required|min_length[10]'
    ];
    
    if (!$this->validate($validation)) {
        return ['success' => false, 'errors' => $this->validator->getErrors()];
    }
    
    // Generate SEO-friendly slug if not provided
    if (empty($pageData['page_link'])) {
        $pageData['page_link'] = $this->generateSlug($pageData['title']);
    }
    
    // Set default values
    $pageData['created_at'] = date('Y-m-d H:i:s');
    $pageData['updated_at'] = date('Y-m-d H:i:s');
    
    $pageId = $this->insert($pageData);
    
    if ($pageId) {
        // Process images if any
        if (!empty($pageData['images'])) {
            $this->processPageImages($pageId, $pageData['images']);
        }
        
        // Clear page cache
        $this->clearPageCache();
        
        return ['success' => true, 'page_id' => $pageId];
    }
    
    return ['success' => false, 'message' => 'Failed to create page'];
}
```

### SEO Management
```php
// Generate SEO-friendly slug
public function generateSlug($title) {
    $slug = strtolower($title);
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    $slug = trim($slug, '-');
    
    // Ensure uniqueness
    $originalSlug = $slug;
    $counter = 1;
    
    while ($this->where('page_link', $slug)->first()) {
        $slug = $originalSlug . '-' . $counter;
        $counter++;
    }
    
    return $slug;
}

// Update SEO metadata
public function updateSEO($pageId, $seoData) {
    $updateData = [
        'meta_title' => $seoData['meta_title'] ?? null,
        'meta_description' => $seoData['meta_description'] ?? null,
        'meta_keywords' => $seoData['meta_keywords'] ?? null,
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    return $this->update($pageId, $updateData);
}
```

### Content Processing
```php
// Process page content
public function processContent($content) {
    // Clean HTML content
    $content = $this->cleanHTML($content);
    
    // Process shortcodes
    $content = $this->processShortcodes($content);
    
    // Optimize images
    $content = $this->optimizeImages($content);
    
    return $content;
}

// Clean HTML content
private function cleanHTML($content) {
    // Allow specific HTML tags
    $allowedTags = '<p><br><strong><em><ul><ol><li><h1><h2><h3><h4><h5><h6><a><img><div><span><blockquote>';
    
    return strip_tags($content, $allowedTags);
}

// Process shortcodes
private function processShortcodes($content) {
    // Process [contact-form] shortcode
    $content = preg_replace('/\[contact-form\]/', $this->renderContactForm(), $content);
    
    // Process [course-list] shortcode
    $content = preg_replace('/\[course-list\]/', $this->renderCourseList(), $content);
    
    return $content;
}
```

### Navigation Integration
```php
// Get navigation pages
public function getNavigationPages() {
    return $this->where('active', 1)
                ->where('show_home', 1)
                ->orderBy('sort', 'ASC')
                ->findAll();
}

// Build navigation menu
public function buildNavigationMenu() {
    $pages = $this->getNavigationPages();
    $menu = [];
    
    foreach ($pages as $page) {
        $menu[] = [
            'title' => $page->title,
            'url' => base_url('page/' . $page->page_link),
            'active' => (current_url() === base_url('page/' . $page->page_link))
        ];
    }
    
    return $menu;
}
```

## Security Features

### Access Control
- **Admin Authorization**: Page management restricted to administrators
- **Content Validation**: Validate and sanitize all page content
- **XSS Prevention**: Escape output and clean HTML content
- **Access Levels**: Control page access based on user roles

### Content Security
- **HTML Sanitization**: Clean and validate HTML content
- **File Upload Security**: Secure image upload with validation
- **SQL Injection Prevention**: Parameterized queries
- **CSRF Protection**: Cross-site request forgery protection

## Integration Points

### Course Module Integration
```php
// Create course-related pages
public function createCoursePages($courseId) {
    $course = $this->coursesModel->find($courseId);
    
    $pageData = [
        'page_link' => 'course-' . $course->slug,
        'title' => $course->course_title,
        'desc' => $course->short_desc,
        'content' => $this->generateCoursePageContent($course),
        'page_type' => 'landing',
        'active' => 1
    ];
    
    return $this->createPage($pageData);
}
```

### User Module Integration
```php
// Check page access permissions
public function canAccessPage($pageId, $userId = null) {
    $page = $this->find($pageId);
    
    if (!$page || !$page->active) {
        return false;
    }
    
    switch ($page->access_level) {
        case 'public':
            return true;
            
        case 'members':
            return $userId !== null;
            
        case 'admin':
            return $userId && $this->usersModel->isAdmin($userId);
            
        default:
            return false;
    }
}
```

### Search Module Integration
```php
// Index pages for search
public function indexPagesForSearch() {
    $pages = $this->where('active', 1)
                  ->where('access_level', 'public')
                  ->findAll();
    
    foreach ($pages as $page) {
        $searchData = [
            'type' => 'page',
            'id' => $page->id,
            'title' => $page->title,
            'content' => strip_tags($page->content),
            'url' => base_url('page/' . $page->page_link),
            'keywords' => $page->meta_keywords
        ];
        
        $this->searchModel->indexContent($searchData);
    }
}
```

## Performance Optimization

### Caching Strategy
- **Page Content**: Cache rendered pages for 1 hour
- **Navigation Menu**: Cache navigation data for 30 minutes
- **SEO Data**: Cache meta information for 2 hours

### Database Optimization
- **Indexed Queries**: Optimize for page link and status queries
- **Query Optimization**: Efficient page retrieval queries
- **Content Compression**: Compress large content blocks

## Page Templates

### Template System
```php
// Available page templates
$templates = [
    'default' => 'Default Page Template',
    'landing' => 'Landing Page Template',
    'full-width' => 'Full Width Template',
    'sidebar' => 'Sidebar Template',
    'contact' => 'Contact Page Template'
];

// Render page with template
public function renderPage($pageId, $template = null) {
    $page = $this->find($pageId);
    
    if (!$page) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException();
    }
    
    $template = $template ?? $page->template ?? 'default';
    
    $data = [
        'page' => $page,
        'meta_title' => $page->meta_title ?? $page->title,
        'meta_description' => $page->meta_description ?? $page->desc,
        'meta_keywords' => $page->meta_keywords
    ];
    
    return view("Modules\\Pages\\Views\\Site\\templates\\{$template}", $data);
}
```

## Configuration

### Environment Variables
```env
# Page Settings
PAGES_CACHE_DURATION=3600
PAGES_IMAGE_MAX_SIZE=5MB
PAGES_CONTENT_MAX_LENGTH=50000
PAGES_AUTO_SLUG=true

# SEO Settings
PAGES_AUTO_META=true
PAGES_META_DESCRIPTION_LENGTH=160
PAGES_META_KEYWORDS_MAX=10

# Security Settings
PAGES_HTML_PURIFIER=true
PAGES_ALLOWED_TAGS=p,br,strong,em,ul,ol,li,h1,h2,h3,h4,h5,h6,a,img
```

## Usage Examples

### Creating a Page
```php
// Create about page
$pageData = [
    'page_link' => 'about-us',
    'title' => 'About MSARLink',
    'desc' => 'Learn about our e-learning platform',
    'content' => '<h1>About Us</h1><p>MSARLink is...</p>',
    'meta_title' => 'About MSARLink - E-Learning Platform',
    'meta_description' => 'Learn about MSARLink e-learning platform...',
    'active' => 1,
    'show_home' => 1,
    'sort' => 1
];

$pageId = $pagesModel->insert($pageData);
```

### Displaying a Page
```php
// Get page by link
$page = $pagesModel->where('page_link', $pageLink)
                   ->where('active', 1)
                   ->first();

if ($page) {
    return $pagesModel->renderPage($page->id);
} else {
    throw new \CodeIgniter\Exceptions\PageNotFoundException();
}
```

### Building Navigation
```php
// Get navigation menu
$navigationMenu = $pagesModel->buildNavigationMenu();

// Display in view
foreach ($navigationMenu as $menuItem) {
    echo '<a href="' . $menuItem['url'] . '" class="' . 
         ($menuItem['active'] ? 'active' : '') . '">' . 
         $menuItem['title'] . '</a>';
}
```

## Testing Strategy

### Unit Tests
- Page CRUD operations
- SEO slug generation
- Content processing and sanitization
- Template rendering

### Integration Tests
- Navigation menu generation
- Search integration
- Access control validation
- Cache functionality

### Security Tests
- HTML sanitization
- XSS prevention
- Access control bypass attempts
- File upload security

## Monitoring & Analytics

### Page Metrics
- **Page Views**: Track page popularity
- **Bounce Rate**: Monitor user engagement
- **Load Times**: Page performance metrics
- **SEO Performance**: Search engine rankings

### Content Analytics
- **Content Effectiveness**: Track user interaction
- **Popular Pages**: Identify most visited pages
- **Search Queries**: Track internal search terms
- **Conversion Rates**: Track page goal completions

## Future Enhancements

### Planned Features
- **Page Builder**: Drag-and-drop page builder
- **Version Control**: Page revision history
- **A/B Testing**: Page variant testing
- **Advanced SEO**: Schema markup and rich snippets

### Content Features
- **Dynamic Content**: Database-driven content blocks
- **Personalization**: User-specific content
- **Multilingual CMS**: Advanced translation management
- **Content Scheduling**: Scheduled content publication

## Dependencies

### Required Libraries
- CodeIgniter 4.x framework
- Shield authentication library
- FireUploader for image management
- HTML Purifier for content sanitization

### Optional Integrations
- Rich text editor (CKEditor/TinyMCE)
- Image optimization libraries
- SEO analysis tools
- Analytics platforms

## Troubleshooting

### Common Issues
1. **Page Not Found**: Check page link and active status
2. **Content Not Displaying**: Verify HTML sanitization settings
3. **SEO Issues**: Check meta tag generation and templates
4. **Performance Problems**: Review caching configuration

### Debug Tools
- Page rendering debugging
- Content processing logging
- SEO analysis tools
- Performance profiling
