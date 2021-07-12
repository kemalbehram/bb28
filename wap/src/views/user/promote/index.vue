<template>
    <div class="relative">
        <userHeader title="代理推广" />
        <div class="rebate">
            <p>
                <van-icon class="icon" name="gold-coin" />
                <a>昨日提成</a>
            </p>
            <p>
                <span>{{yester_amount}}</span>
            </p>
            <p>近60天提成:{{sixty_amount}}</p>
        </div>
        <van-cell-group class="menu-group">
            <van-cell :border="false" :icon="item.icon" :title="item.name" :to="item.to" is-link v-for="(item,index) in items" />
        </van-cell-group>
        <!-- 底部导航 -->
        <lett-tabbar />
    </div>
</template>
<script>
import lettTabbar from '_c/lett-tabbar'
import userHeader from '@/components/user-header'
export default {
    name: 'userPromote',
    data() {
        return {
            items: [
                {
                    img_url: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/my_setting.png',
                    icon: 'user-circle-o',
                    to: { name: 'userPromoteCode' },
                    name: '推广链接',
                },
                {
                    img_url: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/my_setting.png',
                    icon: 'notes-o',
                    to: { name: 'userPromoteOfflineRecord' },
                    name: '下线记录',
                },
                {
                    img_url: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/my_setting.png',
                    icon: 'newspaper-o',
                    to: { name: 'userPromoteWithdrawRecord' },
                    name: '提成记录',
                },
                {
                    img_url: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/my_setting.png',
                    icon: 'description',
                    to: { name: 'userPromoteWlrecord' },
                    name: '输赢记录',
                },
                {
                    img_url: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/my_setting.png',
                    icon: 'balance-list-o',
                    to: { name: 'userPromoteStatis' },
                    name: '数据报表',
                },
                // {
                //     img_url: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/my_setting.png',
                //     icon: 'label-o',
                //     to: { name: 'userPromoteClassification' },
                //     name: '分类统计',
                // },
                // {
                //     img_url: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/my_setting.png',
                //     icon: 'user-o',
                //     to: { name: 'userPromoteAgentSystem' },
                //     name: '代理制度',
                // },
                // {
                //     img_url: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/my_setting.png',
                //     icon: 'manager-o',
                //     to: { name: 'userPromoteAgentNotice' },
                //     name: '代理通知',
                // },
            ],
            yester_amount: 0,
            sixty_amount: 0,
        }
    },
    components: {
        userHeader,
        lettTabbar,
    },
    created() {
        this.getInfo()
    },
    methods: {
        getInfo() {
            this.$get('user/reference/statistics').then((res) => {
                if (res.code !== 200) return this.$notify(res.message)
                this.yester_amount = res.data.yester_amount
                this.sixty_amount = res.data.sixty_amount
            })
        },
    },
}
</script>
<style lang="less" scoped>
.rebate {
    padding: 15px;
    text-align: center;
    background: @white;
    border-bottom: 1px solid #e4e4e4;
    p {
        * {
            padding: 0 2px;
            vertical-align: middle;
        }
        vertical-align: middle;
        span {
            font-size: 3rem;
        }
    }
}
.van-cell {
    padding: 16px;
}
.menu-group {
    padding-bottom: 80px;
}
</style>