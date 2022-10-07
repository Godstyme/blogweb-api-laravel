<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Mail\WebBlogMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;


class UserRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $guest = User::where('role','guest')->get();
        if ($guest) {
            $response =  response()->json([
                "status" => true,
                "message" => "guest retrieved",
                "guest" => $guest,
                "Total Guest"=> count($guest)
            ], 200);
        } else {
            $response =  response()->json([
                "status" => false,
                "message" => "guest not found"
            ], 404);
        }
        return $response;
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
                $result = $validateUser->save();
                // $now = date('Y-m-d H:i');
                DB::table('user_verifications')->insert(['users_id'=>$validateUser['id'],'token'=>$verification_code,'created_at'=>Carbon::now()]);

                $details = [
                    'email' => $request->email,
                    'name' => $request->name,
                    'title' => 'Registration Successful',
                    'subject' => 'Please verify your email address.',
                    'token'=>$verification_code
                ];
                Mail::to($details['email'])->send(new WebBlogMail($details));


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

    public function verifyUser($verification_code){
        try {
            $check = DB::table('user_verifications')->where('token',$verification_code)->first();

            if(!is_null($check)){
                $user = User::find($check->users_id);

                if($user->is_verified == 1){
                    $response = response()->json([
                        'status'=> true,
                        'message'=> 'Account already verified..'
                    ],200);
                } else {
                    $user->update(['is_verified' => 1]);
                    $result = DB::table('user_verifications')->where('token',$verification_code)->delete();
                    if ($result) {
                        $response = response()->json([
                            'status'=> true,
                            'message'=> 'You have successfully verified your email address.'
                        ],200);
                    } else {
                        $response = response()->json([
                            'status'=> false,
                            'message'=> 'Error occurred, email address was not verify. Try again'
                        ],400);
                    }


                }
            } else {
                return response()->json([
                    'status'=> false,
                    'message'=> "Verification code is invalid."
                ],404);
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
