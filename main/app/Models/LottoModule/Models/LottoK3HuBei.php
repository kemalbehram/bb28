<?php
namespace App\Models\LottoModule\Models;

use App\Models\LottoModule\Traits\K3Trait;

class LottoK3HuBei extends BasicModel
{
    use K3Trait;

    public $rememberCacheTag = 'lotto_k3_hubei';

    protected $lotto_name = 'hubk3';

    protected $table = 'lotto_k3_hubei';
}
