<?php

namespace App\Models\LottoModule\Traits;

use App\Models\LottoModule\LottoWinPlace;
trait ShiShiCaiTrait
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
        if (!$this->open_code) {
            return null;
        }
        $code_arr = explode(',', $this->open_code);

        $result             = [];
        $result['code_str'] = $this->open_code;
        $he                 = 0;
        foreach ($code_arr as $value) {
            $he += $value;
        }
        $result['code_he'] = sprintf('%02d', $he);

        $he >= 23 && $result['code_bos']    = '大';
        $he <= 22 && $result['code_bos']    = '小';
        $he % 2 == 1 && $result['code_sod'] = '单';
        $he % 2 == 0 && $result['code_sod'] = '双';
        $result['code_arr']                 = $code_arr;

        return $result;
    }

    public function getWinPlaceAttribute()
    {
        if ($this->open_code === null) {
            return null;
        }
        $win_place = LottoWinPlace::shishicai($this->open_code);
        return $win_place;
    }

    public function placeExtend()
    {
        return null;
    }
}
