<?php

namespace App\Http\Controllers\Admin;

use App\Models\UserAward;
use Illuminate\Http\Request;
use App\Models\UserReference;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\LottoModule\Models\BetLog;
use App\Models\LottoModule\Models\LottoPlayType;

class TurnOverRebateController extends Controller
{
    public function get(Request $request)
    {
        $room_info    = LottoPlayType::where('name', $request->room_name)->first()->toArray();
        $agent_rebate = $room_info['agent_rebate'];
        $start        = date('Y-m-d 00:00:00', strtotime('-1 day'));
        $end          = date('Y-m-d 23:59:59', strtotime('-1 day'));
        $user_list    = BetLog::whereBetween('created_at', [$start, $end])
            ->groupBy('user_id')
            ->with('user:id,nickname,trial')
            ->select('user_id')
            ->get();
        DB::beginTransaction();
        // $model = BetLog::query();

        foreach ($user_list as $value) {

            if (!$value->user->trial) {
                //     \DB::enableQueryLog();
                $agent_turnover = BetLog::whereBetween('created_at', [$start, $end])
                    ->havingRaw('sum(total) >= ' . $agent_rebate[0]['agent_money'])
                    ->havingRaw('count(total) >= ' . $agent_rebate[0]['agent_amount'])
                    ->where('effective', 1)->where('status', 2)
                    ->where('play_type', $request->room_name)
                    ->where('user_id', $value->user_id)
                    ->selectRaw('sum(total) as amount,count(total) as total')
                    // ->groupBy(DB::raw("SUBSTRING_INDEX(lotto_index,':',1)"))
                    ->get()->toArray();
                //     dd(\DB::getQueryLog());
            }

            // continue;
            // if ($value->user_id == 996659) {
            //     dd($agent_turnover);
            // }

            if (empty($agent_turnover)) {
                continue;
            }
            $rabete_config = $agent_rebate[0]['agent_amount'];
            foreach ($agent_rebate as $k => $val) {
                // dump($key);
                $bet_amount = array_sum(array_column($agent_turnover, 'amount'));
                $bet_total  = array_sum(array_column($agent_turnover, 'total'));
                if ($bet_amount >= $val['agent_money'] && $bet_total >= $val['agent_amount']) {
                    $rabete_config = $val; //$agent_rebate[$k]['agent_amount'];
                }
            }

            $parent       = UserReference::where('user_id', $value->user_id)->with('parent')->first();
            $awards_model = UserAward::query();
            foreach ($agent_turnover as $turn_val) { //用户流水

                if ($parent) {
                    $temp   = 'agentrebate.' . $request->room_name;
                    $amount = toDecimal($turn_val['amount'] * $rabete_config['agent_son'] / 100, 3);
                    $param  = [
                        'user_id' => $parent->parent->id,
                        'type'    => 'rebate',
                        'unique'  => $temp,
                        'amount'  => $amount,
                        'extend'  => json_encode([
                            'child'   => $value->user_id,
                            'balance' => $parent->parent->wallet->total,
                            'game'    => $request->room_name, //$turn_val['game'],
                        ]),
                    ];

                    $connect_id = $awards_model->insertGetId($param);
                    $connect_id || real()->exception('添加记录失败！');
                    $amount > 0 && $parent->parent->wallet->balance($temp, $connect_id)->plus($amount);
                    dump($value->user_id . ' to ' . $parent->parent->id . ' increment ' . $amount);
                }
            }
        }
        DB::commit();
        return real()->success('返利完成');
    }
}
