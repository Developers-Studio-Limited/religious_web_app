<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTaskDetail extends Model
{
    use HasFactory;

    public function userTask()
    {
        return $this->belongsTo(UserTask::class,'user_task_id');
    }
    public function usersubTaskDetail()
    {
        return $this->hasMany(UserSubTaskDetail::class,'task_detail_id','id');
    }
}
