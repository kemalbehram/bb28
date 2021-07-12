<?php

$number = [1, 3, 6, 10, 15, 21, 25, 27, 27, 25, 21, 15, 10, 6, 3, 1];
$result = [];
for ($i = 3; $i <= 18; $i++) {
    $num      = 216 / $number[$i - 3] * 0.97;
    $num      = intval($num * 10000) / 10000;
    $result[] = sprintf('%.4f', $num);
}

var_dump($result);

var_dump(implode('|', $result));
