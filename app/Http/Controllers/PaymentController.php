<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Application;
use App\Models\Message;
use Validator;
use App\Http\Controllers\API\BaseController as BaseController;


class PaymentController extends BaseController
{
    public function receive_payments(Request $request)
    {
        dd($request);

    }
}
