<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserAward;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UserLevelUpgradeCommand extends Command
{
    protected $description = 'UserLevelUpgrade';

    protected $signature = 'user:level_upgrade';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('UserLevelUpgrade start');

        if (date('H') > 1) {
            return $this->error('time not correct...');
        }

        $date_start = date('Y-m-d', strtotime('-1 day'));
        $date_end   = date('Y-m-d');

        $cache_name = 'UserLevelUpgrade:' . $date_start . '===' . $date_end;

        $has_bet = cache()->remember($cache_name, 600, function () use ($date_start, $date_end) {
            $bet = DB::table('bet_logs');
            $bet->where('trial', 0);
            $bet->where('status', 2);
            $bet->where('effective', 1);
            $bet->where('confirmed_at', '>=', $date_start);
            $bet->where('confirmed_at', '<', $date_end);
            $bet->groupBy('user_id');
            $raw_bet = DB::raw('SUM(total) as total');
            return $bet->get([$raw_bet, 'user_id']);
        });

        foreach ($has_bet as $value) {
            try {
                DB::beginTransaction();
                $value->total = intval($value->total);
                $user         = User::find($value->user_id);
                $create       = [
                    'user_id'      => $value->user_id,
                    'type'         => 1,
                    'date'         => $date_end,
                    'exp_value'    => $value->total,
                    'exp_before'   => $user->option->level_exp,
                    'level_before' => $user->userLevel()['level_index'],
                ];
                $user->option->increment('level_exp', $value->total);
                $create['exp_after']   = $user->option->level_exp;
                $create['level_after'] = $user->userLevel()['level_index'];
                $insert                = DB::table('user_level_exps')->insert($create);
                $insert || real()->exception('经验记录创建失败');

                //如果有升级,晋级奖励
                if ($create['level_before'] < $create['level_after']) {
                    $config = config('app.user_level.rule_table');
                    for ($i = $create['level_before'] + 1; $i <= $create['level_after']; $i++) {
                        $has_award = UserAward::where('user_id', $value->user_id)
                            ->where('type', 'user_level_upgrade')
                            ->where('unique', $i)
                            ->count();

                        if ($has_award > 0) {
                            $this->comment($value->user_id . ':' . '已领取过晋级奖励');
                            continue;
                        }

                        $amount     = $config[$i]['gift_upgrade'];
                        $user_award = [
                            'user_id' => $value->user_id,
                            'type'    => 'user_level_upgrade',
                            'date'    => date('Y-m-d'),
                            'amount'  => $amount,
                            'extend'  => $create,
                            'unique'  => $i,
                        ];

                        $temp = UserAward::create($user_award);
                        $temp || real()->exception('晋级奖励发送失败');

                        $user->wallet->balance('award.receive.user_level_upgrade', $temp->id)->plus($amount);
                    }
                }
                DB::commit();
            } catch (\Throwable $th) {
                $this->comment($value->user_id . ': ' . $value->total . ' error ===' . $th->getMessage());
            }
            $this->comment($value->user_id . ': ' . $value->total . ' success');
        }

        return $this->info('UserLevelUpgrade success');
    }
}
