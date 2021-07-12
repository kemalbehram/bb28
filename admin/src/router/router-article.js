import Main from '@/components/main'

export default [
    {
        path: '/article',
        name: 'article',
        meta: {
            icon: 'ios-paper',
            title: '文章管理'
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
                path: 'notice',
                name: 'articleNotice',
                meta: {
                    icon: 'ios-arrow-forward',
                    title: '系统公告'
                },
                component: () => import('@/views/article/notice')
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
