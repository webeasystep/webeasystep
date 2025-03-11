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
        $data = [
            'title'   => lang('Courses.Courses'),
            'courses' => $this->coursesModel
                ->where('active', 1)
                ->paginate(10),
            'pager'   => $this->coursesModel->pager,
        ];

        return view('site/index', $data);
    }

    /**
     * Show a single course details page (e.g. 'course_details' view).
     */
    public function course_details(string $slug): string
    {
        $course = $this->coursesModel->getCourseBySlug($slug);
        if (!$course) {
            throw PageNotFoundException::forPageNotFound();
        }

        $structure = json_decode($course->course_structure ?? '[]', true);

        $data = [
            'title'     => $course->course_name,
            'course'    => $course,
            'structure' => $structure,
        ];

        return view('site/course_details', $data);
    }

    /**
     * Display the "course player" page with sections, lessons, next/prev logic, etc.
     */
    public function course_view(string $slug): string
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
            return redirect()->to('/login')->with('error', 'Please log in first.');
        }

        // Retrieve the enrollment
        $enrollment = $this->coursesModel->getEnrollment($userId, $course->id);
        if (!$enrollment) {
            return redirect()->to('/courses/'.$slug)->with('error', 'You are not enrolled in this course.');
        }

        // 3) Decode structure & flatten
        $structureArray    = json_decode($course->course_structure ?? '[]', true);
        $preparedStructure = $this->prepareDynamicStructure($structureArray);
        $flatLessons       = $this->flattenLessons($preparedStructure);

        // 4) Check which lesson is requested in ?lesson=XYZ
        $requestedLessonId = $this->request->getGet('lesson');

        // 5) If no specific lesson is requested, jump to the first incomplete
        if (!$requestedLessonId && !empty($flatLessons)) {
            // Get all completed lessons for this enrollment
            $completedIDs = $this->coursesModel->getCompletedLessonIDs($enrollment->id);

            // Attempt to find the first uncompleted lesson
            $requestedLessonId = $this->findNextIncompleteLesson($flatLessons, $completedIDs);
        }

        // 6) Find the current lesson in $flatLessons
        $currentIndex = array_search($requestedLessonId, array_column($flatLessons, 'lesson_id'));
        $currentLesson = ($currentIndex !== false) ? $flatLessons[$currentIndex] : null;

        // 7) Determine next & prev lessons
        $prevLesson = ($currentIndex > 0)
            ? $flatLessons[$currentIndex - 1]
            : null;

        $nextLesson = ($currentIndex !== false && $currentIndex < count($flatLessons) - 1)
            ? $flatLessons[$currentIndex + 1]
            : null;

        // 8) Mark correct section open & the correct lesson active
        if ($currentLesson) {
            $sectionId = $currentLesson['section_index'];
            foreach ($preparedStructure as &$section) {
                if ($section['section_id'] == $sectionId) {
                    $section['is_open'] = true;
                    foreach ($section['lessons'] as &$lesson) {
                        if ($lesson['lesson_id'] == $requestedLessonId) {
                            $lesson['is_active'] = true;
                        }
                    }
                }
            }
            unset($section);
        } else {
            // If we can't find the current lesson, open the first section by default
            if (!empty($preparedStructure)) {
                $preparedStructure[0]['is_open'] = true;
                if (!empty($preparedStructure[0]['lessons'])) {
                    $preparedStructure[0]['lessons'][0]['is_active'] = true;
                }
            }
        }

        // 9) Calculate real progress from DB
        $courseProgress = $this->calculateProgress($course, $enrollment);

        // 10) Prepare data for the view
        $data = [
            'title'             => $course->course_name,
            'course'            => $course,
            'structure'         => $preparedStructure,
            'course_progress'   => $courseProgress,
            'current_lesson_id' => $requestedLessonId,
            'video_url'         => $currentLesson['lesson_link']  ?? 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'lesson_title'      => $currentLesson['lesson_title'] ?? 'Default Lesson Title',
            'lesson_desc'       => $currentLesson['lesson_desc']  ?? 'Default lesson description',
            'prevLessonUrl' => $prevLesson
                ? site_url('courses/course_view/'.$slug.'?lesson='.$prevLesson['lesson_id'])
                : null,
            'nextLessonUrl' => $nextLesson
                ? site_url('courses/course_view/'.$slug.'?lesson='.$nextLesson['lesson_id'])
                : null,
        ];

        return view('site/course_view', $data);
    }

    /**
     * Enroll the current user in a course (requires user_id from auth).
     */
    public function enroll(int $courseId)
    {
        $userId = auth()->user()->id;
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Please log in first.');
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
     * Mark a lesson as complete for the current user in the given course (via slug).
     */
    public function markLessonComplete()
    {
        $userId = auth()->user()->id;
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Please log in first.');
        }

        $lessonId = (int) $this->request->getPost('lesson_id');
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

        // 3) Mark the lesson as complete
        $this->coursesModel->markLessonComplete($enrollment->id, $lessonId);

        // 4) Find the next lesson ID (if any) in the course structure
        $structureArray    = json_decode($course->course_structure ?? '[]', true);
        $preparedStructure = $this->prepareDynamicStructure($structureArray); // same method used in course_view()
        $flatLessons       = $this->flattenLessons($preparedStructure);       // flatten for next/prev logic

        // Locate the current lesson index
        $currentIndex = array_search($lessonId, array_column($flatLessons, 'lesson_id'));

        if ($currentIndex !== false) {
            $nextIndex = $currentIndex + 1;
            // 5) If next lesson exists, redirect there
            if (isset($flatLessons[$nextIndex])) {
                $nextLessonId = $flatLessons[$nextIndex]['lesson_id'];
                return redirect()->to(site_url('courses/course_view/'.$slug.'?lesson='.$nextLessonId))
                    ->with('success', 'Lesson marked as complete! Moving to next lesson...');
            }
        }

        // 6) If no next lesson, just redirect back or to the course_view
        return redirect()->back()->with('success', 'Lesson marked as complete! No more lessons.');
    }



    /**
     * Show all courses the user is enrolled in, plus dynamic progress.
     */
    public function my_courses()
    {
        $userId = auth()->user()->id;

        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Please log in first.');
        }

        $myCourses = $this->coursesModel->getAllUserCourses($userId);
        //echo $this->db->getLastQuery();exit;
        $enrolledCourses = [];

        foreach ($myCourses as $courseObj) {
            // find enrollment
            $enrollment = $this->coursesModel->getEnrollment($userId, $courseObj->id);
            $progress   = 0;
            if ($enrollment) {
                $progress = $this->calculateProgress($courseObj, $enrollment);
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
     * Flatten the entire lesson list from the structure to find next/prev easily.
     */
    private function flattenLessons(array $preparedStructure): array
    {
        $allLessons = [];
        foreach ($preparedStructure as $section) {
            foreach ($section['lessons'] as $lesson) {
                $allLessons[] = $lesson;
            }
        }
        return $allLessons;
    }

    /**
     * Build an array of sections/lessons with IDs, titles, etc.
     */
    private function prepareDynamicStructure(array $structureData): array
    {
        $dynamicStructure = [];
        $sectionCounter   = 1;

        foreach ($structureData as $sectionData) {
            $section = [
                'section_id'    => $sectionCounter,
                'section_title' => $sectionData['section_title'] ?? 'Section Title',
                'is_open'       => false,
                'lessons'       => [],
            ];

            if (!empty($sectionData['lessons']) && is_array($sectionData['lessons'])) {
                $lessonCounter = 1;
                foreach ($sectionData['lessons'] as $lessonData) {
                    $lesson = [
                        'lesson_id'      => $lessonData['lesson_id'] ?? $lessonCounter,
                        'lesson_title'   => $lessonData['lesson_title'] ?? 'Lesson Title',
                        'lesson_desc'    => $lessonData['lesson_desc']  ?? 'No description provided.',
                        'lesson_link'    => $lessonData['lesson_link']  ?? '#',
                        'video_duration' => $lessonData['video_duration'] ?? '0:00',
                        'is_preview'     => !empty($lessonData['is_preview']),
                        'is_active'      => false,
                        'section_index'  => $sectionCounter,
                    ];
                    $section['lessons'][] = $lesson;
                    $lessonCounter++;
                }
            }

            $dynamicStructure[] = $section;
            $sectionCounter++;
        }

        return $dynamicStructure;
    }

    /**
     * Example: compute user's progress in a course (lessons completed / total).
     */
    private function calculateProgress(object $course, object $enrollment): int
    {
        // decode structure as array
        $structure = json_decode($course->course_structure ?? '[]', true);
        if (!$structure) {
            return 0;
        }

        // total lessons
        $totalLessons = 0;
        foreach ($structure as $section) {
            if (!empty($section['lessons'])) {
                $totalLessons += count($section['lessons']);
            }
        }

        // how many completed
        $completedCount = $this->coursesModel->countCompletedLessons($enrollment->id);

        if ($totalLessons === 0) {
            return 0;
        }
        return (int) round(($completedCount / $totalLessons) * 100);
    }

    /****
     * Finds the first lesson that is NOT in $completedIDs.
     * If all are completed, returns the ID of the LAST lesson.
     ****/
    private function findNextIncompleteLesson(array $flatLessons, array $completedIDs): int
    {
        foreach ($flatLessons as $lesson) {
            if (! in_array($lesson['lesson_id'], $completedIDs)) {
                // Return the first uncompleted lesson
                return $lesson['lesson_id'];
            }
        }
        // If everything is completed, return the last lesson's ID
        return end($flatLessons)['lesson_id'];
    }

}
