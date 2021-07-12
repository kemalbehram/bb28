<template>
    <div class>
        <userHeader title="修改登录密码" />

        <div class="base-wrap pt-40">
            <van-field :border="false" disabled label="登录手机" placeholder="请输入注册手机号" size="large" v-if="setting.disable" v-model="form.mobile" />

            <van-field :border="false" label="注册手机" placeholder="请输入注册手机号" size="large" v-else v-model="form.mobile" />

            <van-field :border="false" label="验证码" placeholder="请输入验证码" size="large" v-model="form.ver_code">
                <div slot="button" v-if="ver_code > 0">{{ver_code}}</div>
                <div @click="getCode" slot="button" v-else>获取验证码</div>
            </van-field>

            <van-field :border="false" class="mb-20 round" label="新密码" placeholder="请输入新登录密码" size="large" type="password" v-model="form.password" />

            <van-button :disabled="form.password !=''? false : true" @click="onConfirm" block type="info">提交</van-button>
        </div>
    </div>
</template>

<script>
import userHeader from '@/components/user-header'
export default {
    data() {
        return {
            form: {
                method: 'login',
                password: '',
                ver_code: '',
                mobile: this.$store.state.user.info.mobile,
            },
            ver_code: 0,
        }
    },
    components: {
        userHeader,
    },
    computed: {
        setting() {
            if (this.$route.name == 'userSettingPassword') {
                this.form.mobile = this.$store.state.user.info.mobile
                return {
                    header: '修改登录密码',
                    disable: true,
                }
            } else {
                return {
                    header: '找回登录密码',
                    disable: false,
                }
            }
        },
    },

    methods: {
        getCode() {
            this.$post('/sms/password', this.form).then((res) => {
                if (res.code === 200) {
                    this.ver_code = 60
                    const timer = setInterval(() => {
                        this.ver_code--
                    }, 1000)
                }
            })
        },

        onConfirm() {
            this.$post('/auth/password', this.form).then((res) => {
                if (res.code === 200) {
                    if (this.setting.disable) {
                        this.$router.go(-1)
                    } else {
                        this.$router.replace({
                            name: 'authLogin',
                        })
                    }
                }
            })
        },
    },
}
</script>
<style lang="less" scoped>
/deep/.van-button {
    width: 90%;
    margin: 0 auto;
    border-radius: 10px;
}
</style>