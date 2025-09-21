<?php
namespace Modules\Courses\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Database\ConnectionInterface; // <-- Import the interface
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use Modules\Courses\Models\CoursesModel;

use Modules\Units\Models\UnitsModel;
use Modules\Units\Models\UnitItemsModel;


class Courses extends BaseController
{
    protected CoursesModel $coursesModel;

    protected UnitsModel $unitsModel;
    protected UnitItemsModel $unitItemsModel;

    public function __construct()
    {
        // Get the database connection service and assign it to the property
        $this->db = \Config\Database::connect(); // <-- ADD THIS LINE
        $this->coursesModel = new CoursesModel();

        $this->unitsModel = new UnitsModel();
        $this->unitItemsModel = new UnitItemsModel();
        helper(['text', 'url', 'form']); // Load needed helpers
    }

    /**
     * View individual item by ID
     */
    public function item(int $itemId)
    {
        // Get the unit item
        $unitItem = $this->unitItemsModel->find($itemId);
        if (!$unitItem) {
            throw PageNotFoundException::forPageNotFound('Item not found');
        }

        // Get the unit
        $unit = $this->unitsModel->find($unitItem->unit_id);
        if (!$unit || !$unit->active) {
            throw PageNotFoundException::forPageNotFound('Unit not found or inactive');
        }

        // Get the course
        $course = $this->coursesModel->find($unit->course_id);
        if (!$course || !$course->active) {
            throw PageNotFoundException::forPageNotFound('Course not found or inactive');
        }

        // Check user authentication
        $userId = auth()->user()->id ?? null;
        if (!$userId) {
            return redirect()->to('login')->with('error', 'Please log in to access course content.');
        }

        // Check if user has access to this content
        $hasAccess = $this->checkUnitAccess($userId, $unit->id);
        if (!$hasAccess) {
            return redirect()->to('/courses/course_details/' . $course->slug)
                ->with('error', 'You do not have access to this content. Please enroll in the course first.');
        }

        // Redirect to the course view with the specific item
        $redirectUrl = site_url('courses/course_view/' . $course->slug . '?item_id=' . $itemId);
        return redirect()->to($redirectUrl);
    }

    /**
     * Get courses data for home page
     */
    public function getCoursesForHome()
    {
        // Get active courses with enhanced data
        $courses = $this->db
            ->table('tb_courses')
            ->where('active', 1)
            ->orderBy('sort', 'ASC')
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();

        // Check if user is logged in
        $userId = auth()->loggedIn() ? auth()->user()->id : null;
        $enrolledCourseIds = [];

        if ($userId) {
            // Get courses with enrolled units for logged-in user
            $enrolledUnits = $this->db
                ->table('tb_unit_enrollments')
                ->select('unit_ids')
                ->where('user_id', $userId)
                ->where('status', 'approved')
                ->get()
                ->getResultArray();

            $enrolledCourseIds = [];
            foreach ($enrolledUnits as $enrollment) {
                $unitIds = json_decode($enrollment['unit_ids'], true);
                if ($unitIds) {
                    // Get courses for these units
                    $unitCourses = $this->db->table('tb_units')
                        ->select('course_id')
                        ->whereIn('id', $unitIds)
                        ->get()
                        ->getResultArray();
                    $enrolledCourseIds = array_merge($enrolledCourseIds, array_column($unitCourses, 'course_id'));
                }
            }
            $enrolledCourseIds = array_unique($enrolledCourseIds);
        }

        // Pre-process each course
        foreach ($courses as &$course) {
            // Provide fallbacks for missing fields
            $course['short_desc'] = $course['short_desc'] ?? $course['course_desc'] ?? '';
            $course['image'] = $course['image'] ?? '[]';

            // Parse image JSON from FireUploader format
            $images = json_decode($course['image'], true);
            $course['image_url'] = 'site/images/default-course.jpg'; // Default fallback

            // Use the thumb helper function for consistent image handling
            if (!empty($course['image'])) {
                $course['image_url'] = thumb($course['image'], 300, 200);
            }

            // Count lessons and units from units system
            $lessonCount = 0;
            $unitCount = 0;
            $totalDuration = 0;

            // Get units and their items for this course
            $units = $this->unitItemsModel->getUnitItems($course['id'], true);
            if (!empty($units)) {
                $unitCount = count($units);
                foreach ($units as $unit) {
                    if (!empty($unit->items)) {
                        $lessonCount += count($unit->items);
                        // Calculate total duration if available
                        foreach ($unit->items as $item) {
                            $itemDuration = 0;
                            if ($item->item_type === 'video' && !empty($item->metadata)) {
                                $metadata = json_decode($item->metadata, true);
                                $itemDuration = $metadata['duration'] ?? 0;
                                // Convert seconds to minutes for consistency
                                $totalDuration += $this->parseDurationToMinutes(gmdate('H:i:s', $itemDuration));
                            }
                        }
                    }
                }
            }

            $course['lesson_count'] = $lessonCount;
            $course['section_count'] = $unitCount;
            $course['total_duration'] = $totalDuration;
            $course['duration_formatted'] = $this->formatDuration($totalDuration);

            // Check enrollment status
            $course['is_enrolled'] = $userId ? in_array($course['id'], $enrolledCourseIds) : false;

            // Set course URL based on enrollment status
            if ($course['is_enrolled']) {
                $course['course_url'] = base_url('courses/course_view/' . $course['slug']);
            } else {
                $course['course_url'] = base_url('courses/course_details/' . $course['slug']);
            }
        }
        unset($course); // Good practice after reference loops

        return $courses;
    }

    /**
     * Parse duration string (e.g., "10:30" or "1:05:30") to minutes
     */
    private function parseDurationToMinutes(string $duration): int
    {
        $parts = explode(':', $duration);
        $minutes = 0;

        if (count($parts) == 2) {
            // Format: MM:SS
            $minutes = (int)$parts[0] + ((int)$parts[1] / 60);
        } elseif (count($parts) == 3) {
            // Format: HH:MM:SS
            $minutes = ((int)$parts[0] * 60) + (int)$parts[1] + ((int)$parts[2] / 60);
        }

        return (int)$minutes;
    }

    /**
     * Format minutes to readable duration string (always show in minutes)
     */
    private function formatDuration(int $minutes): string
    {
        // Always show in minutes, regardless of the total duration
        return $minutes . ' دقيقة';
    }

    /**
     * Handle course enrollment/access
     */
    public function courseAction($courseId = null)
    {
        if (!$courseId) {
            return redirect()->to('/')->with('error', 'معرف الكورس مطلوب');
        }

        // Check if user is logged in
        if (!auth()->loggedIn()) {
            // Store intended course in session for redirect after login
            session()->set('intended_course', $courseId);
            return redirect()->to('login')->with('info', 'يرجى تسجيل الدخول أولاً للوصول إلى الكورس');
        }

        $userId = auth()->user()->id;

        // Get course details
        $course = $this->db->table('tb_courses')
            ->where('id', $courseId)
            ->where('active', 1)
            ->get()
            ->getRowArray();

        if (!$course) {
            return redirect()->to('/')->with('error', 'الكورس غير موجود');
        }

        // Check if user has enrolled in any units of this course
        $courseUnits = $this->db->table('tb_units')
            ->select('id')
            ->where('course_id', $courseId)
            ->get()
            ->getResultArray();
        $courseUnitIds = array_column($courseUnits, 'id');

        $enrollment = null;
        if (!empty($courseUnitIds)) {
            $enrollments = $this->db->table('tb_unit_enrollments')
                ->where('user_id', $userId)
                ->where('status', 'approved')
                ->get()
                ->getResultArray();

            foreach ($enrollments as $enroll) {
                $unitIds = json_decode($enroll['unit_ids'], true);
                if ($unitIds && array_intersect($unitIds, $courseUnitIds)) {
                    $enrollment = $enroll;
                    break;
                }
            }
        }

        if ($enrollment) {
            // User is enrolled, redirect to course view
            return redirect()->to('courses/course_view/' . $course['slug']);
        } else {
            // User is not enrolled, redirect to course details for enrollment
            return redirect()->to('courses/course_details/' . $course['slug']);
        }
    }

    /**
     * Show a paginated list of active courses for site visitors.
     */
    public function index(): string
    {
        // 3) Courses with stats (unit_count and quiz_count)
        $data['courses'] = $this->coursesModel->getAllCoursesWithStats();

        // (Optional) Check if user is logged in
        $userId = session()->get('user_id');
        $enrolledCourseIds = [];

        // If user is logged in, fetch the courses they’re enrolled in
        if (!empty($userId)) {
            $enrolledUnits = $this->db
                ->table('tb_unit_enrollments')
                ->select('unit_ids')
                ->where('user_id', $userId)
                ->where('status', 'approved')
                ->get()
                ->getResultArray();

            $enrolledCourseIds = [];
            foreach ($enrolledUnits as $enrollment) {
                $unitIds = json_decode($enrollment['unit_ids'], true);
                if ($unitIds) {
                    // Get courses for these units
                    $unitCourses = $this->db->table('tb_units')
                        ->select('course_id')
                        ->whereIn('id', $unitIds)
                        ->get()
                        ->getResultArray();
                    $enrolledCourseIds = array_merge($enrolledCourseIds, array_column($unitCourses, 'course_id'));
                }
            }
            $enrolledCourseIds = array_unique($enrolledCourseIds);
        }

        // Pre-process each course
        foreach ($data['courses'] as &$course) {
            // Convert object to array for consistency
            $course = (array) $course;
            
            // Provide a fallback if short_desc doesn't exist
            $course['short_desc'] = $course['short_desc'] ?? '';

            // Count lessons from units system
            $lessonCount = 0;
            $units = $this->unitItemsModel->getUnitItems($course['id'], true);
            if (!empty($units)) {
                foreach ($units as $unit) {
                    if (!empty($unit->items)) {
                        $lessonCount += count($unit->items);
                    }
                }
            }
            $course['lesson_count'] = $lessonCount;

            // Mark if user is enrolled
            $course['is_enrolled'] = in_array($course['id'], $enrolledCourseIds);
        }
        unset($course); // Good practice after reference loops

        $data['title']  = 'All Courses' ;
        $data['desc']  = 'Browse our comprehensive course catalog' ;
        $data['featured_courses'] = $this->coursesModel->getFeaturedCourses(3);
        $data['popular_courses'] = $this->coursesModel->getPopularCourses(3);
        return view('site/index', $data);
    }


    /**
     * Show a single course details page (e.g. 'course_details' view).
     */
    public function course_details(string $slug): string
    {
        // 1) Fetch the course by slug
        $course = $this->coursesModel->getCourseBySlug($slug);
        if (!$course) {
            throw PageNotFoundException::forPageNotFound();
        }

        // 2) Get course units with their items
        $units = $this->unitsModel->getUnitsByCourse($course->id);

        // Get unit items for each unit and process metadata
        foreach ($units as &$unit) {
            $unit->items = $this->unitItemsModel->getUnitItems($unit->id, true); // Only active items

            // Process each item's metadata
            if (isset($unit->items)) {
                foreach ($unit->items as &$item) {
                    // Ensure metadata is properly decoded
                    if (!empty($item->metadata) && is_string($item->metadata)) {
                        $item->metadata = json_decode($item->metadata, true);
                    }

                    // Add video duration in readable format for display (in minutes)
                    if ($item->item_type === 'video' && isset($item->metadata['video_duration'])) {
                        $durationSeconds = (int)$item->metadata['video_duration'];
                        // Convert to minutes format instead of hours
                        $minutes = floor($durationSeconds / 60);
                        $seconds = $durationSeconds % 60;
                        $item->duration_formatted = sprintf('%d:%02d', $minutes, $seconds);
                    }
                }
                unset($item);
            }
        }
        unset($unit);

        // 3) Check if user is enrolled
        $userId = auth()->loggedIn() ? auth()->user()->id : null;
        $isEnrolled = false;
        if ($userId) {
            $isEnrolled = $this->coursesModel->isUserEnrolled($userId, $course->id);
        }

        // 4) Calculate course statistics
        $totalItems = 0;
        $totalDuration = 0;
        $videoCount = 0;
        $quizCount = 0;
        $pageCount = 0;

        foreach ($units as $unit) {
            if (isset($unit->items)) {
                $totalItems += count($unit->items);
                foreach ($unit->items as $item) {
                    if ($item->item_type === 'video') {
                        $videoCount++;
                        // Extract duration from metadata if available
                        $itemDuration = 0;
                        if (!empty($item->metadata)) {
                            $metadata = is_array($item->metadata) ? $item->metadata : json_decode($item->metadata, true);
                            if (is_array($metadata)) {
                                $itemDuration = $metadata['video_duration'] ?? 0;
                            }
                        }
                        $totalDuration += (int)$itemDuration;
                    } elseif ($item->item_type === 'quiz') {
                        $quizCount++;
                    } elseif ($item->item_type === 'page') {
                        $pageCount++;
                    }
                }
            }
        }

        // Add calculated stats to course object
        $course->video_count = $videoCount;
        $course->quizzes_count = $quizCount;
        $course->page_count = $pageCount;
        $course->total_items = $totalItems;
        $course->duration = $this->formatDuration((int)($totalDuration / 60)); // Convert seconds to minutes and ensure integer

        // Ensure required fields exist with defaults
        $course->collection_id = $course->collection_id ?? '495222';
        $course->intro_video_id = $course->intro_video_id ?? '';
        
        // Add unit and quiz counts for display
        $course->unit_count = $this->coursesModel->getUnitCount($course->id);
        $course->quiz_count = $this->coursesModel->getQuizCount($course->id);

        // 5) Prepare data for the view
        $data = [
            'title' => $course->course_title,
            'course' => $course,
            'units' => $units,
            'isEnrolled' => $isEnrolled,
            'totalItems' => $totalItems,
            'videoCount' => $videoCount,
            'quizCount' => $quizCount,
            'pageCount' => $pageCount,
        ];

        // 6) Render the updated "course_details" view
        return view('site/course_details', $data);
    }

    /**
     * Display the "course player" page with units, items, next/prev logic, etc.
     */
    public function course_view(string $slug): string|RedirectResponse
    {
        // 1) Fetch the course by slug
        $course = $this->coursesModel->getCourseBySlug($slug);
        if (!$course) {
            throw PageNotFoundException::forPageNotFound();
        }

        // 2) Check if user is logged in & enrolled
        $userId = auth()->user()->id ?? null;
        if (!$userId) {
            return redirect()->to('login')->with('error', 'Please log in first.');
        }

        // Check enrollment using the new Units system
        $hasAccess = $this->checkCourseAccess($userId, $course->id);
        if (!$hasAccess) {
            return redirect()->to('/courses/course_details/' . $slug)->with('error', 'You need to enroll in this course to access its content.');
        }

        // 3) Get course units with their items - FILTER BY ENROLLMENT
        $allUnits = $this->unitsModel->getUnitsByCourse($course->id);
        
        // Get user's enrolled unit IDs
        $enrolledUnitIds = [];
        $enrollments = $this->db->table('tb_unit_enrollments')
            ->where('user_id', $userId)
            ->where('status', 'approved')
            ->get()
            ->getResultArray();

        foreach ($enrollments as $enrollment) {
            $unitIds = json_decode($enrollment['unit_ids'], true);
            if ($unitIds) {
                $enrolledUnitIds = array_merge($enrolledUnitIds, $unitIds);
            }
        }
        $enrolledUnitIds = array_unique($enrolledUnitIds);

        // Filter units to show only enrolled ones
        $units = [];
        foreach ($allUnits as $unit) {
            if (in_array($unit->id, $enrolledUnitIds)) {
                $units[] = $unit;
            }
        }

        // Get unit items for each enrolled unit using the new UnitItemsModel
        $flatItems = [];
        foreach ($units as &$unit) {
            $unit->items = $this->unitItemsModel->getUnitItemsWithDetails($unit->id, true); // Get items with related data

            // Add items to flat array for navigation
            foreach ($unit->items as $item) {
                // Extract duration from metadata if it's a video item
                $duration = 0;
                $thumbnail = '';
                $parsedMetadata = [];

                if (!empty($item->metadata)) {
                    $parsedMetadata = is_string($item->metadata)
                        ? json_decode($item->metadata, true)
                        : $item->metadata;

                    if (!is_array($parsedMetadata)) {
                        $parsedMetadata = [];
                    }
                }

                if ($item->item_type === 'video' && !empty($parsedMetadata)) {
                    $duration = $parsedMetadata['video_duration'] ?? 0;
                    $thumbnail = $parsedMetadata['video_thumbnail'] ?? '';
                }

                // Add duration property to the item object for view access
                $item->duration = $duration;
                $item->thumbnail = $thumbnail;

                $flatItems[] = [
                    'id' => $item->id,
                    'unit_id' => $unit->id,
                    'unit_name' => $unit->unit_name,
                    'item_type' => $item->item_type,
                    'title' => $item->title,
                    'description' => $item->description,
                    'item_id' => $item->item_id, // References quiz_id, page_id, or video_id
                    'duration' => $duration,
                    'thumbnail' => $thumbnail,
                    'metadata' => $parsedMetadata,
                    'quiz_details' => $item->quiz_details ?? null,
                    'page_details' => $item->page_details ?? null,
                    'is_preview' => false // Will be determined by enrollment
                ];
            }
        }
        unset($unit);

        // 4) Check which item is requested in ?item=XYZ (generic parameter for all item types)
        $requestedItemId = $this->request->getGet('item') ?: $this->request->getGet('video') ?: $this->request->getGet('item_id');

        // 5) If no specific item is requested, jump to the first one
        if (!$requestedItemId && !empty($flatItems)) {
            $requestedItemId = $flatItems[0]['id'];
        }

        // 6) Find the current item in $flatItems
        $currentIndex = array_search($requestedItemId, array_column($flatItems, 'id'));
        $currentItem = ($currentIndex !== false) ? $flatItems[$currentIndex] : null;

        // 7) Determine next & prev items
        $prevItem = ($currentIndex > 0)
            ? $flatItems[$currentIndex - 1]
            : null;

        $nextItem = ($currentIndex !== false && $currentIndex < count($flatItems) - 1)
            ? $flatItems[$currentIndex + 1]
            : null;

        // 8) Mark correct unit open & the correct item active
        if ($currentItem) {
            $currentUnitId = $currentItem['unit_id'];
            foreach ($units as &$unit) {
                if ($unit->id == $currentUnitId) {
                    $unit->is_open = true;
                    foreach ($unit->items as &$item) {
                        if ($item->id == $requestedItemId) {
                            $item->is_active = true;
                        }
                    }
                }
            }
            unset($unit);
        } else {
            // If we can't find the current item, open the first unit by default
            if (!empty($units)) {
                $units[0]->is_open = true;
                if (!empty($units[0]->items)) {
                    $units[0]->items[0]->is_active = true;
                }
            }
        }

        // 9) Calculate progress using the Progress module
        $courseProgress = 0;
        if ($hasAccess) {
            $progressModel = new \Modules\Progress\Models\UserUnitProgressModel();
            $courseProgress = $progressModel->getCourseCompletionPercentage($userId, $course->id);
        }

        // 10) Prepare data for the view based on current item type
        $videoId = 'dQw4w9WgXcQ'; // Default fallback
        $videoLibraryId = '495222'; // Default fallback
        $itemTitle = 'Default Item Title';
        $itemDesc = 'Default item description';
        $quizData = null;
        $pageData = null;

        if ($currentItem) {
            $itemTitle = $currentItem['title'];
            $itemDesc = $currentItem['description'];

            // Debug logging for switch case
            file_put_contents('d:/laragon/www/msarlink/debug.log', 
                date('Y-m-d H:i:s') . ' SWITCH CASE DEBUG - ENTERING IF BLOCK' . "\n", 
                FILE_APPEND | LOCK_EX);
            file_put_contents('d:/laragon/www/msarlink/debug.log', 
                date('Y-m-d H:i:s') . ' SWITCH CASE DEBUG - item_type: ' . $currentItem['item_type'] . "\n", 
                FILE_APPEND | LOCK_EX);
            file_put_contents('d:/laragon/www/msarlink/debug.log', 
                date('Y-m-d H:i:s') . ' SWITCH CASE DEBUG - metadata: ' . json_encode($currentItem['metadata']) . "\n", 
                FILE_APPEND | LOCK_EX);
            file_put_contents('d:/laragon/www/msarlink/debug.log', 
                date('Y-m-d H:i:s') . ' SWITCH CASE DEBUG - ABOUT TO ENTER SWITCH' . "\n", 
                FILE_APPEND | LOCK_EX);

            switch ($currentItem['item_type']) {
                case 'video':
                    file_put_contents('d:/laragon/www/msarlink/debug.log', 
                        date('Y-m-d H:i:s') . ' SWITCH CASE DEBUG - ENTERED VIDEO CASE' . "\n", 
                        FILE_APPEND | LOCK_EX);
                    // Extract video_id and video_library_id from metadata
                    if (!empty($currentItem['metadata']) && is_array($currentItem['metadata'])) {
                        $videoId = $currentItem['metadata']['video_id'] ?? $currentItem['item_id'] ?? 'dQw4w9WgXcQ';

                        // BunnyCDN expects numeric library ID, not UUID
                        // The collection_id is a UUID, but we need the actual library ID
                        $videoLibraryId = '495222'; // Default BunnyCDN library ID

                        // Only use video_library_id if it's numeric (valid BunnyCDN library ID)
                        if (!empty($currentItem['metadata']['video_library_id']) &&
                            is_numeric($currentItem['metadata']['video_library_id'])) {
                            $videoLibraryId = $currentItem['metadata']['video_library_id'];
                        }

                    } else {
                        $videoId = $currentItem['item_id'] ?? 'dQw4w9WgXcQ';
                        $videoLibraryId = '495222'; // Default fallback
                    }
                    break;
                case 'quiz':
                    file_put_contents('d:/laragon/www/msarlink/debug.log', 
                        date('Y-m-d H:i:s') . ' SWITCH CASE DEBUG - ENTERED QUIZ CASE' . "\n", 
                        FILE_APPEND | LOCK_EX);
                    // Extract quiz_id from metadata and fetch quiz data
                    if (!empty($currentItem['metadata']) && is_array($currentItem['metadata'])) {
                        $quizId = $currentItem['metadata']['quiz_id'] ?? $currentItem['item_id'];

                        if ($quizId) {
                            // Load QuizzesModel and fetch quiz data
                            $quizzesModel = new \Modules\Quizzes\Models\QuizzesModel();
                            $quizData = $quizzesModel->getQuizById($quizId);

                            // Debug logging for quiz loading
                            file_put_contents('d:/laragon/www/msarlink/debug.log', 
                                date('Y-m-d H:i:s') . ' QUIZ LOADING DEBUG - quizId: ' . $quizId . "\n", 
                                FILE_APPEND | LOCK_EX);
                            file_put_contents('d:/laragon/www/msarlink/debug.log', 
                                date('Y-m-d H:i:s') . ' QUIZ LOADING DEBUG - quizData result: ' . json_encode($quizData) . "\n", 
                                FILE_APPEND | LOCK_EX);

                            if ($quizData) {
                                $itemDesc = $quizData->quiz_desc ?? $itemDesc;
                                
                                // Add user attempt information if user is logged in
                                $user = session()->get('user');
                                $userId = $user['id'] ?? null;
                                
                                if ($userId) {
                                    $attemptsModel = new \Modules\Quizzes\Models\QuizAttemptsModel();
                                    $userAttemptCount = $attemptsModel->getUserAttemptCount($userId, $quizId);
                                    $userBestScore = $attemptsModel->getUserBestScore($userId, $quizId);
                                    $userLatestAttempt = $attemptsModel->getUserLatestAttempt($userId, $quizId);
                                    
                                    // Add attempt information to quiz data
                                    $quizData->user_attempt_count = $userAttemptCount;
                                    $quizData->user_best_score = $userBestScore;
                                    $quizData->remaining_attempts = max(0, $quizData->max_attempts - $userAttemptCount);
                                    $quizData->has_exceeded_attempts = $userAttemptCount >= $quizData->max_attempts;
                                    $quizData->user_latest_attempt = $userLatestAttempt;
                                    
                                    // Debug logging for attempt info
                                    file_put_contents('d:/laragon/www/msarlink/debug.log', 
                                        date('Y-m-d H:i:s') . ' ATTEMPT INFO DEBUG - User: ' . $userId . ', Attempts: ' . $userAttemptCount . '/' . $quizData->max_attempts . "\n", 
                                        FILE_APPEND | LOCK_EX);
                                }
                            }
                        }
                    } else if (isset($currentItem['quiz_details'])) {
                        $quizData = $currentItem['quiz_details'];
                        $itemDesc = $quizData->quiz_desc ?? $itemDesc;
                    }
                    break;
                case 'page':
                    // Extract page_id from metadata and fetch page data
                    if (!empty($currentItem['metadata']) && is_array($currentItem['metadata'])) {
                        $pageId = $currentItem['metadata']['page_id'] ?? $currentItem['item_id'];

                        if ($pageId) {
                            // Load PagesModel and fetch page data
                            $pagesModel = new \Modules\Pages\Models\PagesModel();
                            $pageData = $pagesModel->find($pageId);

                            if ($pageData) {
                                $itemDesc = $pageData->content ?? $itemDesc;
                            }
                        }
                    } else if (isset($currentItem['page_details'])) {
                        $pageData = $currentItem['page_details'];
                        $itemDesc = $pageData->content ?? $itemDesc;
                    }
                    break;
            }
        }

        // Debug logging for quiz data
        log_message('debug', 'COURSES_CONTROLLER DEBUG - quizData: ' . json_encode($quizData));
        error_log('COURSES_CONTROLLER DEBUG - quizData: ' . json_encode($quizData));
        file_put_contents('D:\laragon\www\msarlink\debug.log', 
            date('Y-m-d H:i:s') . ' COURSES_CONTROLLER DEBUG - quizData: ' . json_encode($quizData) . "\n", 
            FILE_APPEND | LOCK_EX);

        $data = [
            'title'             => $course->course_title,
            'course'            => $course,
            'units'             => $units, // Changed from 'structure' to 'units'
            'course_progress'   => $courseProgress,
            'current_id'        => $requestedItemId,
            'current_item'      => $currentItem,
            'current_item_type' => $currentItem ? $currentItem['item_type'] : 'video',
            'video_id'          => $videoId,
            'video_library_id'  => $videoLibraryId,
            'video_title'       => $itemTitle,
            'video_desc'        => $itemDesc,
            'itemTitle'         => $itemTitle,
            'itemDesc'          => $itemDesc,
            'quiz_data'         => $quizData,
            'page_data'         => $pageData,
            'metadata'          => $currentItem['metadata'] ?? [],
            'prevLessonUrl'     => $prevItem
                ? site_url('courses/course_view/'.$slug.'?item='.$prevItem['id'])
                : null,
            'nextLessonUrl'     => $nextItem
                ? site_url('courses/course_view/'.$slug.'?item='.$nextItem['id'])
                : null,
        ];

        // Debug logging
        log_message('debug', 'COURSES_CONTROLLER DEBUG - currentItem: ' . json_encode($currentItem));
        log_message('debug', 'COURSES_CONTROLLER DEBUG - requestedItemId: ' . $requestedItemId);
        log_message('debug', 'COURSES_CONTROLLER DEBUG - flatItems count: ' . count($flatItems));
        
        // Also use error_log for immediate visibility
        error_log('COURSES_CONTROLLER DEBUG - currentItem: ' . json_encode($currentItem));
        error_log('COURSES_CONTROLLER DEBUG - requestedItemId: ' . $requestedItemId);
        error_log('COURSES_CONTROLLER DEBUG - flatItems count: ' . count($flatItems));
        
        // Write to custom debug file
        file_put_contents('D:\laragon\www\msarlink\debug.log', 
            date('Y-m-d H:i:s') . ' COURSES_CONTROLLER DEBUG - currentItem: ' . json_encode($currentItem) . "\n", 
            FILE_APPEND | LOCK_EX);
        file_put_contents('D:\laragon\www\msarlink\debug.log', 
            date('Y-m-d H:i:s') . ' COURSES_CONTROLLER DEBUG - requestedItemId: ' . $requestedItemId . "\n", 
            FILE_APPEND | LOCK_EX);
        file_put_contents('D:\laragon\www\msarlink\debug.log', 
            date('Y-m-d H:i:s') . ' COURSES_CONTROLLER DEBUG - flatItems count: ' . count($flatItems) . "\n", 
            FILE_APPEND | LOCK_EX);

        return view('site/course_view', $data);
    }

    /**
     * Enroll the current user in a course (requires user_id from auth).
     */
    public function enroll(int $courseId): RedirectResponse
    {
        $userId = auth()->user()->id;
        if (!$userId) {
            return redirect()->to('login')->with('error', 'Please log in first.');
        }

        $course = $this->coursesModel->find($courseId);
        if (!$course) {
            throw PageNotFoundException::forPageNotFound();
        }

        // Enroll user in the course
        $this->coursesModel->enrollUser($userId, $courseId);

        return redirect()->to('/courses/my_courses')->with('success', 'Enrolled in course!');
    }

    /**
     * Mark an item as complete for the current user in the given course (via slug).
     */
    public function markLessonComplete(): RedirectResponse
    {
        $userId = auth()->user()->id;
        if (!$userId) {
            return redirect()->to('login')->with('error', 'Please log in first.');
        }

        $itemId = (int) $this->request->getPost('id');
        $slug   = $this->request->getPost('slug'); // from hidden input in form

        // 1) Find the course by slug
        $course = $this->coursesModel->getCourseBySlug($slug);
        if (!$course) {
            throw PageNotFoundException::forPageNotFound();
        }

        // 2) Check enrollment
        $enrollment = $this->coursesModel->getEnrollment($userId, $course->id);
        if (!$enrollment) {
            return redirect()->to('/courses/'.$slug)->with('error', 'You are not enrolled in this course.');
        }

        // 3) Mark the item as complete
        // Use the existing method for now - this tracks video completion
        $this->coursesModel->markLessonComplete($enrollment->id, $itemId);

        // 4) Get course units and flatten items to find next item
        $units = $this->unitsModel->getUnitsByCourse($course->id);
        $flatItems = [];

        foreach ($units as $unit) {
            $items = $this->unitItemsModel->getUnitItems($unit->id, true);
            foreach ($items as $item) {
                $flatItems[] = [
                    'id' => $item->id,
                    'unit_id' => $unit->id,
                    'title' => $item->title
                ];
            }
        }

        // Debug logging
        log_message('debug', 'Current item ID: ' . $itemId);
        log_message('debug', 'Flat items: ' . json_encode($flatItems));

        // Locate the current item index
        $currentIndex = array_search($itemId, array_column($flatItems, 'id'));
        log_message('debug', 'Current index: ' . ($currentIndex !== false ? $currentIndex : 'not found'));

        if ($currentIndex !== false) {
            $nextIndex = $currentIndex + 1;
            log_message('debug', 'Next index: ' . $nextIndex);
            // 5) If next item exists, redirect there
            if (isset($flatItems[$nextIndex])) {
                $nextItemId = $flatItems[$nextIndex]['id'];
                log_message('debug', 'Next item ID: ' . $nextItemId);
                $redirectUrl = site_url('courses/course_view/'.$slug.'?item='.$nextItemId);
                log_message('debug', 'Redirect URL: ' . $redirectUrl);
                return redirect()->to($redirectUrl)
                    ->with('success', 'Item marked as complete! Moving to next item...');
            } else {
                log_message('debug', 'No next item found - reached end of course');
            }
        } else {
            log_message('debug', 'Current item not found in flat items array');
        }

        // 6) If no next item, just redirect back or to the course_view
        return redirect()->back()->with('success', 'Item marked as complete! No more items.');
    }


    /**
     * Show all courses the user is enrolled in, plus dynamic progress.
     */
    public function my_courses()
    {
        $userId = auth()->user()->id;

        if (!$userId) {
            return redirect()->to('login')->with('error', 'Please log in first.');
        }

        $myCourses = $this->coursesModel->getAllUserCourses($userId);
        $enrolledCourses = [];

        foreach ($myCourses as $courseObj) {
            // find enrollment
            $enrollment = $this->coursesModel->getEnrollment($userId, $courseObj->id);
            $progress   = 0;

            // TODO: Implement progress calculation for units system
            // Calculate progress using the Progress module
            if ($enrollment) {
                $progressModel = new \Modules\Progress\Models\UserUnitProgressModel();
                $progress = $progressModel->getCourseCompletionPercentage($userId, $courseObj->id);
            }

            $enrolledCourses[] = [
                'course'   => $courseObj,
                'progress' => $progress,
            ];
        }

        $data = [
            'title'           => 'My Courses',
            'desc'            => 'All courses you are enrolled in',
            'enrolledCourses' => $enrolledCourses,
        ];

        return view('site/my_courses', $data);
    }

    /**
     * Purchase course with credits
     */
    public function purchaseWithCredits(int $courseId): RedirectResponse
    {
        $userId = auth()->user()->id ?? null;
        if (!$userId) {
            return redirect()->to('login')->with('error', 'Please log in first.');
        }

        $course = $this->coursesModel->find($courseId);
        if (!$course || !$course->active) {
            throw PageNotFoundException::forPageNotFound();
        }

        // Check if already enrolled
        if ($this->coursesModel->isUserEnrolled($userId, $courseId)) {
            return redirect()->back()->with('error', 'You are already enrolled in this course.');
        }

        // Check if course is free
        if ($course->is_free) {
            $this->coursesModel->enrollUser($userId, $courseId);
            return redirect()->to('/courses/my_courses')->with('success', 'Successfully enrolled in free course!');
        }

        // For paid courses, redirect to unit enrollment since pricing is now unit-based
        return redirect()->to('/enrollments/enroll/' . $courseId)->with('info', 'Please select units to enroll in this course.');
    }

    /**
     * Mark course item as complete
     */
    public function mark_complete()
    {
        if (!$this->request->getMethod() === 'POST') {
            return redirect()->back()->with('error', 'Invalid request method.');
        }

        $userId = auth()->user()->id ?? null;
        if (!$userId) {
            return redirect()->to('login')->with('error', 'Please log in first.');
        }

        $itemId = $this->request->getPost('id');
        $courseSlug = $this->request->getPost('slug');
        $itemType = $this->request->getPost('item_type') ?? 'video';

        if (!$itemId || !$courseSlug) {
            return redirect()->back()->with('error', 'Missing required parameters.');
        }

        // Get the unit item
        $unitItem = $this->unitItemsModel->find($itemId);
        if (!$unitItem) {
            return redirect()->back()->with('error', 'Item not found.');
        }

        // Check if user has access to this unit
        if (!$this->checkUnitAccess($userId, $unitItem->unit_id)) {
            return redirect()->back()->with('error', 'You do not have access to this content.');
        }

        // Handle completion based on item type
        $success = false;
        switch ($itemType) {
            case 'video':
                $success = $this->markVideoComplete($userId, $itemId, $unitItem);
                break;
            case 'quiz':
                // For quizzes, completion is handled by the quiz system
                $success = $this->markQuizComplete($userId, $itemId, $unitItem);
                break;
            case 'page':
                $success = $this->markPageComplete($userId, $itemId, $unitItem);
                break;
        }

        if ($success) {
            return redirect()->back()->with('success', 'Item marked as complete!');
        } else {
            return redirect()->back()->with('error', 'Failed to mark item as complete.');
        }
    }

    /**
     * Mark video item as complete
     */
    private function markVideoComplete($userId, $itemId, $unitItem): bool
    {
        // Load Progress model to save completion
        $progressModel = new \Modules\Progress\Models\UserUnitProgressModel();
        
        // Mark unit as completed with 100% progress
        $success = $progressModel->markUnitCompleted($userId, $unitItem->unit_id);
        
        if ($success) {
            log_message('info', "User {$userId} completed video item {$itemId} in unit {$unitItem->unit_id}");
        } else {
            log_message('error', "Failed to mark video item {$itemId} as complete for user {$userId}");
        }
        
        return $success;
    }

    /**
     * Mark quiz item as complete
     */
    private function markQuizComplete($userId, $itemId, $unitItem): bool
    {
        // First check if user has already completed the quiz through the quiz system
        $quizModel = new \Modules\Quizzes\Models\QuizAttemptsModel();
        $attempts = $quizModel->where('user_id', $userId)
                             ->where('quiz_id', $unitItem->item_id)
                             ->where('is_completed', 1)
                             ->findAll();

        $quizCompleted = !empty($attempts);
        
        // If quiz is completed, update the progress system
        if ($quizCompleted) {
            // Load Progress model to save completion
            $progressModel = new \Modules\Progress\Models\UserItemProgressModel();
            
            // Get user enrollment for this course
            $enrollmentModel = new \Modules\Courses\Models\EnrollmentModel();
            $enrollment = $enrollmentModel->where([
                'user_id' => $userId,
                'course_id' => $unitItem->course_id
            ])->first();
            
            if (!$enrollment) {
                log_message('error', 'No enrollment found for user ' . $userId . ' in course ' . $unitItem->course_id);
                return false;
            }
            
            // Update progress for this quiz item
            $progressData = [
                'user_id' => $userId,
                'unit_id' => $unitItem->unit_id,
                'item_id' => $itemId,
                'enrollment_id' => $enrollment->id,
                'progress_percentage' => 100.00,
                'is_completed' => 1,
                'completed_at' => date('Y-m-d H:i:s'),
                'last_accessed_at' => date('Y-m-d H:i:s')
            ];
            
            // Check if progress record exists
            $existingProgress = $progressModel->where([
                'user_id' => $userId,
                'item_id' => $itemId
            ])->first();
            
            if ($existingProgress) {
                $progressModel->update($existingProgress->id, $progressData);
            } else {
                $progressData['first_accessed_at'] = date('Y-m-d H:i:s');
                $progressModel->insert($progressData);
            }
            
            log_message('info', "Quiz item {$itemId} marked as complete for user {$userId}");
            return true;
        }
        
        return false;
    }

    /**
     * Mark page item as complete
     */
    private function markPageComplete($userId, $itemId, $unitItem): bool
    {
        // Load Progress model to save completion
        $progressModel = new \Modules\Progress\Models\UserUnitProgressModel();
        
        // Mark unit as completed with 100% progress
        $success = $progressModel->markUnitCompleted($userId, $unitItem->unit_id);
        
        if ($success) {
            log_message('info', "User {$userId} completed page item {$itemId} in unit {$unitItem->unit_id}");
        } else {
            log_message('error', "Failed to mark page item {$itemId} as complete for user {$userId}");
        }
        
        return $success;
    }

    /**
     * Show course structure with sections and units
     */
    public function courseStructure(string $slug): string
    {
        $course = $this->coursesModel->getCourseBySlug($slug);
        if (!$course) {
            throw PageNotFoundException::forPageNotFound();
        }

        $courseWithStructure = $this->coursesModel->getCourseWithStructure($course->id);

        $userId = auth()->user()->id ?? null;
        $isEnrolled = false;
        $progress = 0;

        if ($userId) {
            $isEnrolled = $this->coursesModel->isUserEnrolled($userId, $course->id);
            if ($isEnrolled) {
                $enrollment = $this->coursesModel->getEnrollment($userId, $course->id);
                $progress = $this->coursesModel->calculateProgress($course, $enrollment);
            }
        }

        $data = [
            'title' => $course->course_title . ' - Course Structure',
            'course' => $courseWithStructure,
            'isEnrolled' => $isEnrolled,
            'progress' => $progress
        ];

        return view('site/course_details', $data);
    }

    /**
     * Search courses
     */
    public function search(): string
    {
        $query = $this->request->getGet('q') ?? '';
        $courses = [];

        if (!empty($query)) {
            $courses = $this->coursesModel->searchCourses($query);

            // Add enrollment status for logged-in users
            $userId = session()->get('user_id');
            if ($userId) {
                foreach ($courses as &$course) {
                    $course->is_enrolled = $this->coursesModel->isUserEnrolled($userId, $course->id);
                }
            }
        }

        $data = [
            'title' => 'Search Results',
            'desc' => 'Search results for: ' . htmlspecialchars($query),
            'courses' => $courses,
            'query' => $query
        ];

        return view('site/search_results', $data);
    }

    /**
     * Get course progress via AJAX
     */
    public function getCourseProgress(int $courseId)
    {
        $userId = auth()->user()->id ?? null;
        if (!$userId) {
            return $this->response->setJSON(['error' => 'Not authenticated']);
        }

        $course = $this->coursesModel->find($courseId);
        if (!$course) {
            return $this->response->setJSON(['error' => 'Course not found']);
        }

        $enrollment = $this->coursesModel->getEnrollment($userId, $courseId);
        if (!$enrollment) {
            return $this->response->setJSON(['error' => 'Not enrolled']);
        }

        $progress = $this->coursesModel->calculateProgress($course, $enrollment);
        $completedLessons = $this->coursesModel->countCompletedLessons($enrollment->id);
        $totalLessons = $this->unitsModel->where('course_id', $courseId)
                                        ->where('active', 1)
                                        ->countAllResults();

        return $this->response->setJSON([
            'progress' => $progress,
            'completed_lessons' => $completedLessons,
            'total_lessons' => $totalLessons
        ]);
    }

    /**
     * Mark unit as complete (AJAX)
     */
    public function markUnitComplete()
    {
        $userId = auth()->user()->id ?? null;
        if (!$userId) {
            return $this->response->setJSON(['error' => 'Not authenticated']);
        }

        $unitId = $this->request->getPost('unit_id');
        $courseId = $this->request->getPost('course_id');

        if (!$unitId || !$courseId) {
            return $this->response->setJSON(['error' => 'Missing parameters']);
        }

        // Verify enrollment
        $enrollment = $this->coursesModel->getEnrollment($userId, $courseId);
        if (!$enrollment) {
            return $this->response->setJSON(['error' => 'Not enrolled in course']);
        }

        // Mark unit as complete
        $this->coursesModel->markLessonComplete($enrollment->id, $unitId);

        // Get updated progress
        $course = $this->coursesModel->find($courseId);
        $progress = $this->coursesModel->calculateProgress($course, $enrollment);

        // Get next unit
        $nextUnit = $this->unitsModel->getNextUnit($unitId);

        return $this->response->setJSON([
            'success' => true,
            'progress' => $progress,
            'next_unit' => $nextUnit ? [
                'id' => $nextUnit->id,
                'title' => $nextUnit->unit_title,
                'url' => site_url('courses/unit/' . $nextUnit->id)
            ] : null
        ]);
    }

    /**
     * Check if user has access to course content through unit enrollments
     */
    private function checkCourseAccess($userId, $courseId): bool
    {
        // Get all units for this course
        $courseUnits = $this->db->table('tb_units')
            ->select('id')
            ->where('course_id', $courseId)
            ->where('active', 1)
            ->get()
            ->getResultArray();

        if (empty($courseUnits)) {
            return false;
        }

        $courseUnitIds = array_column($courseUnits, 'id');

        // Check if user has enrollment for any of these units
        $enrollments = $this->db->table('tb_unit_enrollments')
            ->where('user_id', $userId)
            ->where('status', 'approved')
            ->get()
            ->getResultArray();

        foreach ($enrollments as $enrollment) {
            $unitIds = json_decode($enrollment['unit_ids'], true);
            if ($unitIds && array_intersect($unitIds, $courseUnitIds)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has access to a specific unit
     */
    private function checkUnitAccess($userId, $unitId): bool
    {
        // Check if unit is free/preview
        $unit = $this->unitsModel->find($unitId);
        if ($unit && isset($unit->is_free) && $unit->is_free) {
            return true;
        }

        // Check unit purchases
        $unitPurchasesModel = new \Modules\Units\Models\UnitPurchasesModel();
        return $unitPurchasesModel->hasUnitAccess($userId, $unitId);
    }

    /**
     * View individual unit with progress tracking
     */
    public function viewUnit(int $unitId): string
    {
        $unit = $this->unitsModel->find($unitId);
        if (!$unit || !$unit->active) {
            throw PageNotFoundException::forPageNotFound();
        }

        // Get course info
        $course = $this->coursesModel->find($unit->course_id);
        if (!$course) {
            throw PageNotFoundException::forPageNotFound();
        }

        // Check enrollment
        $userId = auth()->user()->id ?? null;
        if (!$userId) {
            return redirect()->to('login')->with('error', 'Please log in to access course content.');
        }

        $enrollment = $this->coursesModel->getEnrollment($userId, $course->id);
        if (!$enrollment && !$unit->is_preview) {
            return redirect()->to('/courses/' . $course->slug)->with('error', 'You must be enrolled to access this content.');
        }

        // Get navigation
        $prevUnit = $this->unitsModel->getPreviousUnit($unitId);
        $nextUnit = $this->unitsModel->getNextUnit($unitId);

        // Get progress data from new progress system
        $progressModel = new \Modules\Progress\Models\UserUnitProgressModel();
        $unitProgress = null;
        $isCompleted = false;

        if ($enrollment) {
            $unitProgress = $progressModel->getUserUnitProgress($userId, $unitId);
            $isCompleted = $unitProgress ? $unitProgress->is_completed : false;
        }

        // Get course completion percentage
        $courseCompletion = 0;
        if ($enrollment) {
            $courseCompletion = $progressModel->getCourseCompletionPercentage($userId, $course->id);
        }

        $data = [
            'title' => $unit->unit_title,
            'course' => $course,
            'section' => $section,
            'unit' => $unit,
            'prevUnit' => $prevUnit,
            'nextUnit' => $nextUnit,
            'isCompleted' => $isCompleted,
            'enrollment' => $enrollment,
            'unitProgress' => $unitProgress,
            'courseCompletion' => $courseCompletion,
            'enableProgressTracking' => true
        ];

        return view('site/unit_view', $data);
    }

}
