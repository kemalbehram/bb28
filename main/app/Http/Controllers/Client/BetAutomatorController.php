<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ModelTrait\ThisUserTrait;
use App\Models\LottoModule\Models\BetTools;
use App\Models\LottoModule\Models\BetAutomator;

class BetAutomatorController extends Controller
{
    use ThisUserTrait;

    public function create(Request $request)
    {
        $rule = [
            'lotto_name'    => 'required',
            'title'         => 'required',
            'play_type'     => 'required',
            'lotto_id'      => 'required',
            'bet_direction' => 'required',
            'bet_stop'      => 'required|int',
            'start_tool'    => 'required|int',
            'status'        => 'required',
            'balance_min'   => 'required',
            'balance_max'   => 'required',
        ];

        $data = $request->all();
        real()->validator($data, $rule);

        if ($request->balance_min > $request->balance_max) {
            real()->exception('资金保护下线不能大于上限');
        }

        if ($request->bet_direction === 'assign' && !$request->assign_rule) {
            real()->exception('请选择对号模式的投注策略');
        }

        DB::beginTransaction();

        $item = BetAutomator::where('user_id', $this->user->id)
            ->where('lotto_name', $request->lotto_name)
            ->where('play_type', $request->play_type)
            ->first();

        if ($item === null) {
            $params = [
                'title'         => $request->title,
                'user_id'       => $this->user->id,
                'lotto_name'    => $request->lotto_name,
                'play_type'     => $request->play_type,
                'lotto_id'      => $request->lotto_id,
                'bet_direction' => $request->bet_direction,
                'bet_stop'      => $request->bet_stop,
                'start_tool'    => $request->start_tool,
                'balance_min'   => $request->balance_min,
                'balance_max'   => $request->balance_max,
                'status'        => $request->status,
                'assign_rule'   => $request->assign_rule,
            ];
            $item = BetAutomator::create($params);
        } else {
            $item->lotto_id      = $request->lotto_id;
            $item->bet_direction = $request->bet_direction;
            $item->bet_stop      = $request->bet_stop;
            $item->start_tool    = $request->start_tool;
            $item->balance_min   = $request->balance_min;
            $item->balance_max   = $request->balance_max;
            $item->status        = $request->status;
            $item->assign_rule   = $request->assign_rule;

            $item->status == 1 && $item->stop_reason = '';
            $item->save();
        }

        foreach ($request->bet_tools as $bet_tool) {
            $this->updateBetTool($bet_tool);
        }

        DB::commit();

        $user_tools = BetTools::where('user_id', $this->user->id)
            ->where('lotto_name', $request->lotto_name)
            ->get();

        $item->user_tools = $user_tools;

        $result = $item->toArray();
        return real($result)->success('自动投注 保存成功');
    }

    public function index(Request $request)
    {
        $items  = BetAutomator::where('user_id', $this->user->id)->get();
        $result = ['items' => $items->toArray()];
        return real($result)->success();
    }

    private function updateBetTool($data)
    {
        $item = BetTools::find($data['id']);
        $item || real()->exception('数据类型错误 请刷新重试');

        $item->win_tool  = isset($data['win_tool']) ? $data['win_tool'] : $item->id;
        $item->lose_tool = isset($data['lose_tool']) ? $data['lose_tool'] : $item->id;

        $item->save() || real()->exception('数据类型错误 请刷新重试');
    }
}
