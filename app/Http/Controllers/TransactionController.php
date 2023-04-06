<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Validator;
use App\Http\Controllers\API\BaseController as BaseController;


class TransactionController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::with(['user', 'wallet'])->paginate();
        return $this->sendResponse($transactions, "Transactions Fetched Successfully");
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
    public function transact(Request $request, string $user_id)
    {
        // validate fields
        $validator = Validator::make($request->all(), [
            // 'user_id' => 'required',
            // 'wallet_id' => 'required',
            'type' => 'required',
            'amount' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();
        $user = User::where('id', $user_id)->first();
        // dd($user->wallet->id);
        $wallet = Wallet::where('id', $user->wallet->id)->first();
        $input['user_id'] = $user->id;
        $input['wallet_id'] = $wallet->id;
        $transaction = Transaction::create($input);
        // dd(($transaction->type == 'Debit' || $transaction->type == 'debit') &&  $wallet->amount >= $transaction->amount);

        // edit wallet amount
        if(($transaction->type == 'Debit' || $transaction->type == 'debit') && $wallet->amount >= $transaction->amount)
        {
            $wallet->amount = $wallet->amount - $transaction->amount;
            $wallet->save();
        }

       
        else if($transaction->type == 'Credit' || $transaction->type == 'credit')
        {
            $wallet->amount = $wallet->amount + $transaction->amount;
            $wallet->save();
        }

        else
        {
            $transaction->delete();
            return $this->sendResponse([], "Error! Money in the wallet is less than transacting money" );

        }

        $trans = $transaction->wallet;

        return $this->sendResponse($transaction, "Transaction Created Successfully" );



    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $transaction = Transaction::with(['user', 'wallet'])->find($id);

        return $this->sendResponse($transaction, "Transaction Found Successfully" );



    }



    public function get_transaction(string $user_id)
    {
        $transaction = Transaction::with(['user', 'wallet'])->where('user_id', $user_id)->get();

        return $this->sendResponse($transaction, "Transaction Found Successfully" );

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
        $transaction = Transaction::find($id);

        $input = $request->all();
        $result = $transaction->update($input);

        return $this->sendResponse($transaction, "Transaction Updated Successfully" );



    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $transaction = Transaction::find($id);

        if ($transaction->has('user'))
        {
            $result = [];
            $message = "Cannot delete Transaction,it contains user";
        }

        else 
        {
            $result = [];
            $message = "Transaction Deleted Successfully";
            $transaction->delete();

        }


        return $this->sendResponse([], $message );

    }
}
