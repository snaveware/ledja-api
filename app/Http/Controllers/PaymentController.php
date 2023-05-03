<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Notifications\PaymentIntiated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Application;
use App\Models\Message;
use App\Models\Transaction;
use App\Models\User;
use Validator;
use App\Http\Controllers\API\BaseController as BaseController;
use GuzzleHttp\Client;
use Flutterwave\Flutterwave;
use Illuminate\Support\Facades\Notification;





class PaymentController extends BaseController
{
    // public function __construct($notifiable)
    // {
    //     $this->notifiable = new VonageMessage();
    // }

    public function success()
    {
        return view('payments.success');
    }

    public function intiate_payment(Request $request)
    {
        /* 
        PAYLOAD
           "card_number":"5531886652142950",
            "cvv":"564",
            "expiry_month":"09",
            "expiry_year":"32",
            "currency":"KES",
            "amount":"100",
            "fullname":"Talmon Mwakesi",
            "phone_number": "254272136485",
            "email":"talimwakesi@gmail.com",
            "redirect_url":"https://b987-105-163-158-25.ngrok-free.app/api/receive_payments",

            "authorization": {
                "mode": "pin", 
                "pin": "1234"
            }
        */
        // $notifiable = new VonageMessage;
        // $this->toVonage()->content('asfasfas');

      
  
        $user = \Auth::user();
        $payload = $request->all();

        // Set new Transaction ref
        $payload['tx_ref'] = 'LDJ-'.uniqid();
        try {
            $response =  $this->charge_customer($payload, 'https://api.flutterwave.com/v3/charges?type=card');

        }

        catch (\GuzzleHttp\Exception\ClientException $e)
        {
            dd($e);
            return response()->json([
                'success' => false,
                'error' => 'Something went wrong!,please contact your bank for further details'
            ]);
        }

        $data = json_decode($response->getBody()->getContents());

        if($response->getStatusCode() == 200)
        {

            $wallet = $user->wallet;

            $pin = mt_rand(1111,9999);

            $transaction_payload = [
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'trx_ref' => $payload['tx_ref'],
                'payment_mode' => 'card',
                'trx_otp' => $pin,
                'mobile_no' => $request->phone_number,
                'trx_status' => 'pending authorization',
            ];

            $user_transaction = Transaction::create($transaction_payload);

            if(!empty($user_transaction))
            {
                    $data->user_transaction = $user_transaction;
                    // Add transaction to table
                    // Send OTP to user and store it
                    $message = "Your One Time Pin is {$pin}.Please do not share this with anyone.";
                    // Notification::sendNow($user, new PaymentIntiated());
                    $user_transaction->notify(new PaymentIntiated($user_transaction));

            }
            
            // Return success message
            return response()->json([
                'data' => $data,
                'status_code' => 200
            ]); 
        }

        else
        {
            // Return error message
            return response()->json([
                'data' => $data,
                'status_code' => $response->getStatusCode()
            ]); 
        }
        
    }


    public function create_payment(Request $request)
    {
        /* 
        PAYLOAD
           "card_number":"5531886652142950",
            "cvv":"564",
            "expiry_month":"09",
            "expiry_year":"32",
            "currency":"KES",
            "amount":"100",
            "fullname":"Talmon Mwakesi",
            "phone_number": "254272136485",
            "email":"talimwakesi@gmail.com",
            "redirect_url":"https://b987-105-163-158-25.ngrok-free.app/api/receive_payments",

            "authorization": {
                "mode": "pin", 
                "pin": "1234"
            }
        */
        // $notifiable = new VonageMessage;
        // $this->toVonage()->content('asfasfas');

      
  
        $user = \Auth::user();
        $payload = $request->all();

        // Set new Transaction ref
        $payload['tx_ref'] = 'LDJ-'.uniqid();
        // dd($payload);
        $response =  $this->charge_customer($payload, 'https://api.flutterwave.com/v3/payments' );
        /* try {
            $response =  $this->charge_customer($payload, 'https://api.flutterwave.com/v3/payments' );

        }

        catch (\GuzzleHttp\Exception\ClientException $e)
        {
            dd($e);
            return response()->json([
                'success' => false,
                'error' => 'Something went wrong!,please contact your bank for further details'
            ]);
        } */

        $data = json_decode($response->getBody()->getContents());

        if($response->getStatusCode() == 429)
        {
            Log::info("*** RATE LIMIT EXCEEDED ***");
            // Return success message
            return response()->json([
                'data' => 'Too many requests,try again after 5 minutes please.',
                'status_code' => 429
            ]); 
        }

        if($response->getStatusCode() == 200)
        {
            Log::info("*** TRANSACTION BEGINS ***");
            // $wallet = $user->wallet;

            // $pin = mt_rand(1111,9999);

          /*   $transaction_payload = [
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'trx_ref' => $payload['tx_ref'],
                'payment_mode' => $payload['payment_mode'],
                // 'trx_otp' => $pin,
                'mobile_no' => $payload['customer']['phonenumber'],
                'trx_status' => 'pending authorization',
            ]; */

            // $user_transaction = Transaction::create($transaction_payload);

         /*    if(!empty($user_transaction))
            {
                    $data->user_transaction = $user_transaction;
                    // Add transaction to table
                    // Send OTP to user and store it
                    $message = "Your One Time Pin is {$pin}.Please do not share this with anyone.";
                    // Notification::sendNow($user, new PaymentIntiated());
                    $user_transaction->notify(new PaymentIntiated($user_transaction));

            } */
            
            // Return success message
            return response()->json([
                'data' => $data,
                'status_code' => 200
            ]); 
        }

        else
        {
            Log::error("*** TRANSACTION ERROR ***");

            // Return error message
            return response()->json([
                'data' => $data,
                'status_code' => $response->getStatusCode()
            ]); 
        }
        
    }

    public function authorize_charge(Request $request)
    {

        $user = \Auth::user();
        $payload = $request->all();

        $transaction = Transaction::where('user_id', $user->id)->latest()->first();
        $request_otp = $payload['authorization']['pin'];
        $actual_otp = $transaction->trx_otp;
        // dd($payload['authorization']);
        // Check if OTP is a match
        if($request_otp != $actual_otp)
        {
            // Return error message
            return response()->json([
                'data' => 'Invalid Credentials!',
                'status_code' => 200
            ]); 
        }
        // Set new Transaction ref
        $payload['tx_ref'] = $transaction->trx_ref;
        $response = $this->charge_customer($payload, 'https://api.flutterwave.com/v3/charges?type=card');
        $data = json_decode($response->getBody()->getContents());

         // Return success message
        //  return response()->json([
        //     'data' => $data,
        //     'status_code' => 200
        // ]); 



        if($response->getStatusCode() == 200)
        {

            
            $transaction->trx_id = $data->data->id;
            $transaction->trx_status = $data->data->status;
            $transaction->save();            
            // Return success message
            return response()->json([
                'data' => $data,
                'status_code' => 200
            ]); 
        }

        else
        {
            // Return error message
            return response()->json([
                'data' => $data,
                'status_code' => $response->getStatusCode()
            ]); 
        }

    }

    public function stk_push(Request $request)
    {
        // get access token

        $payload = $request->all();
        $response = $this->charge_customer($payload, 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest');

        $token = env('SECRET_KEY');
        $headers = ['Authorization' => 'Bearer '. $token];
        $client = new Client([
            'headers' => $headers
        ]);

        $key = env('ENCRYPTION_KEY');



        $encrypted_payload = $this->encrypt($key, $payload);

        // dd($encrypted_payload);

       

        $response = $client->request('POST', $base_uri, [
            'json' => [ 'client' => $encrypted_payload]
        ]);




    }


    public function receive_payments(Request $request)
    {
        /* 
        **** SUCCESSFUL MPESA

        {
            "event": "charge.completed",
            "data": {
            "id": 1191376,
            "tx_ref": "MC-15852113s09v5050e8",
            "flw_ref": "0379962762",
            "device_fingerprint": "N/A",
            "amount": 1500,
            "currency": "KES",
            "charged_amount": 1500,
            "app_fee": 43.5,
            "merchant_fee": 0,
            "processor_response": "Successful",
            "auth_model": "LIPA_MPESA",
            "ip": "::ffff:10.45.226.51",
            "narration": "FLW-PBF MPESA Transaction ",
            "status": "successful",
            "payment_type": "mpesa",
            "created_at": "2020-03-27T15:46:37.000Z",
            "account_id": 74843,
            "meta": null,
            "customer": {
            "id": 349271,
            "phone_number": "25454709929220",
            "name": "Anonymous Customer",
            "email": "i@need.money",
            "created_at": "2020-03-27T15:46:13.000Z"
            }
    }    
       
        */


        /* 
        SUCCESSFUL CARD PAYMENT

        {
            "event": "charge.completed",
            "data": {
                "id": 285959875,
                "tx_ref": "Links-616626414629",
                "flw_ref": "PeterEkene/FLW270177170",
                "device_fingerprint": "a42937f4a73ce8bb8b8df14e63a2df31",
                "amount": 100,
                "currency": "NGN",
                "charged_amount": 100,
                "app_fee": 1.4,
                "merchant_fee": 0,
                "processor_response": "Approved by Financial Institution",
                "auth_model": "PIN",
                "ip": "197.210.64.96",
                "narration": "CARD Transaction ",
                "status": "successful",
                "payment_type": "card",
                "created_at": "2020-07-06T19:17:04.000Z",
                "account_id": 17321,
                "customer": {
                "id": 215604089,
                "name": "Yemi Desola",
                "phone_number": null,
                "email": "user@gmail.com",
                "created_at": "2020-07-06T19:17:04.000Z"
                },
                "card": {
                "first_6digits": "123456",
                "last_4digits": "7889",
                "issuer": "VERVE FIRST CITY MONUMENT BANK PLC",
                "country": "NG",
                "type": "VERVE",
                "expiry": "02/23"
                }
            }
        }
          
        */
        // unpack the request via  a webhook
        // update wallet with the amount paid
        $incoming_data = $request->all();
        if($request->header('verif-hash') == env('SECRET_HASH'))
        {
            Log::info("HASH CHECK COMPLETE");
            Log::info('***** INSIDE WEBHOOK *****');
            Log::info('PAYLOAD INCOMING -->> ', [
                'data' => $request->all()
            ]);

            $email = $incoming_data['data']['customer']['email'];
            $status = $incoming_data['data']['status'];
            $amount = $incoming_data['data']['amount'];
            $mobile_no = $incoming_data['data']['customer']['phone_number'];
            $tx_id = $incoming_data['data']['id'];
            $tx_ref = $incoming_data['data']['tx_ref'];
            $payment_mode = $incoming_data['data']['payment_type'];
            $error_message = $incoming_data['data']['processor_response'];
            $user = User::where('email', $email)->first();
            $wallet = $user->wallet;


            if($status == 'successful')
            {
                // Create Transaction
                $transaction_payload = [
                    'payment_mode' => $payment_mode,
                    'user_id' => $user->id,
                    'wallet_id' => $wallet->id,
                    'trx_id' => $tx_id,
                    'trx_ref' => $tx_ref,
                    'mobile_no' => $mobile_no,
                    'trx_status' => $status,
                    'type' => 'credit',
                    'amount' => $amount,
                ];

                $transaction = Transaction::create($transaction_payload);

                // Verfiy the transaction
                $data = $this->verify_payment($transaction->trx_id);

                Log::info("*** SUCCESS *** ", [
                    'data' => $data
                ]);
            }

            if($status == 'failed')
            {
                Log::error('### PAYMENT ERROR ### ', ['error' => $error_message]);
                return response()->json([
                    'success' => false,
                    'data' => [],
                    'error' => $error_message,
                ], 200);
            }
        }
       
        // echo "**** Inside webhook **** <br>";
        // print_r($request->all());
        // $json_output = json_decode($request, true);
        // echo "THE PAYLOAD IS --->> $json_output <br>";
        // echo "VERIFYING TRANSACTION NOW.... <br>";
        // $this->verify_payment($request->data->trx_ref);


        // add values to transaction table
        /* $user = \Auth::user();
        $wallet = $user->wallet;
        $webhook_data = $request->all();
        $trx_data = [
            'user_id' => $user->id,
            'wallet_id' => $wallet->id,
            'mobile_no' => $webhook_data->data->phone_number,
            'trx_id' => $webhook_data->data->id,
            'trx_ref' => $webhook_data->data->tx_ref,
            'trx_status' => $webhook_data->data->status,
            'payment_mode' => $webhook_data->data->payment_type,
            'user_id' => $user->id,
        ];

        $transaction = Transaction::create($trx_data);
        $wallet->amount = $webhook_data->data->amount();
        $wallet->save();  */

        return response()->json([
            'data' => $data
        ]);

    }


    public function verify_payment(string $transaction_id)
    {
        Flutterwave::bootstrap();
        $transactions = new \Flutterwave\Service\Transactions();
        Log::info('*** NOW VERIFYING PAYMENT ');

        
        $response = $transactions->verify($transaction_id);
        // dd($response);

        if ( $response->data->status === "successful"
            && $response->data->currency === 'KES') 
        {
            // Success! Confirm the customer's payment
            // Change status to successful in transaction
            $transaction = Transaction::where('trx_id', $transaction_id)->first();
            // dd($transaction);

            if(!empty($transaction))
            {

                if($transaction->trx_status == 'successful')
                {
                    Log::info("*** TRANSACTION WITH ID ${transaction_id} ALREADY EXISTS ***");
                    return response()->json([
                        'data' => 'Transaction has already been verified,please verify another transaction!',
                        'status_code' => 200
                    ]);
                }
                $transaction->trx_status = $response->data->status;
                $transaction->amount = $response->data->amount;
                $transaction->save();

                // Add amount to wallet
                $wallet = $transaction->wallet;
                $wallet->amount += $response->data->amount_settled;
                $wallet->save();

                $transaction_amount = $transaction->amount;
                $wallet_amount = $wallet->amount;

                Log::info("*** TRANSACTION WITH ID ${transaction_id}  HAS BEEN VERIFIED ***");
                Log::info("*** WALLET CREDITED WITH ${transaction_amount} *** ");
                Log::info("*** NEW WALLET AMOUNT IS ${wallet_amount} *** ");


                $response->wallet = $wallet;

                Log::info('PAYLOAD RETURNED -->> ', [
                    'data' => $response
                ]);

                return response()->json([
                    'data' => $response,
                    'status_code' => 200
                ]);

            }

            else
            {
                return response()->json([
                    'success' => false,
                    'data' => [],
                    'message' => 'Transaction does not exist!'
                ]);

            }

            
        } 
        
        else {
            // Inform the customer their payment was unsuccessful
            return response()->json([
                'data' => 'Transaction failed,contact your bank for more details',
                'status_code' => 200
            ]);

        }

        

    }

    public function encrypt(string $encryptionKey, array $payload)
    {
        $encrypted = openssl_encrypt(json_encode($payload), 'DES-EDE3', $encryptionKey, OPENSSL_RAW_DATA);
        return base64_encode($encrypted);
        return  response()->json([
            "client" => base64_encode($encrypted)
        ]);
    }


    public function charge_customer($payload, $base_uri)
    {

        $token = env('SECRET_KEY');
        $headers = [
            'Authorization' => 'Bearer '. $token,
            // 'Content-Type' => 'application/json'
        ];
        $client = new Client([
            'headers' => $headers
        ]);

        $key = env('ENCRYPTION_KEY');



        // dd($payload);
        // $encrypted_payload = $this->encrypt($key, $payload);

        // dd($request);

       try
       {

        $response = $client->request('POST', $base_uri, [
            // 'json' => [ 'client' => $payload]
            'json' => $payload 
        ]);

       }

       catch (\Guzzle\Http\Exception\BadResponseException $e)
       {
            return $e;
       }

        


        return $response;
        // $data = json_decode($response->getBody()->getContents());

        // return response()->json([
        //     'data' => $data,
        //     'status_code' => 200
        // ]);
    }

    public function mpesa_pay(Request $request)
    {
        // Install with: composer require flutterwavedev/flutterwave-v3
        $user = \Auth::user();
        $wallet = $user->wallet;

        $mpesaService = new \Flutterwave\Service\Mpesa();
        $payloadService = new \Flutterwave\Service\Payload();
       /*  $payload = [
            "phone_number" => $request->phone_number,
            "amount" => $request->amount,
            "currency" => 'KES',
            "email" => $user->email,
            "fullname" => $request->fullname,
            "tx_ref" => 
        ]; */

        // dd($request->all());

        // $response = $mpesaService($payload);
        // dd($response);
        $payload = $request->all();
        $payload['tx_ref'] = 'LDJ-'.uniqid();
        // dd($payload);

        $response = $this->mpesa_safcom($payload, 'https://api.flutterwave.com/v3/charges?type=mpesa'); 
        $res = json_decode($response->getBody());
        // dd($res);


        if($res->status == 'success')
        {
            // Success! Confirm the customer's payment
            // Create Transaction
            $amount_credited = $res->data->charged_amount - $res->data->app_fee;
            // dd($amount_credited);

            $transaction_payload = [
                'type' => 'credit',
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'trx_ref' => $res->data->tx_ref,
                'payment_mode' => 'mpesa',
                'mobile_no' => $res->data->customer->phone_number,
                'trx_status' =>  $res->data->status,
                'amount' => $amount_credited,
            ];

            $transaction = Transaction::create($transaction_payload);

            // Add amount to wallet
            $wallet = $transaction->wallet;
            $wallet->amount += $transaction->amount;
            $wallet->save();
            $res->wallet = $wallet;
        }

        return response()->json([
            'data' => $res,
            'status_code' => 200
        ]);

        
    }


    public function mpesa_safcom($payload, $base_uri, $token = "")
    {
          
    
            $token = env('SECRET_KEY');
            if ($token != "")
            {
                $headers = ['Authorization' => 'Bearer '. $token];
                $client = new Client([
                    'headers' => $headers
                ]);
            }
           
    
            $key = env('ENCRYPTION_KEY');
    
    
    
            // $encrypted_payload = $this->encrypt($key, $payload);
    
            // dd($encrypted_payload);
    
           
    
            $response = $client->request('POST', $base_uri, [
                // 'json' => [ 'client' => $encrypted_payload]
                'json' =>  $payload
            ]);
    
    
            return $response;
            
    
    }

    
}
