<?php
namespace App\Models\LottoModule\Models;

use App\Models\ModelTrait\ModelTrait;
use Watson\Rememberable\Rememberable;
use Illuminate\Database\Eloquent\Model;
use App\Models\LottoModule\Models\BetLog;
// use App\Models\LottoModule\Models\BetStats;
use App\Models\LottoModule\Models\LottoConfig;

class BasicModel extends Model
{
    use Rememberable, ModelTrait;

    public $incrementing = false;

    public $timestamps = false;

    protected $appends = ['lotto_name', 'win_extend', 'bet_count_down', 'open_count_down', 'short_id'];

    protected $casts = ['extend' => 'array', 'id' => 'string'];

    protected $connection = 'lotto_data';

    protected $fillable = ['id', 'open_code', 'lotto_at', 'opened_at', 'mark', 'status', 'extend', 'control', 'logs'];

    protected $hidden = ['extend', 'control', 'logs'];

    public function betLog()
    {
        return $this->hasMany(BetLog::class, 'lotto_index', 'lotto_index');
    }

    public function betStats()
    {
        return $this->hasMany(BetLog::class, 'lotto_index', 'lotto_index');
    }

    public function getBetCountDownAttribute()
    {
        if ($this->status != 1) {
            return -1;
        }
        try {
            $config    = LottoConfig::remember(600)->find($this->lotto_name, ['stop_ahead']);
            $ahead     = $config->stop_ahead;
            $timestamp = strtotime($this->lotto_at);
            return $timestamp - time() - $ahead;
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function getLottoIndexAttribute()
    {
        return $this->lotto_name . ':' . $this->id;
    }

    public function getLottoNameAttribute()
    {
        return $this->lotto_name;
    }

    public function getOpenCountDownAttribute()
    {
        if ($this->status != 1) {
            return -1;
        }
        try {
            $timestamp = strtotime($this->lotto_at);
            return $timestamp - time();
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function getShortIdAttribute()
    {
        $length = mb_strlen($this->id);
        if ($length >= 11) {
            $need = $length - 4;
            return (string) substr($this->id, -$need);
        } else {
            return (string) $this->id;
        }
    }

    public function lastLotto()
    {
        $datetime = date('Y-m-d H:i:s', time());
        return $this->where('status', 1)->where('lotto_at', '<', $datetime)->orderBy('id', 'desc')->first();
    }

    public function newestLotto()
    {
        $config = LottoConfig::remember(600)->find($this->lotto_name, ['stop_ahead']);
        $datetime = date('Y-m-d H:i:s', time() + $config->stop_ahead);
        return $this->where('status', 1)->where('lotto_at', '>', $datetime)->first();
    }
}
