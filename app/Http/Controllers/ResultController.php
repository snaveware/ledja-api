<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Result;
use App\Models\Answer;
use App\Models\Question;
use Validator;
use App\Http\Controllers\API\BaseController as BaseController;


class ResultController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $results = Result::with(['user','skills_assessment','question'])->latest()->paginate();
        return $this->sendResponse($results, "Results Fetched Successfully");
    }

    public function get_result($user_id, $test_id)
    {
        // get the test in question from the jobseeker's result
        $result = Result::with(['skills_assessment', 'user'])->where('user_id', $user_id)->where('skills_assessment_id', $test_id)
        ->latest()
        ->get();
        
        return $this->sendResponse($result, "Results for user fetched successfully" );

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
            'question_id' => 'required',
            'answer' => 'required',
        ]);
        // dd('here');

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();
        // job seeker gives answer for the question
        // system checks if answer is correct
        // system adds points for the question to the user
        // system stores the answer for the user
        $correct_answer = Answer::where('question_id',$input['question_id'])->first();
        $question = Question::where('id', $input['question_id'])->first();
        if($input['answer'] == $correct_answer->correct_answer)
        {
            $input['is_answer_correct'] = true;
            $input['points'] = $question->marks;
        }

        else
        {
            $input['is_answer_correct'] = false;
            $input['points'] = 0;
        }
        
        $result = Result::create($input);

        return $this->sendResponse($result, "Result Created Successfully" );



    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $result = Result::with(['user','skill_assessment','question'])->find($id);

        return $this->sendResponse($result, "Result Found Successfully" );


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
        $result = Result::find($id);

        $input = $request->all();

        // job seeker gives answer for the question
        // system checks if answer is correct
        // system adds points for the question to the user
        // system stores the answer for the user
        $correct_answer = Answer::where('question_id',$result->question_id)->first();
        $question = Question::where('id', $result->question_id)->first();
        if($input['answer'] == $correct_answer->correct_answer)
        {
            $input['is_answer_correct'] = true;
            $input['points'] = $question->marks;
        }

        else
        {
            $input['is_answer_correct'] = false;
            $input['points'] = 0;
        }
        $my_result = $result->update($input);

        return $this->sendResponse($result, "Result Updated Successfully" );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $result = Result::find($id);

        if ($result->has('user'))
        {
            $result = [];
            $message = "Cannot delete Result,it contains users";
        }

        else 
        {
            $result = [];
            $message = "Result Deleted Successfully";
            $result->delete();

        }


        return $this->sendResponse([], $message );

    }
}
