<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\LottoModule\LottoUtils;

class LottoStatsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->date_start && $request->date_end) {
            $start = $request->date_start;
            $end   = $request->date_end;
        } else {
            $start = date('Y-m-d', strtotime('-30 days'));
            $end   = date('Y-m-d');
        }

        if ($end > date('Y-m-d')) {
            $end = date('Y-m-d');
        }

        $dates = $this->dateHandle('day', $start, $end);
        $items = [];
        foreach ($dates as $value) {
            $items[$value] = ['date' => $value];
        }

        $lotto_items = LottoUtils::lottoItems();

        foreach ($lotto_items as $lotto) {
            $lotto_name = $lotto['name'];
            // foreach ($play_types as $play_type) {
            foreach ($dates as $date) {
                $temp = $this->lottoStats($lotto_name, $date);
                foreach ($temp as $_key => $_value) {
                    $key                = $lotto_name . '_' . $_key;
                    $items[$date][$key] = toDecimal($_value);
                }
            }
            // }
        }

        $result = ['items' => array_values(array_reverse($items))];
        return real($result)->success();
    }

    private function dateHandle($type = 'day', $start_date, $end_date)
    {
        $params = [
            'day'   => 'Y-m-d',
            'month' => 'Y-m',
        ];
        $start_time = strtotime($start_date);
        $end_time   = strtotime($end_date);
        $result     = [];
        while ($start_time <= $end_time) {
            $result[]   = date($params[$type], $start_time);
            $start_time = strtotime('+1 ' . $type, $start_time);
        }

        return $result;
    }

    private function lottoStats($lotto_name, $date)
    {
        $cache_name = 'lottoStats:' . $lotto_name . $date;
        if ($date > date('Y-m-d')) {
            return (object) [
                'date'      => $date,
                'total'     => '0.000',
                'bonus'     => '0.000',
                'profit'    => '0.000',
                'effective' => '0.000',
            ];
        }

        $cache_sec = $date == date('Y-m-d') ? 600 : 8640000;
        return cache()->remember($cache_name, $cache_sec, function () use ($lotto_name, $date) {
            $raw_bet        = DB::raw('SUM(total) as total');
            $raw_bonus      = DB::raw('SUM(bonus) as bonus');
            $raw_profit     = DB::raw('(SUM(bonus) - SUM(total)) as profit');
            $raw_effective  = DB::raw('SUM(if(effective=1,total,0)) as effective');
            $raw_date       = DB::raw('date_format(`confirmed_at`,"%Y-%m-%d") as date');
            $raw_date_where = DB::raw('date_format(`confirmed_at`,"%Y-%m-%d")');

            $data = DB::table('bet_logs')->where('lotto_index', 'regexp', $lotto_name)
                ->where('trial', '0')
                ->where('status', '2')
                ->where($raw_date_where, $date)
                ->first([$raw_date, $raw_bet, $raw_bonus, $raw_profit, $raw_effective]);

            return $data;
        });
    }
}
