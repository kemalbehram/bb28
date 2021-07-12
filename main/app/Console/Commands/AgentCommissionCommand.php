<?php

namespace App\Console\Commands;

use App\Models\Commission;
use App\Models\User;
use App\Models\UserReference;
use App\Models\UserWallet\WalletLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AgentCommissionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agent:commission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $users = UserReference::get()->groupby('ref_id')->toArray();
        foreach ($users as $key => $val) {

            //下级个数
            $next_level_count = 0;
            //下级总流水
            $next_level_bet = 0;

            //业绩

            foreach ($val as $nkey => $nval) {
                //投注流水 $nval['user_id']
                $raw_bet = DB::raw('SUM(total) as total');

                $raw_profit = DB::raw('(SUM(bonus) - SUM(total)) as profit');
                //当月
                # 获取当前月第一天的日期
                $now_month_first_date = date('Y-m-1');

                # 获取当前月最后一天的日期
                $now_month_last_date = date('Y-m-d', strtotime(date('Y-m-1', strtotime('next month')) . '-1 day'));

                $bet_data = DB::table('bet_logs')->where('user_id', $nval['user_id'])->where('confirmed_at', '>=', $now_month_first_date)->where('confirmed_at', '<=', $now_month_last_date)->first([$raw_bet, $raw_profit]);

                $bet_total = $bet_data->total;
                // dd(floatval($bet_data->profit));
                $bet_data->profit = 100;
                $profit = floatval($bet_data->profit) < 0 ? floatval($bet_data->profit) : 0;

                //用户充值
                $recharge = WalletLog::where('user_id', $nval['user_id'])->where('source_name', 'user.recharge')->sum('amount');
                //有效用户
                if ($bet_total >= 30000 && $recharge >= 3000) {
                    $next_level_count++;
                }
                //总盈利
                // $bonus = BetLog::where('user_id', $nval['user_id'])->sum('bonus');

            }
            //返水率
            $rate = 0;
            if ($next_level_count >= 5 && $next_level_count < 8) {
                if (abs($profit) >= 50000 && abs($profit) < 100000) {
                    $rate = 8;
                }
                if (abs($profit) >= 100000 && abs($profit) < 300000) {
                    $rate = 9;
                }
                if (abs($profit) >= 300000) {
                    $rate = 10;
                }

            }

            if ($next_level_count >= 8 && $next_level_count < 10) {
                if (abs($profit) >= 50000 && abs($profit) < 100000) {
                    $rate = 8;
                }
                if (abs($profit) >= 100000 && abs($profit) < 300000) {
                    $rate = 9;
                }
                if (abs($profit) >= 300000 && abs($profit) < 500000) {
                    $rate = 10;
                }
                if (abs($profit) >= 500000) {
                    $rate = 12;
                }
            }

            if ($next_level_count >= 10 && $next_level_count < 15) {
                if (abs($profit) >= 50000 && abs($profit) < 100000) {
                    $rate = 8;
                }
                if (abs($profit) >= 100000 && abs($profit) < 300000) {
                    $rate = 9;
                }
                if (abs($profit) >= 300000 && abs($profit) < 500000) {
                    $rate = 10;
                }
                if (abs($profit) >= 500000 && abs($profit) < 750000) {
                    $rate = 12;
                }
                if (abs($profit) >= 750000 && abs($profit) < 1000000) {
                    $rate = 13;
                }
                if (abs($profit) >= 1000000) {
                    $rate = 15;
                }
            }
            if ($next_level_count >= 15) {
                if (abs($profit) >= 50000 && abs($profit) < 100000) {
                    $rate = 8;
                }
                if (abs($profit) >= 100000 && abs($profit) < 300000) {
                    $rate = 9;
                }
                if (abs($profit) >= 300000 && abs($profit) < 500000) {
                    $rate = 10;
                }
                if (abs($profit) >= 500000 && abs($profit) < 750000) {
                    $rate = 12;
                }
                if (abs($profit) >= 750000 && abs($profit) < 1000000) {
                    $rate = 13;
                }
                if (abs($profit) >= 1000000 && abs($profit) < 2000000) {
                    $rate = 15;
                }
                if (abs($profit) > 2000000) {
                    $rate = 18;
                }
            }
            // $rate = 8;
            if ($rate != 0) {
                //开始反水
                DB::beginTransaction();
                $model = new Commission();
                $model->user_id = $key;
                $model->profit = $next_level_bet * $rate / 100;
                //$model->profit = 1;
                $model->desc = '下级人数' . $next_level_count . '-业绩' . $next_level_bet;

                $model->save();
                $user = User::find($key);

                $wallet = $user->wallet->balance('agent.commission', $model->id);
                //($wallet);
                $wallet->plus($next_level_bet * $rate / 100);
                DB::commit();
            }
        }

        return $this->info('代理月度分红反水成功2');

    }
}
