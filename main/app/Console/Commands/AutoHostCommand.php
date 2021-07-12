<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LottoModule\LottoUtils;
use App\Models\LottoModule\Models\LottoConfig;
use App\Http\Controllers\Client\LottoController;

class AutoHostCommand extends Command
{
    // foreach($items as ){
    // }
    // dd($items->toArray());
    // }

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Host Start';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'host:start {name} {type}';

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
        if (empty($this->argument('name'))) {
            return $this->error('没有游戏名字');
        }
        if (empty($this->argument('type'))) {
            return $this->error('没有游戏类型');
        }
        $item = LottoConfig::where(['visible' => '1', 'name' => $this->argument('name')])->orderBy('sort', 'asc')->first(['name', 'play_types', 'stop_ahead']); //缓存获取

        start:
        $value        = LottoUtils::model($item->name)->where('status', 1)->where('lotto_at', '>=', date('Y-m-d H:i:s'))->whereNull('open_code')->orderBy('lotto_at', 'asc')->first();
        $surplus_time = strtotime($value->lotto_at) - time() - $item->stop_ahead;
        // cache([$item->name . '-bet' => $value->id]);
        $this->info($this->argument('name') . ' 期号:' . $value->id . '等待' . $surplus_time . '秒');
        $val = $this->argument('type');
        request()->offsetSet('lotto_id', $value->id);
        request()->offsetSet('room', str_replace('room', '', $val));
        request()->offsetSet('type', $item->name);
        cache()->put('robot-' . $this->argument('name'), $value->id, $surplus_time);
        if ($surplus_time < 10) { //如果小于发送倒计时时间
            goto opend;
        }
        sleep($surplus_time - 10); //如果大于等于发送倒计时时间 sleep到倒计时
        $controller = new LottoController();

        request()->offsetSet('add_type', 5);
        $this->info($this->argument('name') . ' begin ' . $item->name . '-' . $val . '-' . $value->id . ' 发送10秒倒计时');
        $controller->stopInfo(request()); //发送倒计时
        sleep(10); //发送完了 sleep10秒
        request()->offsetSet('add_type', 4);
        $this->info($this->argument('name') . ' begin ' . $item->name . '-' . $val . '-' . $value->id . ' 发送封盘线');
        $controller->stopInfo(request()); //发送封盘
        $this->info($this->argument('name') . ' 期号:' . $value->id . '等待开奖');
        sleep(5); //sleep封盘到开奖时间
        opend:
        $opend = LottoUtils::model(request()->type)->find(request()->lotto_id)->toArray();
        if (empty($opend['open_code'])) {
            sleep(3);
            cache(['name' => isset($item->name) ? $item->name : cache('name')]);
            cache(['val' => isset($val) ? $val : cache('val')]);
            cache(['id' => isset($item->id) ? $item->id : cache('id')]);
            $this->info($this->argument('name') . ' 没有开奖结果 重来');
            goto opend;
        }
        $controller = new LottoController();
        $controller->chatOpen(request()); //发送开奖消息
        $this->info($this->argument('name') . ' begin ' . $item->name ?? cache('name') . '-' . $val ?? cache('val') . '-' . $value->id ?? cache('id') . ' 发送开奖');
        request()->offsetSet('add_type', 3);
        request()->offsetSet('lotto_id', ($value->id ?? cache('id')) + 1);
        $this->info($this->argument('name') . ' begin ' . $item->name ?? cache('name') . '-' . $val ?? cache('val') . '-' . ($value->id ?? cache('id')) + 1 . ' 发送开盘');
        $controller->stopInfo(request()); //发送开盘消息
        cache()->put('robot-' . $this->argument('name'), ($value->id ?? cache('id')) + 1, 300);
        goto start;
        // }
    }
}
