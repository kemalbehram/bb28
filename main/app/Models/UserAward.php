<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Models\UserWallet\WalletLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAward extends Model
{
    use SoftDeletes;

    public $timestamp = false;

    protected $appends = ['title'];

    protected $casts = ['amount' => 'decimal:3', 'extend' => 'array'];

    protected $connection = 'main_sql';

    protected $fillable = ['user_id', 'amount', 'type', 'date', 'extend', 'unique'];

    protected $hidden = ['deleted_at'];

    protected $ref_5_loss = [
        1 => 0.0044,
        2 => 0.0020,
        3 => 0.0016,
        4 => 0.0012,
        5 => 0.0008,
    ];

    protected $title_maps = [
        'ref_1_bet_rebate'   => '直属下线投注反水',
        'ref_5_loss_rebate'  => '五级下线亏损周返',
        'user_level_upgrade' => '用户等级晋级奖励',
        'rebate'             => '下线流水返水',
    ];

    public function getTitleAttribute()
    {
        // try {
        //     $config = OptionAward::remember(600)->find($this->type);
        //     return $config->title;
        // } catch (\Throwable $th) {
        //     return $this->title_maps[$this->type];
        // }
        $result = WalletLog::trans($this->unique);
        return $result;
    }

    public function settleRefBetLevel1($ref_id, $date)
    {
        $award_rate = option('ref_1_bet_rebate');

        if ($award_rate === null || $award_rate == 0) {
            return false;
        }

        $type = 'ref_1_bet_rebate';

        $was = $this->where('type', $type)->where('user_id', $ref_id)->where('date', $date)->first();
        if ($was !== null) {
            return $ref_id . ' was settle...';
        }

        $child     = UserReference::where('ref_id', $ref_id)->where('has_rebate', '1')->get(['user_id']);
        $child_ids = array_column($child->toArray(), 'user_id');

        //查询下级用户的投注记录
        $raw_date  = DB::raw('date_format(`confirmed_at`,"%Y-%m-%d")');
        $raw_total = DB::raw('sum(total) AS total');
        $bets      = DB::table('bet_logs')->whereIn('user_id', $child_ids)
            ->where($raw_date, $date)
            ->where('effective', '1')
            ->groupBy('user_id')
            ->get(['user_id', $raw_total]);

        $adopt = [];
        //排除投注总额低于100的
        foreach ($bets as $key => $value) {
            if ($value->total < 100) {
                continue;
            }
            $adopt[] = (array) $value;
        }

        if (count($adopt) == 0) {
            return 'no adopt...';
        }

        dump($adopt);

        $total  = array_column($adopt, 'total');
        $total  = array_sum($total);
        $amount = bcmul($total, $award_rate, 3);

        $create = [
            'user_id' => $ref_id,
            'type'    => $type,
            'date'    => $date,
            'amount'  => $amount,
            'extend'  => $adopt,
        ];

        $create = $this->create($create);
        $create || real()->exception('award.receive.failed.retry');

        $user   = User::find($ref_id);
        $wallet = $user->wallet->balance('award.receive.' . $type, $create->id)->plus($amount);

        return true;
    }

    public function settleRefLossLevel5($ref_id, $date_start, $date_end, $date)
    {
        $type = 'ref_5_loss_rebate';

        $was = $this->where('type', $type)->where('user_id', $ref_id)->where('date', $date)->first();
        if ($was !== null) {
            return $ref_id . ' was settle...';
        }

        $child = UserReference::with('children')->where('ref_str', 'regexp', $ref_id)->where('has_rebate', '1')->get();

        //组装下级用户 共5级
        $position = [];
        foreach ($child as $value) {
            $temp                      = array_flip($value->ref_str);
            $position[$value->user_id] = [
                'position'  => strval($temp[$ref_id] + 1),
                'bet_total' => 0,
                'bet_bonus' => 0,
                'bet_loss'  => 0,
            ];
        }

        $child_ids = array_keys($position);
        //查询下级用户的投注记录
        $date_raw = DB::raw('date_format(`confirmed_at`,"%Y-%m-%d")');
        $bets     = DB::table('bet_logs')->whereIn('user_id', $child_ids)
            ->where($date_raw, '>=', $date_start)
            ->where($date_raw, '<=', $date_end)
            ->where('effective', '1')
            ->groupBy('user_id')
            ->get([
                'user_id',
                DB::raw('sum(total) AS total'),
                DB::raw('sum(bonus) AS bonus'),
            ]);

        if (count($bets->toArray()) == 0) {
            return true;
        }

        // dd($bets);

        //重组下级用户投注总额
        foreach ($bets as $value) {
            $position[$value->user_id]['bet_total'] = $value->total;
            $position[$value->user_id]['bet_bonus'] = $value->bonus;
            $position[$value->user_id]['bet_loss']  = toDecimal($value->bonus - $value->total);
        }

        //统计所有的总和
        $comm_total = 0;
        $config     = $this->ref_5_loss;
        foreach ($position as $key => $value) {
            if ($value['bet_loss'] > -100) {
                unset($position[$key]);
                continue;
            }
            $proportion   = $config[$value['position']];
            $current_rate = bcmul(abs($value['bet_loss']), $proportion, 3);
            $comm_total += $current_rate;
        }

        if ($comm_total <= 0) {
            return 'no comm total';
        }

        dump($comm_total, $position);

        $create = [
            'user_id' => $ref_id,
            'type'    => $type,
            'date'    => $date,
            'amount'  => $comm_total,
            'extend'  => compact('date_start', 'date_end', 'date', 'position'),
        ];

        // dd($create);

        $create = $this->create($create);
        $create || real()->exception('award.receive.failed.retry');

        $user   = User::find($ref_id);
        $wallet = $user->wallet->balance('award.receive.' . $type, $create->id)->plus($comm_total);

        return true;
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
