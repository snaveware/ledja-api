<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Question;
use Validator;
use App\Http\Controllers\API\BaseController as BaseController;


class QuestionController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = Question::with(['user','skills_assessment','answers','results'])->latest()->paginate();
        return $this->sendResponse($questions, "Questions Fetched Successfully");
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
            'content' => 'required',
            'choice_a' => 'required',
            'choice_b' => 'required',
            'choice_c' => 'required',
            'choice_d' => 'required',
            'marks' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();
        $question = Question::create($input);

        return $this->sendResponse($question, "Question Created Successfully" );



    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $question = Question::with(['user','skills_assessment','answers','results'])->find($id);

        return $this->sendResponse($question, "Question Found Successfully" );


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
        $question = Question::find($id);

        $input = $request->all();
        $result = $question->update($input);

        return $this->sendResponse($question, "Question Updated Successfully" );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $question = Question::find($id);

        if ($question->has('user'))
        {
            $result = [];
            $message = "Cannot delete Question,it contains users";
        }

        else 
        {
            $result = [];
            $message = "Question Deleted Successfully";
            $question->delete();

        }


        return $this->sendResponse([], $message );

    }
}
