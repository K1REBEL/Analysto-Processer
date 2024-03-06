<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function tracking()
    {
        return $this->hasMany(Track::class);
    }
}
