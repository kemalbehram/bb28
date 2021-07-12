export default [
    {
        path: '/auth/login',
        name: 'authLogin',
        meta: {
            title: '登录',
        },
        component: () => import('@/views/user/auth/login'),
    },

    {
        path: '/auth/register',
        name: 'authRegister',
        meta: {
            title: '注册',
        },
        component: () => import('@/views/user/auth/register'),
    },
    {
        path: '/auth/password',
        name: 'authPassword',
        meta: {
            title: '忘记密码',
        },
        component: () => import('@/views/user/auth/password'),
    },
    {
        path: '/user/setting',
        name: 'userSetting',
        meta: {
            title: '账户设置',
            auth: true,
        },
        component: () => import('@/views/user/setting/index'),
    },
    {
        path: '/user/setting/nickname',
        name: 'userSettingNickname',
        meta: {
            title: '修改昵称',
            auth: true,
        },
        component: () => import('@/views/user/setting/nickname'),
    },
    {
        path: '/user/setting/realname',
        name: 'userSettingRealkname',
        meta: {
            title: '修改真实姓名',
            auth: true,
        },
        component: () => import('@/views/user/setting/realname'),
    },
    {
        path: '/user/setting/mobile',
        name: 'userSettingMobile',
        meta: {
            title: '修改手机号',
            auth: true,
        },
        component: () => import('@/views/user/setting/mobile'),
    },
    {
        path: '/user/setting/bind',
        name: 'userSettingBind',
        meta: {
            title: '绑定账号',
            auth: true,
        },
        component: () => import('@/views/user/setting/bind'),
    },
    {
        path: '/user/setting/bank',
        name: 'userSettingBank',
        meta: {
            title: '银行卡',
            auth: true,
        },
        component: () => import('@/views/user/setting/bank'),
    },
    {
        path: '/user/setting/qq',
        name: 'userSettingQq',
        meta: {
            title: '联系QQ',
            auth: true,
        },
        component: () => import('@/views/user/setting/contact-qq'),
    },
    {
        path: '/user/setting/safe-word',
        name: 'userSettingSafeWord',
        meta: {
            title: '安全密码',
            auth: true,
        },
        component: () => import('@/views/user/setting/safe-word'),
    },
    {
        path: '/user/setting/password',
        name: 'userSettingPassword',
        meta: {
            title: '修改登录密码',
            auth: true,
        },
        component: () => import('@/views/user/auth/password'),
    },
    {
        path: '/user/promote',
        name: 'userPromote',
        meta: {
            title: '代理推广',
            auth: true,
        },
        component: () => import('@/views/user/promote'),
    },
    {
        path: '/user/promote/code/:user_id',
        name: 'userPromoteCode',
        meta: {
            title: '推广码',
            auth: true,
        },
        component: () => import('@/views/user/promote/code'),
    },
    {
        path: '/user/promote/wlrecord',
        name: 'userPromoteWlrecord',
        meta: {
            title: '输赢记录',
            auth: true,
        },
        component: () => import('@/views/user/promote/wlrecord'),
    },
    {
        path: '/user/promote/statis',
        name: 'userPromoteStatis',
        meta: {
            title: '数据报表',
            auth: true,
        },
        component: () => import('@/views/user/promote/statis'),
    },
    {
        path: '/user/promote/classification',
        name: 'userPromoteClassification',
        meta: {
            title: '分类统计',
            auth: true,
        },
        component: () => import('@/views/user/promote/classification'),
    },
    {
        path: '/user/promote/agentsystem',
        name: 'userPromoteAgentSystem',
        meta: {
            title: '代理制度',
            auth: true,
        },
        component: () => import('@/views/user/promote/agentsystem'),
    },
    {
        path: '/user/promote/agentnotice',
        name: 'userPromoteAgentNotice',
        meta: {
            title: '代理制度',
            auth: true,
        },
        component: () => import('@/views/user/promote/agentnotice'),
    },
    {
        path: '/user/promote/offline-record',
        name: 'userPromoteOfflineRecord',
        meta: {
            title: '下线记录',
            auth: true,
        },
        component: () => import('@/views/user/promote/offline-record'),
    },
    {
        path: '/user/promote/withdraw-record',
        name: 'userPromoteWithdrawRecord',
        meta: {
            title: '提现记录',
            auth: true,
        },
        component: () => import('@/views/user/promote/withdraw-record'),
    },
    {
        path: '/user/promote/next-level',
        name: 'userPromoteNextLevel',
        meta: {
            title: '下级用户',
            auth: true,
        },
        component: () => import('@/views/user/promote/nextLevel'),
    },
    {
        path: '/user/promote/next-level/:user_id',
        name: 'userPromoteNextLevelDetail',
        meta: {
            title: '下级用户详情',
            auth: true,
        },
        component: () => import('@/views/user/promote/nextLevelDetail'),
    },
    {
        path: '/user/promote/next-open',
        name: 'userNextOpen',
        meta: {
            title: '下级开户',
            auth: true,
        },
        component: () => import('@/views/user/promote/nextOpen'),
    },
    {
        path: '/user/promote/add-plan',
        name: 'addPlan',
        meta: {
            title: '新增方案',
            auth: true,
        },
        component: () => import('@/views/user/promote/addPlan'),
    },
    {
        path: '/user/account-change',
        name: 'userAccountChange',
        meta: {
            title: '帐变明细',
            auth: true,
            pop:true
        },
        component: () => import('@/views/user/account'),
    },
    {
        path: '/user/rebate',
        name: 'userRebate',
        meta: {
            title: '返水规则',
            auth: true,
        },
        component: () => import('@/views/user/rebate'),
    },
    {
        path: '/user/vip',
        name: 'userVip',
        meta: {
            title: '终身会员',
            auth: true,
        },
        component: () => import('@/views/user/vip'),
    },
    {
        path: '/user/profit',
        name: 'userProfit',
        meta: {
            title: '盈利',
            auth: true,
        },
        component: () => import('@/views/user/profit'),
    },
    
]
