import Main from '@/components/main'
export default [
    {
        path: '/risk',
        name: 'risk',
        meta: {
            icon: 'ios-paper',
            title: '风控中心',
            hideInBread: true
        },
        component: Main,
        children: [
            {
                path: 'lotto-warning',
                name: 'riskLottoWarning',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '彩票警报',
                    access: ['master']
                },
                component: () => import('@/views/risk/lotto-warning')
            },
            {
                path: 'system-wallet',
                name: 'riskSystemWallet',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '资金操作'
                },
                component: () => import('@/views/risk/system-wallet')
            },
            {
                path: 'stats-fix',
                name: 'riskStatsFix',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '统计修正'
                },
                component: () => import('@/views/risk/stats-fix')
            },
            {
                path: 'admin-opts',
                name: 'adminOpts',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '人工验证码'
                },
                component: () => import('@/views/risk/opt')
            }
        ]
    }
]
