<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\UserType;
use Validator;
use App\Http\Controllers\API\BaseController as BaseController;


class UserTypeController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_types = UserType::with('users')->latest()->paginate();
        return $this->sendResponse($user_types, "User Types Fetched Successfully");
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
            'name' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();
        $user_type = UserType::create($input);

        return $this->sendResponse($user_type, "User Type Created Successfully" );



    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $user_type = UserType::with('users')->find($id);

        return $this->sendResponse($user_type, "User Type Found Successfully" );



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
        $user_type = UserType::find($id);

        $input = $request->all();
        $result = $user_type->update($input);

        return $this->sendResponse($user_type, "User Type Updated Successfully" );



    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $user_type = UserType::find($id);

        if ($user_type->has('users'))
        {
            $result = [];
            $message = "Cannot delete user type,it contains users";
        }

        else 
        {
            $result = [];
            $message = "User Type Deleted Successfully";
            $user_type->delete();

        }


        return $this->sendResponse([], $message );

    }
}
