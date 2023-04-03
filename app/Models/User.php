<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\Dealership;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'phone_ext',
        'mobile',
        'contact_preference',
        'role_id',
        'status',
        'type_id',
        'created_by_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function dealership()
    {
        return $this->hasOne('App\Models\Dealership','user_id');
    }

    public function scopeSearch($query, $searchItem)
    {
        return $query->where('email', 'LIKE', '%' . $searchItem .'%');
    }

    public function scopeActive($query)
    {
        return $query->where('role_id', 2)->where('status', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('role_id', 2)->where('status', 0);
    }

    public function scopeAll($query)
    {
        return $query->where('role_id', 2);
    }

    public function scopePendingApproval($query)
    {
        return $query->where('type_id', 0);
    }

}
