<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\SavedJob;
use App\Models\JobCategory;
use App\Models\Wallet;
use App\Models\User;
use App\Models\Job;
use Validator;
use App\Http\Controllers\API\BaseController as BaseController;


class SavedJobController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $saved_jobs = SavedJob::with(['jobs', 'users'])->paginate();
        return $this->sendResponse($saved_jobs, "Jobs Fetched Successfully");
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
    public function store(Request $request, string $user_id, string $job_id)
    {
        // Check if user has saved this job
        $is_job_saved = false;
        $user = User::findorFail($user_id);
        $user_saved_jobs = $user->saved_jobs;

        // dd($user_saved_jobs);
        if(!empty($user_saved_jobs))
        {
            // The user has jobs
            foreach($user_saved_jobs as $saved_job)
            {
                foreach($saved_job->jobs as $job)
                {
                    if($job->id == $job_id)
                    {
                        $is_job_saved = true;
                        $input = $request->all();

                        if($request->status == 'deleted')
                        {
                            $saved_job->update($input);
                            return $this->sendResponse([], "Exisiting Saved Job has been unsaved." );

                        }

                        if($request->status == 'saved')
                        {
                            $saved_job->update($input);
                            return $this->sendResponse([], "Job has been saved." );

                        }
                    }

                    
                }
            }
        }

        if($is_job_saved && $request->status == 'deleted')
        {
            // update job status
            return $this->sendResponse([], "Job Was Already Saved,Please Save Another Job" );

        }

        if($is_job_saved)
        {
            return $this->sendResponse([], "Job Was Already Saved,Please Save Another Job" );

        }

        // validate fields
        $validator = Validator::make($request->all(), [
            'status' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();
        // Get job category
        $saved_job = SavedJob::create($input);
        $saved_job->jobs()->attach($job_id);
        $saved_job->users()->attach($user_id);
        $saved_job->jobs;
        $saved_job->users;

        return $this->sendResponse($saved_job, "Job Saved Successfully" );



    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $saved_job = SavedJob::with(['users','jobs'])->find($id);
        return $this->sendResponse($saved_job, "Jobs Found Successfully" );

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
        $saved_job = SavedJob::findorFail($id);
        $input = $request->all();
        $result = $saved_job->update($input);
        return $this->sendResponse($saved_job, "Jobs Updated Successfully" );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $saved_job = SavedJob::findorFail($id);
        $message = "Jobs Deleted Successfully";
        $saved_job->delete();
        return $this->sendResponse([], $message );

    }

     /**
     * Display the specified resource.
     */
    public function get_user_saved_jobs(string $user_id)
    {
        $user_saved_jobs = User::findorFail($user_id)->saved_jobs()->get();
        $jobs_saved = [];
        
        foreach($user_saved_jobs as $saved_job)
        {
            $my_id = $saved_job->pivot->user_id;
            foreach($saved_job->jobs as $my_saved_job)
            {
                if($my_id== $user_id)
                {
                    if ($saved_job->status != 'deleted')
                    {
                        array_push($jobs_saved, [
                            'saved_job' => $saved_job,
                            'the_job' => $my_saved_job,
                        ]);
                    }
                   
                }
            }
        }

        // return $this->sendResponse($jobs_saved, "Saving Job..." );

        // $user_saved_jobs->jobs_saved = $jobs_saved;

        return $this->sendResponse($jobs_saved, "Saved User Jobs Found Successfully" );

    }

}
