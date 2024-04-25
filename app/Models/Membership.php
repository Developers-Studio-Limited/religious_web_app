<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $guarded = ['id'];
    use HasFactory;

    public function service_provider()
    {
        return $this->belongsTo(ServiceProvider::class);
    }
}
