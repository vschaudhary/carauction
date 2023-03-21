<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dealership extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'street_name',
        'city',
        'state',
        'zip_code',
        'website',
        'car_stock',
        'status',
        'type_id',
        'user_id',
        'created_by_id'
    ];
}
