<?php

namespace App\Http\Controllers;

use App\Mail\SendCodeResetPassword;
use App\Models\ResetCodePassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function __invoke(Request $request) {
        $data = $request->validate([
            'email' => 'required|email|exists:users',
        ]);

            ResetCodePassword::where('email', $request->email)->delete();

            $data['token'] = mt_rand(100000, 999999);
            $codeData = ResetCodePassword::create($data);
            Mail::to($request->email)->send(new SendCodeResetPassword($codeData->token));

            return response(['message' => trans('passwords.sent')], 200);


    }


}
