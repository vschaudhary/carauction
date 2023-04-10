<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Constant\Constants;
use App\Http\Requests\StoreEventRequest;
use App\Models\Event;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try { 
            $eventByType = $request->type;
            $eventStatus = $request->status;
            $events = Event::with('vehicle');

            //2,3
            if($eventByType == Constants::TYPE_AUCTION && $eventStatus == Constants::STATE_LIVE)
            {
                $events = $events->liveAuction();

            }
            //1,3
            if($eventByType == Constants::TYPE_LIVE_APPRAISAL && $eventStatus == Constants::STATE_LIVE)
            {
                $events = $events->liveAppraisal();

            }
            //2,2
            if($eventByType == Constants::TYPE_AUCTION && $eventStatus == Constants::STATE_RUN)
            {
                $events = $events->runList();

            }
            //2,4
            if($eventByType == Constants::TYPE_AUCTION && $eventStatus == Constants::STATE_ENDED)
            {
                $events = $events->endedAuction();

            }
            //3,buyer
            if($eventByType == Constants::TYPE_PRIVATE_MARKET && $request->category == 'buyer')
            {
                $events = $events->buyerPrivateMarket();
                
            }
            //3,seller
            if($eventByType == Constants::TYPE_PRIVATE_MARKET && $request->category == 'seller')
            {
                $events = $events->sellerPrivateMarket();
                
            }
            $events = $events->get();
            
            if($events->count() > 0){
                return $this->sendResponse( $events, 'Data Found!');
            }else{
                return $this->sendError('Data not found!',[], 404 );
            }

        } catch (\Exception $e ) {
            return $this->sendError( 'Server Error', $e->getMessage(), 500 );
        }
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
    public function store(StoreEventRequest $request)
    {        
        $validated = $request->validated();
        $event = Event::create($validated);
        if($event){
            return $this->sendResponse( $event, 'Event saved successfully!');
        }else{
            return $this->sendError('Something went wrong!',[], 500 );
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
        try{
            $data = $request->all();
            $validator = Validator::make($request->all(), [
                'status' => ['required|integer'],
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 403);       
            }

            $event  = Event::find($id);
            if($event){
                if($event->update($data)){
                    return $this->sendResponse($user, "Status updated successfully!");
                }
                else{
                    return $this->sendError( 'Error', 'Something went wrong!', 500 );
                }
            } else {
                return $this->sendError( 'Error', 'User not found!', 400 );
            }
        }  catch (\Exception $e ) {
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
