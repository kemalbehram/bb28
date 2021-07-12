<?php

namespace App\Http\Controllers\Client;

use App\Models\User;
use Illuminate\Http\Request;
use function App\Models\option;
use App\Packages\Utils\PushEvent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\LottoModule\LottoUtils;
use App\Models\ModelTrait\ThisUserTrait;
use App\Models\LottoModule\Models\BetLog;
use App\Models\LottoModule\Models\BetTools;
use App\Models\LottoModule\Models\LottoConfig;
use App\Models\LottoModule\Models\BetAutomator;
use App\Models\LottoModule\Models\LottoPlayType;
use App\Models\LottoModule\Models\LottoFloatOdds;
use App\Models\LottoModule\Models\LottoChatHistory;
use App\Http\Controllers\Client\UserWalletController;
use App\Models\UserWallet\BalanceRecharge;

class LottoController extends Controller
{
    use ThisUserTrait;

    public $newest_lotto = null;

    public function assoc_unique($arr, $key)
    {
        $tmp_arr = [];

        foreach ($arr as $k => $v) {
            if (in_array($v[$key], $tmp_arr)) { //搜索$v[$key]是否在$tmp_arr数组中存在，若存在返回true

                unset($arr[$k]);
            } else {
                $tmp_arr[] = $v[$key];
            }
        }

        sort($arr); //sort函数对数组进行排序

        return $arr;
    }

    public function betBasic(Request $request)
    {
        if ($request->user_info) {
            $this->user = $request->user_info;
        }

        $rule = [
            'id'        => 'required',
            'checked'   => 'required',
            'play_type' => 'required',
        ];

        $config = LottoConfig::remember(600)->find($request->lotto_name);
        if (!$config['visible']) {
            return real()->error('游戏维护中 请玩游戏');
        }


        foreach ($request->checked as $key => $value) {
            if (!$value) {
                continue;
            }
            if ($value < 0) {
                return real()->exception('数据异常 请重试');
            }
            if (strstr($key, 'check')) { //充值
                $request->offsetSet('type', 'recharge');
                $request->offsetSet('amount', $value);
                $request->offsetSet('channel', 'another');

                //判断和上次的时间间隔
                $last_recharge = BalanceRecharge::where('user_id', $this->user->id)->orderBy('id', 'desc')->first();
                if ($last_recharge) {
                    if (time() - strtotime($last_recharge->created_at) < 60) {
                        return real()->error('充值操作频繁，请隔一分钟后再操作');
                    }
                }


                return $this->toWalletAction($request);
            }
            if (strstr($key, 'return')) { //提现
                $request->offsetSet('type', 'withdraw');
                $request->offsetSet('amount', $value);
                $request->offsetSet('channel', 'another');
                $request->offsetSet('safe_word', 123456);
                return $this->toWalletAction($request);
            }
        }
        $message = ['id.required' => '暂无最新投注期号 请重试'];
        $data    = $request->all();
        real()->validator($data, $rule, $message);


        $play_type = ['room1', 'room2', 'room3', 'room4', 'room5', 'room6', 'room7', 'room8'];
        if (in_array($request->play_type, $play_type) == false) {
            return real()->error('数据类型错误 请重试');
        }



        //房间7.8 组合 只能投2注
        $last_lotto = LottoUtils::model($request->lotto_name)->find($request->id - 1);
        if ($last_lotto->status == 1) {
            return real()->error('上一期还未开奖,不可投注');
        }
        // $this->user->agent->status && real()->exception('您的身份为代理 不能参与游戏投注');
        $this->user->bet !== true && real()->exception('您已被暂停下注 如需开通下注功能请联系客服');
        $name  = $request->lotto_name;
        $id    = $request->id;
        $total = $request->total;
        $type  = $request->play_type;
        $place = [];

        //检测该玩法是否关闭
        $play_types = LottoPlayType::getFormatResult();
        $is_open    = $play_types[$type]['is_open'];
        if (!$is_open) {
            return real()->error('该房间已关闭，请到其他房间下注!');
        }
        $limit = 0;

        $dxdsTotal     = 0;
        $zuheTotal     = 0;

        $jzTotal       = 0;
        $special027    = 0;
        $special126    = 0;
        $special225    = 0;
        $specialNumber = 0;
        $duiziTotal    = 0;
        $sunziTotal    = 0;
        $baoziTotal    = 0;
        $lhbTotal      = 0;
        $key_13_14 = 0;
        $number_count = 0;
        $checked       = $request->checked;
        if (count(array_keys($checked)) == 1 && strstr(array_keys($checked)[0], 'cancel')) {
            return $this->cancelBet($request);
        }
        $min_bet = $play_types[$type]['bet_limit']['min'];
        foreach ($request->checked as $key => $value) {
            if (!$value) {
                continue;
            }
            // if ($type == 'room7' || $type == 'room8') {
            if (strstr($key, 'bsg')) {
                $limit++;
            }
            if (strstr($key, 'bdo')) {
                $limit++;
            }
            if (strstr($key, 'sdo')) {
                $limit++;
            }
            if (strstr($key, 'ssg')) {
                $limit++;
            }
            if ($value < $min_bet) {
                return real()->error('单个投注金额不得小于:' . $min_bet);
            }
            // if (strstr($key, 'check')) { //充值
            //     $request->offsetSet('type', 'recharge');
            //     $request->offsetSet('amount', $value);
            //     $request->offsetSet('channel', 'another');
            //     return $this->toWalletAction($request);
            // }
            // if (strstr($key, 'return')) { //提现
            //     $request->offsetSet('type', 'withdraw');
            //     $request->offsetSet('amount', $value);
            //     $request->offsetSet('channel', 'another');
            //     $request->offsetSet('safe_word', 123456);
            //     return $this->toWalletAction($request);
            // }
            // }

            // if ($key === 'ts_pai' && $this->user->id !== 330986) {
            //     return real()->exception('外围<对>暂停下注，请重新选择');
            // }

            $place[$key] = [
                'place'  => $key,
                'amount' => $value,
            ];

            //大小单双总投注限制
            if (strstr($key, 'big') || strstr($key, 'sml') || strstr($key, 'sig') || strstr($key, 'dob')) {
                $dxdsTotal += $value;
            }
            //组合限制
            if (strstr($key, 'bdo') || strstr($key, 'bsg') || strstr($key, 'sdo') || strstr($key, 'ssg')) {
                $zuheTotal += $value;
            }
            //极值限制
            if (strstr($key, 'xbg') || strstr($key, 'xsm')) {
                $jzTotal += $value;
            }
            // 0/27限制
            if (strstr($key, '00') || strstr($key, '27')) {
                $special027 += $value;
                $number_count++;
            }
            // 1/26限制
            if (strstr($key, '01') || strstr($key, '26')) {
                $special126 += $value;
                $number_count++;
            }
            //02/25限制
            if (strstr($key, '02') || strstr($key, '25')) {
                $special225 += $value;
                $number_count++;
            }
            //其他数字限制

            for ($i = 3; $i <= 24; $i++) {
                $bet_n = explode('_', $key)[1];
                if (intval($bet_n) == $i) {
                    $specialNumber += $value;
                    $number_count++;
                }
            }

            //13、14
            if (strstr($key, '13') || strstr($key, '14')) {
                $key_13_14 += 1;
            }

            //对子
            if (strstr($key, 'duizi')) {
                $duiziTotal += $value;
            }
            //顺子
            if (strstr($key, 'sunzi')) {
                $sunziTotal += $value;
            }
            //豹子
            if (strstr($key, 'baozi')) {
                $baoziTotal += $value;
            }
            //龙
            if (strstr($key, 'long')) {
                $lhbTotal += $value;
            }
            //虎
            if (strstr($key, 'hu')) {
                $lhbTotal += $value;
            }
            //豹
            if (strstr($key, 'bao')) {
                $lhbTotal += $value;
            }
        }

        //单期只能下注3个组合
        $has_bet_total = 0;
        $has_bet       = BetLog::with('details')->where('lotto_index', $name . ':' . $id)->where('user_id', $this->user->id)->first();
        if ($has_bet != null) {
            $bet_details = $has_bet->details->toArray();
            foreach ($bet_details as $bkey => $bval) {
                if (strstr($bval['place'], 'bsg')) {
                    $limit++;
                }
                if (strstr($bval['place'], 'bdo')) {
                    $limit++;
                }
                if (strstr($bval['place'], 'sdo')) {
                    $limit++;
                }
                if (strstr($bval['place'], 'ssg')) {
                    $limit++;
                }
                if (strstr($bval['place'], '13') || strstr($bval['place'], '14')) {
                    $key_13_14 += 1;
                }

                // 0/27限制
                if (strstr($bval['place'], '00') || strstr($bval['place'], '27')) {
                    // $special027 += $value;
                    $number_count++;
                }
                // 1/26限制
                if (strstr($bval['place'], '01') || strstr($bval['place'], '26')) {
                    // $special126 += $value;
                    $number_count++;
                }
                //02/25限制
                if (strstr($bval['place'], '02') || strstr($bval['place'], '25')) {
                    // $special225 += $value;
                    $number_count++;
                }
                //其他数字限制

                for ($i = 3; $i <= 24; $i++) {
                    $bet_n = explode('_', $key)[1];
                    if (intval($bet_n) == $i) {
                        // $specialNumber += $value;
                        $number_count++;
                    }
                }
            }

            $bet_total     = BetLog::where('lotto_index', $name . ':' . $id)->where('user_id', $this->user->id)->sum('total');
            $has_bet_total = $bet_total;
        }

        if ($number_count > 4) {
            return real()->exception('单期最多下注4个数字');
        }

        if (($has_bet_total + $total) > $play_types[$type]['bet_limit']['total']) {
            return real()->exception('该期投注最高' . $play_types[$type]['bet_limit']['total'] . '元');
        }

        if ($limit >= 2 && $key_13_14 > 0) {
            return real()->exception('单期无法同时下注多个组合+13、14');
        }

        if ($limit > 3) {
            return real()->exception('该玩法单期只能下注3个组合');
        }


        if ($dxdsTotal > $play_types[$type]['bet_limit']['dxds']) {
            return real()->exception('大小单双最多下注' . $play_types[$type]['bet_limit']['dxds'] . '元');
        }
        if ($zuheTotal > $play_types[$type]['bet_limit']['combo']) {
            return real()->exception('组合最多下注' . $play_types[$type]['bet_limit']['combo'] . '元');
        }
        if ($jzTotal > $play_types[$type]['bet_limit']['jdjx']) {
            return real()->exception('极大极小最多下注' . $play_types[$type]['bet_limit']['jdjx'] . '元');
        }
        if ($special027 > $play_types[$type]['bet_limit']['z_27']) {
            return real()->exception('00/27最多下注' . $play_types[$type]['bet_limit']['z_27'] . '元');
        }
        if ($special126 > $play_types[$type]['bet_limit']['o_26']) {
            return real()->exception('01/26最多下注' . $play_types[$type]['bet_limit']['o_26'] . '元');
        }
        if ($special225 > $play_types[$type]['bet_limit']['t_25']) {
            return real()->exception('02/25最多下注' . $play_types[$type]['bet_limit']['t_25'] . '元');
        }

        if ($specialNumber > $play_types[$type]['bet_limit']['o_num']) {
            return real()->exception('其他数字最多下注' . $play_types[$type]['bet_limit']['o_num'] . '元');
        }
        if ($duiziTotal > $play_types[$type]['bet_limit']['duizi']) {
            return real()->exception('对子最多下注' . $play_types[$type]['bet_limit']['duizi'] . '元');
        }
        if ($sunziTotal > $play_types[$type]['bet_limit']['sunzi']) {
            return real()->exception('顺子最多下注' . $play_types[$type]['bet_limit']['sunzi'] . '元');
        }
        if ($baoziTotal > $play_types[$type]['bet_limit']['baozi']) {
            return real()->exception('豹子最多下注' . $play_types[$type]['bet_limit']['baozi'] . '元');
        }
        if ($lhbTotal > $play_types[$type]['bet_limit']['lhb']) {
            return real()->exception('龙虎豹最多下注' . $play_types[$type]['bet_limit']['lhb'] . '元');
        }
        if (!empty($this->user->option->bet_limit) && $total > $this->user->option->bet_limit) {
            return real()->error('该期最高投注' . $this->user->option->bet_limit . '元');
        }
        $temp = $this->user->bet($name, $id, $type)->place($place, $total);
        // dd($temp);
        if ($temp !== true) {
            return $temp;
        }

        $result = [
            'success' => $temp,
            'wallet'  => $this->user->wallet,
            // 'bet_data' => $temp['bet_data'],
        ];

        try {
            $result['was_bet']    = $this->wasBet($request)['data']['items'];
            $result['float_odds'] = $this->floatOdds($request)['data']['items'];
        } catch (\Throwable $th) {
        }

        return real($result)->success('恭喜您在（' . $id . '期)下注成功 祝君好运');
    }

    public function betNewest(Request $request)
    {
        $lotto = $request->lotto_name;

        $config = LottoConfig::remember(600)->find($lotto);

        $datetime = date('Y-m-d H:i:s', time() + $config->stop_ahead);
        $model    = LottoUtils::model();

        $data = $model->where('status', 1)->where('lotto_at', '>', $datetime)->get();
        if (count($data) === 0) {
            $result = ['items' => []];
            return real($result)->success();
        }

        $data->makeHidden(['open_code', 'status', 'mark', 'opened_at', 'lotto_name', 'win_extend', 'updated_at']);
        // dd(12211);
        $items = [];
        foreach ($data->toArray() as $item) {
            $items[$item['id']] = $item;
        }

        $result = ['items' => $items];

        return real($result)->success();
    }

    public function cancelBet(Request $request)
    {
        $name = $request->lotto_name;
        $id   = $request->id;
        $type = $request->play_type;
        $temp = $this->user->bet($name, $id, $type)->cancel();
        // dd($temp);
        if ($temp !== true) {
            return $temp;
        }
        $result = [
            'wallet' => $this->user->wallet,
        ];
        return real($result)->success('您在' . $request->id . '期的投注撤销成功！');
    }

    public function chatHistory(Request $request)
    {
        $result = LottoChatHistory::where('type', $request->lotto_name)->where('room', $request->room)->orderBy('id', 'desc')->take(50)->get()->toArray();
        // $result2 = LottoChatHistory::where('type', $request->lotto_name)->where('room', 'room2')->orderBy('id', 'desc')->take(40)->get()->toArray();

        // $result = array_merge($result1, $result2);
        $items = [];
        // dd($result);
        // foreach ($result as $key => $val) {
        //     if ($key != 0 && $result[$key - 1]['cat'] == 4 && $result[$key]['cat'] == 1) {
        //         // unset($result[$key]);
        //     } else {
        //         $items[] = $val;
        //     }
        // }

        return array_reverse($result);
    }

    public function chatOpen(Request $request)
    {
        $data = LottoUtils::model($request->type)->find($request->lotto_id)->toArray();

        $details = [
            'open_code' => $data['win_extend']['code_arr'], 'code_he' => $data['win_extend']['code_he'], 'code_bos' => $data['win_extend']['code_bos'],
            'code_sod'  => $data['win_extend']['code_sod'],
        ];
        $model                             = new LottoChatHistory();
        $model->type                       = $request->type;
        $model->room                       = 'room' . $request->room;
        $model->nickname                   = env('HOST_NAME');
        $model->avatar                     = env('HOST_AVATAR');
        $model->cat                        = 2;
        $model->lotto_id                   = $request->lotto_id;
        $model->details                    = $details;
        $stop_info                         = $model->save();
        $result['stop_info']               = $model;
        $result['item']                    = [];
        $result['stop_info']['lotto_name'] = $request->type;
        $result['stop_info']['play_type']  = 'room' . $request->room;
        PushEvent::name('chatBet')->toPresence('lotto.' . $request->type)->data($result['stop_info']);
        return real()->success();
        // return real($result)->success();
    }

    public function config(Request $request)
    {
        $lotto  = $request->lotto_name;
        $config = LottoConfig::remember(600)->find($lotto)
            ->makeHidden(['sort', 'bet_places', 'stop_ahead', 'disable', 'lotto_rule', 'updated_at', 'created_at', 'win_function', 'config_file'])
            ->toArray();
        return real($config)->debug($lotto)->success();
    }

    public function floatOdds(Request $request)
    {
        $bet_all    = $this->wasBet($request, true)['data']['items'];
        $lotto_name = $request->lotto_name;

        $has_float = config('lotto.base.has_float');

        if (in_array($lotto_name, $has_float) === false) {
            $result = ['items' => []];
            return real($result)->success();
        }

        $model = LottoUtils::model();
        // dd($request->lotto_name);
        $newest = $model->newestLotto();
        if ($newest === null) {
            $result = ['items' => []];
            return real($result)->success();
        }
        $items = [];
        foreach ($bet_all as $lotto_id => $bet) {
            $lotto_index      = $lotto_name . ':' . $lotto_id;
            $rand             = $lotto_id == $newest->id ? true : false;
            $model            = new LottoFloatOdds();
            $odds             = $model->computeCurrentOdds($lotto_name, $lotto_id, $bet, $rand);
            $items[$lotto_id] = $odds;
        }
        $result = ['items' => $items];

        return real($result)->success();
    }

    public function freshChat(Request $request)
    {
        $result = LottoChatHistory::where('id', '<', $request->id)
            ->where('type', $request->type)->where('room', 'room' . $request->room)
            ->where('cat', 1)
            ->orderBy('id', 'desc')->take(40)->get()->toArray();
        $items = [];
        foreach ($result as $key => $val) {
            if ($key != 0 && $result[$key - 1]['cat'] == 4 && $result[$key]['cat'] == 1) {
                // unset($result[$key]);
            } else {
                $items[] = $val;
            }
        }

        $result['items'] = $items;
        return real($result)->success();
    }

    public function historyLog(Request $request)
    {
        $model = LottoUtils::model();
        $items = $model->where('status', 2);
        $request->start_date && $items->where('lotto_at', '>=', $request->start_date);
        $request->end_date && $items->where('lotto_at', '<=', $request->end_date . ' 23:59:59');
        $items->orderBy('id', 'desc')->whereNotNull('lotto_at');
        $paginate = $items->paginate(15);

        $paginate->data = $paginate->makeHidden(['control', 'updated_at', 'opened_at']);

        $result = $paginate->toArray();
        return real()->listPage($result)->success();
    }

    public function lottoFavorite(Request $request)
    {
        $rule = [
            'lotto_name' => 'required',
            'play_type'  => 'required',
            'status'     => 'required',
        ];
        $data = [
            'lotto_name' => $request->lotto_name,
            'play_type'  => $request->play_type,
            'status'     => $request->status,
        ];

        real()->validator($data, $rule);

        $where = [
            'user_id'    => $this->user->id,
            'lotto_name' => $request->lotto_name,
            'play_type'  => $request->play_type,
        ];
        $value = ['status' => $request->status];
        $data  = LottoUserFavorite::updateOrCreate($where, $value);

        $result = ['items' => LottoUserFavorite::getItemsForApp($this->user->id)];
        return real($result)->success('收藏更新成功');
    }

    public function nowBet(Request $request)
    {
        // $model = LottoUtils::model($request->lotto_name);

        $init = LottoPlayType::where('name', 'room' . $request->room)->first()->place;

        $init = array_slice($init, 0, 10);
        if ($request->type == 'bit28') {
            $loop_num = mt_rand(2, 5);
            dump($loop_num);
            $rand = 0;
            for ($i = 1; $i < $loop_num; $i++) {
                $rand      = 0;
                $rand      = mt_rand(1, 3);
                $rand_init = array_rand($init, $rand);
                $total     = 0;

                if (is_array($rand_init)) {
                    foreach ($rand_init as $rval) {
                        $details = [];
                        $amount  = mt_rand(ceil(20 / 10), floor(500 / 10)) * 10;
                        $total += $amount;
                        $details[] = [
                            'place'  => $init[$rval]['place'],
                            'amount' => toDecimal($amount),
                            'title'  => $init[$rval]['title'],
                            'name'   => $init[$rval]['name'],
                        ];
                    }
                } else {
                    $details = [];
                    $amount  = mt_rand(ceil(20 / 10), floor(500 / 10)) * 10;
                    $total += $amount;
                    $details[] = [
                        'place'  => $init[$rand_init]['place'],
                        'amount' => toDecimal($amount),
                        'title'  => $init[$rand_init]['title'],
                        'name'   => $init[$rand_init]['name'],
                    ];
                }

                $user = User::inRandomOrder()->where('robot', 1)->first();

                $result = [
                    'message_type' => 'bet',
                    'play_type'    => 'room' . $request->room,
                    'nickname'     => $user->nickname,
                    'avatar'       => $user->avatar,
                    'user_id'      => $user->id,
                    'created_at'   => date('Y-m-d H:i:s'),
                    'total'        => toDecimal($total),
                    'lotto_id'     => $request->lotto_id,
                    'details'      => $details,
                    'cat'          => 1,
                    'room'         => 'room' . $request->room,
                    'lotto_name'   => $request->type,

                ];

                //dd(json_encode($details, JSON_UNESCAPED_UNICODE));
                $model           = new LottoChatHistory();
                $model->type     = $request->type;
                $model->room     = 'room' . $request->room;
                $model->avatar   = $user->avatar;
                $model->nickname = $user->nickname;
                $model->total    = toDecimal($total);
                $model->details  = $details;
                $model->lotto_id = $request->lotto_id;
                $model->user_id  = $user->id;
                $model->save();
                $result['id'] = $model->id;
                // dump($request->type);
                // dump($result);

                $push = PushEvent::name('chatBet')->toPresence('lotto.' . $request->type)->data($result);
                sleep(1);
            }
        } else {
            $rand      = mt_rand(1, 3);
            $rand_init = array_rand($init, $rand);
            $total     = 0;

            if (is_array($rand_init)) {
                foreach ($rand_init as $rval) {
                    $amount = mt_rand(ceil(20 / 10), floor(500 / 10)) * 10;
                    $total += $amount;
                    $details[] = [
                        'place'  => $init[$rval]['place'],
                        'amount' => toDecimal($amount),
                        'title'  => $init[$rval]['title'],
                        'name'   => $init[$rval]['name'],
                    ];
                }
            } else {
                $amount = mt_rand(ceil(20 / 10), floor(500 / 10)) * 10;
                $total += $amount;
                $details[] = [
                    'place'  => $init[$rand_init]['place'],
                    'amount' => toDecimal($amount),
                    'title'  => $init[$rand_init]['title'],
                    'name'   => $init[$rand_init]['name'],
                ];
            }

            $user = User::inRandomOrder()->where('robot', 1)->first();

            $result = [
                'message_type' => 'bet',
                'play_type'    => 'room' . $request->room,
                'nickname'     => $user->nickname,
                'avatar'       => $user->avatar,
                'user_id'      => $user->id,
                'created_at'   => date('Y-m-d H:i:s'),
                'total'        => toDecimal($total),
                'lotto_id'     => $request->lotto_id,
                'details'      => $details,
                'cat'          => 1,
                'room'         => 'room' . $request->room,
                'lotto_name'   => $request->type,

            ];

            //dd(json_encode($details, JSON_UNESCAPED_UNICODE));
            $model           = new LottoChatHistory();
            $model->type     = $request->type;
            $model->room     = 'room' . $request->room;
            $model->avatar   = $user->avatar;
            $model->nickname = $user->nickname;
            $model->total    = toDecimal($total);
            $model->details  = $details;
            $model->lotto_id = $request->lotto_id;
            $model->user_id  = $user->id;
            $model->save();
            $result['id'] = $model->id;
            // dump($request->type);
            // dump($result);

            $push = PushEvent::name('chatBet')->toPresence('lotto.' . $request->type)->data($result);
        }

        // $result['bet_info'] = $result;
        // return real($result)->success();
    }

    public function openCheck(Request $request)
    {
        $model  = LottoUtils::model();
        $result = $model->find($request->lotto_id)->openCheck($request->play_type);
        return real($result)->success();
    }

    public function openLast(Request $request)
    {
        $model = LottoUtils::model();
        $model->makeHidden('open_code', 'opened_at', 'mark', 'extend');

        $lotto  = $request->lotto_name;
        $config = LottoConfig::remember(600)->find($lotto);
        $take   = $request->open_last != '' ? $request->open_last : 10;
        if ($config->lotto_disable === true) {
            $data = $model->where('status', '!=', '1')->orderBy('id', 'desc')->take($take)->remember(600)->get();
        } else {
            $datetime = date('Y-m-d H:i:s', time() + $config->stop_ahead);
            $data     = $model->where('lotto_at', '<=', $datetime)->orderBy('id', 'desc')->take($take)->remember(600)->get();
        }

        $data->makeHidden(['updated_at', 'mark', 'status', 'bet_count_down', 'opened_at']);

        $result = [
            'items'  => $data->toArray(),
            'reload' => 10,
        ];
        $bos_line = 1;
        $sod_line = 1;
        foreach ($result['items'] as $key => $val) {
            if ($key > 0 && $result['items'][$key - 1]['win_extend']['code_bos'] != $result['items'][$key]['win_extend']['code_bos']) {
                $bos_line += 1;
            }
            if ($key > 0 && $result['items'][$key - 1]['win_extend']['code_sod'] != $result['items'][$key]['win_extend']['code_sod']) {
                $sod_line += 1;
            }

            $result['items'][$key]['bos_line'] = $bos_line;
            $result['items'][$key]['sod_line'] = $sod_line;
        }

        return real($result)->debug($config->lotto_disable)->success();
    }

    public function openLog(Request $request)
    {
        $model    = LottoUtils::model();
        $items    = $model->orderBy('id', 'desc')->whereNotNull('lotto_at');
        $paginate = $items->paginate(15);

        $paginate->data = $paginate->makeHidden(['control', 'updated_at', 'opened_at']);

        $newest = $model->newestLotto();
        if ($newest === null) {
            $newest = $model->lastLotto();
            if ($newest !== null) {
                $newest->id += 1;
            }
        }

        $indexes = [];

        foreach ($paginate->data as $key => $value) {
            $temp             = $value->lotto_name . ':' . $value->id;
            $value->bet_stats = (object) [];
            $indexes[$temp]   = $key;

            //开始机器人
            if ($newest == null) {
                continue;
            }
            $cache_name        = 'floatOdds' . $temp . $value->status;
            $cache_second      = $value->status != '1' ? 600 : 0;
            $value->float_odds = cache()->remember($cache_name, $cache_second, function () use ($value, $newest) {
                $float_odds = $value->floatOdds($newest->id);
                return $float_odds;
            });
        }

        //开始统计投注统计
        $raw_total = DB::raw('sum(total) AS total');
        $raw_bonus = DB::raw('sum(bonus) AS bonus');
        $raw_group = DB::raw('lotto_index,play_type');
        $bet_logs  = BetLog::whereIn('lotto_index', array_keys($indexes))
            ->where('user_id', $this->user->id)
            ->groupBy($raw_group)
            ->get([$raw_total, $raw_bonus, 'lotto_index', 'play_type']);

        $bet_stats = [];
        foreach ($bet_logs as $log) {
            $bet_stats[$log->lotto_index][$log->play_type] = [
                'total' => toDecimal($log->total),
                'bonus' => toDecimal($log->bonus),
            ];
        }

        foreach ($bet_stats as $key => $value) {
            $index              = $indexes[$key];
            $current            = $paginate->data[$index];
            $current->bet_stats = $value;
        }
        //统计投注结束

        $result = $paginate->toArray();
        return real()->listPage($result)->success();
    }

    public function placeExtend(Request $request)
    {
        $model  = LottoUtils::model();
        $result = $model->placeExtend();
        return $result;
    }

    public function showItem(Request $request)
    {
        $items = LottoChatHistory::select('nickname', 'total', 'details')
            ->where('lotto_id', $request->lotto_id)
            ->where('type', $request->type)
            ->where('room', $request->room)
            ->where('user_id', $request->user_id)
            ->get()->toArray();

        $result             = [];
        $result['nickname'] = $items[0]['nickname'];
        $result['user_id']  = $request->user_id;
        $result['total']    = 0;
        $result['details']  = [];
        foreach ($items as $key => $val) {
            $result['total'] += intval($val['total']);
            foreach ($val['details'] as $dkey => $dval) {
                if (isset($result['details'][$dval['place']])) {
                    $result['details'][$dval['place']]['amount'] += $dval['amount'];
                } else {
                    $result['details'][$dval['place']] = ['amount' => $dval['amount'], 'name' => $dval['name']];
                }
            }
        }
        return real($result)->success();
    }

    public function stopInfo(Request $request)
    {
        // file_put_contents('test.txt', print_r($request->all(), true), FILE_APPEND);
        if ($request->add_type != 4) {
            $data_exist = LottoChatHistory::where('lotto_id', $request->lotto_id)->where('type', $request->type)
                ->where('cat', $request->add_type)->where('room', 'room' . $request->room)->first();
            if ($data_exist) {
                $result['stop_info'] = $data_exist;
                if ($request->add_type == 4) {
                    $total_data_exist = LottoChatHistory::where('lotto_id', $request->lotto_id)->where('type', $request->type)
                        ->where('cat', 6)->where('room', 'room' . $request->room)->first();
                    if ($total_data_exist) {
                        $result['item'] = $total_data_exist;
                    } else {
                        $result['item'] = [];
                    }
                }
                return real($result)->success();
            }
        }

        $model           = new LottoChatHistory();
        $model->type     = $request->type;
        $model->room     = 'room' . $request->room;
        $model->nickname = env('HOST_NAME');
        $model->avatar   = env('HOST_AVATAR');
        $model->lotto_id = $request->lotto_id;

        if ($request->add_type == 3) {
            $model->cat     = 3;
            $model->details = option('open_notice'); //'第' . $request->lotto_id . '期已开盘,可以进行下注,开始下注';
        } else if ($request->add_type == 5) {
            $model->cat     = 5;
            $model->details = option('second_notice');
        } else {
            $model->cat     = 4;
            $model->details = option('close_notice');
        }

        $model->save();
        $result['stop_info']               = $model;
        $result['stop_info']['lotto_name'] = $request->type;
        $result['stop_info']['play_type']  = 'room' . $request->room;
        $items                             = [];
        if ($request->add_type == 4) {
            $items = LottoChatHistory::where('type', $request->type)
                ->select('type', 'room', 'lotto_id', 'user_id', 'nickname')
                ->where('cat', 1)
                ->where('room', 'room' . $request->room)
                ->where('lotto_id', $request->lotto_id)->get()->toArray();
            if (count($items) != 0) {
                //去重
                $array               = $this->assoc_unique($items, 'user_id');
                $new_model           = new LottoChatHistory();
                $new_model->type     = $request->type;
                $new_model->room     = 'room' . $request->room;
                $new_model->nickname = env('HOST_NAME');
                $new_model->avatar   = env('HOST_AVATAR');
                $new_model->cat      = 6;
                $new_model->lotto_id = $request->lotto_id;
                $new_model->details  = $array;
                $new_model->user_id  = 10000;
                $new_model->save();
                $items = $new_model;
            }
        }
        $result['item']                      = $items;
        $result['stop_info']['message_type'] = 'bet';
        PushEvent::name('chatBet')->toPresence('lotto.' . $request->type)->data($result['stop_info']);
        if (!empty($items)) {
            $result['item']['created_at'] = date('Y-m-d H:i:s');
            $result['item']['lotto_name'] = $request->type;
            $result['item']['play_type']  = 'room' . $request->room;
            PushEvent::name('chatBet')->toPresence('lotto.' . $request->type)->data($result['item']);
        }
        if (in_array($request->add_type, [3, 4])) {
            $this->sendAdvertising($request);
        }
        return real()->success();
    }

    public function toWalletAction(Request $request)
    {
        $type   = $request->type;
        $amount = $request->amount;
        if ($type == 'recharge') {
            $controller = new RechargeController();
            $info       = $controller->create($request);
        } else {
            $controller = new WithdrawController();
            $info       = $controller->create($request, false);
        }
        if ($info['code'] != 200) {
            return $info;
        }
        $detail              = $type == 'recharge' ? '查' : '回';
        $new_model           = new LottoChatHistory();
        $new_model->type     = $request->lotto_name;
        $new_model->room     = $request->play_type;
        $new_model->nickname = $this->user->nickname;
        $new_model->avatar   = $this->user->avatar;
        $new_model->cat      = 7;
        $new_model->wechat   = $this->user->wechat;
        $new_model->lotto_id = $request->lotto_id;
        $new_model->details  = ['content' => $detail . ':' . $amount];
        $new_model->user_id  = $this->user->id;
        $new_model->save();
        $items                 = $new_model;
        $items['message_type'] = 'bet';
        $items['created_at']   = date('Y-m-d H:i:s');
        $items['lotto_name']   = $request->lotto_name;
        $items['play_type']    = $request->play_type;
        PushEvent::name('chatBet')->toPresence('lotto.' . $request->lotto_name)->data($items);
        $result = [
            'success' => true,
            'wallet'  => $this->user->wallet,
        ];
        return real($result)->success('下注成功');
    }

    public function united(Request $request)
    {
        $wallet     = new UserWalletController();
        $bet_newest = $this->betNewest($request)['data']['items'];
        $result     = [
            'config'        => $this->config($request)['data'],
            'open_log'      => $this->openLog($request)['data'], //卡啊
            'open_last'     => $this->openLast($request)['data']['items'], //卡啊
            'bet_newest'    => (object) $bet_newest,
            //'place_extend' => $this->placeExtend($request),
            'wallet'        => $this->user->wallet,
            'was_bet'       => (object) $this->wasBet($request)['data']['items'], //卡啊
            // 'float_odds'    => (object) $this->floatOdds($request)['data']['items'],
            'user_tools'    => $this->userTools($request), //卡啊
            // 'bet_automator' => $this->betAutomator($request),
            // 'user_odds'     => (object) LottoUserOdds::userOdds($this->user->id, $request->lotto_name),
            'lotto_history' => $this->chatHistory($request),
        ];
        return real($result)->success();
    }

    public function wasBet(Request $request, $get_all = false)
    {
        $bet_newest = $this->betNewest($request)['data']['items'];
        $ids        = array_keys($bet_newest);
        $indexes    = [];

        try {
            $model = LottoUtils::model();
            $data  = $model->where('id', '<', $ids[0])->orderBy('id', 'desc')->first();
            $data && array_unshift($ids, $data->id);
        } catch (\Throwable $th) {
        }

        $where_in = [];
        foreach ($ids as $value) {
            $key             = $request->lotto_name . ':' . $value;
            $where_in[]      = $key;
            $indexes[$value] = [];
        }

        $bet_log = BetLog::with('details');
        $get_all || $bet_log->where('user_id', $this->user->id);
        $get_all && $bet_log->whereIn('play_type', ['fd', 'st', 'el']);
        $bet_log = $bet_log->whereIn('lotto_index', $where_in)->get();

        foreach ($bet_log as $value) {
            $index = $value->lotto_id;
            foreach ($value->details as $detail) {
                if (isset($indexes[$index][$detail->place])) {
                    $temp = bcadd($indexes[$index][$detail->place], $detail->amount, 3);
                } else {
                    $temp = $detail->amount;
                }

                $indexes[$index][$detail->place] = sprintf('%.3f', $temp);
            }
        }

        foreach ($indexes as &$value) {
            $value = (object) $value;
        }

        $result = ['items' => $indexes];
        return real($result)->success();
    }

    private function BetStatsDay(Request $request, $reload = false)
    {
        $start_date = date('Y-m-d');
        $end_date   = date('Y-m-d 23:59:59');
        $user_id    = $this->user->id;
        $lotto_name = $request->lotto_name;
        $cache_name = 'BetStatsDay:' . $this->user->id . $request->lotto_name . date('Y-m-d');
        $reload && cache()->forget($cache_name);
        $data = cache()->remember($cache_name, 600, function () use ($user_id, $lotto_name, $start_date, $end_date) {
            return DB::table('bet_logs')->where('user_id', $user_id)
                ->where('lotto_index', 'regexp', $lotto_name)
                ->where('created_at', '>=', $start_date)
                ->where('created_at', '<', $end_date)
                ->groupBy('play_type')
                ->get([
                    'play_type',
                    DB::raw('count(id) AS count'),
                    DB::raw('count(if( bonus > 0,1,null)) AS c_win'),
                    DB::raw('sum(total) AS total'),
                    DB::raw('sum(bonus) AS bonus'),
                    DB::raw('sum(bonus - total) AS profit'),
                ]);
        });

        $result = [];
        foreach ($data as $value) {
            $value->win_rate           = intval($value->c_win / $value->count * 100) . '%';
            $result[$value->play_type] = $value;
            $play_type                 = $value->play_type;
            $cache_count_name          = 'BetStatsDay:' . $this->user->id . $request->lotto_name . date('Y-m-d') . '-count';
            $reload && cache()->forget($cache_count_name);
            $result[$value->play_type]->count = cache()->remember($cache_count_name, 600, function () use ($user_id, $play_type, $lotto_name, $start_date, $end_date) {
                return count(DB::table('bet_logs')->where('user_id', $user_id)
                    ->where('lotto_index', 'regexp', $lotto_name)
                    ->where('play_type', $play_type)
                    ->where('created_at', '>=', $start_date)
                    ->where('created_at', '<', $end_date)
                    ->groupBy('lotto_index')->get()->toArray());
            });
        }

        return $result;
    }

    private function betAutomator(Request $request)
    {
        $items  = BetAutomator::where('user_id', $this->user->id)->where('lotto_name', $request->lotto_name)->get();
        $result = [];

        foreach ($items as $value) {
            $result[$value->play_type] = $value;
        }

        return $result;
    }

    private function lastBet()
    {
        $user_id    = $this->user->id;
        $lotto_name = request()->lotto_name;

        $data = BetLog::where('user_id', $user_id)->where('lotto_index', 'regexp', $lotto_name)
            ->with(['details' => function ($query) {
                $query->select('title', 'log_id', 'place', 'amount');
            }])->whereHas('details');
        $result = $data->orderBy('id', 'desc')
            ->groupBy('play_type')->get(['id', 'play_type'])->toArray();

        array_walk($result, function (&$value) {
            foreach ($value['details'] as $val) {
                $details[$val['place']] = $val['amount'];
            }
            $value['details'] = $details;
            unset($value['id'], $value['lotto_name'], $value['lotto_id'], $value['lotto_title'], $value['profit'], $value['short_id']);
        });
        $result = array_combine(array_column($result, 'play_type'), $result);
        array_walk($result, function (&$value) {
            $value = $value['details'];
        });
        return $result;
    }

    private function sendAdvertising(Request $request)
    {
        $model           = new LottoChatHistory();
        $model->type     = $request->type;
        $model->room     = 'room' . $request->room;
        $model->nickname = env('HOST_NAME');
        $model->avatar   = env('HOST_AVATAR');
        $model->lotto_id = $request->lotto_id;
        $model->cat      = 4;
        $model->details  = $request->add_type == 3 ? option('advertising_1') : option('advertising_2');
        $model->user_id  = 10000;
        $model->save();
        $item               = $model;
        $item['created_at'] = date('Y-m-d H:i:s');
        $item['lotto_name'] = $request->type;
        $item['play_type']  = 'room' . $request->room;
        PushEvent::name('chatBet')->toPresence('lotto.' . $request->type)->data($item);
        return real()->success();
    }

    private function userTools(Request $request)
    {
        $items = BetTools::where('user_id', $this->user->id)
            ->where('lotto_name', $request->lotto_name)
            ->get();

        return $items->toArray();
    }
}
