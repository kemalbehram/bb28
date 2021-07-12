<?php
namespace App\Models\LottoModule\Models;

use App\Models\LottoModule\Traits\ShiShiCaiTrait;

class LottoSscXinJiang extends BasicModel
{
    use ShiShiCaiTrait;

    public $rememberCacheTag = 'lotto_ssc_xinjiang';

    protected $lotto_name = 'xjssc';

    protected $table = 'lotto_ssc_xinjiang';
}
