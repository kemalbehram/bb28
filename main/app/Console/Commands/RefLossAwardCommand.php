<?php

namespace App\Console\Commands;

use App\Models\UserAward;
use App\Models\UserReference;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RefLossAwardCommand extends Command
{
    protected $description = 'RefLossAward settle';

    protected $signature = 'award:ref_loss_settle';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('RefLossAward start');

        if (date('w') !== 1) {
            return $this->error('today not monday...');
        }

        $references = UserReference::GroupBy('ref_id')->get(['id', 'ref_id']);
        $start      = date('Y-m-d', strtotime('-1 monday')); //上周周一
        $end        = date('Y-m-d', strtotime($start) + 86400 * 6); //上周周末
        $date       = date('Y-m-d');

        // dd($start, $end, $date);

        foreach ($references as $key => $value) {
            DB::beginTransaction();
            $model = new UserAward();
            $temp  = $model->settleRefLossLevel5($value->ref_id, $start, $end, $date);
            DB::commit();
            $this->comment($value->ref_id . ': ' . $start . '===' . $end . ' ' . $temp);
        }

        return $this->info('RefLossAward success');
    }
}
