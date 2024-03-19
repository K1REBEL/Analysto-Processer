<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class URL extends Model
{
    use HasFactory;

    protected $table = 'links';


    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
