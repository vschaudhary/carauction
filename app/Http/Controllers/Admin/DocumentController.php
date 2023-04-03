<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
Use App\Models\User;

class DocumentController extends Controller
{
    /***
     * Admin verify  user(documents/account)
     * 
     */    
    public function verify(Request $request, $id) {
        //Send email to user to set their password
        $user  = User::find($id);
        if($user){
            $data = $request->all();
            if($data['verify'] == 1){
                // Update user type_id to 1 and user status to 1
                $user->update(['type_id' => "1", "status" => "1"]);
                return $this->sendResponse([], "User account approved by admin!");
            }else{
                $user->update(['type_id' => "0", "status" => "0"]);
                return $this->sendResponse([], "User account rejected by admin!");
            }
        } else {
            return $this->sendError( 'Error', 'User not found!', 400 );
        }       
    }

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
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
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
