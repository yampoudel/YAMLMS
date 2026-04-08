<?php

namespace App\Http\Controllers;

use App\Models\Enrolment;
use Illuminate\Http\Request;

class EnrolmentController extends Controller
{
    /**
     * Display a listing of the enrolments.
     */
    public function index()
    {
        //Get all enrolments
       
        $enrolments= Enrolment::with('user')->get();
        dd($enrolments);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Enrolment $enrolment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Enrolment $enrolment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Enrolment $enrolment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Enrolment $enrolment)
    {
        //
    }
}
