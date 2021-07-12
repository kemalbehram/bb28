<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LottoModule\Models\LottoChatHistory;

class clearChatCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'chat clear';

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
        $this->info("begin to clear");
        $mtime = date("Y-m-d H:i:s", strtotime("-1 hour"));
        LottoChatHistory::where('created_at', '<', $mtime)->delete();
        $this->info("finish");
    }
}
