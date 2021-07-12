<?php
namespace App\Models\LottoModule\Models;

use App\Models\LottoModule\Traits\K3Trait;

class LottoK3BeiJing extends BasicModel
{
    use K3Trait;

    public $rememberCacheTag = 'lotto_k3_beijing';

    protected $lotto_name = 'bjk3';

    protected $table = 'lotto_k3_beijing';
}
