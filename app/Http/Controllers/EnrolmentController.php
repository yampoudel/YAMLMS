<?php

namespace App\Http\Controllers;

use App\Models\Enrolment;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Services\EnrolmentService;
use App\Http\Requests\StoreEnrolmentRequest;

class EnrolmentController extends Controller
{
    //Injecting Enrolment Service
    public function __construct(protected EnrolmentService $enrolmentService) {}

    /**
     * Display a listing of the enrolments.
     */
    public function index(): View
    {
        //Get all enrolments
        $enrolments = $this->enrolmentService->getEnrolmentList(15);
        
        return view('admin.enrolment.index', compact('enrolments'));
    }

    /**
     * Show the form for creating a new resource/enrolment.
     */
    public function create(User $user): View
    {
        //Display new enrolment adding page
        $page_info = [];
        $page_info['title'] = 'Enrol Course';
        
        $courses = Course::all();

        return view('admin.enrolment.create', compact(['user','courses', 'page_info']));
    }

    /**
     * Store a newly created resource/enrolment in storage.
     */
    public function store(StoreEnrolmentRequest $request, User $user): RedirectResponse
    {
        //Data is already validated
        
       $this->enrolmentService->enrollUser($user->id, $request->course_id, auth()->user()->id);

       return redirect()->route('enrolments.index', $user)->with('success', 'User has been enrolled to this course successfully');
    }

    /**
     * Remove the specified resource/enrolment from storage.
     */
    public function destroy(Enrolment $enrolment): RedirectResponse
    {
        //Delete Enrolment
        $this->enrolmentService->deleteEnrolment($enrolment);

        return redirect()->route('enrolments.index')->with('success', 'Enrolment has been deleted successfully');
    }
}
