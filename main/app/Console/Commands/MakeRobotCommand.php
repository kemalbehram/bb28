<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Client\UserAuthController;

class MakeRobotCommand extends Command
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make robot';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:robot';

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
        $controller = new UserAuthController();
        $controller->robotAdd(request());
    }
}
