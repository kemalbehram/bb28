<?php
$format_odds = function ($str, $length) {
    $arr    = explode('.', $str);
    $len_a  = strlen($arr[0]) + 1;
    $diff   = $length - $len_a;
    $format = '%.' . $diff . 'f';
    return sprintf($format, $str);
};

$result = [
    'win_function' => 'lotto28',
    'bet_places'   => [],
];

$he_rate    = [1, 3, 6, 10, 15, 21, 28, 36, 45, 55, 63, 69, 73, 75, 75, 73, 69, 63, 55, 45, 36, 28, 21, 15, 10, 6, 3, 1];
$he_odds    = [];
$fd_odds    = [];
$odds_reset = [0 => 990, 27 => 990];

for ($i = 0; $i <= 27; $i++) {
    if (isset($odds_reset[$i])) {
        $odds = $odds_reset[$i];
    } else {
        $base = bcdiv(1000, $he_rate[$i], 2);
        $odds = bcmul($base, 0.986, 4);
        $odds = intval($odds * 10000) / 10000;
    }

    $he_odds[] = $format_odds($odds, 7);

    $odds      = bcmul(1000 / $he_rate[$i], 1, 4);
    $odds      = intval($odds * 10000) / 10000;
    $fd_odds[] = $format_odds($odds, 7);
}

//固定986
// for ($i = 0; $i <= 27; $i++) {
//     $name = sprintf('%02d', $i);
//     $temp = [
//         'name'  => $name,
//         'title' => '和-' . $name,
//         'odds'  => $he_odds[$i],
//         'init'  => $he_rate[$i] * 10,
//         'place' => 'he_' . $name,
//         'group' => 'he',
//     ];

//     if ($i <= 13) {
//         $temp['position'] = 1;
//     } else {
//         $temp['position'] = 2;
//     }
//     $result['bet_places'][] = $temp;
// }

//浮动
// for ($i = 0; $i <= 27; $i++) {
//     $name = sprintf('%02d', $i);
//     $temp = [
//         'name'  => $name,
//         'title' => '和-' . $name,
//         'odds'  => $fd_odds[$i],
//         'init'  => $he_rate[$i] * 10,
//         'place' => 'fd_' . $name,
//         'group' => 'fd',
//     ];

//     if ($i <= 13) {
//         $temp['position'] = 1;
//     } else {
//         $temp['position'] = 2;
//     }
//     $result['bet_places'][] = $temp;
// }

//16
// $st_rate = [1, 3, 6, 10, 15, 21, 25, 27, 27, 25, 21, 15, 10, 6, 3, 1];

// $st_odds = [];
// for ($i = 3; $i <= 18; $i++) {
//     $odds      = bcmul(216 / $st_rate[$i - 3], 1, 4);
//     $odds      = intval($odds * 10000) / 10000;
//     $st_odds[] = $format_odds($odds, 7);
// }

// for ($i = 3; $i <= 18; $i++) {
//     $name = sprintf('%02d', $i);
//     $temp = [
//         'name'  => $name,
//         'title' => '和-' . $name,
//         'odds'  => $st_odds[$i - 3],
//         'init'  => $st_rate[$i - 3] * 10,
//         'place' => 'st_' . $name,
//         'group' => 'st',
//     ];

//     if ($i <= 10) {
//         $temp['position'] = 1;
//     } else {
//         $temp['position'] = 2;
//     }
//     $result['bet_places'][] = $temp;
// }

// //11
// $st_rate = [1, 2, 3, 4, 5, 6, 5, 4, 3, 2, 1];
// $st_odds = [];
// for ($i = 2; $i <= 12; $i++) {
//     $odds = bcmul(36 / $st_rate[$i - 2], 1, 4);
//     $odds = intval($odds * 10000) / 10000;
//     $st_odds[] = $format_odds($odds, 7);
// }

// for ($i = 2; $i <= 12; $i++) {
//     $name = sprintf('%02d', $i);
//     $temp = [
//         'name' => $name,
//         'title' => '和-' . $name,
//         'odds' => $st_odds[$i - 2],
//         'init' => $st_rate[$i - 2] * 10,
//         'place' => 'el_' . $name,
//         'group' => 'el',
//     ];

//     if ($i <= 7) {
//         $temp['position'] = 1;
//     } else {
//         $temp['position'] = 2;
//     }
//     $result['bet_places'][] = $temp;
// }

// $waiwei = [
//     [
//         'name'     => '大',
//         'title'    => '外围-大',
//         'odds'     => '2.1400',
//         'place'    => 'ww_big',
//         'init'     => 100,
//         'group'    => 'ww',
//         'position' => 1,
//     ],
//     [
//         'name'     => '小',
//         'title'    => '外围-小',
//         'odds'     => '2.1400',
//         'place'    => 'ww_sml',
//         'init'     => 100,
//         'group'    => 'ww',
//         'position' => 2,
//     ],
//     [
//         'name'     => '单',
//         'title'    => '外围-单',
//         'odds'     => '2.1400',
//         'place'    => 'ww_sig',
//         'init'     => 100,
//         'group'    => 'ww',
//         'position' => 1,
//     ],
//     [
//         'name'     => '双',
//         'title'    => '外围-双',
//         'odds'     => '2.1400',
//         'place'    => 'ww_dob',
//         'init'     => 100,
//         'group'    => 'ww',
//         'position' => 2,
//     ],
//     [
//         'name'     => '大双',
//         'title'    => '外围-大双',
//         'odds'     => '4.6500',
//         'place'    => 'ww_bdo',
//         'init'     => 100,
//         'group'    => 'ww',
//         'position' => 1,
//     ],
//     [
//         'name'     => '大单',
//         'title'    => '外围-大单',
//         'odds'     => '4.2600',
//         'place'    => 'ww_bsg',
//         'init'     => 100,
//         'group'    => 'ww',
//         'position' => 1,
//     ],
//     [
//         'name'     => '小双',
//         'title'    => '外围-小双',
//         'odds'     => '4.2600',
//         'place'    => 'ww_sdo',
//         'init'     => 100,
//         'group'    => 'ww',
//         'position' => 2,
//     ],
//     [
//         'name'     => '小单',
//         'title'    => '外围-小单',
//         'odds'     => '4.6500',
//         'place'    => 'ww_ssg',
//         'init'     => 100,
//         'group'    => 'ww',
//         'position' => 2,
//     ],
//     [
//         'name'     => '极大',
//         'title'    => '外围-极大',
//         'odds'     => '17.000',
//         'place'    => 'ww_xbg',
//         'init'     => 100,
//         'group'    => 'ww',
//         'position' => 1,
//     ],
//     [
//         'name'     => '极小',
//         'title'    => '外围-极小',
//         'odds'     => '17.000',
//         'place'    => 'ww_xsm',
//         'init'     => 100,
//         'group'    => 'ww',
//         'position' => 2,
//     ],
//     [
//         'name'     => '豹',
//         'title'    => '外围-豹子',
//         'odds'     => '98.000',
//         'place'    => 'ts_leo',
//         'init'     => 100,
//         'group'    => 'ww',
//         'position' => 1,
//     ],
//     [
//         'name'     => '对',
//         'title'    => '外围-对子',
//         'odds'     => '3.6290',
//         'place'    => 'ts_pai',
//         'init'     => 100,
//         'group'    => 'ww',
//         'position' => 2,
//     ],
//     [
//         'name'     => '顺',
//         'title'    => '外围-顺子',
//         'odds'     => '16.000',
//         'place'    => 'ts_jun',
//         'init'     => 100,
//         'group'    => 'ww',
//         'position' => 1,
//     ],
//     [
//         'name'     => '半',
//         'title'    => '外围-半顺',
//         'odds'     => '2.6600',
//         'place'    => 'ts_juh',
//         'init'     => 100,
//         'group'    => 'ww',
//         'position' => 2,
//     ],

//     [
//         'name'     => '杂',
//         'title'    => '外围-杂',
//         'odds'     => '3.1900',
//         'place'    => 'ts_oth',
//         'init'     => 100,
//         'group'    => 'ww',
//         'position' => 1,
//     ],
// ];
$room1 = [
    [
        'name'     => '大',
        'title'    => '房间1-大',
        'odds'     => '2.0000',
        'place'    => 'room1_big',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '小',
        'title'    => '房间1-小',
        'odds'     => '2.0000',
        'place'    => 'room1_sml',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '单',
        'title'    => '房间1-单',
        'odds'     => '2.0000',
        'place'    => 'room1_sig',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '双',
        'title'    => '房间1-双',
        'odds'     => '2.0000',
        'place'    => 'room1_dob',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '大双',
        'title'    => '房间1-大双',
        'odds'     => '4.6500',
        'place'    => 'room1_bdo',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '大单',
        'title'    => '房间1-大单',
        'odds'     => '4.2500',
        'place'    => 'room1_bsg',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '小双',
        'title'    => '房间1-小双',
        'odds'     => '4.2500',
        'place'    => 'room1_sdo',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '小单',
        'title'    => '房间1-小单',
        'odds'     => '4.6500',
        'place'    => 'room1_ssg',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '极大',
        'title'    => '房间1-极大',
        'odds'     => '15.0000',
        'place'    => 'room1_xbg',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '极小',
        'title'    => '房间1-极小',
        'odds'     => '15.0000',
        'place'    => 'room1_xsm',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '00',
        'title'    => '房间1-00',
        'odds'     => '888.0000',
        'place'    => 'room1_00',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '01',
        'title'    => '房间1-01',
        'odds'     => '330.0000',
        'place'    => 'room1_01',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '02',
        'title'    => '房间1-02',
        'odds'     => '150.0000',
        'place'    => 'room1_02',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '03',
        'title'    => '房间1-03',
        'odds'     => '99.0000',
        'place'    => 'room1_03',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '04',
        'title'    => '房间1-04',
        'odds'     => '63.0000',
        'place'    => 'room1_04',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '05',
        'title'    => '房间1-05',
        'odds'     => '45.0000',
        'place'    => 'room1_05',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '06',
        'title'    => '房间1-06',
        'odds'     => '33.0000',
        'place'    => 'room1_06',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '07',
        'title'    => '房间1-07',
        'odds'     => '27.0000',
        'place'    => 'room1_07',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '08',
        'title'    => '房间1-08',
        'odds'     => '22.0000',
        'place'    => 'room1_08',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '09',
        'title'    => '房间1-09',
        'odds'     => '18.0000',
        'place'    => 'room1_09',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '10',
        'title'    => '房间1-10',
        'odds'     => '15.0000',
        'place'    => 'room1_10',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '11',
        'title'    => '房间1-11',
        'odds'     => '14.0000',
        'place'    => 'room1_11',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '12',
        'title'    => '房间1-12',
        'odds'     => '13.0000',
        'place'    => 'room1_12',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '13',
        'title'    => '房间1-13',
        'odds'     => '13.0000',
        'place'    => 'room1_13',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '14',
        'title'    => '房间1-14',
        'odds'     => '13.0000',
        'place'    => 'room1_14',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '15',
        'title'    => '房间1-15',
        'odds'     => '13.0000',
        'place'    => 'room1_15',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '16',
        'title'    => '房间1-16',
        'odds'     => '14.0000',
        'place'    => 'room1_16',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '17',
        'title'    => '房间1-17',
        'odds'     => '15.0000',
        'place'    => 'room1_17',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '18',
        'title'    => '房间1-18',
        'odds'     => '18.0000',
        'place'    => 'room1_18',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '19',
        'title'    => '房间1-19',
        'odds'     => '22.0000',
        'place'    => 'room1_19',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '20',
        'title'    => '房间1-20',
        'odds'     => '27.0000',
        'place'    => 'room1_20',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '21',
        'title'    => '房间1-21',
        'odds'     => '33.0000',
        'place'    => 'room1_21',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '22',
        'title'    => '房间1-22',
        'odds'     => '45.0000',
        'place'    => 'room1_22',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '23',
        'title'    => '房间1-23',
        'odds'     => '63.0000',
        'place'    => 'room1_23',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '24',
        'title'    => '房间1-24',
        'odds'     => '99.0000',
        'place'    => 'room1_24',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '25',
        'title'    => '房间1-25',
        'odds'     => '150.0000',
        'place'    => 'room1_25',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '26',
        'title'    => '房间1-26',
        'odds'     => '330.0000',
        'place'    => 'room1_26',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => '27',
        'title'    => '房间1-27',
        'odds'     => '888.0000',
        'place'    => 'room1_27',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => 'long',
        'title'    => '房间1-龙',
        'odds'     => '2.9200',
        'place'    => 'room1_long',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => 'hu',
        'title'    => '房间1-虎',
        'odds'     => '2.9200',
        'place'    => 'room1_hu',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => 'bao',
        'title'    => '房间1-豹',
        'odds'     => '2.9200',
        'place'    => 'room1_bao',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => 'baozi',
        'title'    => '房间1-豹子',
        'odds'     => '66.0000',
        'place'    => 'room1_baozi',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => 'sunzi',
        'title'    => '房间1-顺子',
        'odds'     => '14.0000',
        'place'    => 'room1_sunzi',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],
    [
        'name'     => 'duizi',
        'title'    => '房间1-对子',
        'odds'     => '3.2000',
        'place'    => 'room1_duizi',
        'init'     => 0,
        'group'    => 'room1',
        'position' => 1,
    ],

];
$room2 = [
    [
        'name'     => '大',
        'title'    => '房间2-大',
        'odds'     => '2.0000',
        'place'    => 'room2_big',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => '小',
        'title'    => '房间2-小',
        'odds'     => '2.0000',
        'place'    => 'room2_sml',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => '单',
        'title'    => '房间2-单',
        'odds'     => '2.0000',
        'place'    => 'room2_sig',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => '双',
        'title'    => '房间2-双',
        'odds'     => '2.0000',
        'place'    => 'room2_dob',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => '大双',
        'title'    => '房间2-大双',
        'odds'     => '4.6500',
        'place'    => 'room2_bdo',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => '大单',
        'title'    => '房间2-大单',
        'odds'     => '4.2500',
        'place'    => 'room2_bsg',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => '小双',
        'title'    => '房间2-小双',
        'odds'     => '4.2500',
        'place'    => 'room2_sdo',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => '小单',
        'title'    => '房间2-小单',
        'odds'     => '4.6500',
        'place'    => 'room2_ssg',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => '极大',
        'title'    => '房间2-极大',
        'odds'     => '15.0000',
        'place'    => 'room2_xbg',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => '极小',
        'title'    => '房间2-极小',
        'odds'     => '15.0000',
        'place'    => 'room2_xsm',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => '00',
        'title'    => '房间2-00',
        'odds'     => '688.0000',
        'place'    => 'room2_00',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 2,
    ],
    [
        'name'     => '01',
        'title'    => '房间2-01',
        'odds'     => '200.0000',
        'place'    => 'room2_01',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 2,
    ],
    [
        'name'     => '02',
        'title'    => '房间2-02',
        'odds'     => '100.0000',
        'place'    => 'room2_02',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 2,
    ],
    [
        'name'     => '03',
        'title'    => '房间2-03',
        'odds'     => '68.0000',
        'place'    => 'room2_03',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 2,
    ],
    [
        'name'     => '04',
        'title'    => '房间2-04',
        'odds'     => '48.0000',
        'place'    => 'room2_04',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 2,
    ],
    [
        'name'     => '05',
        'title'    => '房间2-05',
        'odds'     => '38.0000',
        'place'    => 'room2_05',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 2,
    ],
    [
        'name'     => '06',
        'title'    => '房间2-06',
        'odds'     => '28.0000',
        'place'    => 'room2_06',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 2,
    ],
    [
        'name'     => '07',
        'title'    => '房间2-07',
        'odds'     => '18.0000',
        'place'    => 'room2_07',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 2,
    ],
    [
        'name'     => '08',
        'title'    => '房间2-08',
        'odds'     => '15.0000',
        'place'    => 'room2_08',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 2,
    ],
    [
        'name'     => '09',
        'title'    => '房间2-09',
        'odds'     => '15.0000',
        'place'    => 'room2_09',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 2,
    ],
    [
        'name'     => '10',
        'title'    => '房间2-10',
        'odds'     => '14.0000',
        'place'    => 'room2_10',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 2,
    ],
    [
        'name'     => '11',
        'title'    => '房间2-11',
        'odds'     => '14.0000',
        'place'    => 'room2_11',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 2,
    ],
    [
        'name'     => '12',
        'title'    => '房间2-12',
        'odds'     => '12.0000',
        'place'    => 'room2_12',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => '13',
        'title'    => '房间2-13',
        'odds'     => '12.0000',
        'place'    => 'room2_13',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => '14',
        'title'    => '房间2-14',
        'odds'     => '12.0000',
        'place'    => 'room2_14',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => '15',
        'title'    => '房间2-15',
        'odds'     => '12.0000',
        'place'    => 'room2_15',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => '16',
        'title'    => '房间2-16',
        'odds'     => '14.0000',
        'place'    => 'room2_16',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => '17',
        'title'    => '房间2-17',
        'odds'     => '14.0000',
        'place'    => 'room2_17',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => '18',
        'title'    => '房间2-18',
        'odds'     => '15.0000',
        'place'    => 'room2_18',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => '19',
        'title'    => '房间2-19',
        'odds'     => '15.0000',
        'place'    => 'room2_19',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => '20',
        'title'    => '房间2-20',
        'odds'     => '18.0000',
        'place'    => 'room2_20',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => '21',
        'title'    => '房间2-21',
        'odds'     => '28.0000',
        'place'    => 'room2_21',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => '22',
        'title'    => '房间2-22',
        'odds'     => '38.0000',
        'place'    => 'room2_22',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => '23',
        'title'    => '房间2-23',
        'odds'     => '48.0000',
        'place'    => 'room2_23',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => '24',
        'title'    => '房间2-24',
        'odds'     => '68.0000',
        'place'    => 'room2_24',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => '25',
        'title'    => '房间2-25',
        'odds'     => '100.0000',
        'place'    => 'room2_25',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => '26',
        'title'    => '房间2-26',
        'odds'     => '200.0000',
        'place'    => 'room2_26',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => '27',
        'title'    => '房间2-27',
        'odds'     => '688.0000',
        'place'    => 'room2_27',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => 'long',
        'title'    => '房间2-龙',
        'odds'     => '2.9200',
        'place'    => 'room2_long',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => 'hu',
        'title'    => '房间2-虎',
        'odds'     => '2.9200',
        'place'    => 'room2_hu',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => 'bao',
        'title'    => '房间2-豹',
        'odds'     => '2.9200',
        'place'    => 'room2_bao',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => 'baozi',
        'title'    => '房间2-豹子',
        'odds'     => '66.0000',
        'place'    => 'room2_baozi',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => 'sunzi',
        'title'    => '房间2-顺子',
        'odds'     => '14.0000',
        'place'    => 'room2_sunzi',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],
    [
        'name'     => 'duizi',
        'title'    => '房间2-对子',
        'odds'     => '3.2000',
        'place'    => 'room2_duizi',
        'init'     => 0,
        'group'    => 'room2',
        'position' => 1,
    ],

];




$result['bet_places'] = array_merge($result['bet_places'], $room1, $room2);

return $result;
