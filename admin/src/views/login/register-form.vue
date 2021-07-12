<template>
    <div class="register">
        <Button @click="showFun(true)" long type="default">立即注册</Button>

        <Modal :footer-hide="true" :mask-closable="closable" title="注册成为商户" v-model="showModal" width="360">
            <Form :label-width="70" class="pt-10 pb-10" label-position="left">
                <FormItem label="注册手机">
                    <Input v-model="form.mobile" />
                </FormItem>

                <FormItem label="验证码">
                    <Input v-model="form.ver_code" />
                </FormItem>

                <FormItem label="登陆密码">
                    <Input v-model="form.password" />
                </FormItem>

                <Button @click="handelRegister" long type="primary">提交注册</Button>
            </Form>

            <!-- <div slot="footer">
        <Button type="primary" long @click="handelRegister">提交注册</Button>
            </div>-->
        </Modal>
    </div>
</template>

<script>
export default {
    data() {
        return {
            form: {
                mobile: '',
                ver_code: '',
                password: ''
            },
            showModal: false,
            modalLoading: true,
            closable: false
        }
    },

    methods: {
        handelRegister() {
            this.$post
                .loading('/merchant/auth/register', this.form)
                .then(res => {})
        },

        showFun(param) {
            this.showModal = param
        }
    }
}
</script>
