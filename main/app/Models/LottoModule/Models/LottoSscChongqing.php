<?php
namespace App\Models\LottoModule\Models;

use App\Models\LottoModule\Traits\ShiShiCaiTrait;

class LottoSscChongqing extends BasicModel
{
    use ShiShiCaiTrait;

    public $rememberCacheTag = 'lotto_ssc_chongqing';

    protected $lotto_name = 'cqssc';

    protected $table = 'lotto_ssc_chongqing';
}
