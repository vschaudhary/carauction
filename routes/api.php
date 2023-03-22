<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\Auth\PasswordResetController;

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

//User Password Reset
Route::post('password/create', [PasswordResetController::class, 'create']);
Route::get('password/find/{token}', [PasswordResetController::class, 'find']);
Route::post('password/reset', [PasswordResetController::class, 'reset']);
     
Route::middleware('auth:api')->group( function () {
    Route::post('abc', [RegisterController::class, 'abc']);
});
