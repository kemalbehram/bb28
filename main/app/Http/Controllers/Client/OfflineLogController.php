<?php

namespace App\Http\Controllers\Client;

use App\Models\UserAward;
use Illuminate\Http\Request;
use App\Models\UserReference;
use function App\Models\option;
use App\Http\Controllers\Controller;
use App\Models\ModelTrait\ThisUserTrait;
use App\Models\LottoModule\Models\BetLog;
use App\Models\LottoModule\Models\BetRebate;

class OfflineLogController extends Controller
{
    use ThisUserTrait;

    public function agentNotice()
    {
        $notice = [
            [
                'title' => option('agent_notice_1'),
                'value' => option('agent_answer_1'),
            ],
            [
                'title' => option('agent_notice_2'),
                'value' => option('agent_answer_2'),
            ],
            [
                'title' => option('agent_notice_3'),
                'value' => option('agent_answer_3'),
            ],
        ];
        $user_offline = UserReference::where('ref_id', $this->user->id)->with('childUser')->first();

        $satisfy = ($user_offline != null && count($user_offline['child_user']) >= 6) ? true : false;
        $result  = [
            'notice'  => $notice,
            'satisfy' => $satisfy,
        ];
        return real($result)->success();
    }

    public function classStatis(Request $request)
    {
        $rule = [
            'game' => 'required',
        ];
        $data = $request->only(array_keys($rule));
        real()->validator($data, $rule);
        $offline = UserReference::where('ref_id', $this->user->id)
            ->with('children')
            ->get()
            ->toArray();
        $child = [];
        foreach ($offline as $value) {
            $child[] = [
                'level'   => 1,
                'user_id' => $value['user_id'],
            ];
            foreach ($value['children'] as $val) {
                $child[] = [
                    'level'   => 2,
                    'user_id' => $val['user_id'],
                ];
            }
        }
        $user_list = array_column($child, 'user_id');

        $start = request()->input('start') ?? date('Y-m-d', strtotime('-29 day'));
        $end   = request()->input('end') ?? date('Y-m-d');

        $game_model = BetLog::whereBetween('created_at', [$start . ' 00:00:00', $end . ' 23:59:59'])
            ->where('status', 2)->where('effective', 1)->whereIn('user_id', $user_list); //8

        $game_amount = $game_model->selectRaw('count(distinct(user_id)) as amount')->first()->amount;

        $game_money = $game_model->sum('total');

        $game_bonus = $game_model->sum('bonus');

        $rebate = BetRebate::whereBetween('date', [$start, $end])
            ->where('user_id', $this->user->id)
            ->sum('profit');

        $offline_rebate = UserAward::whereBetween('created_at', [$start . ' 00:00:00', $end . ' 23:59:59'])
            ->where('user_id', $this->user->id)->where('unique', 'regexp', 'agentrebate')
            ->sum('amount');

        $profit = ($game_bonus - $game_money) + $rebate + $offline_rebate;
        $result = [
            toDecimal($game_amount, 0),
            toDecimal($game_money, 3),
            toDecimal($game_bonus, 3),
            toDecimal($rebate, 3),
            toDecimal($offline_rebate, 3),
            toDecimal($profit, 3),
        ];
        return real($result)->success();
    }

    public function log(Request $request)
    {
        $offline = UserReference::where('ref_id', $this->user->id)
            ->with('children')
            ->get()
            ->toArray();
        $child = [];
        foreach ($offline as $value) {
            $child[] = [
                'level'   => 1,
                'user_id' => $value['user_id'],
            ];
            // foreach ($value['children'] as $val) {
            //     $child[] = [
            //         'level'   => 2,
            //         'user_id' => $val['user_id'],
            //     ];
            // }
        }
        $user_list = array_column($child, 'user_id');
        $model     = UserAward::query();
        $start     = $request->input('start', date('Y-m-d', strtotime('-29 day')));
        $end       = $request->input('end', date('Y-m-d'));
        $model->whereIn('user_id', $user_list);
        // dd($user_list);
        $model->whereBetween('created_at', [$start . ' 00:00:00', $end . ' 23:59:59']);
        $model->where('type', 'regexp', 'rebate'); //代表代理吃的返水
        ($request->type && $request->type != 'all') && $model->where('unique', 'regexp', $request->type);
        $items = $model->paginate(20)->toArray();
        return real()->listPage($items)->success();
    }

    public function rebate(Request $request)
    {
        $model = UserAward::query();
        $start = request()->input('start') ?? date('Y-m-d', strtotime('-29 day'));
        $end   = request()->input('end') ?? date('Y-m-d');
        $model->whereBetween('created_at', [$start . ' 00:00:00', $end . ' 23:59:59']);
        $model->where('user_id', $this->user->id);
        $request->game && $model->whereRaw("json_extract(extend,'$.game') = '$request->game'");
        $request->room && $model->where('unique', 'regexp', $request->room);
        $amount = $model->sum('amount');
        $items  = $model->paginate(20)->toArray();
        return real(['amount' => $amount])->listPage($items)->success();
    }

    public function statis(Request $request)
    {
        $start   = $request->input('start') ?? date('Y-m-d', strtotime('-29 day'));
        $end     = $request->input('end') ?? date('Y-m-d');
        $offline = UserReference::where('ref_id', $this->user->id)
            ->with('children')
            ->get()
            ->toArray();
        $user_list = [];

        foreach ($offline as $value) {
            $user_list[] = $value['user_id'];
        }

        $offline_amount = count($user_list);
        $bet_amount     = BetLog::whereBetween('created_at', [$start . ' 00:00:00', $end . ' 23:59:59'])->where('status', 2)->whereIn('user_id', $user_list)->sum('total');
        $bonus_amount   = BetLog::whereBetween('created_at', [$start . ' 00:00:00', $end . ' 23:59:59'])->where('status', 2)->whereIn('user_id', $user_list)->sum('bonus');
        $bet_rebate     = UserAward::where('user_id', $this->user->id)->whereBetween('created_at', [$start . ' 00:00:00', $end . ' 23:59:59'])->where('unique', 'regexp', 'selfrebate')->sum('amount');
        $data           = [
            'group2' => [
                $offline_amount,
                toDecimal($bet_amount, 3),
                toDecimal($bonus_amount, 3),
                toDecimal($bet_rebate, 3),
            ],
        ];
        return real($data)->success();
    }
}
