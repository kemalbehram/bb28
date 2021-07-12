import Main from '@/components/main'

export default [
    {
        path: '/red-bag',
        name: 'redBag',
        meta: {
            icon: 'ios-paper',
            title: '红包管理',
            hideInBread: true,
        },
        component: Main,
        children: [
            {
                path: 'home',
                name: 'redBagHome',
                meta: {
                    icon: 'ios-paper',
                    title: '红包管理',
                },
                component: () => import('@/views/red-bag/index'),
            },
        ],
    },
]
