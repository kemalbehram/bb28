<?php
namespace App\Models\LottoModule\Models;

use App\Models\LottoModule\Traits\Lotto28Trait;

class LottoBingoTw extends BasicModel
{
    use Lotto28Trait;

    public $rememberCacheTag = 'lotto_bingo_tw';

    protected $appends = ['lotto_name', 'win_extend', 'bet_count_down', 'open_count_down', 'short_id', 'win_ext_st', 'win_ext_el'];

    protected $lotto_name = 'tw28';

    protected $table = 'lotto_bingo_tw';
}
