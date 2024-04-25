<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskTime extends Model
{
    use HasFactory;
    public function task()
    {
        return $this->belongsTo(Task::class,'task_id');
    }
    public function userTask(){
        return $this->hasMany(UserTask::class);
    }
}
