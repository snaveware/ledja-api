<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\SkillsAssessment;
use App\Models\Job;
use Validator;
use App\Http\Controllers\API\BaseController as BaseController;


class SkillsAssessmentController   extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $skill_assessments = SkillsAssessment::with(['user','jobs','questions','scores','results'])->paginate();
        return $this->sendResponse($skill_assessments, "SkillAssessments Fetched Successfully");
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
            'title' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();
        $skill_assessment = SkillsAssessment::create($input);

        return $this->sendResponse($skill_assessment, "SkillsAssessment  Created Successfully" );



    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $skill_assessment = SkillsAssessment ::with(['user','jobs','questions','scores','results'])->find($id);

        return $this->sendResponse($skill_assessment, "SkillsAssessment  Found Successfully" );

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
        $skill_assessment = SkillsAssessment ::find($id);

        $input = $request->all();
        $result = $skill_assessment->update($input);

        return $this->sendResponse($skill_assessment, "SkillsAssessment  Updated Successfully" );

    }

     /**
     * Filter assessments
     */
    public function filter_assessments(Request $request)
    {
        // validate fields
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable',
            'job_id' => 'nullable',
            'title' => 'nullable',
        ]);

        /*   if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        } */

        $input = $request->all();

        // Filter jobs by field
        if(empty($input['user_id']))
        {
            $input['user_id'] = \Auth::user()->id;
        }

        if(empty($input['title']))
        {
            $input['title'] = "";
        }

        $is_present = false;

        // Get all assessments for a particular job
        if(!empty($input['job_id']))
        {
            // Get the assessment for the job_id
            $job = Job::with('skills_assessment')->findorFail($input['job_id']);
            $assessment = SkillsAssessment::with(['user', 'jobs', 'questions', 'scores', 'results'])->findorFail($job->skills_assessment->id)->get();

            return $this->sendResponse($assessment, "Assessments Fetched Successfully" );
        }


        $assessments = SkillsAssessment::with('jobs')->userId($input['user_id'])
        ->title($input['title'])
        ->get();
      
        $filter_assessments = [];
        foreach($assessments as $assessment)
        {
            // Get the recruiter basic info for each user
            $assessment->job_seeker_basic_info = $assessment->user->basic_info_jobseeker;
        }

        return $this->sendResponse($assessments, "Assessments Fetched Successfully" );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $skill_assessment = SkillsAssessment ::find($id);

        /* if ($skill_assessment->has('user'))
        {
            $result = [];
            $message = "Cannot delete SkillsAssessment ,it contains users";
        }

        else 
        {
            $result = [];
            $message = "SkillsAssessment  Deleted Successfully";
            $skill_assessment->delete();

        } */

        $skill_assessment->delete();
        $message = "SkillsAssessment  Deleted Successfully";

        return $this->sendResponse([], $message );

    }
}
