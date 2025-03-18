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
        $preparedStructure = $this->prepareDynamicStructure($structureArray);
        $flatLessons       = $this->flattenLessons($preparedStructure);

        // 4) Check which video is requested in ?video=XYZ
        $requestedLessonId = $this->request->getGet('video');

        // 5) If no specific video is requested, jump to the first incomplete
        if (!$requestedLessonId && !empty($flatLessons)) {
            // Get all completed videos for this enrollment
            $completedIDs = $this->coursesModel->getCompletedLessonIDs($enrollment->id);

            // Attempt to find the first uncompleted video
            $requestedLessonId = $this->findNextIncompleteLesson($flatLessons, $completedIDs);
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
        $courseProgress = $this->calculateProgress($course, $enrollment);

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
        $preparedStructure = $this->prepareDynamicStructure($structureArray); // same method used in course_view()
        $flatLessons       = $this->flattenLessons($preparedStructure);       // flatten for next/prev logic

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
     * Flatten the entire video list from the structure to find next/prev easily.
     */
    private function flattenLessons(array $preparedStructure): array
    {
        $allLessons = [];
        foreach ($preparedStructure as $section) {
            foreach ($section['videos'] as $video) {
                $allLessons[] = $video;
            }
        }
        return $allLessons;
    }

    /**
     * Build an array of sections/videos with IDs, titles, etc.
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
                'videos'       => [],
            ];

            if (!empty($sectionData['videos']) && is_array($sectionData['videos'])) {
                $videoCounter = 1;
                foreach ($sectionData['videos'] as $videoData) {
                    $video = [
                        'id'      => $videoData['id'] ?? $videoCounter,
                        'video_title'   => $videoData['video_title'] ?? 'Lesson Title',
                        'video_desc'    => $videoData['video_desc']  ?? 'No description provided.',
                        'video_id'    => $videoData['video_id']  ?? '#',
                        'video_duration' => $videoData['video_duration'] ?? '0:00',
                        'is_preview'     => !empty($videoData['is_preview']),
                        'is_active'      => false,
                        'section_index'  => $sectionCounter,
                    ];
                    $section['videos'][] = $video;
                    $videoCounter++;
                }
            }

            $dynamicStructure[] = $section;
            $sectionCounter++;
        }

        return $dynamicStructure;
    }

    /**
     * Example: compute user's progress in a course (videos completed / total).
     */
    private function calculateProgress(object $course, object $enrollment): int
    {
        // decode structure as array
        $structure = json_decode($course->course_structure ?? '[]', true);
        if (!$structure) {
            return 0;
        }

        // total videos
        $totalLessons = 0;
        foreach ($structure as $section) {
            if (!empty($section['videos'])) {
                $totalLessons += count($section['videos']);
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
     * Finds the first video that is NOT in $completedIDs.
     * If all are completed, returns the ID of the LAST video.
     ****/
    private function findNextIncompleteLesson(array $flatLessons, array $completedIDs): int
    {
        foreach ($flatLessons as $video) {
            if (! in_array($video['id'], $completedIDs)) {
                // Return the first uncompleted video
                return $video['id'];
            }
        }
        // If everything is completed, return the last video's ID
        return end($flatLessons)['id'];
    }

}
