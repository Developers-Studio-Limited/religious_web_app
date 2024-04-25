<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskRecurringDetail extends Model
{
    use HasFactory;
    
    public function task()
    {
        return $this->belongsTo(Task::class,'amaal_category_id');
    }

}
