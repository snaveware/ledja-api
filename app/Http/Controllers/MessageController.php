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

    public function get_user_message(string $user_id)
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
            $messages = Message::where('jobseeker_id', $user_id)->get();
            $unread = $messages->where('has_jobseeker_read', false)->count();
            $read = $messages->where('has_jobseeker_read', true)->count();
        }

        else{
            $messages = Message::where('recruiter_id', $user_id)->get();
            $unread = $messages->where('has_recruiter_read', false)->count();
            $read = $messages->where('has_recruiter_read', true)->count();
            
        }

        // Show result
        if(!empty($messages))
        {
            foreach($messages as $message)
            {
                 // Get Application With Job
                $application = Application::with(['user', 'job'])
                ->where('user_id', $message->jobseeker_id)->first();

                $message->application = $application;

            }
           
        }

        // dd(gettype($messages));
        // $messages["unread_messages"] = $unread;
        // $messages["read_messages"] = $read;

        $data = [
            "messages" => $messages,
            
            "unread_messages" => $unread,
            "read_messages" => $read
        ];


        return $this->sendResponse($data ,"Messages Fetched Successfully");

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
    public function update(Request $request, string $id)
    {

        $message = Message::findOrFail($id);
        $input = $request->all();
        $message->update($input);

        return $this->sendResponse($message, "Message Updated Successfully" );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message): RedirectResponse
    {
        //
    }
}
