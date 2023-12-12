<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\PasswordResetLink;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class PasswordResetLinkController extends Controller
{
    //
    public function store(Request $request): RedirectResponse
    {
        $user = User::where('email', 'like', '%'.$request->email.'%')->first();
        // dd($request);
 
        // Ship the order...
 
        Mail::to($request->user())->send(new PasswordResetLink($user));
 
        return "done";
    }
}
