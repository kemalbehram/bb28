<?php

$format_odds = function ($str, $length) {
    $arr    = explode('.', $str);
    $len_a  = strlen($arr[0]) + 1;
    $diff   = $length - $len_a;
    $format = '%.' . $diff . 'f';
    return sprintf($format, $str);
};

$result = [
    'win_function' => 'racing',
    'bet_places'   => [],
];

//冠亚和
$gyh_rate = [2, 2, 4, 4, 6, 6, 8, 8, 10, 8, 8, 6, 6, 4, 4, 2, 2];
for ($i = 3; $i <= 19; $i++) {
    $name = '冠亚和-' . sprintf('%02d', $i);
    $odds = bcdiv(90, $gyh_rate[$i - 3], 2);
    $odds = bcmul($odds, 0.980, 2);

    $place = [
        'name'     => sprintf('%02d', $i),
        'odds'     => $format_odds($odds, 6),
        'place'    => 'gyh_' . sprintf('%02d', $i),
        'group'    => 'gyh',
        'title'    => $name,
        'init'     => $gyh_rate[$i - 3] * 100,
        'position' => 1,
    ];

    if ($i > 11) {
        $place['position'] = 2;
    }

    $result['bet_places'][] = $place;
}

$chinese = ['冠军', '亚军', '季军', '第四', '第五', '第六', '第七', '第八', '第九', '第十'];

// 双面盘
$smp_place = ['big' => '大', 'sml' => '小', 'sig' => '单', 'dob' => '双', 'drg' => '龙', 'tig' => '虎'];
for ($i = 1; $i <= 10; $i++) {
    $name = $chinese[$i - 1];
    foreach ($smp_place as $_place => $_name) {
        if ($i > 5 && in_array($_place, ['drg', 'tig'])) {
            continue;
        }

        $result['bet_places'][] = [
            'name'  => $_name,
            'odds'  => '1.980',
            'place' => 'smp_' . sprintf('%02d', $i) . '_' . $_place,
            'group' => 'smp',
            'child' => $name,
            'title' => $name . '-' . $_name,
            'init'  => 1000,
        ];
    }
}

//定位胆 PK冠军
for ($i = 1; $i <= 10; $i++) {
    $name = $chinese[$i - 1];
    for ($_i = 1; $_i <= 10; $_i++) {
        $_name                  = sprintf('%02d', $_i);
        $result['bet_places'][] = [
            'name'  => $_name,
            'odds'  => '9.800',
            'place' => 'dwd_' . sprintf('%02d', $i) . '_' . $_name,
            'group' => 'dwd',
            'child' => $name,
            'init'  => 1000,
            'title' => $name . '-' . $_name,
        ];

        if ($i === 1) {
            $position               = $_i <= 5 ? 1 : 2;
            $result['bet_places'][] = [
                'name'     => $_name,
                'odds'     => '9.800',
                'place'    => 'dwd_' . sprintf('%02d', $i) . '_' . $_name,
                'group'    => 'cha',
                'child'    => $name,
                'init'     => 1000,
                'title'    => $name . '-' . $_name,
                'position' => $position,
            ];
        }
    }
}

//PK22-固定赔率
$pk22_odds = [120, 120, 60, 40, 30, 24, 17, 15, 13, 12, 12, 12, 12, 13, 15, 17, 24, 30, 40, 60, 120, 120];
$pk22_rate = [10, 10, 20, 30, 40, 50, 70, 80, 90, 100, 100, 100, 100, 90, 80, 70, 50, 40, 30, 20, 10, 10];
for ($i = 6; $i <= 27; $i++) {
    $name = sprintf('%02d', $i);
    $odds = bcmul($pk22_odds[$i - 6], 0.980, 2);
    $temp = [
        'name'  => $name,
        'title' => 'PK22-' . $name,
        'odds'  => $format_odds($odds, 6),
        'init'  => $pk22_rate[$i - 6] * 10,
        'place' => 'ptt_' . $name,
        'group' => 'ptt',
    ];

    if ($i <= 16) {
        $temp['position'] = 1;
    } else {
        $temp['position'] = 2;
    }
    $result['bet_places'][] = $temp;
}

//pk10-固定赔率
for ($i = 1; $i <= 10; $i++) {
    $name = sprintf('%02d', $i);
    $temp = [
        'name'  => $name,
        'title' => 'PK10-' . $name,
        'odds'  => '9.800',
        'init'  => 1000,
        'place' => 'ptn_' . $name,
        'group' => 'ptn',
    ];

    if ($i <= 5) {
        $temp['position'] = 1;
    } else {
        $temp['position'] = 2;
    }
    $result['bet_places'][] = $temp;
}

return $result;
