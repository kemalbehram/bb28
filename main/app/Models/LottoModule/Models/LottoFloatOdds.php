<?php

namespace App\Models\LottoModule\Models;

use Watson\Rememberable\Rememberable;
use Illuminate\Database\Eloquent\Model;
use App\Models\LottoModule\Models\LottoPlayType;

class LottoFloatOdds extends Model
{
    use Rememberable;

    public $rememberCacheTag = 'lottoFloatOdds';

    protected $casts = ['extend' => 'array', 'bet_people' => 'int', 'win_people' => 'int', 'bet_total' => 'float'];

    protected $connection = 'main_sql';

    protected $fillable = ['lotto_index', 'bet_total', 'odds_settle', 'bet_people', 'win_people', 'reset', 'extend'];

    protected $hidden = ['id', 'odds_settle', 'created_at', 'updated_at', 'reset'];

    protected $table = 'lotto_float_odds';

    public function basicOdds($lotto_name, $lotto_id, $bet = [], $rand = false)
    {
        $key        = md5(json_encode($bet));
        $cache_name = 'basicOddsFloat:' . $lotto_name . $lotto_id . $rand . $key;
        return cache()->remember($cache_name, 10, function () use ($rand, $bet) {
            $result     = [];

            $bet_places = LottoPlayType::find(1)->place;
            // dd($bet_places);
            $total = ['fd' => 0, 'st' => 0, 'el' => 0];

            foreach ($bet as $place => $amount) {
                if (strpos($place, 'fd') !== false) {
                    $total['fd'] += $amount;
                }

                if (strpos($place, 'st') !== false) {
                    $total['st'] += $amount;
                }

                if (strpos($place, 'el') !== false) {
                    $total['el'] += $amount;
                }
            }

            foreach ($bet_places as $value) {
                $type = $value['group'];
                if (in_array($type, ['fd', 'st', 'el']) === false) {
                    continue;
                }

                $place = $value['place'];
                $odds  = $value['odds'];
                // if ($rand === true ) {
                //     $odds = bcmul($value['odds'], mt_rand(991000, 998000) / 1000000, 4);
                //     $odds = formatOdds($odds, 7);
                // } else

                if (isset($bet[$place])) {
                    $odds = bcdiv($total[$type], $bet[$place], 4);
                    $odds = formatOdds($odds, 7);
                }

                $result[$place] = $odds;
            }

            return $result;
        });
    }

    public function computeCurrentOdds($lotto_name, $lotto_id, $bet, $rand = false)
    {
        $lotto_index = $lotto_name . ':' . $lotto_id;
        $params      = $this->where('lotto_index', $lotto_index)->first();

        return $this->basicOdds($lotto_name, $lotto_id, (array) $bet, $rand);
    }
}
