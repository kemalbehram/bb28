<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Packages\Utils\PushEvent;
use App\Models\LottoModule\Models\BetMock;
use App\Models\LottoModule\Models\LottoConfig;

class LottoMockBetCommand extends Command
{
    protected $description = 'lotto bet mock';

    protected $signature = 'lotto:mock_bet';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('lotto mock start');

        $lottos = LottoConfig::where('visible', 1)->get();
        foreach ($lottos as $lotto) {
            $online = PushEvent::users('lotto.' . $lotto->name);

            $this->comment($lotto->name . ': users ' . count($online['users']));
            // if (count($online['users']) == 0) {
            //     continue;
            // }

            $model = new BetMock($lotto->name);
            $model->execute($lotto->stop_ahead);
        }

        return $this->info('lotto mock success');
    }
}
