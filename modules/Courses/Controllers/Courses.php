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
        helper(['text', 'url']); // 'url' for site_url() calls if needed
    }

    public function index(): string
    {
        // Example index method...
        $data = [
            'title'   => lang('Courses.Courses'),
            'courses' => $this->coursesModel
                ->where('active', 1)
                ->paginate(10),
            'pager'   => $this->coursesModel->pager,
        ];
        return view('site/index', $data);
    }

    public function course_details($slug): string
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
     * Displays the course player view with sections, lessons, and Next/Prev logic.
     */
    public function course_view($slug): string
    {
        // 1) Fetch the course by slug
        $course = $this->coursesModel->getCourseBySlug($slug);
        if (!$course) {
            throw PageNotFoundException::forPageNotFound();
        }

        // 2) Decode the JSON structure, then prepare it
        $structure = json_decode($course->course_structure ?? '[]', true);
        $preparedStructure = $this->prepareDynamicStructure($structure);

        // 3) Determine which lesson is requested via ?lesson=XYZ
        $requestedLessonId = $this->request->getGet('lesson');

        // Flatten lessons for easy next/prev logic
        $flatLessons = $this->flattenLessons($preparedStructure);

        // If no specific lesson is requested, default to the first lesson
        if (!$requestedLessonId && !empty($flatLessons)) {
            $requestedLessonId = $flatLessons[0]['lesson_id'];
        }

        // 4) Find current lesson, plus next & previous
        $currentLessonIndex = array_search($requestedLessonId, array_column($flatLessons, 'lesson_id'));
        $currentLesson      = ($currentLessonIndex !== false) ? $flatLessons[$currentLessonIndex] : null;

        $prevLesson = ($currentLessonIndex > 0)
            ? $flatLessons[$currentLessonIndex - 1]
            : null;

        $nextLesson = ($currentLessonIndex !== false && $currentLessonIndex < count($flatLessons) - 1)
            ? $flatLessons[$currentLessonIndex + 1]
            : null;

        // 5) Mark the correct section open & the correct lesson active
        if ($currentLesson) {
            $currentSectionId = $currentLesson['section_index']; // stored in prepareDynamicStructure()

            // Loop through sections; open the one containing the current lesson
            foreach ($preparedStructure as &$section) {
                if ($section['section_id'] == $currentSectionId) {
                    $section['is_open'] = true;
                    // Within that section, mark the matching lesson as active
                    foreach ($section['lessons'] as &$lesson) {
                        if ($lesson['lesson_id'] == $requestedLessonId) {
                            $lesson['is_active'] = true;
                        }
                    }
                }
            }
            unset($section);
        } else {
            // If we didn't find the requested lesson, open the very first section by default
            if (!empty($preparedStructure)) {
                $preparedStructure[0]['is_open'] = true;
                if (!empty($preparedStructure[0]['lessons'])) {
                    $preparedStructure[0]['lessons'][0]['is_active'] = true;
                }
            }
        }

        // 6) Prepare the data array for the view
        $data = [
            'title'              => $course->course_name,
            'course'             => $course,
            'structure'          => $preparedStructure,
            'course_progress'    => 25, // Example static progress
            'current_lesson_id'  => $requestedLessonId,
            'video_url'          => $currentLesson['lesson_link'] ?? 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'lesson_title'       => $currentLesson['lesson_title'] ?? 'Default Lesson Title',
            'course_description' => $course->course_desc ?? 'Course description goes here.',
            'prevLessonUrl'      => $prevLesson
                ? site_url('courses/course_view/'.$slug.'?lesson='.$prevLesson['lesson_id'])
                : null,
            'nextLessonUrl'      => $nextLesson
                ? site_url('courses/course_view/'.$slug.'?lesson='.$nextLesson['lesson_id'])
                : null,
        ];

        return view('site/course_view', $data);
    }

    /**
     * Helper to build the sections array with section_id, lessons, etc.
     * Also stores a "section_index" in each lesson so we know which section it belongs to.
     */
    private function prepareDynamicStructure(array $structureData): array
    {
        $dynamicStructure = [];
        $sectionCounter   = 1;

        foreach ($structureData as $sectionData) {
            $section = [
                'section_id'    => $sectionCounter,
                'section_title' => $sectionData['section_title'] ?? 'Section Title',
                'is_open'       => false, // We'll set this to true if the user is on a lesson in this section
                'lessons'       => [],
            ];

            if (!empty($sectionData['lessons']) && is_array($sectionData['lessons'])) {
                $lessonCounter = 1;
                foreach ($sectionData['lessons'] as $lessonData) {
                    $lesson = [
                        'lesson_id'      => $lessonData['lesson_id'] ?? $lessonCounter,
                        'lesson_title'   => $lessonData['lesson_title'] ?? 'Lesson Title',
                        'video_duration' => $lessonData['video_duration'] ?? '0:00',
                        'lesson_link'    => $lessonData['lesson_link'] ?? '#',
                        'is_preview'     => !empty($lessonData['is_preview']),
                        'is_active'      => false,
                        // Store section_index so we can expand the correct section
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
     * Flatten the entire lesson list from all sections
     * so we can easily find next/prev by array index.
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
     * Example: Mark a lesson as complete in the database (placeholder).
     */
    public function markLessonComplete(): RedirectResponse
    {
        $lessonId = $this->request->getPost('lesson_id');
        $userId   = session()->get('user_id');

        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Please log in first.');
        }

        // TODO: Save to DB that user has completed lesson $lessonId
        // e.g. $this->coursesModel->markLessonAsComplete($userId, $lessonId);

        return redirect()->back()->with('success', 'Lesson marked as complete!');
    }

    public function my_courses(): String|RedirectResponse
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $myCourses = $this->coursesModel->getAllUserCourses($userId);

        $data = [
            'title'     => 'My Courses',
            'desc'      => 'All courses you are enrolled in',
            'myCourses' => $myCourses ?? [],
        ];

        return view('site/my_courses', $data);
    }
}
