<template>
    <Drawer :footer-hide="true" :title="title" v-model="visible" width="720">
        <lett-loading :visible="loading" />
        <Form :label-width="70" class="mt-20" label-position="right">
            <FormItem label="昵　称">
                <Input placeholder v-model="form.nickname" />
            </FormItem>

            <FormItem label="用户名">
                <Input placeholder v-model="form.username" />
            </FormItem>

            <FormItem label="禁止使用">
                <i-switch v-model="form.disable" />
                <span class="form-desc-middle">开启禁用后，该管理员将无法使用</span>
            </FormItem>

            <FormItem class="pb-20">
                <Button @click="updateProfile" type="primary">保存资料</Button>
            </FormItem>

            <Divider />

            <FormItem class="pt-20" label="新密码">
                <Input placeholder="请输入用户新密码" v-model="form.password" />
            </FormItem>

            <FormItem label="确认密码">
                <Input placeholder="请再次输入新密码" v-model="form.password_confirmation" />
            </FormItem>

            <FormItem>
                <Button @click="updatePassword" type="primary">保存密码</Button>
            </FormItem>
        </Form>
    </Drawer>
</template>

<script>
export default {
    props: {
        config: {},
        action: '',
        title: ''
    },

    data() {
        return {
            loading: false
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
            }
        },

        form: {
            get() {
                return this.config.data
            },
            set() {}
        }
    },

    methods: {
        updateProfile() {
            this.form.method = 'profileUpdate'
            this.$post(this.config.api, this.form).then(res => {
                this.$emit('success', true, res.data, true)
            })
        },

        updatePassword() {
            this.form.method = 'passwordUpdate'
            this.$post(this.config.api, this.form)
        },
        updateAvatar(res) {
            this.$emit('success', true, res, true)
        }
    }
}
</script>

<style lang="less" scoped>
.cell-content {
    line-height: 22px;
    padding: 6px 0;
}
</style>