<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityMessage extends Model
{
    use HasFactory;
    public function read_message()
    {
        return $this->hasMany(ReadMessage::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class,'sender_id');
    }
}
