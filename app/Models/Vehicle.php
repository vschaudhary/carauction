<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use  HasFactory, SoftDeletes;

    protected $fillable =  ['title', 'model_name', 'model_year', 'make', 'body_type', 'distance_covered', 'location', 'amount', 'description', 'status', 'user_id', 'type_id', 'created_by_id' ];
}
