<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Score;
use App\Models\Result;
use App\Models\Question;
use App\Models\User;
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

    public function calculate_scores($test_id)
    {
        // Get all user_ids for the test
        $user_ids = Result::distinct('user_id')
        ->pluck('user_id');

        foreach($user_ids as $user_id)
        {
            // check if user already has score
            $score = Score::with(['user', 'skills_assessment'])->where('user_id', $user_id)
            ->where('skills_assessment_id', $test_id)
            ->first();

            if(is_null($score))
            {
                 // calculate score per user
                $score = Result::where('user_id', $user_id)
                ->where('skills_assessment_id', $test_id)
                ->sum('points');

                 // get rating from total points for the question
                $out_of = Question::where('skills_assessment_id', $test_id)->sum('marks');

                // get rank of the user out of the total no of people
                // get all the scores for all the users
                // arrange them 


                $data = [
                    'skills_assessment_id' => $test_id,
                    'user_id' => $user_id,
                    'score' => $score,
                    'out_of' => $out_of   
                ];

                $user_score = Score::create($data);
                $user_score->user();
                $user_score->skills_assessment();
            }
        }

        // Get rank of all users
        // Iterate over all scores
        // Fetching all scores
        // Arrange them in order of descending scores
        $sorted_user_scores = Score::with(['user', 'skills_assessment'])
        ->where('skills_assessment_id', $test_id)
        ->orderBy('score', 'desc')
        ->get();
        // Rank according to index out of total elements in the collection
        $values = $sorted_user_scores->values();
        $no_of_candidates = $sorted_user_scores->count();

        foreach($sorted_user_scores as $key => $user_final_score)
        {
            $rank = $key + 1;
            $user_final_score->rank = "{$rank} / {$no_of_candidates}";
            $user_final_score->update();

            // Add basic infos and any other thing
            $user = User::with(['user_type',
                            'basic_info_jobseeker',
                            'basic_info_recruiter',
                            'job_seeker_link',
                            'recruiter_link',
                            'about_job_seeker',
                            'about_recruiter',
                            'more_about_recruiter',
                            'upload_job',
                            'wallet',
                            'work_experiences',
                            'skills',
                            'education',
                            'saved_jobs',
            ])->find($user_final_score->user_id);

            $user_final_score->jobseeker = $user;


        }


        return $this->sendResponse($sorted_user_scores, "Collection fetched successfully" );


    }

    public function get_score($user_id, $test_id)
    {
        // Get all user ids
        // check if user has a score for that test
        $score = Score::with(['user', 'skills_assessment'])->where('user_id', $user_id)->where('skills_assessment_id', $test_id)->first();
        // dd($score);
        // fetch score if user has score

        return $this->sendResponse($score, "Score for user fetched successfully" );

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
