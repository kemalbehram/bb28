<?php

namespace App\Models\LottoModule\Models;

use App\Models\User;
use App\Packages\Utils\PushEvent;
use App\Models\LottoModule\LottoUtils;
use App\Models\LottoModule\Models\LottoPlayType;

class BetMock
{
    private $has_types = [];

    private $lotto_name = null;

    private $play_types = [];

    private $stop_ahead = 0;

    public function __construct($lotto_name)
    {
        $this->lotto_name = $lotto_name;
        $items            = $this->lottoItems();
        $this->has_types  = $items[$lotto_name]['types'];

        // dd($this->has_types);
        $play_types = LottoPlayType::getFormatResult();

        $tools = config('lotto.tools');

        foreach ($this->has_types as $name) {
            $data = $play_types[$name];
            if (isset($data['tools'][0])) {
                $use_tool     = $data['tools'][0];
                $data['mock'] = $tools[$use_tool]['action'];
                unset($data['title'], $data['tools']);
            } else {
                $data['mock'] = [];
            }

            foreach ($data['mock'] as $value) {
                $value = $value['place'];
            }
            $this->play_types[$name] = $data;
        }
    }

    public function execute($stop_ahead)
    {
        // $h=date("H");
        // if($h==7){

        // }
        $this->stop_ahead = $stop_ahead;
        foreach ($this->has_types as $type) {
            $mock = $this->play_types[$type]['mock'];

            if (count($mock) > 0) {
                $rand  = mt_rand(0, count($mock) - 1);
                $place = $mock[$rand];
            } else {
                $place = $this->play_types[$type]['place'];
                $place = array_rand($place, mt_rand(1, 8));
            }

            if (!$place) {
                continue;
            }

            $total = $this->randTotal($type);

            $this->mock($type, $place, $total, $this->lotto_name);
        }
    }

    public function lottoItems()
    {
        return cache()->remember('betMockLottoItems', 3600, function () {
            $items  = LottoConfig::where('visible', 1)->orderBy('sort', 'asc')->get();
            $result = [];
            foreach ($items as $item) {
                $result[$item->name] = [
                    'name'     => $item->name,
                    'icon'     => $item->icon_font,
                    'title'    => $item->title,
                    'subtitle' => $item->subtitle,
                    'types'    => $item->types,
                    'hot'      => $item->hot,
                    'visible'  => $item->visible,
                    'group'    => $item->group,
                    'intro'    => $item->intro,
                ];
            }

            return $result;
        });
    }

    public function mock($type, $place, $pre_total, $lotto_name)
    {
        $model       = LottoUtils::model($this->lotto_name);
        $cache_lotto = cache()->get('robot-' . $this->lotto_name);
        $lotto       = $model->where('status', 1)->where('id', $cache_lotto)->whereNull('open_code')->orderBy('lotto_at', 'asc')->first();
        $cache_name  = 'pole:' . $lotto->id;
        if (!cache()->has($cache_name)) {
            cache()->put($cache_name, 0, 1800);
        }
        if ($lotto == null) {
            return false;
        }
        $surplus_time = strtotime($lotto->lotto_at) - time() - $this->stop_ahead;
        dump($this->lotto_name . '可投注秒数：' . $surplus_time);
        if ($surplus_time < 0) {
            return false;
        }

        $pre_total = $pre_total * 1000;
        $init      = $this->play_types[$type]['place'];
        $init      = array_slice($init, 0, 10);

        $rand      = mt_rand(0, 100) > 80 ? 2 : 1;
        $rand_init = array_rand($init, $rand);
        $total     = 0;
        if (!is_array($rand_init)) {
            $rand_init = [$rand_init];
        }
        if ($rand_init == [0, 1] || $rand_init == [2, 3]) {
            $rand_init = [$rand_init[0]];
        }
        // dd($init);
        foreach ($rand_init as $rval) {
            // $amount = mt_rand(5, 100) * 10;
            $amount = mt_rand(0, 100) > 80 ? mt_rand(50, 100) * 10 : mt_rand(5, 50) * 10;
            if ($rval == 8 || $rval == 9) {
                if (cache()->get($cache_name) >= 2) {
                    return false;
                }
                cache()->increment($cache_name);
                $amount = mt_rand(5, 9) * 10;
            }
            $amount += mt_rand(0, 100) > 70 ? mt_rand(0, 10) : 0;
            $total += $amount;
            $details[] = [
                'place'  => $init[$rval]['place'],
                'amount' => toDecimal($amount),
                'title'  => $init[$rval]['title'],
                'name'   => $init[$rval]['name'],
            ];
        }

        $user = User::inRandomOrder()->where('robot', 1)->first();

        $history_data           = new LottoChatHistory();
        $history_data->room     = $type;
        $history_data->avatar   = $user->avatar;
        $history_data->nickname = $user->nickname;
        $history_data->type     = $this->lotto_name;
        $history_data->wechat   = $user->wechat;
        $history_data->details  = $details;
        $history_data->lotto_id = $lotto->id;
        $history_data->total    = $total;
        $history_data->cat      = 1;
        $history_data->user_id  = $user->id;
        $history_data->save();

        $result = [
            'message_type' => 'bet',
            'lotto_id'     => $lotto->id,
            'total'        => $total,
            'id_hash'      => $user->id_hash,
            'play_type'    => $type,
            'details'      => $details,
            'created_at'   => date('Y-m-d H:i:s'),
            'nickname'     => $user->nickname,
            'avatar'       => $user->avatar,
            'wechat'       => $user->wechat,
            'cat'          => 1,
            'lotto_name'   => $this->lotto_name,
            'user_id'      => $user->id,
        ];

        PushEvent::name('chatBet')->toPresence('lotto.' . $this->lotto_name)->data($result);
    }

    public function randTotal($name)
    {
        $mapping = [
            'fd'  => 3000,
            'he'  => 3000,
            'ww'  => 1000,
            'el'  => 1200,
            'st'  => 1500,
            'ts'  => 1000,
            'cha' => 1000,
            'smp' => 1200,
            'dwd' => 1200,
            'qth' => 2000,
            'qzh' => 2000,
        ];

        if (isset($mapping[$name])) {
            $max = $mapping[$name] * 10;
            $min = intval($max * 0.1);
        } else {
            $max = 10000;
            $min = 1000;
        }

        return bcmul(mt_rand($min, $max), 0.1, 2);
    }
}
