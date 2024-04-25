<?php

namespace App\Models;

use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserTask extends Model
{
    use HasFactory;
    public function usertaskDetail()
    {
        return $this->hasMany(UserTaskDetail::class,'user_task_id','id');
    }
    public function task()
    {
        return $this->belongsTo(Task::class,'task_id');
    }
    public function amalCatMonth()
    {
        return $this->belongsTo(Task::class,'task_id')->where('recurring_type','monthly');
    }
    public function amalCatWeekly()
    {
        return $this->belongsTo(Task::class,'task_id')->where('recurring_type','weekly');
    }
    // public function countWeeklyTaskDetail($task_date)
    // {
    //     //dd($task_date);
       
    //     return $this->usertaskDetail()->whereDate('task_date', '>=', $start_date)->whereDate('task_date', '<=', $end_date)->count();
    //     // return $this->usertaskDetail()->whereBetween('task_date', array($start_date,$end_date))->count();
    // }
    public function usersubTaskDetail(){
        return $this->hasManyThrough(UserSubTaskDetail::class, UserTaskDetail::class);
    }
    
    
    
}
