<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    // public function getIconAttribute($value)
    // {
    //     if($value != NULL && $value != '')
    //     {
    //         return asset("images/categories/".$value);
    //     }
    //     else
    //     {
    //         return $value;
    //     }
    // }
    public function parentCategory()
    {
        return $this->belongsTo(Category::class,'parent_id');
    }
    public function task(){
        return $this->hasOne(Task::class);
    }

}
