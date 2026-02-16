# Search Module Technical Specification

## Overview
The Search module provides comprehensive search functionality for the MSARLink e-learning platform, enabling users to search across courses, articles, pages, and other content with advanced filtering and relevance ranking.

## Architecture

### Controllers
- **Search.php**: Main search controller for public search functionality
- **AdminSearch.php**: Administrative search management and analytics

### Models
- **SearchModel.php**: Core search data management and indexing

### Views
- **Site/**: Public search interfaces and results
- **Admin/**: Administrative search management interface

## Key Features

### 1. Full-Text Search
- **Multi-content Search**: Search across courses, articles, pages, and videos
- **Relevance Ranking**: Advanced scoring algorithm for search results
- **Fuzzy Matching**: Handle typos and similar terms
- **Auto-complete**: Real-time search suggestions

### 2. Advanced Filtering
- **Content Type Filters**: Filter by courses, articles, pages, etc.
- **Category Filters**: Filter by course categories or content categories
- **Date Range Filters**: Filter by creation or update dates
- **Difficulty Level Filters**: Filter courses by difficulty

### 3. Search Analytics
- **Search Tracking**: Track search queries and results
- **Popular Searches**: Identify trending search terms
- **Search Performance**: Monitor search effectiveness
- **User Behavior**: Analyze search patterns

## Database Schema

### search_index Table
```sql
CREATE TABLE `search_index` (
    `id` int NOT NULL AUTO_INCREMENT,
    `content_type` enum('course','article','page','video','quiz') NOT NULL,
    `content_id` int NOT NULL,
    `title` varchar(255) NOT NULL,
    `content` longtext,
    `keywords` text,
    `url` varchar(500) NOT NULL,
    `image_url` varchar(500) DEFAULT NULL,
    `category` varchar(100) DEFAULT NULL,
    `difficulty_level` varchar(50) DEFAULT NULL,
    `price` decimal(10,2) DEFAULT NULL,
    `is_free` tinyint(1) DEFAULT 0,
    `active` tinyint(1) DEFAULT 1,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `search_weight` decimal(3,2) DEFAULT 1.00,
    `view_count` int DEFAULT 0,
    `click_count` int DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE KEY `content_unique` (`content_type`, `content_id`),
    KEY `idx_content_type` (`content_type`, `active`),
    KEY `idx_category` (`category`),
    KEY `idx_difficulty` (`difficulty_level`),
    KEY `idx_price` (`is_free`, `price`),
    FULLTEXT KEY `idx_fulltext` (`title`, `content`, `keywords`)
);
```

### search_queries Table
```sql
CREATE TABLE `search_queries` (
    `id` int NOT NULL AUTO_INCREMENT,
    `query` varchar(255) NOT NULL,
    `user_id` int DEFAULT NULL,
    `results_count` int DEFAULT 0,
    `clicked_result_id` int DEFAULT NULL,
    `search_time_ms` int DEFAULT NULL,
    `filters_used` json DEFAULT NULL,
    `ip_address` varchar(45) DEFAULT NULL,
    `user_agent` text,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_query` (`query`),
    KEY `idx_user` (`user_id`),
    KEY `idx_created_date` (`created_at`),
    KEY `idx_results_count` (`results_count`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`clicked_result_id`) REFERENCES `search_index` (`id`) ON DELETE SET NULL
);
```

## API Endpoints

### Public Search API
```php
// Search functionality
GET /search
POST /search/query
GET /search/suggestions?q={query}
GET /search/autocomplete?q={query}

// Search results
GET /search/results?q={query}&type={type}&category={category}
POST /search/track-click

// Popular searches
GET /search/popular
GET /search/trending
```

### Admin Search API
```php
// Search management
GET /dt_admin/search
POST /dt_admin/search/reindex
POST /dt_admin/search/optimize
GET /dt_admin/search/status

// Search analytics
GET /dt_admin/search/analytics
GET /dt_admin/search/queries
GET /dt_admin/search/popular-terms
GET /dt_admin/search/performance

// Index management
POST /dt_admin/search/rebuild-index
POST /dt_admin/search/update-weights
GET /dt_admin/search/index-stats
```

## Search Implementation

### Content Indexing
```php
// Index content for search
public function indexContent($contentType, $contentId, $data) {
    // Prepare search data
    $searchData = [
        'content_type' => $contentType,
        'content_id' => $contentId,
        'title' => $data['title'],
        'content' => $this->cleanContent($data['content']),
        'keywords' => $this->extractKeywords($data),
        'url' => $data['url'],
        'image_url' => $data['image_url'] ?? null,
        'category' => $data['category'] ?? null,
        'difficulty_level' => $data['difficulty_level'] ?? null,
        'price' => $data['price'] ?? null,
        'is_free' => $data['is_free'] ?? 0,
        'active' => $data['active'] ?? 1,
        'search_weight' => $this->calculateWeight($contentType, $data),
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    // Check if already indexed
    $existing = $this->where('content_type', $contentType)
                    ->where('content_id', $contentId)
                    ->first();
    
    if ($existing) {
        return $this->update($existing->id, $searchData);
    } else {
        $searchData['created_at'] = date('Y-m-d H:i:s');
        return $this->insert($searchData);
    }
}
```

### Search Query Processing
```php
// Process search query
public function search($query, $filters = [], $page = 1, $perPage = 20) {
    $startTime = microtime(true);
    
    // Clean and prepare query
    $cleanQuery = $this->cleanQuery($query);
    $searchTerms = $this->extractSearchTerms($cleanQuery);
    
    // Build search query
    $builder = $this->builder()
                   ->select('*, MATCH(title, content, keywords) AGAINST(? IN NATURAL LANGUAGE MODE) as relevance_score')
                   ->where('active', 1)
                   ->where('MATCH(title, content, keywords) AGAINST(? IN NATURAL LANGUAGE MODE)', [$cleanQuery])
                   ->orderBy('relevance_score', 'DESC')
                   ->orderBy('search_weight', 'DESC')
                   ->orderBy('view_count', 'DESC');
    
    // Apply filters
    $this->applyFilters($builder, $filters);
    
    // Get results
    $results = $builder->paginate($perPage, 'default', $page);
    
    // Calculate search time
    $searchTime = round((microtime(true) - $startTime) * 1000);
    
    // Log search query
    $this->logSearchQuery($query, count($results), $searchTime, $filters);
    
    // Enhance results
    $enhancedResults = $this->enhanceSearchResults($results, $searchTerms);
    
    return [
        'results' => $enhancedResults,
        'total' => $builder->countAllResults(false),
        'search_time' => $searchTime,
        'query' => $query,
        'suggestions' => $this->getSuggestions($query)
    ];
}
```

### Auto-complete Implementation
```php
// Get search suggestions
public function getSuggestions($query, $limit = 10) {
    $cleanQuery = $this->cleanQuery($query);
    
    if (strlen($cleanQuery) < 2) {
        return [];
    }
    
    // Get title-based suggestions
    $titleSuggestions = $this->select('title')
                            ->where('active', 1)
                            ->like('title', $cleanQuery, 'after')
                            ->orderBy('view_count', 'DESC')
                            ->limit($limit)
                            ->findAll();
    
    // Get keyword-based suggestions
    $keywordSuggestions = $this->select('keywords')
                              ->where('active', 1)
                              ->like('keywords', $cleanQuery, 'both')
                              ->orderBy('click_count', 'DESC')
                              ->limit($limit)
                              ->findAll();
    
    // Get popular query suggestions
    $popularSuggestions = $this->db->table('search_queries')
                                  ->select('query, COUNT(*) as frequency')
                                  ->like('query', $cleanQuery, 'after')
                                  ->groupBy('query')
                                  ->orderBy('frequency', 'DESC')
                                  ->limit($limit)
                                  ->get()
                                  ->getResultArray();
    
    // Combine and rank suggestions
    return $this->rankSuggestions($titleSuggestions, $keywordSuggestions, $popularSuggestions, $cleanQuery);
}
```

### Search Filters
```php
// Apply search filters
private function applyFilters($builder, $filters) {
    // Content type filter
    if (!empty($filters['type'])) {
        $builder->whereIn('content_type', (array)$filters['type']);
    }
    
    // Category filter
    if (!empty($filters['category'])) {
        $builder->whereIn('category', (array)$filters['category']);
    }
    
    // Difficulty level filter
    if (!empty($filters['difficulty'])) {
        $builder->whereIn('difficulty_level', (array)$filters['difficulty']);
    }
    
    // Price filter
    if (isset($filters['is_free'])) {
        $builder->where('is_free', $filters['is_free']);
    }
    
    if (!empty($filters['price_range'])) {
        $builder->where('price >=', $filters['price_range']['min'])
                ->where('price <=', $filters['price_range']['max']);
    }
    
    // Date range filter
    if (!empty($filters['date_from'])) {
        $builder->where('created_at >=', $filters['date_from']);
    }
    
    if (!empty($filters['date_to'])) {
        $builder->where('created_at <=', $filters['date_to']);
    }
}
```

## Security Features

### Search Security
- **Query Sanitization**: Clean and validate search queries
- **XSS Prevention**: Escape search results and highlights
- **SQL Injection Prevention**: Use parameterized queries
- **Rate Limiting**: Prevent search abuse and spam

### Content Security
- **Access Control**: Respect content access permissions
- **Content Filtering**: Filter results based on user permissions
- **Privacy Protection**: Exclude private or restricted content
- **Data Anonymization**: Anonymize search analytics data

## Integration Points

### Course Module Integration
```php
// Index course content
public function indexCourse($courseId) {
    $course = $this->coursesModel->find($courseId);
    
    if (!$course || !$course->active) {
        return $this->removeFromIndex('course', $courseId);
    }
    
    $searchData = [
        'title' => $course->course_title,
        'content' => $course->course_desc . ' ' . $course->short_desc,
        'url' => base_url('courses/course_details/' . $course->slug),
        'image_url' => $course->image ? json_decode($course->image, true)[0] ?? null : null,
        'category' => $course->category,
        'difficulty_level' => $course->difficulty_level,
        'price' => $course->price,
        'is_free' => $course->is_free,
        'active' => $course->active
    ];
    
    return $this->indexContent('course', $courseId, $searchData);
}
```

### Articles Module Integration
```php
// Index article content
public function indexArticle($articleId) {
    $article = $this->articlesModel->find($articleId);
    
    if (!$article || !$article->active) {
        return $this->removeFromIndex('article', $articleId);
    }
    
    $searchData = [
        'title' => $article->title,
        'content' => strip_tags($article->content),
        'url' => base_url('articles/view/' . $article->slug),
        'image_url' => $article->image ? json_decode($article->image, true)[0] ?? null : null,
        'category' => 'article',
        'active' => $article->active
    ];
    
    return $this->indexContent('article', $articleId, $searchData);
}
```

### Pages Module Integration
```php
// Index page content
public function indexPage($pageId) {
    $page = $this->pagesModel->find($pageId);
    
    if (!$page || !$page->active || $page->access_level !== 'public') {
        return $this->removeFromIndex('page', $pageId);
    }
    
    $searchData = [
        'title' => $page->title,
        'content' => strip_tags($page->content),
        'url' => base_url('page/' . $page->page_link),
        'category' => 'page',
        'active' => $page->active
    ];
    
    return $this->indexContent('page', $pageId, $searchData);
}
```

## Performance Optimization

### Search Performance
- **Full-text Indexes**: MySQL full-text search indexes
- **Query Caching**: Cache frequent search queries
- **Result Caching**: Cache search results for popular queries
- **Index Optimization**: Regular index maintenance and optimization

### Database Optimization
- **Composite Indexes**: Optimize for common filter combinations
- **Query Optimization**: Efficient search and filter queries
- **Pagination**: Efficient result pagination
- **Background Indexing**: Asynchronous content indexing

## Analytics Features

### Search Analytics
```php
// Get search analytics
public function getSearchAnalytics($dateRange = null) {
    $builder = $this->db->table('search_queries');
    
    if ($dateRange) {
        $builder->where('created_at >=', $dateRange['start'])
                ->where('created_at <=', $dateRange['end']);
    }
    
    return [
        'total_searches' => $builder->countAllResults(false),
        'unique_queries' => $builder->distinct()->countAllResults('query', false),
        'average_results' => $builder->selectAvg('results_count')->get()->getRow()->results_count ?? 0,
        'average_search_time' => $builder->selectAvg('search_time_ms')->get()->getRow()->search_time_ms ?? 0,
        'popular_queries' => $this->getPopularQueries($dateRange),
        'zero_result_queries' => $this->getZeroResultQueries($dateRange),
        'click_through_rate' => $this->getClickThroughRate($dateRange)
    ];
}
```

### Popular Searches
```php
// Get popular search queries
public function getPopularQueries($dateRange = null, $limit = 20) {
    $builder = $this->db->table('search_queries')
                       ->select('query, COUNT(*) as frequency, AVG(results_count) as avg_results')
                       ->where('results_count >', 0)
                       ->groupBy('query')
                       ->orderBy('frequency', 'DESC')
                       ->limit($limit);
    
    if ($dateRange) {
        $builder->where('created_at >=', $dateRange['start'])
                ->where('created_at <=', $dateRange['end']);
    }
    
    return $builder->get()->getResultArray();
}
```

## Configuration

### Environment Variables
```env
# Search Settings
SEARCH_RESULTS_PER_PAGE=20
SEARCH_MAX_QUERY_LENGTH=255
SEARCH_MIN_QUERY_LENGTH=2
SEARCH_CACHE_DURATION=300

# Indexing Settings
SEARCH_AUTO_INDEX=true
SEARCH_BATCH_SIZE=100
SEARCH_INDEX_WEIGHT_COURSE=1.5
SEARCH_INDEX_WEIGHT_ARTICLE=1.0
SEARCH_INDEX_WEIGHT_PAGE=0.8

# Performance Settings
SEARCH_ENABLE_FULLTEXT=true
SEARCH_ENABLE_FUZZY=true
SEARCH_RATE_LIMIT=60
```

## Usage Examples

### Basic Search
```php
// Perform search
$results = $searchModel->search('python programming', [], 1, 20);

// Display results
foreach ($results['results'] as $result) {
    echo '<h3><a href="' . $result->url . '">' . $result->title . '</a></h3>';
    echo '<p>' . $result->highlighted_content . '</p>';
}
```

### Advanced Search with Filters
```php
// Search with filters
$filters = [
    'type' => ['course', 'article'],
    'category' => 'programming',
    'difficulty' => 'beginner',
    'is_free' => true
];

$results = $searchModel->search('web development', $filters, 1, 10);
```

### Auto-complete
```php
// Get search suggestions
$suggestions = $searchModel->getSuggestions('prog', 5);

// Return as JSON for AJAX
return $this->response->setJSON($suggestions);
```

## Testing Strategy

### Unit Tests
- Search query processing
- Content indexing logic
- Filter application
- Relevance scoring

### Integration Tests
- Full search workflow
- Module integration
- Performance testing
- Analytics accuracy

### Performance Tests
- Search response times
- Index update performance
- Concurrent search handling
- Large dataset handling

## Monitoring & Logging

### Search Events Logged
- Search queries and results
- Index updates and rebuilds
- Performance metrics
- Error conditions

### Performance Monitoring
- Search response times
- Index size and growth
- Query frequency and patterns
- Cache hit rates

## Future Enhancements

### Planned Features
- **Elasticsearch Integration**: Advanced search engine integration
- **Machine Learning**: AI-powered search relevance
- **Voice Search**: Voice-to-text search capability
- **Visual Search**: Image-based search functionality

### Advanced Features
- **Personalized Search**: User-specific search results
- **Semantic Search**: Meaning-based search matching
- **Multi-language Search**: Advanced multilingual support
- **Real-time Indexing**: Instant content indexing

## Dependencies

### Required Libraries
- CodeIgniter 4.x framework
- MySQL full-text search
- Content modules (Courses, Articles, Pages)
- Caching library

### Optional Integrations
- Elasticsearch or Solr
- Redis for caching
- Machine learning libraries
- Analytics platforms

## Troubleshooting

### Common Issues
1. **No Search Results**: Check index status and content availability
2. **Slow Search**: Review index optimization and query performance
3. **Incorrect Results**: Verify relevance scoring and content indexing
4. **Auto-complete Not Working**: Check suggestion generation and caching

### Debug Tools
- Search query analyzer
- Index status checker
- Performance profiler
- Analytics dashboard
