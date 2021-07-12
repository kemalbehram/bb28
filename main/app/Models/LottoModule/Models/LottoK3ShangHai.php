<?php
namespace App\Models\LottoModule\Models;

use App\Models\LottoModule\Traits\K3Trait;

class LottoK3ShangHai extends BasicModel
{
    use K3Trait;

    public $rememberCacheTag = 'lotto_k3_shanghai';

    protected $lotto_name = 'shk3';

    protected $table = 'lotto_k3_shanghai';
}
