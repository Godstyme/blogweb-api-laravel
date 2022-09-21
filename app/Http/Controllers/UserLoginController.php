<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
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
                // $credentials['is_verified'] = 1;
                if ($credentials['is_verified'] = 0) {
                    $response = response()->json([
                        'status' => false,
                        'message' => 'This account has noyt been verified',
                    ], 422);
                } else {
                    # code...
                }

                // try {
                //     $token = JWTAuth::attempt($request->only($credentials));
                //     if (! $token ) {
                //         $response = response()->json([
                //         'status' => false,
                //         'message' => 'Invalid login details'
                //         ], 404);
                //     } else {
                //         $response = response()->json([
                //             'status' => true,
                //             'message'=> "Login successful",
                //             'data'=> [ 'token' => $token ]
                //         ], 200);
                //     }
                // } catch (\Throwable $th) {
                //     $response = response()->json([
                //         'status' => false,
                //         'message' => 'Failed to login, please try again.',
                //         'error'=>$th->getMessage()
                //     ], 500);
                // }
            }

        return $response;
    }


}
