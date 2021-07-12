<?php

namespace App\Http\Controllers\Admin;

use App\Models\Option;
use Illuminate\Http\Request;
use function App\Models\option;
use App\Http\Controllers\Controller;
use App\Models\LottoModule\LottoUtils;
use Illuminate\Support\Facades\DB;

class LottoDataController extends Controller
{
    public function control(Request $request)
    {
        $control = explode(',', option('master_lotto_control'));
        if (in_array($request->lotto_name, $control) === false) {
            return real()->error('您无权限操作');
        }
        DB::beginTransaction();
        $item = LottoUtils::model()->makeVisible(['control'])->find($request->id);

        $item->control = $request->control;
        $item->save() || real()->exception('控制更新失败 请重试');

        $log = [
            'lotto_name' => $request->lotto_name,
            'lotto_id'   => $request->id,
            'value'      => $request->control,
            'app_name'   => env('APP_NAME'),
            'admin_id'   => auth('admin')->id(),
        ];
        DB::connection('lotto_data')->table('control_logs')->insert($log);

        // $risk = new RiskCenter();
        // $risk->lottoControlNotice($log);

        // AdminAction::createData('lotto_control', $request->lotto_name . ' / ' . $request->id . ' / ' . $request->control);
        DB::commit();
        return real()->success('成功更新控制值为：' . $request->control);
    }

    public function get(Request $request)
    {
        $items = LottoUtils::model()
            ->with('betLog.user:id,nickname,trial')
            ->find($request->id);

        $items->makeVisible(['control']);

        $result = $items->toArray();
        return real($result)->success();
    }

    public function index(Request $request)
    {
        $items = LottoUtils::model()->query();
        $request->status && $items->where('status', $request->status);
        $request->id && $items->where('id', $request->id);
        $order = $request->status === '1' ? 'asc' : 'desc';
        $items->orderBy('id', $order);
        $paginate       = $items->paginate(20);
        $paginate->data = $paginate->makeVisible(['control']);

        $result = $paginate->toArray();
        return real()->listPage($result)->success();
    }
}
