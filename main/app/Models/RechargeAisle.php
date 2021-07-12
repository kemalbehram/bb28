<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RechargeAisle extends Model
{
    protected $casts = ['extend' => 'array', 'status' => 'bool'];
}
