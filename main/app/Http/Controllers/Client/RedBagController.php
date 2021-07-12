<?php

namespace App\Http\Controllers\Client;

use App\Models\User;
use App\Models\RedBag;
use App\Models\RedBagLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ModelTrait\ThisUserTrait;
use App\Models\LottoModule\Models\BetLog;
use App\Models\UserWallet\BalanceRecharge;

class RedBagController extends Controller
{
    use ThisUserTrait;

    //群聊红包领取
    public function chatReceive(Request $request)
    {
        $this->user->CheckIsTrial();
        $rule    = ['id' => 'required'];
        $data    = $request->all();
        $message = ['id.required' => '红包信息有误!'];
        real()->validator($data, $rule, $message);

        // $count = RedBagLog::where('user_id', $this->user->id)->where('created_at', '>=', date('Y-m-d H:i:s', time() - 86400))->count();
        // $count >= 3 && real()->exception('24小时内最多可领取3次红包');

        $item = RedBag::find($request->id);
        $item || real()->exception('该红包不存在');
        $text       = $item->condition == 'yesterday' ? '昨日' : '今日';
        $date_start = $item->condition == 'yesterday' ? date('Y-m-d 00:00:00', strtotime('-1 day')) : date('Y-m-d 00:00:00');
        $date_end   = $item->condition == 'yesterday' ? date('Y-m-d 23:59:59', strtotime('-1 day')) : date('Y-m-d 23:59:59');
        if ($item->receive_id && $item->receive_id != $this->user->id) {
            real()->exception('此红包是专属红包');
        }
        //判断红包是否过期
        if (date('Y-m-d H:i:s') >= $item->expired_at) {
            real()->exception('红包已过期');
        }

        //判断用户是否具有领取资格()
        $today_recharge = BalanceRecharge::where('user_id', $this->user->id)->where('confirmed_at', '>=', date('Y-m-d 00:00:00'))->where('confirmed_at', '<=', date('Y-m-d 23:59:59'))->sum('amount');
        if ($today_recharge < $item->recharge_amount) {
            real()->exception($text . '当日充值金额需大于' . $item->recharge_amount . '元，即可领取红包');
        }

        if ($item->type == 'turnover') {
            $today_bet = BetLog::where('user_id', $this->user->id)->where('confirmed_at', '>=', $date_start)->where('confirmed_at', '<=', $date_end)->sum('total');
            if ($today_bet < $item->bet_amount) {
                real()->exception($text . '流水金额需大于' . $item->bet_amount . '元，即可参与活动');
            }
        }

        //判断用户是否领取过
        $today_received = RedBagLog::where('user_id', $this->user->id)->where('parent_id', $request->id)->first();

        if ($today_received != null) {
            real()->exception('您已领取过该红包');
        }

        DB::beginTransaction();
        $item = RedBag::lockForUpdate()->find($item->id);

        if ($item->received >= $item->total || $item->quantity <= count($item->logs->toArray()) || $item->amount == 0) {
            real()->exception('该红包已被领取完');
        }
        $amount = $item->amount;

        $item->increment('received', $amount);

        $item->save();

        $data = [
            'user_id'   => $this->user->id,
            'amount'    => $amount,
            'parent_id' => $request->id,

        ];
        $create = RedBagLog::create($data);

        $this->user->wallet->balance('award.red_bag.receive', $create->id)->plus($amount);

        $result = ['wallet' => $this->user->wallet->toArray(), 'amount' => $amount];
        DB::commit();

        return real($result)->success('成功领取' . $amount . '元红包');
    }

    public function create(Request $request)
    {
        $this->user->CheckIsAgent();
        $rule = [
            'total'    => 'required|currency',
            'quantity' => 'required',
            'type'     => 'required',
            'amount'   => 'required|currency',
        ];

        $message = [
            'amount.required'   => '请输入红包金额',
            'quantity.required' => '请输入红包领取个数',
        ];

        $data = $request->all();
        real()->validator($data, $rule, $message);

        in_array($request->type, ['turnover', 'unique']) || real()->exception('红包类型错误');

        $request->total >= 1 || real()->exception('红包总额最低必须大于或等于1元');
        $request->quantity > 0 || real()->exception('领取个数必须大于0');

        $request->quantity > 100 && real()->exception('领取个数最大100');
        $request->total > 1000 && real()->exception('红包最大总额为1000元');
        // if ($request->total != $request->amount * $request->quantity) {
        //     real()->exception('数据错误，请联系管理员核对');
        // }

        if ($request->receive_id) {
            $user = User::find($request->receive_id);
            $user || real()->exception('指定领取用户不存在');
        }

        DB::beginTransaction();

        $wallet = $this->user->wallet;
        $wallet->bank < $request->total && real()->exception('您的银行账户余额不足 无法生成红包');

        $data = [
            'total'      => $request->total,
            'type'       => $request->type,
            'quantity'   => $request->quantity,
            'create_id'  => $this->user->id,
            'receive_id' => $request->receive_id,
            'expired_at' => date('Y-m-d H:i:s', time() + 86400),
            'code'       => str_random(16),
        ];

        $create = RedBag::create($data);
        $create->id || real()->exception('红包创建失败 请重试');
        $wallet->bank('red_bag.send', $create->id)->minus($request->total);
        DB::commit();

        $result = ['wallet' => $this->user->wallet->toArray()];
        return real($result)->success('红包创建成功');
    }

    public function createLog(Request $request)
    {
        $this->user->CheckIsAgent();
        $items = RedBag::with([
            'logs' => function ($query) {
                $query->with('user:id,nickname');
            }
        ]);

        $items->where('create_id', $this->user->id);
        $items->where('created_at', '>=', date('Y-m-d', strtotime('-30 day')));
        $items->orderBy('id', 'desc');
        $paginate       = $items->paginate(10);
        $paginate->data = $paginate->makeHidden([]);
        $result         = $paginate->toArray();
        return real()->listPage($result)->success();
    }

    public function detail(Request $request)
    {
        $rule = [
            'id' => 'required|exists:red_bags',
        ];
        $data = $request->only(array_keys($rule));
        real()->validator($data, $rule);
        $detail = RedBag::with(['logs' => function ($query) {
            $query->with('user');
        }])->find($request->id)->toArray();
        $received = RedBagLog::where('user_id', $this->user->id)->where('parent_id', $request->id)->first();
        if ($received != null) {
            real($detail)->exception('您已领取过该红包');
        }
        // dd($detail);
        if (date('Y-m-d H:i:s') >= $detail['expired_at']) {
            real($detail)->exception('该红包已过期');
        }
        $text       = $detail['condition'] == 'yesterday' ? '昨日' : '今日';
        $date_start = $detail['condition'] == 'yesterday' ? date('Y-m-d 00:00:00', strtotime('-1 day')) : date('Y-m-d 00:00:00');
        $date_end   = $detail['condition'] == 'yesterday' ? date('Y-m-d 23:59:59', strtotime('-1 day')) : date('Y-m-d 23:59:59');
        if ($detail['type'] == 'turnover') {
            $today_bet = BetLog::where('user_id', $this->user->id)->where('confirmed_at', '>=', $date_start)->where('confirmed_at', '<=', $date_end)->sum('total');
            if ($today_bet < $detail['bet_amount']) {
                real($detail)->exception($text . '流水金额需大于' . $detail['bet_amount'] . '元，即可参与活动');
            }
        }
        if ($detail['receive_id'] && $detail['receive_id'] != $this->user->id) {
            real($detail)->exception('此红包是专属红包');
        }
        if ($detail['received'] >= $detail['total'] || $detail['quantity'] <= count($detail['logs'])) {
            real($detail)->exception('该红包已被领取完');
        }
        return real($detail)->success();
    }

    public function receive(Request $request)
    {
        $this->user->CheckIsTrial();
        $rule    = ['code' => 'required'];
        $data    = $request->all();
        $message = ['code.required' => '请输入红包码'];
        real()->validator($data, $rule, $message);

        $count = RedBagLog::where('user_id', $this->user->id)->where('created_at', '>=', date('Y-m-d H:i:s', time() - 86400))->count();
        $count >= 3 && real()->exception('24小时内最多可领取3次红包');

        $item = RedBag::where('code', $request->code)->first();
        $item || real()->exception('该红包不存在');

        DB::beginTransaction();
        $item = RedBag::lockForUpdate()->find($item->id);

        if ($item->canceled_at !== null) {
            real()->exception('该红包已过期');
        }

        if ($item->receive_id && $item->receive_id != $this->user->id) {
            real()->exception('该红包不存在或您无权限领取');
        }

        $had = $item->logs()->where('user_id', $this->user->id)->first();
        $had && real()->exception('您已领取过该红包');

        if ($item->received >= $item->total || $item->quantity <= count($item->logs->toArray()) || $item->amount == 0) {
            real()->exception('该红包已被领取完');
        }

        $item->increment('received', $item->amount);
        $item->save();

        $data = [
            'user_id' => $this->user->id,
            'amount'  => $item->amount,
        ];
        $create = $item->logs()->create($data);
        $this->user->wallet->balance('award.red_bag.receive', $create->id)->plus($item->amount);

        $result = ['wallet' => $this->user->wallet->toArray()];
        DB::commit();

        return real($result)->success('红包领取成功');
    }

    public function receiveLog(Request $request)
    {
        // $this->user->CheckIsTrial();
        $items = RedBagLog::with('redBag:id,code,type');
        $items->where('user_id', $this->user->id);
        $items->where('created_at', '>=', date('Y-m-d', strtotime('-30 day')));
        $items->orderBy('id', 'desc');
        $paginate       = $items->paginate(10);
        $paginate->data = $paginate->makeHidden([]);
        $result         = $paginate->toArray();
        return real()->listPage($result)->success();
    }
}
