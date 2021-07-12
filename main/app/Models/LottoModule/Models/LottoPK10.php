<?php
namespace App\Models\LottoModule\Models;

use App\Models\LottoModule\Traits\RacingTrait;

class LottoPK10 extends BasicModel
{
    use RacingTrait;

    public $rememberCacheTag = 'lotto_pk10';

    public $timestamps = false;

    protected $lotto_name = 'pk10';

    protected $table = 'lotto_pk10';
}
