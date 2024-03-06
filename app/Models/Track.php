<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    use HasFactory;

    public function tracked_by()
    {
        return $this->belongsTo(Employee::class);
    }

    public function tracking()
    {
        return $this->belongsTo(Product::class);
    }

    public function tracks()
    {
        return $this->belongsTo(URL::class);
    }
}
