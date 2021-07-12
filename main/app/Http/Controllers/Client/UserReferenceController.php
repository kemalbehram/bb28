<?php

namespace App\Http\Controllers\Client;

use App\Models\User;
use App\Models\UserAward;
use App\Models\Commission;
use Illuminate\Http\Request;
use App\Models\UserReference;
use App\Http\Controllers\Controller;
use App\Models\ModelTrait\ThisUserTrait;

class UserReferenceController extends Controller
{
    use ThisUserTrait;

    public function check(Request $request)
    {
        $this->user->CheckIsTrial();
        if ($request->ref_id == $this->user->id) {
            return real()->error('不能添加自己为上级用户');
        }
        $result = $this->validator($request);

        return real($result)->success('验证成功，请核对上级昵称');
    }

    public function commission()
    {
        $this->user->CheckIsTrial();
        $data = Commission::where('user_id', $this->user->id)
            ->orderBy('id', 'desc')
            ->take(30)
            ->get(['id', 'user_id', 'total', 'date_start', 'date_end'])
            ->makeHidden(['id', 'user_id']);

        $items = [];
        for ($i = 1; $i <= 15; $i++) {
            $start         = date('Y-m-d', strtotime('-' . $i . ' monday'));
            $end           = date('Y-m-d', strtotime($start) + 86400 * 6);
            $items[$start] = [
                'date_start' => $start,
                'date_end'   => $end,
                'total'      => toDecimal(0),
            ];
        }

        foreach ($data->toArray() as $key => $value) {
            $items[$value['date_start']] = $value;
        }

        $result = ['items' => array_values($items)];
        return real($result)->success();
    }

    public function create(Request $request)
    {
        $this->user->CheckIsTrial();

        if ($request->ref_id == $this->user->id) {
            return real()->error('不能添加自己为上级用户');
        }

        $result = $this->validator($request);
        $create = UserReference::createReference($this->user->id, $request->ref_id);
        $create || real()->exception('添加上级用户失败 请重试');

        return real($result)->success('添加上级用户成功');
    }

    public function level(Request $request)
    {
        $this->user->CheckIsTrial();
        $user_id = $request->user_id ? $request->user_id : $this->user->id;
        $items   = UserReference::with('user:id,nickname')->where('ref_id', $user_id)->get(['user_id', 'ref_id', 'created_at']);
        $current = User::find($user_id, ['id', 'nickname']);

        $specific = UserReference::where('user_id', $user_id)->first();

        if ($user_id === $this->user->id) {
            $excerpt = '您目前共有' . count($items) . '名下级用户';
        } else {
            $excerpt = 'Ta是您的' . $specific->level . '级用户';
        }

        $result = [
            'current'  => $current->toArray(),
            'items'    => $items->toArray(),
            // 'items'    => [],
            'excerpt'  => $excerpt,
            'specific' => $specific,
        ];

        return real($result)->success();
    }

    //获取下级详情
    public function levelInfo(Request $request)
    {
        $this->user->CheckIsTrial();
        $item = UserReference::with('user:id,nickname')->where(['ref_id' => $this->user->id, 'user_id' => $request->user_id])->first(['user_id', 'ref_id', 'created_at']);
        if ($item == null) {
            real()->exception('无法查询到该用户');
        }
        $userInfo = User::select(['id', 'nickname'])->find($request->user_id);

        $data = $userInfo->walletLog()->paginate(20);

        $result = [
            'userInfo'  => $userInfo,
            'walletLog' => $data->toArray(),
        ];
        // $item == null ?
        // dd($item);
        return real($result)->success();
    }

    //获取下级人数及累计佣金
    public function statistics(Request $request)
    {
        $this->user->CheckIsTrial();
        $user_id       = $request->user_id ? $request->user_id : $this->user->id;
        $yesterday     = [date('Y-m-d 00:00:00', strtotime('-1 day')), date('Y-m-d 23:59:59', strtotime('-1 day'))];
        $sixtyday      = [date('Y-m-d 00:00:00', strtotime('-59 day')), date('Y-m-d H:i:s', time())];
        $model         = UserAward::query();
        $yester_amount = $model->whereBetween('created_at', $yesterday)->where('user_id', $user_id)
            ->where('unique', 'regexp', 'agentrebate')
            ->sum('amount');
        $sixty_amount = $model->whereBetween('created_at', $sixtyday)->where('user_id', $user_id)
            ->where('unique', 'regexp', 'agentrebate')
            ->sum('amount');
        $result = [
            'yester_amount' => toDecimal($yester_amount, 3),
            'sixty_amount'  => toDecimal($sixty_amount, 3),
        ];
        return real($result)->success();
    }

    private function validator(Request $request)
    {
        $rule    = ['ref_id' => 'required|max:6'];
        $data    = $request->all();
        $message = [
            'ref_id.required' => '请输入推荐码',
            'ref_id.max'      => '推荐码最大为6位',
        ];
        real()->validator($data, $rule, $message);

        $user = User::find($request->ref_id);
        $user || real()->exception('该推荐人不存在，请检查推荐码');

        $had = UserReference::where('user_id', $this->user->id)->count();
        $had && real()->exception('您已有上级推荐人，无法再补登');

        $had = UserReference::where('ref_str', 'regexp', $this->user->id)->count();
        $had && real()->exception('您已有下级用户，无法再补登');

        if (date('Y-m-d', strtotime('-8 day')) >= $this->user->created_at) {
            return real()->error('您已注册超过7天 无法再补登');
        }

        return [
            'nickname' => $user->nickname,
            'avatar'   => $user->avatar,
            'ref_id'   => $user->ref_id,
        ];
    }
}
