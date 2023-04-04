<?php

namespace App\Constant;

class Constants
{
    const FRONTEND_URL = "";
    const BACKEND_URL = "";

    /**
     * constant for role
     */ 
    const TYPE_ADMIN = 1;
    const TYPE_USER = 2;

    /**
     * Media Status
     * 0 => inactive, 1 => active
     * 
     * Registed User
     * 0 => Not Verified, 1 => Active
     */
    const STATE_INACTIVE = 0;
    const STATE_ACTIVE = 1;

    /**
     * Vehicle Status
     *  Sold, Unsold, Pending (Saved with partial data), Completed (ready for auction/live appraisal), Offered (seller offered price & action needed(negotiate)), In_negotiatio (when negotiate action is performed), Re_launched ( when moved to aution again)
     * 
     */
    const STATE_PENDING = 1;
    const STATE_COMPLETED = 2;
    const STATE_SOLD = 3;
    const STATE_UNSOLD = 4;
    const STATE_OFFERED = 5;
    const STATE_INNEGOTIATION = 6;
    const STATE_RELAUNCHED = 7;
    
    /**
     * Events Status
     * ['New', 'Run', 'Live', 'Ended'] 'Run => scheduled for next day, New => when event is created'
     */
    const STATE_NEW = 1;
    const STATE_RUN = 2;
    const STATE_LIVE = 3;
    const STATE_ENDED = 4;

    /**
     * Events Types
     * ['live_appraisal', 'auction', 'private_market' ]
     */
    const TYPE_LIVE_APPRAISAL = 1;
    const TYPE_AUCTION = 2;
    const TYPE_PRIVATE_MARKET = 3;


    /**
     * Document Status
     * ['Pending', 'Approved', 'Rejected']
     */
    
    //STATE_PENDING 
    const STATE_APPROVED = 2;
    const STATE_REJECTED = 3;

    /**
     * event_offers table status
     * ['near_to_reserve', 'reserve_met', 'won', 'bid', 'proxy_bid']
     */
    const  STATE_WON = 1;
    const  STATE_BID = 2;
    const  STATE_RESERVE_MET = 3;
    const  STATE_NEAR_TO_RESERVE = 4;
    const  STATE_PROXY_BID = 5;

}
