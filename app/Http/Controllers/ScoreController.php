<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Score;
use App\Models\Result;
use App\Models\Question;
use App\Models\SkillsAssessment;
use Validator;
use App\Http\Controllers\API\BaseController as BaseController;
 

class ScoreController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $scores = Score::with(['user', 'skill_assessment'])->paginate();
        return $this->sendResponse($scores, "Scores Fetched Successfully");
    }

    public function get_score($user_id, $test_id)
    {
        // get the test in question from the jobseeker's result
        $result = Result::where('user_id', $user_id)->first();
        $test = SkillsAssessment::where('id', $result->skills_assessment_id)->first();
        // check if user has a score for that test
        $score = Score::with(['user', 'skills_assessment'])->where('user_id', $user_id)->where('skills_assessment_id', $test_id)->first();
        // dd($score);
        // fetch score if user has score
        if(!is_null($score))
        {
            return $this->sendResponse($score, "Score for user fetched successfully" );
        }
        // create score if user doesn't have a score
        $score = Result::where('user_id', $user_id)->where('skills_assessment_id', $test_id)->sum('points');
        // get rating from total points for the question
        $points = Question::where('skills_assessment_id', $test_id)->sum('marks');
        $rating = (int)$score / (int) $points ;
        $data = [
            'skills_assessment_id' => $test_id,
            'user_id' => $user_id,
            'score' => $score,
            'rating' => $rating
        ];

        $user_score = Score::create($data);
        $user_score->user();
        $user_score->skills_assessment();

        return $this->sendResponse($user_score, "Score for user fetched successfully" );

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
            'skills_assessment_id' => 'required',
            'user_id' => 'required',
            'score' => 'required',
            'rating' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();
        $score = Score::create($input);

        return $this->sendResponse($score, "Score Created Successfully" );

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $score = Score::with(['user', 'skill_assessment'])->find($id);

        return $this->sendResponse($score, "Score Found Successfully" );


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
        $score = Score::find($id);

        $input = $request->all();
        $result = $score->update($input);

        return $this->sendResponse($score, "Score Updated Successfully" );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $score = Score::find($id);

        if ($score->has('user'))
        {
            $score = [];
            $message = "Cannot delete Score,it contains users";
        }

        else 
        {
            $score = [];
            $message = "Score Deleted Successfully";
            $score->delete();

        }


        return $this->sendResponse([], $message );

    }
}
