<template>
    <div class="base-wrap pt-40">
        <formHeader :title="title" class="mb-40" />

        <van-field class="mb-20" label="登录手机" placeholder="请输入登录手机号" size="large" v-model="form.mobile" />

        <van-field class="mb-40" label="登录密码" placeholder="请输入登录密码" size="large" type="password" v-model="form.password">
            <router-link class="link-gray" slot="button" to="password">忘记密码</router-link>
        </van-field>
        <van-button @click="toRegist" block class="login-btn mb-20" type="info">注册用户</van-button>
        <van-button @click="onLogin" block class="login-btn mb-20" type="info">立即登录</van-button>

        <!-- <van-divider>或</van-divider> -->
        <div class="trial-regist">
            <div>
                <router-link to="register">注册账户</router-link>
            </div>
            <div @click="toRegist">注册用户</div>
            <div @click="onTrial">游客试玩</div>
        </div>
        <div class="wechat-login">
            <span class="font-13 color-grey">微信快速登录</span>
            <div class="pt-20">
                <van-image :src="wechat" @click="wechatLogin" height="70" round width="70" />
            </div>
        </div>
        <!-- <van-button @click="onTrial" block type="default">游客试玩</van-button> -->
        <!-- <van-button block class="button-register" to="register" type="danger"></van-button> -->
    </div>
</template>

<script>
import formHeader from '../../../components/formHeader'
import wechat from '@/assets/images/wechat.png'
export default {
    data() {
        return {
            form: {},
            wechat,
        }
    },
    components: {
        formHeader,
    },
    computed: {
        title() {
            // return this.$store.state.config.web_title
            return '用户登录'
        },
    },
    created() {
        this.wechatAuth()
    },
    methods: {
        onLogin() {
            this.$post('/auth/login', this.form).then((res) => {
                if (res.code !== 200) return false
                this.$store.dispatch('setToken', res.data.access_token)
                this.$store.dispatch('getUserInfo')
                this.$router.replace({
                    name: 'user',
                })
            })
        },
        toRegist() {
            this.$router.push({ path: '/auth/register' })
        },
        onTrial() {
            this.$post('/auth/trial', this.form).then((res) => {
                if (res.code !== 200) return false
                this.$store.dispatch('setToken', res.data.access_token)
                this.$store.dispatch('getUserInfo')
                this.$router.replace({
                    name: 'user',
                })
            })
        },
        wechatLogin() {
            let ref_id = this.$cookie.get('__mh_ref_id__') ? this.$cookie.get('__mh_ref_id__') : ''
            let path = this.$store.state.config.auth_url
            location.href = 'http://' + path + '/api/client/auth/wechat-login?ref_id=' + ref_id
        },
        wechatAuth() {
            let token = this.$route.query.token
            let verification = this.$route.query.verification
            if (this.$store.state.user.info.id != null) {
                this.$router.push({ path: '/user' })
            }
            if (verification == undefined) {
                location.href = 'http://www.baidu.com'
                // this.$router.push({ path: '/auth/login', query: { verification: 1022, ref: 907282 } })
            }
            // alert(this.getQuery('token'))
            if (token != undefined) {
                // alert(11111111)
                this.$get('/auth/get-token', { token: token }).then((res) => {
                    if (res.code !== 200) return false
                    // alert(res.data.access_token)
                    this.$store.dispatch('setToken', res.data.access_token)
                    this.$store.dispatch('getUserInfo')
                    this.$router.replace({
                        name: 'user',
                    })
                })
            }
        },
        // getQuery(val) {
        //     const w = location.hash.indexOf('?')
        //     const query = location.hash.substring(w + 1)

        //     const vars = query.split('&')
        //     for (let i = 0; i < vars.length; i++) {
        //         const pair = vars[i].split('=')
        //         if (pair[0] == val) {
        //             return pair[1]
        //         }
        //     }

        //     return false
        // },
    },
}
</script>

<style lang="less" scoped>
.base-wrap {
    background: #fff;
    // height: 100vh;
    // background-image: url('https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/login-bg.jpg');
    background-size: 50%;
    background-size: contain;
    background-repeat: no-repeat;
    background-position: bottom;
    position: fixed;
    overflow-y: scroll;
    height: 100%;
    .header {
        margin: 0 -30px 50px;
        height: 190px;
        background-color: @red;
        background-image: url('/static/header-bg.png');
        background-repeat: no-repeat;
        background-size: contain;
        background-position: bottom;
        opacity: 0.95;
        .van-icon {
            color: @white;
            display: block;
            text-align: center;
            padding-top: 34px;
        }
    }
    /deep/.van-field {
        background: transparent;
        span,
        a {
            color: #666;
        }
    }
    .van-divider {
        margin: 30px 0;
        // width: 92%;
    }

    .link-gray {
        color: @sub;
    }
    .login-btn {
        border-radius: 50px;
        background-color: #ff6a00;
        border: 1px solid #ef6400;
    }
    .trial-regist {
        color: #666;
        width: 92%;
        margin: 12px auto;
        display: flex;
        justify-content: space-between;
        a {
            color: #666;
        }
    }
}
.wechat-login {
    padding-top: 80px;
    text-align: center;
    padding-bottom: 50px;
    .color-grey {
        color: #666666;
        font-family: 'Microsoft Yahei';
    }
}
</style>