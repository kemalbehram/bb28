<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    public $timestamp = false;

    protected $fillable = ['user_id', 'profit', 'desc'];

}
