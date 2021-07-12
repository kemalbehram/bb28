<?php
$result = [
    'group' => [
        'series_28'     => [
            'title' => '28系列',
            'name'  => 'series_28',
        ],
        'series_racing' => [
            'title' => '竞速彩',
            'name'  => 'series_racing',
        ],
        'series_kuai3'  => [
            'title' => '快三系列',
            'name'  => 'series_k3',
        ],
        'series_ssc'    => [
            'title' => '时时彩',
            'name'  => 'series_ssc',
        ],
    ],
];

$lotto = ['ca28', 'cw28', 'bj28', 'pc28', 'bit28', 'de28', 'in28', 'kojc28'];

foreach ($lotto as $name) {
    $result['trend'][$name . '.fd'] = ['/trend-chart/' . $name . '/keno-28'];
    $result['trend'][$name . '.he'] = ['/trend-chart/' . $name . '/keno-28'];
    $result['trend'][$name . '.ww'] = ['/trend-chart/' . $name . '/keno-28'];
    $result['trend'][$name . '.ts'] = ['/trend-chart/' . $name . '/keno-36'];
    $result['trend'][$name . '.st'] = ['/trend-chart/' . $name . '/keno-16'];
    $result['trend'][$name . '.el'] = ['/trend-chart/' . $name . '/keno-11'];
}

return $result;
