<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\MoreAboutRecruiter;
use Validator;
use App\Http\Controllers\API\BaseController as BaseController;


class MoreAboutRecruiterController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $recruiter_links = MoreAboutRecruiter::with('user')->latest()->paginate();
        return $this->sendResponse($recruiter_links, "Recruiter Links Fetched Successfully");
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
            'company_intro' => 'required',
            'company_culture' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();
        $more_about_recruiter = MoreAboutRecruiter::create($input);

        return $this->sendResponse($more_about_recruiter, "More About Recruiter Created Successfully" );



    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $more_about_recruiter = MoreAboutRecruiter::with('user')->find($id);

        return $this->sendResponse($more_about_recruiter, "More About Recruiter Found Successfully" );



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
        $more_about_recruiter = MoreAboutRecruiter::find($id);

        $input = $request->all();
        $result = $more_about_recruiter->update($input);

        return $this->sendResponse($more_about_recruiter, "More About Recruiter Updated Successfully" );



    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $more_about_recruiter = MoreAboutRecruiter::find($id);

        if ($more_about_recruiter->has('user'))
        {
            $result = [];
            $message = "Cannot delete More About Recruiter,it contains user";
        }

        else 
        {
            $result = [];
            $message = "More About Recruiter Deleted Successfully";
            $more_about_recruiter->delete();

        }


        return $this->sendResponse([], $message );

    }
}
