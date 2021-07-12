<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\RedBag;
use App\Models\RedBagLog;
use App\Models\User;

class RobotReceiveRedBag extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'robt:receive-red-bag';

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
        // $this->info('开始抢红包');
        $now_time = date('Y-m-d H:i:s', time());

        $red_bags = DB::table('red_bags')->where('expired_at', '<', $now_time)->get();
        // $red_bags = RedBag::select('id', 'total', 'received', 'expired_at')->get();
        foreach ($red_bags as $key => $val) {

            if ($val->total > $val->received) {
                DB::beginTransaction();
                $item = RedBag::lockForUpdate()->find($val->id);
                $amount = $item->amount;
                $item->increment('received', $amount);

                $item->save();

                $user = User::inRandomOrder()->where('robot', 1)->first();

                $data = [
                    'user_id'   => $user->id,
                    'amount'    => $amount,
                    'parent_id' => $val->id,

                ];
                $create = RedBagLog::create($data);
                DB::commit();

                // $this->info('机器人' . $user->id . '抢了红包id' . $val->id . '--' . $amount . '元');
                // sleep(5);
            }
        }
        //clear
    }
}
