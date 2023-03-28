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
                $dealershipDetails->status = Constants::STATE_ACTIVE;
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
            dd($user->createToken('MyApp')->accessToken);
            $user[ 'token' ] =  $user->createToken('MyApp')->accessToken;
            return $this->sendResponse( $success, 'User login successfully.' );
        } else {

            return $this->sendError( 'Unauthorised.', [ 'error'=>'Incorrect email or password entered.' ], 401 );
        }

    }

    public function Unauthorised( Request $request )
    {
        return $this->sendError( 'Unauthorised.', [ 'error'=>'Unauthorised' ], 401 );
    }

    public function abc( Request $request )
    {
        return Auth::user();
    }

}
