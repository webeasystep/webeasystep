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

        // Check if user has access to this unit
        $hasAccess = $this->checkUnitAccess($userId, $unit->id);
        if (!$hasAccess) {
            // Check if unit is free/preview
            if (!$unit->is_free) {
                // Redirect back to current course view with Arabic flash message
                $currentUrl = $this->request->getServer('HTTP_REFERER') ?? site_url('courses/course_view/' . $course->slug);
                return redirect()->to($currentUrl)
                    ->with('error', 'يجب عليك شراء الكورس أولاً حتى تتمكن من مشاهدة المحتوى');
            }
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
            // Get enrolled course IDs for logged-in user
            $enrolledCourseIds = $this->db
                ->table('tb_course_enrollments')
                ->select('course_id')
                ->where('user_id', $userId)
                ->where('status', 'approved')
                ->get()
                ->getResultArray();

            $enrolledCourseIds = array_column($enrolledCourseIds, 'course_id');
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

            // Count videos, pages, quizzes, and units from database
            $videoCount = 0;
            $pageCount = 0;
            $unitCount = 0;
            $quizCount = 0;
            $totalDuration = 0;

            // Get units count for this course
            $unitCount = $this->db->table('tb_units')
                ->where('course_id', $course['id'])
                ->where('active', 1)
                ->countAllResults();

            // Get quiz count for this course
            $quizCount = $this->db->table('tb_quizzes')
                ->where('course_id', $course['id'])
                ->where('active', 1)
                ->countAllResults();

            // Get unit items and separate by type
            $unitItems = $this->db->table('tb_unit_items ui')
                ->select('ui.*, u.course_id')
                ->join('tb_units u', 'u.id = ui.unit_id')
                ->where('u.course_id', $course['id'])
                ->where('u.active', 1)
                ->where('ui.is_active', 1)
                ->get()
                ->getResultArray();

            // Count items by type and calculate duration
            foreach ($unitItems as $item) {
                if ($item['item_type'] === 'video') {
                    $videoCount++;

                    // Calculate duration from metadata (video_duration in seconds)
                    if (!empty($item['metadata'])) {
                        $metadata = json_decode($item['metadata'], true);
                        if (isset($metadata['video_duration'])) {
                            // Convert seconds to minutes and round
                            $totalDuration += round((int)$metadata['video_duration'] / 60);
                        }
                    }
                    // Fallback to duration field if metadata is not available
                    elseif (!empty($item['duration'])) {
                        $totalDuration += round((int)$item['duration'] / 60);
                    }
                } elseif ($item['item_type'] === 'page') {
                    $pageCount++;
                }
            }

            $course['video_count'] = $videoCount;
            $course['page_count'] = $pageCount;
            $course['unit_count'] = $unitCount;
            $course['quiz_count'] = $quizCount;

            // Keep legacy fields for backward compatibility
            $course['lesson_count'] = $videoCount; // Videos are considered lessons
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

        // Check if user is enrolled in the course via tb_course_enrollments
        $enrollment = $this->db->table('tb_course_enrollments')
            ->where('user_id', $userId)
            ->where('course_id', $course['id'])
            ->where('status', 'approved')
            ->get()
            ->getRow();

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
        $userId = auth()->loggedIn() ? auth()->user()->id : null;
        $enrolledCourseIds = [];

        // If user is logged in, fetch the courses they're enrolled in
        if (!empty($userId)) {
            $enrolledCourseIds = $this->db
                ->table('tb_course_enrollments')
                ->select('course_id')
                ->where('user_id', $userId)
                ->where('status', 'approved')
                ->get()
                ->getResultArray();

            $enrolledCourseIds = array_column($enrolledCourseIds, 'course_id');
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
    public function course_details(string $slug)
    {
        // 1) Fetch the course by slug
        $course = $this->coursesModel->getCourseBySlug($slug);
        
        if (!$course) {
            throw PageNotFoundException::forPageNotFound();
        }

        // Redirect to home if course is in waiting list mode
        if ($course->waiting_list == 1) {
            return redirect()->to('/')->with('info', 'هذه الدورة قيد الإعداد وستتوفر قريباً. ترقب!');
        }

        // 2) Get course units with their items
        $units = $this->unitsModel->getUnitsByCourse($course->id);

        // 2.1) Check if user is logged in and get user ID
        $userId = auth()->loggedIn() ? auth()->user()->id : null;

        // 2.2) Check user's course enrollment
        $isCourseEnrolled = false;
        if ($userId) {
            $isCourseEnrolled = $this->coursesModel->isUserEnrolled($userId, $course->id);
        }

        // If user is enrolled in the course, they have access to all units.
        // So we don't need to filter out "purchased units" in the same way, 
        // effectively all units are "purchased" / "enrolled".
        // However, the original logic seemed to filter them out from the list of *available to purchase* units?
        // Or was it filtering them out from the display?
        // "Filter out units that user has already purchased and been approved for" -> implies hiding them from a "buy units" list?
        // But the view usually shows all units.
        // Let's assume for now valid course enrollment means all units are "owned".
        
        $filteredUnits = $units; // Show all units
        // If we strictly wanted to hide "enrolled" units (like for a specific "buy units" view), we would filter.
        // But for course_details which is often the sales page, we usually show everything.
        // If the user IS enrolled, they usually see "Access Course" button instead of buy buttons per unit.

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
        // Debug: Log method entry


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

        // 3) Get course units with their items
        $allUnits = $this->unitsModel->getUnitsByCourse($course->id);

        // Get ALL units for display
        $units = [];
        foreach ($allUnits as $unit) {
            // Mark unit as enrolled if user has course access
            $unit->is_enrolled = $hasAccess;

            $units[] = $unit;
        }

        // Get unit items for each unit (enrolled and unenrolled)
        $flatItems = [];
        foreach ($units as &$unit) {
            // Get items for all units (enrolled, free, and locked)
            $unit->items = $this->unitItemsModel->getUnitItemsWithDetails($unit->id, true); // Get items with related data

            // Mark items as locked for unenrolled non-free items
            if (!$unit->is_enrolled) {
                foreach ($unit->items as &$item) {
                    if (!isset($item->is_free) || $item->is_free != 1) {
                        $item->is_locked = true;
                    }
                }
                unset($item);
            }

            // Add items to flat array for navigation (enrolled units + free units)
            $includeInNavigation = $unit->is_enrolled || true; // Allow free items to be included

            if ($includeInNavigation) {
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
                        'is_preview' => false, // Will be determined by enrollment
                        'is_free_item' => isset($item->is_free) ? $item->is_free : 0 // Track if this item is free
                    ];
                }
            }
        }
        unset($unit);

        // 4) Check which item is requested in ?item=XYZ (generic parameter for all item types)
        $requestedItemId = $this->request->getGet('item') ?: $this->request->getGet('video') ?: $this->request->getGet('item_id');

        // Check for last_item parameter (used for external navigation)
        $lastItemId = $this->request->getGet('last_item');

        // 5) If no specific item is requested, redirect to a valid item (server-side)
        if (!$requestedItemId && !empty($flatItems)) {
            if ($lastItemId) {
                // Validate that the last item exists in the flat items list
                foreach ($flatItems as $item) {
                    if ($item['id'] == $lastItemId) {
                        return redirect()->to(site_url('courses/course_view/' . $slug . '?item=' . $lastItemId));
                    }
                }
            }

            // TRY TO FIND LAST WATCHED ITEM IN DB
            $lastAccessedItem = $this->db->table('tb_user_item_progress')
                ->select('tb_user_item_progress.item_id, tb_user_item_progress.is_completed')
                ->join('tb_units', 'tb_units.id = tb_user_item_progress.unit_id')
                ->where('tb_user_item_progress.user_id', $userId)
                ->where('tb_units.course_id', $course->id)
                ->orderBy('tb_user_item_progress.updated_at', 'DESC')
                ->limit(1)
                ->get()
                ->getRow();

            if ($lastAccessedItem) {
                // Fetch all completed item IDs to know which ones to skip if the last item is completed
                $completedItems = $this->db->table('tb_user_item_progress')
                    ->select('tb_user_item_progress.item_id')
                    ->join('tb_units', 'tb_units.id = tb_user_item_progress.unit_id')
                    ->where('tb_user_item_progress.user_id', $userId)
                    ->where('tb_units.course_id', $course->id)
                    ->where('tb_user_item_progress.is_completed', 1)
                    ->get()
                    ->getResultArray();
                $completedItemIds = array_column($completedItems, 'item_id');

                $lastIndex = -1;
                foreach ($flatItems as $index => $item) {
                    if ($item['id'] == $lastAccessedItem->item_id) {
                        $lastIndex = $index;
                        break;
                    }
                }

                if ($lastIndex !== -1) {
                    $targetItemId = $lastAccessedItem->item_id;

                    // If the last accessed item is completed, find the next uncompleted item
                    if ($lastAccessedItem->is_completed == 1) {
                        for ($i = $lastIndex + 1; $i < count($flatItems); $i++) {
                            if (!in_array($flatItems[$i]['id'], $completedItemIds)) {
                                $targetItemId = $flatItems[$i]['id'];
                                break;
                            }
                        }
                    }

                    return redirect()->to(site_url('courses/course_view/' . $slug . '?item=' . $targetItemId));
                }
            }

            // Default: redirect to the first available item
            return redirect()->to(site_url('courses/course_view/' . $slug . '?item=' . $flatItems[0]['id']));
        }

        // 6) Find the current item in $flatItems (search by both id and item_id)
        $currentIndex = false;
        $currentItem = null;

        // Handle case where no item was specified (let JavaScript handle localStorage)
        if ($requestedItemId === null) {
            // Don't search for any item, let JavaScript redirect handle this
            $currentItem = null;
            $currentIndex = false;
        } else {
            // First try to find by id
            $currentIndex = array_search($requestedItemId, array_column($flatItems, 'id'));

            // If not found by id, try to find by item_id
            if ($currentIndex === false) {
                $currentIndex = array_search($requestedItemId, array_column($flatItems, 'item_id'));
            }

            $currentItem = ($currentIndex !== false) ? $flatItems[$currentIndex] : null;
        }

        // If item not found and we have a specific item requested, check if it exists but user doesn't have access
        if (!$currentItem && $requestedItemId) {
            // Check if the item exists in the database
            $requestedItem = $this->unitItemsModel->find($requestedItemId);
            if ($requestedItem) {
                // Item exists - check if user has access to the unit
                $unit = $this->unitsModel->find($requestedItem->unit_id);
                if ($unit && $unit->course_id == $course->id) {
                    // Check if user has access to this unit (should always be true if course is enrolled)
                    // If necessary, add specific unit access logic here, but for now course access covers all.
                }
            }

            // If item doesn't exist or belongs to different course, redirect to first available item
            if (!empty($flatItems)) {
                return redirect()->to(site_url('courses/course_view/' . $slug . '?item=' . $flatItems[0]['id']));
            }
        }

        // 7) Determine next & prev items with smart navigation (skip locked units)
        $prevItem = null;
        $nextItem = null;

        if ($currentIndex !== false) {
            // Find previous available item (skip locked items)
            for ($i = $currentIndex - 1; $i >= 0; $i--) {
                $item = $flatItems[$i];
                // Check if this item is accessible (enrolled or free item)
                if ($item['is_free_item'] || $hasAccess) {
                    $prevItem = $item;
                    break;
                }
            }

            // Find next available item (skip locked items)
            for ($i = $currentIndex + 1; $i < count($flatItems); $i++) {
                $item = $flatItems[$i];
                // Check if this item is accessible (enrolled or free item)
                if ($item['is_free_item'] || $hasAccess) {
                    $nextItem = $item;
                    break;
                }
            }
        }

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
        $completedItemIds = [];
        if ($hasAccess) {
            $progressModel = new \Modules\Progress\Models\UserUnitProgressModel();
            $courseProgress = $progressModel->getCourseCompletionPercentage($userId, $course->id);

            // Fetch completed items for this course and user
            $completedItems = $this->db->table('tb_user_item_progress')
                ->select('tb_user_item_progress.item_id')
                ->join('tb_units', 'tb_units.id = tb_user_item_progress.unit_id')
                ->where('tb_user_item_progress.user_id', $userId)
                ->where('tb_units.course_id', $course->id)
                ->where('tb_user_item_progress.is_completed', 1)
                ->get()
                ->getResultArray();
            $completedItemIds = array_column($completedItems, 'item_id');
        }

        // 10) Prepare data for the view based on current item type
        $videoId = null;
        $videoLibraryId = '495222'; // Default fallback
        $itemTitle = '';
        $itemDesc = 'Default item description';
        $quizData = null;
        $pageData = null;

        if ($currentItem) {
            $itemTitle = $currentItem['title'];
            $itemDesc = $currentItem['description'];



            switch ($currentItem['item_type']) {
                case 'video':

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

                    // Extract quiz_id from metadata and fetch quiz data
                    if (!empty($currentItem['metadata']) && is_array($currentItem['metadata'])) {
                        $quizId = $currentItem['metadata']['quiz_id'] ?? $currentItem['item_id'];

                        if ($quizId) {
                            // Load QuizzesModel and fetch quiz data
                            $quizzesModel = new \Modules\Quizzes\Models\QuizzesModel();
                            $quizData = $quizzesModel->getQuizById($quizId);


                            if ($quizData) {
                                $itemDesc = $quizData->quiz_desc ?? $itemDesc;

                                // Add user attempt information if user is logged in
                                $userId = auth()->loggedIn() ? auth()->user()->id : null;

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

        $data = [
            'title'             => $course->course_title,
            'course'            => $course,
            'isEnrolled'        => $hasAccess,
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
            'completedItemIds'  => $completedItemIds,
        ];


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

        return redirect()->to('/enrollments/my-courses')->with('success', 'Enrolled in course!');
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
            $completedUnits = 0;
            $totalUnits = 0;
            $remainingUnits = 0;

            // Calculate progress and unit statistics using the Progress module
            if ($enrollment) {
                $progressModel = new \Modules\Progress\Models\UserUnitProgressModel();
                $progress = $progressModel->getCourseCompletionPercentage($userId, $courseObj->id);

                // Get total units for this course
                $totalUnits = $this->db->table('tb_units')
                                      ->where('course_id', $courseObj->id)
                                      ->where('active', 1)
                                      ->countAllResults();

                // Get completed units count
                $completedUnits = $this->db->table('tb_user_item_progress')
                                          ->select('tb_user_item_progress.unit_id')
                                          ->join('tb_units', 'tb_units.id = tb_user_item_progress.unit_id')
                                          ->where('tb_user_item_progress.user_id', $userId)
                                          ->where('tb_units.course_id', $courseObj->id)
                                          ->where('tb_user_item_progress.is_completed', 1)
                                          ->groupBy('tb_user_item_progress.unit_id')
                                          ->countAllResults();

                // Calculate remaining units
                $remainingUnits = max(0, $totalUnits - $completedUnits);
            } else {
                // If not enrolled, get total units from course data
                $totalUnits = $courseObj->unit_count ?? 0;
                $remainingUnits = $totalUnits;
            }

            $enrolledCourses[] = [
                'course'         => $courseObj,
                'progress'       => $progress,
                'total_units'    => $totalUnits,
                'completed_units' => $completedUnits,
                'remaining_units' => $remainingUnits,
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
            return redirect()->to('/enrollments/my-courses')->with('success', 'Successfully enrolled in free course!');
        }

        // For paid courses, redirect to unit enrollment since pricing is now unit-based
        return redirect()->to('/enrollments/enroll/' . $courseId)->with('info', 'Please select units to enroll in this course.');
    }

    /**
     * Mark course item as complete
     */
    public function mark_complete()
    {
        if (!$this->request->is('post')) {
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
            $userId = auth()->loggedIn() ? auth()->user()->id : null;
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
        // Check for direct enrollment in the course (New System)
        $hasCourseEnrollment = $this->db->table('tb_course_enrollments')
            ->where('course_id', $courseId)
            ->where('user_id', $userId)
            ->where('status', 'approved')
            ->countAllResults();

        if ($hasCourseEnrollment > 0) {
            return true;
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
    public function viewUnit(int $unitId): RedirectResponse
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
