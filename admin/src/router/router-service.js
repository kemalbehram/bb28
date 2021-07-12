import Main from '@/components/main'
export default [
    {
        path: '/service',
        name: '在线客服',
        meta: {
            hideInBread: true
        },
        component: Main,
        children: [
            {
                path: 'home',
                name: 'serviceHome',
                meta: {
                    icon: 'ios-paper',
                    title: '在线客服'
                },
                component: () => import('@/views/service')
            }
        ]
    }
]
