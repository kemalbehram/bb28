<?php
namespace App\Http\Controllers\Admin;

use App\Models\RedBag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RedBagController extends Controller
{
    public function get(Request $request)
    {
        $rule = [
            'id' => 'required|exists:red_bags',
        ];
        $data = $request->only(array_keys($rule));
        real()->validator($data, $rule);
        $detail = RedBag::with(['logs' => function ($query) {
            $query->with('user');
        }])->find($request->id)->toArray();
        if (date('Y-m-d H:i:s') >= $detail['expired_at']) {
            real($detail)->exception('该红包已过期');
        }

        if ($detail['received'] >= $detail['total'] || $detail['quantity'] <= count($detail['logs'])) {
            real($detail)->exception('该红包已被领取完');
        }
        return real($detail)->success();
    }

    public function index(Request $request)
    {
        $items = RedBag::with([
            'logs' => function ($query) {
                $query->with('user:id,nickname');
            }])->with('user:id,nickname');

        if ($request->receive_id) {
            $items->whereHas('logs', function ($query) use ($request) {
                $query->where('user_id', $request->receive_id);
            });
        }

        $request->create_id && $items->where('create_id', $request->create_id);

        $items->orderBy('id', 'desc');

        $result = $items->paginate(20)->toArray();

        return real()->listPage($result)->success();
    }
}
