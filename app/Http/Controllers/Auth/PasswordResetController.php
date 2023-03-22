<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordChangeRequest;
use App\Notifications\PasswordResetSuccess;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\PasswordReset;
use App\Constant\Constants;
use Validator;

class PasswordResetController extends Controller
{
    /**
     * Create token password reset
     *
     * @param
     *            [string] email
     * @return [string] message
     */
    public function create(Request $request)
    {
        $data = [];
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'reset_url' => 'required|string'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(), 403);       
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->sendError("We can't find a user with that email address.", null, 404); 
        }
        $randomToken = Str::random(60);
        // return $randomToken;
        try {
            $passwordReset = PasswordReset::updateOrCreate([
                'email' => $user->email
            ], [
                'email' => $user->email,
                'token' => $randomToken
            ]);
            return $passwordReset;

            if ($passwordReset) {
                $this->customSendMail($user, (new PasswordResetRequest($passwordReset->token, $request->reset_url)));
                return $this->success('We have emailed your password reset link!');
            } else {
                return $this->failed('Something Went wrong!!!');
            }
        } catch (Exception $e) {
            return $this->sendError('Server Error', $e->getMessage(), 500);
        }
    }

    /**
     * Find token password reset
     *
     * @param [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();
        if (!$passwordReset)
            return $this->failed('This password reset token is invalid.');
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return $this->failed('This password reset token is invalid.');
        }
        return $this->success(["data" => $passwordReset]);
    }

    /**
     * Reset password
     *
     * @param
     *            [string] email
     * @param
     *            [string] password
     * @param
     *            [string] password_confirmation
     * @param
     *            [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(Request $request)
    {
        $request->validate([
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);
        $passwordReset = PasswordReset::where([
            [
                'token',
                $request->token
            ]
        ])->first();
        if (!$passwordReset) {
            return $this->failed('This password reset token is invalid.');
        }
        $user = User::where('email', $passwordReset->email)->first();
        if (!$user)
            return $this->failed("We can't find a user with that email address.");

        try {
            $user->password = bcrypt($request->password);
            $user->save();
            $passwordReset->delete();
             $this->customSendMail($user, (new PasswordChangeRequest($passwordReset->token, Constants::FRONTEND_URL)));
            return $this->success(['data' => $user, 'message' => "Password reset successfully!!!"]);
        } catch (\Illuminate\Database\QueryException $exception) {
            return $this->error($exception->errorInfo);
        }
    }
}
