<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Job;
use App\Models\User;
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
        $jobs = Job::with(['user', 'skills_assessment', 'company', 'job_category', 'job_types'])->latest()->paginate();
        foreach($jobs as $job)
        {
            // Get the recruiter basic info for each user
            $job->recruiter_basic_info = $job->user->basic_info_recruiter;
            $job->more_about_recruiter = $job->user->more_about_recruiter;
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
            'company_id' => 'nullable',
            'hiring_company' => 'nullable',
            'category' => 'required',
            'job_status' => 'required',
            'company_industry' => 'required',
            // 'company_sub_industry' => 'required',
            'title' => 'required',
            'application_link' => 'nullable',
            'location' => 'required',
            'description' => 'required',
            'salary' => 'nullable',
            'experience_level' => 'required',
            'no_of_hires' => 'required',
            'hiring_speed' => 'required',
            'own_completion' => 'required',
            'with_recommendation' => 'required',
            'with_resume' => 'required',
            'communication_preferences' => 'required',
            'apply_method' => 'required',
            'send_to_email' => 'required',
            'job_type_ids' => 'required',
            // 'requirements' => 'required',
            // 'responsibilities' => 'required',
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

        // Check if recruiter is our admin,if so allow to post job
        $user = User::findOrFail($input['user_id']);

        // Pay for posting the job.
        if (!is_null($user))
        {
            /* 
            || Emails to blacklist ||
            *** From Ledja ***

            info@ledja.net,
            tysoreg1@gmail.com,
            tysoreg.1@gmail.com,
            tysoronnie.o@gmail.com,
            tyso.ronnie.o@gmail.com,
            Ledjalimited@gmail.com,


            *** From Me ***

            talimwakesi@gmail.com,
            cmaina413@gmail.com,
            
            */

            $whitelist = [
                "info@ledja.net",
                "tysoreg1@gmail.com",
                "tysoreg.1@gmail.com",
                "tysoronnie.o@gmail.com",
                "tyso.ronnie.o@gmail.com",
                "Ledjalimited@gmail.com",
                "ewak222@yahoo.com",
                "talimwakesi@gmail.com",
                "cmaina413@gmail.com",
            ];

            // if($user->email == 'info@ledja.net' || $user->email == 'ewak222@yahoo.com')
            if( in_array($user->email, $whitelist) )
            {
                // Allow users to post jobs without money in the wallet.
                // Credit them with 2 million for testing
                $wallet->amount = 2000000;
            }

            else
            {
                if ($wallet->amount < $job_category->cost)
                {
                    // Inform user if recruiter doesn't have the funds
                    return $this->sendResponse([], "Not enough funds,you have Kshs. {$wallet->amount} in your wallet,the job costs Kshs {$job_category->cost}, please top up to continue" );

                }
            
                $wallet->amount = $wallet->amount - $job_category->cost;
                $wallet->save();
            }
        }

        $job = Job::create($input);
        $job_type_ids = explode(",", $input['job_type_ids']);
        foreach($job_type_ids as  $key => $value )
        {
            $job->job_types()->attach($value);
        }

        $job->job_types;

        return $this->sendResponse($job, "Jobs Created Successfully" );

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $job = Job::with(['user', 'company', 'job_category', 'job_types', 'skills_assessment'])->find($id);
        $job->recruiter_basic_info = $job->user->basic_info_recruiter;
        $job->more_about_recruiter = $job->user->more_about_recruiter;

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
        $jobs = Job::with(['user','company','job_category','job_types'])->where('user_id', $user_id)->latest()->paginate(15);

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
            'job_types' => 'nullable',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();

        // Filter jobs by field

        $jobs = Job::with(['user', 'company','job_category','job_types'])
        ->JobTypes($input['job_types'])
        ->salary($input['salary'])
        ->title($input['title'])
        ->location($input['location'])
        ->experienceLevel($input['experience_level'])
        ->datePosted($input['date_posted'])
        ->latest()
        ->paginate(15);

      
        $filtered_jobs = [];
        foreach($jobs as $job)
        {
            // Get the recruiter basic info for each user
            $job->recruiter_basic_info = $job->user->basic_info_recruiter;
        }

        return $this->sendResponse($jobs, "Jobs Fetched Successfully" );



    }
}
