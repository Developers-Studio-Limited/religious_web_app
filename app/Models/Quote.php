<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;
    public function getImageAttribute($value)
    {
        if($value != NULL && $value != '')
        {
            return asset("images/quotes/".$value);
        }
        else
        {
            return $value;
        }
    }
}
