<template>
    <Drawer :footer-hide="true" title="创建管理员" v-model="visible" width="720">
        <lett-loading :visible="loading" />

        <Form :label-width="70" class="mt-20" label-position="right">
            <FormItem label="昵称">
                <Input placeholder="请输入管理员昵称" v-model="form.nickname" />
            </FormItem>

            <FormItem label="用户名">
                <Input placeholder="请输入登录用户名" v-model="form.username" />
            </FormItem>

            <FormItem class="mb-40" label="初始密码">
                <Input placeholder="请输入初始登录密码" v-model="form.password" />
            </FormItem>

            <Button @click="confirm" long type="primary">确认创建</Button>
        </Form>
    </Drawer>
</template>

<script>
export default {
    props: {
        config: {},
    },

    data() {
        return {
            loading: false,
        }
    },

    computed: {
        visible: {
            get() {
                return this.config.visible
            },
            set() {
                this.config.visible = false
                this.config.data = {}
            },
        },

        form: {
            get() {
                return {
                    password: '',
                }
            },
            set() {},
        },
    },

    methods: {
        confirm() {
            this.$post(this.config.api, this.form).then((res) => {
                this.form = {}
                this.$emit('success', false, res.data)
            })
        },
    },
}
</script>

