<?php

namespace App\Models\LottoModule\Models;

use App\Models\User;
use App\Packages\Utils\PushEvent;
use App\Models\LottoModule\LottoUtils;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BetAutomator extends Model
{
    use SoftDeletes;

    protected $appends = ['lotto_title'];

    protected $casts = ['balance_min' => 'decimal:3', 'balance_max' => 'decimal:3', 'assign_rule' => 'array'];

    protected $connection = 'main_sql';

    protected $fillable = ['id', 'user_id', 'assign_rule', 'lotto_name', 'play_type', 'lotto_id', 'title', 'bet_stop', 'start_tool', 'bet_direction', 'win_tool', 'lose_tool', 'balance_min', 'balance_max', 'status', 'stop_reason'];

    protected $table = 'bet_automator';

    public function execute()
    {
        $lotto         = LottoUtils::Model($this->lotto_name);
        $current_lotto = $lotto->newestLotto();
        $current_lotto || real()->exception('暂无最新一期');
        $lotto_index = $this->lotto_name . ':' . $current_lotto->id;
        $this->lotto_id > $current_lotto->id && real()->exception('当前期还未开始');
        $this->status !== 1 && real()->exception('自动投注状态不为1');

        $user = User::find($this->user_id);

        $tool_id = null;

        if ($user->agent->status === true) {
            $reason = '代理禁止参与投注';
            $this->stopAutomator($reason);
            real()->exception($reason);
        }

        if ($this->balance_min && $user->wallet->balance <= $this->balance_min) {
            $reason = '促发资金保护下限';
            $this->stopAutomator($reason);
            real()->exception($reason);
        }

        if ($this->balance_max && $user->wallet->balance >= $this->balance_max) {
            $reason = '促发资金保护上限';
            $this->stopAutomator($reason);
            real()->exception($reason);
        }

        // 判断是否已经自动投注过
        $was_bet = BetLog::where('user_id', $this->user_id)
            ->where('auto_id', $this->id)
            ->where('play_type', $this->play_type)
            ->where('lotto_index', $lotto_index)
            ->count();
        $was_bet && real()->exception('该投注已投过');

        // 已投期数
        $lotto_index = $this->lotto_name . ':' . $this->lotto_id;
        $count       = BetLog::where('user_id', $this->user_id)
            ->where('auto_id', $this->id)
            ->where('play_type', $this->play_type)
            ->where('lotto_index', '>=', $lotto_index)
            ->count();

        if ($count >= $this->bet_stop) {
            $reason = '已达自动投注上限';
            $this->stopAutomator($reason);
            real()->exception($reason);
        }

        if ($this->bet_direction === 'direct' || ($count === 0 && $this->bet_direction !== 'assign')) {
            $tool_id = $this->start_tool;
            goto start;
        }

        if ($this->bet_direction === 'open') {
            $last = BetLog::where('user_id', $this->user_id)
                ->where('auto_id', $this->id)
                ->where('lotto_index', '>=', $lotto_index)
                ->orderBy('id', 'desc')
                ->first();
            $last->status !== 2 && real()->exception('上期投注未派奖');
            $tool = BetTools::find($last->tool_id);

            if ($last->bonus > 0) {
                if ($tool->win_tool === 'stop') {
                    $reason = '上次投注中奖后停止';
                    $this->stopAutomator($reason);
                    real()->exception($reason);
                }

                $tool_id = $tool->win_tool ?: $tool->id;
            } else {
                if ($tool->lose_tool === 'stop') {
                    $reason = '上次投注未中奖停止';
                    $this->stopAutomator($reason);
                    real()->exception($reason);
                }
                $tool_id = $tool->lose_tool ?: $tool->id;
            }
        }

        if ($this->bet_direction === 'assign') {
            $last_lotto = LottoUtils::Model($this->lotto_name)
                ->where('id', '<', $current_lotto->id)
                ->orderBy('id', 'desc')->first();

            dump('对号模式上期ID：' . $last_lotto->id);
            if ($last_lotto->win_place === null) {
                real()->exception('上期未开奖');
            }

            $win = array_values(array_intersect($last_lotto->win_place, array_keys($this->assign_rule)));

            if (isset($win[0])) {
                $tool_id = $this->assign_rule[$win[0]];
                if ($tool_id === 'stop') {
                    $reason = '对号下注停止';
                    $this->stopAutomator($reason);
                    real()->exception($reason);
                }
            }
        }

        start:
        $tool_id || real()->exception('tool_id为空');

        $tool = BetTools::find($tool_id);
        $tool === null && real()->exception($tool_id . '模式不存在');
        $checked = $tool->bet_places;

        $place = [];
        $total = 0;
        foreach ($checked as $key => $value) {
            if (!$value) {continue;}
            $place[$key] = [
                'place'  => $key,
                'amount' => $value,
            ];

            $total = bcadd($total, $value, 3);
        }

        // $total = array_sum($checked);
        if ($user->wallet->balance < $total) {
            $reason = '现金账户余额不足';
            $this->stopAutomator($reason);
            real()->exception($reason);
        }
        $temp = $user->bet($this->lotto_name, $current_lotto->id, $this->play_type, $this->id, $tool_id)->place($place, $total);
        $temp || real()->exception('投注失败');

        $this->sendMessage($user);
        return true;
    }

    public function getLottoTitleAttribute()
    {
        $item = LottoConfig::remember(600)->find($this->lotto_name);
        if ($item === null) {
            return null;
        }
        return $item->subtitle;
    }

    public function stopAutomator($reason)
    {
        $this->stop_reason = $reason;
        $this->status      = 2;
        $this->save();
    }

    private function sendMessage($user)
    {
        $message = '您在<' . $this->lotto_title . '>的自动投注已执行';
        $params  = [
            'message' => $message,
            'wallet'  => $user->wallet,
            'action'  => 'lotto_refresh',
        ];
        PushEvent::name('Toast')->toUser($user->id)->data($params);
    }
}
