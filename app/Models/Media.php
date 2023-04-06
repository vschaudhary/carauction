<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = ['title','name','path','mime','driver','mediable_type','mediable_id','status','type_id','created_by_id'];
    
}
