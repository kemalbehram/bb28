import Main from '@/components/main'

export default [
    {
        path: '/option',
        name: 'option',
        meta: {
            icon: 'ios-paper',
            title: '网站设置',
        },
        component: Main,
        children: [
            {
                path: 'combination',
                name: 'optionBase',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '综合设置',
                },
                component: () => import('@/views/option/base'),
            },
            {
                path: 'aisles-setting',
                name: 'aislesSetting',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '银行卡设置',
                    // access: ['lett'],
                },
                component: () => import('@/views/option/aisles-setting'),
            },
            {
                path: 'focus',
                name: 'optionFocus',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '焦点图',
                },
                component: () => import('@/views/option/focus'),
            },
            {
                path: 'helper',
                name: 'optionHelper',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '帮助中心',
                    keepAlive: true,
                },
                component: () => import('@/views/option/helper'),
            },
            {
                path: 'about',
                name: 'optionAbout',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '关于我们',
                    keepAlive: true,
                },
                component: () => import('@/views/option/about'),
            },
            {
                path: 'administrator',
                name: 'administratorHome',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '管理员',
                },
                component: () => import('@/views/option/administrator'),
            },
            {
                path: 'super-setting',
                name: 'superSetting',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '超级设置',
                    access: ['lett'],
                },
                component: () => import('@/views/option/super-setting'),
            },
        ],
    },
]
