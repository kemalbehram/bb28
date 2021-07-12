<template>
    <Drawer :footer-hide="true" :title="title" v-model="visible" width="420">
        <lett-loading :visible="loading" />

        <Form :label-width="70" class="pt-10 pb-10" label-position="left">
            <FormItem label="分类名称">
                <Input class="mb-10" placeholder="请输入分类名称" v-model="form.name" />
            </FormItem>

            <FormItem class="mb-40" label="分类描述">
                <Input :rows="4" class="mb-10" placeholder="请输入分类描述 可不填写" type="textarea" v-model="form.desc" />
            </FormItem>

            <Button @click="confirm" long type="primary">确认提交</Button>
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
            loading: false,
            default: {}
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
                if (this.action == 'create') return this.default
                if (this.action == 'update') return this.config.data
            },
            set() {}
        }
    },

    methods: {
        confirm() {
            const api = this.config.api
            this.$post(api, this.form).then(res => {
                this.form = {}
                this.action == 'create' && (res.data.article_count = 0)
                const update = this.action == 'update' ? true : false
                this.$emit('success', false, res.data, update)
            })
        }
    }
}
</script>

