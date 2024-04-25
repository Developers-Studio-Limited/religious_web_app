<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    protected $guarded = ['id'];
    use HasFactory;
    public function communityRequests()
    {
        return $this->hasMany(CommunityRequest::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function srevice_providers()
    {
        return $this->hasMany(ServiceProvider::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class,'community_user')->withTimestamps();
    }
}
