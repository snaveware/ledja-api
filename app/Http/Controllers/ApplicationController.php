<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Application;
use App\Models\SkillsAssessment;
use App\Models\Score;
use App\Models\Result;
use App\Models\Message;
use App\Models\User;
use App\Models\Job;
use Validator;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Mail\Shortlisted;
use Mail;


class ApplicationController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $applications = Application::with(['job', 'user'])->latest()->paginate();
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
        $applications = Application::
        status($input['status'])->where('job_id', $job_id)->latest()->paginate(30);
       
        $job_apps = [];
        $job_apps_with_names = [];
        

        foreach($applications as $application)
        {
            $application->assessment = SkillsAssessment::with(['user', 'jobs'])
            ->where('user_id', $application->job->user_id)->first();
            $application->score = Score::where('user_id', $application->user_id)
            ->first();
            $application->results = Result::with(['skills_assessment', 'question'])
            ->where('user_id', $application->user_id)
            ->get();
            
            // Check if there is a fname and lname filter
            if ($request->name != null)
            {
                
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

    public function job_application_status(Request $request, string $jobseeker_id)
    {
        $request->status = $request->status == null ? $request->merge(['status' => '']) :  $request->status;
        $input = $request->all();
        $applications = Application::with(['job', 'user'])
        ->status($input['status'])
        ->where('user_id', $jobseeker_id)
        ->latest()
        ->get();

        foreach($applications as $app)
        {
            $skills_assessments = SkillsAssessment::with(['user', 'jobs', 'questions', 'scores', 'results'])
            ->where('user_id', $app->job->user_id)->get();
            $recruiter = User::findorFail($app->job->user_id);
            // dd($recruiter);
            $app->assessment_tests = $skills_assessments;
            $app->recruiter_basic_infos = $recruiter->basic_info_recruiter;
        }

        
        

        return $this->sendResponse($applications, "Jobseeker Applications Fetched");

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
        $response = [
            "recruiter_message" => "You have updated the status for the application to {$application->status}",
            "jobseeker_message" => "Your application status has been set to {$application->status}"
        ];

        // Add status to messages table.
        $message = [];
        $message['recruiter_id'] = $application->job->user_id; 
        $message['jobseeker_id'] = $application->user_id; 
        $message['application_id'] = $application->id; 
        $message['job_id'] = $application->job->id; 
        $message['status'] = $application->status;

        if($message['status'] == 'review')
        {
            $message['jobseeker_message'] = "Your application is under review";
        }

        if($message['status'] == 'shortlisted')
        {
            $message['jobseeker_message'] = "You've been shortlisted, please check your email for next steps";
            $email_data = [];
            // get user
            $user = User::where('id', $application->user->id)->first();
            $user->basic_info_jobseeker;
            array_push($email_data, ['user' => $user]);
            // get job
            $job = Job::where('id', $application->job->id)->first();
            array_push($email_data, ['job' => $job]);

            // send email
            Mail::to('talimwakesi@gmail.com')->send(new Shortlisted($email_data));
            $message['recruiter_message'] = "You have updated the status for the application to {$application->status}";

        }

        else 
        {
            $message['recruiter_message'] = "You have updated the status for the application to {$application->status}";
            $message['jobseeker_message'] = "Your application status has been set to {$application->status} ";
        }
       
        $my_message = Message::create($message);
        $application->message = $my_message;

        // for this application,get the job,get the skills assessment then you cna get the score
        $job = $application->job;
        $skills_test = $job->skills_assessment;
        $scores = $skills_test->scores;

        $application->scores = $scores;
        

        return $this->sendResponse($application, $response );



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


    public function get_active_applications(string $job_id)
    {
        $active_applications = Application::where('job_id', $job_id)->latest()->get();
        $no_active_applications = $active_applications->count();

        $awaiting = Application::where('status', 'awaiting')->where('job_id', $job_id)->get();
        $reviewed = Application::where('status', 'reviewed')->where('job_id', $job_id)->get();
        $contacting = Application::where('status', 'contacting')->where('job_id', $job_id)->get();
        $shortlisted = Application::where('status', 'shortlisted')->where('job_id', $job_id)->get();
        $hired = Application::where('status', 'hired')->where('job_id', $job_id)->get();
        $rejected = Application::where('status', 'rejected')->where('job_id', $job_id)->get();

        $applications = [
            'applications' => $active_applications,
            'awaiting' => $awaiting,
            'reviewed' => $reviewed,
            'contacting' => $contacting,
            'shortlisted' => $shortlisted,
            'hired' => $hired,
            'rejected' => $rejected,

            'no_of_active_candidates' => $active_applications->count(),
            'no_of_awaiting_candidates' => $awaiting->count(),
            'no_of_reviewed_candidates' => $reviewed->count(),
            'no_of_contacting_candidates' => $contacting->count(),
            'no_of_shortlisted_candidates' => $shortlisted->count(),
            'no_of_hired_candidates' => $hired->count(),
            'no_of_rejected_candidates' => $rejected->count(),
        ];

        return $this->sendResponse($applications, "Active Applications Fetched");
    }
}
