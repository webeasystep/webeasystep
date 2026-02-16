# Units Module

## Overview
The Units module is a self-contained, modular component of the MSARLink e-learning platform that handles all unit-related functionality. It provides comprehensive unit management, progress tracking, quiz integration, and content delivery capabilities.

## Architecture

### Controllers
- **AdminUnits.php**: Administrative unit management interface with full CRUD operations
- **Units.php**: Site-facing controller for unit viewing, progress tracking, and completion

### Models
- **UnitsModel.php**: Core unit data management with advanced querying and statistics

### Views
- **Admin/**: Administrative interfaces for unit management
  - `units_index.php`: Main units listing with DataTables integration
  - `units_form.php`: Add/edit unit form with dynamic course/section loading
  - `units_show.php`: Detailed unit view with statistics and quiz management
- **Site/**: Student-facing interfaces
  - `unit_view.php`: Unit viewing page with video player and progress tracking

### Language Files
- **ar/Units.php**: Arabic translations for all unit-related text
- **en/Units.php**: English translations for all unit-related text

## Key Features

### 1. Comprehensive Unit Management
- **CRUD Operations**: Full create, read, update, delete functionality
- **Dynamic Loading**: Real-time loading of sections and quizzes based on course selection
- **Bulk Operations**: Mass status updates and deletions
- **Advanced Filtering**: Filter by course, section, and status
- **Statistics Dashboard**: Real-time unit analytics and performance metrics

### 2. Advanced Unit Elements Management
- **Multi-Element Support**: Support for videos, quizzes, and additional pages within a single unit
- **Video Integration**: Enhanced Bunny.net API integration with automatic data fetching
- **Quiz Assignment**: Dynamic quiz selection based on course context
- **Page Integration**: Integration with Pages module for additional content
- **Element Ordering**: Drag-and-drop ordering of unit elements
- **Element Status Control**: Individual activation/deactivation of elements
- **Rich Element Display**: Enhanced table view with icons and detailed information

### 3. Content Management
- **Video Integration**: Support for external video hosting with intro_video_id and video_id
- **Rich Descriptions**: Full text descriptions with formatting support
- **Preview Options**: Mark units as free preview content
- **Flexible Ordering**: Easy reordering within sections
- **Content Types**: Support for different unit types (video, text, quiz, etc.)

### 4. Enhanced Quiz Integration
- **Course-Based Loading**: Quizzes loaded dynamically based on selected course
- **Detailed Quiz Info**: Display quiz description, duration, and passing score
- **Quick Quiz Creation**: Direct link to create new quizzes when none available
- **Smart Assignment**: Automatic sort order assignment for new quiz elements
- **Visual Quiz Selection**: Enhanced interface with detailed quiz information cards

### 5. Progress Tracking
- **Real-time Progress**: Track student progress through units
- **Completion Tracking**: Mark units as complete with timestamps
- **Progress Percentage**: Granular progress tracking (0-100%)
- **Course Integration**: Unit progress contributes to overall course completion

### 6. Advanced Features
- **Unit Duplication**: Clone units with all associated data
- **Status Management**: Toggle unit activation with AJAX
- **Navigation**: Previous/next unit navigation
- **Statistics**: Comprehensive unit and course-level statistics

## Database Schema

### Core Tables

#### tb_units
```sql
CREATE TABLE `tb_units` (
    `id` int NOT NULL AUTO_INCREMENT,
    `section_id` int NOT NULL,
    `unit_name` varchar(255) NOT NULL,
    `unit_desc` text,
    `video_id` varchar(255) DEFAULT NULL,
    `video_duration` varchar(20) DEFAULT NULL,
    `sort_order` int DEFAULT 1,
    `is_free` tinyint(1) DEFAULT 0,
    `active` tinyint(1) DEFAULT 1,
    `unit_type` varchar(50) DEFAULT 'video',
    `content_data` json DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_section_id` (`section_id`),
    KEY `idx_sort_order` (`sort_order`),
    KEY `idx_active` (`active`),
    FOREIGN KEY (`section_id`) REFERENCES `tb_sections` (`id`) ON DELETE CASCADE
);
```

#### tb_unit_items (Unit Elements Management)
```sql
CREATE TABLE `tb_unit_items` (
    `id` int NOT NULL AUTO_INCREMENT,
    `unit_id` int NOT NULL,
    `item_type` enum('video','quiz','page') NOT NULL,
    `item_id` varchar(255) NOT NULL COMMENT 'video_id, quiz_id, or page_id',
    `title` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `duration` varchar(20) DEFAULT NULL,
    `sort_order` int DEFAULT 1,
    `is_active` tinyint(1) DEFAULT 1,
    `metadata` json DEFAULT NULL COMMENT 'Additional data including video_thumbnail, file size, passing score, etc.',
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
    `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_unit_id` (`unit_id`),
    KEY `idx_item_type` (`item_type`),
    KEY `idx_sort_order` (`sort_order`),
    KEY `idx_is_active` (`is_active`),
    FOREIGN KEY (`unit_id`) REFERENCES `tb_units` (`id`) ON DELETE CASCADE
);
```

**Note**: The `thumbnail` column has been removed and thumbnail data is now stored within the `metadata` JSON column as `video_thumbnail` for better data organization and flexibility.



## API Endpoints

### Administrative Endpoints
- `GET /dt_admin/units` - List all units with filtering and pagination
- `GET /dt_admin/units/add` - Show add unit form
- `POST /dt_admin/units/create` - Create new unit
- `GET /dt_admin/units/edit/{id}` - Show edit unit form
- `POST /dt_admin/units/update/{id}` - Update existing unit
- `GET /dt_admin/units/show/{id}` - Show unit details
- `POST /dt_admin/units/deleteUnit/{id}` - Delete unit

### AJAX Endpoints
- `GET /dt_admin/units/get-sections-by-course/{id}` - Get sections for course
- `GET /dt_admin/units/get-quizzes-by-section/{id}` - Get quizzes for section
- `GET /dt_admin/units/statistics` - Get overall statistics
- `GET /dt_admin/units/statistics/{id}` - Get unit-specific statistics
- `POST /dt_admin/units/duplicate/{id}` - Duplicate unit
- `POST /dt_admin/units/remove-quiz` - Remove quiz from unit
- `POST /dt_admin/units/toggle-status/{id}` - Toggle unit status

### Unit Elements Management Endpoints
- `POST /dt_admin/units/fetch-video-data` - Fetch video data from Bunny.net API
- `GET /dt_admin/units/get-available-quizzes/{course_id}` - Get available quizzes for course
- `GET /dt_admin/units/get-available-pages` - Get available pages from Pages module
- `POST /dt_admin/units/save-unit-items` - Save unit items (videos, quizzes, pages)
- `POST /dt_admin/units/update-item-order` - Update item sort order
- `POST /dt_admin/units/toggle-item-status` - Toggle item active status
- `DELETE /dt_admin/units/delete-item/{id}` - Delete unit item

### Site Endpoints
- `GET /units/view/{id}` - View unit content
- `POST /units/mark-complete` - Mark unit as complete
- `POST /units/update-progress` - Update unit progress
- `GET /units/get-progress` - Get unit progress

## Integration Points

### Module Dependencies
- **Courses Module**: For course and section data
- **Quizzes Module**: For quiz assignment and management
- **Progress Module**: For progress tracking (optional)
- **Enrollments Module**: For enrollment verification

### External Integrations
- **Video Hosting**: Support for external video platforms
- **Authentication**: CodeIgniter Shield integration
- **Caching**: Built-in caching for performance

## Usage Examples

### Creating a Unit with Multiple Elements
```php
// In AdminUnits controller
public function create()
{
    $data = [
        'section_id' => $this->request->getPost('section_id'),
        'unit_name' => $this->request->getPost('unit_name'),
        'unit_desc' => $this->request->getPost('unit_desc'),
        'sort_order' => $this->request->getPost('sort_order'),
        'is_free' => $this->request->getPost('is_free') ? 1 : 0,
        'active' => $this->request->getPost('active') ? 1 : 0
    ];
    
    if ($unitId = $this->unitsModel->insert($data)) {
        // Save unit items (videos, quizzes, pages)
        $unitItems = json_decode($this->request->getPost('unit_items'), true);
        if (!empty($unitItems)) {
            $this->saveUnitItems($unitId, $unitItems);
        }
    }
}

private function saveUnitItems($unitId, $items)
{
    $unitItemsModel = new \Modules\Units\Models\UnitItemsModel();
    
    foreach ($items as $item) {
        $itemData = [
            'unit_id' => $unitId,
            'item_type' => $item['item_type'],
            'item_id' => $item['item_id'],
            'title' => $item['title'],
            'description' => $item['description'] ?? null,
            'thumbnail' => $item['thumbnail'] ?? null,
            'duration' => $item['duration'] ?? null,
            'sort_order' => $item['sort_order'],
            'is_active' => $item['is_active'] ?? 1,
            'metadata' => json_encode($item['metadata'] ?? [])
        ];
        
        $unitItemsModel->insert($itemData);
    }
}
```

### Fetching Video Data from Bunny.net
```php
// AJAX endpoint for fetching video data
public function fetchVideoData()
{
    $videoId = $this->request->getPost('video_id');
    
    if (empty($videoId)) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Video ID is required'
        ]);
    }
    
    try {
        $bunnyService = new \Modules\Units\Services\BunnyNetService();
        $videoData = $bunnyService->getVideoData($videoId);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'title' => $videoData['title'],
                'duration' => $videoData['duration'],
                'thumbnail' => $videoData['thumbnail'],
                'size' => $videoData['size']
            ]
        ]);
    } catch (\Exception $e) {
        return $this->response->setJSON([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}
```

### Getting Unit Progress
```php
// In Units controller
public function getProgress()
{
    $userId = auth()->user()->id;
    $unitId = $this->request->getGet('unit_id');
    
    if (class_exists('\Modules\Progress\Models\UserUnitProgressModel')) {
        $progressModel = new \Modules\Progress\Models\UserUnitProgressModel();
        $progress = $progressModel->getUserUnitProgress($userId, $unitId);
        
        return $this->response->setJSON([
            'is_completed' => (bool)$progress->is_completed,
            'progress_percentage' => $progress->progress_percentage ?? 0
        ]);
    }
}
```

### Dynamic Section Loading (JavaScript)
```javascript
$('#course_id').on('change', function() {
    var courseId = $(this).val();
    if (courseId) {
        $.get('/dt_admin/units/get-sections-by-course/' + courseId, function(sections) {
            var sectionSelect = $('#section_id');
            sectionSelect.html('<option value="">Select Section</option>');
            sections.forEach(function(section) {
                sectionSelect.append(`<option value="${section.id}">${section.section_name}</option>`);
            });
        });
    }
});
```

## Configuration

### Module Settings
- **Video Integration**: Configure video hosting platform settings
- **Progress Tracking**: Enable/disable progress tracking features
- **Quiz Integration**: Configure quiz assignment options
- **Caching**: Set cache duration for unit data

### Performance Optimization
- **Database Indexing**: Optimized indexes for common queries
- **Lazy Loading**: Efficient loading of related data
- **Caching Strategy**: Smart caching of frequently accessed data
- **Query Optimization**: Optimized database queries for large datasets

## Security Features

### Access Control
- **Authentication**: Required for unit access
- **Enrollment Verification**: Check course enrollment before unit access
- **Preview Mode**: Public access for preview units
- **Admin Permissions**: Role-based access for administrative functions

### Data Protection
- **Input Validation**: Comprehensive server-side validation
- **CSRF Protection**: Built-in CSRF token validation
- **SQL Injection Prevention**: Parameterized queries
- **XSS Protection**: Output escaping and sanitization

## Testing

### Unit Tests
- Model functionality testing
- Controller method testing
- Validation rule testing
- Database operation testing

### Integration Tests
- Module interaction testing
- API endpoint testing
- Authentication flow testing
- Progress tracking testing

## Deployment

### Requirements
- CodeIgniter 4.x
- MySQL 8.x or compatible
- PHP 8.1 or higher
- Courses module (dependency)
- Quizzes module (for quiz integration)

### Installation
1. Copy module files to `modules/Units/`
2. Run database migrations
3. Configure module settings
4. Update routing configuration
5. Clear application cache

### Migration
```bash
php spark migrate -n Modules\Units
```

## Maintenance

### Regular Tasks
- Monitor unit statistics
- Clean up orphaned progress records
- Optimize database indexes
- Update video hosting configurations

### Troubleshooting
- Check module dependencies
- Verify database connections
- Review error logs
- Test API endpoints

## Future Enhancements

### Planned Features
- Advanced content types (documents, interactive content)
- Offline content support
- Advanced analytics and reporting
- Mobile app integration
- AI-powered content recommendations

### Scalability Considerations
- Horizontal scaling support
- CDN integration for video content
- Advanced caching strategies
- Microservices architecture preparation

---

**Module Version**: 1.0.0  
**Last Updated**: January 2025  
**Compatibility**: CodeIgniter 4.x, MSARLink Platform v2.0