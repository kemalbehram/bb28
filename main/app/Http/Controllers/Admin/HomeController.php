<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\DataStats;
use Illuminate\Http\Request;
use function App\Models\option;
use App\Models\UserWallet\Wallet;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\LottoModule\LottoUtils;
use App\Models\LottoModule\Models\BetLog;
use App\Models\UserWallet\BalanceRecharge;
use App\Models\UserWallet\BalanceWithdraw;
use App\Models\LottoModule\Models\LottoConfig;
use App\Http\Controllers\Client\LottoController;

class HomeController extends Controller
{
    public function betLog(Request $request)
    {
        $rule = [
            'user_id' => 'required|int',
        ];
        $data = $request->only(array_keys($rule));
        real()->validator($data, $rule);

        $model = BetLog::query();
        $today = date('Y-m-d 00:00:00');
        $model->where('confirmed_at', '>=', $today)->where('status', 2);
        $model->where('user_id', $request->user_id);
        $model->with('details');
        $model->where('status', 2);
        $model->where('effective', 1);
        $model->orderBy('created_at', 'desc');
        $result = $model->paginate(10)->toArray();
        return real()->listPage($result)->success();
    }

    public function getLotto(Request $request)
    {
        $controller = new LottoController();
        $opend      = $controller->openLast($request)['data']['items'][0];
        $newed      = $controller->betNewest($request)['data']['items'];

        $lotto = [
            $opend,
            array_shift($newed),
        ];
        return real(['items' => $lotto])->success();
    }

    public function getWallet(Request $request)
    {
        $recharge     = BalanceRecharge::query();
        $withdraw     = BalanceWithdraw::query();
        $balance_list = [$recharge, $withdraw];

        foreach ($balance_list as &$model) {
            $request->status && $model->where('status', $request->status);
            $request->amount && $model->where('amount', $request->amount);
            $model->select(['id', 'amount', 'status', 'user_id', 'created_at', 'type', 'channel']);
            $model->with('user:id,nickname,real_name,username');
            $model->where('status', 1);
            $model->orderBy('created_at', 'desc');
        }

        $query = $recharge->union($withdraw);

        $result = $query->orderBy('created_at', 'desc')->get(); //->toArray()
        foreach ($result as $value) {
            $value->user->total = $value->user->wallet->total;
        }
        $result = $result->toArray();
        return real(['items' => $result])->success();
    }

    public function index(Request $request)
    {
        $result           = [];
        $result['statis'] = $this->statis($request)['data'];
        $result['lotto']  = $this->getLotto($request)['data'];
        $result['qrcode'] = [
            'wechat_qrcode'   => option('wechat_qrcode'),
            'download_qrcode' => 'https://dss0.bdstatic.com/70cFuHSh_Q1YnxGkpoWK1HF6hhy/it/u=326662611,1926795193&fm=26&gp=0.jpg', //option('download_qrcode'),
        ];
        return real($result)->success();
    }

    public function statis(Request $request)
    {
        $today_recharge = BalanceRecharge::where('status', 2)
            ->where('confirmed_at', '>=', date('Y-m-d 00:00:00'))
            ->sum('amount');
        $today_withdraw = BalanceWithdraw::where('status', 2)
            ->where('confirmed_at', '>=', date('Y-m-d 00:00:00'))
            ->sum('amount');
        $today_turnover = BetLog::where('confirmed_at', '>=', date('Y-m-d 00:00:00'))
            ->where('effective', 1)->where('trial', 0)
            ->where('status', 2)
            ->sum('total');
        // $today_bonus = BetLog::where('confirmed_at', '>=', date('Y-m-d 00:00:00'))
        //     ->where('effective', 1)->where('trial', 0)
        //     ->where('status', 2)
        //     ->sum('bonus');

        //计算
        $date_start     = date('Y-m-d'); //env('DATA_START_DATE');
        $date_end       = $request->date_end ?: date('Y-m-d');
        $date_end       = $date_end . ' 23:59:59';
        $stats          = new DataStats($date_start, $date_end);
        $bet            = $stats->bet();
        $transfer_award = $stats->transferAward();

        $bet_profit           = toDecimal($bet->bonus - $bet->total);
        $user_award_total     = toDecimal($stats->userAward()->total);
        $transfer_award_total = toDecimal($transfer_award->award_agent_base + $transfer_award->award_agent_refer + $transfer_award->award_user_base + $transfer_award->award_user_first);
        $wallet_fund_interest = toDecimal($stats->walletFundInterest());
        $transfer_fee         = toDecimal($stats->transferFee()->total);

        $user_amount = User::where('trial', 0)->where('robot', 0)->count();
        $wallet      = Wallet::where('trial', '!=', '1')->where('robot', '!=', '1');
        $user_wallet = $wallet->first(
            [
                DB::raw('sum(balance) as balance'),
                DB::raw('sum(bank) as bank'),
                DB::raw('sum(fund) as fund'),
            ]
        );
        $user_wallet_amount = $user_wallet->balance + $user_wallet->bank + $user_wallet->fund;
        $result             = [
            'today_recharge'     => toDecimal($today_recharge),
            'today_withdraw'     => toDecimal($today_withdraw),
            'today_turnover'     => toDecimal($today_turnover),
            'today_profit'       => toDecimal(-$bet_profit - $user_award_total - $transfer_award_total - $wallet_fund_interest + $transfer_fee), //-toDecimal($today_bonus - $today_turnover),
            'user_amount'        => $user_amount,
            'user_wallet_amount' => toDecimal($user_wallet_amount),
        ];
        return real($result)->success();
    }

    public function theBetLog(Request $request)
    {
        $rule = [
            'type' => 'required',
        ];
        $data = $request->only(array_keys($rule));
        real()->validator($data, $rule);
        $config   = LottoConfig::remember(600)->find($request->type);
        $datetime = date('Y-m-d H:i:s', time());
        $model    = LottoUtils::model($request->type);
        $lotto    = $model->where(function ($query) use ($request, $datetime) {
            if ($request->issue) {
                $query->where('id', $request->issue);
            } else {
                $query->where('status', 1)->where('lotto_at', '>', $datetime);
            }
        })->orderBy('lotto_at', 'asc')->first();
        $items = [];
        if (empty($lotto)) {
            return real()->error('没有投注记录');
        }

        $items = BetLog::where('lotto_index', $request->type . ':' . $lotto->id)
            //$items =
            ->whereIn('status', [1, 2])
            ->where('effective', 1)
            ->with(['user' => function ($query) {
                $query->select(['id', 'nickname', 'trial']);
                $query->with('wallet');
            }])
            ->with('details')

            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $items = $items->toArray();
        $new_items = $this->array_group_by($items, 'user_id');
        $final_items = [];
        $i = 0;
        $dxds_count = 0;
        $dxds_amount = 0;
        $combo_count = 0;
        $combo_amount = 0;
        $jz_count = 0;
        $jz_amount = 0;
        $key_13_14_count = 0;
        $key_13_14_amount = 0;
        $total_count = 0;
        $total_amount = 0;

        $big_count = 0;
        $big_amount = 0;
        $sml_count = 0;
        $sml_amount = 0;
        $sig_count = 0;
        $sig_amount = 0;
        $dob_count = 0;
        $dob_amount = 0;

        $bsg_count = 0;
        $bsg_amount = 0;
        $bdo_count = 0;
        $bdo_amount = 0;
        $sdo_count = 0;
        $sdo_amount = 0;
        $ssg_count = 0;
        $ssg_amount = 0;

        $key_13_count = 0;
        $key_13_amount = 0;
        $key_14_count = 0;
        $key_14_amount = 0;

        foreach ($new_items as $key => $val) {

            $total = 0;
            foreach ($val as $dkey => $dval) {
                // dd($val);
                // var_dump($val[$dkey]['total']);
                $total += intval($dval['total']);



                foreach ($dval['details'] as $tkey => $tval) {
                    $total_count++;
                    $total_amount += $tval['amount'];
                    $final_items[$i]['details'][] = $tval;

                    if (strstr($tval['place'], 'bsg')) {
                        $combo_count++;
                        $combo_amount += $tval['amount'];
                        $bsg_amount += $tval['amount'];
                        $bsg_count++;
                    }
                    if (strstr($tval['place'], 'bdo')) {
                        $combo_count++;
                        $combo_amount += $tval['amount'];
                        $bdo_amount += $tval['amount'];
                        $bdo_count++;
                    }
                    if (strstr($tval['place'], 'sdo')) {
                        $combo_count++;
                        $combo_amount += $tval['amount'];
                        $sdo_amount += $tval['amount'];
                        $sdo_count++;
                    }
                    if (strstr($tval['place'], 'ssg')) {
                        $combo_count++;
                        $combo_amount += $tval['amount'];
                        $ssg_amount += $tval['amount'];
                        $ssg_count++;
                    }
                    if (strstr($tval['place'], '13') || strstr($tval['place'], '14')) {
                        $key_13_14_count += 1;
                        $key_13_14_amount += $tval['amount'];
                        if (strstr($tval['place'], '13')) {
                            $key_13_count++;
                            $key_13_amount += $tval['amount'];
                        } else {
                            $key_14_count++;
                            $key_14_amount += $tval['amount'];
                        }
                    }
                    if (strstr($tval['place'], 'big') || strstr($tval['place'], 'sml') || strstr($tval['place'], 'sig') || strstr($tval['place'], 'dob')) {
                        $dxds_count += 1;
                        $dxds_amount += $tval['amount'];

                        if (strstr($tval['place'], 'big')) {
                            $big_count++;
                            $big_amount += $tval['amount'];
                        }
                        if (strstr($tval['place'], 'sml')) {
                            $sml_count++;
                            $sml_amount += $tval['amount'];
                        }
                        if (strstr($tval['place'], 'sig')) {
                            $sig_count++;
                            $sig_amount += $tval['amount'];
                        }
                        if (strstr($tval['place'], 'dob')) {
                            $dob_count++;
                            $dob_amount += $tval['amount'];
                        }
                    }
                    //极值限制
                    if (strstr($tval['place'], 'xbg') || strstr($tval['place'], 'xsm')) {
                        $jz_count += 1;
                        $jz_amount += $tval['amount'];
                    }
                }
            }

            $final_items[$i]['total'] = $total;
            $final_items[$i]['user'] = $val[0]['user'];
            $final_items[$i]['lotto_name'] = $val[0]['lotto_title'];

            $i++;
        }
        $result['items'] = $final_items;
        $result['total'] = [$total_count, $total_amount];
        $result['jz'] = [$jz_count, $jz_amount];
        $result['dxds'] = [$dxds_count, $dxds_amount];
        $result['combo'] = [$combo_count, $combo_amount];
        $result['key_13_14'] = [$key_13_14_count, $key_13_14_amount];
        $result['big'] = [$big_count, $big_amount];
        $result['sml'] = [$sml_count, $sml_amount];
        $result['sig'] = [$sig_count, $sig_amount];
        $result['dob'] = [$dob_count, $dob_amount];
        $result['bsg'] = [$bsg_count, $bsg_amount];
        $result['bdo'] = [$bdo_count, $bdo_amount];
        $result['ssg'] = [$ssg_count, $ssg_amount];
        $result['sdo'] = [$sdo_count, $sdo_amount];
        $result['key_13'] = [$key_13_count, $key_13_amount];
        $result['key_14'] = [$key_14_count, $key_14_amount];

        // dd($final_items);
        return real($result)->success();
    }
    public static function array_group_by($arr, $key)
    {
        $grouped = [];
        foreach ($arr as $value) {
            $grouped[$value[$key]][] = $value;
        }
        if (func_num_args() > 2) {
            $args = func_get_args();
            foreach ($grouped as $key => $value) {
                $parms         = array_merge([$value], array_slice($args, 2, func_num_args()));
                $grouped[$key] = call_user_func_array('array_group_by', $parms);
            }
        }
        return $grouped;
    }

    public function userStatis(Request $request)
    {
        $rule = [
            'user_id' => 'required|int',
        ];
        $data = $request->only(array_keys($rule));
        real()->validator($data, $rule);
        $today          = date('Y-m-d 00:00:00');
        $today_recharge = BalanceRecharge::where('confirmed_at', '>=', $today)
            ->where('user_id', $request->user_id)
            ->where('status', 2);
        $today_withdraw = BalanceWithdraw::where('confirmed_at', '>=', $today)
            ->where('user_id', $request->user_id)
            ->where('status', 2);
        $bet_info = BetLog::where('confirmed_at', '>=', $today)->where('status', 2)
            ->where('user_id', $request->user_id)
            ->where('effective', 1);
        $bet_amount   = $bet_info->sum('total');
        $bonus_amount = $bet_info->sum('bonus');
        $result       = [
            'recharge_count'  => $today_recharge->count(),
            'recharge_amount' => toDecimal($today_recharge->sum('amount')),
            'withdraw_count'  => $today_withdraw->count(),
            'withdraw_amount' => toDecimal($today_withdraw->sum('amount')),
            'profit_amount'   => toDecimal($bonus_amount - $bet_amount),
            'bet_amount'      => toDecimal($bet_amount),
        ];
        return real($result)->success();
    }
}
