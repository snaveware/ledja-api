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
        foreach($applications as $application)
        {
            $application->jobseeker_basic_info = $application->user->basic_info_jobseeker;
        }
        return $this->sendResponse($applications, "Applications Fetched Successfully");
    }

    public function recruiter_applications(Request $request, string $job_id)
    {
        $request->status = $request->status == null ? $request->merge(['status' => '']) :  $request->status;
        $input = $request->all();
        $applications = Application::with(['job', 'user'])->status($input['status'])->where('job_id', $job_id)->get();
        $job_apps = [];
        $job_apps_with_names = [];
        // dd($applications);
        // return $this->sendResponse($applications, "Recruiter Applications Fetched");


        foreach($applications as $application)
        {
            // Check if there is a fname and lname filter
            if ($request->fname != null || $request->lname != null)
            {
                // dd($application->user->basic_info_jobseeker->fname);
                if($request->fname == $application->user->basic_info_jobseeker->fname || $request->lname == $application->user->basic_info_jobseeker->lname )
                {
                    array_push($job_apps_with_names, $application);
                }

                else
                {
                    // array_push($job_apps_with_names, "");
                    continue;
                }
            }
           
        }

        if ($request->fname != null || $request->lname != null)
        {
            $job_apps = $job_apps_with_names;
            return $this->sendResponse($job_apps, "Recruiter Applications Fetched");
        }


        if(count($job_apps) == 0)
        {
            $job_apps = $applications;
        }

        return $this->sendResponse($job_apps, "Recruiter Applications Fetched");

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
            'status' => 'nullable',
            'cover_letter' => 'required',
            'skills_assessment' => 'nullable',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        // dd(is_null($request->status));
        
        if(is_null($request->status))
        {
            $request->status = 'awaiting';
        }

        $input = $request->all();
        $input['status'] = $request->status;

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
        $application->jobseeker_basic_info = $application->user->basic_info_jobseeker;

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
