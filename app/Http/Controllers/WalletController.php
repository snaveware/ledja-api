<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Wallet;
use App\Models\User;
use Validator;
use App\Http\Controllers\API\BaseController as BaseController;


class WalletController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wallets = Wallet::with(['user', 'transactions'])->paginate();
        return $this->sendResponse($wallets, "Wallets Fetched Successfully");
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
            'amount' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();
        $wallets = Wallet::create($input);

        return $this->sendResponse($wallets, "Wallet Created Successfully" );



    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $wallets = Wallet::with(['user', 'transactions'])->find($id);

        return $this->sendResponse($wallets, "Wallet Found Successfully" );


    }

    /**
     * Display the wallet for a user.
     */
    public function get_user_wallet(string $user_id)
    {
        // Get wallet  where user is user_id
        $user_wallet = Wallet::with('user')->where('user_id', $user_id)->get();
        // $user_wallet->user();

        return $this->sendResponse($user_wallet, "User Wallet Found Successfully" );

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
        $wallets = Wallet::find($id);

        $input = $request->all();
        $result = $wallets->update($input);

        return $this->sendResponse($wallets, "Wallet Updated Successfully" );



    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $wallets = Wallet::find($id);

        if ($wallets->has('user'))
        {
            $result = [];
            $message = "Cannot delete Wallet,it contains user";
        }

        else 
        {
            $result = [];
            $message = "Wallet Deleted Successfully";
            $wallets->delete();

        }


        return $this->sendResponse([], $message );

    }
}
