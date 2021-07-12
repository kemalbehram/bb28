<?php

$data_base = [
    [
        'title' => '平台总盈亏',
        'field' => 'system_profit',
        'class' => 'color-red',
    ],
    [
        'title' => '平台可用资金',
        'field' => 'system_fund',
    ],
    [
        'title' => '用户资金总额 / 差额',
        'field' => 'wallet_total',
        'fie_2' => 'wallet_diff',
        'cla_2' => 'color-red',
    ],
    [
        'title' => '用户现金结余',
        'field' => 'wallet_balance',
    ],
    // [
    //     'title' => '用户银行结余',
    //     'field' => 'wallet_bank',
    // ],
    // [
    //     'title' => '用户余额宝结余',
    //     'field' => 'wallet_fund',
    // ],

    [
        'title' => '总充值',
        'field' => 'wallet_recharge',
        'route' => '/data-log/wallet-log',
    ],
    [
        'title' => '总提现',
        'field' => 'wallet_withdraw',
        'route' => '/data-log/transfer',
    ],
];

$data_base2 = [
    [
        'title' => '平台现金结余',
        'field' => 'system_balance',
        'class' => 'color-red',
    ],
    [
        'title' => '投注总盈亏',
        'field' => 'bet_profit',
        'route' => '/lotto/bet',
    ],
    [
        'title' => '活动奖励总和',
        'field' => 'user_award_total',
        'route' => '/data-log/award',
    ],
    [
        'title' => '红包领取',
        'field' => 'user_red_bag_received',
    ],
    [
        'title' => '下级返水',
        'field' => 'user_agent_rebate_total',
        'route' => '/member/agent',
    ],
    [
        'title' => '自身返水',
        'field' => 'user_rebate_total',
        'route' => '/balance/recharge',
    ],
    [
        'title' => '充值赠送',
        'field' => 'user_recharge_back_total',
        'route' => '/balance/withdraw',
    ],
    [
        'title' => '福利开支',
        'field' => 'user_expense',
        'route' => '/data-log/expense-log'
    ],
    // [
    //     'title' => '转账奖励',
    //     'field' => 'transfer_award_total',
    //     'route' => '/data-log/transfer',
    // ],


];

$data_user_count = [
    [
        'title' => '注册用户总量',
        'field' => 'register_user_count',
        'route' => '/member/home',
    ],
    [
        'title' => '代理用户总量',
        'field' => 'register_agent_count',
        'route' => '/member/agent',
    ],
    [
        'title' => '下线用户总量',
        'field' => 'user_reference_total',
        'route' => '/member/reference',
    ],
    [
        'title' => '试玩用户总量',
        'field' => 'register_trial_count',
    ],
    [
        'title' => '机器人总量',
        'field' => 'register_robot_count',
    ],
];

$result = [
    'data_base'       => $data_base,
    'data_base2'      => $data_base2,
    'data_user_count' => $data_user_count,

];

return $result;
