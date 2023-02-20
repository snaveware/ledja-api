<?php

namespace App\Http\Controllers;

use App\Models\JobSeekerLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;


class JobSeekerLinkController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $job_seeker_links = JobSeekerLink::with('user')->paginate();
        return $this->sendResponse($job_seeker_links, "JobSeekers Links Fetched Successfully");
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
            'websites' => 'nullable',
            'linked_in' => 'required',
            'twitter' => 'nullable',
            'facebook' => 'nullable'
        ]);


        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();
        $job_seeker_links = JobSeekerLink::create($input);

        return $this->sendResponse($job_seeker_links, "Links Created Successfully" );

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $job_seeker_links = JobSeekerLink::with('user')->find($id);

        return $this->sendResponse($job_seeker_links, "Links Found Successfully" );



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
        $job_seeker_links = JobSeekerLink::find($id);

        // $input = $request->except(['avatar']);
        $input = $request->all();

        $result = $job_seeker_links->update($input);

        return $this->sendResponse($job_seeker_links, "Links Updated Successfully" );


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $job_seeker_links = JobSeekerLink::find($id);

        if ($job_seeker_links->has('users'))
        {
            $result = [];
            $message = "Cannot delete Links,it contains users";
        }

        else 
        {
            $result = [];
            $message = "Links Deleted Successfully";
            $job_seeker_links->delete();

        }


        return $this->sendResponse([], $message );

    }
}
