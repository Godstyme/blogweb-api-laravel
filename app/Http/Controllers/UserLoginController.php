<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserLoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * API Login, on success return JWT Auth token
     * Login a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            $validateUser = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);
            if($validateUser->fails()){
                $response = response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 422);
            } else {
                if (!Auth::attempt($credentials)) {
                    $response = response()->json([
                        'status' => false,
                        'message' => 'Invalid credentials,
                        Please make sure you entered the right information and you have verified your email address.',
                    ], 404);
                } else {
                   if (Auth::user()->is_verified == 0) {
                        $response = response()->json([
                            'status' => false,
                            'message' => 'Unauthorized Login, Please verify your account',
                        ], 401);
                    } else {
                        $token = Auth::attempt($credentials);
                        $user = Auth::user();
                        $response = response()->json([
                            'status' => true,
                            'user' => $user,
                            'message'=> "Login successful",
                            'authorisation'=> [
                                'token' => $token,
                                'type' => 'bearer'
                                ]
                        ], 200);
                    }

                }
            }

        } catch (\Throwable $th) {
            $response = response()->json([
                'status' => false,
                'message' => 'Failed to login, please try again.',
                'error'=>$th->getMessage()
            ], 500);
        }
        return $response;
    }

    public function refresh()
    {
        try {
            $response =  response()->json([
                'status' => true,
                'user' => Auth::user(),
                'authorisation' => [
                    'token' => Auth::refresh(),
                    'type' => 'bearer',
                ]
            ],200);
        } catch (\Throwable $th) {
            $response = response()->json([
                'status' => false,
                'error'=>$th->getMessage()
            ], 500);
        }
        return $response;
    }

    public function logout(Request $request) {
        $this->validate($request, ['token' => 'required']);

        try {
            Auth::invalidate($request->input('token'));
            return response()->json([
                'status' => true,
                'message'=> "You have successfully logged out."
            ],200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to logout, please try again.',
                'error'=>$e->getMessage()
            ], 500);
        }
    }

}
