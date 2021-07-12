import Main from '@/components/main'

export default [
    {
        path: '/balance',
        name: 'balance',
        meta: {
            icon: 'ios-paper',
            title: '充值提现'
        },
        component: Main,
        children: [
            {
                path: 'recharge',
                name: 'balanceRecharge',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '充值管理'
                },
                component: () => import('@/views/balance/recharge')
            },

            {
                path: 'withdraw',
                name: 'balanceWithdraw',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '提现管理'
                },
                component: () => import('@/views/balance/withdraw')
            },

            {
                path: 'withdraw-limit',
                name: 'balanceWithdrawLimit',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '下分限制'
                },
                component: () => import('@/views/balance/withdraw-limit')
            }
        ]
    }
]
