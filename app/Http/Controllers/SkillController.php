<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Skill;
use Validator;
use App\Http\Controllers\API\BaseController as BaseController;


class SkillController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $skills = Skill::with(['user'])->latest()->paginate();
        return $this->sendResponse($skills, "Skills Fetched Successfully");
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
            'name' => 'required',
            'certification' => 'required',
            'proficiency' => 'required',
            'other' => 'nullable',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();
        $skill = Skill::create($input);

        return $this->sendResponse($skill, "Skill Created Successfully" );



    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $skill = Skill::with(['user'])->find($id);

        return $this->sendResponse($skill, "Skill Found Successfully" );


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
        $skill = Skill::find($id);

        $input = $request->all();
        $result = $skill->update($input);

        return $this->sendResponse($skill, "Skill Updated Successfully" );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $skill = Skill::findorFail($id);

       /*  if ($skill->has('user'))
        {
            $result = [];
            $message = "Cannot delete Skill,it contains users";
        }

        else 
        {
            $result = [];
            $message = "Skill Deleted Successfully";
            $skill->delete();

        } */

        $message = "Skill Deleted Successfully";
        $skill->delete();

        return $this->sendResponse([], $message );

    }
}
