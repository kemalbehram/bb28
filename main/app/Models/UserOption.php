<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class UserOption extends Model implements Auditable
{
    use AuditableTrait;

    protected $casts = ['bet_chat' => 'bool', 'bet_hot' => 'bool', 'bet_miss' => 'bool', 'award_receive' => 'bool', 'user_check' => 'bool', 'block_time' => 'bool'];

    protected $connection = 'main_sql';

    protected $fillable = ['user_id', 'bet_chat', 'bet_hot', 'bet_miss', 'fd_tax', 'fee_limit', 'bet_usable', 'bet_model', 'award_receive', 'transfer_fee_model'];

    protected $hidden = ['created_at', 'updated_at'];

    protected $primaryKey = 'user_id';

    public function getTransferFeeModelAttribute($value)
    {
        if ($value === null || $value == '') {
            return env('TRANSFER_FEE_MODEL');
        }
        return $value;
    }
}
