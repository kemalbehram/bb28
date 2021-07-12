<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserWallet\WalletLog;

class RiskController extends Controller
{
    public function systemWallet(Request $request)
    {
        $data = WalletLog::with('user:id,nickname')
            ->where('source_name', 'system.service');

        $request->user_id && $data->where('user_id', $request->user_id);
        $data->orderBy('id', 'desc');

        $result = $data->paginate(20)->toArray();

        return real()->listPage($result)->success();
    }
}
