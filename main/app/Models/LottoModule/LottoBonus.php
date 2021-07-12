<?php

namespace App\Models\LottoModule;

use App\Models\User;
use App\Packages\Utils\PushEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\LottoModule\Models\BetLog;
use App\Models\LottoModule\Models\LottoConfig;
use App\Models\LottoModule\Models\BetAutomator;
use App\Models\LottoModule\Models\LottoPlayType;

class LottoBonus
{
    public function batch($lotto_index, $bet_amount)
    {
        $data    = BetLog::where('lotto_index', $lotto_index)->get(['id', 'lotto_index']);
        $success = [];
        $error   = [];
        foreach ($data as $value) {
            try {
                $temp                = $this->execute($value->id, $bet_amount);
                $success[$value->id] = $temp;
            } catch (\Throwable $th) {
                DB::rollBack();
                $error[$value->id] = $th->getMessage();
                dump($error);
            }
        }

        $error && Log::error($error);
    }

    public function execute($id, $bet_amount)
    {
        DB::beginTransaction();

        $bet_log = BetLog::lockForUpdate()->find($id);
        $bet_log || real()->exception('bet.log.is.null');
        $bet_log->status !== 1 && real()->exception('bonus.status.error');

        $lotto  = LottoUtils::model($bet_log->lotto_name)->find($bet_log->lotto_id);
        $config = LottoConfig::find($bet_log->lotto_name);

        $bet_time  = strtotime($bet_log->created_at);
        $stop_time = strtotime($lotto->lotto_at) - $config->stop_ahead;

        if ($bet_time > $stop_time) {
            real()->exception('bonus.bet_time.error');
        }

        $user            = User::find($bet_log->user_id);
        $odds_discount   = 1;
        $lotto_play_type = LottoPlayType::where('name', $bet_log['play_type'])->first();

        $bssd_bet_amount = $bet_amount['bssd_bet']; //大小单双投注
        $comb_bet_amount = $bet_amount['comb_bet']; //组合投注

        if (empty($lotto_play_type)) {
            real()->exception('not.exist.this.type');
        }

        $open_code = $lotto->open_code;

        $win_extend = $lotto->win_extend;

        //获取开奖计算结果
        $func = $config->win_function;

        $win_place = LottoWinPlace::$func($open_code, $bet_log->lotto_name);

        $details = $bet_log->details;

        $win_max = $config->bet_quota->win;

        $has_win = BetLog::where('lotto_index', $bet_log->lotto_index)
            ->where('status', 2)
            ->where('user_id', $bet_log->user_id)
            ->sum('bonus');
        $win_max -= $has_win;

        $total_bonus = 0;

        $single_limit     = $lotto_play_type['single_limit'];
        $single_condition = $lotto_play_type['single_condition'];
        $comb_limit       = $lotto_play_type['comb_limit'];
        $comb_condition   = $lotto_play_type['comb_condition'];

        foreach ($details as $value) {
            if (!in_array($value->place, $win_place)) {
                $value->bonus = '0.00';
                $value->save() || real()->exception('bonus.log.save.failed');
                continue;
            }

            $odds = $value->odds;
            $odds = bcmul($value->odds, $odds_discount, 4);
            // dump($func);
            //28系列
            if ($func == 'lotto28') {
                // $bet_log['play_type']   = 'room6';
                // $value->place           = 'room6_bdo';
                // $win_extend['code_arr'] = ['3', '6', '5'];
                // $win_extend['code_he']  = '14';
                // $value['amount']        = 50001;
                $code   = $win_extend['code_arr'];
                $unique = array_unique($code);
                $unique = count($unique);

                $is_dui = false;
                if ($unique === 2) {
                    $is_dui = true; //是对子
                }
                //dd($is_dui);
                $is_bao = false;
                if ($unique === 1) {
                    $is_bao = true; //是豹子
                }

                $is_shun = false;
                asort($code);
                $str = implode('', $code);
                if (preg_match('/^(0(?=1)|1(?=2)|2(?=3)|3(?=4)|4(?=5)|5(?=6)|6(?=7)|7(?=8)|8(?=9)){2}\d$/', $str)) {
                    $is_shun = true; //是顺子
                }

                if ($str === '019' || $str === '089') {
                    $is_shun = true; //顺子
                }

                $is_13 = false;
                if ($win_extend['code_he'] === '13') {
                    $is_13 = true;
                }

                $is_14 = false;
                if ($win_extend['code_he'] === '14') {
                    $is_14 = true;
                }
                $is_bssd = false;
                if (strstr($value->place, '_big') || strstr($value->place, '_sml') || strstr($value->place, '_sig') || strstr($value->place, '_dob')) {
                    $is_bssd = true;
                }

                $is_comb = false; //_bsg|_sdo|_ssg|_bdo
                if (strstr($value->place, '_bsg') || strstr($value->place, '_sdo') || strstr($value->place, '_ssg') || strstr($value->place, '_bdo')) {
                    $is_comb = true;
                }

                // if (strstr($value->place, '_sig')) {
                //     dump($bssd_bet_amount);
                // dump($val['bet_amount']);
                // dump($val['status']);
                // dump($is_13 . '--' . $is_14);
                // }

                if ($is_bssd) {
                    foreach ($single_limit as $val) {
                        if ($bssd_bet_amount > $val['bet_amount'] && $val['status'] == true && ($is_13 || $is_14)) {
                            // dump('大于' . $value['bet_amount'] . '且投注开13/14');
                            $odds          = $val['odds'];
                            $value->extend = '总投注大于>' . $val['bet_amount'] . ',倍数' . $val['odds'];
                        }
                    }

                    if ($is_dui && $single_condition['pair']['status'] == true) {
                        // dump('开' . $single_condition['pair']['title'] . '啊');
                        $odds          = $single_condition['pair']['odds'];
                        $value->extend = '大小单双投注开对子时,倍数' . $single_condition['pair']['odds'];
                    }
                    if ($is_bao && $single_condition['leopard']['status'] == true) {
                        //dump('开' . $single_condition['pair']['title'] . '啊');
                        $odds          = $single_condition['leopard']['odds'];
                        $value->extend = '大小单双投注开豹子时,倍数' . $single_condition['leopard']['odds'];
                    }
                    if ($is_shun && $single_condition['straight']['status'] == true) {
                        //dump('开' . $single_condition['pair']['title'] . '啊');
                        $odds          = $single_condition['straight']['odds'];
                        $value->extend = '大小单双投注开顺子时,倍数' . $single_condition['straight']['odds'];
                    }
                    // die;
                }

                if ($is_comb) {
                    // dump('是组合啊');
                    foreach ($comb_limit as $val) {
                        if ($comb_bet_amount > $val['bet_amount'] && $val['status'] == true && ($is_13 || $is_14)) {
                            // dump('大于' . $value['bet_amount'] . '且投注开13/14');
                            $odds          = $val['odds'];
                            $value->extend = '总投注大于>' . $val['bet_amount'] . ',倍数' . $val['odds'];
                        }
                    }
                    if ($is_dui && $comb_condition['pair']['status'] == true) {
                        // dump('开' . $single_condition['pair']['title'] . '啊');
                        $odds          = $comb_condition['pair']['odds'];
                        $value->extend = '组合投注开对子时,倍数' . $comb_condition['pair']['odds'];
                    }
                    if ($is_bao && $comb_condition['leopard']['status'] == true) {
                        //dump('开' . $single_condition['pair']['title'] . '啊');
                        $odds          = $comb_condition['leopard']['odds'];
                        $value->extend = '组合投注开豹子时,倍数' . $comb_condition['leopard']['odds'];
                    }
                    if ($is_shun && $comb_condition['straight']['status'] == true) {
                        //dump('开' . $single_condition['pair']['title'] . '啊');
                        $odds          = $comb_condition['straight']['odds'];
                        $value->extend = '组合投注开顺子时,倍数' . $comb_condition['straight']['odds'];
                    }
                }
                //$lotto_play_type
                if ($bet_log['play_type'] == 'room3') {

                    if ($is_13 && strstr($value->place, '_ssg')) {
                        $odds = 0;
                        $value->extend = '13、 下注小单 通杀';
                    }

                    if ($is_14 && strstr($value->place, '_bdo')) {
                        $odds = 0;
                        $value->extend = '14 下注大双 通杀';
                    }
                }


                //房间5， 对子豹子顺子通杀
                if ($bet_log['play_type'] == 'room5') {

                    if ($is_13 && strstr($value->place, '_ssg')) {
                        $odds = 0;
                        $value->extend = '13、 下注小单 通杀';
                    }

                    if ($is_14 && strstr($value->place, '_bdo')) {
                        $odds = 0;
                        $value->extend = '14 下注大双 通杀';
                    }


                    if (($is_13 || $is_14) && ($is_bao || $is_shun || $is_dui)) {
                        $odds = 0;
                        $value->extend = '13、14 对子豹子顺子 通杀';
                    }
                }
            }

            // dd($odds);
            //如果结算赔率跟原始赔率不一致

            $odds == $value->odds || $value->odds_settle = $odds;

            $value->bonus = bcmul($value->amount, $odds, 3);
            // dd($value->bonus);
            //如果大于 最大中奖额度
            if ($total_bonus + $value->bonus > $win_max) {
                $remain       = bcsub($win_max, $total_bonus, 3);
                $value->bonus = $remain;
            }

            $total_bonus += $value->bonus;
            $value->save() || real()->exception('bonus.log.save.failed');
        }
        $bet_log->open_code    = $open_code;
        $bet_log->bonus        = $total_bonus;
        $bet_log->status       = 2;
        $bet_log->confirmed_at = date('Y-m-d H:i:s');
        $bet_log->save() || real()->exception('bonus.save.failed');

        if ($total_bonus > 0) {
            $temp = 'lotto.bonus.' . $bet_log->lotto_name . '.' . $bet_log->play_type;
            $user->wallet->balance($temp, $bet_log->id)->plus($total_bonus);
        }

        DB::commit();

        try {
            $this->betAutomator($bet_log);
            $this->sendMessage($user, $config, $bet_log);
            $this->riskBonus($user, $bet_log);
        } catch (\Throwable $th) {
            dump($th->getMessage());
            return true;
        }

        return true;
    }

    private function betAutomator($bet_log)
    {
        if ($bet_log->auto_id == null) {
            return false;
        }

        // DB::beginTransaction();
        $auto    = BetAutomator::find($bet_log->auto_id);
        $execute = $auto->execute();
        // DB::commit();

        return $execute;
    }

    private function riskBonus($user, $bet_log)
    {
        if ($user->trial === true || $bet_log->bonus < 5000) {
            return false;
        }

        $risk   = new \App\Models\RiskCenter();
        $params = [
            'user_id'   => $user->id,
            'nickname'  => $user->nickname,
            'log_id'    => $bet_log->id,
            'log_total' => $bet_log->bonus,
        ];
        $risk->lottoBonusNotice($params);
    }

    private function sendMessage($user, $config, $bet_log)
    {
        //通知
        $message = '';

        if ($bet_log->bonus > 0) {
            $message = '恭喜您在<' . $config->title . '>中奖了！';
        } else {
            $message = '您在<' . $config->title . '>的下注开奖了';
        }

        dump($message);

        $params = [
            'message' => $message,
            'bet_log' => $bet_log->id,
            'wallet'  => $user->wallet,
            'action'  => 'lotto_refresh',
        ];
        PushEvent::name('Toast')->toUser($user->id)->data($params);
    }
}
