<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobsApplied extends Model
{
    use HasFactory;
    protected $table = "jobs_applied";

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function jobs()
    {
        return $this->belongsTo(Job::class,'job_id');
    }
}
