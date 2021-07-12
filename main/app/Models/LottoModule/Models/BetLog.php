<?php
namespace App\Models\LottoModule\Models;

use App\Models\User;
use Watson\Rememberable\Rememberable;
use Illuminate\Database\Eloquent\Model;
use App\Models\LottoModule\LottoFormula;

class BetLog extends Model
{
    use Rememberable;

    public $rememberCacheTag = 'bet_log';

    protected $appends = ['lotto_name', 'lotto_id', 'lotto_title', 'profit', 'short_id', 'win_extend'];

    protected $casts = [
        'bets'   => 'object',
        'extend' => 'object',
        'total'  => 'decimal:3',
        'amount' => 'decimal:3',
        'bonus'  => 'decimal:3',
    ];

    protected $connection = 'main_sql';

    protected $eff_config = [
        'he' => 21,
        'fd' => 21,
        'st' => 12,
        'el' => 8,
    ];

    protected $fillable = ['user_id', 'auto_id', 'tool_id', 'lotto_index', 'play_type', 'total', 'amount', 'bonus', 'extend', 'confirmed_at', 'open_code', 'trial', 'effective'];

    protected $hidden = ['extend'];

    public function betPlaceTotal($lotto_index, $user_id)
    {
        $bet_log = $this->where('user_id', $user_id)->where('lotto_index', $lotto_index)->get();
        $place   = [];
        foreach ($bet_log as $value) {
            foreach ($value->details as $detail) {
                $temp                  = isset($place[$detail->place]) ? $place[$detail->place] + $detail->amount : $detail->amount;
                $place[$detail->place] = sprintf('%.3f', $temp);
            }
        }

        return (object) $place;
    }

    public function details()
    {
        return $this->hasMany(BetLogDetail::class, 'log_id', 'id');
    }

    public function getLottoIconAttribute()
    {
        $item = LottoConfig::remember(600)->find($this->lotto_name);
        if ($item === null) {
            return null;
        }
        return $item->icon_font;
    }

    public function getLottoIdAttribute()
    {
        if (!$this->lotto_index) {
            return null;
        }
        $temp = explode(':', $this->lotto_index);
        return $temp[1];
    }

    public function getLottoNameAttribute()
    {
        if (!$this->lotto_index) {
            return null;
        }
        $temp = explode(':', $this->lotto_index);
        return $temp[0];
    }

    public function getLottoTitleAttribute()
    {
        if (!$this->lotto_name) {
            return null;
        }

        $item = LottoConfig::remember(600)->find($this->lotto_name);
        if ($item === null) {
            return null;
        }
        return $item->subtitle;
    }

    public function getProfitAttribute()
    {
        if ($this->status == 2) {
            return sprintf('%01.3f', $this->bonus - $this->total);
        } else {
            return null;
        }
    }

    public function getShortIdAttribute()
    {
        if (!$this->lotto_id) {
            return null;
        }
        $length = mb_strlen($this->lotto_id);
        if ($length >= 11) {
            $need = $length - 4;
            return (string) substr($this->lotto_id, -$need);
        } else {
            return (string) $this->lotto_id;
        }
    }

    public function getStrPlaceAttribute()
    {
        if (!$this->details) {
            return null;
        }

        $result = [];

        foreach ($this->details as $value) {
            $result[] = $value->name;
        }

        return implode('、', $result);
    }

    public function getWinExtendAttribute()
    {
        if ($this->open_code != null) {
            $lotto_name                                        = $this->lotto_name;
            $formula                                           = LottoFormula::$lotto_name($this->open_code);
            $result['code_he']                                 = $formula['code_he'];
            $result['code_arr']                                = $formula['code_arr'];
            $result['code_he'] >= 14 && $result['code_bos']    = '大';
            $result['code_he'] <= 13 && $result['code_bos']    = '小';
            $result['code_he'] % 2 == 1 && $result['code_sod'] = '单';
            $result['code_he'] % 2 == 0 && $result['code_sod'] = '双';

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
            if (in_array($result['code_he'], $long_arr)) {
                $result['code_long'] = true;
            } else {
                $result['code_long'] = false;
            }
            //特殊玩法-虎
            $hu_arr = ['01', '04', '07', '10', '13', '16', '19', '22', '25'];
            if (in_array($result['code_he'], $hu_arr)) {
                $result['code_hu'] = true;
            } else {
                $result['code_hu'] = false;
            }
            //特殊玩法-豹
            $bao_arr = ['02', '05', '08', '11', '14', '17', '20', '23', '26'];
            if (in_array($result['code_he'], $bao_arr)) {
                $result['code_bao'] = true;
            } else {
                $result['code_bao'] = false;
            }

            return $result;
        } else {
            return null;
        }
    }

    // 更新是否为有效流水
    public function updateEffective($lotto_name, $lotto_id, $user_id)
    {
        $user        = User::find($user_id);
        $lotto_index = $lotto_name . ':' . $lotto_id;
        $bet_log     = $this->where('user_id', $user_id)
            ->where('lotto_index', $lotto_index)
            ->get();

        $place       = [];
        $amount_none = [];
        $amount_has  = [];
        foreach ($bet_log as $value) {
            foreach ($value->details as $detail) {
                //如果是浮动 ，就将下注号码归类为 固定中
                if ($value->play_type === 'fd' || $value->play_type === 'he') {
                    $value->play_type = 'he';
                    $detail->place    = str_replace('fd', 'he', $detail->place);
                }

                if (isset($place[$value->play_type][$detail->place])) {
                    $place[$value->play_type][$detail->place] += $detail->amount;
                } else {
                    $place[$value->play_type][$detail->place] = $detail->amount;
                }

                if ($value->effective === 0) {
                    if (isset($amount_none[$value->play_type][$detail->place])) {
                        $amount_none[$value->play_type][$detail->place] += $detail->amount;
                    } else {
                        $amount_none[$value->play_type][$detail->place] = $detail->amount;
                    }
                }

                if ($value->effective === 1) {
                    if (isset($amount_has[$value->play_type][$detail->place])) {
                        $amount_has[$value->play_type][$detail->place] += $detail->amount;
                    } else {
                        $amount_has[$value->play_type][$detail->place] = $detail->amount;
                    }
                }
            }
        }

        foreach ($place as $type => $value) {
            $count     = count($value);
            $effective = 1;
            if (isset($this->eff_config[$type])) {
                $effective = ($count < $this->eff_config[$type]) ? 1 : 2;
            }

            $types = [$type];
            if ($type === 'he' || $type === 'fd') {
                $types = ['he', 'fd'];
            }

            $this->where('lotto_index', $lotto_index)
                ->where('user_id', $user_id)
                ->whereIn('play_type', $types)
                ->update(['effective' => $effective]);

            // 如果是有效流水
            if ($effective === 1 && isset($amount_none[$type])) {
                $amount = array_sum($amount_none[$type]);
                $user->option->increment('bet_usable', $amount);
            }

            //如果是无效流水
            if ($effective === 2 && isset($amount_has[$type])) {
                $amount = array_sum($amount_has[$type]);
                $user->option->decrement('bet_usable', $amount);
            }
        }

        return true;
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
