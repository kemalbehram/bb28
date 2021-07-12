<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataStatsFix extends Model
{
    public $mapping = [
        'wallet_recharge'            => '充值修正',
        'wallet_withdraw'            => '提现修正',
        'red_bag_returned'           => '红包退回修正',
        'transfer_award_agent_base'  => '转账代理基本提成',
        'transfer_award_agent_refer' => '转账代理直属提成',
        'transfer_award_user_base'   => '转账用户奖励',
        'transfer_payee'             => '转账收入',
        'transfer_drawee'            => '转账支出',
    ];

    protected $appends = ['title'];

    protected $connection = 'main_sql';

    protected $fillable = ['admin_id', 'name', 'date', 'value', 'user_id', 'remark'];

    protected $table = 'data_stats_fixes';

    public function getTitleAttribute()
    {
        return $this->mapping[$this->name];
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
