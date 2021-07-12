<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserWallet\TransferData;

class TransferController extends Controller
{
    public function index(Request $request)
    {
        $data = TransferData::with('draweeUser:id,nickname')->with('payeeUser:id,nickname');

        $request->id && $data->where('id', $request->id);
        $request->drawee_id && $data->where('drawee_id', $request->drawee_id);
        $request->payee_id && $data->where('payee_id', $request->payee_id);
        $data->orderBy('id', 'desc');
        $result = $data->paginate(20)->toArray();

        return real()->listPage($result)->success();
    }
}
