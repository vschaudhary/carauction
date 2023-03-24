<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\API\ResetPasswordController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('login', [RegisterController::class, 'login']);
Route::get('Unauthorised', [RegisterController::class, 'Unauthorised'])->name('login');
Route::post('register', [RegisterController::class, 'register']);

 // Password reset routes
Route::post('forgot-password',  [ForgotPasswordController::class, 'forgotPassword']);
Route::post('verify/pin', [ForgotPasswordController::class, 'verifyPin']);
Route::post('reset-password', [ResetPasswordController::class, 'resetPassword']);

     
Route::middleware('auth:api')->group( function () {
    Route::post('abc', [RegisterController::class, 'abc']);
});
