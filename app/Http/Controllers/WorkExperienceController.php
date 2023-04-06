<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\WorkExperience;
use Validator;
use App\Http\Controllers\API\BaseController as BaseController;


class WorkExperienceController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $work_experiences = WorkExperience::with(['user'])->paginate();
        return $this->sendResponse($work_experiences, "WorkExperiences Fetched Successfully");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        //
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate fields
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'title' => 'required',
            'company' => 'required',
            // 'duration' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'description' => 'nullable',
            'tasks' => 'nullable',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        if($request->start_date > $request->end_date)
        {
            return $this->sendResponse([], "Your start date cannot be later than your end date!" );
        }

        $input = $request->all();
        $work_experience = WorkExperience::create($input);

        return $this->sendResponse($work_experience, "WorkExperience Created Successfully" );

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $work_experience = WorkExperience::with(['user'])->find($id);

        return $this->sendResponse($work_experience, "WorkExperience Found Successfully" );


    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $work_experience = WorkExperience::find($id);

        if($request->start_date > $request->end_date)
        {
            return $this->sendResponse([], "Your start date cannot be later than your end date!" );
        }

        $input = $request->all();
        $result = $work_experience->update($input);

        return $this->sendResponse($work_experience, "WorkExperience Updated Successfully" );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $work_experience = WorkExperience::findorFail($id);
/* 
        if ($work_experience->has('user'))
        {
            $result = [];
            $message = "Cannot delete WorkExperience,it contains users";
        }

        else 
        {
            $result = [];
            $message = "WorkExperience Deleted Successfully";
            $work_experience->delete();

        } */

        $message = "WorkExperience Deleted Successfully";
        $work_experience->delete();



        return $this->sendResponse([], $message );

    }
}
