<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\API\UserController as UsersController;

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
Route::post('register', [RegisterController::class, 'register']);
Route::get('logout', [RegisterController::class, 'logout'])->name('logout')->middleware('auth:api');

 // Password reset routes
Route::post('forgot-password',  [ForgotPasswordController::class, 'forgotPassword']);
Route::post('verify/pin', [ForgotPasswordController::class, 'verifyPin']);
Route::post('reset-password', [ResetPasswordController::class, 'resetPassword']);


Route::group(['middleware' => ['IsAdmin','auth:api']], function () {
    
    //User Module Routes
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('users/pending', [UserController::class, 'pending'])->name('users.pending');

    //Admin Profile Module Routes
    Route::get('profile',[ProfileController::class, 'show'])->name('admin.profile');
    Route::put('profile/update',[ProfileController::class, 'update'])->name('admin.profile.update');
});

Route::group(['prefix' => 'user', 'middleware' => ['auth:api']], function () {
    Route::get('profile', [UsersController::class, 'show'])->name('view.show');
    Route::put('update', [UsersController::class, 'update'])->name('users.update');
});


