<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\ResetPassword;
use App\Models\User;
use App\Models\Dealership;
use App\Models\password_reset;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'email' => ['required', 'string', 'email', 'max:255'],
        ]);

      if ($validator->fails()) {
        return $this->sendError('Validation Error.', $validator->errors(), 403);       
    }
    try{

    $data = [];
    $verify = User::where('email', $request->email)->exists();
    if ($verify) {
        
        $token = random_int(100000, 999999);
        $password_reset  = password_reset::updateOrCreate([
            'email'=> $request->email
        ],
         [
            'email'=> $request->email,
            'token'=>  $token
        ]);

        // if ($password_reset) {
        //     Mail::to($request->email)->send(new ResetPassword($token));
         
            return $this->sendResponse(  $data,"Please check your email for a 6 digit pin"
            );
        // }
        
    } else {
        return $this->sendError('This email does not exist', [],401);
      }
    }
    catch (Exception $e){
        return $this->sendError('Server Error', $e->getMessage(), 500);
    }
}

// verifyPin 6 pin digit token
public function verifyPin(Request $request)
{

    $validator = Validator::make($request->all(), [
        'token' => ['required'],
    ]);

    if ($validator->fails()) {
        return $this->sendError('Validation Error.', $validator->errors(), 403);       
    }
    
    try{
    $data = [];
    $check = password_reset::where([
        'token'=> $request->token
    ]);


    if ($check->exists()) {
        $difference = $check->where('created_at','>',Carbon::now()->subHours(1))->first();
        $difference2 = $check->first()->email;
        // dd($difference2);
        if ($difference > 3600) {
            return $this->sendError('Token Expired! please regenerate again', [] ,401 );
        }

        $delete = password_reset::where([
            'token'=> $request->token,
        ])->delete();
        return  $this->sendResponse(['email'=>$difference2],"You can now reset your password");
    } 
    else {
        return $this->sendError([],'Invalid token',401);
    }
    }catch (Exception $e){
        return $this->sendError('Server Error', $e->getMessage(), 500);
    }

   }

}