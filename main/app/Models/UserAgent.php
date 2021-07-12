<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class UserAgent extends Model implements Auditable
{
    use AuditableTrait;

    public $timestamp = false;

    protected $casts = [
        'status'         => 'bool',
        'transfer_rate'  => 'decimal:3',
        'transfer_refer' => 'decimal:3',
        'visible_admin'  => 'bool',
        'visible_agent'  => 'bool',
    ];

    protected $connection = 'main_sql';

    protected $fillable = ['user_id', 'status', 'transfer_rate', 'transfer_refer', 'visible_admin', 'visible_agent', 'contact_qq', 'intro', 'advance'];

    protected $primaryKey = 'user_id';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
