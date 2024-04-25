<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
     public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function assigned_to()
    {
        return $this->belongsTo(User::class, 'assign_to');
    }
    public function subCategory()
    {
        return $this->belongsTo(Category::class,'category_id');
    }
    public function community()
    {
        return $this->belongsTo(Community::class,'community_id');
    }
    public function userJob()
    {
        return $this->hasMany(UserJob::class);
    }
    public function jobMarhoom()
    {
        return $this->hasMany(JobMarhoom::class);
    }
    public function jobKura()
    {
        return $this->hasMany(JobKura::class);
    }

    public function disputes()
    {
        return $this->hasMany(Dispute::class);
    }
}
