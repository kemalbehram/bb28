<?php

namespace App\Models\LottoModule\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class BetRebate extends Model
{
    public $timestamps = false;

    protected $connection = 'main_sql';

    protected $fillable = ['user_id', 'date', 'profit', 'total', 'count', 'desc'];
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
