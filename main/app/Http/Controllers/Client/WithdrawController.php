<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\WithDrawAisle;
use App\Packages\Utils\PushEvent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\UserWallet\WalletLog;
use App\Models\ModelTrait\ThisUserTrait;
use App\Models\LottoModule\Models\BetLog;
use App\Models\UserWallet\BalanceRecharge;
use App\Models\UserWallet\BalanceWithdraw;
use App\Models\LottoModule\Models\LottoPlayType;

class WithdrawController extends Controller
{
    use ThisUserTrait;

    public function create(Request $request, $check = true)
    {


        $this->user->CheckIsTrial();
        $this->user->CheckIsBlock();
        if ($this->user->withdraw) {
            return real()->exception('未均匀下注,无法下分');
        }
        // $this->user->CheckIsAgent();
        // $this->user->real_name || real()->exception('您未补充真实姓名，暂不可申请提现');
        $this->user->safe_word || real(['safe_word' => true])->exception('您未设置安全密码 请先设置安全密码后再提现');
        $rule = [
            'amount'    => 'required|currency',
            'safe_word' => 'required|int',
        ];
        $data    = $request->all();
        $message = [
            'amount.required'    => '请输入提现金额',
            'safe_word.required' => '请输入安全密码',
        ];
        real()->validator($data, $rule, $message);

        //判断用户是否参与首充活动
        $join_activity = WalletLog::where('user_id', $this->user->id)->where('source_name', 'award.first.recharge')->first();
        // dd($join_activity);
        if ($join_activity != null) {
            //提现需3倍流水
            $bet_total = BetLog::where('user_id', $this->user->id)->where('play_type', '!=', 'room1')->where('play_type', '!=', 'room2')->sum('total');
            //充值加送分金额
            $recharge_give = WalletLog::where('user_id', $this->user->id)->where(function ($query) {
                $query->where('source_name', 'user.recharge')->orwhere('source_name', 'award.first.charge');
            })->sum('amount');

            //
            if ($bet_total < $recharge_give * 3) {
                return real()->exception('流水未达到(充值+赠送)的三倍,无法提现');
            }
        }
        //判断会员用户是否参与当日首充
        $join_first_charge = WalletLog::where('user_id', $this->user->id)->where('source_name', 'award.first.day.recharge')->first();
        if ($join_first_charge != null) {
            //提现需3倍流水
            $bet_total = BetLog::where('user_id', $this->user->id)->where('play_type', '!=', 'room1')->where('play_type', '!=', 'room2')->sum('total');
            //充值加送分金额
            $recharge_give = WalletLog::where('user_id', $this->user->id)->where(function ($query) {
                $query->where('source_name', 'user.recharge')->orwhere('source_name', 'award.first.day.recharge');
            })->sum('amount');

            //
            if ($bet_total < $recharge_give * 3) {
                return real()->exception('流水未达到(充值+赠送)的三倍,无法提现');
            }
        }

        //下分限制
        $charge_limits = LottoPlayType::select('recharge_limit', 'name', 'title')->where(['is_open' => 1])->get()->toArray();
        foreach ($charge_limits as $ckey => $cval) {
            //查询客人当前游戏的
            if ($cval['recharge_limit']->count != 0) {
                $today_start = date('Y-m-d') . " 00:00:00";
                $today_end = date('Y-m-d') . " 23:59:59";

                $count  = BetLog::where(['user_id' => $this->user->id, 'play_type' => $cval['name']])->whereBetween('confirmed_at', [$today_start, $today_end])->count();
                if ($count < $cval['recharge_limit']->count) {
                    return real()->exception($cval['title'] . " 玩法当日下注数需达到 " . $cval['recharge_limit']->count . " 才能下分");
                }
            }

            if ($cval['recharge_limit']->amount != 0) {
                $today_start = date('Y-m-d') . " 00:00:00";
                $today_end = date('Y-m-d') . " 23:59:59";
                $amount  = BetLog::where(['user_id' => $this->user->id, 'play_type' => $cval['name']])->whereBetween('confirmed_at', [$today_start, $today_end])->sum('amount');
                if ($amount < $cval['recharge_limit']->amount) {
                    return real()->exception($cval['title'] . " 玩法当日流水需达到 " . $cval['recharge_limit']->amount . " 才能下分");
                }
            }
        }


        $request->amount < 100 && real()->exception('单笔提现最低金额为100元');
        $request->amount % 100 != 0 && real()->exception('提现金额需为100的倍数 请重新申请');
        if ($check) {
            $this->user->safeWordCheck($request->safe_word) || real()->exception('safe_word.check.error');
        }






        DB::beginTransaction();

        $wallet = $this->user->wallet;
        // return real($wallet->balance)->success('提现申请成功 预计3-10分钟内处理');
        $wallet->balance < $request->amount && real()->exception('您的银行账户余额不足 请重新申请');

        $model  = new WithDrawAisle();
        $params = [
            'user_id' => $this->user->id,
            'type'    => $request->type,
            'qrcode'  => $request->qrcode,
            'extend'  => $request->except(['amount', 'qrcode', 'type', 'safe_word']),
        ];
        $model->updateOrCreate(['user_id' => $this->user->id, 'type' => $request->type], $params);
        $extend = $request->except(['amount', 'safe_word']);
        $data   = [
            'user_id' => $this->user->id,
            'amount'  => $request->amount,
            'status'  => 1,
            'type'    => 1,
            'channel' => $request->channel,
            'extend'  => $extend,
        ];
        $item = BalanceWithdraw::create($data);
        $item || real()->exception('data.create.failed.retry');
        $this->user->wallet->balance('balance.withdraw', $item->id)->minus($request->amount);

        $item->with('user:id,nickname'); //->toArray()
        $item->user->total = $item->user->wallet->total;
        $result            = $item->toArray();
        $result['type']    = 1;
        $message           = [
            'message' => '提现订单 - ' . $this->user->nickname,
            'desc'    => '提现金额：' . $item->amount . '元 / ' . $item->bank_name . '<br>提现时间：' . $item->created_at,
            'audio'   => 'withdraw',
            'detail'  => $result,
        ];
        PushEvent::name('notice')->toUser(100000)->data($message);

        DB::commit();

        return real($result)->success('提现申请成功 预计3-10分钟内处理');
    }

    public function getWithDraw()
    {
        $model  = new WithDrawAisle();
        $result = $model->where('user_id', $this->user->id)->groupBy('type')
            ->get()->toArray();
        $items = [];
        foreach ($result as $value) {
            $items[$value['type']] = $value;
        }
        return real(['items' => (object) $items])->success();
    }

    public function index(Request $request)
    {
        $items = BalanceWithdraw::where('user_id', $this->user->id);
        if ($request->start == null) {
            $request->start = date('Y-m-d');
        }
        if ($request->end == null) {
            $request->end = date('Y-m-d');
        }
        $items->where('created_at', '>=', $request->start . ' 00:00:00');
        $items->where('created_at', '<=', $request->end . ' 23:59:59');

        $items->orderBy('id', 'desc');
        $result = $items->paginate(15)->toArray();

        $recharge_amount = BalanceRecharge::where('created_at', '>=', $request->start . ' 00:00:00')
            ->where('user_id', $this->user->id)
            ->where('created_at', '<=', $request->end . ' 23:59:59')
            ->where('status', 2)->sum('amount');
        $withdraw_amount = BalanceWithdraw::where('created_at', '>=', $request->start . ' 00:00:00')
            ->where('user_id', $this->user->id)
            ->where('created_at', '<=', $request->end . ' 23:59:59')
            ->where('status', 2)->sum('amount');
        $data = [
            'recharge_amount' => toDecimal($recharge_amount, 3),
            'withdraw_amount' => toDecimal($withdraw_amount, 3),
        ];
        return real($data)->listPage($result)->success();
    }
}
