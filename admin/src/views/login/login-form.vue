<template>
    <Form :model="form" @keydown.enter.native="handleLogin" ref="loginForm">
        <lett-loading :visible="loading" />

        <FormItem prop="username">
            <Input placeholder="请输入用户名" v-model="form.username">
                <span slot="prepend">
                    <Icon :size="16" type="ios-person"></Icon>
                </span>
            </Input>
        </FormItem>
        <FormItem prop="password">
            <Input placeholder="请输入密码" type="password" v-model="form.password">
                <span slot="prepend">
                    <Icon :size="14" type="md-lock"></Icon>
                </span>
            </Input>
        </FormItem>

        <Button @click="handleLogin" long type="primary">登录</Button>
    </Form>
</template>
<script>
import store from '@/store'
export default {
    name: 'LoginForm',
    data() {
        return {
            loading: false,
            form: {
                username: '',
                password: ''
            }
        }
    },
    methods: {
        handleLogin(params) {
            store.dispatch('handleLogin', this).then(res => {
                if (res.code === 200) {
                    this.$router.push({
                        name: 'home'
                    })
                }
            })
        },

        handleSubmit() {
            this.$refs.loginForm.validate(valid => {
                if (valid) {
                    this.$emit('on-success-valid', this.form)
                }
            })
        }
    }
}
</script>
