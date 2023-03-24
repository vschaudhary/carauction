<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
public function resetPassword(Request $request)
{   
    $validator = Validator::make($request->all(), [
        'email' => ['required', 'string', 'email', 'max:255'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);
    

    if ($validator->fails()) {
        return response()->json(['status_code'=>401, 'success' => false,  'message' => $validator->errors()]);
    }
    try {
    $data =[];
    $user = User::where('email',$request->email);
    $user->update([
        'password'=>Hash::make($request->password)
    ]);

    return $this->sendResponse(  $data,"Your password has been reset successfully" ,200);
   }
  catch (Exception $e){
    return $this->sendError('Server Error', $e->getMessage(), 500);
     }
   }
}