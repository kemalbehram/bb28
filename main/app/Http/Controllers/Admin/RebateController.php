<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserRebateConfig;
use App\Models\UserWallet\Wallet;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\LottoModule\Models\BetLog;
use App\Models\LottoModule\Models\BetRebate;

class RebateController extends Controller
{
    public function allconfirm(Request $request)
    {
        $datas        = $request->data;
        $rebate_count = 0;
        foreach ($datas as $key => $val) {
            $rebate_exist = BetRebate::where('user_id', $val['user_id'])->where('date', '>=', $request->date)->where('date', '<=', $request->date . ' 23:59:59')->first();
            if ($rebate_exist != null) {
                continue;
            } else {
                //给未反水的用户反水
                //计算总反水()
                $profit = $val['total_rebate'];
                //dd($val['rebate_list']);

                // foreach ($val['rebate_list'] as $key => $dval) {
                //     $profit += $dval['single'] + $dval['combo'] + $dval['lhb'] + $dval['other'] + $dval['number'];
                // }

                //$wallet = $user->wallet->balance('lotto.cancel.bet.' . $item->lotto_name, $item->id);

                DB::beginTransaction();
                $model          = new BetRebate();
                $model->user_id = $val['user_id'];
                $model->date    = $request->date;

                $model->profit = $profit;
                $model->desc   = json_encode($val['detail']);
                $model->save();
                $user   = User::find($val['user_id']);
                $wallet = $user->wallet->balance('user.bet.rebate', $model->id);
                $wallet->plus($profit);
                DB::commit();
                $rebate_count++;
            }
        }
        return real()->success($rebate_count . '个用户反水成功');
    }

    public static function array_group_by($arr, $key)
    {
        $grouped = [];
        foreach ($arr as $value) {
            $grouped[$value[$key]][] = $value;
        }
        if (func_num_args() > 2) {
            $args = func_get_args();
            foreach ($grouped as $key => $value) {
                $parms         = array_merge([$value], array_slice($args, 2, func_num_args()));
                $grouped[$key] = call_user_func_array('array_group_by', $parms);
            }
        }
        return $grouped;
    }

    public function checkBetType($bet_details, $play_type)
    {
        $single_count  = 0;
        $single_amount = 0;
        $single_profit = 0;

        $combo_count  = 0;
        $combo_amount = 0;
        $combo_profit = 0;

        $lhb_count  = 0;
        $lhb_amount = 0;
        $lhb_profit = 0;

        $number_count  = 0;
        $number_amount = 0;
        $number_profit = 0;

        $other_count  = 0;
        $other_amount = 0;
        $other_profit = 0;

        $sha_count = 0;
        //$sha_amount = 0;

        // $pt_count  = 0;
        // $pt_amount = 0;
        $dy_count   = 0;
        $many_combo = 0;
        // $dy_amount = 0;
        $number_array = [
            '00', '01', '02', '03', '04', '05', '06', '07', '08', '09',
            '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27',
        ];

        foreach ($bet_details as $key => $val) {
            $place = explode('_', $val['place'])[1];

            if ($place == 'big' || $place == 'sml' || $place == 'sig' || $place == 'dob') {
                $single_count++;
                $single_amount += $val['amount'];
                $single_profit += $val['amount'] - $val['bonus']; //($val['amount'] - $val['bonus']) > 0 && $play_type != 'room1' ? abs($val['amount'] - $val['bonus']) : 0;
            } elseif ($place == 'bdo' || $place == 'bsg' || $place == 'sdo' || $place == 'ssg') {
                $combo_amount += $val['amount'];
                $combo_profit += $val['amount'] - $val['bonus']; //($val['amount'] - $val['bonus']) > 0 && $play_type != 'room1' ? abs($val['amount'] - $val['bonus']) : 0;

                $combo_count++;
            } elseif ($place == 'long' || $place == 'hu' || $place == 'bao') {
                $lhb_count++;
                $lhb_amount += $val['amount'];
                $lhb_profit += $val['amount'] - $val['bonus']; //($val['amount'] - $val['bonus']) > 0 && $play_type != 'room1' ? abs($val['amount'] - $val['bonus']) : 0;
            } elseif (in_array($place, $number_array)) {
                $number_count++;
                $number_amount += $val['amount'];
                $number_profit += $val['amount'] - $val['bonus']; //($val['amount'] - $val['bonus']) > 0 && $play_type != 'room1' ? abs($val['amount'] - $val['bonus']) : 0;
            } else {
                $other_count++;
                $other_amount += $val['amount'];
                $other_profit += $val['amount'] - $val['bonus']; //($val['amount'] - $val['bonus']) > 0 && $play_type != 'room1' ? abs($val['amount'] - $val['bonus']) : 0;
            }
        }
        //杀组合
        if ($single_count == 1 && $combo_count == 2) {
            $sha_count++;
            // $sha_amount = $single_amount + $combo_amount;
        }
        //多组合
        if ($combo_count > 1) {
            $many_combo++;
        }

        // //平推
        // if (count($bet_details) == 1) {
        //     $pt_count++;
        //     $pt_amount = $bet_details[0]['amount'];
        // }
        //对压(只算大小单双)
        if (count($bet_details) == 2) {
            if (strstr($bet_details[0]['place'], 'big') && strstr($bet_details[1]['place'], 'sml')) {
                $dy_count++;
                // $dy_amount = $bet_details[0]['amount'] + $bet_details[1]['amount'];
            }

            if (strstr($bet_details[0]['place'], 'sig') && strstr($bet_details[1]['place'], 'dob')) {
                $dy_count++;
                // $dy_amount = $bet_details[0]['amount'] + $bet_details[1]['amount'];
            }
        }

        $result = [
            'single' => [$single_count, $single_amount, $single_profit],
            'combo'  => [$combo_count, $combo_amount, $combo_profit],
            'lhb'    => [$lhb_count, $lhb_amount, $lhb_profit],
            'number' => [$number_count, $number_amount, $number_profit],
            'other'  => [$other_count, $other_amount, $other_profit],
            'sha'    => $sha_count,
            'dy'     => $dy_count,
            'many'   => $many_combo,
        ];

        return $result;
    }

    public function config()
    {
        $items                  = UserRebateConfig::where('id', 1)->orwhere('id', 2)->get();
        $result['agent_rebate'] = $items[0];
        $result['user_rebate']  = $items[1];
        return real($result)->success();
    }

    public function confirm(Request $request)
    {
        //判断用户当天是否已反水

        $rebate_exist = BetRebate::where('user_id', $request->user_id)->where('date', '>=', $request->date)->where('date', '<=', $request->date . ' 23:59:59')->first();
        $rebate_exist != null && real()->exception('该用户当天已反水');
        //计算总反水()
        $profit = $request->amount;
        // dd($request->all());
        // foreach ($request->detail as $key => $val) {
        //     $profit += $request->detail[$key]['single'] + $request->detail[$key]['combo'] + $request->detail[$key]['lhb'] + $request->detail[$key]['other'] + $request->detail[$key]['number'];
        // }

        //$wallet = $user->wallet->balance('lotto.cancel.bet.' . $item->lotto_name, $item->id);

        DB::beginTransaction();
        $model          = new BetRebate();
        $model->user_id = $request->user_id;
        $model->date    = $request->date;

        $model->profit = $profit;
        $model->desc   = json_encode($request->detail);
        $model->save();
        $user   = User::find($request->user_id);
        $wallet = $user->wallet->balance('user.bet.rebate', $model->id);
        $wallet->plus($profit);
        DB::commit();

        return real()->success('返水成功');
    }

    public function index(Request $request)
    {
        $rebate_config = UserRebateConfig::find(2);
        $open_room     = [];
        for ($i = 1; $i < 9; $i++) {
            if ($rebate_config['room' . $i]['opend'] == 1) {
                $open_room[] = 'room' . $i;
            }
        }

        $date_start = $request->date . ' 00:00:00';
        // $date_start = '2019-01-01 12:00:00';
        $date_end = $request->date . ' 23:59:59';
        //  $date_end = '2021-01-01 12:00:00';

        $items = BetLog::where('confirmed_at', '>=', $date_start)->where('confirmed_at', '<=', $date_end)->where('trial', 0)
            ->whereIn('play_type', $open_room)->with('details', 'user')->get()->groupBy('user_id')->toArray();
        $rebate_arr = [];
        $list       = [];
        foreach ($items as $key => $val) {
            $play_group = $this->array_group_by($val, 'play_type');

            $group_computed = [];
            $total_profit   = 0;
            foreach ($play_group as $kkk => $vvv) {
                $group_computed[$kkk] = [
                    'bet_amount' => array_sum(array_column($vvv, 'total')),
                    'bet_bonus'  => array_sum(array_column($vvv, 'bonus')),
                    'bet_time'   => count(array_column($vvv, 'total')),
                ];

                $list[$kkk]['other']['rate'] = 0;
                $the_config                  = $rebate_config[$kkk];

                if ($the_config['model'] == 1) {
                    $total_profit += $group_computed[$kkk]['bet_amount'];
                    foreach ($the_config['items'] as $k => $v) {
                        // if ($key == '275558' && $k == 1) {
                        //     dump($group_computed[$kkk]['bet_amount'] . '///' . $v['money']);
                        //     dump($group_computed[$kkk]['bet_time'] . '///' . $v['count']);
                        // }
                        if ($group_computed[$kkk]['bet_amount'] >= $v['money'] && $group_computed[$kkk]['bet_time'] >= $v['count'] && $v['is_open'] == true) {
                            $list[$kkk]['other']['rate'] = $v['other'];
                        }
                    }
                }

                if ($the_config['model'] == 2) {
                    if ($group_computed[$kkk]['bet_amount'] > $group_computed[$kkk]['bet_bonus']) {
                        $total_profit += $group_computed[$kkk]['bet_amount'] - $group_computed[$kkk]['bet_bonus'];
                    }
                    foreach ($the_config['items'] as $v) {
                        if ($group_computed[$kkk]['bet_amount'] > $group_computed[$kkk]['bet_bonus']) {
                            if ($group_computed[$kkk]['bet_amount'] - $group_computed[$kkk]['bet_bonus'] >= $v['money'] && $group_computed[$kkk]['bet_time'] >= $v['count'] && $v['is_open'] == true) {
                                $list[$kkk]['other']['rate'] = $v['other'];
                            }
                        }
                    }
                }
            }

            //投注玩法总数
            $total_count  = 0;
            $total_amount = 0;

            //按期数分组
            $short_ids = $this->array_group_by($val, 'short_id');

            $room_count = ['room1' => 0, 'room3' => 0, 'room4' => 0, 'room5' => 0, 'room6' => 0];

            foreach ($short_ids as $skey => $sval) {
                if (count($sval) > 0) {
                    if ($sval[0]['play_type'] == 'room1') {
                        $room_count['room1']++;
                    } elseif ($sval[0]['play_type'] == 'room3') {
                        $room_count['room3']++;
                    } elseif ($sval[0]['play_type'] == 'room5') {
                        $room_count['room5']++;
                    } elseif ($sval[0]['play_type'] == 'room4') {
                        $room_count['room4']++;
                    } else {
                        $room_count['room6']++;
                    }
                }
            }

            foreach ($open_room as $okey => $oval) {
                $list[$oval]['single']['count']  = 0;
                $list[$oval]['single']['amount'] = 0;
                // $list[$oval]['single']['rate']         = $rebate_config[$oval]['other'];
                $list[$oval]['single']['profit']       = 0;
                $list[$oval]['single']['rebate_price'] = 0;
                $list[$oval]['combo']['count']         = 0;
                $list[$oval]['combo']['amount']        = 0;
                // $list[$oval]['combo']['rate']          = $rebate_config[$oval]['other'];
                $list[$oval]['combo']['profit']       = 0;
                $list[$oval]['combo']['rebate_price'] = 0;
                $list[$oval]['lhb']['count']          = 0;
                $list[$oval]['lhb']['amount']         = 0;
                // $list[$oval]['lhb']['rate']            = $rebate_config[$oval]['other'];
                $list[$oval]['lhb']['profit']       = 0;
                $list[$oval]['lhb']['rebate_price'] = 0;

                $list[$oval]['number']['count']  = 0;
                $list[$oval]['number']['amount'] = 0;
                // $list[$oval]['number']['rate']         = $rebate_config[$oval]['other'];
                $list[$oval]['number']['profit']       = 0;
                $list[$oval]['number']['rebate_price'] = 0;

                $list[$oval]['other']['count']  = 0;
                $list[$oval]['other']['amount'] = 0;
                // $list[$oval]['other']['rate']         = $rebate_config[$oval]['other'];
                $list[$oval]['other']['profit']       = 0;
                $list[$oval]['other']['rebate_price'] = 0;
                $list[$oval]['sha']                   = 0;
                $list[$oval]['dy']                    = 0;
                $list[$oval]['many']                  = 0;
            }

            foreach ($val as $dkey => $dval) {
                $total_amount += $dval['total'];

                $play_type        = $dval['play_type'];
                $play_type_detail = $this->checkBetType($dval['details'], $rebate_config[$play_type]);

                $list[$play_type]['other']['count'] += $play_type_detail['other'][0];
                $list[$play_type]['single']['count'] += $play_type_detail['single'][0];
                $list[$play_type]['combo']['count'] += $play_type_detail['combo'][0];
                $list[$play_type]['lhb']['count'] += $play_type_detail['lhb'][0];
                $list[$play_type]['number']['count'] += $play_type_detail['number'][0];

                $list[$play_type]['other']['amount'] += $play_type_detail['other'][1];
                $list[$play_type]['single']['amount'] += $play_type_detail['single'][1];
                $list[$play_type]['combo']['amount'] += $play_type_detail['combo'][1];
                $list[$play_type]['number']['amount'] += $play_type_detail['number'][1];
                $list[$play_type]['lhb']['amount'] += $play_type_detail['lhb'][1];

                $list[$play_type]['other']['profit'] += $play_type_detail['other'][2];
                $list[$play_type]['single']['profit'] += $play_type_detail['single'][2];
                $list[$play_type]['combo']['profit'] += $play_type_detail['combo'][2];
                $list[$play_type]['lhb']['profit'] += $play_type_detail['lhb'][2];
                $list[$play_type]['number']['profit'] += $play_type_detail['number'][2];
                $list[$play_type]['sha'] += $play_type_detail['sha'];
                $list[$play_type]['dy'] += $play_type_detail['dy'];
                $list[$play_type]['many'] += $play_type_detail['many'];

                // $total_profit += $play_type_detail['other'][2] + $play_type_detail['single'][2] + $play_type_detail['combo'][2] + $play_type_detail['lhb'][2] + $play_type_detail['number'][2];
            }
            $rebate_list = [
                'room1' => [
                    'single' => 0,
                    'combo'  => 0,
                    'lhb'    => 0,
                    'number' => 0,
                    'other'  => 0,
                    'profit' => 0,
                    'amount' => 0,
                    'count'  => 0,
                    'log'    => '',

                ],

                'room3' => [
                    'single' => 0,
                    'combo'  => 0,
                    'lhb'    => 0,
                    'number' => 0,
                    'other'  => 0,
                    'log'    => '',
                    'profit' => 0,
                    'amount' => 0,
                    'count'  => 0,
                ],
                'room4' => [
                    'single' => 0,
                    'combo'  => 0,
                    'lhb'    => 0,
                    'number' => 0,
                    'other'  => 0,
                    'log'    => '',
                    'profit' => 0,
                    'amount' => 0,
                    'count'  => 0,
                ],

                'room5' => [
                    'single' => 0,
                    'combo'  => 0,
                    'lhb'    => 0,
                    'number' => 0,
                    'other'  => 0,
                    'log'    => '',
                    'profit' => 0,
                    'amount' => 0,
                    'count'  => 0,
                ],
                'room6' => [
                    'single' => 0,
                    'combo'  => 0,
                    'lhb'    => 0,
                    'number' => 0,
                    'other'  => 0,
                    'log'    => '',
                    'profit' => 0,
                    'amount' => 0,
                    'count'  => 0,
                ],

            ];
            $total_rebate = 0;
            $kkkk         = $key;

            foreach ($list as $key => $uval) {
                $room_total  = 0;
                $room_profit = 0;
                $rate        = isset($list[$key]['other']['rate']) ? $list[$key]['other']['rate'] : 0;
                // dd($rebate_config);
                $model = $rebate_config[$key]['model'];
                $limit = $rebate_config[$key]['limit'];
                $room_total  = isset($group_computed[$key]) ? $group_computed[$key]['bet_amount'] : 0;
                $room_profit = isset($group_computed[$key]) ? $group_computed[$key]['bet_bonus'] : 0;

                // $room_total  = //$list[$key]['single']['amount'] + $list[$key]['combo']['amount'] + $list[$key]['lhb']['amount'] + $list[$key]['number']['amount'] + $list[$key]['other']['amount'];
                //$room_profit = //$list[$key]['single']['profit'] + $list[$key]['combo']['profit'] + $list[$key]['lhb']['profit'] + $list[$key]['number']['profit'] + $list[$key]['other']['profit'];
                // if ($list[$key]['single']['count'] != 0) {
                //     $rebate_list[$key]['single'] = floatval(sprintf('%.4f', ($model == 1 ? $list[$key]['single']['amount'] : $list[$key]['single']['profit']) * $rate / 100));
                // }
                // if ($list[$key]['combo']['count'] != 0) {
                //     $rebate_list[$key]['combo'] = floatval(sprintf('%.4f', ($model == 1 ? $list[$key]['combo']['amount'] : $list[$key]['combo']['profit']) * $rate / 100));
                // }
                // if ($list[$key]['lhb']['count'] != 0) {
                //     $rebate_list[$key]['lhb'] = floatval(sprintf('%.4f', ($model == 1 ? $list[$key]['lhb']['amount'] : $list[$key]['lhb']['profit']) * $rate / 100));
                // }
                // if ($list[$key]['number']['count'] != 0) {
                //     $rebate_list[$key]['number'] = floatval(sprintf('%.4f', ($model == 1 ? $list[$key]['number']['amount'] : $list[$key]['number']['profit']) * $rate / 100));
                // }
                // if ($list[$key]['other']['count'] != 0) {
                //     $rebate_list[$key]['other'] = floatval(sprintf('%.4f', ($model == 1 ? $list[$key]['other']['amount'] : $list[$key]['other']['profit']) * $rate / 100));
                // }
                // $rebate_list[$key]['count']  = $room_count[$key];
                // $rebate_list[$key]['amount'] = $room_total;

                // $amount                      = $model == 1 ? $room_total : ($room_total - $room_profit);
                if ($model == 1) {
                    if ($limit == 1) {
                        $amount  = $room_total;
                    } else {
                        $amount = $list[$key]['combo']['amount'];
                    }
                } else {
                    if ($limit == 1) {
                        $amount = $room_total - $room_profit;
                    } else {
                        $amount = $list[$key]['combo']['profit'];
                    }
                }
                $room_profit                 = floatval(sprintf('%.4f', $amount * $rate / 100));
                $rebate_list[$key]['profit'] = $room_profit;
                // if ($model == 1) {
                //     $amount = $room_total;
                // } else {
                //     if ($room_total > $room_profit) {
                //         $amount = $room_total - $room_profit;
                //     } else {
                //         $amount = 0;
                //     }
                // }

                $total_rebate += $room_profit;
                // if ($kkkk == 275558) {
                //     // dump($model . '/////' . $room_total . '/////' . -$room_profit);
                //     dump($amount);
                //     dump($rate);
                //     // dump($total_rebate);
                // }
            }
            // die;
            if ($total_rebate <= 0) {
                continue;
            }
            $total_count = $room_count['room1'] + $room_count['room3'] + $room_count['room5'] + $room_count['room4'] + $room_count['room6'];

            $rebate_arr[] = ['user_id' => $val[0]['user_id'], 'user_avatar' => $val[0]['user']['avatar'], 'user_name' => $val[0]['user']['nickname'], 'detail' => $list, 'total_count' => $total_count, 'total_amount' => $total_amount, 'rebate_list' => $rebate_list, 'total_rebate' => floatval(sprintf('%.4f', $total_rebate)), 'total_profit' => floatval(sprintf('%.4f', $total_profit))];
        }
        // die;
        return real($rebate_arr)->success();
    }

    public function rebatelist(Request $request)
    {
        $data = BetRebate::with('user');

        $request->date && $data->where('date', $request->date);
        // $request->id && $data->where('id', $request->id);
        // $request->status && $data->where('status', $request->status);
        // $request->title && $data->where('title', 'regexp', $request->title);
        $data->orderBy('id', 'desc');

        $result = $data->paginate(20)->toArray();

        return real()->listPage($result)->success();
    }

    public function updateConfig(Request $request)
    {
        UserRebateConfig::where('id', $request->id)->update([
            'room1' => $request['data']['room1'],
            'room2' => $request['data']['room2'],
            'room3' => $request['data']['room3'],
            'room4' => $request['data']['room4'],
            'room5' => $request['data']['room5'],
            'room6' => $request['data']['room6'],
            'room7' => $request['data']['room7'],
            'room8' => $request['data']['room8'],

        ]);
        return real([])->success('update.success');
    }
}
