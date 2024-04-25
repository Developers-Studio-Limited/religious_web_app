<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $guarded = ['id'];
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(Organization::class, 'organization_providers')->using(OrganizationProvider::class);
    }
}
