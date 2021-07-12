import Main from '@/components/main'
export default [
    {
        path: '/data-stats',
        name: 'dataStats',
        meta: {
            icon: 'ios-paper',
            title: '数据统计',
            hideInBread: true,
        },
        component: Main,
        children: [
            {
                path: 'home',
                name: 'dataStatsHome',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '综合统计',
                },
                component: () => import('@/views/stats/home'),
            },
            {
                path: 'table',
                name: 'dataStatsTable',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '综合报表',
                },
                component: () => import('@/views/stats/table'),
            },
            {
                path: 'lotto',
                name: 'dataStatsLotto',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '游戏报表',
                },
                component: () => import('@/views/stats/lotto'),
            },
            {
                path: 'user-bet-stats',
                name: 'userTableStats',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '用户报表',
                },
                component: () => import('@/views/stats/user-table-stats'),
            }
        ],
    },
]
