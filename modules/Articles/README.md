# Articles Module Technical Specification

## Overview
The Articles module provides content management functionality for the MSARLink e-learning platform, allowing administrators to create, manage, and publish articles and blog posts.

## Architecture

### Controllers
- **Articles.php**: Main articles controller for public article display
- **AdminArticles.php**: Administrative article management interface

### Models
- **ArticlesModel.php**: Core article data management

### Views
- **Site/**: Public article display templates
- **Admin/**: Administrative article management interface

## Key Features

### 1. Article Management
- **CRUD Operations**: Create, read, update, delete articles
- **Rich Text Editor**: Full-featured content editing
- **Image Management**: Article image upload and management
- **SEO Optimization**: Meta descriptions, slugs, and SEO-friendly URLs
- **Publication Control**: Draft/published status management

### 2. Content Organization
- **Categories**: Article categorization system
- **Tags**: Flexible tagging system for content organization
- **Sorting**: Custom article ordering and priority
- **Search**: Full-text search capabilities

### 3. Multilingual Support
- **Arabic Content**: Native Arabic language support
- **English Content**: English language support
- **RTL Layout**: Right-to-left layout support for Arabic

## Database Schema

### articles Table
```sql
CREATE TABLE `articles` (
    `id` int NOT NULL AUTO_INCREMENT,
    `title` varchar(100) NOT NULL,
    `slug` varchar(100) DEFAULT NULL,
    `description` text,
    `content` text NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `active` tinyint(1) NOT NULL DEFAULT '0',
    `sort` int NOT NULL DEFAULT '1',
    `image` json DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_active_sort` (`active`, `sort`),
    KEY `idx_slug` (`slug`),
    KEY `idx_created_date` (`created_at`)
);
```

## API Endpoints

### Public Article API
```php
// Article browsing
GET /articles
GET /articles/view/{slug}
GET /articles/category/{category}
GET /articles/search?q={query}

// RSS/Feed
GET /articles/feed
GET /articles/sitemap
```

### Admin Article API
```php
// Article management
GET /dt_admin/articles
POST /dt_admin/articles/create
GET /dt_admin/articles/edit/{id}
POST /dt_admin/articles/update/{id}
POST /dt_admin/articles/delete/{id}

// Bulk operations
POST /dt_admin/articles/bulk-delete
POST /dt_admin/articles/bulk-publish
```

## Features Implementation

### Content Editor
- **WYSIWYG Editor**: Rich text editing with formatting options
- **Image Upload**: Drag-and-drop image upload with automatic resizing
- **Media Library**: Centralized media management
- **Code Highlighting**: Syntax highlighting for code blocks

### SEO Features
- **URL Slugs**: SEO-friendly URL generation
- **Meta Tags**: Custom meta descriptions and keywords
- **Open Graph**: Social media sharing optimization
- **Schema Markup**: Structured data for search engines

### Publication Workflow
- **Draft System**: Save articles as drafts before publishing
- **Scheduling**: Schedule articles for future publication
- **Version Control**: Track article revisions and changes
- **Author Management**: Multi-author support with attribution

## Security Features

### Access Control
- **Admin Authorization**: Article management restricted to admins
- **Content Validation**: Input sanitization and validation
- **XSS Protection**: Cross-site scripting prevention
- **CSRF Protection**: Cross-site request forgery protection

### Content Security
- **HTML Sanitization**: Safe HTML content filtering
- **File Upload Security**: Secure image upload with type validation
- **SQL Injection Prevention**: Parameterized queries

## Integration Points

### Course Module Integration
- **Course Articles**: Link articles to specific courses
- **Learning Resources**: Articles as supplementary learning materials
- **Course Announcements**: Course-specific article publishing

### User Module Integration
- **Author Profiles**: Article author information display
- **User Preferences**: Personalized article recommendations
- **Reading History**: Track user article reading patterns

### Search Module Integration
- **Full-text Search**: Article content indexing
- **Search Analytics**: Track popular search terms
- **Related Content**: Suggest related articles

## Performance Optimization

### Caching Strategy
- **Article Caching**: Cache published articles for 1 hour
- **Category Caching**: Cache category listings for 30 minutes
- **Search Results**: Cache search results for 15 minutes

### Database Optimization
- **Indexed Queries**: Optimized indexes for common queries
- **Query Optimization**: Efficient database queries
- **Pagination**: Large article lists paginated for performance

## Configuration

### Environment Variables
```env
# Article Settings
ARTICLES_PER_PAGE=10
ARTICLE_CACHE_DURATION=3600
ARTICLE_IMAGE_MAX_SIZE=5MB
ARTICLE_EXCERPT_LENGTH=200

# Editor Settings
EDITOR_UPLOAD_PATH=uploads/articles
EDITOR_ALLOWED_TAGS=p,br,strong,em,ul,ol,li,h1,h2,h3,h4,h5,h6
```

## Usage Examples

### Creating an Article
```php
$articleData = [
    'title' => 'Introduction to Programming',
    'slug' => 'introduction-to-programming',
    'description' => 'Learn the basics of programming...',
    'content' => '<p>Programming is...</p>',
    'active' => 1,
    'sort' => 1
];

$articleId = $articlesModel->insert($articleData);
```

### Displaying Articles
```php
// Get published articles
$articles = $articlesModel->where('active', 1)
                         ->orderBy('sort', 'ASC')
                         ->paginate(10);

// Get article by slug
$article = $articlesModel->where('slug', $slug)
                        ->where('active', 1)
                        ->first();
```

## Testing Strategy

### Unit Tests
- Article CRUD operations
- Content validation and sanitization
- SEO slug generation
- Image upload functionality

### Integration Tests
- Admin interface functionality
- Public article display
- Search functionality
- Cache invalidation

## Monitoring & Analytics

### Content Analytics
- **Page Views**: Track article popularity
- **Reading Time**: Average time spent reading
- **Engagement Metrics**: Comments, shares, likes
- **Search Performance**: Article findability metrics

### Performance Monitoring
- **Load Times**: Article page load performance
- **Cache Hit Rates**: Caching effectiveness
- **Database Performance**: Query execution times

## Future Enhancements

### Planned Features
- **Comment System**: User comments and discussions
- **Social Sharing**: Enhanced social media integration
- **Newsletter Integration**: Article newsletter distribution
- **Advanced Analytics**: Detailed content performance metrics

### Content Features
- **Video Embedding**: Rich media content support
- **Interactive Elements**: Polls, quizzes within articles
- **Collaborative Editing**: Multi-author collaborative editing
- **Content Templates**: Pre-designed article templates

## Dependencies

### Required Libraries
- CodeIgniter 4.x framework
- Shield authentication library
- FireUploader for image management
- DtTable for admin data tables

### Optional Integrations
- Rich text editor (CKEditor/TinyMCE)
- Image optimization libraries
- Search engine integration
- Social media APIs

## Troubleshooting

### Common Issues
1. **Images Not Displaying**: Check upload permissions and file paths
2. **Slug Conflicts**: Ensure unique slug generation
3. **Editor Issues**: Verify JavaScript dependencies
4. **Search Problems**: Check search index integrity

### Debug Tools
- Article creation/edit logging
- Image upload debugging
- Search query logging
- Performance profiling tools