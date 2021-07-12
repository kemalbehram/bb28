<template>
    <div class>
        <userHeader title="修改手机号" />

        <div class="base-wrap pt-40">
            <van-field :border="false" class="mb-20 round" label="新手机" size="large" v-model="form.mobile" />

            <van-field :border="false" class="mb-40 round" label="验证码" placeholder="请输入验证码" size="large" v-model="form.ver_code">
                <div slot="button" v-if="ver_code > 0">{{ver_code}}</div>
                <div @click="getCode" slot="button" v-else>获取验证码</div>
            </van-field>

            <van-button @click="onSave" block round type="danger">保存</van-button>
        </div>
    </div>
</template>

<script>
import userHeader from '@/components/user-header'
export default {
    data() {
        return {
            ver_code: '',
            form: {
                mobile: null,
                ver_code: null,
                method: 'mobileUpdate',
            },
        }
    },
    components: {
        userHeader,
    },
    methods: {
        onSave() {
            this.$post('/user/update', this.form).then((res) => {
                if (res.code === 200) {
                    this.$store.state.user.info.mobile = this.form.mobile
                    this.$router.go(-1)
                }
            })
        },

        getCode() {
            let params = { mobile: this.form.mobile }
            this.$post('/sms/mobileUpdate', params).then((res) => {
                if (res.code === 200) {
                    this.ver_code = 60
                    const timer = setInterval(() => {
                        this.ver_code--
                    }, 1000)
                }
            })
        },
    },
}
</script>