<?php

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserRegistrationController;
use App\Http\Controllers\UserLoginController;
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

Route::controller(UserLoginController::class)->group(function() {
    Route::post('login','login');
    Route::post('refresh', 'refresh');
});

Route::group(['middleware' => ['jwt.auth']], function() {
    Route::get('logout', [UserLoginController::class,'logout']);

});

