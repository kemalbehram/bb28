<?php

namespace App\Models;

use App\Packages\Utils\PushEvent;
use Illuminate\Database\Eloquent\Model;

class RiskCenter extends Model
{
    protected $connection = 'main_sql';

    protected $fillable = ['content', 'model_name', 'model_id', 'level'];

    protected $table = 'risk_center';

    public function lottoBetNotice($params)
    {
        $cache_name = 'lottoBetNotice';
        extract($params);
        $content = '用户：' . $user_id . ' / ' . $nickname . ' 投注了。金额：' . $log_total . '。 流水编号：' . $log_id;
        $content = '用户详情：' . $user_id . ' / ' . $nickname . '<br>投注金额：<span class="color-red">' . $log_total . '</span><br>流水编号：' . $log_id;
        $message = [
            'message'  => '风险提醒 - <span class="color-red">大额投注</span>',
            'desc'     => $content,
            'duration' => 20,
            'audio'    => 'lotto_bet',
        ];

        if (cache()->has($cache_name)) {
            unset($message['audio']);
        }

        cache()->put($cache_name, true, 2);
        PushEvent::name('notice')->toUser(100000)->data($message);
    }

    public function lottoBonusNotice($params)
    {
        $cache_name = 'lottoBonusNotice';
        extract($params);
        $content = '用户：' . $user_id . ' / ' . $nickname . ' 单注盈利过大。盈利金额：' . $log_total . '。 流水编号：' . $log_id;

        $data = [
            'content' => $content,
            'level'   => 1,
        ];
        $this->create($data);

        $content = '用户详情：' . $user_id . ' / ' . $nickname . '<br>盈利金额：<span class="color-red">' . $log_total . '</span><br>流水编号：' . $log_id;
        $message = [
            'message'  => '风险提醒 - <span class="color-red">盈利过大</span>',
            'desc'     => $content,
            'duration' => 20,
            'audio'    => 'lotto_win',

        ];

        if (cache()->has($cache_name)) {
            unset($message['audio']);
        }

        cache()->put($cache_name, true, 2);
        PushEvent::name('notice')->toUser(100000)->data($message);
    }
}
