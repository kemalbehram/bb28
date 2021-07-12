<?php
namespace App\Models\LottoModule\Traits;

use Illuminate\Support\Facades\Cache;
use App\Models\LottoModule\LottoFormula;
use App\Models\LottoModule\LottoWinPlace;
use App\Models\LottoModule\Models\LottoFloatOdds;

trait Lotto28Trait
{
    public function floatOdds($newest_id)
    {
        // $reset_total  = toDecimal(mt_rand(10000000000, 11000000000) / 1000);
        $reset_total  = toDecimal(mt_rand(1000000000, 1100000000) / 1000);
        $fd_min       = env('LOTTO_28_FD_MIN', 9800);
        $fd_max       = env('LOTTO_28_FD_MAX', 9850);
        $reset_odds   = mt_rand($fd_min, $fd_max) / 10000;
        $reset_people = mt_rand(200, 240);
        $create       = [
            'bet_total'   => $reset_total,
            'odds_settle' => $reset_odds,
            'bet_people'  => $reset_people,
            'reset'       => 1,
            'extend'      => [
                'fd' => [
                    // 'bet_total'  => mt_rand(1200000000, 2300000000) / 1000,
                    'bet_total'  => mt_rand(120000000, 230000000) / 1000,
                    'bet_people' => mt_rand(0, 20),
                    'win_people' => mt_rand(0, 20),
                ],

                'st' => [
                    // 'bet_total'  => mt_rand(1200000000, 2300000000) / 1000,
                    'bet_total'  => mt_rand(120000000, 230000000) / 1000,
                    'bet_people' => mt_rand(0, 20),
                    'win_people' => mt_rand(0, 20),
                ],

                'el' => [
                    // 'bet_total'  => mt_rand(1200000000, 2300000000) / 1000,
                    'bet_total'  => mt_rand(120000000, 230000000) / 1000,
                    'bet_people' => mt_rand(0, 20),
                    'win_people' => mt_rand(0, 20),
                ],
            ],
        ];

        $float_odds = $this->hasOne(LottoFloatOdds::class, 'lotto_index', 'lotto_index');

        if ($float_odds->first() == null) {
            if ($this->id > $newest_id) {
                return null;
            }
            $float_odds = $float_odds->create($create);
        } else {
            $float_odds = $float_odds->remember(600)->first();
        }

        $cache_name = 'floatOddsUpdate:' . $newest_id;
        if ($this->status == 1 && $this->id == $newest_id && cache()->has($cache_name) == false) {
            $temp = toDecimal(mt_rand(20000, 100000000) / 1000);
            $float_odds->increment('bet_total', $temp);
            cache()->put($cache_name, 1, 10);
        }

        if ($this->status != 1) {
            $max                    = $float_odds->bet_people - 20;
            $min                    = intval($max - 10);
            $float_odds->win_people = mt_rand($min, $max);
            $float_odds->save();
        }

        $float_odds->bet_total = toDecimal($float_odds->bet_total);
        return $float_odds;
    }

    public function getWinExtElAttribute()
    {
        if ($this->open_code === null) {
            return null;
        }

        $formula            = LottoFormula::basic11($this->open_code);
        $formula['code_he'] = sprintf('%02d', $formula['code_he']);
        return $formula;
    }

    public function getWinExtStAttribute()
    {
        if ($this->open_code === null) {
            return null;
        }

        $formula            = LottoFormula::basic16($this->open_code);
        $formula['code_he'] = sprintf('%02d', $formula['code_he']);
        return $formula;
    }

    public function getWinExtendAttribute()
    {
        if ($this->open_code === null) {
            return null;
        }
        $lotto_name = $this->lotto_name;
        $formula    = LottoFormula::$lotto_name($this->open_code);
        $he         = $formula['code_he'];

        $win_place = LottoWinPlace::lotto28($this->open_code, $lotto_name);

        $result['code_arr'] = $formula['code_arr'];
        $result['code_str'] = $formula['code_str'];
        $result['code_he']  = sprintf('%02d', $he);

        $he >= 14 && $result['code_bos']    = '大';
        $he <= 13 && $result['code_bos']    = '小';
        $he % 2 == 1 && $result['code_sod'] = '单';
        $he % 2 == 0 && $result['code_sod'] = '双';

        $code   = $result['code_arr'];
        $unique = array_unique($code);
        $unique = count($unique);

        $is_dui = false;
        if ($unique === 2) {
            $is_dui = true;
        }

        $is_bao = false;
        if ($unique === 1) {
            $is_bao = true;
        }

        $is_shun = false;
        asort($code);
        $str = implode('', $code);
        if (preg_match('/^(0(?=1)|1(?=2)|2(?=3)|3(?=4)|4(?=5)|5(?=6)|6(?=7)|7(?=8)|8(?=9)){2}\d$/', $str)) {
            $is_shun = true;
        }

        if ($str === '019' || $str === '089') {
            $is_shun = true;
        }
        $result['code_dui']   = $is_dui ? true : false;
        $result['code_baozi'] = $is_bao ? true : false;
        $result['code_shun']  = $is_shun ? true : false;

        // $ts = ['ts_leo' => '豹', 'ts_pai' => '对', 'ts_jun' => '顺', 'ts_juh' => '半', 'ts_oth' => '杂'];
        // $result['code_ts'] = $ts[$win_place[0]];
        //特殊玩法-龙
        $long_arr = ['00', '03', '06', '09', '12', '15', '18', '21', '24', '27'];
        if (in_array($he, $long_arr)) {
            $result['code_long'] = true;
        } else {
            $result['code_long'] = false;
        }
        //特殊玩法-虎
        $hu_arr = ['01', '04', '07', '10', '13', '16', '19', '22', '25'];
        if (in_array($he, $hu_arr)) {
            $result['code_hu'] = true;
        } else {
            $result['code_hu'] = false;
        }
        //特殊玩法-豹
        $bao_arr = ['02', '05', '08', '11', '14', '17', '20', '23', '26'];
        if (in_array($he, $bao_arr)) {
            $result['code_bao'] = true;
        } else {
            $result['code_bao'] = false;
        }
        //dd($result);
        return $result;
    }

    public function openCheck($play_type = 'he')
    {
        $func = function ($data, $title) {
            $result          = [];
            $result['label'] = '第' . $title . '区 取' . implode('/', $data['label']) . '位';
            foreach ($data['code'] as &$value) {
                $value = sprintf('%02d', $value);
            }
            $result['code']  = implode(' ', $data['code']);
            $result['total'] = $data['total'];
            $result['mod']   = $data['mod'];

            return $result;
        };

        $lotto_name = $this->lotto_name;
        $formula    = LottoFormula::$lotto_name($this->open_code);

        $win_extend = $this->win_extend;

        if ($play_type == 'st') {
            $win_extend = $this->win_ext_st;
            $sec_intro  = '分区和值除以6的余数 + 1';
            $formula    = LottoFormula::basic16($this->open_code);
        } elseif ($play_type == 'el') {
            $win_extend = $this->win_ext_el;
            $sec_intro  = '分区和值除以6的余数 + 1';
            $formula    = LottoFormula::basic11($this->open_code);
        } else {
            $win_extend = $this->win_extend;
            $sec_intro  = '取分区和值的尾数';
            $lotto_name = $this->lotto_name;
            $formula    = LottoFormula::$lotto_name($this->open_code);
        }

        $open_code = implode(' ', $formula['source']);

        $sec_1 = $func($formula['extend']['sec_1'], '一');
        $sec_2 = $func($formula['extend']['sec_2'], '二');
        if (isset($formula['extend']['sec_3'])) {
            $sec_3 = $func($formula['extend']['sec_3'], '三');
        } else {
            $sec_3 = null;
        }
        $section = [$sec_1, $sec_2, $sec_3];

        $result = [
            'id'        => $this->id,
            'lotto_at'  => $this->lotto_at,
            'open_code' => $open_code,
            'section'   => $section,
            'sec_intro' => $sec_intro,
            'code_he'   => $win_extend['code_he'],
        ];

        return $result;
    }

    public function placeExtend($refresh = false)
    {
        $last = $this->where('status', '2')->orderBy('id', 'desc')->first();

        $cache_name = 'PlaceExtendLotto28:' . __CLASS__ . ':' . $last->id;
        $cache_has  = cache()->has($cache_name);

        if ($cache_has === false || $refresh === true) {
            $data = $this->where('status', '!=', 1)->whereNotNull('open_code')->orderBy('id', 'desc')->remember(60)->take(5000);

            $items = $data->get();

            //$count = count($items->toArray());
            $count  = 5000;
            $result = [];

            for ($i = 0; $i <= 27; $i++) {
                $key          = sprintf('%02d', $i);
                $result[$key] = ['miss' => sprintf('%03d', $count), 'hot' => '00'];
            }

            $index = 0;
            foreach ($items as $index => $item) {
                $code_he = $item->win_extend['code_he'];
                if ($index < 1000) {
                    $result[$code_he]['hot'] = sprintf('%02d', $result[$code_he]['hot'] + 1);
                }
                if ($result[$code_he]['miss'] == $count) {
                    $result[$code_he]['miss'] = sprintf('%03d', $index);
                }

                $index++;
            }

            cache()->put($cache_name, $result);
        }

        return cache()->get($cache_name);
    }
}
