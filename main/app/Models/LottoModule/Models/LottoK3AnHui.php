<?php
namespace App\Models\LottoModule\Models;

use App\Models\LottoModule\Traits\K3Trait;

class LottoK3AnHui extends BasicModel
{
    use K3Trait;

    public $rememberCacheTag = 'lotto_k3_anhui';

    protected $lotto_name = 'ahk3';

    protected $table = 'lotto_k3_anhui';
}
