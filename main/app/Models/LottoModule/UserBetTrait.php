<?php

namespace App\Models\LottoModule;

use App\Models\Option;
use function App\Models\option;
use App\Packages\Utils\PushEvent;
use Illuminate\Support\Facades\DB;
use App\Models\LottoModule\Models\BetLog;
use App\Models\LottoModule\Models\ControlBet;
use App\Models\LottoModule\Models\LottoConfig;
use App\Models\LottoModule\Models\LottoPlayType;
use App\Models\LottoModule\Models\LottoUserOdds;
use App\Models\LottoModule\Models\LottoChatHistory;

trait UserBetTrait
{
    protected $auto_id = null;

    protected $lotto = null;

    protected $play_type = null;

    protected $tool_id = null;

    public function bet($name, $id, $type = null, $auto_id = null, $tool_id = null)
    {
        $this->lotto     = LottoUtils::model($name)->find($id);
        $this->play_type = $type;
        $this->auto_id   = $auto_id;
        $this->tool_id   = $tool_id;
        $this->lotto || real()->exception('lotto.notexist');

        $down = $this->lotto->bet_count_down - 2;
        $down <= 0 && real()->exception('当前期号已截止下注 请重新下注');

        $config = LottoConfig::find($name);

        $config->disable_status && real()->exception('该游戏已暂停下注<br>详情请关注公告');

        if ($type && in_array($type, $config->types) === false) {
            return real()->exception('游戏类型错误 请联系客服处理' . $type);
        }

        return $this;
    }

    public function betLog()
    {
        return $this->hasMany(BetLog::class, 'user_id', 'id');
    }

    public function cancel()
    {
        $model   = BetLog::query();
        $regexp  = $this->lotto->lotto_name . ':' . $this->lotto->id;
        $get_log = $model->where('user_id', $this->id)->where('play_type', $this->play_type)
            ->where('lotto_index', 'regexp', $regexp)
            ->where('effective', 1)
            ->where('status', 1)
            ->with('details')
            ->get()->toArray();
        if (count($get_log) == 0) {
            return real()->error('您在' . $this->lotto->id . '期的还没有有效投注！');
        }
        $id_list = array_column($get_log, 'id');
        DB::beginTransaction();
        $result = $model->whereIn('id', $id_list)->update(['status' => 0, 'effective' => 2]);
        if (!$result) {
            return real()->error('您在' . $this->lotto->id . '期的投注撤销失败！');
        }
        $str = '';
        foreach ($get_log as $value) {
            foreach ($value['details'] as $val) {
                $str .= $val['name'] . '-' . $val['amount'] . ',';
            }
            $temp = 'bet.cancel.' . $this->lotto->lotto_name . '.' . $this->play_type;
            $this->wallet->balance($temp, $value['id'])->plus($value['total']);
        }
        // $history = LottoChatHistory::query();
        try {
            $this->sendLog($this->id, $get_log[0]['id'], true);
            $param_users = [
                'user_id'    => $this->id,
                'cat'        => 7,
                'avatar'     => $this->avatar,
                'nickname'   => $this->nickname,
                'type'       => $this->lotto->lotto_name,
                'lotto_id'   => $this->lotto->id,
                'room'       => $this->play_type,
                'details'    => json_encode(['content' => '取消']),
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $result = [
                'message_type' => 'bet',
                'lotto_id'     => $this->lotto->id,
                'id_hash'      => '',
                'play_type'    => request()->play_type,
                'details'      => ['content' => $str . "\r\n" . '取消成功'],
                'created_at'   => date('Y-m-d H:i:s'),
                'avatar'       => env('HOST_AVATAR'),
                'nickname'     => env('HOST_NAME'),
                'user_id'      => $this->id,
                'cat'          => 8,
                'lotto_name'   => request()->lotto_name,

            ];

            $lotto_name = request()->lotto_name;
            PushEvent::name('chatBet')->toPresence('lotto.' . $lotto_name)->data($result);
            $param_host = [
                'user_id'    => 1000,
                'cat'        => 8,
                'avatar'     => env('HOST_AVATAR'),
                'nickname'   => env('HOST_NAME'),
                'type'       => $this->lotto->lotto_name,
                'lotto_id'   => $this->lotto->id,
                'room'       => $this->play_type,
                'details'    => json_encode(['content' => $str . "\r\n" . '取消成功']),
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $history_model = new LottoChatHistory();
            $history_model->insert($param_users);
            $history_model->insert($param_host);
        } catch (\Exception $e) {
            return real()->error('取消失败！');
        }
        DB::commit();
        return true;
    }

    public function place($params, $total_check = null, $amount = null)
    {
        $lotto_id    = $this->lotto->id;
        $lotto_name  = $this->lotto->lotto_name;
        $lotto_index = $this->lotto->lotto_index;
        $config      = LottoConfig::find($lotto_name);
        $play_type   = $this->play_type;
        $type_option = $config->type_option->$play_type;
        $bet_max     = $type_option->max;
        $bet_min     = $type_option->min;

        $total_check > $bet_max && real()->exception('单注下注金额最大' . $bet_max . '元<br>请修改后重新下注');
        $total_check < $bet_min && real()->exception('单注下注金额最低' . $bet_min . '元<br>请修改后重新下注');

        $sum = BetLog::where('lotto_index', $lotto_index)->where('status', '!=', '3')->where('user_id', $this->id)->sum('total');

        if ($sum + $total_check > $bet_max) {
            real()->exception('单期所有下注金额最大' . $bet_max . '元<br>您已下注' . toDecimal($sum) . '元，请修改后重新下注');
        }

        DB::beginTransaction();

        $total = 0;
        foreach ($params as $value) {
            $total = bcadd($total, $value['amount'], 3);
        }

        if ($total == 0) {
            real()->exception('bet.log.create.failed');
        }
        // 插入投注记录
        $log_params = [
            'user_id'     => $this->id,
            'trial'       => $this->trial,
            'lotto_index' => $lotto_index,
            'play_type'   => $this->play_type,
            'auto_id'     => $this->auto_id,
            'tool_id'     => $this->tool_id,
            'total'       => $total,
            'amount'      => $amount,
        ];

        // dd($log_params);

        $bet_log = BetLog::create($log_params);
        $bet_log || real()->exception('bet.log.create.failed');
        $this->betLog = $bet_log;

        $places = LottoPlayType::where('name', $this->play_type)->first()->place;
        $places = array_column($places, null, 'place');
        //自定义赔率
        $user_odds    = LottoUserOdds::userOdds($this->id);
        $chat_history = [];

        foreach ($params as $value) {
            $place = $value['place'];
            if ($value['amount'] < 0) {
                return real()->exception('数据异常 请重试');
            }
            // if ($this->play_type !== $places->$place->group && $places->$place->group !== 'cha') {
            //     return real()->exception('数据类型错误 请重试');
            // }
            try {
                $value['title'] = $places[$place]['title'];
                $value['odds']  = $places[$place]['odds'];

                $history['place']  = $value['place'];
                $history['amount'] = $value['amount'];
                $history['title']  = $places[$place]['title'];
                $history['name']   = $places[$place]['name'];
                $chat_history[]    = $history;
                if (isset($user_odds[$place])) {
                    $value['odds'] = $user_odds[$place];
                }
            } catch (\Throwable $th) {
                real()->exception('系统配置错误 请刷新后重试');
            }
            $bet_log->details()->create($value);
        }
        $history_data           = new LottoChatHistory();
        $history_data->room     = $this->play_type;
        $history_data->avatar   = $this->avatar;
        $history_data->nickname = $this->nickname;
        $history_data->type     = $lotto_name;
        $history_data->details  = $chat_history;
        $history_data->lotto_id = $lotto_id;
        $history_data->total    = $total;
        $history_data->cat      = 1;
        $history_data->user_id  = $this->id;
        $history_data->wechat   = $this->wechat;
        // dd($history_data);
        $history_data->save();
        // $push       = PushEvent::name('chatBet')->toPresence('lotto.' . $lotto_name)->data($history_data);

        if ($total_check && $total != $total_check && $this->robot != 1) {
            return real()->debug($total_check, $total)->exception('总额校验失败，请重新提交');
        }

        $time    = date('Y-m-d H:i:s', time() - 3);
        $has_bet = BetLog::where('user_id', $this->id)->where('created_at', '>=', $time)->where('id', '!=', $bet_log->id)->count();
        $has_bet > 0 && real()->exception('您3秒内已有投注，请核对投注记录<br>避免重复投注');

        // 扣除用户零钱
        $source = 'lotto.bet.' . $lotto_name . '.' . $this->play_type;
        $bet_log->with('user:id,nickname'); //
        $bet_log->details;
        $bet_log->user->wallet;
        $extend  = $bet_log->toArray();
        $message = [
            'message' => '下注提醒 - ' . $this->nickname,
            'desc'    => '下注金额：' . $extend['total'] . '元 / ' . '<br>下注时间：' . $extend['created_at'],
            'type'    => 'bet',
            'detail'  => $extend,
        ];

        PushEvent::name('notice')->toUser(100000)->data($message);

        $this->wallet->balance($source, $bet_log->id)->minus($total);

        // //如果是浮动
        // if (in_array($bet_log->play_type, ['fd', 'st', 'el'])) {
        //     $float = LottoFloatOdds::where('lotto_index', $lotto_index)->first();
        //     if ($float) {
        //         $float->increment('bet_total', $total);
        //         $float->increment('bet_people', 1);
        //     }
        // }

        $bet_log->updateEffective($lotto_name, $lotto_id, $this->id);

        DB::commit();

        try {
            $hidden     = ['id', 'log_id', 'extend', 'bonus', 'odds_settle', 'title', 'name'];
            $bet_places = $bet_log->details->makeHidden($hidden)->toArray();
            $this->controlBet($lotto_name, $lotto_index, $bet_places);
            $this->riskBet($bet_log);
            $this->sendLog($this->id, $bet_log->id);
        } catch (\Throwable $th) {
            return true;
        }
        // $res['status'] = true;
        // $res['bet_data'] = $this->sendLog($this->id, $bet_log->id);
        return true;
    }

    private function controlBet($lotto_name, $lotto_index, $bet_places)
    {
        if ($this->trial === true) {
            return false;
        }

        $control_lotto = config('lotto.base.control_bet');
        if (in_array($lotto_name, $control_lotto) !== true) {
            return false;
        }

        $model = new ControlBet();

        $data = [
            'lotto_index' => $lotto_index,
            'bet_places'  => $bet_places,
            'app_name'    => env('APP_NAME'),
        ];

        return ControlBet::create($data);
    }

    private function riskBet($bet_log)
    {
        $limit = option('bet_warning_total');

        if ($this->trial === true || $bet_log->total < $limit) {
            return false;
        }

        $risk   = new \App\Models\RiskCenter();
        $params = [
            'user_id'   => $this->id,
            'nickname'  => $this->nickname,
            'log_id'    => $bet_log->id,
            'log_total' => $bet_log->total,
        ];
        return $risk->lottoBetNotice($params);
    }

    private function sendLog($user_id, $log_id, $cancel = false)
    {
        $user = $this->find($user_id);

        $bet_log = BetLog::with('details:log_id,title,place,amount')->find($log_id) //->find()
            ->makeHidden(['id', 'status', 'bonus', 'updated_at', 'confirmed_at', 'open_code', 'extend']);
        $details = $bet_log->details->makeHidden(['log_id'])->toArray();
        $cat     = 1;
        if ($cancel == true) {
            $details = ['content' => '取消'];
            $cat     = 7;
        }
        $result = [
            'message_type' => 'bet',
            'lotto_id'     => $bet_log->lotto_id,
            'total'        => $bet_log->total,
            'id_hash'      => $user->id_hash,
            'play_type'    => $bet_log->play_type,
            'details'      => $details,
            'created_at'   => date('Y-m-d H:i:s'),
            'nickname'     => $user->nickname,
            'avatar'       => $user->avatar,
            'cat'          => $cat,
            'wechat'       => $user->wechat,
            'lotto_name'   => $bet_log->lotto_name,
            'user_id'      => $this->id,
        ];

        $lotto_name = $bet_log->lotto_name;

        $push = PushEvent::name('chatBet')->toPresence('lotto.' . $lotto_name)->data($result);
    }
}
