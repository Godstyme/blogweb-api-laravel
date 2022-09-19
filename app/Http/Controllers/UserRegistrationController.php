<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class UserRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(["message"=>'Hello World ma',"status"=>true], 305);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateUser = [
            'name' =>   'required|string|min:5|max:50',
            'email' => 'required|string|email|max:60|unique:users',
            'password' => 'required|min:8'
        ];
        $validator = Validator::make($request->all(), $validateUser);
        if ($validator->fails()) {
            $response =  response()->json([
                "status" => false,
                "message" => $validator->errors()
            ], 422);
        } else {
            $validateUser = new User;
            $validateUser->name = $request->name;
            $validateUser->email = $request->email;
            $validateUser->password = Hash::make($request->password);
            $user = User::first();
            $token = JWTAuth::fromUser($user);
            $result = $validateUser->save();
            if ($result) {
                $response = response()->json([
                    "status" => true,
                    "message" => 'User registration was successful',
                    'authorisation' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ]
                ], 201);
            } else {
                $response = response()->json([
                    "status" => false,
                    "message" => 'Operation failed, User not registered'
                ], 400);
            }
        }
        return $response;
    }

}
