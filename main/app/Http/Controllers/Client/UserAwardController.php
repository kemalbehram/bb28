<?php

namespace App\Http\Controllers\Client;

use App\Models\Option;
use App\Models\UserAward;
use App\Models\OptionAward;
use Illuminate\Http\Request;
use App\Packages\Utils\PushEvent;
use Illuminate\Support\Facades\DB;
use App\Models\UserWallet\Transfer;
use App\Http\Controllers\Controller;
use App\Models\ModelTrait\ThisUserTrait;
use App\Models\LottoModule\Models\BetLog;

class UserAwardController extends Controller
{
    use ThisUserTrait;

    public function home()
    {
        $is_auth = $this->user->id ? true : false;
        $config  = OptionAward::remember(600)->where('status', '1')->orderBy('sort', 'asc')->get();

        $items = [];

        $received = $this->received();
        foreach ($config->toArray() as $value) {
            $key               = $value['type'];
            $fun               = toCamelCase('_', $key);
            $value['progress'] = $this->$fun();
            $value['type']     = $key;
            $value['received'] = false;
            if (in_array($key, $received)) {
                $value['received'] = true;
            }
            $value['finished'] = false;
            if ($value['progress'] >= $value['target']) {
                $value['finished'] = true;
            }

            if ($key === 'user_level_day') {
                $value['finished'] = true;
            }

            //首冲奖励
            if ($key === 'day_first_recharge') {
                $first_charge = Transfer::where('payee_id', $this->user->id)
                    ->where('status', 2)
                    ->where('created_at', '>=', date('Y-m-d'))
                    ->first();

                if ($first_charge) {
                    $value['target'] = '已首冲';
                }
                $value['progress'] = $value['progress'] . '倍';

                if ($value['progress'] >= 10) {
                    $value['finished'] = true;
                }
            }
            $items[$key] = $value;
        }

        $result = [
            'items'   => $items,
            'is_auth' => $is_auth,
        ];
        return real($result)->success();
    }

    public function receive(Request $request)
    {
        $this->user->CheckIsTrial();
        $this->user->agent->status && real()->exception('您的身份为代理 不能领取该任务奖励');
        if ($this->user->option->award_receive !== true) {
            real()->exception('您未有领取任务奖励资格');
        }
        $type = $request->type;
        $fun  = toCamelCase('_', $type);

        $progress = $this->$fun();
        $config   = OptionAward::find($type)->toArray();

        if ($config['status'] !== true) {
            return real()->error('该奖励状态错误 请重试');
        }

        if (floatval($progress) < $config['target'] && $type !== 'user_level_day') {
            return real()->error('该任务奖励进度未达标，请达标后再领取');
        }

        DB::beginTransaction();
        $wallet = $this->user->wallet;

        $has = UserAward::where('type', $request->type)
            ->where('date', date('Y-m-d'))
            ->where('user_id', $this->user->id)
            ->sharedLock()
            ->first();

        $has && real()->exception('您已经领取过该奖励');

        $award                                   = $config['amount'];
        $type === 'bet_day_loss' && $award       = $this->$fun(true);
        $type === 'bet_day_total' && $award      = $this->$fun(true);
        $type === 'user_level_day' && $award     = $this->$fun(true);
        $type === 'day_first_recharge' && $award = $this->$fun(true);

        $error = ($type === 'user_level_day') ? '您暂无等级奖励可领取，再接再厉哦' : '任务奖励设置错误，请联系在线客服';
        $award > 0 || real()->exception($error);

        $data = [
            'type'    => $request->type,
            'user_id' => $this->user->id,
            'amount'  => $award,
            'date'    => date('Y-m-d'),
        ];
        $type === 'user_level_day' && $data['extend'] = $this->user->userLevel();

        $create = UserAward::create($data);
        $create || real()->exception('award.receive.failed.retry');

        $wallet->balance('award.receive.' . $type, $create->id)->plus($award);

        //如果用户充值前倍数 大于4倍， 重置为4倍
        $transfer  = new Transfer();
        $fee_limit = $transfer->feeLimit($this->user->id);
        $this->user->option->increment('fee_limit', $award / 2);
        DB::commit();

        $message = [
            'message'  => '奖励领取 - ' . $this->user->nickname,
            'desc'     => '有玩家领取了奖励，请核对数据',
            'duration' => 20,
            'audio'    => 'award_receive',
        ];
        PushEvent::name('notice')->toUser(100000)->data($message);

        $result  = ['wallet' => $this->user->wallet];
        $message = '您成功获得奖励' . $award . '元 请关注现金账户余额变化';
        return real($result)->success($message);
    }

    public function received()
    {
        $items = UserAward::where('user_id', $this->user->id)
            ->where('created_at', '>=', date('Y-m-d'))
            ->get();

        $result = [];
        foreach ($items as $item) {
            $result[] = $item->type;
        }
        return $result;
    }

    private function betDayLoss($award = false)
    {
        $model = BetLog::where('confirmed_at', '<', date('Y-m-d'))
            ->where('confirmed_at', '>=', date('Y-m-d', strtotime('yesterday')))
            ->where('effective', '1')
            ->where('user_id', $this->user->id);

        $total  = $model->sum('total');
        $bonus  = $model->sum('bonus');
        $profit = $bonus - $total;

        if ($profit > 0) {return '0.000';}

        //如果是获取奖励结算
        if ($award === true) {
            $config   = OptionAward::find('bet_day_loss');
            $params   = $config->params;
            $multiple = bcdiv($total, abs($profit), 4);

            $rate                                     = $params['rate_a'];
            $multiple >= $params['multiple'] && $rate = $prams['rate_b'];

            return toDecimal(abs($profit) * $rate);
        }

        return toDecimal(abs($profit));
    }

    private function betDayTotal($award = false)
    {
        $model = BetLog::where('confirmed_at', '<', date('Y-m-d'))
            ->where('confirmed_at', '>=', date('Y-m-d', strtotime('yesterday')))
            ->where('effective', '1')
            ->where('user_id', $this->user->id);

        $total = $model->sum('total');

        //如果是获取奖励结算
        if ($award === true) {
            $config = OptionAward::find('bet_day_total');
            $params = $config->params;
            $result = toDecimal(abs($total) * $params['rate']);
            if ($result > $params['max']) {
                $result = $params['max'];
            }
            return $result;
        }

        return toDecimal(abs($total));
    }

    private function countGameBet($game)
    {
        $sum = BetLog::where('lotto_index', 'regexp', $game)
            ->where('created_at', '>=', date('Y-m-d'))
            ->where('user_id', $this->user->id)
            ->sum('total');

        return toDecimal($sum);
    }

    private function dayFirstRecharge($award = false)
    {
        $first_charge = Transfer::where('payee_id', $this->user->id)
            ->where('status', 2)
            ->where('created_at', '>=', date('Y-m-d'))
            ->first();

        if ($first_charge === null) {
            return '0.000';
        }

        $bet_total = BetLog::where('user_id', $this->user->id)
            ->where('status', 2)
            ->where('effective', 1)
            ->where('created_at', '>=', $first_charge->created_at)
            ->sum('total');

        $bet_total = $bet_total ?: 0;
        $multiple  = $bet_total / $first_charge->amount;

        if ($award === true) {
            $config = OptionAward::find('day_first_recharge');
            $params = $config->params;

            if ($multiple >= $params['multiple']) {
                return bcmul($first_charge->amount, $params['rate'], 2);
            }
        }

        return toDecimal($multiple);

        return $bet_total;
    }

    private function playBit28()
    {
        return $this->countGameBet('bit28');
    }

    private function playBj28()
    {
        return $this->countGameBet('bj28');
    }

    private function playCa28()
    {
        return $this->countGameBet('ca28');
    }

    private function playCw28()
    {
        return $this->countGameBet('cw28');
    }

    private function playDe28()
    {
        return $this->countGameBet('de28');
    }

    private function playPc28()
    {
        return $this->countGameBet('pc28');
    }

    private function rechargeK2()
    {
        $data = Transfer::where('payee_id', $this->user->id)
            ->where('amount', '>=', 2000)
            ->where('status', 2)
            ->where('created_at', '>=', date('Y-m-d'))
            ->first();

        return $data ? toDecimal($data->amount) : '0.000';
    }

    private function rechargeW5()
    {
        $sum = Transfer::where('payee_id', $this->user->id)
            ->where('status', 2)
            ->where('created_at', '>=', date('Y-m-d'))
            ->sum('amount');

        return toDecimal($sum);
    }

    private function userLevelDay($award = false)
    {
        $user_level = $this->user->userLevel();
        if ($award === true) {
            $days   = date('t');
            $amount = bcdiv($user_level['gift_month'], $days, 3);
            return toDecimal($amount);
        }

        return $user_level['level_title'];
    }
}
