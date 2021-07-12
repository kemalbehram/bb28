<template>
    <div>
        <van-cell-group class="menu-group">
            <van-cell :border="false" :icon="item.icon" :key="index" :title="item.name" @click="toUrl(item)" is-link v-for="(item,index) in itemList" />
        </van-cell-group>
    </div>
</template>
<script>
export default {
    data() {
        return {
            itemList: [
                {
                    img_url: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/my_setting.png',
                    icon: 'todo-list-o',
                    to: '/vote',
                    name: '投注记录',
                },
                {
                    img_url: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/my_setting.png',
                    icon: 'todo-list-o',
                    to: '/user/account-change',
                    name: '帐变明细',
                },
                // {
                //     img_url: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/life_member.png',
                //     icon: 'todo-list-o',
                //     to: '/user/vip',
                //     name: '终身会员',
                // },
                // {
                //     img_url: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/life_member.png',
                //     icon: 'shrink',
                //     to: '/user/promote',
                //     name: '代理推广',
                // },
                // {
                //     img_url: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/rebate_rule.png',
                //     icon: 'star-o',
                //     to: '/promotion',
                //     name: '活动中心',
                // },
                {
                    img_url: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/my_setting.png',
                    icon: 'idcard',
                    to: '/user/setting',
                    name: '密码安全',
                },

                {
                    img_url: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/my_setting.png',
                    icon: 'todo-list-o',
                    to: '/service',
                    name: '在线客服',
                },
                {
                    img_url: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/my_setting.png',
                    icon: 'chat-o',
                    to: '/service/wechat',
                    name: '微信客服',
                },
                {
                    img_url: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/my_setting.png',
                    icon: 'more-o',
                    to: '/user/setting/bind',
                    name: '绑定账号',
                },
                {
                    img_url: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/my_setting.png',
                    icon: 'down',
                    download: true,
                    to: 'https://www.google.com',
                    name: 'APP下载',
                },
                {
                    img_url: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/my_setting.png',
                    icon: 'setting-o',
                    to: '/config/setting',
                    name: '设置',
                },

                // {
                //     img_url: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/rebate_rule.png',
                //     icon: 'todo-list-o',
                //     to: '/user/rebate',
                //     name: '返水规则',
                // },
            ],
        }
    },
    methods: {
        toUrl(item) {
            if (item.download === true) {
                // location.href = item.to
                this.toDownload()
                return
            }

            this.$router.push({ path: item.to })
        },
        toDownload() {
            var u = navigator.userAgent
            var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1 //android终端
            var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/) //ios终端
            if (isAndroid) {
                location.href = 'http://yl.digin-wywyn-ndfb.superwaters.cn/andriod.html'
            }
            if (isiOS) {
                location.href = 'http://yl.digin-wywyn-ndfb.superwaters.cn/ios.html'
            }
        },
    },
}
</script>
<style lang="less" scoped>
.van-cell {
    padding: 14px;
}
.van-cell__title,
.van-cell__value {
    text-align: left;
}
.van-nav-bar .van-icon {
    color: #969799;
}
</style>