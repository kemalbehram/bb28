<?php
namespace App\Models\LottoModule\Models;

use App\Models\LottoModule\Traits\Lotto28Trait;

class LottoKenoDe extends BasicModel
{
    use Lotto28Trait;

    public $rememberCacheTag = 'lotto_keno_de';

    protected $appends = ['lotto_name', 'win_extend', 'bet_count_down', 'open_count_down', 'short_id', 'win_ext_st', 'win_ext_el'];

    protected $lotto_name = 'de28';

    protected $table = 'lotto_keno_de';
}
