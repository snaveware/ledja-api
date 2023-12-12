<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\AboutRecruiter;
use Validator;
use App\Http\Controllers\API\BaseController as BaseController;


class AboutRecruiterController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $about_recruiters = AboutRecruiter::with('user')->paginate();
        return $this->sendResponse($about_recruiters, "About Recruiters Fetched Successfully");
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
            'company_position' => 'required',
            'phone_no' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();
        $about_recruiter = AboutRecruiter::create($input);

        return $this->sendResponse($about_recruiter, "About Recruiter Created Successfully" );



    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $about_recruiter = AboutRecruiter::with('user')->find($id);

        return $this->sendResponse($about_recruiter, "About Recruiter Found Successfully" );



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
        $about_recruiter = AboutRecruiter::find($id);

        $input = $request->all();
        $result = $about_recruiter->update($input);

        return $this->sendResponse($about_recruiter, "About Recruiter Updated Successfully" );



    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $about_recruiter = AboutRecruiter::find($id);

        if ($about_recruiter->has('user'))
        {
            $result = [];
            $message = "Cannot delete About Recruiter,it contains user";
        }

        else 
        {
            $result = [];
            $message = "About Recruiter Deleted Successfully";
            $about_recruiter->delete();

        }


        return $this->sendResponse([], $message );

    }
}
