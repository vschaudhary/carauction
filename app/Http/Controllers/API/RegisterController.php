<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Constant\Constants;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Dealership;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreUserRequest;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
 {
    /**
    * Register api
    *
    * @return \Illuminate\Http\Response
    */

    public function register(StoreUserRequest $request)
    {
        //Validate request data
        $validated = $request->validated();
        //if data validated
        try {
            DB::beginTransaction();
            $success = [];
            $emailExists = User::withTrashed()->where('email', $validated['profile']['email'])->get()->count();
            if($emailExists > 0){
                return $this->sendError( 'Error', 'This email has already been taken.', 403);
            }
            $userData= [
                'password' => Hash::make('Mind@123'),
                'role_id' => Constants::TYPE_USER,
                'status' => Constants::STATE_DEACTIVATE,
                'type_id' => Constants::STATE_DEACTIVATE  
            ];          
            //Save User
            $user = User::create(array_merge($validated['profile'],$userData));
            
            if($user){
                $dealershipDetails = new Dealership($validated['dealership']);  
                $dealershipDetails->status = Constants::STATE_DEACTIVATE;
                $dealershipDetails->type_id = Constants::STATE_DEACTIVATE;  
                //save user's dealership data            
                $dealer = $user->dealership()->save($dealershipDetails);
                if(!$dealer){
                    DB::rollback();
                    return $this->sendError( 'Server Error', 'Something went wrong.', 500 );
                }
                DB::commit();
                return $this->sendResponse( $success, 'User registered successfully.' );
            } else {
                DB::rollback();
                return $this->sendError( 'Server Error', 'Something went wrong.', 500 );
            }
        } catch ( Exception $e ) {
            DB::rollback();
            return $this->sendError( 'Server Error', $e->getMessage(), 500 );
        }
    }

    /**
    * Login api
    *
    * @return \Illuminate\Http\Response
    */

    public function login( Request $request )
    {
        if ( Auth::attempt( [ 'email' => $request->email, 'password' => $request->password ] ) ) {
            $user = Auth::user();
            if($user->status != "1"){
                return $this->sendError( [], ['error' => 'Your account is blocked by admin!'], 400);
            }
            $user[ 'token' ] =  $user->createToken('MyApp')->accessToken;
            return $this->sendResponse( $user, 'User login successfully.' );
        } else {
            return $this->sendError( [], [ 'error'=>'Incorrect email or password entered.' ], 401 );
        }
    }

    public function logout()
    {      
        if(Auth::user()){
            Auth::user()->tokens->each(function ($token) {
                $token->delete();
            });
            Session::flush();
            return $this->sendResponse( [], 'Logout successfully.' );
        }  else {
            return $this->sendError( [], [ 'error'=>'User is not login!' ], 400 );
        }
    }
}
