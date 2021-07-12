<template>
    <div class="base-wrap pt-40">
        <formHeader class="mb-40" title="注册用户" />
        <van-field :border="false" class="mb-20 round" label="注册手机" placeholder="请输入注册手机号" size="large" v-model="form.mobile" />
        <van-field :border="false" class="mb-20 round" label="您的昵称" placeholder="请输入昵称" size="large" v-model="form.nickname">
            <!-- <div @click="getNickname" slot="button">随机昵称</div> -->
        </van-field>
        <van-field :border="false" class="mb-20 round" label="验证码" placeholder="请输入验证码" size="large" v-model="form.ver_code">
            <div slot="button" v-if="ver_code > 0">{{ver_code}}</div>
            <div @click="getCode" slot="button" v-else>获取验证码</div>
        </van-field>
        <van-field :border="false" class="mb-20 round" label="登录密码" placeholder="请输入登录密码" size="large" type="password" v-model="form.password" />

        <van-field :border="false" class="mb-40 round" label="推荐码(选填)" placeholder="请输入推荐码或用户ID" size="large" v-model="form.ref_id" />

        <van-button @click="onRegitser" block class="mb-40 register-btn" type="info">注册</van-button>
        <div @click="login" class="on-login">已有账号,现在登录</div>
    </div>
</template>

<script>
import formHeader from '../../../components/formHeader'
export default {
    data() {
        return {
            form: {
                method: 'login',
                nickname: '',
            },
            ver_code: 0,
        }
    },
    created() {
        if (this.$cookie.get('__mh_ref_id__')) {
            if (this.$cookie.get('__mh_ref_id__') != false && this.$cookie.get('__mh_ref_id__') != 'false') {
                this.form.ref_id = this.$cookie.get('__mh_ref_id__')
            }
        }
    },
    components: {
        formHeader,
    },
    methods: {
        login() {
            this.$router.push({ path: '/auth/login' })
        },
        getCode() {
            this.$post('/sms/register', this.form).then((res) => {
                if (res.code === 200) {
                    this.ver_code = 60
                    const timer = setInterval(() => {
                        this.ver_code--
                    }, 1000)
                }
            })
        },
        getNickname() {
            this.$get('/auth/nickname').then((res) => {
                this.form.nickname = res.data.nickname
            })
        },

        onRegitser() {
            this.$post('/auth/register', this.form).then((res) => {
                if (res.code === 200) {
                    this.$store.dispatch('setToken', res.data.access_token)
                    this.$store.dispatch('getUserInfo')
                    this.$router.replace({
                        name: 'home',
                    })
                }
            })
        },
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
    .register-btn {
        border-radius: 50px;
        background-color: #ff6a00;
        border: 1px solid #ef6400;
    }
    .on-login {
        text-align: center;
        width: 100%;
        padding-bottom: 50px;
    }
}
</style>