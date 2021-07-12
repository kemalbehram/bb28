<?php

namespace App\Models\LottoModule\Models;

use App\Models\ModelTrait\ModelTrait;
use Watson\Rememberable\Rememberable;
use Illuminate\Database\Eloquent\Model;

class LottoPlayType extends Model
{
    use ModelTrait, Rememberable;

    // public function getPlaceAttribute()
    // {
    //     $lotto28 = config('lotto._lotto28.bet_places');
    //     $places     = $lotto28;
    //     $places_arr = [];
    //     foreach ($places as $value) {
    //         if ($value['group'] == $this->name) {
    //             $places_arr[$value['place']] = $value;
    //         }
    //     }
    //     return $places_arr;
    // }

    public $incrementing = false;

    public $rememberCacheTag = 'lotto_play_type';

    //protected $appends = ['place'];

    protected $casts = ['tools' => 'array', 'subtitle' => 'array', 'model' => 'array', 'is_open' => 'bool', 'place' => 'array', 'bet_limit' => 'array', 'agent_rebate' => 'array', 'single_limit' => 'array', 'single_condition' => 'array', 'comb_limit' => 'array', 'comb_condition' => 'array', 'recharge_limit' => 'object'];

    protected $connection = 'main_sql';

    protected $fillable = ['name', 'title', 'subtitle', 'tools', 'is_open', 'model', 'place', 'rules', 'single_limit', 'single_condition', 'comb_limit', 'comb_condition'];

    protected $hidden = ['created_at', 'updated_at'];

    public static function getFormatResult()
    {
        $play_types = self::get();
        $result     = [];
        foreach ($play_types as $val) {
            $result[$val['name']] = $val;
            // $val['place']         = json_decode($val['place']);
        }
        return $result;
    }
}
