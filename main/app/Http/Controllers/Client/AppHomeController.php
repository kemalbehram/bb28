<?php

namespace App\Http\Controllers\Client;

use App\Models\User;
use App\Models\Article;
use function App\Models\option;
use App\Http\Controllers\Controller;
use App\Models\LottoModule\Models\LottoConfig;

class AppHomeController extends Controller
{
    public function home()
    {
        $result = [
            'news'      => $this->news(),
            'promotion' => $this->promotion(),
            'winner'    => $this->winner(),
        ];

        return real($result)->success();
    }

    private function news()
    {
        $fields = ['title', 'id', 'created_at'];
        $data   = Article::query()->where('status', 1)->where('cat_id', '1001');
        $data->orderBy('id', 'desc');
        $data->remember(600);
        return $data->get($fields);
    }

    private function promotion()
    {
        return [
            'image'      => option('web_home_promotion_image'),
            'title'      => option('web_home_promotion_title'),
            'created_at' => option('web_home_promotion_time'),
            'route'      => [
                'name' => 'promotion',
            ],
        ];
    }

    private function winner()
    {
        $users = User::where('robot', 1)->take(50)->get('nickname');

        $name   = explode(',', option('master_mock_winner'));
        $lotto  = LottoConfig::whereIn('name', $name)->get(['name', 'title'])->toArray();
        $result = [];

        foreach ($users as $value) {
            $amount   = mt_rand(1000, 3000000);
            $amount   = sprintf('%01.3f', $amount / 1000);
            $rand     = mt_rand(0, count($name) - 1);
            $result[] = [
                'nickname' => $value['nickname'],
                'bonus'    => $amount,
                'lotto'    => $lotto[$rand]['title'],
            ];
        }

        return $result;
    }
}
