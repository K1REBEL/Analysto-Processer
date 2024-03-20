<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $table = 'tracking_results';
    protected $fillable = ['platform','date','time','brand','category','identifier','sku','title','current_seller','last_seller','current_price','last_price'];
}
