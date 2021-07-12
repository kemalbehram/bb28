<template>
    <div class="pb-64 index">
        <!--wrap-->
        <div class="my-info">
            <van-image :src="user_ico" class="avatar head" fit="cover" height="100" round width="100" />
            <span class="nickname">{{user.nickname}}</span>
            <div class="grow">
                <span v-if="user.level">成长值:{{user.level.level_exp}}/{{user.level.exp_diff}}</span>
                <span v-else>成长值:0/200000</span>
                <div class="bg" ref="grow"></div>
            </div>
        </div>
        <div class="scoll-menu">
            <van-cell-group class="menu-group" v-if="user.id">
                <van-cell :border="false" :title="user.nickname" icon="manager-o" />
                <van-cell :border="false" :title="'UID-'+user.id" icon="label-o" />
            </van-cell-group>

            <action-menu />
        </div>
        <lett-tabbar />
    </div>
</template>

<script>
import actionMenu from '_c/action-menu'
import lettTabbar from '_c/lett-tabbar'
// import Menu from '../../components/menu.vue'
export default {
    name: 'user',
    data() {
        return {
            user_ico: 'http://ooo28-public.oss-cn-hangzhou.aliyuncs.com/yl28/user_ico.png',
            logout_img: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/logout.png',
            app_img: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/rebate_rule.png',
            show: false,
            down_load_url: 'https://mahua28.com/app/index.html',
            profit: 0,
        }
    },
    created() {
        this.$store.dispatch('getUserInfo'), this.getStats()
    },
    methods: {
        onLogout() {
            this.$store.dispatch('removeToken')
            // window.Echo.disconnect()
            this.$router.replace({
                name: 'home',
            })
        },
        getStats() {
            this.$get('user/stats').then((res) => {
                this.profit = parseFloat(res.data.bet_bonus) - parseFloat(res.data.bet_total) + parseFloat(res.data.user_award)
            })
        },
        toDetail() {
            this.$router.push('/user/profit')
        },
        showDownLoad() {
            this.show = true
        },
    },
    computed: {
        user() {
            return this.$store.state.user.info
        },
        wallet() {
            return this.$store.state.user.wallet
        },
    },
    watch: {
        user(val) {
            let level = val.level
            if (level) {
                let proportion = level.level_exp <= 0 ? '0%' : Math.round((level.level_exp / level.exp_diff) * 10000) / 100.0 + '%'
                this.$refs.grow.style.width = proportion
                console.log(this.$refs.grow)
            }
            if (val.wechat) {
                this.user_ico = val.avatar
            }
        },
    },
    components: {
        lettTabbar,
        actionMenu,
    },
}
</script>
<style lang="less" scoped>
.my-info {
    height: 200px;
    background: #ff6a00;
    // background-image: url('http://ooo28-public.oss-cn-hangzhou.aliyuncs.com/yl28/my_info.png');
    // background-size: 100% 100%;
    position: relative;
    .avatar {
        position: absolute;
        left: 50%;
        top: 25%;
        transform: translate(-50%, -30%);
    }
    .nickname {
        position: absolute;
        left: 50%;
        top: 74%;
        transform: translate(-50%, -65%);
        color: white;
        font-size: 17px;
    }
    .grow {
        position: absolute;
        left: 50%;
        top: 90%;
        transform: translate(-50%, -65%);
        // color: black;
        font-size: 12px;
        width: 50%;
        margin-left: 0 auto;
        text-align: center;
        background: #fff;
        border-radius: 30px;
        height: 24px;
        line-height: 24px;
        .bg {
            position: absolute;
            height: 100%;
            background-color: #62b6f8;
            border-radius: 30px;
            top: 0;
        }
        span {
            z-index: 20;
            position: relative;
        }
    }
    .user-id {
        position: absolute;
        left: 50%;
        top: 80%;
        transform: translate(-50%, -80%);
        color: white;
    }
    .agent {
        position: absolute;
        right: 5%;
        top: 10%;
        color: white;
        /deep/.van-icon {
            -webkit-animation: hue 1s infinite linear;
        }
        @-webkit-keyframes hue {
            from {
                color: #fa8c25;
            }
            to {
                color: #fc1111;
            }
        }
    }
}
.money-info {
    height: 50px;
    background: black;
    text-align: center;
    color: #d8bb99;
    .line {
        width: 1px;
        height: 30px;
        background: #d8bb99;
    }
}
.label {
    margin-top: 65px;
}
// .item-list {
//     /deep/.van-grid-item {
//         padding: 0px 2px;
//         height: 110px;
//     }
//     /deep/.van-grid-item__content {
//         background-color: white;
//         border-radius: 10px;
//         text-align: center;
//         box-shadow: 1px 1px 10px 0px #f1f0f0;
//     }
//     .down-load-content {
//         padding: 10px 10px;
//         a {
//             color: @red;
//             font-weight: 400;
//         }
//     }
// }
.menu-group {
    padding: 10px 0px;
}
.scoll-menu {
    overflow-y: scroll;
    /* height: calc(100% - 200px); */
    position: fixed;
    width: 100%;
    bottom: 50px;
    top: 200px;
}
</style>
