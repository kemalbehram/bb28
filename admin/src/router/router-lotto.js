import Main from '@/components/main'

export default [
    {
        path: '/lotto',
        name: 'lotto',
        meta: {
            icon: 'ios-paper',
            title: '彩票管理',
        },
        component: Main,
        children: [
            {
                path: 'data',
                name: 'lottoData',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '开奖数据',
                },
                component: () => import('@/views/lotto/data'),
            },
            {
                path: 'bet',
                name: 'lottoBet',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '下注明细',
                },
                component: () => import('@/views/lotto/bet'),
            },
            {
                path: 'user-bet-stats',
                name: 'userBetStats',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '下注统计',
                },
                component: () => import('@/views/lotto/user-bet-stats'),
            },
            {
                path: 'config',
                name: 'lottoConfig',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '彩票设置',
                },
                component: () => import('@/views/lotto/config'),
            },
            {
                path: 'room',
                name: 'lottoRoom',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '房间设置',
                },
                component: () => import('@/views/lotto/room'),
            },
            // {
            //     path: 'import',
            //     name: 'lottoDataImport',
            //     meta: {
            //         icon: 'ios-arrow-forward',
            //         title: '数据导入',
            //     },
            //     component: () => import('@/views/lotto/import'),
            // },
        ],
    },
]
