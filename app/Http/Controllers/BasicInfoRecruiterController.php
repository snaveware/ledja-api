<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\BasicInfoRecruiter;
use Validator;
use App\Http\Controllers\API\BaseController as BaseController;


class BasicInfoRecruiterController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $basic_recruiters_info = BasicInfoRecruiter::with('user')->paginate();
        return $this->sendResponse($basic_recruiters_info, "Basic Recruiter's Info Fetched Successfully");
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
            'company_name' => 'required',
            'industry' => 'required',
            'headquarters' => 'required',
            'company_size' => 'required',
            'revenue' => 'required',
            'founded_on' => 'required',
            'ceo' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();
        $job_category = BasicInfoRecruiter::create($input);

        return $this->sendResponse($job_category, "Basic Recruiter's Info Created Successfully" );



    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $job_category = BasicInfoRecruiter::with('user')->find($id);

        return $this->sendResponse($job_category, "Basic Recruiter's Info Found Successfully" );



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
        $job_category = BasicInfoRecruiter::find($id);

        $input = $request->all();
        $result = $job_category->update($input);

        return $this->sendResponse($job_category, "Basic Recruiter's Info Updated Successfully" );



    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $job_category = BasicInfoRecruiter::find($id);

        if ($job_category->has('user'))
        {
            $result = [];
            $message = "Cannot delete Basic Recruiter's Info,it contains users";
        }

        else 
        {
            $result = [];
            $message = "Basic Recruiter's Info Deleted Successfully";
            $job_category->delete();

        }


        return $this->sendResponse([], $message );

    }
}
