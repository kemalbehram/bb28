<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Expense extends Model
{
	// protected $casts = ['extend' => 'array', 'status' => 'bool'];
	protected $table = 'expenses';
	protected $connection = 'main_sql';

	public function user()
	{
		return $this->hasOne(User::class, 'id', 'user_id');
	}
}
