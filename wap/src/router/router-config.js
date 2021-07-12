export default [
    {
        path: '/config/setting',
        name: 'configIndex',
        meta: { title: '设置'},
        component: () => import('@/views/user/config'),
    },
    {
        path: '/config/setting/about',
        name: 'configAbout',
        meta: { title: '关于'},
        component: () => import('@/views/user/config/about'),
    },
]
