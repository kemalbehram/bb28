<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LottoModule\LottoBonus;
use App\Models\LottoModule\Models\BetLog;
use App\Models\LottoModule\Models\BetLogDetail;

class LottoBonusCommand extends Command
{
    protected $description = 'lotto bonus';

    protected $signature = 'lotto:bonus {--bet_log=}';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('lotto bonus start');

        if ($this->option('bet_log')) {
            $id    = $this->option('bet_log');
            $bonus = new LottoBonus();
            $bonus->execute($id);
            return $this->info('lotto create success');
        }

        // 获取所有未派奖的lotto_index
        // $logs = BetLog::groupBy('lotto_index')->take(2)->get(['lotto_index', 'user_id']);
        $logs   = BetLog::where('status', 1)->groupBy('lotto_index')->get(['lotto_index', 'user_id']);
        $lottos = [];
        $users  = [];
        foreach ($logs as $value) {
            $lottos[$value->lotto_name][] = $value->lotto_id;
            $users[$value->lotto_name]    = $value->user_id;
        }

        //获取所有未派奖对应的lotto（仅查已开奖）
        $mapping = config('lotto.model.system');
        foreach ($lottos as $key => $value) {
            $model = $mapping[$key];
            $data  = app($model)->whereIn('id', $value)->where('status', 2)->get();

            foreach ($data as $_lotto) {
                $lotto_index = $key . ':' . $_lotto->id;
                $user_id     = $users[$key];
                // $cache_name  = 'bet-amount: ' . $user_id . ':' . $lotto_index;
                // $bssd_bet_amount=BetLogDetail::where('log_id',);
                // $cache_second = 120;
                // dd($user_id);
                // $bet_amount = cache()->remember($cache_name, $cache_second, function () use ($lotto_index, $user_id) {
                $bet_id             = BetLog::where('lotto_index', $lotto_index)->where('status', 1)->where('user_id', $user_id)->pluck('id');
                // $result             = [];
                $bet_amount['bssd_bet'] = BetLogDetail::whereIn('log_id', $bet_id)->whereRaw("place REGEXP '(_big|_sml|_sig|_dob)'")->sum('amount'); //本局大小单双总投注额度
                $bet_amount['comb_bet'] = BetLogDetail::whereIn('log_id', $bet_id)->whereRaw("place REGEXP '(_bsg|_sdo|_ssg|_bdo)'")->sum('amount'); //本局组合总投注额度
                // return $result;
                // });
                $bonus = new LottoBonus();

                $bonus->batch($lotto_index, $bet_amount);
            }
        }

        return $this->info('lotto bonus success');
    }
}
