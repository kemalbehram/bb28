<?php
namespace App\Models\LottoModule\Models;

use App\Models\LottoModule\Traits\K3Trait;

class LottoK3JiangSu extends BasicModel
{
    use K3Trait;

    public $rememberCacheTag = 'lotto_k3_jiangsu';

    protected $lotto_name = 'jsk3';

    protected $table = 'lotto_k3_jiangsu';
}
