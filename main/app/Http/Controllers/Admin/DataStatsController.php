<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\RedBag;
use App\Models\DataStats;
use App\Models\Expense;
use Illuminate\Http\Request;
use function App\Models\option;
use App\Models\UserWallet\Wallet;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\LottoModule\Models\BetLog;
use App\Models\UserWallet\BalanceWithdraw;

class DataStatsController extends Controller
{
    public function dateAll(Request $request)
    {
        $user_id = null;
        if ($request->user_id) {
            User::find($request->user_id) || real()->exception('指定查询用户不存在');
            $user_id = $request->user_id;
        }
        $date_start = $request->date_start ?: date('Y-m-d');
        $date_end   = $request->date_end ?: date('Y-m-d');
        $date_end   = $date_end . ' 23:59:59';

        $stats         = new DataStats($date_start, $date_end, $user_id);
        $bet           = $stats->bet();
        $bet_effective = $stats->bet(1);

        $wallet  = $stats->wallet();
        $red_bag = $stats->redBag();
        $result  = [
            'bet_profit'           => toDecimal($bet->bonus - $bet->total),
            'bet_total'            => toDecimal($bet->total),
            'bet_bonus'            => toDecimal($bet->bonus),
            'bet_total_effective'  => toDecimal($bet_effective->total),
            'wallet_recharge'      => toDecimal($stats->walletRecharge()->total),
            'wallet_withdraw'      => toDecimal($stats->walletWithdraw()->total),
            'transfer_fee'         => toDecimal($stats->transferFee()->total),
            'wallet_system_plus'   => toDecimal($stats->walletSystemService(true)),
            'wallet_system_minus'  => toDecimal($stats->walletSystemService(false)),
            'red_bag_log'          => toDecimal($stats->redBagLog()->total),
            'red_bag_send'         => toDecimal($red_bag->total),
            'red_bag_received'     => toDecimal($red_bag->received),
            'red_bag_returned'     => toDecimal($red_bag->returned),
            'user_award_total'     => toDecimal($stats->userAward()->total),
            'user_reference_total' => (string) $stats->userReference(),
            'user_reference_1'     => (string) $stats->userReference(1),
            'user_reference_2'     => (string) $stats->userReference(2),
            'user_reference_3'     => (string) $stats->userReference(3),
            'user_reference_4'     => (string) $stats->userReference(4),
            'user_reference_5'     => (string) $stats->userReference(5),
            'wallet_fund_interest' => toDecimal($stats->walletFundInterest()),
        ];

        if ($request->user_id) {
            $result['transfer_award_agent_base']  = toDecimal($stats->transferUserAward('award_agent_base'));
            $result['transfer_award_agent_refer'] = toDecimal($stats->transferUserAward('award_agent_refer'));
            $result['transfer_award_user_total']  = toDecimal($stats->transferUserAward('award_user_base') + $stats->transferUserAward('award_user_first'));
            $result['transfer_in']                = toDecimal($stats->transferUser('payee')->total);
            $result['transfer_out']               = toDecimal($stats->transferUser('drawee')->total);
            $result['user_id']                    = $request->user_id;
            $system_profit                        = -$result['bet_profit']
                - $result['transfer_award_agent_base']
                - $result['transfer_award_agent_refer']
                - $result['transfer_award_user_total']
                - $result['wallet_fund_interest']
                - $result['user_award_total']
                + $result['transfer_fee'];
        } else {
            $transfer_award                       = $stats->transferAward();
            $result['transfer_award_agent_base']  = toDecimal($transfer_award->award_agent_base);
            $result['transfer_award_agent_refer'] = toDecimal($transfer_award->award_agent_refer);
            $result['transfer_award_user_total']  = toDecimal($transfer_award->award_user_base + $transfer_award->award_user_first);
            $result['transfer_user_to_agent']     = toDecimal($stats->transferType('user.to.agent')->total);
            $result['transfer_agent_to_user']     = toDecimal($stats->transferType('agent.to.user')->total);
            $result['transfer_award_total']       = toDecimal($transfer_award->award_agent_base + $transfer_award->award_agent_refer + $transfer_award->award_user_base + $transfer_award->award_user_first);
            $system_profit                        = -$result['bet_profit']
                - $result['user_award_total']
                - $result['transfer_award_total']
                - $result['wallet_fund_interest']
                + $result['transfer_fee'];
        }

        $result['system_profit'] = toDecimal($system_profit);

        //游戏查询
        $result = array_merge($result, $stats->lottoStatsGroup());
        //用户奖励
        $result = array_merge($result, $stats->userAwardGroup());

        return real($result)->success();
    }

    public function dateSimple(Request $request)
    {
        $user_id = null;
        if ($request->user_id) {
            User::find($request->user_id) || real()->exception('指定查询用户不存在');
            $user_id = $request->user_id;
        }
        $date_start = $request->date_start ?: date('Y-m-d');
        $date_end   = $request->date_end ?: date('Y-m-d');
        $date_end   = $date_end . ' 23:59:59';

        $stats = new DataStats($date_start, $date_end, $user_id);

        $bet           = $stats->bet();
        $bet_effective = $stats->bet(1);
        $red_bag       = $stats->redBag();
        $result        = [
            'bet_profit'           => toDecimal($bet->bonus - $bet->total),
            'bet_total'            => toDecimal($bet->total),
            'bet_total_effective'  => toDecimal($bet_effective->total),
            'bet_bonus'            => toDecimal($bet->bonus),
            'transfer_fee'         => toDecimal($stats->transferFee()->total),
            'red_bag_log'          => toDecimal($stats->redBagLog()->total),
            'red_bag_send'         => toDecimal($red_bag->total),
            'red_bag_received'     => toDecimal($red_bag->received),
            'red_bag_returned'     => toDecimal($red_bag->returned),
            'user_award_total'     => toDecimal($stats->userAward()->total),
            'wallet_fund_interest' => toDecimal($stats->walletFundInterest()),
            'wallet_recharge'      => toDecimal($stats->walletRecharge()->total),
            'wallet_withdraw'      => toDecimal($stats->walletWithdraw()->total),
            // 'user_award_total'     => toDecimal($stats->userAward()->total),
            'user_award_total'     => toDecimal($stats->userRebate()->total + $stats->userAgentRebate()->total + $stats->userRechargeBack()->total + $red_bag->received),
            'user_rebate_total'    => toDecimal($stats->userRebate()->total),
            'user_agent_rebate_total'    => toDecimal($stats->userAgentRebate()->total),
            'user_recharge_back_total'    => toDecimal($stats->userRechargeBack()->total),
            'user_expense'         => toDecimal($stats->getExpense()->total),
        ];

        if ($request->user_id) {
            $result['transfer_award_agent_base']  = toDecimal($stats->transferUserAward('award_agent_base'));
            $result['transfer_award_agent_refer'] = toDecimal($stats->transferUserAward('award_agent_refer'));
            $result['transfer_award_user_total']  = toDecimal($stats->transferUserAward('award_user_base') + $stats->transferUserAward('award_user_first'));
            $result['transfer_in']                = toDecimal($stats->transferUser('payee')->total);
            $result['transfer_out']               = toDecimal($stats->transferUser('drawee')->total);
            $result['user_id']                    = $request->user_id;
            $system_profit                        = -$result['bet_profit']
                - $result['transfer_award_agent_base']
                - $result['transfer_award_agent_refer']
                - $result['transfer_award_user_total']
                - $result['wallet_fund_interest']
                - $result['user_award_total']
                + $result['transfer_fee'];
        } else {
            $transfer_award                       = $stats->transferAward();
            $result['transfer_award_agent_base']  = toDecimal($transfer_award->award_agent_base);
            $result['transfer_award_agent_refer'] = toDecimal($transfer_award->award_agent_refer);
            $result['transfer_award_user_total']  = toDecimal($transfer_award->award_user_base + $transfer_award->award_user_total);
            $result['transfer_user_to_agent']     = toDecimal($stats->transferType('user.to.agent')->total);
            $result['transfer_agent_to_user']     = toDecimal($stats->transferType('agent.to.user')->total);
            $result['transfer_award_total']       = toDecimal($transfer_award->award_agent_base + $transfer_award->award_agent_refer + $transfer_award->award_user_base + $transfer_award->award_user_first);
            $system_profit                        = -$result['bet_profit'] - $result['user_award_total'] - $result['transfer_award_total'] - $result['wallet_fund_interest'] + $result['transfer_fee'] - $result['user_expense'];
        }
        $result['system_profit'] = toDecimal($system_profit);

        return real($result)->success();
    }

    public function tableData(Request $request)
    {
        if ($request->date_start && $request->date_end) {
            $start = $request->date_start;
            if ($start < env('DATA_START_DATE')) {
                $start = env('DATA_START_DATE');
            }

            $end = $request->date_end;
        } else {
            $start = date('Y-m-d', strtotime('-30 days'));
            $end   = date('Y-m-d');
        }

        if ($end > date('Y-m-d')) {
            $end = date('Y-m-d');
        }

        $dates = $this->dateHandle('day', $start, $end);
        $items = [];
        $today = date('Y-m-d');
        foreach ($dates as $value) {
            $cache_name   = 'dataStatsTableData: ' . $value;
            $cache_second = ($value === $today ? 10 : 86400);
            $result       = cache()->remember($cache_name, $cache_second, function () use ($value) {
                $date_start    = $value;
                $date_end      = date('Y-m-d 23:59:59', strtotime($value));
                $stats         = new DataStats($date_start, $date_end);
                $bet           = $stats->bet();
                $bet_effective = $stats->bet(1);
                $result        = [
                    'bet_profit'           => toDecimal($bet->bonus - $bet->total),
                    'bet_total'            => toDecimal($bet->total),
                    'bet_total_effective'  => toDecimal($bet_effective->total),
                    'bet_bonus'            => toDecimal($bet->bonus),
                    'transfer_fee'         => toDecimal($stats->transferFee()->total),
                    'user_award_total'     => toDecimal($stats->userAward()->total),
                    'wallet_fund_interest' => toDecimal($stats->walletFundInterest()),
                    'wallet_recharge'      => toDecimal($stats->walletRecharge()->total),
                    'wallet_withdraw'      => toDecimal($stats->walletWithdraw()->total),
                ];

                $transfer_award                       = $stats->transferAward();
                $result['transfer_award_agent_base']  = toDecimal($transfer_award->award_agent_base);
                $result['transfer_award_agent_refer'] = toDecimal($transfer_award->award_agent_refer);
                $result['transfer_award_user_total']  = toDecimal($transfer_award->award_user_base + $transfer_award->award_user_first);
                $result['transfer_user_to_agent']     = toDecimal($stats->transferType('user.to.agent')->total);
                $result['transfer_agent_to_user']     = toDecimal($stats->transferType('agent.to.user')->total);
                $result['transfer_award_total']       = toDecimal($transfer_award->award_agent_base + $transfer_award->award_agent_refer + $transfer_award->award_user_base + $transfer_award->award_user_first);
                $system_profit                        = -$result['bet_profit'] - $result['user_award_total'] - $result['transfer_award_total'] - $result['wallet_fund_interest'] + $result['transfer_fee'];
                $result['system_profit']              = (float) $system_profit;
                return $result;
            });

            $items[$value]         = $result;
            $items[$value]['date'] = $value;
        }

        $result = ['items' => array_values(array_reverse($items))];
        return real($result)->success();
    }

    public function total(Request $request)
    {
        $date_start = env('DATA_START_DATE');
        $date_end   = $request->date_end ?: date('Y-m-d');
        $date_end   = $date_end . ' 23:59:59';

        $stats          = new DataStats($date_start, $date_end);
        $wallet         = $stats->wallet();
        $bet            = $stats->bet();
        $red_bag        = $stats->redBag();
        $transfer_award = $stats->transferAward();

        $result         = [
            'bet_profit'           => toDecimal($bet->bonus - $bet->total),
            'register_user_count'  => (string) $stats->registerUserCount(),
            'register_agent_count' => (string) $stats->registerAgentCount(),
            'register_trial_count' => (string) $stats->registerTrialCount(),
            'register_robot_count' => (string) $stats->registerRobotCount(),
            'wallet_recharge'      => toDecimal($stats->walletRecharge()->total),
            'wallet_withdraw'      => toDecimal($stats->walletWithdraw()->total),
            'wallet_balance'       => toDecimal($wallet->balance),
            'wallet_bank'          => toDecimal($wallet->bank),
            'wallet_total'         => toDecimal($wallet->bank + $wallet->balance + $wallet->fund),
            'wallet_fund'          => toDecimal($wallet->fund),
            'wallet_fund_interest' => toDecimal($stats->walletFundInterest()),
            'agent_advance'        => toDecimal($stats->agentAdvance()),
            'user_reference_total' => (string) $stats->userReference(),
            // 'user_award_total'     => toDecimal($stats->userAward()->total),
            'user_award_total'     => toDecimal($stats->userAgentRebate()->total + $stats->userRechargeBack()->total + $stats->redBagReceive()->total + $stats->userRebate()->total),
            'transfer_fee'         => toDecimal($stats->transferFee()->total),
            'user_rebate_total'    => toDecimal($stats->userRebate()->total),
            'user_agent_rebate_total'    => toDecimal($stats->userAgentRebate()->total),
            'user_recharge_back_total'    => toDecimal($stats->userRechargeBack()->total),
            'user_red_bag_received'     =>  toDecimal($stats->redBagReceive()->total),
            'transfer_award_total' => toDecimal($transfer_award->award_agent_base + $transfer_award->award_agent_refer + $transfer_award->award_user_base + $transfer_award->award_user_first),
            'user_expense' => toDecimal($stats->getExpense()->total),
        ];

        $red_bag         = RedBag::where('total', '>', 'received')->where('expired_at', '>=', date('Y-m-d H:i:s'))->first(DB::raw('SUM(total - received) AS diff '));
        $withdraw_handle = BalanceWithdraw::where('status', 1)->first(DB::raw('SUM(amount) as total'));
        $betting         = BetLog::where('status', 1)->where('trial', 0)->sum('total');
        $diff            = $result['wallet_recharge']
            + $result['bet_profit']
            + $result['user_award_total']
            + $result['wallet_fund_interest']
            + $result['transfer_award_total']
            - $result['wallet_withdraw']
            - $result['wallet_total']
            // - $result['transfer_fee']
            - $withdraw_handle->total
            - $red_bag->diff
            - $betting;

        $result['wallet_diff'] = toDecimal($diff);

        $system_profit            = -$result['bet_profit'] - $result['user_award_total'] - $result['transfer_award_total'] - $result['wallet_fund_interest'] + $result['transfer_fee'] - $result['user_expense'];
        $result['system_profit']  = toDecimal($system_profit);
        $system_fund              = option('master_app_init_fund') - $result['wallet_recharge'] + $result['wallet_withdraw'] - $result['user_award_total'] - $result['transfer_award_total'] + $result['transfer_fee'] - $result['wallet_fund_interest'];
        $result['system_fund']    = toDecimal($system_fund);
        $result['system_balance'] = toDecimal($result['wallet_recharge'] - $result['wallet_withdraw'] - $result['agent_advance']);
        return real($result)->success();
    }

    public function userTableStats(Request $request)
    {
        $date_start = $request->date_start ?: env('DATA_START_DATE');
        $date_end   = $request->date_end ?: date('Y-m-d');
        $date_end   = $date_end . ' 23:59:59';

        if ($date_start < env('DATA_START_DATE')) {
            $date_start = env('DATA_START_DATE');
        }

        $source = DB::table('user_wallets')
            ->join('user_wallet_logs', 'user_wallets.user_id', '=', 'user_wallet_logs.user_id')
            ->where('user_wallets.updated_at', '>=', $date_start)
            ->where('user_wallets.updated_at', '<=', $date_end)
            ->where('user_wallets.user_id', '<', 1000000)
            ->orderBy('user_wallets.updated_at', 'desc')
            ->get([
                'user_wallets.user_id AS user_id',
                'user_wallets.updated_at AS updated_at',
                DB::raw('(user_wallets.balance + user_wallets.bank + user_wallets.fund) AS wallet_total'),
            ]);

        $items = [];
        foreach ($source as $value) {
            $items[$value->user_id] = [
                'user_id'      => $value->user_id,
                'updated_at'   => $value->updated_at,
                'wallet_total' => (float) $value->wallet_total,
                'wallet_diff'  => 0,
            ];
        }

        $users = User::query()->whereIn('id', array_keys($items))->orderBy('requested_at', 'desc')->get(['id', 'nickname', 'requested_at']);
        foreach ($users as $key => $value) {
            $items[$value->id]['requested_at'] = $value->requested_at ?: date('Y-m-d H:i:s');
            $items[$value->id]['betting']      = 0;
            $items[$value->id]['user']         = [
                'avatar'   => $value->avatar,
                'nickname' => $value->nickname,
                'id'       => $value->id,
            ];
        }

        $betting = DB::table('bet_logs')
            ->where('status', '1')
            ->where('trial', '!=', '1')
            ->groupBy('user_id')
            ->get(['user_id', DB::raw('SUM(total) AS total')]);

        foreach ($betting as $value) {
            $items[$value->user_id]['betting'] = $value->total;
        }

        foreach ($items as $user_id => &$item) {
            $cache_name   = 'AdminUserStatsTableA:' . $user_id . '==' . $item['requested_at'] . $item['updated_at'] . $date_start . $date_end;
            $cache_second = 86400;
            if ($item['requested_at'] > date('Y-m-d')) {
                $cache_second = 300;
            }

            $result = cache()->remember($cache_name, $cache_second, function () use ($date_start, $date_end, $user_id, $item) {
                $stats         = new DataStats($date_start, $date_end, $user_id);
                $bet           = $stats->bet();
                $bet_effective = $stats->bet(1);
                $red_bag       = $stats->redBag();
                $result        = [
                    'bet_profit'           => toDecimal($bet->bonus - $bet->total),
                    'bet_total'            => toDecimal($bet->total),
                    'bet_total_effective'  => toDecimal($bet_effective->total),
                    'bet_bonus'            => toDecimal($bet->bonus),
                    'transfer_fee'         => toDecimal($stats->transferFee()->total),
                    'user_award_total'     => toDecimal($stats->userAward()->total),
                    'wallet_fund_interest' => toDecimal($stats->walletFundInterest()),
                    'wallet_recharge'      => toDecimal($stats->walletRecharge()->total),
                    'wallet_withdraw'      => toDecimal($stats->walletWithdraw()->total),
                    'red_bag_log'          => toDecimal($stats->redBagLog()->total),
                    'red_bag_send'         => toDecimal($red_bag->total),
                    'red_bag_received'     => toDecimal($red_bag->received),
                    'red_bag_returned'     => toDecimal($red_bag->returned),
                ];

                $result['transfer_award_agent_base']  = toDecimal($stats->transferUserAward('award_agent_base'));
                $result['transfer_award_agent_refer'] = toDecimal($stats->transferUserAward('award_agent_refer'));
                $result['transfer_award_user_total']  = toDecimal($stats->transferUserAward('award_user_base') + $stats->transferUserAward('award_user_first'));
                $result['transfer_in']                = toDecimal($stats->transferUser('payee')->total);
                $result['transfer_out']               = toDecimal($stats->transferUser('drawee')->total);
                $system_profit                        = -$result['bet_profit']
                    - $result['transfer_award_agent_base']
                    - $result['transfer_award_agent_refer']
                    - $result['transfer_award_user_total']
                    - $result['wallet_fund_interest']
                    - $result['user_award_total']
                    + $result['transfer_fee'];

                $result['system_profit'] = (float) $system_profit;

                // $red_bag = RedBag::where('total', '>', 'received')
                //     ->where('create_id', $user_id)
                //     ->where('expired_at', '>=', date('Y-m-d H:i:s'))
                //     ->first(DB::raw('SUM(total - received) AS diff '));

                $diff = $result['wallet_recharge']
                    + $result['bet_profit']
                    + $result['user_award_total']
                    + $result['wallet_fund_interest']
                    + $result['transfer_award_agent_base']
                    + $result['transfer_award_agent_refer']
                    + $result['transfer_award_user_total']
                    + $result['red_bag_log']
                    + $result['red_bag_returned']
                    + $result['transfer_in']
                    - $result['transfer_out']
                    - $result['red_bag_send']
                    - $result['wallet_withdraw']
                    - $result['transfer_fee']
                    - $item['betting']
                    - $item['wallet_total'];
                //  - $red_bag->diff;
                $result['wallet_diff_float'] = (float) $diff;
                $result['wallet_diff']       = toDecimal($diff);

                return $result;
            });
            $item = array_merge($item, $result);
        }

        $result = ['items' => array_values($items)];
        return real($result)->success();
    }

    private function dateHandle($type = 'day', $start_date, $end_date)
    {
        $params = [
            'day'   => 'Y-m-d',
            'month' => 'Y-m',
        ];
        $start_time = strtotime($start_date);
        $end_time   = strtotime($end_date);
        $result     = [];
        while ($start_time <= $end_time) {
            $result[]   = date($params[$type], $start_time);
            $start_time = strtotime('+1 ' . $type, $start_time);
        }

        return $result;
    }

    public function expenseLog(Request $request)
    {
        $model = Expense::with('user');

        if ($request->user_id != null) {
            $model->where('user_id', $request->user_id);
        }
        $result = $model->paginate(20)->toArray();
        return real()->listPage($result)->success();
    }

    public function expenseCreate(Request $request)
    {

        $model = new Expense();
        $model->admin_id = $request->admin_id;
        $model->user_id = $request->user_id;
        $model->amount = $request->amount;
        $model->remarks = $request->remark;
        $result = $model->save();

        return real($result)->success('添加成功');
    }
}
