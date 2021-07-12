<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class UserRebateConfig extends Model implements Auditable
{
	use AuditableTrait;

	public $timestamp = false;

	protected $casts = [
		'room1'         => 'array',
		'room2'         => 'array',
		'room3'         => 'array',
		'room4'         => 'array',
		'room5'         => 'array',
		'room6'         => 'array',
		'room7'         => 'array',
		'room8'         => 'array',

	];

	protected $connection = 'main_sql';
	protected $hidden = ['created_at', 'updated_at', 'id', 'user_id'];
	// protected $fillable = ['user_id', 'status', 'transfer_rate', 'transfer_refer', 'visible_admin', 'visible_agent', 'contact_qq', 'intro', 'advance'];

	protected $primaryKey = 'user_id';

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}
}
