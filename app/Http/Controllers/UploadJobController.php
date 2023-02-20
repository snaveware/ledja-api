<?php

namespace App\Http\Controllers;

use App\Models\UploadJob;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;

class UploadJobController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $upload_jobs = UploadJob::with('user')->paginate();
        return $this->sendResponse($upload_jobs, "JobSeekers Upload Jobs Fetched Successfully");
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
            'resume' => 'required',
            'other_docs' => 'required'
        ]);

        $resume_path = $request->file('resume')->storeAs(
            'resumes', $request->user()->id
        );

        $other_docs_path = $request->file('other_docs')->storeAs(
            'other_docs', $request->user()->id
        );

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();
        $input['resume'] = $resume_path;
        $input['other_docs'] = $other_docs_path;
        $upload_job = UploadJob::create($input);

        return $this->sendResponse($upload_job, "Upload Jobs Created Successfully" );

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $upload_jobs = UploadJob::with('user')->find($id);

        return $this->sendResponse($upload_jobs, "Upload Jobs Found Successfully" );



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
        $upload_jobs = UploadJob::find($id);

        // $input = $request->except(['avatar']);
        $input = $request->all();

        if(count($input))
        {


        if($request->has('resume'))
        {
            $path = $request->file('resume')->storeAs(
                'resume', $request->user()->id
            );

            $input['resume'] = $path;
            
            
        }

        if($request->has('other_docs'))
        {
            $path = $request->file('other_docs')->storeAs(
                'other_docs', $request->user()->id
            );

            $input['other_docs'] = $path;
            
            
        }
        $result = $upload_jobs->update($input);

        return $this->sendResponse($upload_jobs, "Upload Jobs Updated Successfully" );

       }


       else
       {
         return $this->sendResponse($upload_jobs, "Upload Jobs Untouched" );

       }



    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $upload_jobs = UploadJob::find($id);

        if ($upload_jobs->has('users'))
        {
            $result = [];
            $message = "Cannot delete Upload Jobs,it contains users";
        }

        else 
        {
            $result = [];
            $message = "Upload Jobs Deleted Successfully";
            $upload_jobs->delete();

        }


        return $this->sendResponse([], $message );

    }
}