<?php

namespace App\Http\Controllers\Admin;

use App\Models\Option;
use App\Models\AdminOpt;
use App\Models\DataStatsFix;
use function App\Models\option;
use App\Http\Controllers\Controller;
use App\Models\LottoModule\Models\LottoConfig;
use App\Models\LottoModule\Models\LottoPlayType;

class ConfigController extends Controller
{
    public function get()
    {
        $focus = [
            'items' => config('app.focus.items'),
            'scope' => config('app.focus.scope'),
        ];

        $result = [
            'focus_mapping'          => $focus['items'],
            'focus_scope'            => $focus['scope'],
            'lotto_items'            => $this->lottoItems(),
            'lotto_control'          => explode(',', option('master_lotto_control')),
            'audio'                  => $this->audio(),
            'lotto_waning'           => config('lotto.warning'),

            'play_types'             => LottoPlayType::getFormatResult(),
            'web_title'              => option('web_title'),
            'data_fix_label'         => $this->dataFixLabel(),
            'admin_opts'             => $this->adminOpts(),
            'user_query_default'     => option('user_query_default'),
            'service_avatar'         => option('master_service_avatar'),
            'usdt_rate'              => option('usdt_rate'),
            'transfer_model_setting' => option('master_trans_dual_mode', 'bool', false),
            'stats_total'            => config('app.admin.stats_total'),
            'stats_simple'           => config('app.admin.stats_simple'),
            'stats_all'              => config('app.admin.stats_all'),
        ];

        return real($result)->success();
    }

    private function adminOpts()
    {
        $model = new AdminOpt();

        $result = $model->mapping;

        return $result;
    }

    private function audio()
    {
        $result = [
            'message'                => 'https://ooo28-public.oss-accelerate.aliyuncs.com/admin-beep/message.mp3',
            'withdraw'               => 'https://ooo28-public.oss-accelerate.aliyuncs.com/admin-beep/withdraw.mp3',
            'recharge'               => 'https://ooo28-public.oss-accelerate.aliyuncs.com/admin-beep/recharge.mp3',
            'lotto_bet'              => 'https://ooo28-public.oss-accelerate.aliyuncs.com/admin-beep/lotto-bet.mp3',
            'lotto_win'              => 'https://ooo28-public.oss-accelerate.aliyuncs.com/admin-beep/lotto-win.mp3',
            'lotto_bet_open'         => 'https://ooo28-public.oss-accelerate.aliyuncs.com/admin-beep/lotto-bet-open.mp3',
            'player_wallet_recharge' => 'https://ooo28-public.oss-accelerate.aliyuncs.com/admin-beep/player-wallet-recharge.mp3',
            'player_wallet_withdraw' => 'https://ooo28-public.oss-accelerate.aliyuncs.com/admin-beep/player-wallet-withdraw.mp3',
            'award_receive'          => 'https://ooo28-public.oss-accelerate.aliyuncs.com/admin-beep/award-receive.mp3',
            'wallet_diff'            => 'https://ooo28-public.oss-accelerate.aliyuncs.com/admin-beep/wallet-diff.mp3',
        ];

        return $result;
    }

    private function dataFixLabel()
    {
        $model = new DataStatsFix();

        $result = $model->mapping;

        return $result;
    }

    private function lottoItems()
    {
        $items = LottoConfig::whereIn('name', ['ca28', 'ylde28'])->orderBy('sort', 'asc')->get();

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
                'web_site' => $item->web_site,
            ];
        }

        return $result;
    }
}
