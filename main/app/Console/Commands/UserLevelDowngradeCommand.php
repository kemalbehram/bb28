<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UserLevelDowngradeCommand extends Command
{
    protected $description = 'UserLevelDowngrade';

    protected $signature = 'user:level_downgrade';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('UserLevelDowngrade start');

        if (date('w') !== '1') {
            return $this->error('today not monday...');
        }

        if (date('H') > 2 || date('H') <= 1) {
            return $this->error('time not correct...');
        }

        $query = DB::table('user_options')
            ->where('level_exp', '>=', 200000)
            ->get(['user_id', 'level_exp']);

        $has_level = [];
        foreach ($query as $value) {
            $user_id = $value->user_id;
            $has_level[$user_id] = $value;
        }

        $user_ids = array_keys($has_level);

        $last_monday = date('Y-m-d', strtotime('-1 monday'));
        $last_sunday = date('Y-m-d 23:59:59', strtotime('-1 sunday'));

        $transfer = DB::table('user_wallet_transfers')->whereIn('payee_id', $user_ids)
            ->where('type', 'agent.to.user')
            ->where('status', 2)
            ->where('created_at', '>=', $last_monday)
            ->where('created_at', '<', $last_sunday)
            ->groupBy('payee_id')
            ->get(
                [
                    DB::raw('payee_id AS user_id'),
                    DB::raw('SUM(amount) AS transfer_in'),
                ]
            );

        $has_transfer = [];
        foreach ($transfer as $value) {
            $user_id = $value->user_id;
            $has_transfer[$user_id] = $value;
        }

        foreach ($has_level as $user_id => $value) {
            if (isset($has_transfer[$user_id]) && $has_transfer[$user_id]->transfer_in >= 500) {
                $this->comment($user_id . ' 无需降级');
                continue;
            }

            DB::beginTransaction();
            try {
                $user = User::find($user_id);
                $user_level = $user->userLevel();

                $config = config('app.user_level.rule_table');
                $up_level = $config[$user_level['level_index'] - 1];

                //需要扣除的经验
                $diff_value = $up_level['exp_target'] - $user_level['level_exp'];

                $create = [
                    'user_id' => $user_id,
                    'type' => 2,
                    'date' => date('Y-m-d'),
                    'exp_value' => $diff_value,
                    'exp_before' => $user->option->level_exp,
                    'level_before' => $user->userLevel()['level_index'],
                ];
                $user->option->increment('level_exp', $diff_value);
                $create['exp_after'] = $user->option->level_exp;
                $create['level_after'] = $user->userLevel()['level_index'];
                $create = DB::table('user_level_exps')->insert($create);
                $create || real()->exception('经验记录创建失败');
                $this->comment($user_id . ': ' . $diff_value . ' 降级成功');
            } catch (\Throwable $th) {
                $this->comment($user_id . ': ' . ' error ===' . $th->getMessage());
            }
            DB::commit();
        }

        return $this->info('UserLevelDowngrade success');
    }
}
