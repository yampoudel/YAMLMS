<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLessonRequest;
use App\Http\Requests\UpdateLessonRequest;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonCompleted;
use App\Services\EmailService;
use App\Services\LessonService;
use App\Services\ProgressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use inertia\Response as InertiaResponse;

class LessonController extends Controller
{
    /**
     * Inject Lessonservice , ProgressService and EmailService
     */
    public function __construct(
        protected LessonService $lessonService,
        protected ProgressService $progressService,
        protected EmailService $emailService
    ) {}

    /**
     * Display a listing of the lesson.
     */
    public function index(Request $request): RedirectResponse|InertiaResponse
    {
        // Check policy
        $this->authorize('viewAny', Lesson::class);

        // Get list of lessons by passing the limit and only the requested search filters
        $lessons = $this->lessonService->getLessonList(15, $request->only(['title', 'status']));

        return Inertia::render('Admin/Lesson/Index', [
            'lessons' => $lessons,
            'filters' => $request->only(['title', 'status']),
        ]);
    }

    /**
     * Show the form for creating a new lesson.
     */
    public function create(Request $request): RedirectResponse|InertiaResponse
    {
        // Check policy
        if (Gate::denies('create', Lesson::class)) {
            return redirect()->route('lessons.index')
                ->with('error', 'You are not authorized to create lesson.');
        }

        // Get the course_id from url if selected
        $selected_course_id = $request->query('course_id');

        // Adding page information for create
        $page_info = [
            'title' => 'Add Lesson',
            'back_button' => 'Back To Lessons',
        ];

        $user = auth()->user();

        // Get courses for this user
        $courses = $user->isAdmin() ? Course::all() :
            Course::where('created_by', $user->id)->get();

        return Inertia::render('Admin/Lesson/Create', [
            'courses' => $courses,
            'page_info' => $page_info,
            'selected_course_id' => $selected_course_id,
            'button_label' => __('buttons.lessons.create'),
        ]);
    }

    /**
     * Store a newly created lesson in storage.
     */
    public function store(StoreLessonRequest $request): RedirectResponse
    {
        // Check policy
        if (Gate::denies('create', Lesson::class)) {
            return redirect()->route('lessons.index')
                ->with('error', 'You are not authorized to create lesson.');
        }

        // Data is already validated
        $this->lessonService->storeLesson($request->validated());

        return redirect()->route('lessons.index')
            ->with('success', 'Lesson has been created successfully.');
    }

    /**
     * Show the form for editing the specified lesson.
     */
    public function edit(Lesson $lesson): RedirectResponse|InertiaResponse
    {
        // Check policy
        if (Gate::denies('update', $lesson)) {
            return redirect()->route('lessons.index')
                ->with('error', 'You are not authorized to edit this lesson.');
        }

        // Get page information for edit page
        $page_info = [
            'title' => 'Edit Lesson',
            'back_button' => 'Go To Lessons',
        ];

        $user = auth()->user();

        // Get the courses assigned to this users
        $courses = $user->isAdmin() ? Course::all() :
            Course::where('created_by', $user->id)->get();

        return Inertia::render('Admin/Lesson/Edit', [
            'lesson' => $lesson,
            'courses' => $courses,
            'page_info' => $page_info,
            'button_label' => __('buttons.lessons.edit'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLessonRequest $request, Lesson $lesson): RedirectResponse
    {
        // Check policy
        if (Gate::denies('update', $lesson)) {
            return redirect()->route('lessons.index')
                ->with('error', 'You are not authorized to edit this lesson.');
        }

        $this->lessonService->updateLesson($lesson, $request->validated());

        return redirect()->route('lessons.index')
            ->with('success', 'Lessson has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson): RedirectResponse
    {
        // Check policy
        if (Gate::denies('delete', $lesson)) {
            return redirect()->route('lessons.index')
                ->with('error', 'You are not authorized to delete this lesson.');
        }

        // Delete lesson
        $this->lessonService->delete($lesson);

        return redirect()->route('lessons.index')
            ->with('success', 'Lessson has been deleted successfully');
    }

    /**
     * Show course player
     */
    public function play(Course $course, ?Lesson $lesson = null): InertiaResponse
    {
        $user = auth()->user();

        // Fetch the list of completed lesson IDs for this user and course
        $completedIds = LessonCompleted::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->pluck('lesson_id')
            ->map(fn ($id) => (int) $id)
            ->toArray();

        // Ensure that the current lesson is set to the first lesson if none is provided, and that it is marked as completed if it exists in the completed IDs
        $current_lesson = $lesson ?? $course->lessons()->orderBy('position', 'asc')->first();

        if ($current_lesson) {
            $current_lesson->completed_ids = $completedIds;
        }

        // Calculate the course progress percentage for this user
        $totalLessonsCount = $course->lessons()->count();
        $course->progress_percentage = $totalLessonsCount > 0
            ? (int) round((count($completedIds) / $totalLessonsCount) * 100)
            : 0;

        // Fetch all lessons for this course in order and mark them as completed based on the completed IDs
        $lessons = $course->lessons()->orderBy('position', 'asc')->get()->map(function ($l) use ($completedIds) {
            $l->is_completed = in_array((int) $l->id, $completedIds, true);

            return $l;
        });

        return Inertia::render('Learner/CoursePlayer', [
            'course' => $course,
            'current_lesson' => $current_lesson,
            'lessons' => $lessons,
        ]);
    }

    /**
     * Initiate course progress and redirect to first lesson
     */
    public function start(Course $course): RedirectResponse
    {
        $this->progressService->startCourse(auth()->user(), $course);

        // Redirect to play routes
        return redirect()->route('lessons.play', $course);
    }

    /**
     * Lesson completion
     */
    public function complete(Course $course, Lesson $lesson): RedirectResponse
    {
        $user = auth()->user();

        // Mark the lesson as completed for the user and course
        $this->progressService->completeLesson($user, $course, $lesson);

        // Fetch all lessons for this course in order to determine the next lesson
        $orderedLessons = $course->lessons()
            ->orderBy('position', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // Determine the index of the current lesson in the ordered list to find the next lesson
        $currentIndex = $orderedLessons->search(function ($item) use ($lesson) {
            return (int) $item->id === (int) $lesson->id;
        });

        // Determine the next lesson in the sequence, if it exists
        $next_lesson = ($currentIndex !== false && isset($orderedLessons[$currentIndex + 1]))
            ? $orderedLessons[$currentIndex + 1]
            : null;

        // If a next lesson exists, redirect to it with a success message; otherwise, send completion email and redirect to dashboard
        if ($next_lesson) {
            return to_route('lessons.play', ['course' => $course->id, 'lesson' => $next_lesson->id])
                ->with('success', 'Nice job! On to the next lesson.');
        }

        // Send completion email and redirect to the dashboard when the path is fully cleared
        $this->emailService->sendCourseCompletedEmail($user, $course);

        return to_route('dashboard')
            ->with('success', 'Congratulations! Course has been completed: '.$course->title);
    }
}
