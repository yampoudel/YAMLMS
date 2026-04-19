<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnrolmentRequest;
use App\Models\Course;
use App\Models\Enrolment;
use App\Models\User;
use App\Services\EnrolmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class EnrolmentController extends Controller
{
    // Injecting Enrolment Service
    public function __construct(protected EnrolmentService $enrolmentService) {}

    /**
     * Display a listing of the enrolments.
     */
    public function index(): View|RedirectResponse
    {
        if (Gate::denies('viewAny', Enrolment::class)) {
            return redirect()->route('users.index')
                ->with('error', 'You are not authorized to view enrolments.');
        }

        // Get all enrolments
        $enrolments = $this->enrolmentService->getEnrolmentList(15);

        return view('admin.enrolment.index', compact('enrolments'));
    }

    /**
     * Show the form for creating a new resource/enrolment.
     */
    public function create(User $user): View|RedirectResponse
    {
        // Adding page information for create page
        $page_info = [
            'title' => 'Enrol User',
            'back_button' => 'Back To Users',
        ];

        if (Gate::denies('create', [Enrolment::class, $user])) {
            return redirect()->route('users.index')
                ->with('error', 'You are not authorized to enrol this user.');
        }

        // Role based course filtering
        $courses = auth()->user()->isAdmin()
            ? Course::All()
            : Course::where('created_by', auth()->id())->get();

        return view('admin.enrolment.create', compact(['user', 'courses', 'page_info']));
    }

    /**
     * Store a newly created resource/enrolment in storage.
     */
    public function store(StoreEnrolmentRequest $request, User $user): RedirectResponse
    {
        if (Gate::denies('create', [Enrolment::class, $user])) {
            return redirect()->route('users.index')
                ->with('error', 'You are not authorized to enrol this user.');
        }

        // Data is already validated
        $this->enrolmentService->enrollUser($user->id, $request->course_id, auth()->user()->id);

        return redirect()->route('enrolments.index', $user)
            ->with('success', 'User has been enrolled to this course successfully');
    }

    /**
     * Remove the specified resource/enrolment from storage.
     */
    public function destroy(Enrolment $enrolment): RedirectResponse
    {
        if (Gate::denies('delete', $enrolment)) {
            return redirect()->route('enrolments.index')
                ->with('error', 'You are not authorized to delete this enrolment.');
        }

        try {
            $this->enrolmentService->deleteEnrolment($enrolment);

            return redirect()->route('enrolments.index')
                ->with('success', 'Enrolment has been deleted successfully.');
        } catch (\Exception $e) {
            // Log the error so you can check it in storage/logs/laravel.log
            return redirect()->route('enrolments.index')
                ->with('error', 'You are not authorized to delete this enrolment.');
        }
    }
}
