<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedBagLog extends Model
{
    protected $connection = 'main_sql';

    protected $fillable = ['parent_id', 'user_id', 'amount'];

    protected $table = 'red_bag_logs';

    public function redBag()
    {
        return $this->hasOne(RedBag::class, 'id', 'parent_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
