<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Option;
use Illuminate\Http\Request;
use App\Packages\Utils\PushEvent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\UserWallet\BalanceRecharge;
use App\Models\LottoModule\Models\LottoChatHistory;

class RechargeController extends Controller
{
    public function cancel(Request $request)
    {
        $rule = [
            'id' => 'required|int',
        ];
        $data    = $request->all();
        $message = ['cancel.required' => '请输入取消备注'];
        real()->validator($data, $rule, $message);

        DB::beginTransaction();
        $item = BalanceRecharge::lockForUpdate()->find($request->id);
        $item || real()->exception('data.notexist');
        $item->status === 1 && real()->exception('该条记录状态为未处理 请重试');

        $expired = date('Y-m-d H:i:s', strtotime('+1 hours'));

        time() >= strtotime($item->updated_at) + 3600 && real()->exception('已超过最后处理时间 请联系超级管理员处理');

        $user = User::find($item->user_id);

        if ($item->status == 2) {
            $user || real()->exception('user.notexist');
            $wallet = $user->wallet->bank('bank.recharge.cancel', $item->id);
            $wallet->remark($request->cancel);
            $wallet->minus($item->amount);
        }

        $item->status       = 4;
        $item->remark       = '';
        $item->award        = null;
        $item->confirmed_at = null;
        $item->canceled_at  = date('Y-m-d H:i:s');
        $save               = $item->save();
        $save || real()->exception('data.update.failed');

        $message = [
            'message' => '充值申请 - 充值撤消',
            'desc'    => '您有充值被管理员撤消 请联系在线客服' . '<br>通过时间：' . date('m-d H:i:s'),
            'wallet'  => $user->wallet->toArray(),
        ];
        // PushEvent::name('Notice')->toUser($item->user_id)->data($message);
        DB::commit();
        return $this->get($request, '撤销成功，请重新审核');
    }

    public function checkUser(Request $request)
    {
        $rule = ['user_id' => 'required|int'];
        $data = $request->all();
        real()->validator($data, $rule);

        $user = User::find($request->user_id);
        // $user->trial && real()->exception('对方为试玩账户 不能接受转账');
        // $user->agent->status || real()->exception('对方不是代理，不支持转账');
        $user || real()->exception('对方账户ID不存在 请核对后再试');

        $result = ['nickname' => $user->nickname];
        return real($result)->success('对方账户ID校验成功');
    }

    public function create(Request $request)
    {
        $rule = [
            'user_id' => 'required',
            'amount'  => 'required|currency',
        ];
        $data = $request->all();
        real()->validator($data, $rule);

        DB::beginTransaction();
        $user = User::find($request->user_id);
        $user || real()->exception('user.notexist');
        // $user->agent->status === true || real()->exception('仅可给代理操作');

        $data = [
            'user_id'      => $user->id,
            'amount'       => $request->amount,
            'status'       => 2,
            'channel'      => 'service',
            'remark'       => '您的充值申请已审核通过，请关注余额变化',
            'confirmed_at' => date('Y-m-d H:i:s'),
        ];

        $create = BalanceRecharge::create($data);
        $create || real()->exception('recharge.order.create.failed');

        $wallet = $user->wallet->balance('balance.recharge.service', $create->id);
        $wallet->plus($request->amount);

        $message = [
            'message' => '充值申请 - 客服直充',
            'desc'    => '充值金额：' . $create->amount . '元' . '<br>通过时间：' . date('m-d H:i:s'),
            'wallet'  => $user->wallet->toArray(),
        ];

        // PushEvent::name('Notice')->toUser($user->id)->data($message);

        DB::commit();

        return real()->success('给用户充值成功');
    }

    public function get(Request $request, $message = '')
    {
        $rule = ['id' => 'required|int'];
        $data = $request->all();
        real()->validator($data, $rule);

        $item = BalanceRecharge::with('user:id,nickname,real_name')->with('wallet')->find($request->id);
        $item || real()->exception('data.notexist');
        $result = $item->toArray();
        return real($result)->success($message);
    }

    public function index(Request $request)
    {
        $items = BalanceRecharge::with('user:id,nickname')->with('wallet');
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
        $index    = '查:';
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
            $details             = '@' . $user->nickname . '上分' . $request->amount;
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
        $item = BalanceRecharge::lockForUpdate()->find($request->id);
        $item || real()->exception('data.notexist');
        $item->status !== 1 && real()->exception('该条记录以处理，请勿重复处理');

        $user = User::find($item->user_id);
        $user || real()->exception('user.notexist');
        $award = null;
        if ($request->status == 2) {
            $wallet = $user->wallet->balance('balance.recharge', $item->id);
            $wallet->remark($request->remark);
            $wallet->plus($request->amount);
        }
        //修改充值状态
        $item->status                       = $request->status;
        $item->remark                       = $request->remark;
        $item->amount                       = $request->amount;
        $request->channel && $item->channel = $request->channel;
        $item->confirmed_at                 = date('Y-m-d H:i:s');
        $award && $item->award              = $award;
        $save                               = $item->save();
        $save || real()->exception('data.update.failed');

        if ($request->status == 2) {
            $message = [
                'message' => '充值申请 - 审核通过',
                'desc'    => '充值金额：' . $request->amount . '元' . '<br>通过时间：' . date('m-d H:i:s'),
                'wallet'  => $user->wallet->toArray(),
            ];

            //查看是否开启赠送百分比
            $recharge_back = Option::where('name', 'recharge_back')->first();

            if (floatval($recharge_back->value) !== 0.0) {

                $back = $request->amount * floatval($recharge_back->value);

                $wallet = $user->wallet->balance('award.recharge.back', $item->id);
                $wallet->remark($request->remark);
                $wallet->plus($back);
            }
            if ($request->notice) {
                $this->saveChatHistory($request, $user);
            }
        }

        if ($request->status == 3) {
            $message = [
                'message' => '充值申请 - 审核拒绝',
                'desc'    => '您有充值申请被拒绝 请核对申请信息' . '<br>通过时间：' . date('m-d H:i:s'),
                'wallet'  => $user->wallet->toArray(),
            ];
        }
        PushEvent::name('Notice')->toUser($item->user_id)->data($message);
        DB::commit();

        $prompt = [
            '2' => '充值申请已审核通过',
            '3' => '充值申请已拒绝',
        ];
        return $this->get($request, $prompt[$request->status]);
    }

    private function baseValidator(Request $request, $extend = [])
    {
        $rule = [];
        $rule = array_merge($extend, $rule);
        $data = $request->all();
        return real()->validator($data, $rule);
    }
}
