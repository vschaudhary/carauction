<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use App\Constant\Constants;
use Illuminate\Support\Facades\Auth;

class Event extends Model
{
    use HasApiTokens, SoftDeletes;

    protected $fillable = ['type', 'reserve_amount', 'started_at', 'completed_at', 'status', 'vehicle_id', 'seller_id', 'type_id', 'created_by_id' ];

    //Return event Vehicle dtails
    public function vehicle()
    {
        return $this->hasOne(Vehicle::class, 'id', 'vehicle_id');
    }

    //Return live auctions
    public function scopeLiveAuction($query)
    {
        return $query->where('type', Constants::TYPE_AUCTION)->where('status', Constants::STATE_LIVE);
    }

    //Return live appraisal auctions (with 0 reserve amount)
    public function scopeLiveAppraisal($query)
    {
        return $query->where('type', Constants::TYPE_LIVE_APPRAISAL)->where('status', Constants::STATE_LIVE);
    }

    //Return auctions scheduled for the next day
    public function scopeRunList($query)
    {
        return $query->where('type',Constants::TYPE_AUCTION)->where('status', Constants::STATE_RUN);
    }

    //Return completed auctions (after 2hrs)
    public function scopeEndedAuction($query)
    {
        return $query->where('type',Constants::TYPE_AUCTION)->where('status', Constants::STATE_ENDED);
    }   

    //Return private market events to sell
    public function scopeSellerPrivateMarket($query)
    {
        return $query->where('type', Constants::TYPE_PRIVATE_MARKET)->where('seller_id', Auth::id());
    }

    //Return  private market events to buy
    public function scopeBuyerPrivateMarket($query)
    {
        return $query->where('type', Constants::TYPE_PRIVATE_MARKET)->where('seller_id', '!=', Auth::id());
    }
}
