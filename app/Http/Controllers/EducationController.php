<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Education;
use Validator;
use App\Http\Controllers\API\BaseController as BaseController;


class EducationController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $education = Education::with(['user'])->paginate();
        return $this->sendResponse($education, "Educations Fetched Successfully");
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
            'institution' => 'required',
            'certification' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        if($request->start_date > $request->end_date)
        {
            return $this->sendResponse([], "Your start date cannot be later than your end date!" );
        }

        $input = $request->all();
        $education = Education::create($input);

        return $this->sendResponse($education, "Education Created Successfully" );

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $education = Education::with(['user'])->find($id);

        return $this->sendResponse($education, "Education Found Successfully" );


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
        $education = Education::find($id);

        if($request->start_date > $request->end_date)
        {
            return $this->sendResponse([], "Your start date cannot be later than your end date!" );
        }

        $input = $request->all();
        $result = $education->update($input);

        return $this->sendResponse($education, "Education Updated Successfully" );



    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $education = Education::findorFail($id);

        /* if ($education->has('user'))
        {
            $result = [];
            $message = "Cannot delete Education,it contains users";
        }

        else 
        {
            $result = [];
            $message = "Education Deleted Successfully";
            $education->delete();

        } */

        $education->delete();
        $message = "Education Deleted Successfully";


        return $this->sendResponse([], $message );

    }
}
