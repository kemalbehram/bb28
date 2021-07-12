<template>
    <div class>
        <userHeader title="绑定账号" />

        <van-field :border="false" class="round mb-20" disabled label="当前手机号" size="large" v-if="$store.state.user.info.bind" v-model="$store.state.user.info.mobile" />

        <div v-else>
            <van-field :border="false" class="round" label="当前手机号" placeholder="请输入要绑定的手机号" size="large" v-model="form.mobile" />
            <van-field :border="false" class label="验证码" placeholder="请输入验证码" size="large" v-model="form.ver_code">
                <div slot="button" v-if="ver_code > 0">{{ver_code}}</div>
                <div @click="getCode" slot="button" v-else>获取验证码</div>
            </van-field>
            <van-field :border="false" class="round" label="密码" placeholder="请输入密码" size="large" type="password" v-model="form.password" />

            <van-field :border="false" class="mb-20 round" label="确认密码" placeholder="请再次输入密码" size="large" type="password" v-model="form.re_password" />

            <!-- <van-field :border="false" class="mb-20 round" label="安全密码" placeholder="请输入安全密码" size="large" type="password" v-model="form.safe_word" /> -->
        </div>

        <van-button @click="onConfirm" block type="warning">提交</van-button>
    </div>
</template>

<script>
import userHeader from '@/components/user-header'
export default {
    data() {
        return {
            check_safe: false,
            safe_word: '',
            showKeyboard: true,
            form: {
                mobile: '',
                password: '',
                re_password: '',
                ver_code: null,
                method: 'bindAccount',
            },
            ver_code: 0,
        }
    },
    components: {
        userHeader,
    },
    created() {
        if (this.$store.state.user.info.safe_word) {
            this.check_safe = true
        }
    },
    methods: {
        onDelete() {
            this.safe_word = this.safe_word.slice(0, this.safe_word.length - 1)
        },

        getCode() {
            this.$post('/sms/bindAccount', this.form).then((res) => {
                if (res.code === 200) {
                    this.ver_code = 60
                    const timer = setInterval(() => {
                        this.ver_code--
                    }, 1000)
                }
            })
        },

        onConfirm() {
            this.$post('/user/update', this.form).then((res) => {
                if (res.code === 200) {
                    this.$store.state.user.info.mobile = this.form.mobile
                    this.$store.state.user.info.bind = true
                    this.$router.go(-1)
                }
            })
        },
    },
}
</script>

<style lang="less" scoped>
.van-password-input {
    margin: 40% 0;
}
.van-password-input__security {
    height: 54px;
    border: 1px solid #eeefef;
}
/deep/.van-button {
    width: 90%;
    margin: 0 auto;
    border-radius: 10px;
}
</style>