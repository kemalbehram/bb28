<?php

namespace App\Http\Controllers\Client;

use App\Models\Option;
use App\Models\Article;
use App\Models\OptionFocus;
use App\Models\RechargeAisle;
use function App\Models\option;
use App\Models\UserRebateConfig;
use App\Http\Controllers\Controller;
use App\Models\LottoModule\LottoUtils;
use App\Models\ModelTrait\ThisUserTrait;
use App\Models\LottoModule\Models\LottoConfig;
use App\Models\LottoModule\Models\LottoPlayType;

class OptionController extends Controller
{
    use ThisUserTrait;

    public function focus($scope)
    {
        $data = OptionFocus::query();
        $data->remember(600);
        $data->where('scope', 'regexp', $scope);
        $data->orderBy('id', 'desc');
        $fields = ['image', 'mapping', 'params'];
        return $data->get($fields)->toArray();
    }

    public function get()
    {
        $lotto                    = config('lotto.web');
        $result                   = [];
        $result['pc_home_focus']  = $this->focus('pc_home');
        $result['wap_home_focus'] = $this->focus('wap_home');
        $result['lotto_items']    = $this->lottoItems();

        $result['play_types']     = LottoPlayType::getFormatResult();
        $result['refer_intro']    = format_html(Option::remember(3600)->find('refer_intro')->value);
        $result['service_qq']     = explode_break(Option::remember(3600)->find('service_qq')->value);
        $result['web_title']      = option('web_title');
        $result['web_lawyer']     = Option::remember(3600)->find('web_lawyer')->value;
        $result['web_copyright']  = format_html(Option::remember(3600)->find('web_copyright')->value);
        $result['icon']           = 'http://ooo28-public.oss-cn-hangzhou.aliyuncs.com/yl28/config/icon-transparent.png';
        $result['recharge_aisle'] = $this->getRechargeAisle();
        $result['withdraw_aisle'] = $this->getWithDrawAisle();
        $result['usdt_rate']      = option('usdt_rate');
        $result['notice']         = $this->getNotice();
        $result['auth_url']       = config('wechat.auth_url');
        $result['domain_url']     = config('wechat.domain_url');

        return real($result)->success();
    }

    public function rebateConfig()
    {
        $items = UserRebateConfig::where('id', 1)->orWhere('id', 2)->get()->toArray();
        return $items;
    }

    //下期开奖
    private function betNewest($lotto_name)
    {
        $lotto  = $lotto_name;
        $config = LottoConfig::remember(600)->find($lotto);

        $datetime = date('Y-m-d H:i:s', time() + $config->stop_ahead);
        $model    = LottoUtils::model($lotto);

        $data = $model->where('status', 1)->where('lotto_at', '>', $datetime)->get();

        if (count($data) === 0) {
            $result = ['items' => []];
            return real($result)->success();
        }

        $data->makeHidden(['open_code', 'status', 'mark', 'opened_at', 'lotto_name', 'win_extend', 'updated_at']);

        $result = ['items' => $data->toArray()];

        return real($result)->success();
    }

    private function getNotice()
    {
        $model          = new Article();
        $items          = [];
        $items['index'] = $model->where('status', 1)->where('type', 'index')->orderBy('created_at', 'desc')->get();
        $items['scroll'] = $model->where('status', 1)->where('type', 'scroll')->orderBy('created_at', 'desc')->get();
        $items['game']  = $model->where('status', 1)->where('type', 'game')->orderBy('created_at', 'desc')->first();
        return $items;
    }

    private function getRechargeAisle()
    {
        $model  = new RechargeAisle();
        $items  = $model->where('status', 1)->get()->toArray();
        $result = [];
        foreach ($items as $value) {
            $result[$value['type']] = $value;
        }
        return $result;
    }

    private function getWithDrawAisle()
    {
        $items = [
            'bank'   => [
                'name'   => '银联提现',
                'status' => option('bank'),
            ],
            'wechat' => [
                'name'   => '微信提现',
                'status' => option('wechat'),
            ],
            'alipay' => [
                'name'   => '支付宝提现',
                'status' => option('alipay'),
            ],
            'usdt'   => [
                'name'   => 'USDT泰达币提现',
                'status' => option('usdt'),
            ],
        ];
        $result = [];
        foreach ($items as $key => $value) {
            if ($value['status'] != 1) {
                continue;
            }
            $result[$key] = $value;
        }
        return $result;
    }

    private function lottoItems()
    {
        $items = LottoConfig::where('visible', '1')->orderBy('sort', 'asc')->get();

        $result = [];

        foreach ($items as $item) {
            $result[$item->name] = [
                'name'      => $item->name,
                'icon'      => $item->icon_font,
                'title'     => $item->title,
                'subtitle'  => $item->subtitle,
                'types'     => $item->types,
                'hot'       => $item->hot,
                'visible'   => $item->visible,
                'group'     => $item->group,
                'intro'     => $item->intro,
                'bg'        => $item->bg,
                'stop'      => false,
                'is_stop'   => false,
                'room_icon' => $item->room_icon,
                'play_now'  => rand(100, 1000),
            ];
        }
        return $result;
    }
}
