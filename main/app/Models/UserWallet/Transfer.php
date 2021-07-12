<?php

namespace App\Models\UserWallet;

use App\Models\User;
use App\Models\Option;
use App\Models\UserOption;
use function App\Models\option;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Transfer extends Model implements Auditable
{
    use AuditableTrait;

    protected $appends = ['source', 'can_cancel', 'target_id', 'user_id'];

    protected $casts = ['amount' => 'decimal:3', 'award_ag_base' => 'decimal:3', 'fee' => 'decimal:3', 'award' => 'array', 'extend' => 'array'];

    protected $connection = 'main_sql';

    protected $fillable = ['drawee_id', 'payee_id', 'amount', 'award', 'type', 'transfer_fee', 'status', 'canceled_at', 'extend'];

    protected $table = 'user_wallet_transfers';

    public function feeLimit($user_id)
    {
        $fee_model = env('TRANSFER_FEE_MODEL', 'base');
        $result    = [];

        $user_option = UserOption::find($user_id);
        $user_model  = $user_option->transfer_fee_model;

        if ($user_model !== null) {
            $fee_model = $user_model;
        }

        if ($fee_model === 'base') {
            $result = $this->feeLimitBase($user_id);
        }

        if ($fee_model === 'bet') {
            $result = $this->feeLimitBet($user_id);
        }

        $title                 = ['base' => '倍数模式', 'bet' => '流水模式'];
        $result['fee_model']   = $fee_model;
        $result['model_title'] = $title[$fee_model];
        return $result;
    }

    public function feeLimitBase($user_id)
    {
        $user = User::find($user_id);

        $fee_rate = option('transfer_fee_rate');

        if ($user->agent->status) {
            $result = [
                'fee_rate'   => $fee_rate,
                'bet_rate'   => 0,
                'fee_limit'  => 0,
                'bet_usable' => 0,
                'bet_need'   => 0,
            ];
            return $result;
        }

        $option = $user->option;
        $limit  = $option->fee_limit;
        $limit  = $limit > 0 ? $limit : 0;

        $fee_limit = $option->fee_limit > 0 ? $option->fee_limit : 1;

        $bet_need = ($option->fee_limit * 4) - ($option->bet_usable);

        if ($bet_need <= 0) {$bet_need = 0;}

        $result = [
            'fee_rate'   => $fee_rate,
            'bet_rate'   => toDecimal($option->bet_usable / $fee_limit),
            'fee_limit'  => $option->fee_limit,
            'bet_usable' => $option->bet_usable,
            'bet_need'   => toDecimal($bet_need),
        ];

        return $result;
    }

    public function feeLimitBet($user_id)
    {
        $user     = User::find($user_id);
        $fee_rate = option('transfer_fee_rate');

        if ($user->agent->status) {
            $result = [
                'fee_rate'   => $fee_rate,
                'free_trans' => 0,
                'bet_usable' => 0,
            ];
            return $result;
        }

        $option = $user->option;
        $result = [
            'fee_rate'   => $fee_rate,
            'free_trans' => toDecimal($option->bet_usable / 3),
            'bet_usable' => $option->bet_usable,
        ];

        return $result;
    }

    public function getAmountAttribute($value)
    {
        if ($this->source == 'outcome') {
            return '-' . $value;
        } else {
            return $value;
        }
    }

    public function getCanCancelAttribute()
    {
        $cache_name = 'TransferCanCancelUserID:' . $this->user_id;
        if (!$this->user_id) {return false;}
        $agent = cache()->remember($cache_name, 60, function () {
            $user = User::find($this->user_id);
            return $user->agent;
        });
        if (
            $this->source === 'outcome'
            && $agent->status === true
            && $this->status == 2
            && $this->type !== 'agent.to.agent'
            && date('Y-m-d H:i:s', time() - 300) <= $this->created_at
        ) {
            return true;
        }
        return false;
    }

    public function getSourceAttribute()
    {
        if ($this->drawee_id == $this->user_id) {
            return 'outcome';
        }

        if ($this->payee_id == $this->user_id) {
            return 'income';
        }

        return null;
    }

    public function getTargetIdAttribute()
    {
        $target_id = null;
        $ids       = [$this->drawee_id, $this->payee_id];
        if (in_array($this->user_id, $ids)) {
            $current   = [$this->user_id];
            $diff      = array_diff($ids, $current);
            $target_id = array_shift($diff);
        }

        return $target_id;
    }

    public function getUserIdAttribute()
    {
        return request()->user_id;
    }

    public function targetInfo()
    {
        return $this->hasOne(User::class, 'id', 'target_id')->select(['id', 'nickname']);
    }
}
