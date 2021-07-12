<?php

namespace App\Models\UserWallet;

use App\Models\User;
use App\Models\RechargeAisle;
use App\Models\UserWallet\Wallet;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class BalanceRecharge extends Model implements Auditable
{
    use AuditableTrait;

    protected $appends = ['channel_info', 'can_cancel'];

    protected $casts = ['amount' => 'decimal:3', 'extend' => 'array'];

    protected $connection = 'main_sql';

    protected $fillable = ['user_id', 'amount', 'award', 'status', 'channel', 'name', 'remark', 'extend', 'canceled_at', 'confirmed_at'];

    protected $table = 'balance_recharges';

    public function getCanCancelAttribute()
    {
        $user = User::find($this->user_id);
        if ($this->status == 2 && date('Y-m-d H:i:s', time() - 300) <= $this->created_at
        ) {
            return true;
        }
        return false;
    }

    public function getChannelInfoAttribute()
    {
        // $channels = config('app.recharge.channel');
        return RechargeAisle::where('type', $this->channel)->first();
        // return $channels[$this->channel];
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'user_id', 'user_id');
    }
}
