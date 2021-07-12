<?php

namespace App\Models;

use App\Models\RedBag;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    //
    protected $fillable = ['message', 'user_id', 'type', 'red_bag_id'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function redbag()
    {
        return $this->hasOne(RedBag::class, 'id', 'red_bag_id');
    }
}
