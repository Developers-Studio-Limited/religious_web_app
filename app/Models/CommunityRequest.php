<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityRequest extends Model
{
    use HasFactory;
    public function community()
    {
        return $this->belongsTo(Community::class,'community_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function userReq()
    {
        return $this->belongsTo(User::class,'request_to');
    }
}
