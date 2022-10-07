<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $Post = BlogPost::find($request-> id);
        $userid = Auth::user()->id;

        try {

            $validateComment = Validator::make($request->all(),[
                'comment' =>   'required|string'

            ]);
            if($validateComment->fails()){
                $response = response()->json([
                    "status"=>false,
                    'errors'=>$validateComment->getMessageBag()->toArray()
                ], 422);
            } else {

                $commentSave = $Post->postComment()->create([
                    'comments_content'=>$request->comment,
                    'users_id'=>$userid
                ]);
                if ($commentSave) {
                    $response = response()->json([
                        "status"=>true,
                        "message" => 'Comment created successfully !'
                    ],201);
                } else {
                    $response = response()->json([
                        "status"=>false,
                        "message" => 'Opps :), failed to comment'
                    ],400);
                }
            }

        } catch (\Throwable $th) {
            // report($th);
            $response = response()->json([
                "status"=>false,
                "message" => 'Operation failed, Comment was not created!'.$th
            ],500);
        }
        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
