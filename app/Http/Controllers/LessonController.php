<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLessonRequest;
use App\Models\Course;
use App\Models\Lesson;
use App\Services\LessonService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class LessonController extends Controller
{
    /**
     * Inject Lesson Service
     */
    public function __construct(protected LessonService $lessonService) {}

    /**
     * Display a listing of the lesson.
     */
    public function index(): View
    {
        // Get list of lessons
        $lessons = $this->lessonService->getLessonList(15);

        return view('admin.lesson.index', compact('lessons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View|RedirectResponse
    {
        // Check policy
        if (Gate::denies('create', Lesson::class)) {
            return redirect()->route('lessons.index')
                ->with('error', 'You are not authorized to create lesson.');
        }

        // Adding page information
        $page_info = [
            'title' => 'Add Lesson',
            'back_button' => 'Back To Lessons',
        ];

        // Get courses for this user
        $user = auth()->user();

        $courses = $user->isAdmin() ? Course::all() :
            Course::where('created_by', $user->id)->get();

        return view('admin.lesson.create', compact('courses', 'page_info'));
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show(Lesson $lesson)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lesson $lesson)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lesson $lesson)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson)
    {
        //
    }
}
