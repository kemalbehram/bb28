<template>
    <Drawer :footer-hide="true" :title="trans[action].title" v-model="visible" width="720">
        <lett-loading :visible="loading" />

        <Form :label-width="70" class="pt-10 pb-10" label-position="left">
            <FormItem label="文章标题">
                <Input v-model="form.title" />
            </FormItem>

            <FormItem label="排序" v-if="update">
                <Input v-model="form.sort" />
            </FormItem>

            <FormItem class="mb-40" label="文章内容">
                <Input :rows="10" type="textarea" v-model="form.content" />
            </FormItem>

            <Button @click="confirm" long type="primary">{{trans[action].button}}</Button>
        </Form>
    </Drawer>
</template>

<script>
export default {
    props: {
        config: {},
        cat: {
            default: function () {
                return []
            },
            type: Array,
        },
        action: '',
    },

    data() {
        return {
            trans: {
                create: {
                    button: '确认创建',
                    title: '创建文章',
                },
                update: {
                    button: '确认修改',
                    title: '修改文章',
                },
            },
            loading: false,
            default: {
                title: '',
                content: '',
            },
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

        update: {
            get() {
                return this.action == 'update' ? true : false
            },
        },

        form: {
            get() {
                if (this.action == 'create') return this.default
                if (this.action == 'update') return this.config.data
            },
            set() {},
        },
    },

    methods: {
        confirm() {
            this.$post(this.config.api, this.form).then((res) => {
                this.form = this.default
                const update = this.action == 'update' ? true : false
                this.$emit('success', false, res.data, update)
                if (res.data.refresh === true) {
                    this.$emit('refresh')
                }
            })
        },
    },
}
</script>

