<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\RedBag;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RedBagCancelCommand extends Command
{
    protected $description = 'red_bag cancel';

    protected $signature = 'red_bag:cancel';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('red_bag cancel start');

        $raw_where = DB::raw('total');
        $data      = RedBag::whereNull('canceled_at')
            ->where('expired_at', '<', date('Y-m-d H:i:s'))
            ->where('received', '<', $raw_where)->get();

        foreach ($data as $value) {
            DB::beginTransaction();
            $item   = RedBag::lockForUpdate()->find($value->id);
            $remain = $item->total - $item->received;

            $item->canceled_at = date('Y-m-d H:i:s');
            $item->save();
            $user   = User::find($item->create_id);
            $wallet = $user->wallet;
            $wallet->bank('red_bag.canceled', $item->id)->plus($remain);
            $this->comment('red_bag: ' . $item->id . ' == success == ' . $remain);
            DB::commit();
        }
        return $this->info('red_bag cancel success');
    }
}
