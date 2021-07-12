<?php

namespace App\Http\Controllers\Admin;

use App\Models\Option;
use App\Models\RechargeAisle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use function AlibabaCloud\Client\json;

class OptionController extends Controller
{
    public function get(Request $request)
    {
        $model = Option::query();
        $request->regexp && $model->where('name', 'regexp', $request->regexp);
        $data   = $model->get();
        $result = [];
        foreach ($data as $source) {
            $name          = $source->name;
            $value         = $source->value;
            $result[$name] = $value;
        }
        return real($result)->success();
    }

    public function update(Request $request)
    {
        $rule = [
            'name'  => 'required',
            'value' => 'required|array',
        ];
        $data = $request->all();
        real()->validator($data, $rule);

        $where = ['name' => $request->name];
        $value = ['value' => $request->value];
        $data  = Option::updateOrCreate($where, $value);

        return real()->success('data.update.success');
    }

    public function updatePatch(Request $request)
    {
        DB::beginTransaction();
        foreach ($request->post() as $name => $value) {
            $where = ['name' => $name];
            $value = ['value' => $value];
            $data  = Option::updateOrCreate($where, $value);
            $data || real()->exception('data.update.faild');
        }
        DB::commit();
        return real()->success('data.update.success');
    }

    public function aisles(Request $request)
    {
        $model = RechargeAisle::query();
        $data   = $model->where(['type' => 'bank'])->first();
        $result = [];
        $result['data'] = $data;
        return real($result)->success();
    }

    public function aislesUpdate(Request $request)
    {
        $aisle = RechargeAisle::where(['type' => $request->type])->update(['extend' => json_encode($request->extend)]);


        return real()->success('更新成功');
    }
}
