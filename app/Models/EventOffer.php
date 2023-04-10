<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventOffer extends Model
{
    use HasFactory;

    protected $fillable = ['buyer_offered_amount','seller_offered_amount','status','buyer_id','event_id','type_id','created_by_id'];
}
