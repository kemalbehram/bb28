<?php

namespace App\Models\UserWallet;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Watson\Rememberable\Rememberable;
use Illuminate\Database\Eloquent\Model;

class TransferData extends Model
{
    use Rememberable;

    protected $casts = ['extend' => 'array'];

    protected $connection = 'main_sql';

    protected $rememberCacheTag = 'TransferData';

    protected $table = 'user_wallet_transfer_extends';

    public function dateHandle($type = 'day', $start_date, $end_date)
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

    public function draweeUser()
    {
        return $this->hasOne(User::class, 'id', 'drawee_id')->remember(600);
    }

    public function payeeUser()
    {
        return $this->hasOne(User::class, 'id', 'payee_id')->remember(600);
    }

    public function statsAgent($type, $user_id = null, $start = null, $end = null)
    {
        $dates  = array_reverse($this->dateHandle($type, $start, $end));
        $result = [];
        $raws   = [
            'day'   => 'date_format(`created_at`,"%Y-%m-%d")',
            'month' => 'date_format(`created_at`,"%Y-%m")',
        ];

        $raw = DB::raw($raws[$type]);
        foreach ($dates as $value) {
            $income = TransferData::where($raw, $value)
                ->where('status', 2)
                ->where('payee_id', $user_id)
                ->first(DB::raw('sum(amount) as income'));

            $outcome = TransferData::where($raw, $value)
                ->where('status', 2)
                ->where('drawee_id', $user_id)
                ->first(
                    [
                        DB::raw('sum(amount) as outcome'),
                        DB::raw('sum(award_agent_base) as award_agent_base'),
                        DB::raw('sum(award_agent_refer) as award_agent_refer'),
                    ]
                );

            $award = $outcome->award_agent_base + $outcome->award_agent_refer;
            $data  = [
                'date'      => $value,
                'user_id'   => $user_id,
                'income'    => $income->income ?: '0.000',
                'award_in'  => $income->income ? toDecimal($income->income * 0.02) : '0.000',
                'outcome'   => $outcome->outcome ?: '0.000',
                'award_out' => $award ? sprintf('%01.3f', $award) : '0.000',
            ];

            $data['award_total'] = toDecimal($data['award_in'] + $data['award_out']);
            $result[]            = $data;
        }

        return $result;
    }
}
