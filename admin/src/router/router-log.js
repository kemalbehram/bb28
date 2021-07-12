import Main from '@/components/main'
export default [
    {
        path: '/data-log',
        name: 'dataLog',
        meta: {
            icon: 'ios-paper',
            title: '明细记录',
            hideInBread: true,
        },
        component: Main,
        children: [
            {
                path: 'transfer',
                name: 'dataTransfer',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '转账明细',
                },
                component: () => import('@/views/log/transfer'),
            },
            {
                path: 'red-bag',
                name: 'dataRedBag',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '红包明细',
                },
                component: () => import('@/views/log/red-bag'),
            },
            {
                path: 'award',
                name: 'dataAward',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '奖励明细',
                },
                component: () => import('@/views/log/award'),
            },
            {
                path: 'wallet-log',
                name: 'dataWalletLog',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '资金明细',
                },
                component: () => import('@/views/log/wallet-log'),
            },
            {
                path: 'rebate-log',
                name: 'userRebateLog',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '返利明细',
                },
                component: () => import('@/views/log/rebate-log'),
            },
            {
                path: 'expense-log',
                name: 'expenseLog',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '福利开支',
                },
                component: () => import('@/views/log/expense'),
            },
        ],
    },
]
