<?php

$number = [];

for ($i = 0; $i < 1000; $i++) {
    $num      = str_split(sprintf('%03d', $i));
    $number[] = $num[0] + $num[1] + $num[2];
}

$count = array_count_values($number);

$odds = [];
foreach ($count as $key => $value) {
    $odds[$key] = 1000 / $value;
}

$bet = [];
foreach ($odds as $key => $value) {
    $bet[$key] = 100000000 * $count[$key] / 1000;
    if (in_array($key, [13, 14])) {
        $bet[$key] = 3750000;
    } else {
        $bet[$key] += 7500000 * $count[$key] / 1000;
    }

    if (in_array($key, [13, 14])) {
        $bet[$key] = 3750000;
    } else {
        $bet[$key] += (100000000 - 98875000) * $count[$key] / 1000;
    }

    if (in_array($key, [13, 14])) {
        $bet[$key] = 3750000;
    } else {
        $bet[$key] += (100000000 - 99831250) * $count[$key] / 1000;
    }

    if (in_array($key, [13, 14])) {
        $bet[$key] = 3750000;
    } else {
        $bet[$key] += (100000000 - 99974687.5) * $count[$key] / 1000;
    }

    if (in_array($key, [13, 14])) {
        $bet[$key] = 3750000;
    } else {
        $bet[$key] += (100000000 - 99996203.125) * $count[$key] / 1000;
    }

    if (in_array($key, [13, 14])) {
        $bet[$key] = 3750000;
    } else {
        $bet[$key] += (100000000 - 99999430.46875) * $count[$key] / 1000;
    }

    if (in_array($key, [13, 14])) {
        $bet[$key] = 3750000;
    } else {
        $bet[$key] += (100000000 - 99999914.570312) * $count[$key] / 1000;
    }

    echo $key . '====' . $bet[$key] . '===' . $value . '====' . $bet[$key] * $value . '====' . "\n";
    // var_dump($temp);
}

$all = 0;

foreach ($bet as $key => $value) {
    $all += $value;
}

echo $all;
// for ($i=0; $i < 100000000; $i++) {
//     # code...
// }
// var_dump($odds);
