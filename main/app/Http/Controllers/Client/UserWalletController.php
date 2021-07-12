<?php

namespace App\Http\Controllers\Client;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserReference;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\UserWallet\WalletLog;
use App\Models\LottoModule\LottoUtils;
use App\Models\ModelTrait\ThisUserTrait;
use App\Models\LottoModule\Models\BetLog;

class UserWalletController extends Controller
{
    use ThisUserTrait;

    public function bankAction(Request $request)
    {
        $this->user->CheckIsTrial();
        $rule = [
            'amount'    => 'required|currency',
            'safe_word' => 'required|safe_word',
            'in'        => 'required',
            'out'       => 'required',
        ];
        $data = $request->all();
        real()->validator($data, $rule);

        $out = $request->out;
        $in  = $request->in;
        $out == $in && real()->exception('转入转出账户不能相同，请重新选择');

        DB::beginTransaction();
        if ($out === 'bank' && $this->user->agent->advance > 0) {
            $remain = $this->user->wallet->bank - $this->user->agent->advance;
            $remain < $request->amount && real()->exception('转出后银行账户余额不能低于铺货分');
        }

        $desc = $out . '.to.' . $in;
        $this->user->wallet->$out($desc)->minus($request->amount);
        $this->user->wallet->$in($desc)->plus($request->amount);

        $result = ['wallet' => $this->user->wallet];

        DB::commit();
        return real($result)->success('资金转入对应账户成功');
    }

    public function betLog(Request $request)
    {
        $status         = $request->status;
        $condition      = $request->field;
        $data           = $this->user->betLog()->with('details');
        $request->start = $request->input('start', date('Y-m-d', strtotime('-30 day')));
        $request->end   = $request->input('end', date('Y-m-d'));
        // dd($request->start, $request->end);
        $data->whereBetween('created_at', [$request->start . ' 00:00:00', $request->end . ' 23:59:59']);
        $request->lotto_name && $data->where('lotto_index', 'regexp', $request->lotto_name);
        $data->orderBy('id', 'desc');
        $data->where('effective', 1);
        if ($status == null) {
            $data->where('status', '>', 0);
        }
        $status == 1 && $data->where('status', 1);
        $status == 2 && $data->where('status', 2);
        $status == 3 && $data->where('bonus', '>', 0)->where('status', 2);
        $status == 4 && $data->where('bonus', '=', 0)->where('status', 2);

        $condition == 'win' && $data->where('bonus', '>', 0)->where('status', 2);
        $condition == 'lose' && $data->where('bonus', '=', 0)->where('status', 2);
        $condition == 'wait' && $data->where('status', 1);

        $request->game && $data->where('lotto_index', 'regexp', $request->game);
        $request->room && $data->where('play_type', $request->room);
        $amount = [];
        if ($condition) {
            $amount = $this->getAmount();
        }
        $page           = env('PAGE_LIMIT', 20);
        $paginate       = $data->paginate($page);
        $paginate->data = $paginate->makeHidden(['lotto_index', 'updated_at', 'trial']);
        $result         = $paginate->toArray();

        return real(['amount' => $amount])->listPage($result)->success();
    }

    public function betLogGet(Request $request)
    {
        $rule = ['id' => 'required|int'];
        $data = $request->all();
        real()->validator($data, $rule);

        $item = BetLog::with('details')->find($request->id);

        $item || real()->exception('data.notexist');
        $lotto = LottoUtils::model($item->lotto_name)
            ->find($item->lotto_id)
            ->makeHidden(['mark', 'opened_at', 'updated_at']);
        $item->lotto    = $lotto;
        $item->lotto_at = $lotto->lotto_at;

        $result = $item->toArray();
        return real($result)->success();
    }

    public function log(Request $request)
    {
        $page = env('PAGE_LIMIT', 20);
        $data = $this->user->walletLog();

        if ($request->source === 'bank-in-out') {
            $source = ['balance.to.bank', 'bank.to.balance'];
            $data->where('field', 'bank');
            $data->whereIn('source_name', $source);
            $page = 10;
        }

        $raw_date = DB::raw("date_format(`created_at`, '%Y-%m-%d')");
        if ($request->start == null) {
            $request->start = date('Y-m-d');
        }
        if ($request->end == null) {
            $request->end = date('Y-m-d');
        }
        // if ($request->field && $request->field !== 'all') {
        //     if ($request->field == 'award') {
        //         $data->where('source_name', 'regexp', 'award.receive');
        //     } else {
        //         $data->where('field', $request->field);
        //     }
        // }
        //dd(date('Y-m-d', strtotime($request->date) + 3600 * 24));
        $request->field && $request->field !== 'all' && $data->where('source_name', 'like', '%' . $request->field . '%');
        $request->start && $data->where($raw_date, '>=', date('Y-m-d', strtotime($request->start)));
        $request->end && $data->where($raw_date, '<=', date('Y-m-d', strtotime($request->end)));
        // $request->date_start && $data->where($raw_date, '>=', $request->date_start);
        // $request->date_end && $data->where($raw_date, '<=', $request->date_end);

        $data->orderBy('id', 'desc');
        $paginate       = $data->paginate($page);
        $paginate->data = $paginate->makeHidden(['extend', 'source_id', 'remark']);
        $result         = $paginate->toArray();

        return real()->listPage($result)->success();
    }

    public function offlineWinLose(Request $request)
    {
        $page           = env('PAGE_LIMIT', 20);
        $request->start = $request->input('start', date('Y-m-d'));
        $request->end   = $request->input('end', date('Y-m-d'));
        $offline        = UserReference::where('ref_id', $this->user->id)
            ->with('children')
            ->get()
            ->toArray();
        $child = [];
        foreach ($offline as $value) {
            $child[] = [
                'level'   => 1,
                'user_id' => $value['user_id'],
            ];
            // foreach ($value['children'] as $val) {
            //     $child[] = [
            //         'level'   => 2,
            //         'user_id' => $val['user_id'],
            //     ];
            // }
        }
        $user_list = array_column($child, 'user_id');
        $model     = BetLog::query();
        $result    = $model->whereIn('user_id', $user_list)->where('status', 2)
            ->whereBetween('created_at', [$request->start . ' 00:00:00', $request->end . ' 23:59:59'])
            ->select(['user_id', 'total', 'bonus', 'id', 'lotto_index'])
            ->paginate($page)->toArray();
        return real()->listPage($result)->success();
    }

    public function totalChange(Request $request)
    {
        // $raw_date       = DB::raw("date_format(``, '%Y-%m-%d')");
        $request->start = $request->input('start', date('Y-m-d'));
        $request->end   = $request->input('end', date('Y-m-d'));

        $data    = WalletLog::where('user_id', $this->user->id);
        $income  = 0;
        $expense = 0;

        $data->whereBetween('created_at', [$request->start . ' 00:00:00', $request->end . ' 23:59:59']);

        ($request->field && $request->field !== 'all') && $data->where('source_name', 'like', '%' . $request->field . '%');
        $data = $data->get()->toArray();

        foreach ($data as $key => $val) {
            if ($val['amount'] > 0) {
                $income += $val['amount'];
            } else {
                $expense += $val['amount'];
            }
        }
        $result['income']  = $income;
        $result['expense'] = abs($expense);
        return real($result)->success();
    }

    protected function getAmount()
    {
        $request = request();
        $model   = BetLog::query();
        // $data  = $model->where('created_at', '>=', date('Y-m-d', strtotime('-30 day')));
        $request->start = $request->input('start', date('Y-m-d', strtotime('-30 day')));
        $request->end   = $request->input('end', date('Y-m-d'));

        $data = $model->whereBetween('created_at', [$request->start . ' 00:00:00', $request->end . ' 23:59:59']);
        $request->game && $data->where('lotto_index', 'regexp', $request->game);
        $request->room && $data->where('play_type', $request->room);
        $data->where('user_id', $this->user->id);
        $data->where('effective', 1);

        $data->where('status', '>', 0);
        $condition = request()->field;
        $condition == 'win' && $data->where('bonus', '>', 0)->where('status', 2);
        $condition == 'lose' && $data->where('bonus', '=', 0)->where('status', 2);
        $condition == 'wait' && $data->where('status', 1);
        $bet_amount    = $data->sum('total');
        $bonus_amount  = $data->sum('bonus');
        $profit_amount = $bonus_amount - $bet_amount;
        return ['bet' => $bet_amount, 'bonus' => $bonus_amount, 'profit' => $profit_amount];
    }
}
