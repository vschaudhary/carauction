<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;

class Event extends Model
{
    use HasApiTokens, SoftDeletes;

    protected $fillable = [ 'reserve_amount', 'started_at', 'completed_at', 'status', 'vehicle_id', 'seller_id', 'type_id', 'created_by_id' ];
}
