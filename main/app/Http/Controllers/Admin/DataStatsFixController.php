<?php

namespace App\Http\Controllers\Admin;

use App\Models\DataStatsFix;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DataStatsFixController extends Controller
{
    public function create(Request $request)
    {
        $rule = [
            'user_id' => 'required',
            'name'    => 'required',
            'date'    => 'required',
            'value'   => 'required|currency',
            'remark'  => 'required',
        ];
        $data = $request->all();
        real()->validator($data, $rule);

        $data = [
            'user_id'  => $request->user_id,
            'name'     => $request->name,
            'date'     => $request->date,
            'value'    => $request->value,
            'remark'   => $request->remark,
            'admin_id' => auth('admin')->id(),
        ];

        $create = DataStatsFix::create($data);
        $create || real()->exception('数据修正失败 请重试');

        return real()->success('数据修正成功 请刷新相关统计页面');
    }

    public function index(Request $request)
    {
        $data = DataStatsFix::with('user:id,nickname');
        $request->admin_id && $data->where('admin_id', 'regexp', $request->admin_id);
        $request->id && $data->where('id', 'regexp', $request->id);
        $request->name && $data->where('name', 'regexp', $request->name);
        $request->value && $data->where('value', 'regexp', $request->value);
        $data->orderBy('id', 'desc');
        $result = $data->paginate(20)->toArray();

        return real()->listPage($result)->success();
    }
}
