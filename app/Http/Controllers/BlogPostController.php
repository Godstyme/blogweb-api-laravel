<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BlogPostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $posts = BlogPost::all();
            $totalPost = count($posts);
            if (count($posts) == 0) {
                $response = response()->json([
                    "status"=>false,
                    "message" => 'No Search Results Found'
                ],404);
            } else {
                $response = response()->json([
                    "status"=>true,
                    'data' => $posts,
                    'message' => 'Retrieved successfully',
                    "Total Post"=>$totalPost
                ],200);
            }
        } catch (\Throwable $th) {
            $response = response()->json([
                'status' => false,
                'message'=> 'Please login to view all blog',
                'error' => $th->getMessage()
            ], 500);
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
            $id = Auth::user()->id;
            $validateData = [
                'blog_title' =>   'required|string|between:5,50',
                'blog_description' =>   'required|string|between:5,1000'
            ];
            $validator = Validator::make($request->all(),$validateData);
            if ($validator->fails()) {
                $response =  response()->json([
                    "status"=>false,
                    "message" =>$validator->errors()
                ],422);
            } else {
                $validateUser = new BlogPost;
                $validateUser->blog_posts_title = $request->blog_title;
                $validateUser->blog_posts_desc = $request->blog_description;
                $validateUser->users_id = $id;
                $result = $validateUser->save();
                if ($result) {
                    $response = response()->json([
                        "status"=>true,
                        "message" => 'You have successful made a post'
                    ],200);
                } else {
                    $response = response()->json([
                        "status"=>false,
                        "message" => 'Operation failed, Post content was not inserted'
                    ],400);
                }
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $posts = BlogPost::find($id);
            if ($posts) {
                $response = response()->json([
                    "status"=>true,
                    "user"=>$posts,
                    "message" => 'Post Retrieved'
                ],200);
            } else {
                $response = response()->json([
                    "status"=>false,
                    "message" => 'Post does not exist'
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
                $posts = BlogPost::find($id);
                $deletePost = $posts->delete();

                if ($deletePost) {
                    $response = response()->json([
                        'status' => true,
                        'message' => 'Post Deleted successfully by the admin :)',
                    ], 200);
                } else {
                    $response = response()->json([
                        'status' => true,
                        'message' => 'Operation failed, post was not deleted',
                    ], 400);
                }


            } else {
                $posts = $user->post;
                $postCount = count($posts);
                if ($postCount > 0) {
                    foreach($posts as $post){

                        if ($post->id == $id) {
                            $post->delete();
                            // dd("hello");
                            $response = response()->json([
                                "status"=>true,
                                "message" => 'Post Deleted successfully :)'
                            ],200);
                        }  else {
                            $response =  response()->json([
                                "status"=>false,
                                "message" => 'Unable to delete this post, it doesn\'t belong to you'
                            ],403);
                        }

                    }
                } else {
                    $response =  response()->json([
                        "status"=>false,
                        "message" => 'You dont have any post'
                    ],404);
                }
            }


        } catch (\Throwable $th) {
            report($th);
            $response = response()->json([
                "status"=>false,
                "message" => 'Operation failed, Post not deleted'
            ],400);
        }

        return $response;
    }
}
