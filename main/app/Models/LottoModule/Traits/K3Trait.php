<?php

namespace App\Models\LottoModule\Traits;

use App\Models\LottoModule\LottoWinPlace;
trait K3Trait
{
    public function floatOdds()
    {
        return null;
    }

    public function getWinCodeAttribute()
    {
        return $this->open_code;
    }

    public function getWinExtendAttribute()
    {
        $result = [];
        $code   = $this->open_code;
        if (null === $code) {
            return null;
        }

        $code_arr = explode(',', $code);
        $he       = $code_arr[0] + $code_arr[1] + $code_arr[2];

        $result['code_str'] = $code;

        $result['code_he'] = sprintf('%02d', $he);

        $he >= 11 && $result['code_bos']    = '大';
        $he <= 10 && $result['code_bos']    = '小';
        $he % 2 == 1 && $result['code_sod'] = '单';
        $he % 2 == 0 && $result['code_sod'] = '双';

        $result['code_arr'] = $code_arr;
        return $result;
    }

    public function getWinPlaceAttribute()
    {
        if ($this->open_code === null) {
            return null;
        }
        $win_place = LottoWinPlace::kuai3($this->open_code);
        return $win_place;
    }

    public function placeExtend()
    {
        return null;
    }
}
