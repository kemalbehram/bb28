export default [
    {
        path: '*',
        name: '404',
        meta: {
            title: '404',
        },
        component: () => import('@/views/single-page/error-404'),
    },

    {
        path: '/',
        name: 'home',
        meta: {
            title: '首页',
            auth: true,
        },
        component: () => import('@/views/home'),
    },

    {
        path: '/user',
        name: 'user',
        meta: {
            title: '用户中心',
            auth: true,
        },
        component: () => import('@/views/user'),
    },
    {
        path: '/wallet',
        name: 'wallet',
        meta: {
            title: '钱包',
            auth: true,
        },
        component: () => import('@/views/wallet'),
    },

    {
        path: '/vote',
        name: 'vote',
        meta: {
            title: '已投',
            auth: true,
        },
        component: () => import('@/views/vote'),
    },

    {
        path: '/promotion',
        name: 'promotion',
        meta: {
            title: '活动中心',
        },
        component: () => import('@/views/promotion'),
    },
    {
        path: '/service',
        name: 'service',
        meta: {
            title: '在线客服',
            auth: true,
        },
        component: () => import('@/views/service'),
    },
    {
        path: '/service/wechat',
        name: 'wechat',
        meta: {
            title: '微信客服',
            auth: true,
        },
        component: () => import('@/views/service/wechat'),
    },
    {
        path: '/chatroom',
        name: 'chatroom',
        meta: {
            title: '聊天室',
            auth: true,
            ws: 'chat',
        },
        component: () => import('@/views/chatroom'),
    },
    {
        path: '/dialogue',
        name: 'dialogue',
        meta: {
            title: '会话',
            auth: true,
        },
        component: () => import('@/views/dialogue'),
    },
    {
        path: '/notice-list',
        name: 'noticeList',
        meta: {
            title: '公告列表',
            auth: true,
        },
        component: () => import('@/views/dialogue/notice-list'),
    },
]
