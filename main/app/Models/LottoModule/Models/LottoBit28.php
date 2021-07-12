<?php
namespace App\Models\LottoModule\Models;

use App\Models\LottoModule\LottoFormula;
use App\Models\LottoModule\LottoWinPlace;
use App\Models\LottoModule\Traits\Lotto28Trait;

class LottoBit28 extends BasicModel
{
    use Lotto28Trait;

    public $rememberCacheTag = 'lotto_bit_28';

    protected $lotto_name = 'bit28';

    protected $table = 'lotto_bit_28';

    public function getWinExtendAttribute()
    {
        if (null === $this->open_code) {
            return null;
        }

        $formula = LottoFormula::bit28($this->open_code);
        $he      = $formula['code_he'];

        $result             = [];
        $result['code_arr'] = $formula['code_arr'];
        $result['code_str'] = $formula['code_str'];
        $result['code_he']  = sprintf('%02d', $he);

        $he >= 14 && $result['code_bos']    = '大';
        $he <= 13 && $result['code_bos']    = '小';
        $he % 2 == 1 && $result['code_sod'] = '单';
        $he % 2 == 0 && $result['code_sod'] = '双';

        return $result;
    }
}
