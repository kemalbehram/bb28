<?php

namespace App\Console\Commands;

use App\Models\Robot\AutoReChe;
use Illuminate\Console\Command;

class RobotAutoWalletCommand extends Command
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Robot Return Check';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'robot:re_che';

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
        $this->info('开始机器人自动查回');

        $model = AutoReChe::reche();
        $this->info('结束机器人自动查回');
    }
}
