<template>
    <div class="relative">
        <userHeader title="微信客服" />
        <div class="container">
            <van-image height="200" src="http://ooo28-public.oss-cn-hangzhou.aliyuncs.com/yl28/wechat.jpeg" width="200" />
            <p>微信客服</p>

            <p>
                <b>yingli28q</b>
            </p>
        </div>
    </div>
</template>
<script>
import userHeader from '@/components/user-header'
export default {
    components: {
        userHeader,
    },
}
</script>
<style lang="less" scoped>
.container {
    text-align: center;
    padding: 15vw;
    p {
        color: @black;
        span {
            color: #b7b7c7;
        }
        b {
            font-size: 20px;
        }
    }
}
</style>