<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Answer;
use Validator;
use App\Http\Controllers\API\BaseController as BaseController;


class AnswerController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $answers = Answer::with(['user','question'])->paginate();
        return $this->sendResponse($answers, "Answers Fetched Successfully");
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
            'question_id' => 'required',
            'correct_answer' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();
        $answer = Answer::create($input);

        return $this->sendResponse($answer, "Answer Created Successfully" );



    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $answer = Answer::with(['user'])->find($id);

        return $this->sendResponse($answer, "Answer Found Successfully" );


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
        $answer = Answer::find($id);

        $input = $request->all();
        $result = $answer->update($input);

        return $this->sendResponse($answer, "Answer Updated Successfully" );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $answer = Answer::find($id);

        if ($answer->has('user'))
        {
            $result = [];
            $message = "Cannot delete Answer,it contains users";
        }

        else 
        {
            $result = [];
            $message = "Answer Deleted Successfully";
            $answer->delete();

        }


        return $this->sendResponse([], $message );

    }
}
