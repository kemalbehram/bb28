<?php

namespace App\Console\Commands;

use App\Models\UserAward;
use App\Models\UserReference;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RefBetAwardCommand extends Command
{
    protected $description = 'RefBetAward settle';

    protected $signature = 'award:ref_bet_settle';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('RefBetAward settlement start');

        $references = UserReference::GroupBy('ref_id')->get(['id', 'ref_id']);

        foreach ($references as $key => $value) {
            $date = date('Y-m-d', strtotime('-1 day'));
            DB::beginTransaction();
            $model = new UserAward();
            $temp  = $model->settleRefBetLevel1($value->ref_id, $date);
            DB::commit();
            $this->comment($value->ref_id . ': ' . $date . ' ' . $temp);
        }

        return $this->info('RefBetAward settlement success');
    }
}
