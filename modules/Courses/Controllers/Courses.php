<?php
namespace Modules\Courses\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use Modules\Courses\Models\CoursesModel;

class Courses extends BaseController
{
    protected CoursesModel $coursesModel;

    public function __construct()
    {
        $this->coursesModel = new CoursesModel();
        helper(['text', 'url']); // Load needed helpers
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
            $userCourses = $this->db
                ->table('tb_enrollments')  // <-- use the correct table name here
                ->select('course_id')
                ->where('user_id', $userId)
                ->get()
                ->getResultArray();

            // Extract course_ids into a simple array
            $enrolledCourseIds = array_column($userCourses, 'course_id');
        }

        // Pre-process each course
        foreach ($data['courses'] as &$course) {
            // Provide a fallback if short_desc doesn't exist
            $course['short_desc'] = $course['short_desc'] ?? '';

            // Count lessons from JSON structure
            $lessonCount = 0;
            if (!empty($course['course_structure'])) {
                $structure = json_decode($course['course_structure'], true);
                if (is_array($structure)) {
                    foreach ($structure as $section) {
                        if (!empty($section['videos']) && is_array($section['videos'])) {
                            $lessonCount += count($section['videos']);
                        }
                    }
                }
            }
            $course['lesson_count'] = $lessonCount;

            // Mark if user is enrolled
            $course['is_enrolled'] = in_array($course['id'], $enrolledCourseIds);
        }
        unset($course); // Good practice after reference loops

        $data['title']  = 'My Courses' ;
        $data['desc']  = 'All courses you are enrolled in' ;
        // Provide a unified data array if needed
        $data['data'] = $data;
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

        // 2) Decode JSON structure (array of sections)
        $structure = json_decode($course->course_structure ?? '[]', true);

        // 3) Check if user is enrolled
        //    If user is logged in, check enrollment in the DB
        //    e.g. $this->coursesModel->isUserEnrolled($userId, $course->id)
        $userId      = auth()->loggedIn() ? auth()->user()->id : null;
        $isEnrolled  = false;
        if ($userId) {
            $isEnrolled = $this->coursesModel->isUserEnrolled($userId, $course->id);
        }

        // 4) Prepare data for the view
        $data = [
            'title'      => $course->course_name,
            'course'     => $course,
            'structure'  => $structure,
            'isEnrolled' => $isEnrolled,
        ];

        // 5) Render the updated "course_details" (or any view name you use)
        return view('site/course_details', $data);
    }

    /**
     * Display the "course player" page with sections, videos, next/prev logic, etc.
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
            // If your site allows free preview without login, skip this check. Otherwise:
            return redirect()->to('site/login')->with('error', 'Please log in first.');
        }

        // Retrieve the enrollment
        $enrollment = $this->coursesModel->getEnrollment($userId, $course->id);
        if (!$enrollment) {
            return redirect()->to('/courses')->with('error', 'You are not enrolled in this course.');
        }

        // 3) Decode structure & flatten
        $structureArray    = json_decode($course->course_structure ?? '[]', true);
        $preparedStructure = $this->coursesModel->prepareDynamicStructure($structureArray);
        $flatLessons       = $this->coursesModel->flattenLessons($preparedStructure);

        // 4) Check which video is requested in ?video=XYZ
        $requestedLessonId = $this->request->getGet('video');

        // 5) If no specific video is requested, jump to the first incomplete
        if (!$requestedLessonId && !empty($flatLessons)) {
            // Get all completed videos for this enrollment
            $completedIDs = $this->coursesModel->getCompletedLessonIDs($enrollment->id);

            // Attempt to find the first uncompleted video
            $requestedLessonId = $this->coursesModel->findNextIncompleteLesson($flatLessons, $completedIDs);
        }

        // 6) Find the current video in $flatLessons
        $currentIndex = array_search($requestedLessonId, array_column($flatLessons, 'id'));
        $currentLesson = ($currentIndex !== false) ? $flatLessons[$currentIndex] : null;

        // 7) Determine next & prev videos
        $prevLesson = ($currentIndex > 0)
            ? $flatLessons[$currentIndex - 1]
            : null;

        $nextLesson = ($currentIndex !== false && $currentIndex < count($flatLessons) - 1)
            ? $flatLessons[$currentIndex + 1]
            : null;

        // 8) Mark correct section open & the correct video active
        if ($currentLesson) {
            $sectionId = $currentLesson['section_index'];
            foreach ($preparedStructure as &$section) {
                if ($section['section_id'] == $sectionId) {
                    $section['is_open'] = true;
                    foreach ($section['videos'] as &$video) {
                        if ($video['id'] == $requestedLessonId) {
                            $video['is_active'] = true;
                        }
                    }
                }
            }
            unset($section);
        } else {
            // If we can't find the current video, open the first section by default
            if (!empty($preparedStructure)) {
                $preparedStructure[0]['is_open'] = true;
                if (!empty($preparedStructure[0]['videos'])) {
                    $preparedStructure[0]['videos'][0]['is_active'] = true;
                }
            }
        }

        // 9) Calculate real progress from DB
        $courseProgress = $this->coursesModel->calculateProgress($course, $enrollment);

        // 10) Prepare data for the view
        $data = [
            'title'             => $course->course_name,
            'course'            => $course,
            'structure'         => $preparedStructure,
            'course_progress'   => $courseProgress,
            'current_id' => $requestedLessonId,
            'video_id'         => $currentLesson['video_id']  ?? 'dQw4w9WgXcQ',
            'video_title'      => $currentLesson['video_title'] ?? 'Default Lesson Title',
            'video_desc'       => $currentLesson['video_desc']  ?? 'Default video description',
            'prevLessonUrl' => $prevLesson
                ? site_url('courses/course_view/'.$slug.'?video='.$prevLesson['id'])
                : null,
            'nextLessonUrl' => $nextLesson
                ? site_url('courses/course_view/'.$slug.'?video='.$nextLesson['id'])
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
            return redirect()->to('site/login')->with('error', 'Please log in first.');
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
     * Mark a video as complete for the current user in the given course (via slug).
     */
    public function markLessonComplete(): RedirectResponse
    {
        $userId = auth()->user()->id;
        if (!$userId) {
            return redirect()->to('site/login')->with('error', 'Please log in first.');
        }

        $videoId = (int) $this->request->getPost('id');
        $slug     = $this->request->getPost('slug'); // from hidden input in form

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

        // 3) Mark the video as complete
        $this->coursesModel->markLessonComplete($enrollment->id, $videoId);

        // 4) Find the next video ID (if any) in the course structure
        $structureArray    = json_decode($course->course_structure ?? '[]', true);
        $preparedStructure = $this->coursesModel->prepareDynamicStructure($structureArray); // same method used in course_view()
        $flatLessons       = $this->coursesModel->flattenLessons($preparedStructure);       // flatten for next/prev logic

        // Locate the current video index
        $currentIndex = array_search($videoId, array_column($flatLessons, 'id'));

        if ($currentIndex !== false) {
            $nextIndex = $currentIndex + 1;
            // 5) If next video exists, redirect there
            if (isset($flatLessons[$nextIndex])) {
                $nextLessonId = $flatLessons[$nextIndex]['id'];
                return redirect()->to(site_url('courses/course_view/'.$slug.'?video='.$nextLessonId))
                    ->with('success', 'Lesson marked as complete! Moving to next video...');
            }
        }

        // 6) If no next video, just redirect back or to the course_view
        return redirect()->back()->with('success', 'Lesson marked as complete! No more videos.');
    }


    /**
     * Show all courses the user is enrolled in, plus dynamic progress.
     */
    public function my_courses()
    {
        $userId = auth()->user()->id;

        if (!$userId) {
            return redirect()->to('site/login')->with('error', 'Please log in first.');
        }

        $myCourses = $this->coursesModel->getAllUserCourses($userId);
        //echo $this->db->getLastQuery();exit;
        $enrolledCourses = [];

        foreach ($myCourses as $courseObj) {
            // find enrollment
            $enrollment = $this->coursesModel->getEnrollment($userId, $courseObj->id);
            $progress   = 0;
            if ($enrollment) {
                $progress = $this->coursesModel->calculateProgress($courseObj, $enrollment);
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


}
