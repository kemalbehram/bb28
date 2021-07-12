<?php
$result              = [];
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
        'title' => '返奖总额',
        'field' => 'bet_bonus',
    ],
    [
        'title' => '有效投注',
        'field' => 'bet_total_effective',
    ],
    [
        'title' => '充值',
        'field' => 'wallet_recharge',
    ],
    [
        'title' => '提现',
        'field' => 'wallet_withdraw',
    ],
    // [
    //     'title' => '余额宝利息',
    //     'field' => 'wallet_fund_interest',
    // ],
    [
        'title' => '活动奖励总和',
        'field' => 'user_award_total',
    ],

    [
        'title' => '下级返水',
        'field' => 'user_agent_rebate_total',
    ],
    [
        'title' => '自身返水',
        'field' => 'user_rebate_total',
    ],
    [
        'title' => '充值赠送',
        'field' => 'user_recharge_back_total',
    ],
    [
        'title' => '福利开支',
        'field' => 'user_expense',
    ],
];

$result['data_red_bag'] = [
    [
        'title' => '红包领取',
        // 'field' => 'red_bag_log',
        'field' => 'red_bag_received',
    ],
    [
        'title' => '红包发送',
        'field' => 'red_bag_send',
    ],
    [
        'title' => '红包退回',
        'field' => 'red_bag_returned',
    ],
    // [
    //     'title' => '红包被领取',
    //     'field' => 'red_bag_received',
    // ],
];

$result['data_transfer'] = [
    // [
    //     'title' => '代理转用户',
    //     'field' => 'transfer_agent_to_user',
    // ],
    // [
    //     'title' => '用户转代理',
    //     'field' => 'transfer_user_to_agent',
    // ],
    // [
    //     'title' => '代理利润-基本',
    //     'field' => 'transfer_award_agent_base',
    // ],
    // [
    //     'title' => '代理利润-下线',
    //     'field' => 'transfer_award_agent_refer',
    // ],
    // [
    //     'title' => '用户转账奖励',
    //     'field' => 'transfer_award_user_total',
    // ],
    // [
    //     'title' => '转账手续费',
    //     'field' => 'transfer_fee',
    // ],
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

return $result;
