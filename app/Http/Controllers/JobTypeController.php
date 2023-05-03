<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\JobType;
use Validator;
use App\Http\Controllers\API\BaseController as BaseController;


class JobTypeController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $job_types = JobType::with('jobs')->latest()->paginate();
        return $this->sendResponse($job_types, "Job Types Fetched Successfully");
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
            'title' => 'required',
            'description' => 'nullable',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();
        $job_type = JobType::create($input);

        return $this->sendResponse($job_type, "Job Type Created Successfully" );



    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $job_type = JobType::with(['jobs'])->find($id);

        return $this->sendResponse($job_type, "Job Type Found Successfully" );



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
        $job_type = JobType::find($id);

        $input = $request->all();
        $result = $job_type->update($input);
        $result->jobs();

        return $this->sendResponse($job_type, "Job Type Updated Successfully" );



    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $job_type = JobType::find($id);

        if ($job_type->has('users'))
        {
            $result = [];
            $message = "Cannot delete Job Type,it contains users";
        }

        else 
        {
            $result = [];
            $message = "Job Type Deleted Successfully";
            $job_type->delete();

        }


        return $this->sendResponse([], $message );

    }
}
