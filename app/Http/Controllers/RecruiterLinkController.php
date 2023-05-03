<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\RecruiterLink;
use Validator;
use App\Http\Controllers\API\BaseController as BaseController;


class RecruiterLinkController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $recruiter_links = RecruiterLink::with('user')->latest()->paginate();
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
            'websites' => 'nullable',
            'linked_in' => 'nullable',
            'twitter' => 'nullable',
            'facebook' => 'nullable',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();
        $recruiter_link = RecruiterLink::create($input);

        return $this->sendResponse($recruiter_link, "Recruiter Link Created Successfully" );



    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $recruiter_link = RecruiterLink::with('user')->find($id);

        return $this->sendResponse($recruiter_link, "Recruiter Link Found Successfully" );



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
        $recruiter_link = RecruiterLink::find($id);

        $input = $request->all();
        $result = $recruiter_link->update($input);

        return $this->sendResponse($recruiter_link, "Recruiter Link Updated Successfully" );



    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $recruiter_link = RecruiterLink::find($id);

        if ($recruiter_link->has('user'))
        {
            $result = [];
            $message = "Cannot delete Recruiter Link,it contains users";
        }

        else 
        {
            $result = [];
            $message = "Recruiter Link Deleted Successfully";
            $recruiter_link->delete();

        }


        return $this->sendResponse([], $message );

    }
}
