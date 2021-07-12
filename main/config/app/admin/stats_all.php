<?php

$result = [];

$result['data_base'] = [
    [
        'title' => '平台总盈亏',
        'field' => 'system_profit',
        'class' => 'color-red',
    ],
    [
        'title' => '投注盈亏',
        'field' => 'bet_profit',
        'class' => 'color-red',
    ],
    [
        'title' => '投注总额',
        'field' => 'bet_total',
    ],
    [
        'title' => '有效投注',
        'field' => 'bet_total_effective',
    ],
    [
        'title' => '返奖总额',
        'field' => 'bet_bonus',
    ],
    [
        'title' => '充值',
        'field' => 'wallet_recharge',
    ],
    [
        'title' => '提现',
        'field' => 'wallet_withdraw',
    ],
    [
        'title' => '余额宝利息',
        'field' => 'wallet_fund_interest',
    ],
];

$result['data_red_bag'] = [
    [
        'title' => '红包领取',
        'field' => 'red_bag_log',
    ],
    [
        'title' => '红包发送',
        'field' => 'red_bag_send',
    ],
    [
        'title' => '红包退回',
        'field' => 'red_bag_returned',
    ],
    [
        'title' => '红包被领取',
        'field' => 'red_bag_received',
    ],
];

$result['data_transfer'] = [
    [
        'title' => '代理转用户',
        'field' => 'transfer_agent_to_user',
    ],
    [
        'title' => '用户转代理',
        'field' => 'transfer_user_to_agent',
    ],
    [
        'title' => '代理利润-基本',
        'field' => 'transfer_award_agent_base',
    ],
    [
        'title' => '代理利润-下线',
        'field' => 'transfer_award_agent_refer',
    ],
    [
        'title' => '用户转账奖励',
        'field' => 'transfer_award_user_total',
    ],
    [
        'title' => '转账手续费',
        'field' => 'transfer_fee',
    ],
];

$result['data_transfer_user'] = [
    [
        'title' => '转账收入',
        'field' => 'transfer_in',
    ],
    [
        'title' => '转账支出',
        'field' => 'transfer_out',
    ],
    [
        'title' => '代理利润-基本',
        'field' => 'transfer_award_agent_base',
    ],
    [
        'title' => '代理利润-下线',
        'field' => 'transfer_award_agent_refer',
    ],
    [
        'title' => '用户转账奖励',
        'field' => 'transfer_award_user_total',
    ],
    [
        'title' => '转账手续费',
        'field' => 'transfer_fee',
    ],
];

$result['data_award'] = [
    [
        'title' => '任务奖励总和',
        'field' => 'user_award_total',
    ],
    [
        'title' => '用户等级日领',
        'field' => 'user_award_user_level_day',
    ],
    [
        'title' => '用户晋级奖励',
        'field' => 'user_award_user_level_upgrade',
    ],
    [
        'title' => '单次充值2K',
        'field' => 'user_award_recharge_k2',
    ],
    [
        'title' => '累计充值50K',
        'field' => 'user_award_recharge_w5',
    ],
    [
        'title' => '每日亏损返水',
        'field' => 'user_award_bet_day_loss',
    ],
    [
        'title' => '一级下线日返',
        'field' => 'user_award_ref_1_bet_rebate',
    ],
    [
        'title' => '五级下线周返',
        'field' => 'user_award_ref_5_loss',
    ],
    [
        'title' => '加拿大系列',
        'field' => 'user_award_play_ca28',
    ],
    [
        'title' => '加西部系列',
        'field' => 'user_award_play_cw28',
    ],
    [
        'title' => '比特币系列',
        'field' => 'user_award_play_bit28',
    ],
    [
        'title' => '德国系列',
        'field' => 'user_award_play_de28',
    ],
    [
        'title' => '北京系列',
        'field' => 'user_award_play_bj28',
    ],
    [
        'title' => '蛋蛋系列',
        'field' => 'user_award_play_pc28',
    ],
];

$result['data_commission'] = [
    [
        'title' => '下线用户总量',
        'field' => 'user_reference_total',
    ],
    [
        'title' => '一级下线用户',
        'field' => 'user_reference_1',
    ],
    [
        'title' => '二级下线用户',
        'field' => 'user_reference_2',
    ],
    [
        'title' => '三级下线用户',
        'field' => 'user_reference_3',
    ],
    [
        'title' => '四级下线用户',
        'field' => 'user_reference_4',
    ],
    [
        'title' => '五级下线用户',
        'field' => 'user_reference_5',
    ],
];

return $result;
