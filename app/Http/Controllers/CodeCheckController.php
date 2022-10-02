<?php

namespace App\Http\Controllers;

use App\Models\ResetCodePassword;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CodeCheckController extends Controller
{
    public function __invoke(Request $request)
    {
       try {
            $request->validate([
                'token' => 'required|string|exists:password_resets',
            ]);

            // find the code
            $passwordReset = ResetCodePassword::firstWhere('token', $request->token);

            // check if it does not expired: the time is one minutes
            $expiringTime = Carbon::now()->addMinutes(2);
            // return $a;
            if ($passwordReset->created_at > $expiringTime) {
                $passwordReset->delete();
                return response([
                    'status'=> false,
                    'message' => trans('passwords.code_is_expire')
                ], 422);
            }

            return response()->json([
                'status'=> true,
                'token' => $passwordReset->token,
                'timecreated' => $passwordReset->created_at,
                'currenttime'=> $expiringTime,
                'message' => trans('passwords.code_is_valid')
            ], 200);
       } catch (\Throwable $th) {
            return response()->json([
                'status'=> false,
                'message' => 'Token not found',
                // 'error'=>$th->validator->excludeUnvalidatedArrayKeys
            ], 404);
       }
    }
}
