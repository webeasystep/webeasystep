# Project Context Reference – CodeIgniter 4

**Project:** MsarLink CMS  
**Generated:** 2025-12-18  
**Last Verified:** 2025-12-18  

---

## 1. Project Overview

### Business Purpose
MsarLink is a **Learning Management System (LMS)** designed to deliver educational content, manage course enrollments, track student progress, and administer quizzes. The system supports both administrative backend operations and public-facing student interfaces.

### Primary Use Cases
1. **Course Management** – Create, organize, and publish educational courses with units and quizzes
2. **Student Enrollment** – Process and manage unit-based enrollment purchases with approval workflows
3. **Progress Tracking** – Monitor student progress through courses, units, and individual items
4. **Quiz Administration** – Create, deliver, and grade quizzes with multiple question types
5. **User Management** – Handle user registration, authentication, and role-based access control

### Target Users
| Role | Description |
|------|-------------|
| **Superadmin** | Complete system control and configuration |
| **Admin** | Day-to-day site administration |
| **User** | General registered users (students/customers) |
| **Developer** | System programmers with access to settings |

### High-Level System Responsibilities
- Authentication and authorization via CodeIgniter Shield
- Content delivery through modular course/unit structure
- Payment and enrollment processing
- Progress tracking and reporting
- Multi-language support (Arabic primary, English secondary)

---

## 2. Framework & Environment

### Version Information
| Component | Version |
|-----------|---------|
| **CodeIgniter** | ^4.6.0 |
| **PHP** | ^8.1 |
| **Shield** | ^1.0 (Authentication) |
| **Settings** | ^2.2 (CodeIgniter Settings Package) |

### Required Extensions
- `ext-curl`
- `ext-intl`
- `ext-json`
- `ext-mbstring`

### Key Dependencies
| Package | Purpose |
|---------|---------|
| `codeigniter4/shield` | Authentication & Authorization |
| `codeigniter4/settings` | Dynamic settings management |
| `mpdf/mpdf` | PDF generation (v8.1.6) |
| `phpoffice/phpspreadsheet` | Excel import/export |
| `laminas/laminas-escaper` | Output escaping |

### Environment Configuration
- **Default Locale:** `ar` (Arabic)
- **Supported Locales:** `['ar', 'en']`
- **Timezone:** `Asia/Baghdad`
- **Character Set:** `UTF-8`
- **Base URL Pattern:** `http://msarlink.test` (development)

### Entry Points
| Entry Point | Purpose |
|-------------|---------|
| `public/index.php` | Main web entry point |
| `spark` | CLI entry point |
| `app/Config/Routes.php` | Central route definitions |

### Bootstrap Flow
1. `public/index.php` → CodeIgniter bootstrap
2. `app/Config/Autoload.php` → PSR-4 namespace registration
3. `app/Config/Modules.php` → Auto-discovery enabled
4. `app/Config/Routes.php` → Main routes + dynamic module route loading via `glob()`
5. Module routes loaded from `modules/*/Config/Routes.php`

---

## 3. Directory Structure

### Root Directory
```
d:\laragon\www\msarlink\
├── app/                    # Main application directory
├── modules/                # HMVC modular structure (14 modules)
├── public/                 # Web-accessible files (1729 items)
├── vendor/                 # Composer dependencies
├── writable/               # Logs, cache, sessions
├── composer.json           # Dependency definitions
├── spark                   # CLI tool
└── preload.php             # PHP preloading configuration
```

### Application Directory (`app/`)
| Directory | Content | Purpose |
|-----------|---------|---------|
| `Cells/` | 1 file | View Cells for reusable view components |
| `Commands/` | 9 files | CLI commands for Spark |
| `Config/` | 47 files | Framework configuration |
| `Controllers/` | 4 files | Core controllers (Admin, Site, Base) |
| `Database/` | Migrations (22) + Seeds (5) | Schema management |
| `Entities/` | 1 file | Data entities (User) |
| `Filters/` | 3 files | Request filters |
| `Helpers/` | 34 files | Function libraries |
| `Language/` | 22 files | Localization files |
| `Libraries/` | 7 files | Custom libraries |
| `Models/` | 3 files | Core data models |
| `Services/` | 2 files | Service classes |
| `Validation/` | 1 file | Custom validation rules |
| `Views/` | 3 subdirs | Templates (admin_layout, site_layout, errors) |

### Modules Directory (`modules/`)
```
modules/
├── Articles/       (14 items)  - Content articles
├── ContactUs/      (15 items)  - Contact form handling
├── Courses/        (15 items)  - Course management
├── Enrollments/    (22 items)  - Enrollment processing
├── Groups/         (14 items)  - User groups
├── Pages/          (19 items)  - Static pages
├── Permissions/    (13 items)  - Access control
├── Progress/       (18 items)  - Learning progress tracking
├── Quizzes/        (24 items)  - Quiz system
├── Search/         (13 items)  - Search functionality
├── Sections/       (13 items)  - Page sections
├── Settings/       (10 items)  - System settings
├── Units/          (22 items)  - Course units
└── Users/          (21 items)  - User management
```

### Standard Module Structure
Each module follows the HMVC pattern:
```
Module/
├── Config/
│   └── Routes.php      # Module-specific routes
├── Controllers/
│   ├── Admin*.php      # Admin controllers
│   └── *.php           # Site/public controllers
├── Models/
│   └── *Model.php      # Data access models
├── Views/
│   ├── Admin/          # Admin view templates
│   └── Site/           # Public view templates
├── Language/           # Module translations (ar/, en/)
├── Database/           # Module migrations (if any)
├── Filters/            # Module-specific filters (if any)
└── README.md           # Module documentation
```

---

## 4. Routing Strategy

### Configuration
```php
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->setAutoRoute(false);  // Explicit routing only
```

### Route Definition Style
- **Explicit route definitions** (auto-routing disabled)
- **Method-specific matching** using `match(['GET', 'POST'], ...)`
- **Group-based organization** by namespace and filter

### URL Prefix Convention
| Prefix | Purpose | Filter |
|--------|---------|--------|
| `/dt_admin/` | Admin panel routes | `admin_filter` |
| `/` | Public site routes | None or `site_filter` |
| `/site/` | Authenticated site routes | `site_filter` |

### Route Grouping Pattern
```php
// Admin routes pattern
$routes->group('dt_admin', [
    'namespace' => 'Modules\{Module}\Controllers',
    'filter' => 'admin_filter'
], static function ($routes) {
    $routes->match(['GET', 'POST'], '{module}', [Admin{Module}::class, 'index']);
    $routes->post('{module}/index', [Admin{Module}::class, 'index']);
    $routes->match(['GET', 'POST'], '{module}/add', [Admin{Module}::class, 'add']);
    $routes->match(['GET', 'POST'], '{module}/edit/(:num)', [Admin{Module}::class, 'edit/$1']);
    $routes->post('{module}/show/(:num)', [Admin{Module}::class, 'show/$1']);
    $routes->post('{module}/switchToggle', [Admin{Module}::class, 'switchToggle']);
    $routes->post('{module}/delete', [Admin{Module}::class, 'delete']);
});
```

### Dynamic Module Route Loading
Routes are dynamically loaded from all modules at the end of `app/Config/Routes.php`:
```php
foreach (glob(ROOTPATH . 'modules/*', GLOB_ONLYDIR) as $item_dir) {
    if (file_exists($item_dir . '/Config/Routes.php')) {
        require_once($item_dir . '/Config/Routes.php');
    }
}
```

### Shield Authentication Routes
Shield routes are integrated with exclusions:
```php
service('auth')->routes($routes, ['except' => ['login', 'register', 'auth/a/show', 'auth/a/verify']]);
```

---

## 5. Controllers Architecture

### Base Controllers

#### `App\Controllers\BaseController`
**Location:** `app/Controllers/BaseController.php`

**Responsibilities:**
- Initialize database connection, session, and language services
- Load common helpers: `url`, `function`, `form`, `validation`, `utils`
- Provide utility methods for flash messages, AJAX responses

**Key Properties:**
```php
public Session $session;
public Language $language;
public BaseConnection $db;
protected $helpers = ['url', 'function', 'form', 'validation', 'utils'];
```

**Key Methods:**
| Method | Purpose |
|--------|---------|
| `initController()` | Framework initialization hook |
| `langSwitch()` | Language toggle handler |
| `show_msg()` | Flash message setter |
| `show($id)` | Generic AJAX record display |
| `switchToggle()` | Generic AJAX toggle handler |
| `delete()` | Generic AJAX bulk delete handler |

#### `App\Controllers\Admin`
**Location:** `app/Controllers/Admin.php`

**Responsibilities:**
- Admin dashboard rendering
- Admin authentication (login, logout, register)
- Password reset flow with magic links
- Account activation

#### `App\Controllers\Site`
**Location:** `app/Controllers/Site.php`

**Responsibilities:**
- Public homepage rendering
- Site-level authentication flows
- Search functionality
- Order handling (legacy)

### Module Controller Pattern
Admin controllers in modules follow this pattern:
```php
namespace Modules\{Module}\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DtTable;

class Admin{Module} extends BaseController
{
    protected $model;
    protected $table;

    public function __construct()
    {
        $this->model = new {Module}Model();
        $this->table = new DtTable('{table_name}', '{module}');
    }

    public function index() { /* DataTable listing */ }
    public function add() { /* Create form/handler */ }
    public function edit($id) { /* Edit form/handler */ }
    public function data_arr($id = null) { /* Insert/Update data builder */ }
}
```

### Naming Conventions
| Type | Pattern | Example |
|------|---------|---------|
| Admin Controller | `Admin{Module}` | `AdminCourses`, `AdminUsers` |
| Site Controller | `{Module}` | `Courses`, `Users` |
| Method | snake_case or camelCase | `data_arr()`, `getAllCourses()` |

---

## 6. Models & Data Access

### Base Model

#### `App\Models\BaseModel`
**Location:** `app/Models/BaseModel.php`

**Responsibilities:**
- Extend CodeIgniter's Model with shared functionality
- Initialize request and database connection
- Provide legacy utility methods

**Key Properties:**
```php
protected $request;
protected $db;
```

### User Model

#### `App\Models\UserModel`
**Location:** `app/Models/UserModel.php`

**Extends:** `CodeIgniter\Shield\Models\UserModel`

**Custom Fields:**
```php
protected $allowedFields = [
    'username', 'status', 'active', 'last_active',
    'full_name', 'mobile', 'email', 'avatar',
    'user_type', 'group_id'
];
```

**Key Methods:**
| Method | Purpose |
|--------|---------|
| `findByCredentials()` | Custom authentication supporting mobile and email |
| `insert()` | Override to ensure custom fields are saved |

### Module Model Pattern
```php
namespace Modules\{Module}\Models;

use App\Models\BaseModel;

class {Module}Model extends BaseModel
{
    protected $table = 'tb_{table}';
    protected $primaryKey = 'id';
    protected $allowedFields = [...];
    protected $useTimestamps = true;
    protected $returnType = 'object';

    // Custom query methods
    public function getById(int $id) { }
    public function insertRecord(array $data) { }
    public function updateRecord(int $id, array $data) { }
}
```

### Table Naming Convention
- **Prefix:** `tb_` for application tables
- **Auth tables:** `auth_*` (Shield standard)
- **Legacy tables:** `fd_*` (food delivery legacy)
- **Examples:** `tb_courses`, `tb_units`, `tb_quizzes`, `tb_unit_enrollments`

### Query Patterns
- Direct `$this->db` query builder usage
- Model methods using CI4 Model interface
- Raw SQL for complex queries with `$this->db->query()`

---

## 7. Entities

### User Entity

#### `App\Entities\User`
**Location:** `app/Entities/User.php`

**Extends:** `CodeIgniter\Shield\Entities\User`

**Purpose:** Custom User Entity extending Shield's User with application-specific fields

**Properties (via PHPDoc):**
```php
@property string|null $full_name
@property string|null $mobile
@property string|null $gender
@property string|null $address
@property string|null $job_title_ar
@property string|null $job_title_en
@property string|null $avatar
@property string|null $user_type
@property int|null    $group_id
```

> **Note:** The entity is minimal and inherits from Shield. Custom casts and dates are managed by the parent class.

---

## 8. Services & Libraries

### Custom Services

#### `App\Services\SettingsService`
**Location:** `app/Services/SettingsService.php`

**Purpose:** Order suggestion management (legacy functionality)

**Methods:**
- `add_suggestions()` – Add driver suggestions for orders
- `suggestions_worker()` – Background worker for suggestion processing

#### `App\Services\NotificationService`
**Location:** `app/Services/NotificationService.php`

**Purpose:** Push notification and notification logging

**Methods:**
- `send_notification()` – Send and log notifications
- `build_notification_message()` – Construct notification data
- `get_sender()` – Retrieve sender information
- `generate_dynamic_message()` – Template-based message generation

### Service Container Registration
```php
// app/Config/Services.php
public static function settingsService($getShared = true)
public static function notificationService($getShared = true)
public static function activator($getShared = true)
```

### Custom Libraries

| Library | Location | Purpose |
|---------|----------|---------|
| `DtTable` | `app/Libraries/DtTable.php` | DataTables server-side processing |
| `FireUploader` | `app/Libraries/FireUploader.php` | File upload handling |
| `EmailActivator` | `app/Libraries/EmailActivator.php` | Email-based account activation |
| `SMSActivator` | `app/Libraries/SMSActivator.php` | SMS-based verification |
| `TwilioLibrary` | `app/Libraries/TwilioLibrary.php` | Twilio integration |
| `EasyCron` | `app/Libraries/EasyCron.php` | Cron job utilities |
| `Predis` | `app/Libraries/Predis.php` | Redis client wrapper |

#### DtTable Library
**Purpose:** Server-side DataTables processing for admin listings

**Key Methods:**
- `tableRender()` – Generate DataTable JSON response
- `setShowColumns()` – Define visible columns
- `setColumnSwitch()` – Toggle column configuration
- `setColumnImage()` – Image column rendering
- `setColumnLink()` – Linked column rendering
- `setColumnModal()` – Modal content columns
- `changeColumn()` – Custom column callbacks
- `setAction()` – Action button configuration
- `hideActions()` – Conditional action hiding
- `searchableColumns()` – Define searchable fields

---

## 9. Filters & Middleware

### Filter Configuration
**Location:** `app/Config/Filters.php`

### Registered Filters
| Alias | Class | Purpose |
|-------|-------|---------|
| `admin_filter` | `AdminFilter` | Admin authentication & authorization |
| `site_filter` | `SiteFilter` | Site user authentication |
| `api_filter` | `ApiAuthFilter` | API authentication |
| `CorsFilter` | `CorsFilter` | CORS headers (global) |
| `csrf` | Shield CSRF | Cross-site request forgery |
| `cors` | CI4 Cors | Built-in CORS handling |

### AdminFilter
**Location:** `app/Filters/AdminFilter.php`

**Logic:**
1. Check if user is logged in via `auth()->loggedIn()`
2. Check if user is in `superadmin` group
3. Handle AJAX vs regular request responses appropriately
4. Redirect to `/dt_admin/login` on failure

### SiteFilter
**Location:** `app/Filters/SiteFilter.php`

**Logic:**
1. Log filter execution for debugging
2. Check authentication status
3. Return JSON error for AJAX requests
4. Redirect to `/login` for regular requests

### Global Filter Configuration
```php
public array $globals = [
    'before' => ['CorsFilter'],
    'after' => ['CorsFilter'],
];
```

### Request Lifecycle
```
Request → Required Filters → Global Before → Route Filters → Controller → Global After → Response
```

---

## 10. Validation & Security

### Validation Configuration
**Location:** `app/Config/Validation.php`

### Custom Rules
**Location:** `app/Validation/CustomRules.php`

| Rule | Purpose |
|------|---------|
| `egyptian_mobile` | Validate Egyptian mobile format (01xxxxxxxxx) |
| `time_check` | Validate open/close time relationship |
| `after` | Validate date is after another field |

### Registration Validation
```php
public array $registrationRules = [
    'full_name' => 'required|min_length[2]|max_length[100]',
    'email' => 'permit_empty|valid_email|is_unique[auth_identities.secret]',
    'mobile' => 'required|egyptian_mobile|is_unique[users.mobile]',
    'password' => 'required|min_length[6]',
    'password_confirm' => 'required|matches[password]',
];
```

### Security Measures
1. **CSRF Protection** – Configured but currently commented out globally
2. **Password Hashing** – Via Shield's secure password handling
3. **Input Validation** – Per-controller validation rules
4. **Filter-based Access Control** – `admin_filter` for admin routes
5. **Shield Integration** – Groups and permissions system

### Authentication Groups
**Location:** `app/Config/AuthGroups.php`

| Group | Permissions |
|-------|-------------|
| `superadmin` | `admin.*`, `users.*`, `beta.*` |
| `admin` | `admin.access`, `users.create/edit/delete`, `beta.access` |
| `developer` | `admin.access/settings`, `users.create/edit`, `beta.access` |
| `user` | None |
| `beta` | `beta.access` |

---

## 11. Database Design

### Database Configuration
- **Driver:** MySQLi
- **Charset:** `utf8mb4`
- **Collation:** `utf8mb4_general_ci`
- **Test Database:** SQLite3 in-memory

### Core Tables

#### Authentication (Shield)
| Table | Purpose |
|-------|---------|
| `users` | User accounts |
| `auth_identities` | Email/mobile credentials |
| `auth_logins` | Login attempt logging |
| `auth_token_logins` | Token-based login logging |
| `auth_remember_tokens` | Remember me tokens |
| `auth_groups` | User groups |
| `auth_groups_users` | User-group associations |
| `auth_permissions` | Permission definitions |
| `auth_permissions_users` | User-permission associations |

#### Learning Management
| Table | Purpose |
|-------|---------|
| `tb_courses` | Course definitions |
| `tb_units` | Course units |
| `tb_unit_items` | Unit content items |
| `tb_quizzes` | Quiz definitions |
| `tb_quiz_questions` | Quiz questions |
| `tb_quiz_attempts` | Student quiz attempts |
| `tb_unit_enrollments` | Unit purchase/enrollment records |
| `tb_video_completions` | Video completion tracking |
| `tb_user_unit_progress` | User progress in units |
| `tb_user_item_progress` | User progress on items |

#### Content Management
| Table | Purpose |
|-------|---------|
| `articles` | Content articles |
| `pages` | Static pages |
| `sections` | Navigation sections |
| `settings` | Application settings |
| `contact_us` | Contact form submissions |

### Migration Strategy
- Migrations stored in `app/Database/Migrations/`
- 22 migration files present
- Naming format: `YYYY-MM-DD-HHmmss_Description.php`
- Run via: `php spark migrate`

### Key Migration Files
| Migration | Purpose |
|-----------|---------|
| `2023-10-03-125706_CreateUsersTable` | Auth system tables |
| `2024-01-01-000002_enhance_courses_structure` | Course system |
| `2024-01-01-000003_create_quizzes_system` | Quiz system |
| `2025-01-17-000001_ReviseEnrollmentsForUnits` | Unit enrollment system |

### Seeders
**Location:** `app/Database/Seeds/`
- 5 seed files available for initial data population

---

## 12. Error Handling & Logging

### Error Configuration
**Location:** `app/Config/Exceptions.php`

### Logging Configuration
**Location:** `app/Config/Logger.php`

### Log Levels
Standard CI4 log levels: emergency, alert, critical, error, warning, notice, info, debug

### Application Logging Patterns
```php
// Filter debugging
log_message('debug', 'SITE_FILTER: Checking authentication for URI: ' . $request->getUri());
log_message('debug', 'SITE_FILTER: Session data: ' . json_encode($sessionData));

// Model debugging
log_message('debug', 'UserModel insert called with data: ' . json_encode($data));
```

### Error Views
**Location:** `app/Views/errors/`
- 9 error view templates

### Writable Directory
**Location:** `writable/`
- Logs stored in `writable/logs/`
- Cache in `writable/cache/`
- Sessions in `writable/session/`

---

## 13. Coding Conventions & Rules

### Naming Conventions

| Element | Convention | Example |
|---------|------------|---------|
| Controllers | PascalCase, prefixed | `AdminCourses`, `Courses` |
| Models | PascalCase + `Model` | `CoursesModel`, `UserModel` |
| Methods | camelCase or snake_case | `getById()`, `data_arr()` |
| Views | snake_case | `add.php`, `index.php`, `edit.php` |
| Tables | snake_case with prefix | `tb_courses`, `auth_users` |
| Columns | snake_case | `created_at`, `user_id` |

### File Organization
- **Admin Views:** `Views/Admin/` subdirectory
- **Site Views:** `Views/Site/` subdirectory
- **Module Languages:** `Language/{locale}/` (ar, en)

### View Helper Functions
Custom view helpers defined in `app/Common.php`:

| Function | Purpose |
|----------|---------|
| `view()` | Module-aware view rendering (Admin default) |
| `View()` | Explicit view type specification |
| `MainView()` | Standard CI4 view with enhanced error handling |

### DataTable Standard Methods
Admin controllers typically implement:
- `index()` – List with DataTable
- `add()` – Create form and handler
- `edit($id)` – Edit form and handler
- `show($id)` – AJAX detail view
- `delete()` – Bulk delete handler
- `switchToggle()` – Toggle field handler
- `data_arr($id)` – Data preparation for insert/update

### Response Patterns
**AJAX Success:**
```php
return $this->response->setJSON([
    'validation' => true,
    'success' => true,
    'message' => 'Operation successful'
]);
```

**AJAX Error:**
```php
return $this->response->setJSON([
    'validation' => true,
    'success' => false,
    'message' => 'Error description'
]);
```

---

## 14. Critical Constraints for Future Changes

### What MUST NOT Be Changed

1. **Namespace Structure**
   - `App\*` for core application
   - `Modules\{Module}\*` for modules
   - PSR-4 autoloading in `app/Config/Autoload.php`

2. **Authentication System**
   - Shield integration and configuration
   - `superadmin` group check in `AdminFilter`
   - Auth identity types (email_password, mobile_password)

3. **Database Prefixes**
   - `tb_` prefix for application tables
   - `auth_` prefix for Shield tables
   - No prefix changes without full migration

4. **Route Prefixes**
   - `/dt_admin/` for admin routes
   - Module route loading mechanism

5. **View Structure**
   - `Admin/` and `Site/` subdirectories in module views
   - Custom `view()` helper logic in `Common.php`

### Backward Compatibility Requirements

1. **API Responses** – Maintain existing JSON structure for AJAX endpoints
2. **URL Patterns** – Preserve existing route patterns for bookmarks/links
3. **Session Keys** – Keep existing session variable names
4. **Database Schema** – Use additive migrations, avoid column type changes

### Dependency Assumptions

1. **Shield Version** – v1.0 API compatibility
2. **PHP Version** – 8.1+ features may be used
3. **MySQL/MariaDB** – Stored procedures in use (`sp_*`)
4. **Redis** – Optional, used for order suggestions

### Coupling Points

| Component | Coupled To |
|-----------|------------|
| `AdminFilter` | Shield auth service, `superadmin` group |
| `DtTable` | Controller index methods, view templates |
| `BaseModel` | Database connection, Request service |
| View helpers | Module naming, folder structure |

---

## 15. Historical & Architectural Intent

### Why HMVC Modular Structure
- **Separation of Concerns** – Each module encapsulates related functionality
- **Scalability** – New features added as independent modules
- **Maintainability** – Changes isolated to specific modules
- **Dynamic Loading** – Routes auto-loaded from module Config folders

### Why Shield for Authentication
- **Official Package** – Maintained by CodeIgniter Foundation
- **Comprehensive** – Groups, permissions, session handling
- **Extensible** – Custom identity types (mobile_password)
- **Security** – Industry-standard password hashing

### Why Unit-Based Enrollment (vs Course-Based)
Based on migration `2025-01-17-000001_ReviseEnrollmentsForUnits`:
- **Granular Purchases** – Users buy individual units, not entire courses
- **Flexible Pricing** – Different units can have different prices
- **Approval Workflow** – `pending` → `approved` status flow
- **Payment Tracking** – Receipt upload and verification

### Trade-offs Made

| Decision | Trade-off | Rationale |
|----------|-----------|-----------|
| Auto-routing disabled | More explicit route definitions | Security and predictability |
| Arabic as default locale | English users need toggle | Primary market is Arabic-speaking |
| Custom view() helper | Non-standard CI4 pattern | Module-aware view loading |
| DtTable library | Learning curve | Consistent admin table handling |

### Non-Obvious Design Decisions

1. **Mobile as Primary Identity**
   - `findByCredentials()` in UserModel handles mobile authentication
   - Custom identity type `mobile_password` for mobile logins

2. **Legacy Table Prefixes**
   - `fd_*` tables exist from food delivery system
   - Maintained for backward compatibility, not active development

3. **Dual View Functions**
   - `view()` – Module-aware, defaults to Admin views
   - `MainView()` – Standard path resolution for layout templates

4. **Progress Tracking Architecture**
   - Unit-level progress via `tb_unit_enrollments`
   - Item-level progress via `tb_user_item_progress`
   - Video completions separate in `tb_video_completions`

---

## Appendix A: Constants Reference

**Location:** `app/Config/Constants.php`

### URL Constants
| Constant | Value Pattern |
|----------|---------------|
| `BASEURL` | Dynamic from `$_SERVER` |
| `ADMIN_URL` | `{baseUrl}dt_admin/` |
| `ADMIN_PREFIX` | `dt_admin/` |
| `ADMIN_PATH` | `ROOTPATH/public/admin` |
| `SITE_PATH` | `ROOTPATH/public/site` |

### User Type Constants
| Constant | Value |
|----------|-------|
| `ADMIN_USER` | 1 |
| `MERCHANT_USER` | 1 |
| `CLIENT_USER` | 2 |
| `DRIVER_USER` | 3 |

### Order Status Constants (Legacy)
| Constant | Value |
|----------|-------|
| `ORDER_DELETED` | 0 |
| `ORDER_PENDING` | 1 |
| `ORDER_ACCEPTED` | 2 |
| `ORDER_TAKEN` | 3 |
| `ORDER_CANCELED` | 4 |
| `ORDER_FINISHED` | 5 |
| `ORDER_HIDDEN` | 6 |

---

## Appendix B: Helper Functions

### Global Helpers (Auto-loaded)
- `auth` – Shield authentication helpers
- `setting` – Settings package helpers
- `utils` – Custom utility functions

### BaseController Helpers
- `url` – URL generation
- `function` – Custom functions (legacy)
- `form` – Form generation
- `validation` – Validation helpers

### Key Custom Functions

**From `app/Helpers/utils_helper.php`:**
| Function | Purpose |
|----------|---------|
| `truncate()` | Text truncation with ellipsis |
| `is_image()` | Validate image file |
| `thumb()` | Generate thumbnail images |
| `localized_field()` | Get field by current locale |
| `download_report()` | Generate PDF with mPDF |
| `currentModule()` | Get current module name |
| `generateSidebarLinks()` | Build admin sidebar |
| `round_currency()` | Round to available denominations |
| `add_order_log()` | Log order status changes |

---

## Appendix C: Module Quick Reference

| Module | Admin Route | Model | Key Tables |
|--------|-------------|-------|------------|
| Courses | `/dt_admin/courses` | `CoursesModel` | `tb_courses` |
| Units | `/dt_admin/units` | `UnitsModel` | `tb_units`, `tb_unit_items` |
| Quizzes | `/dt_admin/quizzes` | `QuizzesModel` | `tb_quizzes`, `tb_quiz_questions` |
| Enrollments | `/dt_admin/enrollments` | `EnrollmentsModel` | `tb_unit_enrollments` |
| Progress | `/dt_admin/progress` | `ProgressModel` | `tb_user_unit_progress` |
| Users | `/dt_admin/users` | `UsersModel` | `users`, `auth_*` |
| Settings | `/dt_admin/settings` | `SettingsModel` | `settings` |
| Articles | `/dt_admin/articles` | `ArticlesModel` | `articles` |
| Pages | `/dt_admin/pages` | `PagesModel` | `pages` |
| Sections | `/dt_admin/sections` | `SectionsModel` | `sections` |
| Groups | `/dt_admin/groups` | `GroupsModel` | `auth_groups` |
| Permissions | `/dt_admin/permissions` | `PermissionsModel` | `auth_permissions` |
| ContactUs | `/dt_admin/contact_us` | `ContactUsModel` | `contact_us` |
| Search | `/dt_admin/search` | `SearchModel` | N/A (query-based) |

---

*This document serves as the authoritative technical reference for AI Coding Agents working on the MsarLink project. All modifications should be verified against the constraints and patterns documented herein.*
