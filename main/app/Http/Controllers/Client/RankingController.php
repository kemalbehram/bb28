<?php

namespace App\Http\Controllers\Client;

use App\Models\RankMock;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RankingController extends Controller
{
    public function home()
    {
        $today     = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $result    = [
            'today'     => $this->days($today),
            'yesterday' => $this->days($yesterday, true),
            'last_7'    => $this->last7Days(),
        ];

        return real($result)->success();
    }

    private function days($date, $bonus = false)
    {
        $cache_key = 'betRank' . $date . $bonus;
        return cache()->remember($cache_key, 600, function () use ($date, $bonus) {
            $model = new RankMock();
            $mocks = $model->where('date', $date)->orderBy('total', 'desc')->get(['user_id', 'total']);

            $raw_date  = DB::raw('date_format(`confirmed_at`,"%Y-%m-%d")');
            $raw_total = DB::raw('(sum(bonus)  - sum(total)) AS total');

            $user = DB::table('bet_logs')
                ->where('status', 2)
                ->where('trial', 0)
                ->where($raw_date, $date)
                ->groupBy('user_id')
                ->get(['user_id', $raw_total])
                ->map(function ($value) {
                    return (array) $value;
                });

            $ranks = array_merge($user->toArray(), $mocks->toArray());
            $temp  = array_column($ranks, 'total');
            array_multisort($temp, SORT_DESC, $ranks);

            $ranks = array_slice($ranks, 0, 30);
            $ids   = array_column($ranks, 'user_id');

            $users = DB::table('users')->whereIn('id', $ids)->get(['id', 'nickname']);
            $users = array_column($users->toArray(), null, 'id');

            $index     = 1;
            $bonus_arr = $model->rankBonus();
            foreach ($ranks as &$rank) {
                if ($bonus === true) {
                    $rank['bonus'] = $bonus_arr[$index];
                }
                $user_id          = $rank['user_id'];
                $rank['nickname'] = $users[$user_id]->nickname;
                $index += 1;
            }
            return $ranks;
        });
    }

    private function last7Days()
    {
        $cache_key = 'betRankLast7Days';
        return cache()->remember($cache_key, 600, function () {
            $model     = new RankMock();
            $yesterday = date('Y-m-d', strtotime('-1 day'));
            $days_7    = date('Y-m-d', strtotime('-8 day'));

            $raw_date = DB::raw('date_format(`confirmed_at`,"%Y-%m-%d")');

            $raw_total = DB::raw('sum(total) AS total');
            $mocks     = $model
                ->where('date', '<=', $yesterday)
                ->where('date', '>=', $days_7)
                ->groupBy('user_id')
                ->get(['user_id', $raw_total]);

            $raw_total = DB::raw('(sum(bonus)  - sum(total)) AS total');
            $user      = DB::table('bet_logs')
                ->where($raw_date, '<=', $yesterday)
                ->where($raw_date, '>=', $days_7)
                ->where('status', 2)
                ->where('trial', 0)
                ->groupBy('user_id')
                ->get(['user_id', $raw_total])
                ->map(function ($value) {
                    return (array) $value;
                });

            $ranks = array_merge($user->toArray(), $mocks->toArray());
            $temp  = array_column($ranks, 'total');
            array_multisort($temp, SORT_DESC, $ranks);

            $ranks = array_slice($ranks, 0, 30);
            $ids   = array_column($ranks, 'user_id');

            $users = DB::table('users')->whereIn('id', $ids)->get(['id', 'nickname']);
            $users = array_column($users->toArray(), null, 'id');

            $index     = 1;
            $bonus_arr = $model->rankBonus();
            foreach ($ranks as &$rank) {
                $user_id          = $rank['user_id'];
                $rank['nickname'] = $users[$user_id]->nickname;
                $index += 1;
            }
            return $ranks;
        });
    }
}
