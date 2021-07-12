<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\LottoModule\Models\BetAutomator;

class BetAutomatorCommand extends Command
{
    protected $description = 'bet automator';

    protected $signature = 'bet:automator';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('bet automator start');

        $items = BetAutomator::where('status', 1)->get();

        foreach ($items as $value) {
            DB::beginTransaction();
            // dd($value->id);
            $item = BetAutomator::find($value->id);

            // dd($item);
            try {
                $item->execute();
                $this->comment($value->id . ':' . $value->user_id);
            } catch (\Throwable $th) {
                $this->comment($value->id . ':' . $value->user_id . ' === ' . $th->getMessage());
            }

            DB::commit();
        }

        return $this->info('bet automator success');
    }
}
