<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\UserAward;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserWallet\WalletLog;

class UserAwardController extends Controller
{
    public function index(Request $request)
    {
        $items = WalletLog::with('user');

        $request->user_id && $items->where('user_id', $request->user_id);
        $items->where('source_name', 'regexp', 'award');
        $items->orderBy('id', 'desc');

        $result = $items->paginate(20)->toArray();

        return real()->listPage($result)->success();
    }

    public function rebateLog(Request $request)
    {
        $model      = UserAward::select(['user_awards.id', 'user_awards.user_id', 'user_awards.amount', 'user_awards.unique', 'user_awards.extend', 'user_awards.created_at'])->join('users', function ($join) {
            $join->on('user_awards.user_id', 'users.id')
                ->Where('users.trial', 0);
        });
        $user_model = new User();
        $request->user_id && $model->where('user_id', $request->user_id);

        $items = $model->with('user:id,nickname,trial')->orderBy('user_awards.created_at', 'desc')->paginate(20)->toArray();
        foreach ($items['data'] as &$value) {

            if (isset($value['extend']['child'])) {
                $value['child_info'] = $user_model->where('id', $value['extend']['child'])->first(['id', 'nickname'])->toArray();
            }
        }
        return real()->listPage($items)->success();
    }
}
