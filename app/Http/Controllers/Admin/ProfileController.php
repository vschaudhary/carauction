<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreMediaRequest;
use Illuminate\Support\Facades\Validator;


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

    public function uploadImage(StoreMediaRequest $request)
    {
        $validator = Validator::make($request->all(), 
        [ 
            'image' => 'required|image|mimes:doc,docx,pdf,txt,png,jpeg|max:2048',
        ]);   
 
        if ($validator->fails()) {          
            return response()->json(['error'=>$validator->errors()], 401);                        
        }  
        $validated = $request->all();

        // $validated = $request->validated();
        try{
            $extension =  $validated['image']->extension();
            $mimeType = $validated['image']->getClientMimeType();
            $imageName = time().'.'.$extension;  
            $path = public_path('images/admin');
            $profileImage = $validated['image']->move($path, $imageName);
            
            if($profileImage){
                //save media 
                $media = new Media([
                    'title' => $validated['title'] ?? null,
                    'name' =>  $imageName,
                    'path' => $path,
                    'mime' => $mimeType,
                    'driver' => 'localhost',
                    'mediable_type' => User::class,
                    'mediable_id' => Auth::id(),
                    'status' => 1,
                    'type_id' => 1,
                    'created_by_id' => Auth::id(),
                ]);
                $mediaUploaded  = $media->save();
                if(!$mediaUploaded){
                    unlink($path.'/'.$imageName);
                    return $this->sendError('Somthing went wrong, please try again!',[], 500 );
                }
                return $this->sendResponse( [], 'Profile image uploaded successfully.' ); 
            } else {
                return $this->sendError('Somthing went wrong, please try again!',[], 500 );
            }
        } catch(\Exception $e) {
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
