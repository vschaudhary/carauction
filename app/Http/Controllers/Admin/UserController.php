<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Constant\Constants;
use App\Models\User;
use App\Models\Dealership;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use App\Http\Requests\StoreUserRequest;

class UserController extends Controller {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function index( Request $request ) {
        try { 
            $searchItem = $request->search;
            $sortBy = $request->sort;
            $searchByType = $request->type;

            $users = User::with('dealership');
            //return users based on type like pending for admin approval,active, inactive
            switch($searchByType)
            {
                case('pending');
                    //users pending for admin approval
                    $users = $users->pendingApproval();
                    break;
                case('active');
                    //active users with status 1
                    $users = $users->active();
                    break;
                case('inactive');
                    //Inactive users with status 0
                    $user = $users->inactive();
                    break;
                default :
                    //all the users
                    $users = $users->all();
                    break;
            }
            //return searched users
            $users = $searchItem ? $users->search($searchItem) : $users;

            //sort users by email id
            $users = $sortBy ? $users->orderBy( 'email', $sortBy ) : $users;

            $users = $users->get();
            
            if($users->count() > 0){
                $message = 'Users fetched successfully!';
            }else{
                $message =  'Users not found!';
            }

            return $this->sendResponse( $users, $message );
        } catch ( Exception $e ) {
            return $this->sendError( 'Server Error', $e->getMessage(), 500 );
        }
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create() {
        //
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

    public function store( Request $request ) {
        //
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function show( $id ) {
        $user = User::with('dealership')->find($id);
        $message =  $user ?  "User Found" : 'User Not Found!';
        return $this->sendResponse( $user, $message );
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function edit( $id ) {
        //
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function update(StoreUserRequest $request, $id ) {
        try {
            DB::beginTransaction();
            //Validate request data
            $validated = $request->validated();   
            $success = [];      
            //after validation
            $user = User::find($id);
            $data = $validated['profile'];
            $emailExists = User::withTrashed()->where('email', $validated['profile']['email'])->where('id', '!=', $id)->get()->count();
            if($emailExists > 0){
                return $this->sendError( 'Error', 'This email has already been taken.', 403);
            }
            if($user){
                $user->update($data);
                $dealer = Dealership::where('user_id', $id)->firstOrFail();
                if(!$dealer){
                    DB::rollback();
                    return $this->sendError( 'Server Error', 'Something went wrong.', 500 );
                }
                $dealer->update($validated['dealership']);
                DB::commit();
                $user = User::with('dealership')->find($id);
                return $this->sendResponse( $user, 'User Updated successfully.' ); 
            } else {
                return $this->sendError('User not found!',[], 404 );
                DB::rollback();
            }
        } catch ( Exception $e ) {
            DB::rollback();
            return $this->sendError( 'Server Error', $e->getMessage(), 500 );
        }
    }   

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function destroy( $id ) {
        try {
        DB::beginTransaction();
        $user = User::find($id);
        if(!$user){
            return $this->sendError('User not found!',[], 404 );
        }
        if($user->delete()){
            $dealer = Dealership::where('user_id' , $id)->delete();
            DB::commit();
            return $this->sendResponse( [], 'User with dealership data Deleted successfully.' ); 
        }
        }  catch ( Exception $e ) {
            DB::rollback();
            return $this->sendError( 'Server Error', $e->getMessage(), 500 );
        }
    }

    /**
     * Update user status
     */
    public function status(Request $request, $id)
    {
        $data = $request->all();
        $validator = Validator::make($request->all(), [
            'status' => ['required', 'in:0,1'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 403);       
        }

        $user  = User::find($id);
        if($user){
            if($user->update($data)){
                return $this->sendResponse($user, "Status updated successfully!");
            }
            else{
                return $this->sendError( 'Error', 'Something went wrong!', 500 );
            }
        } else {
            return $this->sendError( 'Error', 'User not found!', 400 );
        }       
    }
}