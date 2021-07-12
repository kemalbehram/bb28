<?php
$result = [
    'win_function' => 'kuai3',
    'bet_places'   => [
        [
            'name'     => '大',
            'odds'     => '1.9600',
            'place'    => 'qth_big',
            'group'    => 'qth',
            'title'    => '和-大',
            'position' => 1,
            'init'     => 1000,
        ],
        [
            'name'     => '小',
            'odds'     => '1.9600',
            'place'    => 'qth_sml',
            'group'    => 'qth',
            'title'    => '和-小',
            'position' => 2,
            'init'     => 1000,
        ],
        [
            'name'     => '单',
            'odds'     => '1.9600',
            'place'    => 'qth_sig',
            'group'    => 'qth',
            'title'    => '和-单',
            'position' => 1,
            'init'     => 1000,
        ],
        [
            'name'     => '双',
            'odds'     => '1.9600',
            'place'    => 'qth_dob',
            'group'    => 'qth',
            'title'    => '和-双',
            'position' => 2,
            'init'     => 1000,
        ],
        [
            'name'     => '03',
            'odds'     => '188.00',
            'place'    => 'qth_03',
            'group'    => 'qth',
            'title'    => '和-03',
            'position' => 1,
            'init'     => 1000,
        ],
        [
            'name'     => '04',
            'odds'     => '69.840',
            'place'    => 'qth_04',
            'group'    => 'qth',
            'title'    => '和-04',
            'position' => 1,
            'init'     => 1000,
        ],
        [
            'name'     => '05',
            'odds'     => '34.920',
            'place'    => 'qth_05',
            'group'    => 'qth',
            'title'    => '和-05',
            'position' => 1,
            'init'     => 1000,
        ],
        [
            'name'     => '06',
            'odds'     => '20.952',
            'place'    => 'qth_06',
            'group'    => 'qth',
            'title'    => '和-06',
            'position' => 1,
            'init'     => 1000,
        ],
        [
            'name'     => '07',
            'odds'     => '13.968',
            'place'    => 'qth_07',
            'group'    => 'qth',
            'title'    => '和-07',
            'position' => 1,
            'init'     => 1000,
        ],
        [
            'name'     => '08',
            'odds'     => '9.9771',
            'place'    => 'qth_08',
            'group'    => 'qth',
            'title'    => '和-08',
            'position' => 1,
            'init'     => 1000,
        ],
        [
            'name'     => '09',
            'odds'     => '8.3808',
            'place'    => 'qth_09',
            'group'    => 'qth',
            'title'    => '和-09',
            'position' => 1,
            'init'     => 1000,
        ],
        [
            'name'     => '10',
            'odds'     => '7.7600',
            'place'    => 'qth_10',
            'group'    => 'qth',
            'title'    => '和-10',
            'position' => 1,
            'init'     => 1000,
        ],
        [
            'name'     => '11',
            'odds'     => '7.7600',
            'place'    => 'qth_11',
            'group'    => 'qth',
            'title'    => '和-11',
            'position' => 2,
            'init'     => 1000,
        ],
        [
            'name'     => '12',
            'odds'     => '8.3808',
            'place'    => 'qth_12',
            'group'    => 'qth',
            'title'    => '和-12',
            'position' => 2,
            'init'     => 1000,
        ],
        [
            'name'     => '13',
            'odds'     => '9.9771',
            'place'    => 'qth_13',
            'group'    => 'qth',
            'title'    => '和-13',
            'position' => 2,
            'init'     => 1000,
        ],
        [
            'name'     => '14',
            'odds'     => '13.968',
            'place'    => 'qth_14',
            'group'    => 'qth',
            'title'    => '和-14',
            'position' => 2,
            'init'     => 1000,
        ],
        [
            'name'     => '15',
            'odds'     => '20.952',
            'place'    => 'qth_15',
            'group'    => 'qth',
            'title'    => '和-15',
            'position' => 2,
            'init'     => 1000,
        ],
        [
            'name'     => '16',
            'odds'     => '34.920',
            'place'    => 'qth_16',
            'group'    => 'qth',
            'title'    => '和-16',
            'position' => 2,
            'init'     => 1000,
        ],
        [
            'name'     => '17',
            'odds'     => '69.840',
            'place'    => 'qth_17',
            'group'    => 'qth',
            'title'    => '和-17',
            'position' => 2,
            'init'     => 1000,
        ],
        [
            'name'     => '18',
            'odds'     => '188.00',
            'place'    => 'qth_18',
            'group'    => 'qth',
            'title'    => '和-18',
            'position' => 2,
            'init'     => 1000,
        ],

        //其它玩法

        // [
        //     'name'  => '豹子通选',
        //     'odds'  => '31.00',
        //     'place' => 'leo',
        //     'group' => 'other',
        //     'title' => '豹子通选',
        // ],
        // [
        //     'name'  => '顺子通选',
        //     'odds'  => '7.87',
        //     'place' => 'jun',
        //     'group' => 'other',
        //     'title' => '顺子通选',
        // ],

    ],
];

// 三军
for ($i = 1; $i <= 6; $i++) {
    $result['bet_places'][] = [
        'name'     => $i,
        'odds'     => '1.960',
        'place'    => 'qtj_' . $i,
        'group'    => 'qzh',
        'title'    => '三军-' . $i,
        'position' => $i < 4 ? 1 : 2,
        'child'    => 'qtj',
        'init'     => 1000,
    ];
}

//围骰
for ($i = 1; $i <= 6; $i++) {
    $name = $i . $i . $i;

    $result['bet_places'][] = [
        'name'     => $name,
        'odds'     => '158.0',
        'place'    => 'qws_' . $name,
        'group'    => 'qzh',
        'title'    => '围骰-' . $name,
        'position' => $i < 4 ? 1 : 2,
        'child'    => 'qws',
        'init'     => 1000,
    ];
}

//短牌
for ($i = 1; $i <= 6; $i++) {
    $name = $i . '·' . $i;

    $result['bet_places'][] = [
        'name'     => $name,
        'odds'     => '9.800',
        'place'    => 'qdp_' . $i . $i,
        'group'    => 'qzh',
        'title'    => '短牌-' . $name,
        'position' => $i < 4 ? 1 : 2,
        'child'    => 'qdp',
        'init'     => 1000,
    ];
}

//长牌

$position = 2;
for ($i = 11; $i < 66; $i++) {
    $num = str_split($i);
    if ($num[1] === 0 || $num[0] > $num[1] || $num[0] == $num[1] || $num[1] > 6) {
        continue;
    }

    $position++;

    $name = $num[0] . '·' . $num[1];

    $result['bet_places'][] = [
        'name'     => $name,
        'odds'     => '6.000',
        'place'    => 'qcp_' . $num[0] . $num[1],
        'group'    => 'qzh',
        'title'    => '长牌-' . $name,
        'position' => (int) ($position / 3),
        'child'    => 'qcp',
        'init'     => 1000,
    ];
}

return $result;
