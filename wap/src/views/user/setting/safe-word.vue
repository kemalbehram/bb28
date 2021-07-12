<template>
    <div v-if="check_safe">
        <userHeader title="验证安全码" />
        <van-password-input :focused="showKeyboard" :value="safe_word" info="安全密码为 6 位数字" />
        <van-number-keyboard :show="showKeyboard" @delete="onDelete" @input="onInput" />
    </div>

    <div class v-else>
        <userHeader title="设置安全密码" />
        <!-- <form-header class="pt-40 mb-40" title="" /> -->

        <!-- <van-field :border="false" class="round" disabled label="当前手机" size="large" v-model="$store.state.user.info.mobile" />

        <van-field :border="false" class label="验证码" placeholder="请输入验证码" size="large" v-model="form.ver_code">
            <div slot="button" v-if="ver_code > 0">{{ver_code}}</div>
            <div @click="getCode" slot="button" v-else>获取验证码</div>
        </van-field>-->

        <van-field :border="false" class="mb-20 round" label="安全密码" maxlength="6" placeholder="请输入新安全密码" size="large" type="password" v-model="form.safe_word" />

        <van-button :disabled="form.safe_word !=''? false : true" @click="onConfirm" block type="warning">提交</van-button>
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
                mobile: this.$store.state.user.info.mobile,
                safe_word: '',
                ver_code: null,
                method: 'safeWordUpdate',
            },
            ver_code: 0,
        }
    },
    components: {
        userHeader,
    },
    created() {
        // console.log(this.$store.state.user.info.safe_word)
        if (this.$store.state.user.info.safe_word) {
            this.check_safe = true
        }
    },
    methods: {
        onInput(key) {
            this.safe_word = (this.safe_word + key).slice(0, 6)
            if (this.safe_word.length === 6) {
                var form = { safe_word: this.safe_word }
                this.$post('user/safe-word/check', form).then((res) => {
                    this.safe_word = ''
                    if (res.code === 200) {
                        this.check_safe = false
                    }
                })
            }
        },

        onDelete() {
            this.safe_word = this.safe_word.slice(0, this.safe_word.length - 1)
        },

        getCode() {
            this.form.mobile = this.$store.state.user.info.mobile
            this.$post('/sms/safeWord', this.form).then((res) => {
                if (res.code === 200) {
                    this.ver_code = 60
                    const timer = setInterval(() => {
                        this.ver_code--
                    }, 1000)
                }
            })
        },

        onConfirm() {
            this.form.mobile = this.$store.state.user.info.mobile
            this.form.ver_code = 883488
            if (!this.isNumber(this.form.safe_word)) {
                this.$dialog.alert({
                    message: '安全密码必须是数字,且大于6位',
                })
                return
            }
            this.$post('/user/update', this.form).then((res) => {
                if (res.code === 200) {
                    this.$store.state.user.info.safe_word = true
                    this.check_safe = true
                    this.form.ver_code = ''
                    this.form.safe_word = ''
                    this.$router.go(-1)
                }
            })
        },
        isNumber(val) {
            var regPos = /^\d{6}$/ //非负浮点数
            // var regNeg = /^(-(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*)))$/ //负浮点数
            if (regPos.test(val)) {
                return true
            } else {
                return false
            }
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