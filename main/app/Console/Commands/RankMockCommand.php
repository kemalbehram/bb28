<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\RankMock;
use Illuminate\Console\Command;

class RankMockCommand extends Command
{
    protected $description = 'rank mock';

    protected $signature = 'rank:mock';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('rank mock start ');

        $cache_name = 'rankMockCommand:' . date('Y-m-d');

        if (cache()->has($cache_name)) {
            return $this->info('rank mock has cache.');
        }

        $totals = [];

        for ($i = 0; $i < 30; $i++) {
            // $offset   = $i < 20 ? $i : 20;
            $offset   = $i;
            $limit    = 2000000 / 30 * (30 - $offset);
            $totals[] = mt_rand(1000, $limit);
        }

        $date = date('Y-m-d');
        start:
        $rank = RankMock::where('date', $date)->orderBy('total', 'desc')->get();

        if ($rank->toArray() === []) {
            $users = User::where('robot', 1)->take(30)->get();

            foreach ($users as $user) {
                $where = ['user_id' => $user->id, 'date' => $date];
                $value = ['total' => 0];
                $data  = RankMock::updateOrCreate($where, $value);
            }
            goto start;
        }

        $index = 0;
        foreach ($rank as $value) {
            $value->increment('total', $totals[$index] / 1000);
            $index += 1;
        }

        cache()->put($cache_name, 1, 600);

        return $this->info('rank mock success');
    }
}
