<?php

namespace App\Http\Controllers;

use App\Models\ResetCodePassword;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required|string|exists:password_resets',
                'password' => 'required|string|min:6|confirmed',
            ]);

            // find the code
            $passwordReset = ResetCodePassword::firstWhere('token', $request->token);


            // check if it does not expired: the time is one hour
            $expiringTime = Carbon::now()->addMinutes(2);
            if ($passwordReset->created_at > $expiringTime) {
                $passwordReset->delete();
                return response()->json([
                    'status' => false,
                    'message' => trans('passwords.code_is_expire')
                ], 422);
            }

            // find user's email
            $user = User::firstWhere('email', $passwordReset->email);

            $password =  $request->only('password');
            function ($user, $password) {
               return $user->forceFill([
                    'password' => Hash::make($password)]);
            };
            // update user password
            return Hash::make($password);
            // $result = $user->update($password);

            // delete current code
             $passwordReset->delete();
            // if ( $result) {
            //     return response()->json([
            //         'message' =>'password has been successfully reset'
            //     ], 201);
            // } else {
            //     return response()->json([
            //         'message' =>'Operation failed'
            //     ], 400);
            // }


        } catch (\Throwable $th) {
            return response()->json([
                "status"=>false,
                'password' =>  $user->password,
                "message" => 'Operation failed, Something went wrong',
                // 'error'=>$th
            ],500);
        }

    }
}
