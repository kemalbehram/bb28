<?php
return [
    'collect_own'      => ['de28', 'in28', 'kojc28', 'ylde28'], //自有游戏
    'formula_basic28'  => ['bj28', 'ca28', 'de28', 'ylde28', 'hero28', 'cw28', 'in28', 'kojc28', 'tw28'], //用基本算法的28 //LottoFormula
    'win_place_has_st' => ['ca28', 'cw28', 'de28', 'bj28', 'in28', 'kojc28', 'tw28', 'ylde28'], //有16的游戏 //LottoWinPlace
    'cache_extend'     => ['ca28', 'cw28', 'bit28', 'de28', 'bj28', 'pc28', 'kojc28', 'tw28', 'ylde28'], // lottoCacheCommand
    'has_float'        => ['ca28', 'cw28', 'de28', 'bj28', 'pc28', 'bit28', 'kojc28', 'tw28', 'ylde28'], // 有浮动的游戏 LottoController
    'web_hot_lotto'    => ['ca28.fd', 'cw28.fd', 'bj28.fd', 'pc28.fd', 'de28.fd', 'bit28.fd', 'pk10.ptn', 'kojc28.fd'], //有热门游戏
    'control_bet'      => ['de28', 'bit28', 'hero28', 'in28', 'kojc28', 'ylde28'], //需要推送控制的游戏 //UserBetTrait

];
