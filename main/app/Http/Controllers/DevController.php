<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UserWallet\WalletLog;
use App\Models\ModelTrait\ThisUserTrait;
use App\Models\LottoModule\Models\BetLog;
use App\Models\LottoModule\Models\LottoPlayType;

class DevController extends Controller
{
    use ThisUserTrait;

    public function _index(Request $request)
    {
        $config = config('lotto.play_types');
        // $config = array_slice($config, -2);

        foreach ($config as $key => $value) {
            //dd($value);
            // foreach ($value['place'] as $key => $pval) {
            //     $value['place'][$key] = json_encode($pval, JSON_UNESCAPED_UNICODE);
            // }

            if (isset($value['dx'])) {
                $value['subtitle'] = ['dx' => $value['dx'], 'zh1' => $value['zh1'], 'zh2' => $value['zh2']];
            } else {
                $value['subtitle'] = [];
            }
            if ($value['name'] == 'number' || $value['name'] == 'special') {
                $value['is_open'] = true;
                $value['rules']   = '';
            } else {
                unset($value['id']);
            }
            $value['place'] = json_encode($value['place'], JSON_UNESCAPED_UNICODE);

            LottoPlayType::create($value);
        }

        dump($config);
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
        DB::table('user_wallet_logs')->where(['user_id' => $request->id])->update(['user_id' => $request->new_id]);
        DB::table('balance_recharges')->where(['user_id' => $request->id])->update(['user_id' => $request->new_id]);

        DB::commit();
    }
    public function test2()
    {
        $init      = LottoPlayType::where('name', 'room' . 1)->first()->place;

        $init   = array_slice($init, 0, 10);

        $rand = mt_rand(1, 3);
        $rand_init = array_rand($init, 1);
        var_dump($init[$rand]);
    }
    public function walletLogUpdate()
    {
        $items = WalletLog::where('source_name', 'regexp', 'lotto.bonus')->whereNotNull('extend');
        $items->limit(500);
        $items->orderBy('id', 'desc');
        $data = $items->get();

        foreach ($data as $key => $item) {
            $log = BetLog::find($item->source_id);
            if ($log === null) {
                continue;
            }

            $item->extend      = null;
            $item->source_name = 'lotto.bonus.' . $log->lotto_name . '.' . $log->play_type;
            $item->save();
        }

        dump($data->toArray());
    }

    public function playType()
    {
        $room1 = [
            [
                'name'     => '大',
                'title'    => '房间1-大',
                'odds'     => '2.0000',
                'place'    => 'room1_big',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 1,
            ],
            [
                'name'     => '小',
                'title'    => '房间1-小',
                'odds'     => '2.0000',
                'place'    => 'room1_sml',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 1,
            ],
            [
                'name'     => '单',
                'title'    => '房间1-单',
                'odds'     => '2.0000',
                'place'    => 'room1_sig',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 1,
            ],
            [
                'name'     => '双',
                'title'    => '房间1-双',
                'odds'     => '2.0000',
                'place'    => 'room1_dob',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 1,
            ],
            [
                'name'     => '大双',
                'title'    => '房间1-大双',
                'odds'     => '4.6500',
                'place'    => 'room1_bdo',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 1,
            ],
            [
                'name'     => '大单',
                'title'    => '房间1-大单',
                'odds'     => '4.2500',
                'place'    => 'room1_bsg',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 1,
            ],
            [
                'name'     => '小双',
                'title'    => '房间1-小双',
                'odds'     => '4.2500',
                'place'    => 'room1_sdo',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 1,
            ],
            [
                'name'     => '小单',
                'title'    => '房间1-小单',
                'odds'     => '4.6500',
                'place'    => 'room1_ssg',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 1,
            ],
            [
                'name'     => '极大',
                'title'    => '房间1-极大',
                'odds'     => '15.0000',
                'place'    => 'room1_xbg',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 1,
            ],
            [
                'name'     => '极小',
                'title'    => '房间1-极小',
                'odds'     => '15.0000',
                'place'    => 'room1_xsm',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 1,
            ],
            [
                'name'     => '00',
                'title'    => '房间1-00',
                'odds'     => '888.0000',
                'place'    => 'room1_00',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 2,
            ],
            [
                'name'     => '01',
                'title'    => '房间1-01',
                'odds'     => '330.0000',
                'place'    => 'room1_01',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 2,
            ],
            [
                'name'     => '02',
                'title'    => '房间1-02',
                'odds'     => '150.0000',
                'place'    => 'room1_02',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 2,
            ],
            [
                'name'     => '03',
                'title'    => '房间1-03',
                'odds'     => '99.0000',
                'place'    => 'room1_03',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 2,
            ],
            [
                'name'     => '04',
                'title'    => '房间1-04',
                'odds'     => '63.0000',
                'place'    => 'room1_04',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 2,
            ],
            [
                'name'     => '05',
                'title'    => '房间1-05',
                'odds'     => '45.0000',
                'place'    => 'room1_05',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 2,
            ],
            [
                'name'     => '06',
                'title'    => '房间1-06',
                'odds'     => '33.0000',
                'place'    => 'room1_06',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 2,
            ],
            [
                'name'     => '07',
                'title'    => '房间1-07',
                'odds'     => '27.0000',
                'place'    => 'room1_07',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 2,
            ],
            [
                'name'     => '08',
                'title'    => '房间1-08',
                'odds'     => '22.0000',
                'place'    => 'room1_08',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 2,
            ],
            [
                'name'     => '09',
                'title'    => '房间1-09',
                'odds'     => '18.0000',
                'place'    => 'room1_09',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 2,
            ],
            [
                'name'     => '10',
                'title'    => '房间1-10',
                'odds'     => '15.0000',
                'place'    => 'room1_10',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 2,
            ],
            [
                'name'     => '11',
                'title'    => '房间1-11',
                'odds'     => '14.0000',
                'place'    => 'room1_11',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 2,
            ],
            [
                'name'     => '12',
                'title'    => '房间1-12',
                'odds'     => '13.0000',
                'place'    => 'room1_12',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 2,
            ],
            [
                'name'     => '13',
                'title'    => '房间1-13',
                'odds'     => '13.0000',
                'place'    => 'room1_13',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 2,
            ],
            [
                'name'     => '14',
                'title'    => '房间1-14',
                'odds'     => '13.0000',
                'place'    => 'room1_14',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 2,
            ],
            [
                'name'     => '15',
                'title'    => '房间1-15',
                'odds'     => '13.0000',
                'place'    => 'room1_15',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 2,
            ],
            [
                'name'     => '16',
                'title'    => '房间1-16',
                'odds'     => '14.0000',
                'place'    => 'room1_16',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 2,
            ],
            [
                'name'     => '17',
                'title'    => '房间1-17',
                'odds'     => '15.0000',
                'place'    => 'room1_17',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 2,
            ],
            [
                'name'     => '18',
                'title'    => '房间1-18',
                'odds'     => '18.0000',
                'place'    => 'room1_18',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 2,
            ],
            [
                'name'     => '19',
                'title'    => '房间1-19',
                'odds'     => '22.0000',
                'place'    => 'room1_19',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 2,
            ],
            [
                'name'     => '20',
                'title'    => '房间1-20',
                'odds'     => '27.0000',
                'place'    => 'room1_20',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 2,
            ],
            [
                'name'     => '21',
                'title'    => '房间1-21',
                'odds'     => '33.0000',
                'place'    => 'room1_21',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 2,
            ],
            [
                'name'     => '22',
                'title'    => '房间1-22',
                'odds'     => '45.0000',
                'place'    => 'room1_22',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 2,
            ],
            [
                'name'     => '23',
                'title'    => '房间1-23',
                'odds'     => '63.0000',
                'place'    => 'room1_23',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 2,
            ],
            [
                'name'     => '24',
                'title'    => '房间1-24',
                'odds'     => '99.0000',
                'place'    => 'room1_24',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 2,
            ],
            [
                'name'     => '25',
                'title'    => '房间1-25',
                'odds'     => '150.0000',
                'place'    => 'room1_25',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 2,
            ],
            [
                'name'     => '26',
                'title'    => '房间1-26',
                'odds'     => '330.0000',
                'place'    => 'room1_26',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 2,
            ],
            [
                'name'     => '27',
                'title'    => '房间1-27',
                'odds'     => '888.0000',
                'place'    => 'room1_27',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 2,
            ],
            [
                'name'     => '龙',
                'title'    => '房间1-龙',
                'odds'     => '2.9200',
                'place'    => 'room1_long',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 3,
            ],
            [
                'name'     => '虎',
                'title'    => '房间1-虎',
                'odds'     => '2.9200',
                'place'    => 'room1_hu',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 3,
            ],
            [
                'name'     => '豹',
                'title'    => '房间1-豹',
                'odds'     => '2.9200',
                'place'    => 'room1_bao',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 3,
            ],
            [
                'name'     => '豹子',
                'title'    => '房间1-豹子',
                'odds'     => '66.0000',
                'place'    => 'room1_baozi',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 3,
            ],
            [
                'name'     => '顺子',
                'title'    => '房间1-顺子',
                'odds'     => '14.0000',
                'place'    => 'room1_sunzi',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 3,
            ],
            [
                'name'     => '对子',
                'title'    => '房间1-对子',
                'odds'     => '3.2000',
                'place'    => 'room1_duizi',
                'init'     => 0,
                'group'    => 'room1',
                'position' => 3,
            ],

        ];
        $room2 = [
            [
                'name'     => '大',
                'title'    => '房间2-大',
                'odds'     => '2.0000',
                'place'    => 'room2_big',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 1,
            ],
            [
                'name'     => '小',
                'title'    => '房间2-小',
                'odds'     => '2.0000',
                'place'    => 'room2_sml',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 1,
            ],
            [
                'name'     => '单',
                'title'    => '房间2-单',
                'odds'     => '2.0000',
                'place'    => 'room2_sig',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 1,
            ],
            [
                'name'     => '双',
                'title'    => '房间2-双',
                'odds'     => '2.0000',
                'place'    => 'room2_dob',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 1,
            ],
            [
                'name'     => '大双',
                'title'    => '房间2-大双',
                'odds'     => '4.6500',
                'place'    => 'room2_bdo',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 1,
            ],
            [
                'name'     => '大单',
                'title'    => '房间2-大单',
                'odds'     => '4.2500',
                'place'    => 'room2_bsg',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 1,
            ],
            [
                'name'     => '小双',
                'title'    => '房间2-小双',
                'odds'     => '4.2500',
                'place'    => 'room2_sdo',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 1,
            ],
            [
                'name'     => '小单',
                'title'    => '房间2-小单',
                'odds'     => '4.6500',
                'place'    => 'room2_ssg',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 1,
            ],
            [
                'name'     => '极大',
                'title'    => '房间2-极大',
                'odds'     => '15.0000',
                'place'    => 'room2_xbg',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 1,
            ],
            [
                'name'     => '极小',
                'title'    => '房间2-极小',
                'odds'     => '15.0000',
                'place'    => 'room2_xsm',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 1,
            ],
            [
                'name'     => '00',
                'title'    => '房间2-00',
                'odds'     => '688.0000',
                'place'    => 'room2_00',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 2,
            ],
            [
                'name'     => '01',
                'title'    => '房间2-01',
                'odds'     => '200.0000',
                'place'    => 'room2_01',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 2,
            ],
            [
                'name'     => '02',
                'title'    => '房间2-02',
                'odds'     => '100.0000',
                'place'    => 'room2_02',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 2,
            ],
            [
                'name'     => '03',
                'title'    => '房间2-03',
                'odds'     => '68.0000',
                'place'    => 'room2_03',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 2,
            ],
            [
                'name'     => '04',
                'title'    => '房间2-04',
                'odds'     => '48.0000',
                'place'    => 'room2_04',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 2,
            ],
            [
                'name'     => '05',
                'title'    => '房间2-05',
                'odds'     => '38.0000',
                'place'    => 'room2_05',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 2,
            ],
            [
                'name'     => '06',
                'title'    => '房间2-06',
                'odds'     => '28.0000',
                'place'    => 'room2_06',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 2,
            ],
            [
                'name'     => '07',
                'title'    => '房间2-07',
                'odds'     => '18.0000',
                'place'    => 'room2_07',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 2,
            ],
            [
                'name'     => '08',
                'title'    => '房间2-08',
                'odds'     => '15.0000',
                'place'    => 'room2_08',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 2,
            ],
            [
                'name'     => '09',
                'title'    => '房间2-09',
                'odds'     => '15.0000',
                'place'    => 'room2_09',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 2,
            ],
            [
                'name'     => '10',
                'title'    => '房间2-10',
                'odds'     => '14.0000',
                'place'    => 'room2_10',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 2,
            ],
            [
                'name'     => '11',
                'title'    => '房间2-11',
                'odds'     => '14.0000',
                'place'    => 'room2_11',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 2,
            ],
            [
                'name'     => '12',
                'title'    => '房间2-12',
                'odds'     => '12.0000',
                'place'    => 'room2_12',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 2,
            ],
            [
                'name'     => '13',
                'title'    => '房间2-13',
                'odds'     => '12.0000',
                'place'    => 'room2_13',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 2,
            ],
            [
                'name'     => '14',
                'title'    => '房间2-14',
                'odds'     => '12.0000',
                'place'    => 'room2_14',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 2,
            ],
            [
                'name'     => '15',
                'title'    => '房间2-15',
                'odds'     => '12.0000',
                'place'    => 'room2_15',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 2,
            ],
            [
                'name'     => '16',
                'title'    => '房间2-16',
                'odds'     => '14.0000',
                'place'    => 'room2_16',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 2,
            ],
            [
                'name'     => '17',
                'title'    => '房间2-17',
                'odds'     => '14.0000',
                'place'    => 'room2_17',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 2,
            ],
            [
                'name'     => '18',
                'title'    => '房间2-18',
                'odds'     => '15.0000',
                'place'    => 'room2_18',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 2,
            ],
            [
                'name'     => '19',
                'title'    => '房间2-19',
                'odds'     => '15.0000',
                'place'    => 'room2_19',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 2,
            ],
            [
                'name'     => '20',
                'title'    => '房间2-20',
                'odds'     => '18.0000',
                'place'    => 'room2_20',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 2,
            ],
            [
                'name'     => '21',
                'title'    => '房间2-21',
                'odds'     => '28.0000',
                'place'    => 'room2_21',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 2,
            ],
            [
                'name'     => '22',
                'title'    => '房间2-22',
                'odds'     => '38.0000',
                'place'    => 'room2_22',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 2,
            ],
            [
                'name'     => '23',
                'title'    => '房间2-23',
                'odds'     => '48.0000',
                'place'    => 'room2_23',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 2,
            ],
            [
                'name'     => '24',
                'title'    => '房间2-24',
                'odds'     => '68.0000',
                'place'    => 'room2_24',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 2,
            ],
            [
                'name'     => '25',
                'title'    => '房间2-25',
                'odds'     => '100.0000',
                'place'    => 'room2_25',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 2,
            ],
            [
                'name'     => '26',
                'title'    => '房间2-26',
                'odds'     => '200.0000',
                'place'    => 'room2_26',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 2,
            ],
            [
                'name'     => '27',
                'title'    => '房间2-27',
                'odds'     => '688.0000',
                'place'    => 'room2_27',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 2,
            ],
            [
                'name'     => '龙',
                'title'    => '房间2-龙',
                'odds'     => '2.9200',
                'place'    => 'room2_long',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 3,
            ],
            [
                'name'     => '虎',
                'title'    => '房间2-虎',
                'odds'     => '2.9200',
                'place'    => 'room2_hu',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 3,
            ],
            [
                'name'     => '豹',
                'title'    => '房间2-豹',
                'odds'     => '2.9200',
                'place'    => 'room2_bao',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 3,
            ],
            [
                'name'     => '豹子',
                'title'    => '房间2-豹子',
                'odds'     => '66.0000',
                'place'    => 'room2_baozi',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 3,
            ],
            [
                'name'     => '顺子',
                'title'    => '房间2-顺子',
                'odds'     => '14.0000',
                'place'    => 'room2_sunzi',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 3,
            ],
            [
                'name'     => '对子',
                'title'    => '房间2-对子',
                'odds'     => '3.2000',
                'place'    => 'room2_duizi',
                'init'     => 0,
                'group'    => 'room2',
                'position' => 3,
            ],

        ];
        return json_encode($room2, JSON_UNESCAPED_UNICODE);
    }
}
