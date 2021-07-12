<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserWallet\Wallet;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\LottoModule\LottoUtils;
use App\Models\LottoModule\Models\BetLog;
use App\Models\LottoModule\Models\LottoConfig;
use App\Models\LottoModule\Models\BetLogDetail;

class BetLogController extends Controller
{
    public function cancel(Request $request)
    {
        DB::beginTransaction();
        $item = BetLog::with('user')->with('details')->find($request->id);
        ($item->status === 3 || $item->status === 0) && real()->exception('该订单已取消下注');
        $item->status === 2 && real()->exception('该订单已派奖，不能取消');
        $user         = $item->user;
        $diff         = $item->total - $item->bonus;
        $item->status = 0;
        $item->save();
        if ($diff != 0) {
            $wallet = $user->wallet->balance('lotto.cancel.bet.' . $item->lotto_name, $item->id);
            $wallet->remark('系统取消下注');
            $diff > 0 && $wallet->plus($diff);
            $diff < 0 && $wallet->minus($diff);
        }

        DB::commit();

        $result = $item->toArray();
        return real($result)->success('取消投注成功');
    }

    public function get(Request $request)
    {
        $rule = ['id' => 'required|int'];
        $data = $request->all();
        real()->validator($data, $rule);

        if ($request->model == 'group') {
            $lotto = BetLog::find($request->id);

            $columns = [
                '*',
                DB::raw('SUM(total) AS total'),
                DB::raw('SUM(bonus) AS bonus'),
            ];
            $item = BetLog::with('user')
                ->where('lotto_index', $lotto->lotto_index)
                ->where('status', '>', 0)
                ->where('user_id', $lotto->user->id)
                ->where('play_type', $lotto->play_type)
                ->first($columns);

            $logs = BetLog::where('lotto_index', $lotto->lotto_index)
                ->where('status', '>', 0)
                ->where('user_id', $lotto->user->id)
                ->where('play_type', $lotto->play_type)
                ->get();

            $log_ids = [];
            foreach ($logs as $value) {
                $log_ids[] = $value->id;
            }

            $columns = [
                '*',
                DB::raw('SUM(amount) AS amount'),
                DB::raw('SUM(bonus) AS bonus'),
            ];
            $details = BetLogDetail::whereIn('log_id', $log_ids)
                ->groupBy('place')
                ->get($columns);

            $item->details = $details;
        } else {
            $item = BetLog::with('user')->with('details')->find($request->id);
        }

        $item || real()->exception('data.notexist');
        $config                = LottoConfig::remember(600)->find($item->lotto_name);
        $lotto                 = LottoUtils::model($item->lotto_name)->find($item->lotto_id);
        // dd($lotto);
        $item->lotto           = $lotto;
        $item->lotto_at        = $lotto->lotto_at;
        $item->bet_details     = $item->details;
        $item->bet_time_diff_a = strtotime($item->lotto_at) - strtotime($item->created_at);
        $item->bet_time_diff_b = $item->bet_time_diff_a - $config->stop_ahead;

        $result = $item->toArray();
        return real($result)->success();
    }

    public function index(Request $request)
    {
        $request->offsetSet('trial', $request->trial ?: '0');
        $items = BetLog::query();
        $request->user === 'true' && $items->with('user');
        $request->user_id && $items->where('user_id', $request->user_id);
        $request->id && $items->where('id', 'regexp', $request->id);
        if ($request->lotto_name && $request->lotto_name != 'all') {
            $lotto_index = $request->lotto_name;
            $request->lotto_id && $lotto_index .= ':' . $request->lotto_id;
            $items->where('lotto_index', 'regexp', $lotto_index);
        }

        if (isset($request->trial) && $request->trial != 'all' && !$request->user_id) {
            $items->where('trial', $request->trial);
        }
        if (isset($request->room) && $request->room != 'all' && !$request->user_id) {
            $items->where('play_type', $request->room);
        }
        $request->status && $request->status != '2-1' && $items->where('status', $request->status);

        if ($request->status == '2-1') {
            $items->where('status', 2)->where('bonus', '>', 0);
        }
        $items->where('status', '>', 0);
        $columns = ['*'];

        if ($request->model === 'group') {
            $items->groupBy('user_id')->groupBy('play_type');
            $columns = [
                '*',
                DB::raw('SUM(total) AS total'),
                DB::raw('SUM(bonus) AS bonus'),
            ];
        }

        $items->orderBy('created_at', 'desc');

        $result = $items->paginate(20, $columns)->toArray();
        return real()->listPage($result)->success();
    }

    public function userBetStats(Request $request)
    {
        $date_start = $request->date_start ?: date('Y-m-d');
        $date_end   = $request->date_end ?: date('Y-m-d');
        $date_end   = $date_end . ' 23:59:59';
        $bet_log    = DB::table('bet_logs')
            ->where('trial', 0)
            ->where('created_at', '>=', $date_start)
            ->where('created_at', '<=', $date_end)
            ->where('status', '!=', 3)
            ->where('status', '!=', 0)
            ->groupBy('user_id')
            ->get(
                [
                    'user_id',
                    DB::raw('SUM(total) AS bet_total'),
                    DB::raw('SUM(bonus) AS bet_bonus'),
                    DB::raw('(SUM(bonus) - SUM(total)) as bet_profit'),
                ]
            );

        $bet_log = array_column($bet_log->toArray(), null, 'user_id');

        // $transfer_in = DB::table('user_wallet_transfers')
        //     ->where('type', 'agent.to.user')
        //     ->where('status', 2)
        //     ->where('created_at', '>=', $date_start)
        //     ->where('created_at', '<=', $date_end)
        //     ->groupBy('payee_id')
        //     ->get(
        //         [
        //             DB::raw('payee_id AS user_id'),
        //             DB::raw('SUM(amount) AS transfer_in'),
        //         ]
        //     );

        // $transfer_in = array_column($transfer_in->toArray(), null, 'user_id');

        $transfer_in = DB::table('balance_recharges')->where('status', '2')->sum('amount');

        // $transfer_out = DB::table('user_wallet_transfers')
        //     ->where('type', 'user.to.agent')
        //     ->where('status', 2)
        //     ->where('created_at', '>=', $date_start)
        //     ->where('created_at', '<=', $date_end)
        //     ->groupBy('drawee_id')
        //     ->get(
        //         [
        //             DB::raw('drawee_id AS user_id'),
        //             DB::raw('SUM(amount) AS transfer_out'),
        //         ]
        //     );

        // $transfer_out = array_column($transfer_out->toArray(), null, 'user_id');


        // foreach ($transfer_in as $key => $item) {
        //     $exist = array_key_exists($item->user_id, $bet_log);
        //     if ($exist) {
        //         $bet_log[$key]->transfer_in = $item->transfer_in;
        //     } else {
        //         $bet_log[$key] = (object) [
        //             'transfer_in' => $item->transfer_in,
        //         ];
        //     }
        // }

        // foreach ($transfer_out as $key => $item) {
        //     $exist = array_key_exists($item->user_id, $bet_log);
        //     if ($exist) {
        //         $bet_log[$key]->transfer_out = $item->transfer_out;
        //     } else {
        //         $bet_log[$key] = (object) [
        //             'transfer_out' => $item->transfer_out,
        //         ];
        //     }
        // }

        $users = User::whereIn('id', array_keys($bet_log))->get(['id', 'nickname']);

        foreach ($users as $item) {
            $bet_log[$item->id]->nickname = $item->nickname;
            $bet_log[$item->id]->avatar   = $item->avatar;
            $bet_log[$item->id]->id       = $item->id;
        }

        foreach ($bet_log as &$value) {
            $value->bet_total    = isset($value->bet_total) ? (float) $value->bet_total : 0;
            $value->bet_bonus    = isset($value->bet_bonus) ? (float) $value->bet_bonus : 0;
            $value->bet_profit   = isset($value->bet_profit) ? (float) $value->bet_profit : 0;
            // $value->transfer_in  = isset($value->transfer_in) ? (float) $value->transfer_in : 0;
            $value->transfer_in  = DB::table('balance_recharges')->where('status', '2')->where('user_id', $value->id)->whereBetween('confirmed_at', [$date_start, $date_end])->sum('amount');

            $value->transfer_out = $transfer_out = DB::table('balance_withdraws')->where('user_id', $value->id)->where('status', '2')->whereBetween('confirmed_at', [$date_start, $date_end])->sum('amount');
        }

        $wallet = Wallet::whereIn('user_id', array_keys($bet_log))->get();

        foreach ($wallet as $item) {
            $bet_log[$item->user_id]->wallet_total = (float) $item->total;
        }

        $result = ['items' => array_values($bet_log)];
        return real($result)->success();
    }
}
