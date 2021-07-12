import Main from '@/components/main'
export default [
  {
    path: '/contact',
    name: 'contact',
    meta: {
      hideInBread: true
    },
    component: Main,
    children: [
      {
        path: 'index',
        name: 'contactIndex',
        meta: {
          icon: 'ios-paper',
          title: '留言反馈'
        },
        component: () => import('@/views/contact')
      }
    ]
  }
]
