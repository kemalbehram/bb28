<?php

$result = [
    'win_function' => 'shishicai',
    'bet_places'   => [
        [
            'name'  => '大',
            'odds'  => '1.960',
            'place' => 'ssm_he_big',
            'group' => 'ssm',
            'child' => '总和',
            'title' => '总和-大',
            'init'  => 1000,
        ],
        [
            'name'  => '小',
            'odds'  => '1.960',
            'place' => 'ssm_he_sml',
            'group' => 'ssm',
            'child' => '总和',
            'title' => '总和-小',
            'init'  => 1000,
        ],
        [
            'name'  => '单',
            'odds'  => '1.960',
            'place' => 'ssm_he_sig',
            'group' => 'ssm',
            'child' => '总和',
            'title' => '总和-单',
            'init'  => 1000,
        ],
        [
            'name'  => '双',
            'odds'  => '1.960',
            'place' => 'ssm_he_dob',
            'group' => 'ssm',
            'child' => '总和',
            'title' => '总和-双',
            'init'  => 1000,
        ],

    ],
];

$lhd = ['wq' => '万千', 'wb' => '万百', 'ws' => '万十', 'wg' => '万个', 'qb' => '千百', 'qs' => '千十', 'qg' => '千个', 'bs' => '百十', 'bg' => '百个', 'sg' => '十个'];

foreach ($lhd as $key => $group_name) {
    $result['bet_places'][] = [

        'name'  => '龙',
        'odds'  => '2.180',
        'place' => 'lhd_' . $key . '_drg',
        'group' => 'lhd',
        'child' => $group_name,
        'title' => $group_name . '-龙',
        'init'  => 1000,
    ];

    $result['bet_places'][] = [
        'name'  => '虎',
        'odds'  => '2.180',
        'place' => 'lhd_' . $key . '_tig',
        'group' => 'lhd',
        'child' => $group_name,
        'title' => $group_name . '-虎',
        'init'  => 1000,
    ];

    $result['bet_places'][] = [
        'name'  => '和',
        'odds'  => '9.800',
        'place' => 'lhd_' . $key . '_pea',
        'group' => 'lhd',
        'child' => $group_name,
        'title' => $group_name . '-和',
        'init'  => 1000,
    ];
}

$ssm_group = ['01' => '万位', '02' => '千位', '03' => '百位', '04' => '十位', '05' => '个位'];
$ssm_child = ['big' => '大', 'sml' => '小', 'sig' => '单', 'dob' => '双', 'qua' => '质', 'clo' => '合'];

foreach ($ssm_group as $group_key => $group_name) {
    foreach ($ssm_child as $key => $name) {
        $result['bet_places'][] = [
            'name'  => $name,
            'odds'  => '1.960',
            'place' => 'ssm_' . $group_key . '_' . $key,
            'group' => 'ssm',
            'child' => $group_name,
            'title' => $group_name . '-' . $name,
            'init'  => 1000,
        ];
    }
}

foreach ($ssm_group as $group_key => $group_name) {
    for ($i = 0; $i <= 9; $i++) {
        $result['bet_places'][] = [
            'name'  => $i,
            'odds'  => '9.800',
            'place' => 'sdw_' . $group_key . '_' . $i,
            'group' => 'sdw',
            'child' => $group_name,
            'title' => $group_name . '-' . $i,
            'init'  => 1000,
        ];
    }
}

return $result;
