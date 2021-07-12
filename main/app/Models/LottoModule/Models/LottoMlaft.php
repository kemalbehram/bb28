<?php
namespace App\Models\LottoModule\Models;

use App\Models\LottoModule\Traits\RacingTrait;

class LottoMlaft extends BasicModel
{
    use RacingTrait;

    public $rememberCacheTag = 'lotto_mlaft';

    protected $table = 'lotto_mlaft';
}
