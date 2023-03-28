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

class RegisterController extends Controller
 {
    /**
    * Register api
    *
    * @return \Illuminate\Http\Response
    */

    public function register( Request $request )
 {
        $data = [ 'data' => $request->all() ];
        $validator = Validator::make( $data, [
            'data.profile.first_name' => 'required|string',
            'data.profile.last_name' => 'required|string',
            'data.profile.email' => 'required|string',
            'data.profile.phone' => 'required|string',
            'data.dealership.dealership_name' => 'required|string',
            'data.dealership.dealership_street_name' => 'required|string',
            'data.dealership.city' => 'required|string',
            'data.dealership.state' => 'required|string',
            'data.dealership.zip_code' => 'required|string',
            'data.dealership.car_stock' => 'required|string'
        ] );

        if ( $validator->fails() ) {
            return $this->sendError( 'Validation Error.', $validator->errors(), 403 );

        }
        try {
            DB::beginTransaction();
            $success = [];
            $userData = User::create( [
                'first_name'=>$request->profile[ 'first_name' ],
                'last_name'=>$request->profile[ 'last_name' ],
                'email'=>$request->profile[ 'email' ],
                'phone'=>$request->profile[ 'phone' ],
                'phone_ext'=>$request->profile[ 'phone_ext' ],
                'mobile'=>$request->profile[ 'mobile' ],
                'contact_preference'=>$request->profile[ 'contact_preference' ],
                'role_id'=>Constants::TYPE_USER,
                'status'=>Constants::STATE_DEACTIVATE,
                'type_id'=>Constants::STATE_DEACTIVATE
            ] );
            if ( $userData ) {
                $dealershipData = Dealership::create( [
                    'name'=>$request->dealership[ 'dealership_name' ],
                    'street_name'=>$request->dealership[ 'dealership_street_name' ],
                    'city'=>$request->dealership[ 'city' ],
                    'state'=>$request->dealership[ 'state' ],
                    'zip_code'=>$request->dealership[ 'zip_code' ],
                    'website'=>$request->dealership[ 'website' ],
                    'car_stock'=>$request->dealership[ 'car_stock' ],
                    'status'=>Constants::STATE_ACTIVE,
                    'type_id'=>Constants::STATE_DEACTIVATE,
                    'user_id'=>$userData->id
                ] );
            } else {
                DB::rollback();
                return $this->sendError( 'Server Error', 'Something went wrong.', 500 );
            }
            if ( $dealershipData ) {
                // $success = User::where( 'id', $userData->id )->with( 'dealership' )->firstOrFail();
                DB::commit();
                return $this->sendResponse( $success, 'User register successfully.' );
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
            $success = Auth::user();
            $success[ 'token' ] =  $success->createToken( 'MyApp' )-> accessToken;

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
