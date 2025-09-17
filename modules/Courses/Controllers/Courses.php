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

            if (!empty($images) && is_array($images)) {
                // Check if it's FireUploader format with 'files' array
                if (isset($images['files']) && is_array($images['files']) && !empty($images['files'])) {
                    $firstFile = $images['files'][0];
                    if (isset($firstFile['full_path']) && !empty($firstFile['full_path'])) {
                        $course['image_url'] = $firstFile['full_path'];
                    }
                }
                // Check if it's a direct array of image paths (legacy format)
                elseif (isset($images[0]) && is_string($images[0])) {
                    $course['image_url'] = $images[0];
                }
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
                            if (!empty($item->duration)) {
                                $totalDuration += $this->parseDurationToMinutes($item->duration);
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
     * Format minutes to readable duration string
     */
    private function formatDuration(int $minutes): string
    {
        if ($minutes < 60) {
            return $minutes . ' دقيقة';
        }

        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        if ($remainingMinutes == 0) {
            return $hours . ' ساعة';
        }

        return $hours . ' ساعة و ' . $remainingMinutes . ' دقيقة';
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
        // 3) Courses
        $data['courses'] = $this->db
            ->table('tb_courses')
            ->where('active', 1)
            ->get()
            ->getResultArray();

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

        // Get unit items for each unit
        foreach ($units as &$unit) {
            $unit->items = $this->unitItemsModel->getUnitItems($unit->id, true); // Only active items
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

        foreach ($units as $unit) {
            if (isset($unit->items)) {
                $totalItems += count($unit->items);
                foreach ($unit->items as $item) {
                    if ($item->item_type === 'video') {
                        $videoCount++;
                        $totalDuration += (int)($item->duration ?? 0);
                    } elseif ($item->item_type === 'quiz') {
                        $quizCount++;
                    }
                }
            }
        }

        // Add calculated stats to course object
        $course->video_count = $videoCount;
        $course->quizzes_count = $quizCount;
        $course->duration = $this->formatDuration($totalDuration / 60); // Convert seconds to minutes

        // 5) Prepare data for the view
        $data = [
            'title' => $course->course_title,
            'course' => $course,
            'units' => $units,
            'isEnrolled' => $isEnrolled,
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

        // Retrieve the enrollment
        $enrollment = $this->coursesModel->getEnrollment($userId, $course->id);
        if (!$enrollment) {
            return redirect()->to('/courses')->with('error', 'You are not enrolled in this course.');
        }

        // 3) Get course units with their items
        $units = $this->unitsModel->getUnitsByCourse($course->id);

        // Get unit items for each unit and flatten all items
        $flatItems = [];
        foreach ($units as &$unit) {
            $unit->items = $this->unitItemsModel->getUnitItems($unit->id, true); // Only active items

            // Add items to flat array for navigation
            foreach ($unit->items as $item) {
                $flatItems[] = [
                    'id' => $item->id,
                    'unit_id' => $unit->id,
                    'unit_name' => $unit->unit_name,
                    'item_type' => $item->item_type,
                    'title' => $item->title,
                    'description' => $item->description,
                    'item_id' => $item->item_id, // video_id for videos
                    'duration' => $item->duration,
                    'is_preview' => false // Will be determined by enrollment
                ];
            }
        }
        unset($unit);

        // 4) Check which item is requested in ?video=XYZ (keeping same parameter name for compatibility)
        $requestedItemId = $this->request->getGet('video');

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

        // 9) Calculate progress (simplified for now)
        $courseProgress = 0; // TODO: Implement progress calculation for units system

        // 10) Prepare data for the view
        $data = [
            'title'             => $course->course_title,
            'course'            => $course,
            'units'             => $units, // Changed from 'structure' to 'units'
            'course_progress'   => $courseProgress,
            'current_id'        => $requestedItemId,
            'video_id'          => $currentItem['item_id'] ?? 'dQw4w9WgXcQ',
            'video_title'       => $currentItem['title'] ?? 'Default Item Title',
            'video_desc'        => $currentItem['description'] ?? 'Default item description',
            'prevLessonUrl'     => $prevItem
                ? site_url('courses/course_view/'.$slug.'?video='.$prevItem['id'])
                : null,
            'nextLessonUrl'     => $nextItem
                ? site_url('courses/course_view/'.$slug.'?video='.$nextItem['id'])
                : null,
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

        // 3) Mark the item as complete (TODO: Implement item completion tracking)
        // For now, we'll use the existing method but this should be updated for units system
        // $this->coursesModel->markLessonComplete($enrollment->id, $itemId);

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

        // Locate the current item index
        $currentIndex = array_search($itemId, array_column($flatItems, 'id'));

        if ($currentIndex !== false) {
            $nextIndex = $currentIndex + 1;
            // 5) If next item exists, redirect there
            if (isset($flatItems[$nextIndex])) {
                $nextItemId = $flatItems[$nextIndex]['id'];
                return redirect()->to(site_url('courses/course_view/'.$slug.'?video='.$nextItemId))
                    ->with('success', 'Item marked as complete! Moving to next item...');
            }
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
            // For now, set progress to 0 until we implement unit-based progress tracking
            if ($enrollment) {
                // $progress = $this->coursesModel->calculateProgress($courseObj, $enrollment);
                $progress = 0; // Placeholder until units progress is implemented
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

        // Get user's current credits
        $userCredits = $this->creditTransactionsModel->getUserCredits($userId);

        if ($userCredits < $course->price) {
            return redirect()->back()->with('error', 'Insufficient credits. You need ' . $course->price . ' credits but only have ' . $userCredits . '.');
        }

        // Deduct credits and enroll
        $this->creditTransactionsModel->recordTransaction([
            'user_id' => $userId,
            'type' => 'debit',
            'amount' => $course->price,
            'description' => 'Course purchase: ' . $course->course_title,
            'reference_type' => 'course_purchase',
            'reference_id' => $courseId
        ]);

        $this->coursesModel->enrollUser($userId, $courseId);

        return redirect()->to('/courses/my_courses')->with('success', 'Course purchased successfully!');
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
