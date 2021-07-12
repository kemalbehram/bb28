<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\AdminOpt;
use App\Packages\Utils\SMS;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminOptController extends Controller
{
    public function create(Request $request)
    {
        $rule = [
            'mobile' => 'required',
            'type'   => 'required',
        ];
        $data = $request->all();
        real()->validator($data, $rule);
        //判断用户是否存在
        $user = User::where('mobile', $request->mobile)->first();
        if ($request->type != 'register') {
            if ($user == null) {
                return real()->exception('该用户不存在，请确认');
            }
        }

        $code    = $request->opt_value != null ? $request->opt_value : mt_rand(100000, 999999);
        $message = SMS::send($request->type, $request->mobile, $code);
        if (!$message['success']) {
            return real()->exception('验证码发送失败，请尝试再次发送');
        }

        $data = [
            'mobile'    => $request->mobile,
            'type'      => $request->type,
            'opt_value' => $code,
            'user_id'   => $user != null ? $user->id : 0,
            'admin_id'  => auth('admin')->id(),
        ];
        $create = AdminOpt::create($data);

        return real()->success('验证码发送成功 请刷新页面');
    }

    public function index(Request $request)
    {
        $data = AdminOpt::with('admin:id,nickname');
        $request->admin_id && $data->where('admin_id', 'regexp', $request->admin_id);
        $request->user_id && $data->where('user_id', 'regexp', $request->user_id);
        $request->mobile && $data->where('mobile', 'regexp', $request->mobile);
        $data->orderBy('id', 'desc');
        $result = $data->paginate(20)->toArray();
        return real()->listPage($result)->success();
    }
}
