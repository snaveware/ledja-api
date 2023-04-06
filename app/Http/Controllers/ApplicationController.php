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
        // dd($applications);
        $job_apps = [];
        $job_apps_with_names = [];
        

        foreach($applications as $application)
        {
            // Check if there is a fname and lname filter
            if ($request->name != null)
            {
                // dd($application->user->basic_info_jobseeker->fname);
                // if($request->fname == $application->user->basic_info_jobseeker->fname || $request->lname == $application->user->basic_info_jobseeker->lname )
                // dd(str_contains($application->user->basic_info_jobseeker->fname, $request->fname ));
                if(str_contains($application->user->basic_info_jobseeker->fname, $request->name )  )
                {
                    $application->jobseeker_basic_info = $application->user->basic_info_jobseeker;
                    array_push($job_apps_with_names, $application);
                }

                else if(str_contains($application->user->basic_info_jobseeker->lname, $request->name )  )
                {
                    $application->jobseeker_basic_info = $application->user->basic_info_jobseeker;
                    array_push($job_apps_with_names, $application);
                }

                else
                {
                    continue;
                }
            }
           
        }

        if ($request->name != null)
        {
            $job_apps = $job_apps_with_names;
            return $this->sendResponse($job_apps, "Recruiter Applications Fetched");
        }


        if(count($job_apps) == 0)
        {
            $job_apps = $applications;
            foreach($applications as $application)
            {
                $application->jobseeker_basic_info = $application->user->basic_info_jobseeker;

            }
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
            'cover_letter' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        // check if user has already applied for the job
        $job_applied = Application::where('user_id', $request->user_id)
        ->where('job_id', $request->job_id)
        ->first();

        if(!empty($job_applied))
        {
            return $this->sendResponse([], "User has already applied for this job!" );

        }
        
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
