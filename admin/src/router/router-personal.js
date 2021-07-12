import Main from '@/components/main'
export default [
  {
    path: '/personal',
    name: 'personal',
    meta: {
      hideInBread: true
    },
    component: Main,
    children: [
      {
        path: 'personal',
        name: 'personalHome',
        meta: {
          icon: 'ios-paper',
          title: '个人资料'
        },
        component: () => import('@/views/personal')
      }
    ]
  }
]
