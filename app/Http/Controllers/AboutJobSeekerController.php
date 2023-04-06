<?php

namespace App\Http\Controllers;

use App\Models\AboutJobSeeker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;


class AboutJobSeekerController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $about_job_seekers = AboutJobSeeker::with('user')->paginate();
        return $this->sendResponse($about_job_seekers, "About Job Seekers Fetched Successfully");
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
            'work_experience' => 'required',
            'education' => 'required',
            'skills' => 'required',
        ]);


        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();
        $about_job_seekers = AboutJobSeeker::create($input);

        return $this->sendResponse($about_job_seekers, "About Job Seekers Created Successfully" );

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $about_job_seekers = AboutJobSeeker::with('user')->find($id);

        return $this->sendResponse($about_job_seekers, "About Job Seekers Found Successfully" );



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
        $about_job_seekers = AboutJobSeeker::find($id);

        $input = $request->all();

        $result = $about_job_seekers->update($input);

        return $this->sendResponse($about_job_seekers, "About Job Seekers Updated Successfully" );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $about_job_seekers = AboutJobSeeker::find($id);

        if ($about_job_seekers->has('users'))
        {
            $result = [];
            $message = "Cannot delete About Job Seekers,it contains users";
        }

        else 
        {
            $result = [];
            $message = "About Job Seekers Deleted Successfully";
            $about_job_seekers->delete();

        }


        return $this->sendResponse([], $message );

    }
}
