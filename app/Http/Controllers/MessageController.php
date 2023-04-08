<?php
namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Message;
use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use Validator;
use App\Http\Controllers\API\BaseController as BaseController;


class MessageController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        //Get all messages
    }

    public function get_user_message(string $user_id, string $application_id)
    {
        // Get user 
        $user = User::findorFail($user_id);
        if($user->user_type->name == "jobseeker")
        {
            $is_jobseeker = true;
        }

        if($user->user_type->name == "recruiter")
        {
            $is_jobseeker = false;
        }

        // Fetch latest message for user for that application
        if($is_jobseeker)
        {
            $message = Message::where('jobseeker_id', $user_id)->where('application_id', $application_id)->latest()->first();
        }
        else{
            $message = Message::where('recruiter_id', $user_id)->where('application_id', $application_id)->latest()->first();
        }
        // Show result
        if(!empty($message))
        {
            // Get Application
            $application = Application::findOrFail($application_id);
            // Get Job
            $job = Job::findOrFail($application->job->id);

            $message->application = $application;
            $message->job = $job;
        }

        return $this->sendResponse($message ,"Message Fetched Successfully");

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
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message): RedirectResponse
    {
        //
    }
}
