<?php

namespace App\Http\Controllers;

use App\Models\ResetCodePassword;
use Illuminate\Http\Request;

class CodeCheckController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'token' => 'required|string|exists:password_resets',
        ]);

        // find the code
        $passwordReset = ResetCodePassword::firstWhere('token', $request->token);

        // check if it does not expired: the time is one hour
        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return response(['message' => trans('passwords.code_is_expire')], 422);
        }

        return response([
            'code' => $passwordReset->token,
            'message' => trans('passwords.code_is_valid')
        ], 200);
    }
}
