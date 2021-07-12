<?php
namespace App\Http\Controllers\Client;

use App\Models\User;
use App\Models\Option;
use App\Models\UserAward;
use Illuminate\Http\Request;
use App\Models\UserReference;
use function App\Models\option;
use App\Packages\Utils\PushEvent;
use Illuminate\Support\Facades\DB;
use App\Models\UserWallet\Transfer;
use App\Http\Controllers\Controller;
use App\Models\ModelTrait\ThisUserTrait;
use App\Models\LottoModule\Models\BetLog;

class TransferController extends Controller
{
    use ThisUserTrait;

    public $params = [];

    public $payee = null;

    public function action(Request $request)
    {
        $this->user->CheckIsTrial();
        $this->user->transfer !== true && real()->exception('您暂无转账权限 如需开通下注功能请联系客服');
        $rule = [
            'amount'   => 'required|currency',
            'payee_id' => 'required|int',
        ];
        $data = $request->all();
        real()->validator($data, $rule);

        $user  = $this->user;
        $payee = User::find($request->payee_id);
        $payee || real()->exception('收款用户' . $request->payee_id . '不存在 请核对');
        $this->payee = $payee;

        $payee->trial && real()->exception('对方为试玩账户 不能接受转账');
        $payee->id == $user->id && real()->exception('您不能给自己转账');
        $payee || real()->exception('对方账户ID不存在 请核对后再试');
        // ($user->agent->status === true && $payee->agent->status === true) && real()->exception('代理仅能对用户转账');
        if ($user->agent->status === false) {
            $payee->agent->status === false && real()->exception('用户仅能对代理转账');
            $request->amount % 1 != 0 && real()->exception('转账金额需为1的倍数 请重新申请');
        }

        $this->params = [
            'drawee_id'    => $user->id,
            'payee_id'     => $payee->id,
            'amount'       => $request->amount,
            'status'       => 2,
            'transfer_fee' => $request->transfer_fee,
        ];
        DB::beginTransaction();

        if ($user->wallet->bank < $request->amount + $request->transfer_fee) {
            return real()->error('您的银行账户余额不足，请从现金账户中转入资金');
        }

        $time    = date('Y-m-d H:i:s', time() - 10);
        $has_bet = Transfer::where('drawee_id', $user->id)->where('created_at', '>=', $time)->count();
        $has_bet > 0 && real()->exception('您10秒内已有转账，请核对转账记录，避免重复转账');

        //用户给代理转账
        if ($user->agent->status === false && $payee->agent->status === true) {
            $request->amount < 100 && real()->exception('单笔转账最低金额为100元');
            $request->amount % 100 != 0 && real()->exception('转账金额需为100的倍数 请重新申请');
            $action = $this->userToAgent($request);
        }

        //代理给用户转账
        if ($user->agent->status === true && $payee->agent->status === false) {
            $action = $this->agentToUser($request);
        }

        //代理给代理转账
        if ($user->agent->status === true && $payee->agent->status === true) {
            $action = $this->agentToAgent($request);
        }

        DB::commit();

        $transfer  = new Transfer();
        $fee_limit = $transfer->feeLimit($this->user->id);

        $result = ['wallet' => $this->user->wallet, 'fee_limit' => $fee_limit];
        return real($result)->success('给对方转账成功 请关注余额变化');
    }

    public function cancel(Request $request)
    {
        $request->offsetSet('user_id', $this->user->id);
        $rule = ['id' => 'required|int'];
        $data = $request->all();
        real()->validator($data, $rule);

        $drawee = $this->user;
        $drawee->agent->status || real()->exception('您无权限撤回该条转账');

        DB::beginTransaction();
        $transfer = Transfer::lockForUpdate()->find($request->id);
        $transfer || real()->exception('数据错误 请重试');
        $transfer->status != 2 && real()->exception('该条记录状态错误， 不可撤消');
        $transfer->drawee_id != $drawee->id && real()->exception('您无权限操作该条转账 请联系在线客服');
        $transfer->can_cancel || real()->exception('该条转账已超时，暂不能撤消');

        $payee = User::find($transfer->payee_id);

        $start_date = date('Y-m-d', strtotime($transfer->created_at));
        $end_date   = date('Y-m-d', strtotime($start_date) + 86400);

        //如果转账金额大于2000 查询用户是否领取过任务中心2000的奖励
        if (abs($transfer->amount) >= 2000) {
            $award_recharge_k2 = UserAward::where('user_id', $payee->id)
                ->where('type', 'recharge_k2')
                ->where('date', $start_date)
                ->first();

            //如果领取过recharge_k2，且满足活动条件的条数 小于2条
            if ($award_recharge_k2) {
                $count = Transfer::where('payee_id', $transfer->payee_id)
                    ->where('id', '!=', $transfer->id)
                    ->where('status', 2)
                    ->where('created_at', '>', $start_date)
                    ->where('created_at', '<', $end_date)
                    ->count();

                //删除任务中心领取记录、并扣除金额
                if ($count === 0) {
                    $award_recharge_k2->delete();
                    $award_recharge_k2->trashed() || real()->exception('撤回异常，请重试');
                    $payee->wallet->balance('award.cancel.recharge_k2', $award_recharge_k2->id)
                        ->minus($award_recharge_k2->amount);
                }
            }
        }

        //recharge_w5 任务开始
        $sum = Transfer::where('payee_id', $this->user->id)
            ->where('status', 2)
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<', $end_date)
            ->sum('amount');

        //充值总额减去撤回金额， 查询用户是否领取recharge_w5
        if (($sum - abs($transfer->amount)) < 50000) {
            $award_recharge_w5 = UserAward::where('user_id', $payee->id)
                ->where('type', 'recharge_w5')
                ->where('date', $start_date)
                ->first();

            //删除任务中心领取记录、并扣除金额
            if ($award_recharge_w5) {
                $award_recharge_w5->delete();
                $award_recharge_w5->trashed() || real()->exception('撤回异常，请重试。');
                $payee->wallet->balance('award.cancel.recharge_w5', $award_recharge_w5->id)
                    ->minus($award_recharge_w5->amount);
            }
        }

        if ($payee->wallet->balance < ($transfer->amount + $transfer->award['user_base'])) {
            real()->exception('对方现金账户不能够足额撤消。');
        }

        $transfer->status      = 3;
        $transfer->canceled_at = date('Y-m-d H:i:s');
        $transfer->save();

        //代理增加转账金额
        $drawee->wallet->bank('transfer.cancel.return', $transfer->id)->plus($transfer->amount);

        //扣除代理基本提成
        if (isset($transfer->award['agent_base']) && $transfer->award['agent_base'] > 0) {
            $drawee->wallet->bank('transfer.cancel.return.award.agent_base', $transfer->id)->minus($transfer->award['agent_base']);
        }

        //扣除代理直属下线提成
        if (isset($transfer->award['agent_refer']) && $transfer->award['agent_refer'] > 0) {
            $drawee->wallet->bank('transfer.cancel.return.award.agent_refer', $transfer->id)->minus($transfer->award['agent_refer']);
        }

        $new_limit = abs($transfer->amount);
        //扣除用户笔笔返金额
        if (isset($transfer->award['user_base']) && $transfer->award['user_base'] > 0) {
            $new_limit += abs($transfer->award['user_base']);
            $payee->wallet->balance('transfer.cancel.agent.award.user_base', $transfer->id)->minus($transfer->award['user_base']);
        }

        //扣除首冲奖励
        if (isset($transfer->award['user_first']) && $transfer->award['user_first'] > 0) {
            $new_limit += abs($transfer->award['user_first']);
            $payee->wallet->balance('transfer.cancel.agent.award.user_first', $transfer->id)->minus($transfer->award['user_first']);
        }

        //扣除用户收款金额
        $payee->wallet->balance('transfer.cancel.agent', $transfer->id)->minus($transfer->amount);
        $payee->option->decrement('fee_limit', $new_limit);

        $message = [
            'message'  => '转账撤消 - ' . $this->user->nickname,
            'desc'     => '撤消金额：' . abs($transfer->amount) . '元' . '<br>撤消时间：' . date('m-d H:i:s'),
            'wallet'   => $payee->wallet->toArray(),
            'action'   => 'transfer_refresh',
            'duration' => 20,
        ];
        PushEvent::name('Notice')->toUser($payee->id)->data($message);
        DB::commit();

        $result = ['wallet' => $this->user->wallet];
        return real($result)->success('transfer.cancel.success');
    }

    public function checkUser(Request $request)
    {
        $rule = ['payee_id' => 'required|int'];
        $data = $request->all();
        real()->validator($data, $rule);

        $payee = User::find($request->payee_id);
        $payee || real()->exception('收款用户' . $request->payee_id . '不存在 请核对');
        $payee->trial && real()->exception('对方为试玩账户 不能接受转账');
        $payee || real()->exception('对方账户ID不存在 请核对后再试');

        $result = ['nickname' => $payee->nickname];
        return real($result)->success('对方账户ID校验成功');
    }

    public function feeLimit(Request $request)
    {
        // $this->user->CheckIsTrial();
        $transfer = new Transfer();
        $result   = $transfer->feeLimit($this->user->id);
        return real($result)->success();
    }

    public function log(Request $request)
    {
        $request->offsetSet('user_id', $this->user->id);

        $data = Transfer::with('targetInfo');

        $data->where(function ($query) {
            $query->where('drawee_id', $this->user->id)->orWhere('payee_id', $this->user->id);
        });

        if ($request->target_id) {
            $data->where(function ($query) use ($request) {
                $query->where('drawee_id', $request->target_id)->orWhere('payee_id', $request->target_id);
            });
        }

        $raw_date = DB::raw("date_format(`created_at`, '%Y-%m-%d')");
        $request->date_start && $data->where($raw_date, '>=', $request->date_start);
        $request->date_end && $data->where($raw_date, '<=', $request->date_end);

        $data->orderBy('id', 'desc');
        $paginate       = $data->paginate(10);
        $paginate->data = $paginate->makeHidden([]);
        $result         = $paginate->toArray();

        return real()->listPage($result)->success();
    }

    private function agentToAgent(Request $request)
    {
        DB::beginTransaction();

        if ($this->user->agent->advance > 0) {
            $remain = $this->user->wallet->bank - $this->user->agent->advance;
            $remain < $this->params['amount'] && real()->exception('转账后银行账户余额不能低于铺货分 请重新输入');
        }

        $this->params['type'] = 'agent.to.agent';
        $create               = Transfer::create($this->params);

        $this->user->wallet->bank('transfer.to.agent', $create->id)->minus($this->params['amount']);
        $this->payee->wallet->bank('transfer.from.agent', $create->id)->plus($this->params['amount']);
        DB::commit();

        $message = [
            'message' => '转账提醒 - ' . $this->user->nickname,
            'desc'    => '转账金额：' . $this->params['amount'] . '元' . '<br>转账时间：' . date('m-d H:i:s'),
            'wallet'  => $this->payee->wallet->toArray(),
        ];
        PushEvent::name('Notice')->toUser($this->payee->id)->data($message);

        return true;
    }

    //代理给用户转账
    private function agentToUser(Request $request)
    {
        DB::beginTransaction();

        $transfer_award_user_base            = option('transfer_award_user_base');
        $transfer_award_user_first           = option('transfer_award_user_first');
        $this->params['type']                = 'agent.to.user';
        $this->params['award']['user_base']  = bcmul($this->params['amount'], $transfer_award_user_base, 3);
        $this->params['award']['agent_base'] = bcmul($this->params['amount'], $this->user->agent->transfer_rate, 3);

        //首冲奖励
        $has_first                           = Transfer::where('payee_id', $this->payee->id)->where('status', '2')->where('created_at', '>=', date('Y-m-d'))->first();
        $this->params['award']['user_first'] = $has_first === null ? bcmul($this->params['amount'], $transfer_award_user_first, 3) : 0;

        //如果该用户被禁止获取奖励
        if ($this->payee->option->award_receive !== true) {
            $this->params['award']['user_base']  = 0;
            $this->params['award']['user_first'] = 0;
        }

        //如果是直属下线
        $refer = UserReference::getReference($this->payee->id);

        if ($refer['ref_id'] === $this->user->id && $this->user->agent->transfer_refer > 0) {
            $this->params['award']['agent_refer'] = bcmul($this->params['amount'], $this->user->agent->transfer_refer, 3);
        }

        $create = Transfer::create($this->params);

        $create->drawee_id != $this->user->id && real($create->toArray())->exception('数据错误 请联系管理员');

        $new_limit = $this->params['amount'];

        $this->user->wallet->bank('transfer.to.user', $create->id)->minus($this->params['amount']);

        if (isset($this->params['award']['agent_base']) && $this->params['award']['agent_base'] > 0) {
            $this->user->wallet->bank('transfer.to.user.award.agent_base', $create->id)->plus($this->params['award']['agent_base']);
        }
        if (isset($this->params['award']['agent_refer']) && $this->params['award']['agent_refer'] > 0) {
            $this->user->wallet->bank('transfer.to.user.award.agent_refer', $create->id)->plus($this->params['award']['agent_refer']);
        }

        //如果用户充值前倍数 大于4倍， 重置为4倍
        $transfer  = new Transfer();
        $fee_limit = $transfer->feeLimit($this->payee->id);
        if ($fee_limit['fee_model'] === 'base') {
            //如果流水大于4倍，重置流水
            if ($fee_limit['bet_rate'] > 4) {
                $this->payee->option->bet_usable = ($this->payee->option->fee_limit * 4) + ($this->params['amount'] * 2);
                if (isset($this->params['award']['user_base']) && $this->params['award']['user_base'] > 0) {
                    $this->payee->option->bet_usable += $this->params['award']['user_base'] * 2;
                }
                $this->payee->option->save();
            } else {
                //最后一次充值是否超过3天
                $time      = date('Y-m-d H:i:s', strtotime('-3 day'));
                $has_trans = Transfer::where('payee_id', $this->payee->id)
                    ->where('id', '!=', $create->id)
                    ->where('status', '2')
                    ->where('created_at', '>=', $time)
                    ->count();

                //当前用户余额小于100块
                $payee_total = $this->payee->wallet->total;
                if ($has_trans === 0 && $payee_total <= 100) {
                    $this->payee->option->fee_limit  = $payee_total;
                    $this->payee->option->bet_usable = 0;
                    $this->payee->option->save();
                }
            }
        }

        //给用户加钱
        $this->payee->wallet->balance('transfer.from.agent', $create->id)->plus($this->params['amount']);
        if (isset($this->params['award']['user_base']) && $this->params['award']['user_base'] > 0) {
            $new_limit += $this->params['award']['user_base'];
            $this->payee->wallet->balance('transfer.from.agent.award.user_base', $create->id)->plus($this->params['award']['user_base']);
        }

        //首冲奖励
        if (isset($this->params['award']['user_first']) && $this->params['award']['user_first'] > 0) {
            $new_limit += $this->params['award']['user_first'];
            $this->payee->wallet->balance('transfer.from.agent.award.user_first', $create->id)->plus($this->params['award']['user_first']);
        }

        $this->payee->option->increment('fee_limit', $new_limit);

        DB::commit();

        $message = [
            'message'  => '转账提醒 - ' . $this->user->nickname . ' / ' . $this->user->id,
            'desc'     => '转账金额：' . $this->params['amount'] . '元' . '<br>转账时间：' . date('m-d H:i:s'),
            'wallet'   => $this->payee->wallet->toArray(),
            'audio'    => 'player_wallet_recharge',
            'action'   => 'transfer_refresh',
            'duration' => 20,
        ];
        PushEvent::name('Notice')->toUser($this->payee->id)->data($message);
        $message['desc'] .= '<br>收款用户：' . $this->payee->nickname . ' / ' . $this->payee->id;
        PushEvent::name('Notice')->toUser(100000)->data($message);

        return true;
    }

    //用户给代理转账
    private function userToAgent(Request $request)
    {
        DB::beginTransaction();

        $this->user->contact_qq || real()->exception('请先前往个人首页绑定QQ后再转账');
        $this->user->real_name || real()->exception('请先前往个人首页 填写真是姓名后再转账');

        $this->params['type'] = 'user.to.agent';

        $time    = date('Y-m-d H:i:s', time() - 600);
        $last_in = Transfer::where('payee_id', $this->user->id)->where('created_at', '>=', $time)->count();
        $last_in > 0 && real()->exception('为了您的资金安全，上分后十分钟内不可下分');

        //24小时内有效流水
        $time        = date('Y-m-d H:i:s', time() - 86400);
        $recharge_24 = Transfer::where('payee_id', $this->user->id)->where('created_at', '>=', $time)->sum('amount');
        $bet_24      = BetLog::where('user_id', $this->user->id)->where('confirmed_at', '>=', $time)->where('effective', 1)->sum('total');
        $recharge_24 > $bet_24 && real()->exception('您24小时内总有效流水未达到一倍 暂不支持转账');

        $transfer  = new Transfer();
        $fee_limit = $transfer->feeLimit($this->user->id);

        $extend = $fee_limit;

        if ($fee_limit['fee_model'] === 'base' && $fee_limit['bet_rate'] < 4) {
            $transfer_fee = bcmul($this->params['amount'], $fee_limit['fee_rate'], 3);
            $diff         = abs($this->params['transfer_fee'] - $transfer_fee);
            if ($request->transfer_fee && $diff > 0.02) {
                real($this->params['transfer_fee'])->exception('手续费计算错误 请尝试修改转账金额');
            }

            if ($this->user->wallet->total == 0) {
                $this->user->option->fee_limit  = 0;
                $this->user->option->bet_usable = 0;
                $this->user->option->save();
            }
        }

        if ($fee_limit['fee_model'] === 'bet') {
            if ($this->params['amount'] > $fee_limit['free_trans'] || $request->transfer_fee) {
                $diff         = $this->params['amount'] - $fee_limit['free_trans'];
                $transfer_fee = bcmul($diff, $fee_limit['fee_rate'], 3);
                $diff         = abs($this->params['transfer_fee'] - $transfer_fee);
                if ($request->transfer_fee && $diff > 0.02) {
                    real($this->params['transfer_fee'])->exception('手续费计算错误 请尝试修改转账金额');
                }

                $this->user->option->bet_usable = 0;
                $this->user->option->save();
            } else {
                $remain = $this->user->option->bet_usable - ($this->params['amount'] * 3);

                $this->user->option->bet_usable = $remain;
                $this->user->option->save();
            }

            $extend['bet_usable_new'] = toDecimal($this->user->option->bet_usable);
        }

        $this->params['extend'] = $extend;
        $create                 = Transfer::create($this->params);
        $create->drawee_id != $this->user->id && real($create->toArray())->exception('数据错误 请联系管理员');

        $this->payee->wallet->bank('transfer.from.user', $create->id)->plus($this->params['amount']);
        $this->user->wallet->bank('transfer.to.agent', $create->id)->minus($this->params['amount']);

        if (isset($this->params['transfer_fee']) && $this->params['transfer_fee'] > 0) {
            $this->user->wallet->bank('transfer.to.agent.transfer_fee', $create->id)->minus($this->params['transfer_fee']);
        }

        DB::commit();

        $message = [
            'message'  => '转账提醒 - ' . $this->user->nickname . ' / ' . $this->user->id,
            'desc'     => '转账金额：' . $this->params['amount'] . '元' . '<br>转账时间：' . date('m-d H:i:s'),
            'wallet'   => $this->payee->wallet->toArray(),
            'audio'    => 'player_wallet_withdraw',
            'action'   => 'transfer_refresh',
            'duration' => 20,
        ];
        PushEvent::name('Notice')->toUser($this->payee->id)->data($message);

        $message['desc'] .= '<br>收款用户：' . $this->payee->nickname . ' / ' . $this->payee->id;
        PushEvent::name('Notice')->toUser(100000)->data($message);

        return true;
    }
}
