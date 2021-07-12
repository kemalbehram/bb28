<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ModelTrait\ThisUserTrait;
use App\Models\LottoModule\Models\BetTools;

class BetToolsController extends Controller
{
    use ThisUserTrait;

    public function action(Request $request)
    {
        $rule = [
            'lotto_name' => 'required',
            'title'      => 'required|max:4',
            'total'      => 'required',
            'play_type'  => 'required',
            'checked'    => 'required',
            'chips_rate' => 'required',
            'total'      => 'required',
        ];

        $data = $request->all();
        real()->validator($data, $rule);

        $count = BetTools::where('user_id', $this->user->id)
            ->where('lotto_name', $request->lotto_name)
            ->where('play_type', $request->play_type)
            ->count();

        $count >= 100 && real()->exception('每个游戏最多创建100个模式，请尝试更新其它模式');

        $place = [];
        foreach ($request->checked as $key => $value) {
            if (!$value) {continue;}
            $place[$key] = $value / $request->chips_rate;
        }

        $params = [
            'id'         => $request->id,
            'title'      => $request->title,
            'user_id'    => $this->user->id,
            'lotto_name' => $request->lotto_name,
            'play_type'  => $request->play_type,
            'bet_places' => $place,
            'total'      => $request->total,
        ];

        if ($request->id === 0) {
            $message = $this->create($params);
        } else {
            $message = $this->update($params);
        }

        $items = BetTools::where('user_id', $this->user->id)
            ->where('lotto_name', $request->lotto_name)
            ->get();

        $result = ['user_tools' => $items->toArray()];
        return real($result)->success($message);
    }

    public function delete(Request $request)
    {
        $rule = ['id' => 'required|int'];
        $data = $request->all();
        real()->validator($data, $rule);

        $tool = BetTools::find($request->id);
        $tool || real()->exception('该模式不存在 请刷新网页后重试');

        $related = BetTools::where('win_tool', $request->id)->orWhere('lose_tool', $request->id)->count();
        $related && real()->exception('该模式已有其它模式关联，请前往自动投注修改关联后再删除');

        $tool->delete();
        $tool->trashed() || real()->exception('模式删除失败，请重试');

        $items = BetTools::where('user_id', $this->user->id)
            ->where('lotto_name', $request->lotto_name)
            ->get();

        $result = ['items' => $items->toArray()];
        return real($result)->success('模式删除成功');
    }

    private function create($params)
    {
        $item = BetTools::create($params);

        $item->lose_tool = $item->id;
        $item->win_tool  = $item->id;
        $item->save();

        return '创建成功';
    }

    private function update($params)
    {
        $item = BetTools::find($params['id']);
        $item->user_id != $this->user->id && real()->exception('数据错误 请重试');
        $item->title      = $params['title'];
        $item->bet_places = $params['bet_places'];
        $item->total      = $params['total'];
        $item->save();

        return '模式更新成功';
    }
}
