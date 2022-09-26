<?php

// use Illuminate\Http\Request;

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserRegistrationController;
use App\Http\Controllers\UserLoginController;
use App\Http\Controllers\BloggerOperation;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('jwt.auth')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::controller(UserRegistrationController::class)->group(function() {
    Route::get('index','index');
    Route::post('register','store');
    Route::get('user/verify/{verification_code}', 'verifyUser');
});

Route::controller(AdminController::class)->group(function() {
    Route::get('admin/view','index');
    Route::post('admin/register','store');
});

// Route::post('admin/register',[AdminController::class,'store'])->middleware('admin');
// Route::get('admin/view',[AdminController::class,'index'])->middleware('admin');

Route::controller(UserLoginController::class)->group(function() {
    Route::post('login','login');
    Route::post('refresh', 'refresh');
});

Route::group(['middleware' => ['jwt.auth']], function() {
    Route::get('logout', [UserLoginController::class,'logout']);

    Route::controller(BloggerOperation::class)->group(function() {
        Route::get('show/{id}','show');
    });

});


