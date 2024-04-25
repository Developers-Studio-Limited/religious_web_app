<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }
    public function taskrecurringDetail()
    {
        return $this->hasMany(TaskRecurringDetail::class);
    }
    public function userTask()
    {
        return $this->hasMany(UserTask::class);
    }
    public function taskDetail()
    {
        return $this->hasMany(TaskDetail::class);
    }
    public function taskTime()
    {
        return $this->hasMany(TaskTime::class);
    }
    public function taskDay()
    {
        return $this->hasMany(TaskDay::class);
    }
    public function countUserTask()
    {
        return $this->hasManyThrough(UserTask::class, TaskTime::class);
    }

}
