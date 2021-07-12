<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Packages\Utils\PushEvent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ModelTrait\ThisUserTrait;
use App\Models\UserWallet\BalanceRecharge;
use App\Models\UserWallet\BalanceWithdraw;

class RechargeController extends Controller
{
    use ThisUserTrait;

    public function channel()
    {
        $this->user->CheckIsAgent();
        $channel = config('app.recharge.channel');
        unset($channel['service']);
        $result = ['channel' => $channel];
        return real($result)->success();
    }

    public function create(Request $request)
    {
        // $this->user->CheckIsAgent();
        $this->user->CheckIsTrial();
        $this->user->CheckIsBlock();
        DB::beginTransaction();
        $rule = [
            'amount'  => 'required|currency|min:1|max:50000',
            'channel' => 'required',
            // 'name'    => 'min:1|max:2',
        ];

        $message = [
            'amount.required'  => '请输入充值金额',
            'channel.required' => '请选择充值渠道',
            'name.required'    => '请输入您的转账户名',
        ];
        if ($request->amount <= 0) {
            return real()->error('金额错误，请重新输入');
        }
        // if ($request->amount > 500000 || $request->amount < 5) {
        //     return real()->error('单次充值最多50万，最低5元');
        // }

        $data = $request->all();
        real()->validator($data, $rule, $message);
        $data = [
            'user_id' => $this->user->id,
            'amount'  => (int) ($request->amount * 100) / 100,
            'channel' => $request->channel,
            'name'    => $request->name,
            'status'  => 1,
            'type'    => 0,
            'extend'  => ['usdt_address' => $request->usdt_address, 'code' => $request->code],
        ];
        $item = BalanceRecharge::create($data);
        $item || real()->exception('data.create.failed.retry');

        DB::commit();
        $item->with('user:id,nickname'); //->toArray()
        $item->user->total = $item->user->wallet->total;
        $result            = $item->toArray();
        $result['type']    = 0;
        $message           = [
            'message' => '充值提醒 - ' . $this->user->nickname,
            'desc'    => '充值金额：' . $item->amount . '元 / ' . $item->channel_info['bank_name'] . '<br>充值时间：' . $item->created_at,
            'audio'   => 'recharge',
            'detail'  => $result,
        ];

        PushEvent::name('notice')->toUser(100000)->data($message);

        return real($result)->success('充值申请提交成功 预计1-5钟内处理');
    }

    public function index(Request $request)
    {
        $recharge     = BalanceRecharge::query();
        $withdraw     = BalanceWithdraw::query();
        $balance_list = [$recharge, $withdraw];

        foreach ($balance_list as &$model) {
            $request->status && $model->where('status', $request->status);
            $request->amount && $model->where('amount', $request->amount);
            $request->type == 'withdraw' && $model->where('type', 1);
            $request->type == 'recharge' && $model->where('type', 0);
            if ($request->start == null) {
                $request->start = date('Y-m-d');
            }
            if ($request->end == null) {
                $request->end = date('Y-m-d');
            }
            $model->select(['amount', 'status', 'user_id', 'created_at', 'type']);
            $model->where('created_at', '>=', $request->start . ' 00:00:00');
            $model->where('created_at', '<=', $request->end . ' 23:59:59');
            $model->where('user_id', $this->user->id);
            $model->orderBy('created_at', 'desc');
        }

        $query = $recharge->union($withdraw);
        //
        $result = $query->orderBy('created_at', 'desc')->paginate(15)->toArray();

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
