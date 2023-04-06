<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\Wallet;
use Validator;
use App\Http\Controllers\API\BaseController as BaseController;


class JobController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobs = Job::with(['user', 'skills_assessment', 'job_category'])->paginate();
        foreach($jobs as $job)
        {
            // Get the recruiter basic info for each user
            $job->recruiter_basic_info = $job->user->basic_info_recruiter;
        }
        return $this->sendResponse($jobs, "Jobs Fetched Successfully");
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
            'skills_assessment_id' => 'nullable',
            'job_category_id' => 'required',
            'job_status' => 'required',
            'company_industry' => 'required',
            'company_sub_industry' => 'required',
            'title' => 'required',
            'location' => 'required',
            'description' => 'required',
            'salary' => 'required',
            'experience_level' => 'required',
            'type' => 'required',
            'no_of_hires' => 'required',
            'hiring_speed' => 'required',
            'own_completion' => 'required',
            'with_recommendation' => 'required',
            'with_resume' => 'required',
            'communication_preferences' => 'required',
            'apply_method' => 'required',
            'send_to_email' => 'required',
            // 'skills_assessment' => 'nullable',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        // $path = $request->file('skills_assessment')->storeAs(
        //     'skills_assessment_tests', $request->user()->id
        // );

        // $input['skills_assessment'] = $path;


        $input = $request->all();
        // Get job category
        $job_category = JobCategory::find($input['job_category_id']);
        $wallet = Wallet::where('user_id', $input['user_id'])->first();
        // dd($wallet->amount < $job_category->cost);
        // dd($job_category->cost);
        // dd($wallet->amount);
        // Deduct cost from wallet
        if ($wallet->amount < $job_category->cost)
        {
            // Inform user if recruiter doesn't have the funds
            return $this->sendResponse([], "Not enough funds,you have Kshs. {$wallet->amount} in your wallet,the job costs Kshs {$job_category->cost}, please top up to continue" );

        }
       
        $wallet->amount = $wallet->amount - $job_category->cost;
        $wallet->save();
        $jobs = Job::create($input);

        return $this->sendResponse($jobs, "Jobs Created Successfully" );



    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $job = Job::with(['user','job_category'])->find($id);
        $job->recruiter_basic_info = $job->user->basic_info_recruiter;

        return $this->sendResponse($job, "Jobs Found Successfully" );



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
        $jobs = Job::find($id);

        $input = $request->all();
        $result = $jobs->update($input);

        return $this->sendResponse($jobs, "Jobs Updated Successfully" );



    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $jobs = Job::find($id);

        if ($jobs->has('user'))
        {
            $result = [];
            $message = "Cannot delete Jobs,it contains user";
        }

        else 
        {
            $result = [];
            $message = "Jobs Deleted Successfully";
            $jobs->delete();

        }


        return $this->sendResponse([], $message );

    }

     /**
     * Display the specified resource.
     */
    public function get_user_jobs(string $user_id)
    {
        //
        $jobs = Job::with(['user','job_category'])->where('user_id', $user_id)->get();

        return $this->sendResponse($jobs, "User Jobs Found Successfully" );



    }


     /**
     * Filter jobs
     */
    public function filter_jobs(Request $request)
    {
        // validate fields
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable',
            'job_category_id' => 'nullable',
            'job_status' => 'nullable',
            'company_industry' => 'nullable',
            'company_sub_industry' => 'nullable',
            'title' => 'nullable',
            'location' => 'nullable',
            'description' => 'nullable',
            'salary' => 'nullable',
            'experience_level' => 'nullable',
            'type' => 'nullable',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();

        // Filter jobs by field

        $jobs = Job::type($input['type'])
        ->salary($input['salary'])
        ->title($input['title'])
        ->title($input['location'])
        ->experienceLevel($input['experience_level'])
        ->datePosted($input['date_posted'])
        ->get();
      
        $filtered_jobs = [];
        foreach($jobs as $job)
        {
            // Get the recruiter basic info for each user
            $job->recruiter_basic_info = $job->user->basic_info_recruiter;
        }

        return $this->sendResponse($jobs, "Jobs Fetched Successfully" );



    }
}
