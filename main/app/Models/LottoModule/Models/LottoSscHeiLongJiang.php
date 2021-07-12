<?php
namespace App\Models\LottoModule\Models;

use App\Models\LottoModule\Traits\ShiShiCaiTrait;

class LottoSscHeiLongJiang extends BasicModel
{
    use ShiShiCaiTrait;

    public $rememberCacheTag = 'lotto_ssc_heilongjiang';

    protected $lotto_name = 'hljssc';

    protected $table = 'lotto_ssc_heilongjiang';
}
