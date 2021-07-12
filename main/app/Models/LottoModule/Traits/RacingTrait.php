<?php

namespace App\Models\LottoModule\Traits;

use App\Models\LottoModule\LottoWinPlace;
trait RacingTrait
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
        $arr = explode(',', $this->open_code);

        $result = [];

        //冠亚和
        $gyh                = sprintf('%02d', ($arr[0] + $arr[1]));
        $result['source']   = $this->open_code;
        $result['code_arr'] = $arr;
        $result['code_gyh'] = [
            'gyh' => $gyh,
            'bos' => $gyh >= 12 ? '大' : '小',
            'sod' => $gyh % 2 == 1 ? '单' : '双',
        ];

        //PK22
        $ptt                = sprintf('%02d', ($arr[0] + $arr[1] + $arr[2]));
        $result['code_ptt'] = [
            'ptt' => $ptt,
            'bos' => $ptt >= 17 ? '大' : '小',
            'sod' => $ptt % 2 == 1 ? '单' : '双',
        ];

        //PK10
        $position           = substr($this->id, -1);
        $position           = $position != 0 ? $position : 10;
        $ptn_code           = $arr[$position - 1];
        $result['code_ptn'] = [
            'ptn' => $ptn_code,
            'pos' => intval($position),
            'bos' => $ptn_code >= 6 ? '大' : '小',
            'sod' => $ptn_code % 2 == 1 ? '单' : '双',
        ];

        //PK冠军
        $position           = 0;
        $cha_code           = $arr[$position];
        $result['code_cha'] = [
            'cha' => $cha_code,
            'pos' => intval($position),
            'bos' => $cha_code >= 6 ? '大' : '小',
            'sod' => $cha_code % 2 == 1 ? '单' : '双',
        ];

        return $result;
    }

    public function getWinPlaceAttribute()
    {
        if ($this->open_code === null) {
            return null;
        }
        $win_place = LottoWinPlace::racing($this->open_code, $this->id);
        return $win_place;
    }

    public function placeExtend()
    {
        return null;
    }
}
