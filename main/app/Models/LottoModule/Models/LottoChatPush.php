<?php
namespace App\Models\LottoModule\Models;

use function App\Models\option;
use App\Packages\Utils\PushEvent;
use App\Models\LottoModule\LottoUtils;
use App\Models\LottoModule\Models\LottoConfig;
use App\Models\LottoModule\Models\LottoPlayType;

class LottoChatPush
{
    private $lotto_id = null;

    private $lotto_name = null;

    private $lotto_types = [];

    public function __construct($lotto_name = null, $lotto_id = null)
    {
        if ($lotto_name !== null) {
            $this->lotto_name  = $lotto_name;
            $this->lotto_id    = $lotto_id;
            $lotto_config      = LottoConfig::remember(6000)->find($this->lotto_name);
            $has_chat_types    = LottoPlayType::tplBaseNames('chat');
            $this->lotto_types = array_intersect($lotto_config->types, $has_chat_types);
        }
    }

    public function betClose()
    {
        $cache_name = 'LottoBetCloseMessage' . $this->lotto_name . $this->lotto_id;

        if (cache()->has($cache_name) === true) {
            return true;
        }
        foreach ($this->lotto_types as $type) {
            $message = [
                'message_type' => 'text',
                'id_hash'      => 'system',
                'avatar'       => option('master_service_avatar'),
                'nickname'     => option('web_title'),
                'message'      => '第<b class="color-red">' . $this->lotto_id . '</b>期已封盘',
                'play_type'    => $type,
            ];
            $push = PushEvent::name('chatBet')->toPresence('lotto.' . $this->lotto_name)->data($message);
        }
        cache()->put($cache_name, true, 6000);

        return true;
    }

    public function betOpen()
    {
        $cache_name = 'LottoBetOpenMessage' . $this->lotto_name . $this->lotto_id;
        if (cache()->has($cache_name) === true) {
            return true;
        }

        $lotto        = LottoUtils::Model($this->lotto_name);
        $latest_issue = $lotto->where('status', 1)->where('lotto_at', '>', date('Y-m-d H:i:s'))->orderBy('id', 'asc')->remember(2)->first();

        if ($latest_issue === null || $latest_issue->id !== $this->lotto_id) {
            return false;
        }

        foreach ($this->lotto_types as $type) {
            $message = [
                'message_type' => 'text',
                'id_hash'      => 'system',
                'avatar'       => option('master_service_avatar'),
                'nickname'     => option('web_title'),
                'message'      => '第<b class="color-red">' . $this->lotto_id . '</b>期已开始投注',
                'play_type'    => $type,
            ];
            $push = PushEvent::name('chatBet')->toPresence('lotto.' . $this->lotto_name)->data($message);
            $this->updateChatLog($message);
        }
        cache()->put($cache_name, true, 6000);

        return true;
    }

    public function lottoOpen($data)
    {
        usleep(mt_rand(100000, 999999));
        $cache_name = 'LottoOpenCodeMessage' . $this->lotto_name . $this->lotto_id;
        if (cache()->has($cache_name) === true) {
            return true;
        }

        foreach ($this->lotto_types as $type) {
            $data['lotto_id'] = $this->lotto_id;
            $message          = [
                'message_type' => 'open_code',
                'id_hash'      => 'system',
                'avatar'       => option('master_service_avatar'),
                'nickname'     => option('web_title'),
                'message'      => '第' . $this->lotto_id . '期已开奖',
                'play_type'    => $type,
                'data'         => $data,
            ];
            $push = PushEvent::name('chatBet')->toPresence('lotto.' . $this->lotto_name)->data($message);
            $this->updateChatLog($message);
        }
        cache()->put($cache_name, true, 6000);
    }

    public function notice()
    {
        foreach ($this->lotto_types as $type) {
            $message = [
                'message_type' => 'notice',
                'id_hash'      => 'system',
                'avatar'       => option('master_service_avatar'),
                'nickname'     => option('web_title'),
                'message'      => '这里是通知消息',
                'play_type'    => $type,
            ];

            $push = PushEvent::name('chatBet')->toPresence('lotto.' . $this->lotto_name)->data($message);

            $this->updateChatLog($message);
        }
    }

    public function userBet($message, $lotto_name = null)
    {
        // $this->notice();
        $push = PushEvent::name('chatBet')->toPresence('lotto.' . $this->lotto_name)->data($message);
        $this->updateChatLog($message);
    }

    private function updateChatLog($data)
    {
        if (isset($data['play_type']) === false) {
            return false;
        }
        $cache_name = 'LottoChatLog:' . $this->lotto_name;

        $result = [];
        if (cache()->has($cache_name) === true) {
            $result = cache()->get($cache_name);
        }

        $play_type = $data['play_type'];

        if (isset($result[$play_type]) && count($result[$play_type]) > 100) {
            array_shift($result[$play_type]);
        }

        $result[$play_type][] = $data;

        cache()->put($cache_name, $result, 86400);

        return true;
    }
}
