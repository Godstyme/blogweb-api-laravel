<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class BloggerOperation extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allUsers = User::all();
        if ($allUsers) {
            $response =  response()->json([
                "status" => true,
                "message" => "All users retrieved",
                "guest" => $allUsers,
                "Total Guest"=> count($allUsers)
            ], 200);
        } else {
            $response =  response()->json([
                "status" => false,
                "message" => "No user found"
            ], 404);
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
        $users = User::find($id);
        if ($users) {
            $response = response()->json([
                "status" => true,
                "user" => $users,
                "message" => 'User Retrieved'
            ], 200);
        } else {
            $response = response()->json([
                "status" => false,
                "message" => 'User does not exist'
            ], 404);
        }

        return $response;
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
