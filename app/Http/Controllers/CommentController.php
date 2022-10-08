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
        try {
            $comments = Comment::find($id);
            if ($comments) {
                $response = response()->json([
                    "status"=>true,
                    "comment"=>$comments,
                    "message" => 'comment Retrieved'
                ],200);
            } else {
                $response = response()->json([
                    "status"=>false,
                    "message" => 'comment does not exist'
                ],404);
            }
        } catch (\Throwable $th) {
            $response = response()->json([
                'status' => false,
                'error' => $th->getMessage()
            ], 500);
        }
        return $response;
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
        try {
            $user = Auth::user();

            if ($user->role == "admin") {
                $posts = Comment::find($id);
                $deletePost = $posts->delete();

                if ($deletePost) {
                    $response = response()->json([
                        'status' => true,
                        'message' => 'Comment Deleted successfully by the admin :)',
                    ], 204);
                } else {
                    $response = response()->json([
                        'status' => true,
                        'message' => 'Operation failed, Comment was not deleted',
                    ], 400);
                }


            } else {
                $comments = $user->comment;
                $postCount = count($comments);
                if ($postCount > 0) {
                    foreach($comments as $comment){

                        if ($comment->id == $id) {
                            $comment->delete();
                            // dd("hello");
                            $response = response()->json([
                                "status"=>true,
                                "message" => 'Comment Deleted successfully :)'
                            ],204);
                        }  else {
                            $response =  response()->json([
                                "status"=>false,
                                "message" => 'Unable to delete this comment, it doesn\'t belong to you'
                            ],403);
                        }

                    }
                } else {
                    $response =  response()->json([
                        "status"=>false,
                        "message" => 'You dont have any comment'
                    ],404);
                }
            }


        } catch (\Throwable $th) {
            report($th);
            $response = response()->json([
                "status"=>false,
                "message" => 'Operation failed, Comment not deleted'
            ],400);
        }

        return $response;
    }
}
