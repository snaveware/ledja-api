<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Application;
use Validator;
use App\Http\Controllers\API\BaseController as BaseController;


class ApplicationController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $applications = Application::with(['job', 'user'])->paginate();
        return $this->sendResponse($applications, "Applications Fetched Successfully");
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
            'job_id' => 'required',
            'user_id' => 'required',
            'status' => 'required',
            'cover_letter' => 'required',
            // 'skills_assessment' => 'nullable',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();
        // $path = $request->file('skills_assessment')->storeAs(
        //     'completed_skills_assessment', $request->user()->id
        // );
        // $input['skills_assessment'] = $path;
        $application = Application::create($input);

        return $this->sendResponse($application, "Application Created Successfully" );



    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $application = Application::with(['job','user'])->find($id);

        return $this->sendResponse($application, "Application Found Successfully" );


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
        $application = Application::find($id);

        $input = $request->all();
        $result = $application->update($input);

        return $this->sendResponse($application, "Application Updated Successfully" );



    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $application = Application::find($id);

        if ($application->has('user'))
        {
            $result = [];
            $message = "Cannot delete Application,it contains users";
        }

        else 
        {
            $result = [];
            $message = "Application Deleted Successfully";
            $application->delete();

        }


        return $this->sendResponse([], $message );

    }
}
