<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LottoModule\Models\LottoConfig;
use App\Models\LottoModule\Models\LottoPlayType;

class LottoRoomController extends Controller
{
    public function get(Request $request)
    {
        $room = $request->room_name;

        $item = LottoPlayType::where('name', $room)->first();

        $item->subtitle = count($item->subtitle) > 0 ? $item['subtitle']['dx'] . '/' . $item['subtitle']['zh1'] . '/' . $item['subtitle']['zh2'] : '';

        $result = $item->toArray();

        return real($result)->success();
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $item = LottoPlayType::find($request->id);
        // if ($data['name'] != 'number' && $data['name'] != 'special') {
        $rule = [
            'title'            => 'required',
            'subtitle'         => 'required',
            'is_open'          => 'required',
            'rules'            => 'required',
            'place'            => 'required',
            'single_limit'     => 'required',
            'single_condition' => 'required',
            'comb_limit'       => 'required',
            'comb_condition'   => 'required',
        ];
        real()->validator($data, $rule);

        $subtitle_array = explode('/', $data['subtitle']);

        if (count($subtitle_array) < 3) {
            return real()->exception('副标题格式错误');
        }

        $subtitle = [
            'dx'  => $subtitle_array[0],
            'zh1' => $subtitle_array[1],
            'zh2' => $subtitle_array[2],
        ];

        $item->title            = $data['title'];
        $item->subtitle         = $subtitle;
        $item->is_open          = $data['is_open'];
        $item->rules            = $data['rules'];
        $item->place            = $data['place'];
        $item->bet_limit        = $data['bet_limit'];
        $item->agent_rebate     = $data['agent_rebate'];
        $item->single_limit     = $data['single_limit'];
        $item->single_condition = $data['single_condition'];
        $item->comb_limit       = $data['comb_limit'];
        $item->comb_condition   = $data['comb_condition'];
        $item->recharge_limit = $data['recharge_limit'];
        // } else {
        //     $rule = ['place' => 'required'];
        //     real()->validator($data, $rule);
        //     $item->place = $data['place'];
        // }
        $item->place = $data['place'];
        $save        = $item->save();
        $save || real()->exception('data.update.failed');
        $item->subtitle = count($item->subtitle) > 0 ? $item['subtitle']['dx'] . '/' . $item['subtitle']['zh1'] . '/' . $item['subtitle']['zh2'] : '';

        $result = $item->toArray();
        return real($result)->success('data.update.success');
    }

    private function betSettingUpdate(Request $request)
    {
        $item = LottoConfig::find($request->lotto_name);
        $item || real()->exception('data.notexist');

        $enum = $request->place;

        $data = [
            'place' => $request->place,
            'ratio' => $request->ratio,
            'odds'  => $request->odds,
        ];

        $result = $item->betSettingUpdate($enum, $data);

        return real($result)->success();
    }

    private function combinationUpdate(Request $request)
    {
        $item = LottoConfig::find($request->lotto_name);
        $item || real()->exception('data.notexist');

        $item->bet_quota   = $request->bet_quota;
        $item->stop_ahead  = $request->stop_ahead;
        $item->disable     = $request->disable;
        $item->lotto_rule  = $request->lotto_rule;
        $item->web_site    = $request->web_site;
        $item->visible     = $request->visible;
        $item->hot         = $request->hot;
        $item->type_option = $request->type_option;
        $save              = $item->save();
        $save || real()->exception('data.update.failed');
        $result = $item->toArray();
        return real($result)->success('data.update.success');
    }
}
