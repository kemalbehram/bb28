<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    protected $fillable = ['admin_id', 'referer', 'user_agent', 'path', 'ip'];
}
