<?php

namespace App\Models\Robot;

use App\Models\User;
use App\Packages\Utils\PushEvent;
use Illuminate\Database\Eloquent\Model;
use App\Models\LottoModule\Models\LottoConfig;
use App\Models\LottoModule\Models\LottoChatHistory;

class AutoReChe extends Model
{
    public static function reche()
    {
        $probability = mt_rand(0, 100);
        if ($probability > 80) {
            dump('20%几率不查回');
            return false;
        }
        if (cache()->has('lotto-reche')) {
            $re_che     = cache()->get('lotto-reche');
            $type_array = [
                'return' => '下分',
                'check'  => '上分',
            ];
            foreach ($re_che as $key => $value) {
                $user = User::find($key);
                $text = '@' . $user->nickname;
                $text .= $type_array[$value['type']] . toDecimal($value['amount'], 3);
                $lotto_game = $value['lotto_game'];
                $room       = $value['room'];
                self::sendMessage($user, $lotto_game, $room, 8, $text, $value['amount'], true);
            }
            cache()->forget('lotto-reche');
        }
        $type_array = [
            'return' => '回',
            'check'  => '查',
        ];

        $take       = mt_rand(0, 100) > 80 ? 2 : 1;
        $lotto_game = LottoConfig::where('visible', 1)->inRandomOrder()->get()->toArray();
        if (empty($lotto_game)) {
            dump('没有开启的游戏');
            return false;
        }
        $robot = User::inRandomOrder()->where('robot', 1)->take($take)->get();
        $line  = [];
        foreach ($robot as $key => $value) {
            $lotto_game = $lotto_game[array_rand($lotto_game, 1)];
            dump('is:' . $lotto_game['name']);
            if (!self::needEnd($lotto_game['name'])) {
                return false;
            }
            $lotto_room = explode(',', $lotto_game['play_types']);
            $room       = $lotto_room[array_rand($lotto_room, 1)];

            $amount = mt_rand(1, 5) * 100;
            $type   = mt_rand(0, 100) > 80 ? 'return' : 'check';
            $detail = $type_array[$type] . ':' . $amount;
            //
            self::sendMessage($value, $lotto_game['name'], $room, 7, $detail, $amount);
            //
            if ($take > 1 && $key < 1) {
                sleep(mt_rand(10, 30));
            }
            $line[$value->id] = [
                'lotto_game' => $lotto_game['name'],
                'type'       => $type,
                'room'       => $room,
                'amount'     => $amount,
            ];
        }
        cache()->put('lotto-reche', $line, 600);
    }

    private static function needEnd($name)
    {
        if ($name != 'ca28') {
            return true;
        }
        $da = date('w');
        if ($da == 1 && (date('H') == 19 || date('H') == 20)) { //星期一晚上七八点不开
            dump('ca-end 7 - 8');
            return false;
        } elseif ($da != 1 && date('H') == 19) { //其他时间晚上七点不开
            dump('ca-end 7');
            return false;
        }
        return true;
    }

    private static function sendMessage($user, $lotto_game, $lotto_room, $cat = 7, $content = '', $amount = 0, $host = false)
    {
        $history_data           = new LottoChatHistory();
        $history_data->room     = $lotto_room;
        $history_data->avatar   = $host ? env('HOST_AVATAR') : $user->avatar;
        $history_data->nickname = $host ? env('HOST_NAME') : $user->nickname;
        $history_data->type     = $lotto_game;
        $history_data->wechat   = $host ? false : $user->wechat;
        $history_data->details  = ['content' => $content]; //$type_array[$type] . ':' . $amount;
        $history_data->lotto_id = 0;
        $history_data->total    = $amount;
        $history_data->cat      = $cat;
        $history_data->user_id  = $host ? 10000 : $user->id;
        $history_data->save();

        $result = [
            'message_type' => 'bet',
            'lotto_id'     => 0,
            'total'        => $amount,
            'id_hash'      => $host ? 10000 : $user->id,
            'play_type'    => $lotto_room,
            'details'      => ['content' => $content],
            'created_at'   => date('Y-m-d H:i:s'),
            'nickname'     => $host ? env('HOST_NAME') : $user->nickname,
            'avatar'       => $host ? env('HOST_AVATAR') : $user->avatar,
            'wechat'       => $host ? false : $user->wechat,
            'cat'          => $cat,
            'lotto_name'   => $lotto_game,
            'user_id'      => $host ? 10000 : $user->id,
        ];
        dump('user:' . $user->id);
        PushEvent::name('chatBet')->toPresence('lotto.' . $lotto_game)->data($result);
    }
}
