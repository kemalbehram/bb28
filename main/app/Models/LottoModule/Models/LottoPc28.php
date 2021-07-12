<?php
namespace App\Models\LottoModule\Models;

use App\Models\LottoModule\Traits\Lotto28Trait;

class LottoPc28 extends BasicModel
{
    use Lotto28Trait;

    public $rememberCacheTag = 'lotto_beijing8';

    protected $lotto_name = 'pc28';

    protected $table = 'lotto_beijing8';
}
