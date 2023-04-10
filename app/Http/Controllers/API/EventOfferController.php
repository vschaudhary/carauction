<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Constant\Constants;
use Validator;
use App\Models\EventOffer;

class EventOfferController extends Controller
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
        try{
            $validated = Validator::make($request->all(), [
                'event_id' => 'required|integer|exists:events,id',
                'buyer_offered_amount' => 'required|integer'
            ]);        
            if($validated->fails()){
                return $this->sendError('Validation Errors', $validated->errors(), 403);
            }
            $data = [
                'buyer_id' => Auth::id(),
                'buyer_offered_amount' => $request->get('buyer_offered_amount'),
                'seller_offered_amount' => null,
                'status' => Constants::STATE_BID,
                'event_id' => $request->get('event_id'),
                'type_id' => Constants::STATE_ACTIVE,
                'created_by_id' =>Auth::id()
            ];
            $eventOffer = EventOffer::updateOrCreate(['buyer_id' => Auth::id()], $data);

            if($eventOffer){
                return $this->sendResponse( $eventOffer, 'You made an offer successfully!');
            }else{
                return $this->sendError('Somthing went wrong, try again.',[], 500 );
            }
        } catch(\Exception $e) {
            return $this->sendError( 'Server Error', $e->getMessage(), 500 );
        }
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
