export default [
    {
        path: '/lotto/:name',
        name: 'lottoRoom',
        meta: { title: '彩票房间', auth: true, is_lotto: true },
        component: () => import('@/views/lotto'),
    },
    // {
    //   path: '/lotto/:name/tra/:room_id',
    //   name: 'lottoTraRoom',
    //   meta: { title: '彩票详情', auth: true, ws: 'lotto', is_lotto: true },
    //   component: () => import('@/views/lotto/tra'),
    // },
    {
        path: '/lotto/:name/tra/:room_id',
        name: 'lottoTraRoom',
        meta: { title: '彩票详情', auth: true, ws: 'lotto', is_lotto: true },
        component: () => import('@/views/lotto/tra'),
    },
    {
        path: '/lotto/:name/chat/:room_id',
        name: 'lottoChatRoom',
        meta: { title: '彩票详情', auth: true, ws: 'lotto', is_lotto: true },
        component: () => import('@/views/lotto/chat'),
    },

    {
        path: '/lotto-trend/:name',
        name: 'lottoTrend',
        meta: { title: '彩票走势', auth: true },
        component: () => import('@/views/lotto/trend'),
    },
]
