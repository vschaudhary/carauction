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

    public function update( Request $request, $id ) {
        //
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function destroy( $id ) {
        //
    }
}