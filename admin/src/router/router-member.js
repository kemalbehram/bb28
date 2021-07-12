import Main from '@/components/main'

export default [
    {
        path: '/member',
        name: 'member',
        meta: {
            icon: 'ios-paper',
            title: '用户管理',
            hideInBread: true,
        },
        component: Main,
        children: [
            {
                path: 'home',
                name: 'memberHome',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '所有用户',
                },
                component: () => import('@/views/member/all'),
            },
            {
                path: 'agent',
                name: 'memberAgent',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '合作代理',
                },
                component: () => import('@/views/member/agent'),
            },
            {
                path: 'single',
                name: 'memberSingle',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '用户查询',
                },
                component: () => import('@/views/member/single'),
            },
            {
                path: 'reference',
                name: 'memberReference',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '下级推广',
                },
                component: () => import('@/views/member/reference'),
            },
            {
                path: 'rebate',
                name: 'memberRebate',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '会员返水'
                },
                component: () => import('@/views/member/rebate')
            }
        ]
    }
]
