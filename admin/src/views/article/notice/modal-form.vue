<template>
    <Drawer :footer-hide="true" :title="title" v-model="visible" width="820">
        <lett-loading :visible="loading" />
        <Form :label-width="76" label-position="right">
            <FormItem class="mt-6" label="公告标题">
                <Input placeholder="请输入标题" v-model="form.title"></Input>
            </FormItem>

            <FormItem label="公告状态">
                <i-switch v-model="form.status" />
                <span class="form-desc-middle">关闭后 网页中将不显示该公告</span>
            </FormItem>

            <FormItem label="公告类型">
                <Select style="width:100px" v-model="form.type">
                    <Option :key="index" :value="item.value" v-for="(item,index) in types">{{ item.label }}</Option>
                </Select>
            </FormItem>

            <FormItem label="公告内容" v-if="visible">
                <tinymce-editor element="notice-editor" plugins="table code" ref="editor" v-model="form.content"></tinymce-editor>
            </FormItem>

            <FormItem>
                <Button @click="confirm" type="primary">保存公告</Button>
            </FormItem>
        </Form>
    </Drawer>
</template>

<script>
import TinymceEditor from '@/components-ue/tinymce-editor'
export default {
    components: {
        TinymceEditor,
    },
    props: {
        config: {},
        action: '',
        title: '',
    },

    data() {
        return {
            loading: false,
            default: { status: true },
            types: [
                {
                    value: 'index',
                    label: '首页弹出',
                },
                {
                    value: 'scroll',
                    label: '首页滚动',
                },
                {
                    value: 'list',
                    label: '列表',
                },
                {
                    value: 'game',
                    label: '游戏',
                },
            ],
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
                if (this.action == 'create') return this.default
                if (this.action == 'update') return this.config.data
            },
            set() {},
        },
    },

    methods: {
        confirm() {
            const api = this.config.api
            this.$post(api, this.form).then((res) => {
                this.form = {}
                const update = this.action == 'update' ? true : false
                this.$emit('success', false, res.data, update)
            })
        },
    },
}
</script>


