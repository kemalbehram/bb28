<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithDrawAisle extends Model
{
    protected $casts = ['extend' => 'array', 'status' => 'bool'];

    protected $fillable = ['user_id', 'type', 'qrcode', 'extend'];

    protected $table = 'withdraw_aisles';
}
