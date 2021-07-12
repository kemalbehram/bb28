<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LottoModule\Models\LottoPlayType;
use App\Http\Controllers\Admin\TurnOverRebateController;

class AutoRebateCommand extends Command
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto Rebate';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:rebate';

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
        $controller = new TurnOverRebateController();
        $play_type  = LottoPlayType::pluck('name')->toArray();
        foreach ($play_type as $value) {
            $this->info('begin ' . $value);
            request()->offsetSet('room_name', $value);
            $controller->get(request());
            $this->info('end ' . $value);
        }
    }
}
