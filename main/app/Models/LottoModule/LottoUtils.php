<?php

namespace App\Models\LottoModule;

use App\Models\LottoModule\Models\LottoConfig;

class LottoUtils
{
    public static function Model($name = '')
    {

        $name || $name = request()->lotto_name;

        $mapping       = config('lotto.model.system');
        $class         = $mapping[$name];
        return app($class);
    }

    public static function lottoItems()
    {
        return cache()->remember('betMockLottoItems', 3600, function () {
            $items  = LottoConfig::orderBy('sort', 'asc')->get();
            $result = [];
            foreach ($items as $item) {
                $result[$item->name] = [
                    'name'     => $item->name,
                    'icon'     => $item->icon_font,
                    'title'    => $item->title,
                    'subtitle' => $item->subtitle,
                    'types'    => $item->types,
                    'hot'      => $item->hot,
                    'visible'  => $item->visible,
                    'group'    => $item->group,
                    'intro'    => $item->intro,
                ];
            }

            return $result;
        });
    }
}
