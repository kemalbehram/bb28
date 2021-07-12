<template>
    <Card dis-hover title="个人资料维护">
        <lett-loading :visible="loading" @click="getData()" />
        <Form :label-width="100" class="width-50 pt-20 pb-10" label-position="right">
            <FormItem label="昵　　称">
                <Input placeholder="请输入新的昵称" v-model="profile.nickname" />
            </FormItem>

            <FormItem label="用户名">
                <Input disabled v-model="profile.username" />
            </FormItem>

            <FormItem class="mb-10">
                <Button @click="profileUpdate" type="primary">保存资料</Button>
            </FormItem>
        </Form>

        <Divider />

        <Form :label-width="100" class="width-50 pt-10 pb-10" label-position="right">
            <FormItem label="当前密码">
                <Input placeholder="请输入当前使用密码" type="password" v-model="form.current" />
                <p class="form-desc-base">如果您忘了当前登陆密码，请联系管理员重置</p>
            </FormItem>

            <FormItem label="新密码">
                <Input placeholder="请输入新密码" type="password" v-model="form.password" />
            </FormItem>

            <FormItem label="确认密码">
                <Input placeholder="请在次输入新密码" type="password" v-model="form.password_confirmation" />
            </FormItem>

            <FormItem>
                <Button @click="passwordUpdate" type="primary">保存密码</Button>
            </FormItem>
        </Form>
    </Card>
</template>
<script>
export default {
    name: 'personalHome',
    data() {
        return {
            loading: false,
            message: '',
            profile: {},
            form: {}
        }
    },
    created() {
        this.getData()
    },
    methods: {
        getData() {
            this.$get('/auth/get').then(res => {
                this.profile = res.data
            })
        },

        profileUpdate() {
            this.profile.method = 'profileUpdate'
            this.$post('/auth/update', this.profile)
        },

        passwordUpdate() {
            this.form.method = 'passwordUpdate'
            this.$post('/auth/update', this.form)
        }
    }
}
</script>
