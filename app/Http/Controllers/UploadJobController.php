<?php

namespace App\Http\Controllers;

use App\Models\UploadJob;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use Illuminate\Support\Facades\Storage;


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
            'other_docs' => 'nullable'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();

        if($request->hasFile('resume'))
        {
            $path = Storage::disk('public')->putFile('resumes', $request->file('resume'));
            $url = env('APP_URL') . Storage::url($path);
            $input['resume_url'] = $url;
            
        }

        $other_docs_urls = [];
        if($request->hasFile('other_docs'))
        {
            
            $files = $request->file('other_docs');

            foreach($files as $file)
            {
                $path = Storage::disk('public')->putFile('other_docs', $file);
                $url = env('APP_URL') . Storage::url($path);
                array_push($other_docs_urls, $url);
            }

            $result = implode(" | ", $other_docs_urls);
            $input['other_docs_urls'] = $result;
           
        }

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
        
        $input = $request->all();

        if($request->hasFile('resume'))
        {
            $path = Storage::disk('public')->putFile('resumes', $request->file('resume'));
            $url = env('APP_URL') . Storage::url($path);
            $input['resume_url'] = $url;
            
        }

        $other_docs_urls = [];
        if($request->hasFile('other_docs'))
        {
            
            $files = $request->file('other_docs');

            foreach($files as $file)
            {
                $path = Storage::disk('public')->putFile('other_docs', $file);
                $url = env('APP_URL') . Storage::url($path);
                array_push($other_docs_urls, $url);
            }

            $result = implode(" | ", $other_docs_urls);
            $input['other_docs_urls'] = $result;
            
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