<?php

namespace App\Models\LottoModule\Models;

use Illuminate\Database\Eloquent\Model;

class LottoChatHistory extends Model
{

	protected $table = 'lotto_chat_history';
	protected $casts = ['details' => 'array'];
}
