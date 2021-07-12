<?php
namespace App\Models\LottoModule\Models;

use App\Models\LottoModule\Traits\ShiShiCaiTrait;

class LottoSscTianJin extends BasicModel
{
    use ShiShiCaiTrait;

    public $rememberCacheTag = 'lotto_ssc_tianjin';

    protected $lotto_name = 'tjssc';

    protected $table = 'lotto_ssc_tianjin';
}
