<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Mail\WebBlogMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
// use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class UserRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(["message"=>'Hello World ma',"status"=>true], 202);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {

            $validateUser = [
                'name' =>   'required|string|min:5|max:50',
                'email' => ['required', 'string', 'email', 'max:80', 'unique:users'],
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

                $verification_code = Str::random(12);
                DB::table('user_verifications')->insert(['users_id'=>1,'token'=>$verification_code]);

                $details = [
                    'email' => $request->email,
                    'name' => $request->name,
                    'title' => 'Registration Successful',
                    'subject' => 'Please verify your email address.'
                    // 'subject'=>'Thank you for registring an account with us.
                    // Stay tune as we promise to give you useful and educations contents',
                ];
                Mail::to($details['email'])->send(new WebBlogMail($details));





                $result = $validateUser->save();
                if ($result) {
                    $response = response()->json([
                        "status" => true,
                        "message" => 'Thanks for signing up! Please check your email to complete your registration.',
                        "user"=> $validateUser
                    ], 201);
                } else {
                    $response = response()->json([
                        "status" => false,
                        "message" => 'Operation failed, User not registered'
                    ], 400);
                }
            }
        } catch (\Throwable $th) {
            $response = response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
        return $response;



    }

}
