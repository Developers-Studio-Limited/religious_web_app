<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskDetail extends Model
{
    use HasFactory;
    public function amaalDetail()
    {
        return $this->belongsTo(AmaalDetail::class,'amaal_detail_id');
    }
    public function task()
    {
        return $this->belongsTo(Task::class,'amaal_category_id','id');
    }
}
