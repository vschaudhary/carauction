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
            $users = [];
            $search = $request->search;
            $sorting = $request->sorting;
            $users = User::query();

            if ( !empty( $search ) ) {
                $users = $users->where( 'email', 'LIKE', "%{$search}%" );
            }

            elseif ( !empty( $sorting ) ) {
                $users = $users->orderBy( 'email', 'desc' );
            } else {
                $users = $users->where( 'role_id', 2 );
            }
            $users = $users->paginate( 20 );
            return $this->sendResponse( $users, 'ok' );
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
        dd('show');
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
            if($user){
                $user->update($data);
                $dealer = Dealership::find($validated['dealership']['id']);
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
}