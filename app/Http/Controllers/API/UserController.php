<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; 
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreUserRequest;
use App\Models\Dealership;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $userId = Auth::id();
        $user = User::with('dealership')->find($userId);
        if($user){
            return $this->sendResponse( $user, 'User found successfully.'); 
        } else {
            return $this->sendError('User not found!',[], 404 );
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUserRequest $request)
    {
        try {
            DB::beginTransaction();
            //Validate request data
            $validated = $request->validated();      
            $success = [];      
            //after validation
            $id = Auth::id();
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
    public function destroy($id)
    {
        //
    }
}
