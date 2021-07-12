<?php

namespace App\Models\LottoModule\Models;

use Illuminate\Database\Eloquent\Model;

class BetLogDetail extends Model
{
    public $timestamps = false;

    protected $appends = ['name'];

    protected $casts = [
        'amount'      => 'decimal:3',
        'bonus'       => 'decimal:3',
        'odds'        => 'decimal:4',
        'odds_settle' => 'decimal:4',
    ];

    protected $connection = 'main_sql';

    protected $fillable = ['log_id', 'title', 'place', 'amount', 'bonus', 'odds', 'odds_settle', 'extend'];

    public function getNameAttribute()
    {
        $title = $this->title;

        $temp  = explode('-', $title);

        return $temp[1];
    }
}
