<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;
use App\Models\Wallet;
use App\Models\PasswordResetToken;
use Illuminate\Support\Carbon;



class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */

   

     public function index()
    {
        $users = User::with(['user_type',
                            'basic_info_jobseeker',
                            'basic_info_recruiter',
                            'job_seeker_link',
                            'recruiter_link',
                            'about_job_seeker',
                            'about_recruiter',
                            'more_about_recruiter',
                            'upload_job',
                            'wallet',
                            'work_experiences',
                            'skills',
                            'education',
                            'saved_jobs',
    ])->paginate();
        return $this->sendResponse($users, "Users Fetched Successfully");
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_type_id' => 'nullable',
            'email' => ['required', 'email' , 'unique:users' ],
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['email'] =  $user->email;
        $user->user_type;
        $user->basic_info_jobseeker;
        $user->basic_info_recruiter;
        $success['user'] =  $user;

        // create user wallet
        if ($user->user_type->name == "recruiter")
        {

            $wallet_data = [
                'user_id' => $user->id,
                'amount' => 0,
            ];

            $wallet = Wallet::create($wallet_data);
            $success['wallet'] = $wallet;
       
        }
        
        return $this->sendResponse($success, 'User registered successfully.');
    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')->plainTextToken; 
            $success['email'] =  $user->email;
            // 'job_seeker_link',
            // 'recruiter_link',
            // 'about_job_seeker',
            // 'about_recruiter',
            // 'more_about_recruiter',
            // 'upload_job',
            // 'wallet',
            // 'work_experiences',
            // 'skills',
            // 'education',
            $user->user_type;
            $user->basic_info_jobseeker;
            $user->basic_info_recruiter;
            $user->job_seeker_link;
            $user->recruiter_link;
            $user->about_job_seeker;
            $user->about_recruiter;
            $user->more_about_recruiter;
            $user->upload_job;
            $user->wallet;
            $user->work_experiences;
            $user->skills;
            $user->education;
            $success['user'] =  $user;
            // $success['user_type'] =  $user->user_type;

   
            return $this->sendResponse($success, 'User login successfull.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }

     /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $user = User::with(['user_type',
                            'basic_info_jobseeker',
                            'basic_info_recruiter',
                            'job_seeker_link',
                            'recruiter_link',
                            'about_job_seeker',
                            'about_recruiter',
                            'more_about_recruiter',
                            'upload_job',
                            'wallet',
                            'work_experiences',
                            'skills',
                            'education',
                            'saved_jobs',
        ])->find($id);

        return $this->sendResponse($user, "User Found Successfully" );

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $user = User::find($id);

        $input = $request->all();
        $result = $user->update($input);

        return $this->sendResponse($user, "User Updated Successfully" );



    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $user = User::find($id);

        if($user)
        {

            $user->delete();
            $result = [];
            $message = "User Deleted Successfully";
        }

        else
        {
            $result = [];
            $message = "User Does Not Exist";
        }

        return $this->sendResponse([], $message );

    }

    // Forgot Password Api
    public function forgot_password(Request $request)
    {
        $input = $request->all();
        $rules = array(
            'email' => "required|email",
        );
        $validator = Validator::make($input, $rules);
        // Send email to user
        // Notify user that email has been sent
    }

    public function reset_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'code' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        $userPasswordReset = PasswordResetToken::where('email', $request->email)->latest()->first();
        $createdAt = $userPasswordReset->created_at;
        $token = $userPasswordReset->token;

        if($createdAt < Carbon::now()->subMinutes(2)->toDateTimeString())
        {
            return $this->sendResponse([], "You only have 2 minutes to change your password,please start the process again..");
        }

        if($userPasswordReset == null)
        {
            return $this->sendResponse([], "Invalid Credentials!");
        }

        if($request->code != $userPasswordReset->code)
        {
            return $this->sendResponse([], "Invalid Credentials..");
        }
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->only('password');
        $input['password'] = bcrypt($input['password']);
        $user = User::where('email', $request->email)->first();
        $user->update($input);


        return $this->sendResponse($user, "Password Reset Successfull" );
    }

}
