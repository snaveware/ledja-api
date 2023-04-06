<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\JobCategory;
use Validator;
use App\Http\Controllers\API\BaseController as BaseController;


class JobCategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $job_categories = JobCategory::with('jobs')->paginate();
        return $this->sendResponse($job_categories, "Job Categories Fetched Successfully");
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
            'type' => 'required',
            'cost' => 'required',
            'description' => 'nullable',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();
        $job_category = JobCategory::create($input);

        return $this->sendResponse($job_category, "Job Category Created Successfully" );



    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $job_category = JobCategory::find($id);

        return $this->sendResponse($job_category, "Job Category Found Successfully" );



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
        $job_category = JobCategory::find($id);

        $input = $request->all();
        $result = $job_category->update($input);

        return $this->sendResponse($job_category, "Job Category Updated Successfully" );



    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $job_category = JobCategory::find($id);

        if ($job_category->has('users'))
        {
            $result = [];
            $message = "Cannot delete Job Category,it contains users";
        }

        else 
        {
            $result = [];
            $message = "Job Category Deleted Successfully";
            $job_category->delete();

        }


        return $this->sendResponse([], $message );

    }
}
