<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubTaskDetail extends Model
{
    use HasFactory;
    
    public function taskDetail()
    {
        return $this->belongsTo(TaskDetail::class,'task_detail_id');
    }
}
