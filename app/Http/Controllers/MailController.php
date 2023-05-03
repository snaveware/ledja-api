<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Mail\ResetPassword;
use App\Models\User;

class MailController extends Controller {
   public function store(Request $request)
   {
      // get user for that email if user exists
      $user = User::where('email', $request->email)->first();
      // get code


      if($user != null)
      {
         // send email with otp
         // Mail to user
         Mail::to($request->email)->send(new ResetPassword($request));
      }
      
      return response()->json([
         'success' => true,
         'data' => "An email has been sent to you if the email exists in our system",
         'status_code' => 200,
      ]);
   }

   public function basic_email() {
      $data = array('name'=>"Virat Gandhi");

      echo "*** Sending Email ***";
   
      Mail::send(['text'=>'mail'], $data, function($message) {
         $message->to('abc@gmail.com', 'Tutorials Point')->subject
            ('Laravel Basic Testing Mail');
         $message->from('bonyeza.music@gmail.com','Ledja Inc.');
      });
      echo "Basic Email Sent. Check your inbox.";
   }
   public function html_email() {
      $data = array('name'=>"Virat Gandhi");
      Mail::send('mail', $data, function($message) {
         $message->to('abc@gmail.com', 'Tutorials Point')->subject
            ('Laravel HTML Testing Mail');
         $message->from('bonyeza.music@gmail.com','Virat Gandhi');
      });
      echo "HTML Email Sent. Check your inbox.";
   }
   public function attachment_email() {
      $data = array('name'=>"Virat Gandhi");
      Mail::send('mail', $data, function($message) {
         $message->to('abc@gmail.com', 'Tutorials Point')->subject
            ('Laravel Testing Mail with Attachment');
         $message->attach('C:\laravel-master\laravel\public\uploads\image.png');
         $message->attach('C:\laravel-master\laravel\public\uploads\test.txt');
         $message->from('bonyeza.music@gmail.com','Virat Gandhi');
      });
      echo "Email Sent with attachment. Check your inbox.";
   }
}