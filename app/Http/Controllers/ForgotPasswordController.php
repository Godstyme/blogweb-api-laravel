<?php

namespace App\Http\Controllers;

use App\Mail\SendCodeResetPassword;
use App\Models\ResetCodePassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function __invoke(Request $request) {
        try {
            $data = $request->validate([
                'email' => 'required|email|exists:users',
            ]);

                ResetCodePassword::where('email', $request->email)->delete();

                $data['token'] = mt_rand(100000, 999999);
                $expiringTime = Carbon::now()->addMinutes(2);
                // $a = DB::table( 'password_resets' )->create( $expiringTime);
                // return $expiringTime;
                $codeData = ResetCodePassword::create($data);
                Mail::to($request->email)->send(new SendCodeResetPassword($codeData->token, $codeData-> $expiringTime));

                return response()->json([
                    'status'=> true,
                    'message' => trans('passwords.sent')
                ], 200);
        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => "Operation failed",
                'error' => $th
            ], 500);
        }


    }


}
