<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedBag extends Model
{
    protected $appends = ['returned'];

    protected $connection = 'main_sql';

    protected $fillable = ['total', 'type', 'quantity', 'code', 'received', 'create_id', 'condition', 'recharge_amount', 'bet_amount', 'receive_id', 'expired_at', 'canceled_at'];

    protected $table = 'red_bags';

    public function getAmountAttribute()
    {
        if ($this->type == 'unique') {
            // return toDecimal($this->total / $this->quantity);
            return $this->total;
        }

        if ($this->type == 'turnover') {
            $fee = $this->total - $this->received;
            $qua = $this->quantity - count($this->logs->toArray());

            return $this->redLuck($fee, $qua);
        }
    }

    public function getReturnedAttribute()
    {
        if ($this->canceled_at == null) {
            return null;
        }

        return toDecimal($this->total - $this->received);
    }

    public function logs()
    {
        return $this->hasMany(RedBagLog::class, 'parent_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'create_id');
    }

    private function redLuck($red_total_money, $red_num)
    {
        // if ($total < $qua * 0.01) {
        //     return false;
        // }

        // $rand_arr = [];
        // for ($i = 0; $i < $qua; $i++) {
        //     $rand       = rand(1, 100);
        //     $rand_arr[] = $rand;
        // }

        // $rand_sum = array_sum($rand_arr);
        // $result   = [];
        // $result   = array_pad($result, $qua, 0.01); //保证每个红包至少0.01

        // foreach ($rand_arr as $key => $r) {
        //     $rand_money = number_format($total * $r / $rand_sum, 2);

        //     if ($rand_money <= 0.01 || number_format(array_sum($result), 2) >= number_format($total, 2)) {
        //         $result[$key] = 0.01;
        //     } else {
        //         $result[$key] = $rand_money;
        //     }
        // }

        // $max_index = $max_rand = 0;
        // foreach ($result as $key => $rm) {
        //     if ($rm > $max_rand) {
        //         $max_rand  = $rm;
        //         $max_index = $key;
        //     }
        // }

        // unset($result[$max_index]);
        // //这里的array_sum($result  )一定是小于$total的
        // $result[$max_index] = number_format($total - array_sum($result), 2);

        // ksort($result);
        // return $result[0];
        //1 声明定义最小红包值
        $red_min = 0.01;

        //2 声明定义生成红包结果
        $result_red = [];

        //3 惊喜红包计算
        for ($i = 1; $i < $red_num; $i++) {
            //3.1 计算安全上限 | 保证每个人都可以分到最小值
            $safe_total = ($red_total_money - ($red_num - $i) * $red_min) / ($red_num - $i);
            //3.2 随机取出一个数值
            $red_money_tmp = mt_rand($red_min * 100, $safe_total * 100) / 100;
            //3.3 将金额从红包总金额减去
            $red_total_money -= $red_money_tmp;
            $result_red[] = $red_money_tmp;
        }

        //4 最后一个红包
        $result_red[] = $red_total_money;

        return $result_red[0]; //
    }
}
