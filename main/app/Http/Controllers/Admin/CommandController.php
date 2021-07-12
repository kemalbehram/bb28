<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Packages\Utils\PushEvent;
use App\Http\Controllers\Controller;
use App\Models\LottoModule\LottoBonus;

class CommandController extends Controller
{
    public function lottoBonus(Request $request)
    {
        if ($request->name == null || $request->id == null) {
            return real()->error('参数错误');
        }

        sleep(2);
        $lotto_index = $request->name . ':' . $request->id;
        dump($lotto_index);
        $bonus = new LottoBonus();
        $data  = $bonus->batch($lotto_index);

        $cache_name = 'LottoBonusCommand';
        if (count($data) > 0 && cache()->has($cache_name) === false && cache()->has('lottoBonusNotice') === false) {
            $message = ['audio' => 'lotto_bet_open'];
            PushEvent::name('notice')->toUser(100000)->data($message);
            cache()->put($cache_name, true, 2);
        }
    }
}
