<?php

namespace App\Http\Controllers\Client;

use App\Models\UserAward;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ModelTrait\ThisUserTrait;
use App\Models\LottoModule\Models\BetLog;

class UserLevelController extends Controller
{
    use ThisUserTrait;

    public function day()
    {
        $this->user->CheckIsTrial();
        $user_level   = $this->user->userLevel();
        $level_config = config('app.user_level');
        if ($user_level['level_index'] == 0) {
            real()->exception('等级不足，无法领取。快去提升等级吧！');
        }
        $user_level['level_index'] = 1;
        $level_info                = $level_config['rule_table'][$user_level['level_index']];
        $month_day                 = date('t');

        //查看当天是否已领取
        $has_received = UserAward::where('user_id', $this->user->id)->where('date', date('Y-m-d'))->first();
        if ($has_received != null) {
            real()->exception('今日已经领取啦,请明日领取');
        }

        // {"exp_diff": 999657,
        //     "exp_title": "200万",
        //     "level_exp": 2000343,
        //     "exp_target": 2000000,
        //     "gift_month": 80,
        //     "gift_total": 562,
        //     "gift_wechat": null,
        //     "level_index": 4,
        //     "level_title": "Lv. 04",
        //     "gift_upgrade": 288}
        DB::beginTransaction();
        $model          = new UserAward();
        $model->user_id = $this->user->id;
        $model->unique  = 1;
        $model->amount  = $level_info['gift_month'] / $month_day;
        $model->type    = 'user_level_day';
        $model->date    = date('Y-m-d');
        $extend_info    = [
            'exp_diff'     => $user_level['exp_diff'],
            'exp_title'    => $level_info['exp_title'],
            'level_exp'    => $user_level['level_exp'],
            'exp_target'   => $level_info['exp_target'],
            'gift_month'   => $level_info['gift_month'],
            'gift_total'   => $level_info['gift_total'],
            'gift_wechat'  => $level_info['gift_wechat'],
            'level_index'  => $level_info['level_index'],
            'level_title'  => $level_info['level_title'],
            'gift_upgrade' => $level_info['gift_upgrade'],
        ];
        //dd($extend_info);
        $model->extend = json_encode($extend_info);
        $model->save();
        $wallet = $this->user->wallet->balance('award.receive.user_level_day', $model->id);
        $wallet->plus($level_info['gift_month'] / $month_day);
        DB::commit();
        $result = $model->amount;
        return real($result)->success('成功领取每日俸禄');
    }

    public function info()
    {
        //获取总投注
        $bet_total1 = BetLog::where('user_id', $this->user->id)->where(function ($query) {
            $query->where('play_type', 'room3')
                ->orWhere('play_type', 'room4')
                ->orWhere('play_type', 'room5')
                ->orWhere('play_type', 'room6')
                ->orWhere('play_type', 'room7')
                ->orWhere('play_type', 'room8');
        })->sum('total');
        $bet_total2 = BetLog::where('user_id', $this->user->id)->where(function ($query) {
            $query->where('play_type', 'room1')
                ->orWhere('play_type', 'room2');
        })->sum('total');
        //获取已领取的礼金和俸禄
        $awards = UserAward::where('user_id', $this->user->id)->where(function ($query) {
            $query->where('type', 'user_level_day')
                ->orwhere('type', 'user_level_upgrade');
        })->sum('amount');
        $result = [
            'total_bet' => $bet_total1 + $bet_total2 / 2,
            'award'     => $awards,
        ];

        return real($result)->success();
    }

    public function rule()
    {
        $result = config('app.user_level');
        return real($result)->success();
    }
}
