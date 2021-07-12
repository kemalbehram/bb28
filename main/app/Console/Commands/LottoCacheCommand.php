<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LottoModule\LottoUtils;

class LottoCacheCommand extends Command
{
    protected $description = 'lotto cache';

    protected $signature = 'lotto:cache';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('lotto cache start');

        if (date('s') >= 20) {
            $this->info('lotto cache not time');
            return false;
        }

        $handle = ['ca28', 'cw28', 'bit28', 'de28', 'bj28', 'pc28', 'tw28'];

        foreach ($handle as $lotto) {
            $model      = LottoUtils::Model($lotto);
            $last       = $model->where('status', 2)->orderBy('id', 'desc')->first();
            $cache_name = 'OpenedExtendUpdate:' . $lotto;
            $cache_get  = cache()->get($cache_name);
            $this->comment($last->id . ':' . $cache_get);
            if ($cache_get != $last->id) {
                cache()->put($cache_name, $last->id);
                $model->placeExtend(true);
                $this->comment($lotto . ':' . $last->id);
            }
        }

        return $this->info('lotto cache success');
    }
}
