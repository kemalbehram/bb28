<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserReference;
use App\Models\UserWallet\Wallet;
use Illuminate\Support\Facades\DB;
use App\Models\UserWallet\Transfer;
use App\Http\Controllers\Controller;
use App\Models\UserWallet\WalletLog;
use App\Models\ModelTrait\ThisAdminTrait;
use App\Models\UserWallet\BalanceRecharge;

class MemberController extends Controller
{
    use ThisAdminTrait;

    public function TransferLog(Request $request)
    {
        $data = Transfer::with('targetInfo');
        if ($request->field === 'income') {
            $data->where('payee_id', $request->user_id);
        } elseif ($request->field === 'outcome') {
            $data->where('drawee_id', $request->user_id);
        } else {
            $data->where('drawee_id', $request->user_id)->orWhere('payee_id', $request->user_id);
        }
        $data->orderBy('id', 'desc');
        $paginate       = $data->paginate(20);
        $paginate->data = $paginate->makeHidden([]);
        $result         = $paginate->toArray();

        return real()->listPage($result)->success();
    }

    public function WalletLog(Request $request)
    {
        $items = WalletLog::with('user');
        $request->user_id && $items->where('user_id', $request->user_id);
        $request->user_id || $items->where('user_id', '<', 1000000);
        if ($request->field && ($request->field == 'balance-recharge' || $request->field == 'balance-withdraw') || $request->field == 'balance-recharge-back' || $request->field == 'balance-withdraw-failed') {
            if ($request->field == 'balance-recharge') {
                $items->where('source_name', 'balance.recharge');
            } else if ($request->field == 'balance-withdraw') {
                $items->where('source_name', 'balance.withdraw');
            } else if ($request->field == 'balance-withdraw-failed') {
                $items->where('source_name', 'balance.withdraw.failed');
            } else {
                $items->where('source_name', 'award.recharge.back');
            }
        } else {
            $request->field && $request->field !== 'all' && $items->where('field', $request->field);
        }

        $items->orderBy('id', 'desc');
        $paginate = $items->paginate(20);

        $result = $paginate->toArray();
        return real()->listPage($result)->success();
    }

    public function agentIndex(Request $request)
    {
        $request->offsetSet('user_type', 'agent');

        return $this->index($request);
    }

    public function agentUpdate(Request $request)
    {
        $member = User::with('agent')->find($request->user_id);
        $agent  = $member->agent;

        $request->transfer_rate > 0.03 && real()->exception('代理提成大于0.03，请联系技术工程师处理');
        $request->transfer_refer > 0.01 && real()->exception('直属提成大于0.01，请联系技术工程师处理');

        $agent->status         = $request->status;
        $agent->transfer_rate  = $request->transfer_rate;
        $agent->transfer_refer = $request->transfer_refer;
        $agent->visible_admin  = $request->visible_admin;
        $agent->contact_qq     = $request->contact_qq;
        $agent->intro          = $request->intro;
        $agent->advance        = $request->advance;

        $agent->save();
        return real()->success('代理资料修改成功');
    }

    public function checkIP(Request $request)
    {
        $user  = User::find($request->user_id);
        $check = $user->checkIP();
        extract($check);
        $message = "请求IP 模糊匹配:$req_regexp / 完全匹配:$req_100\n注册IP 模糊匹配:$reg_regexp / 完全匹配:$reg_100";

        return real()->success($message);
    }

    public function get(Request $request)
    {
        $data = User::with('wallet')
            ->with('option')
            ->with('agent')
            ->find($request->id);

        $data || real()->exception('指定ID用户不存在 请重新输入');

        $transfer        = new Transfer();
        $data->fee_limit = $transfer->feeLimit($data->id);
        $data->ref_info  = UserReference::getReference($request->id);

        $request->offsetSet('user_id', $request->id);
        $transfer_in = Transfer::with('targetInfo')
            ->where('payee_id', $request->id)
            ->orderBy('id', 'desc')->first();

        $transfer_in && $data->transfer_in_last = $transfer_in->toArray()['target_info'];

        $transfer_out = Transfer::with('targetInfo')
            ->where('drawee_id', $request->id)
            ->orderBy('id', 'desc')->first();

        $transfer_out && $data->transfer_out_last = $transfer_out->toArray()['target_info'];

        $data->user_level = $data->userLevel();
        $result           = $data->toArray();
        return real($result)->success();
    }

    public function idUpdate(Request $request)
    {
        $rule = [
            'id'     => 'required|int',
            'new_id' => 'required|int|max:999999|min:100000',
        ];
        $data = $request->all();
        real()->validator($data, $rule);

        $user = User::find($request->id);
        $user || real()->exception('该ID用户不存在');

        $new = User::find($request->new_id);
        $new && real()->exception('新ID已存在 不能修改');

        $count = DB::table('user_wallet_logs')->where(['user_id' => $request->id])->count();
        $count === 0 || real()->exception('该用户已有资金记录 暂不支持修改');
        $count = DB::table('user_references')->where(['user_id' => $request->id])->count();
        $count === 0 || real()->exception('该用户有上级用户 暂不支持修改');
        $count = DB::table('user_references')->where(['ref_id' => $request->id])->count();
        $count === 0 || real()->exception('该用户有下级用户 暂不支持修改');
        DB::beginTransaction();

        $update = ['id' => $request->new_id];
        DB::table('users')->where(['id' => $request->id])->update(['id' => $request->new_id]);
        DB::table('user_wallets')->where(['user_id' => $request->id])->update(['user_id' => $request->new_id]);
        DB::table('user_options')->where(['user_id' => $request->id])->update(['user_id' => $request->new_id]);
        DB::table('user_agents')->where(['user_id' => $request->id])->update(['user_id' => $request->new_id]);

        $data = User::with('option')->find($request->new_id);
        $data || real()->exception('用户ID');
        DB::commit();

        $result = $data->toArray();
        return real($result)->success('用户ID更新成功 请重新刷新网页 避免不必要的错误');

        return real()->success();
    }

    public function index(Request $request)
    {
        $is_trial = 0;

        $data = User::with('wallet')->where('robot', '0');
        $request->id && $data->where('id', 'regexp', $request->id);
        $request->mobile && $data->where('mobile', 'regexp', $request->mobile);
        $request->nickname && $data->where('nickname', 'regexp', $request->nickname);
        $request->real_name && $data->where('real_name', 'regexp', $request->real_name);

        $request->user_type === 'trial' && $is_trial = 1;
        $data->where('id', '<>', 100000);
        $data->where('trial', $is_trial);
        if ($request->user_type === 'agent') {
            $data->whereHas('agent', function ($query) {
                $query->where('status', 1);
            });
            $data->with('agent');
        }

        $request->user_type === 'transfer' && $data->where('transfer', '0');
        $request->user_type === 'bet' && $data->where('bet', '0');

        $requested_ip = $request->requested_ip;
        if ($request->requested_ip && strstr($request->requested_ip, '.')) {
            $temp         = explode('.', $request->requested_ip);
            $requested_ip = $temp[0] . '.' . $temp[1] . '.';
        }

        $request->requested_ip && $data->where('requested_ip', 'like', $requested_ip . '%');

        $created_ip = $request->created_ip;
        if ($request->created_ip && strstr($request->created_ip, '.')) {
            $temp       = explode('.', $request->created_ip);
            $created_ip = $temp[0] . '.' . $temp[1] . '.';
        }

        $request->created_ip && $data->where('created_ip', 'like', $created_ip . '%');
        $request->disable && $data->where('disable', 1);

        $ids = [];
        if ($request->order_key === 'wallet') {
            $wallets = DB::table('user_wallet_total')
                ->where('trial', $is_trial)
                ->orderBy('total', $request->order_value)
                ->get();
            foreach ($wallets as $value) {
                $ids[] = $value->user_id;
            }

            $data->whereIn('id', $ids);
            $data->orderBy(DB::raw('field(id,' . implode(',', $ids) . ')'));
        } else {
            $order_key   = $request->order_key ?: 'sort';
            $order_value = $request->order_value ?: 'desc';
            $data->orderBy($order_key, $order_value);
        }

        $pagination       = $data->paginate(20);
        $data             = $pagination->makeVisible(['avatar']);
        $pagination->data = $data;
        $result           = $pagination->toArray();

        return real()->listPage($result)->debug($ids)->success();
    }

    public function nextLevel(Request $request)
    {
        $user_id = $request->user_id;
        $items   = UserReference::with('user:id,nickname')->where('ref_id', $user_id)->get(['user_id', 'ref_id', 'created_at']);
        $current = User::find($user_id, ['id', 'nickname']);

        $specific = UserReference::where('user_id', $user_id)->first();

        $excerpt = 'TA目前共有' . count($items) . '名下级用户';

        $result = [
            'current'  => $current->toArray(),
            'items'    => $items->toArray(),
            // 'items'    => [],
            'excerpt'  => $excerpt,
            'specific' => $specific,
        ];

        return real($result)->success();
    }

    public function reference(Request $request)
    {
        $data = UserReference::with('user:id,nickname');
        $request->user_id && $data->where('user_id', $request->user_id);
        $request->ref_str && $data->where('ref_str', 'regexp', $request->ref_str);
        $data->orderBy('id', 'desc');
        $paginate       = $data->paginate(20);
        $paginate->data = $paginate->makeHidden([]);

        foreach ($paginate->data as $key => $value) {
            $cache_name       = 'admin.reference:' . $value->user_id;
            $value->superiors = cache()->remember($cache_name, 600, function () use ($value) {
                return $value->superiors();
            });
        }

        $result = $paginate->toArray();

        return real()->listPage($result)->success();
    }

    public function update(Request $request)
    {
        $rule = [
            'id'       => 'required|int',
            //  'mobile'   => 'mobile',
            'mobile'   => 'required',
            'nickname' => 'min:1|max:32',
            'disable'  => 'bool',
            'status'   => 'int',
        ];
        $data = $request->all();
        real()->validator($data, $rule);

        $data = User::with('option')->find($request->id);

        $request->nickname && $data->nickname        = $request->nickname;
        $request->mobile && $data->mobile            = $request->mobile;
        $request->mobile && $data->contact_qq        = $request->contact_qq;
        isset($request->disable) && $data->disable   = $request->disable;
        $request->password && $data->password        = $request->password;
        $request->safe_word && $data->safe_word      = $request->safe_word;
        isset($request->status) && $data->status     = $request->status;
        isset($request->transfer) && $data->transfer = $request->transfer;
        isset($request->bet) && $data->bet           = $request->bet;
        isset($request->withdraw) && $data->withdraw   = $request->withdraw;
        // dd($request->option['fd_tax']);
        if (isset($request->option['fd_tax'])) {
            $request->option['fd_tax'] > 100 && real()->exception('浮动抽税大于100，如需修改 请联系程序工程师');
        }

        $data->option->fd_tax             = $request->option['fd_tax'];
        $data->option->fee_limit          = $request->option['fee_limit'];
        $data->option->bet_usable         = $request->option['bet_usable'];
        $data->option->transfer_fee_model = $request->option['transfer_fee_model'];
        $data->option->bet_limit          = $request->option['bet_limit'];
        $data->option->bet_kill           = $request->option['bet_kill'];
        // $data->option->level_exp  = $request->option['level_exp'];

        $data->save();
        $data->option->save();
        $result = $data->toArray();
        return real($result)->success('user.profile.update.success');
    }

    public function walletBankAction(Request $request)
    {
        $user = User::find($request->id);
        $user->CheckIsTrial();
        $rule = [
            'amount' => 'required|currency',
            'in'     => 'required',
            'out'    => 'required',
            'remark' => 'required',
        ];
        $data = $request->all();
        real()->validator($data, $rule);

        $out = $request->out;
        $in  = $request->in;
        $out == $in && real()->exception('转入转出账户不能相同，请重新选择');

        DB::beginTransaction();
        if ($out === 'bank' && $user->agent->advance > 0) {
            $remain = $user->wallet->bank - $user->agent->advance;
            $remain < $request->amount && real()->exception('转出后银行账户余额不能低于铺货分');
        }

        $desc   = $out . '.to.' . $in;
        $remark = '管理员操作:' . $this->admin->id . '==' . $request->remark;
        $user->wallet->remark($remark)->$out($desc)->minus($request->amount);
        $user->wallet->remark($remark)->$in($desc)->plus($request->amount);

        $result = ['wallet' => $user->wallet];

        DB::commit();
        return real($result)->success('资金转入对应账户成功');
    }

    public function walletTransfer(Request $request)
    {
        $rule = [
            'amount'   => 'required|currency',
            'payee_id' => 'required|int',
            'remark'   => 'required',
        ];
        $data = $request->all();
        real()->validator($data, $rule);

        $user  = User::find($request->drawee_id);
        $payee = User::find($request->payee_id);
        $payee || real()->exception('收款用户' . $request->payee_id . '不存在 请核对');

        $payee->trial && real()->exception('对方为试玩账户 不能接受转账');
        $payee->id == $user->id && real()->exception('不能给自己转账');

        $params = [
            'drawee_id' => $user->id,
            'payee_id'  => $payee->id,
            'amount'    => $request->amount,
            'status'    => 2,
            'type'      => null,
        ];
        DB::beginTransaction();

        if ($user->wallet->bank < $request->amount + $request->transfer_fee) {
            return real()->error('您的银行账户余额不足，请从现金账户中转入资金');
        }

        $time    = date('Y-m-d H:i:s', time() - 10);
        $has_bet = Transfer::where('drawee_id', $user->id)->where('created_at', '>=', $time)->count();
        $has_bet > 0 && real()->exception('该账户10秒内已有转账，请核对转账记录，避免重复转账');

        $drawee_type = $user->agent->status === false ? 'user' : 'agent';
        $payee_type  = $payee->agent->status === false ? 'user' : 'agent';

        $params['type'] = $drawee_type . '.to.' . $payee_type;

        $create = Transfer::create($params);
        $remark = '管理员操作:' . $this->admin->id . '==' . $request->remark;
        $user->wallet->remark($remark)->bank('transfer.to.' . $payee_type, $create->id)->minus($params['amount']);
        $payee->wallet->remark($remark)->bank('transfer.from.' . $drawee_type, $create->id)->plus($params['amount']);

        DB::commit();

        $result = ['wallet' => $user->wallet];
        return real($result)->success('给对方转账成功 请关注余额变化');
    }

    public function walletUpdate(Request $request)
    {
        $rule = [
            'field'       => 'required',
            'id'          => 'required',
            'amount'      => 'required',
            'source_name' => 'required',
            'remark'      => 'required|min:1|max:256',
        ];
        $data = $request->all();
        real()->validator($data, $rule);
        //判断是否已经为用户操作过满月奖或满周奖
        if ($request->next_level != null) {
            $data_exist = WalletLog::where('user_id', $request->id)->where('source_name', $request->source_name)->where('source_id', $request->next_level)->first();
            if ($data_exist != null) {
                real()->exception('已针对该下级 返奖');
            }
        }
        DB::beginTransaction();
        $user = User::find($request->id);
        $user || real()->exception('user.notexist');
        // $user->agent->status === true || real()->exception('仅可给代理操作');
        $field = $request->field;
        if ($request->source_name == 'user.recharge') {
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
        }
        $wallet = $user->wallet->$field($request->source_name);
        $wallet->remark($request->remark);
        $request->amount > 0 && $wallet->plus($request->amount);
        $request->amount < 0 && $wallet->minus($request->amount);

        DB::commit();
        $result = ['wallet' => $wallet->toArray()];
        return real($result)->success('user.balance.update.success');
    }

    public function usernameUpdate(Request $request)
    {
        $result = User::where(['id' => $request->user_id])->update(['username' => $request->username]);
        return real($result)->success('用户备注修改成功');
    }
}
