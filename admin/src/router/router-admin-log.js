import Main from '@/components/main'

export default [
    {
        path: '/admin-log',
        name: 'adminLog',
        meta: {
            icon: 'ios-paper',
            title: '管理员日志'
        },
        component: Main,
        children: [
            // {
            //     path: 'news',
            //     name: 'articleNews',
            //     meta: {
            //         icon: 'ios-arrow-forward',
            //         title: '热门活动'
            //     },
            //     component: () => import('@/views/article/news')
            // },

            {
                path: '/admin-log',
                name: 'adminLog',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '管理员日志',
                    access: ['lett'],
                },
                component: () => import('@/views/admin-log/index')
            }

            // {
            //   path: 'category',
            //   name: 'articleCategory',
            //   meta: {
            //     icon: 'ios-arrow-forward',
            //     title: '文章分类'
            //   },
            //   component: () => import('@/views/article/category')
            // }
        ]
    }
]
