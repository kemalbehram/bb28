<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LottoModule\Models\LottoConfig;

class LottoConfigController extends Controller
{
    public function get(Request $request)
    {
        $lotto       = $request->lotto_name;
        $item        = LottoConfig::remember(600)->find($lotto);
        $item->types = explode(',', $item->play_types);
        $result      = $item->toArray();
        return real($result)->success();
    }

    public function update(Request $request)
    {
        $rule = ['method' => 'required'];
        $data = $request->all();
        real()->validator($data, $rule);

        $method = $request->method;
        return $this->$method($request);
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
