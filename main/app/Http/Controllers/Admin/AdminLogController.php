<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdminLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminLogController extends Controller
{
    public function index(Request $request)
    {
        $model = AdminLog::query();
        $start = $request->start ?? date('Y-m-d 00:00:00');
        $end   = $request->end ?? date('Y-m-d 23:59:59');
        $model->whereBetween('created_at', [$start, $end]);
        $request->admin_id && $model->where('admin_id', $request->admin_id);
        $request->ip && $model->where('ip', $request->ip);
        $items = $model->orderBy('created_at','desc')->paginate(10)->toArray();
        return real()->listPage($items)->success();
    }
}
