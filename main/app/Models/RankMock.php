<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RankMock extends Model
{
    public $timestamp = false;

    protected $fillable = ['user_id', 'date', 'total'];

    public function rankBonus()
    {
        $bonus = [
            1 => '1588.00',
            2 => '1188.00',
            3 => '888.00',
            4 => '388.00',
            5 => '188.00',
        ];

        for ($i = 6; $i <= 10; $i++) {
            $bonus[$i] = '88.00';
        }

        for ($i = 11; $i <= 20; $i++) {
            $bonus[$i] = '58.00';
        }

        for ($i = 21; $i <= 30; $i++) {
            $bonus[$i] = '28.00';
        }

        return $bonus;
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
