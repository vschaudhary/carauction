<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller {
    public function resetPassword( Request $request ) {

        $validator = Validator::make( $request->all(), [
            'email' => [ 'required', 'string', 'email', 'max:255' ],
            'password' => [ 'required', 'string', 'min:8', 'confirmed' ],
        ] );

        if ( $validator->fails() ) {
            return $this->sendError( 'Validation Error.', $validator->errors(), 403 );

        }
        try {
            $data = [];
            $user = User::where( 'email', $request->email )->first();
            if($user){
                $user->update( [
                    'password'=>Hash::make( $request->password )
                ] );
                return $this->sendResponse( $data, 'Your password has been reset successfully', 200 );
            }
            else{
                return $this->sendResponse( $data, 'User not found!', 404 );
            }
        } catch ( Exception $e ) {
            return $this->sendError( 'Server Error', $e->getMessage(), 500 );
        }
    }
}