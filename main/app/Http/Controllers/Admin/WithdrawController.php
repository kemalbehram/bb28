<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Packages\Utils\PushEvent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\UserWallet\BalanceWithdraw;
use App\Models\LottoModule\Models\LottoChatHistory;

class WithdrawController extends Controller
{
    public function create(Request $request)
    {
        $rule = [
            'user_id' => 'required|int',
            'amount'  => 'required|currency',
        ];
        $data    = $request->all();
        $message = ['amount.required' => '请输入提现金额'];
        real()->validator($data, $rule, $message);

        DB::beginTransaction();
        $user = User::find($request->user_id);
        $user || real()->exception('user.notexist');
        $user->agent->status === true || real()->exception('仅可给代理操作');

        $wallet = $user->wallet;
        $wallet->bank < $request->amount && real()->exception('该银行账户余额不足 请重新申请');

        if ($user->agent->advance > 0) {
            $remain = $wallet->bank - $user->agent->advance;
            $remain < $request->amount && real()->exception('提现后银行账户余额不能低于铺货分 请重新申请');
        }

        $data = [
            'user_id' => $user->id,
            'amount'  => $request->amount,
            'status'  => 1,
        ];
        $item = BalanceWithdraw::create($data);
        $item || real()->exception('data.create.failed.retry');
        $user->wallet->bank('bank.withdraw', $item->id)->minus($request->amount);

        DB::commit();
        $result = $item->toArray();
        return real($result)->success('提现申请成功 请在列表中审核');
    }

    public function get(Request $request, $message = '')
    {
        $rule = ['id' => 'required|int'];
        $data = $request->all();
        real()->validator($data, $rule);

        $item = BalanceWithdraw::with('user:id,nickname,real_name')->with('wallet')->find($request->id);
        $item || real()->exception('data.notexist');
        $result = $item->toArray();
        return real($result)->success($message);
    }

    public function index(Request $request)
    {
        $items = BalanceWithdraw::with('user:id,nickname');
        $request->status && $items->where('status', $request->status);
        $request->user_id && $items->where('user_id', 'regexp', $request->user_id);
        $request->amount && $items->where('amount', $request->amount);
        $request->id && $items->where('id', $request->id);
        $items->orderBy('id', 'desc');
        $result = $items->paginate(20)->toArray();
        return real()->listPage($result)->success();
    }

    public function saveChatHistory(Request $request, $user = null)
    {
        $index    = '回:';
        $last_day = date('Y-m-d H:i:s', strtotime('-1 day'));
        $chat     = LottoChatHistory::query();
        // $log      = $chat->where('user_id', 10382366)->where('details', 'regexp', $index)
        $log = $chat->where('user_id', $user->id)->where('details', 'regexp', $index)
            ->where('created_at', '>=', $last_day)
            ->orderBy('created_at', 'desc')
            ->first();
        if ($log != null) {
            $online    = PushEvent::users('lotto.' . $log->type);
            $user_list = array_column($online['users'], 'id');
            if (!in_array($user->id, $user_list)) {
                return real()->error('用户不在房间,不可推送消息');
            }
            $details             = '@' . $user->nickname . '下分' . $request->amount;
            $new_model           = new LottoChatHistory();
            $new_model->type     = $log->type;
            $new_model->room     = $log->room;
            $new_model->nickname = env('HOST_NAME');
            $new_model->avatar   = env('HOST_AVATAR');
            $new_model->cat      = 3;
            $new_model->lotto_id = $log->lotto_id;
            $new_model->details  = $details;
            $new_model->user_id  = 10000;
            $new_model->save();
            $new_model['created_at'] = date('Y-m-d H:i:s');
            $new_model['lotto_name'] = $log->type;
            $new_model['play_type']  = $log->room;
            PushEvent::name('chatBet')->toPresence('lotto.' . $log->type)->data($new_model);
            return real()->success();
        }
    }

    public function update(Request $request)
    {
        $rule = [
            'id'     => 'required|int',
            'status' => 'required|int|between:2,3',
            // 'remark' => 'required|max:120',
        ];

        $message = ['status.between' => '审核状态错误 请重新选择'];
        $data    = $request->all();
        real()->validator($data, $rule, $message);
        DB::beginTransaction();
        $item = BalanceWithdraw::lockForUpdate()->find($request->id);
        $item || real()->exception('data.notexist');
        $item->status !== 1 && real()->exception('该条记录以处理，请勿重复处理');

        $item->status                       = $request->status;
        $item->remark                       = $request->remark;
        $request->channel && $item->channel = $request->channel;
        $item->confirmed_at                 = date('Y-m-d H:i:s');
        $save                               = $item->save();
        $save || real()->exception('data.update.failed');

        $user = User::find($item->user_id);
        $user || real()->exception('user.notexist');

        if ($request->status == 3) {
            $user->wallet->balance('balance.withdraw.failed', $item->id)->plus($item->amount);
        }
        if ($request->status == 2) {
            if ($request->notice) {
                $this->saveChatHistory($request, $user);
            }
        }

        $notify = [
            '2' => '您有提现申请已经通过审核 请关注对应提现渠道',
            '3' => '您有提现申请被拒绝 请核对申请信息',
        ];

        $message = [
            'message' => $notify[$request->status],
            'event'   => 'recharge',
            'wallet'  => $user->wallet,
        ];
        PushEvent::name('Toast')->toUser($item->user_id)->data($message);

        DB::commit();

        $message = [
            '2' => '提现申请已审核通过',
            '3' => '提现申请已拒绝',
        ];
        return $this->get($request, $message[$request->status]);
    }
}
