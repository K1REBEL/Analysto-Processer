<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function tracked_at()
    {
        return $this->hasMany(Track::class);
    }

    public function links()
    {
        return $this->hasMany(URL::class , 'prod_id');
    }
}
