<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLessonRequest;
use App\Http\Requests\UpdateLessonRequest;
use App\Models\Course;
use App\Models\Lesson;
use App\Services\EmailService;
use App\Services\LessonService;
use App\Services\ProgressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class LessonController extends Controller
{
    /**
     * Inject Lessonservice and ProgressService
     */
    public function __construct(
        protected LessonService $lessonService,
        protected ProgressService $progressService,
        protected EmailService $emailService
    ) {}

    /**
     * Display a listing of the lesson.
     */
    public function index(Request $request): View|RedirectResponse
    {
        // Check policy
        $this->authorize('viewAny', Lesson::class);

        // Get list of lessons by passing the limit and only the requested search filters
        $lessons = $this->lessonService->getLessonList(15, $request->only(['title', 'status']));

        return view('admin.lesson.index', compact('lessons'));
    }

    /**
     * Show the form for creating a new lesson.
     */
    public function create(Request $request): View|RedirectResponse
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

        return view('admin.lesson.create', compact('courses', 'page_info', 'selected_course_id'));
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
    public function edit(Lesson $lesson): View
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

        return view('admin.lesson.edit', compact('lesson', 'courses', 'page_info'));
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
     *
     * @param  Lesson  $lessons
     */
    public function play(Course $course, ?Lesson $lesson = null): View
    {
        $user = auth()->user();

        // This forces a clean, fresh query to the lms_lessons_completed table
        $user->load('completedLessons');

        // If no specific lesson requested get first by position
        $current_lesson = $lesson ?? $course->lessons()->orderBy('position', 'asc')->first();

        // Get list of lessons for sidebars
        $lessons = $course->lessons()->orderBy('position', 'asc')->get();

        return view('learner.course-player', compact('course', 'current_lesson', 'lessons'));
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
        // Mark current lesson as complete
        $this->progressService->completeLesson(auth()->user(), $course, $lesson);

        // Find the next lesson by position
        $next_lesson = $course->lessons()
            ->where('position', '>', $lesson->position)
            ->orderBy('position', 'asc')
            ->first();

        // Redirect to next lesson if it exists
        if ($next_lesson) {
            return redirect()->route('lessons.play', [$course, $next_lesson])
                ->with('success', 'Nice job! On to the next lesson.');
        }

        // If no next lesson, course is finished
        // Send course completed email to the learner
        $this->emailService->sendCourseCompletedEmail(auth()->user(), $course);

        return redirect()->route('dashboard')
            ->with('success', 'Congratulations! Course has been completed: '.$course->title);
    }
}
