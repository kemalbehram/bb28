<?php

namespace App\Http\Controllers\Client;

use App\Models\User;
use App\Models\UserAgent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserWallet\TransferData;
use App\Models\ModelTrait\ThisUserTrait;

class UserAgentController extends Controller
{
    use ThisUserTrait;

    public function partner(Request $request)
    {
        $data = UserAgent::with('user:id,nickname')->where('visible_admin', 1)
            ->where('visible_agent', 1)
            ->whereNotNull('contact_qq')
            ->get();

        $items = [];
        foreach ($data as $item) {
            $temp = [
                'nickname'   => $item->user->nickname,
                'contact_qq' => $item->contact_qq,
                'avatar'     => $item->user->avatar,
                'intro'      => $item->intro ?: '实力代理 诚信高效',
            ];

            $items[] = $temp;
        }

        shuffle($items);
        $result = ['items' => $items];

        return real($result)->success();
    }

    public function statsDays(Request $request)
    {
        $data   = new TransferData();
        $start  = date('Y-m-d', strtotime('-15 days'));
        $end    = date('Y-m-d');
        $items  = $data->statsAgent('day', $this->user->id, $start, $end);
        $result = ['items' => $items];
        return real($result)->success();
    }

    public function statsMonths(Request $request)
    {
        $data   = new TransferData();
        $end    = date('Y-m');
        $start  = date('Y-m', strtotime('-6 month'));
        $items  = $data->statsAgent('month', $this->user->id, $start, $end);
        $result = ['items' => $items];
        return real($result)->success();
    }

    public function update(Request $request)
    {
        $agent = $this->user->agent;

        $agent->status === true || real()->exception('您无权限修改');

        $agent->visible_agent = $request->visible_agent;
        $agent->contact_qq    = $request->contact_qq;
        $agent->intro         = $request->intro;

        $agent->save();

        return real()->success('代理资料更新成功');
    }
}
