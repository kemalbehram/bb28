<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use App\Models\UserWallet\Wallet;
use Illuminate\Support\Facades\DB;
use App\Models\UserWallet\WalletLog;

class FundSettleCommand extends Command
{
    protected $description = 'fund settle';

    protected $signature = 'fund:settle';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('fund settle start');

        $has_fund = Wallet::where('fund', '>', 0)->get();

        foreach ($has_fund as $value) {
            DB::beginTransaction();
            $date   = date('Y-m-d');
            $action = $this->settle($value->user_id, $date);
            $this->comment($value->user_id . ':' . $date . '===' . $action);
            DB::commit();
        }

        return $this->info('fund settle success');
    }

    public function settle($user_id, $date)
    {
        $last_date = date('Y-m-d', strtotime($date) - 86400);

        $user     = User::find($user_id);
        $wallet   = $user->wallet;
        $raw_date = DB::raw('date_format(`created_at`,"%Y-%m-%d")');

        $settled = WalletLog::where('user_id', $user_id)
            ->where('source_id', $date)
            ->where('source_name', 'fund.settle')
            ->first();

        if ($settled) {
            return 'has settled';
        }

        $was_in = WalletLog::where('user_id', $user_id)
            ->where('field', 'fund')
            ->where($raw_date, '>=', $last_date)
            ->whereIn('source_name', ['bank.to.fund', 'balance.to.fund'])
            ->sum('amount');

        $was_out = WalletLog::where('user_id', $user_id)
            ->where('field', 'fund')
            ->where($raw_date, '>=', $last_date)
            ->whereIn('source_name', ['fund.to.bank', 'fund.to.balance'])
            ->sum('amount');

        $diff = $was_in + $was_out;
        $diff = $diff > 0 ? $diff : 0;
        $effe = $wallet->fund - $diff;

        if ($effe < 100) {
            return 'limit 100';
        }

        $rate   = 0.00033;
        $amount = bcmul($effe, $rate, 3);

        $extend = ['effe' => $effe, 'rate' => $rate, 'amount' => $amount];
        $wallet->fund('fund.settle', $date)->extend($extend)->plus($amount);

        return json_encode($extend);
    }
}
