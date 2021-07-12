<?php
namespace App\Models\LottoModule\Models;

use App\Models\LottoModule\Traits\K3Trait;

class LottoK3HeBei extends BasicModel
{
    use K3Trait;

    public $rememberCacheTag = 'lotto_k3_hebei';

    protected $lotto_name = 'hebk3';

    protected $table = 'lotto_k3_hebei';
}
