<template>
    <div>
        <img @click="showPop" alt src="https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/user-icon.png" />
        <!-- <van-cell  is-link>展示弹出层</van-cell> -->
        <van-popup position="left" v-model="show">
            <div class="top">
                <img :src="user.avatar" alt />
                <span class="nickname">{{user.nickname}}</span>
                <span class="mobile">账号：{{user.mobile}}</span>
                <span class="id">ID：{{user.id}}</span>
            </div>
            <action-menu />
        </van-popup>
    </div>
</template>
<script>
// import { Notify } from 'vant'
import { Toast } from 'vant'
import actionMenu from './action-menu'
export default {
    data() {
        return {
            show: false,
        }
    },
    components: {
        actionMenu,
    },
    computed: {
        user() {
            // console.log(this.$store.state.user.info)
            return this.$store.state.user.info
        },
    },
    methods: {
        showPop() {
            if (this.$store.state.user.token == undefined || this.$store.state.user.token == '') {
                Toast.fail('请先登录')
                return
            }
            this.show = true
        },
    },
}
</script>
<style lang="less" scoped>
img {
    width: 33px;
    height: 33px;
    padding-top: 6px;
    padding-left: 10px;
}
/deep/.van-popup {
    height: 100vh;
    width: 80%;
    border-top-right-radius: 4px;
    border-bottom-right-radius: 4px;
}
.top {
    height: 35vh;
    background: url('https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/drawer-bg.png');
    background-size: 100% 100%;
    position: relative;
    color: white;
    img {
        position: absolute;
        left: 6%;
        top: 60%;
    }
    .nickname {
        position: absolute;
        left: 25%;
        top: 65%;
    }
    .mobile {
        position: absolute;
        left: 9%;
        top: 80%;
    }
    .id {
        position: absolute;
        left: 60%;
        top: 80%;
    }
}
</style>
