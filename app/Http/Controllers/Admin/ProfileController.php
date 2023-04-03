<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
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
        $id = Auth::id();
        $details = User::find($id);
        if($details)
        {
            return $this->sendResponse( $details, 'Admin details found successfully.'); 
        } else {
            return $this->sendError('Admin not found!',[], 404 );
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
    public function update(Request $request)
    {
        try{
            $data = $request->all();
            $id = Auth::id();
            $emailExists = User::withTrashed()->where('email', $data['email'])->where('id', '!=', $id)->get()->count();
            if($emailExists > 0){
                return $this->sendError( 'Error', 'This email has already been taken.', 403);
            }
            $admin = User::find($id);
            if($admin){
                if($admin->update($data)){
                    return $this->sendResponse( $admin, 'Admin updated successfully.' ); 
                } else {
                    return $this->sendError('Somthing went wrong, please try again!',[], 500 );
                }
            } else {
                return $this->sendError('Admin not found!',[], 404 );
            }
        } catch ( Exception $e ) {
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
