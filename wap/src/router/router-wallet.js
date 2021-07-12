export default [
  {
    path: '/wallet/withdraw',
    name: 'walletWithdraw',
    meta: {
      title: '提现',
      auth: true,
    },
    component: () => import('@/views/wallet/withdraw'),
  },
  {
    path: '/wallet/recharge',
    name: 'walletRecharge',
    meta: {
      title: '充值',
      auth: true,
    },
    component: () => import('@/views/wallet/recharge'),
  },
  {
    path: '/wallet/rewilog',
    name: 'walletReWiLog',
    meta: {
      title: '充值提现记录',
      auth: true,
    },
    component: () => import('@/views/wallet/log'),
  },
  {
    path: '/wallet/recharge-item',
    name: 'walletRechargeitem',
    meta: {
      title: '充值',
      auth: true,
    },
    component: () => import('@/views/wallet/recharge-item'),
  }
]
