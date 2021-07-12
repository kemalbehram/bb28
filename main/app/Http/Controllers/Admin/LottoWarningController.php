<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\LottoModule\Models\LottoWarning;

class LottoWarningController extends Controller
{
    public function delay()
    {
        $lotto   = ['ca28', 'cw28', 'bj28', 'tw28', 'pk10', 'bjk3', 'ahk3', 'jsk3', 'shk3', 'hebk3', 'hubk3', 'xjssc', 'tjssc', 'cqssc'];
        $mapping = config('lotto.model.system');
        $items   = [];

        $date = date('Y-m-d H:i', strtotime('-3 minute'));
        foreach ($lotto as $name) {
            $model = $mapping[$name];
            $data  = app($model)->where('lotto_at', '<=', $date)->where('status', 1)->get(['id', 'lotto_at']);
            foreach ($data as &$value) {
                $value->model_name = $model;
                array_push($items, $value->toArray());
            }
        }

        $result = ['items' => $items];
        return real($result)->success();
    }

    public function fix(Request $request)
    {
        if (env('ADMIN_ACCESS') !== 'master') {
            return real()->error('您无权限操作');
        }

        $rule = [
            'model_name' => 'required',
            'model_id'   => 'required',
            'field'      => 'required',
            'value'      => 'required',
        ];
        $data = $request->all();
        real()->validator($data, $rule);

        DB::beginTransaction();
        $model = app($request->model_name);
        $item  = $model->lockForUpdate()->find($request->model_id);
        $item || real()->exception('对应游戏的期号不存在 请重新输入');

        if ($request->field === 'lotto_at') {
            if (strtotime($request->value) === false) {
                real()->exception('修正时间格式错误 请重新输入');
            }
            $item->lotto_at = $request->value;
            $item->save();
            $model->where('status', '1')->where('id', '>', $request->model_id)->delete();
        }

        if ($request->field === 'open_code') {
            $item->status != 1 && real()->exception('该期游戏状态为已开奖 请核对相关信息');
            $item->open_code = $request->value;
            $item->status    = 2;
            $item->save();

            $item->win_extend || real()->exception('开奖号码错误 请重试');
        }

        DB::commit();

        return real($item->toArray())->success('数据修正成功 请再次人工核对');
    }

    public function handle(Request $request)
    {
        $rule = ['id' => 'required'];
        $data = $request->all();
        real()->validator($data, $rule);

        $item = LottoWarning::find($request->id);
        $item || real()->exception('该条记录不存在 请重试');

        if ($item->handled_at) {
            return real()->success('操作成功');
        }

        $item->handled_at = date('Y-m-d H:i:s');
        $item->save();

        return real()->success('操作成功');
    }

    public function index(Request $request)
    {
        $items = LottoWarning::query();
        $items->orderBy('id', 'desc');
        $result = $items->paginate(20)->toArray();
        return real()->listPage($result)->success();
    }
}
