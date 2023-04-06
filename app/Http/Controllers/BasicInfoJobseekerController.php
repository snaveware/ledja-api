<?php

namespace App\Http\Controllers;

use App\Models\BasicInfoJobseeker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use Illuminate\Support\Facades\Storage;



class BasicInfoJobseekerController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $basic_infos = BasicInfoJobSeeker::with('user')->paginate();
        return $this->sendResponse($basic_infos, "JobSeekers Basic Info Fetched Successfully");
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
            'fname' => 'required',
            'lname' => 'required',
            'phone_no' => 'required',
            'position' => 'required',
            'location' => 'required',
            'avatar' => 'nullable',
        ]);

        $input = $request->all();

        if($request->hasFile('avatar') )
        {
            $path = Storage::disk('public')->putFile('avatars', $request->file('avatar'));
            $url = env('APP_URL') . Storage::url($path);
            $input['avatar_url'] = $url;
        }

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $basic_infos = BasicInfoJobSeeker::create($input);

        return $this->sendResponse($basic_infos, "Basic Info Created Successfully" );

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $basic_infos = BasicInfoJobSeeker::with('user')->find($id);

        return $this->sendResponse($basic_infos, "Basic Info Found Successfully" );



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
        $basic_infos = BasicInfoJobSeeker::find($id);

        // $input = $request->except(['avatar']);
        $input = $request->all();

        if(count($input))
        {


        if($request->has('avatar'))
        {
            $path = Storage::disk('public')->putFile('avatars', $request->file('avatar'));
            $url = env('APP_URL') . Storage::url($path);
            $input['avatar_url'] = $url;
            
        }
        $result = $basic_infos->update($input);

        return $this->sendResponse($basic_infos, "Basic Info Updated Successfully" );

       }


       else
       {
         return $this->sendResponse($basic_infos, "Basic Info Untouched" );

       }



    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $basic_infos = BasicInfoJobSeeker::find($id);

        if ($basic_infos->has('users'))
        {
            $result = [];
            $message = "Cannot delete Basic Info,it contains users";
        }

        else 
        {
            $result = [];
            $message = "Basic Info Deleted Successfully";
            $basic_infos->delete();

        }


        return $this->sendResponse([], $message );

    }
}