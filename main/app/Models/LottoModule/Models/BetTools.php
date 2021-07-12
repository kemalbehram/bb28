<?php

namespace App\Models\LottoModule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BetTools extends Model
{
    use SoftDeletes;

    protected $casts = ['bet_places' => 'array'];

    protected $connection = 'main_sql';

    protected $fillable = ['id', 'user_id', 'lotto_name', 'play_type', 'title', 'bet_places', 'total', 'win_tool', 'lose_tool'];

    protected $table = 'bet_tools';
}
