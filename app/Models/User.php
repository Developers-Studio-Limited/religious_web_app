<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens , HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'email',
        'user_image',
        'type',
        'birth_date',
        'gender',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
    public function sahadats()
    {
        return $this->hasMany(Sahadat::class);
    }
    public function taqleed()
    {
        return $this->belongsTo(Taqleed::class,'taqleed_id','id');
    }
    public function message_sender()
    {
        return $this->hasMany(CommunityMessage::class,'sender_id','id');
    }

    public function userAddresses()
    {
        return $this->hasMany(UserAdress::class);
    }

    public function communities()
    {
        return $this->belongsToMany(Community::class,'community_user')->withTimestamps();
    }

}
