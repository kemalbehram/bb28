<?php

namespace App\Models\LottoModule;

class LottoWinPlace
{
    public static function kuai3($code)
    {
        $code = explode(',', $code);
        $he   = $code[0] + $code[1] + $code[2];

        $result   = [];
        $result[] = 'qth_' . sprintf('%02d', $he);

        $he % 2 == 0 && $result[] = 'qth_dob';
        $he % 2 == 1 && $result[] = 'qth_sig';
        $he >= 11 && $result[]    = 'qth_big';
        $he <= 10 && $result[]    = 'qth_sml';

        //是否为顺子
        asort($code);
        $str = implode('', $code);
        if (preg_match('/^(0(?=1)|1(?=2)|2(?=3)|3(?=4)|4(?=5)|5(?=6)|6(?=7)|7(?=8)|8(?=9)){2}\d$/', $str)) {
            $result[] = 'jun';
        }

        //是否为豹子
        $unique                           = array_unique($code);
        count($unique) === 1 && $result[] = 'qto_leo';

        //三军
        foreach (array_unique($code) as $value) {
            $result[] = 'qtj_' . $value;
        }

        return $result;
    }

    public static function lotto28($open_code, $lotto_name)
    {
        $formula = LottoFormula::$lotto_name($open_code);
        $code    = $formula['code_arr'];

        $he = $formula['code_he'];

        $win = [];

        $he % 2 == 0 && $win[]              = 'dob'; //双
        $he % 2 == 1 && $win[]              = 'sig'; //单
        $he >= 14 && $win[]                 = 'big'; //大
        $he <= 13 && $win[]                 = 'sml'; //小
        $he <= 5 && $win[]                   = 'xsm'; //极小
        $he >= 22 && $win[]                  = 'xbg'; //极大
        $he % 2 == 0 && $he >= 14 && $win[] = 'bdo'; //大双
        $he % 2 == 1 && $he >= 14 && $win[] = 'bsg'; //大单
        $he % 2 == 0 && $he <= 13 && $win[] = 'sdo'; //小双
        $he % 2 == 1 && $he <= 13 && $win[] = 'ssg'; //小单

        //36部分开奖
        // $code = $formula['code_arr'];
        // asort($code);
        // $code = array_values($code);
        // $str = implode('', $code);
        // $unique = array_unique($code);
        // $win_ts = 'oth'; //36玩法开奖 默认为杂

        // ($code[0] + 1 == $code[1] || $code[1] + 1 == $code[2] || ($code[0] == 0 && $code[2] == 9)) && $win_ts = 'juh'; //半顺
        // count($unique) === 2 && $win_ts = 'pai'; //对
        // count($unique) === 1 && $win_ts = 'leo'; //豹子
        // implode('', $code) === '019' && $win_ts = 'jun'; //019为顺
        // ($code[0] + 1 == $code[1] && $code[1] + 1 == $code[2]) && $win_ts = 'jun'; //顺

        $result = [];

        //特殊玩法-龙
        $long_arr = ['00', '03', '06', '09', '12', '15', '18', '21', '24', '27'];
        if (in_array($he, $long_arr)) {
            $win[] = 'long';
        }
        //特殊玩法-虎
        $hu_arr = ['01', '04', '07', '10', '13', '16', '19', '22', '25'];
        if (in_array($he, $hu_arr)) {
            $win[] = 'hu';
        }
        //特殊玩法-豹
        $bao_arr = ['02', '05', '08', '11', '14', '17', '20', '23', '26'];
        if (in_array($he, $bao_arr)) {
            $win[] = 'bao';
        }
        //是否为顺子
        asort($code);
        $str = implode('', $code);
        if (preg_match('/^(0(?=1)|1(?=2)|2(?=3)|3(?=4)|4(?=5)|5(?=6)|6(?=7)|7(?=8)|8(?=9)){2}\d$/', $str)) {
            $win[] = 'sunzi';
        }
        //顺子特殊
        if (implode('', $code) === '019') {
            $win[] = 'sunzi';
        }

        //是否为豹子
        $unique                        = array_unique($code);
        count($unique) === 1 && $win[] = 'baozi';

        //是否为对子
        count($unique) === 2 && $win[] = 'duizi';

        //数字玩法
        if (strlen($he) == 1) {
            $win[] = '0' . $he;
        } else {
            $win[] = $he;
        }

        // $result[] = 'ts_' . $win_ts;

        foreach ($win as $value) {
            $result[] = 'room1_' . $value;
            $result[] = 'room2_' . $value;
            $result[] = 'room3_' . $value;
            $result[] = 'room4_' . $value;
            $result[] = 'room5_' . $value;
            $result[] = 'room6_' . $value;
            $result[] = 'room7_' . $value;
            $result[] = 'room8_' . $value;
        }
        //
        // $result[] = 'he_' . sprintf('%02d', $he);
        // $result[] = 'fd_' . sprintf('%02d', $he);

        //16玩法
        // $has_ext = ['ca28', 'cw28', 'de28', 'bj28'];
        // if (in_array($lotto_name, $has_ext)) {
        //     $formula = LottoFormula::basic16($open_code);
        //     $result[] = 'st_' . sprintf('%02d', $formula['code_he']);
        //     $formula = LottoFormula::basic11($open_code);
        //     $result[] = 'el_' . sprintf('%02d', $formula['code_he']);
        // }

        return $result;
    }

    public static function racing($code, $lotto_id = null)
    {
        $code   = explode(',', $code);
        $result = [];

        //冠亚和
        $gyh                       = sprintf('%02d', $code[0] + $code[1]);
        $gyh % 2 == 0 && $result[] = 'gyh_dob';
        $gyh % 2 == 1 && $result[] = 'gyh_sig';
        $gyh >= 12 && $result[]    = 'gyh_big';
        $gyh <= 11 && $result[]    = 'gyh_sml';
        $result[]                  = 'gyh_' . $gyh;

        //PK22
        $ptt      = sprintf('%02d', $code[0] + $code[1] + $code[2]);
        $result[] = 'ptt_' . $ptt;

        //pk10
        $position = substr($lotto_id, -1);
        $position = $position != 0 ? $position : 10;
        $ptn_code = $code[$position - 1];
        $result[] = 'ptn_' . $ptn_code;

        //双面盘
        for ($i = 1; $i <= 10; $i++) {
            $win_prefix                 = 'smp_' . sprintf('%02d', $i) . '_';
            $temp                       = $code[$i - 1];
            $temp % 2 == 0 && $result[] = $win_prefix . 'dob';
            $temp % 2 == 1 && $result[] = $win_prefix . 'sig';
            $temp >= 6 && $result[]     = $win_prefix . 'big';
            $temp <= 5 && $result[]     = $win_prefix . 'sml';

            if ($i <= 5) {
                $temp_1   = $code[$i - 1];
                $position = 11 - $i;
                $temp_2   = $code[10 - $i];
                if ($temp_1 > $temp_2) {
                    $result[] = $win_prefix . 'drg';
                } else {
                    $result[] = $win_prefix . 'tig';
                }
            }

            //计算定位胆
            $result[] = 'dwd_' . sprintf('%02d', $i) . '_' . sprintf('%02d', $temp);
        }

        return $result;
    }

    public static function shishicai($code)
    {
        $code   = explode(',', $code);
        $result = [];

        //双面盘算法
        $smpFun = function ($num, $position) {
            $prefix                                      = 'ssm_' . sprintf('%02d', $position);
            $result                                      = [];
            $num % 2 == 0 && $result[]                   = $prefix . '_dob';
            $num % 2 == 1 && $result[]                   = $prefix . '_sig';
            $num >= 5 && $result[]                       = $prefix . '_big';
            $num <= 4 && $result[]                       = $prefix . '_sml';
            in_array($num, [1, 2, 3, 5, 7]) && $result[] = $prefix . '_qua';
            in_array($num, [0, 4, 6, 8, 9]) && $result[] = $prefix . '_clo';
            return $result;
        };

        //龙虎斗
        $lhdFun = function ($position, $num1, $num2) {
            $prefix                   = 'lhd_' . $position;
            $result                   = '';
            $num1 > $num2 && $result  = $prefix . '_drg';
            $num1 < $num2 && $result  = $prefix . '_tig';
            $num1 == $num2 && $result = $prefix . '_pea';
            return $result;
        };

        //和值
        $he = 0;
        foreach ($code as $key => $value) {
            $he += $value;
            $smp    = $smpFun($value, $key + 1);
            $result = array_merge($result, $smp);

            //定位胆
            $result[] = 'sdw_' . sprintf('%02d', $key + 1) . '_' . $value;
        }

        $he % 2 == 0 && $result[] = 'ssm_he_dob';
        $he % 2 == 1 && $result[] = 'ssm_he_sig';
        $he >= 23 && $result[]    = 'ssm_he_big';
        $he <= 22 && $result[]    = 'ssm_he_sml';

        //龙虎斗
        $result[] = $lhdFun('wq', $code[0], $code[1]);
        $result[] = $lhdFun('wb', $code[0], $code[2]);
        $result[] = $lhdFun('ws', $code[0], $code[3]);
        $result[] = $lhdFun('wg', $code[0], $code[4]);
        $result[] = $lhdFun('qb', $code[1], $code[2]);
        $result[] = $lhdFun('qs', $code[1], $code[3]);
        $result[] = $lhdFun('qg', $code[1], $code[4]);
        $result[] = $lhdFun('bs', $code[2], $code[3]);
        $result[] = $lhdFun('bg', $code[2], $code[3]);
        $result[] = $lhdFun('sg', $code[3], $code[4]);

        return $result;
    }
}
