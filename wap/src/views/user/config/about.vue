<template>
    <div class="relative">
        <userHeader title="关于我们" />
        <div class="container">
            <van-image height="100" src="http://ooo28-public.oss-cn-hangzhou.aliyuncs.com/yl28/user_ico.png" width="100" />
            <p>英利28</p>
            <p>
                <span>v1.11</span>
            </p>
            <p>
                <b>业界最优先的彩票投注</b>
            </p>
            <p>
                <span>心境平和方能制胜千里</span>
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