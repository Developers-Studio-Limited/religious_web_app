<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded = ['id'];
    use HasFactory;
    public function community()
    {
        return $this->belongsTo(Community::class);
    }
}
