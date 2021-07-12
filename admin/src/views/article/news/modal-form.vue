<template>
    <Drawer :footer-hide="true" :title="title" v-model="visible" width="820">
        <lett-loading :visible="loading" />
        <Form :label-width="76" label-position="right">
            <image-upload :action="upload.action" :height="40" :id="upload_id(1)" :width="180" class="upload-position" v-model="form.thumb" />
            <image-upload :action="upload.action" :height="85" :id="upload_id(2)" :width="180" class="upload-position second" v-model="form.thumb_wap" />

            <div class="form-padding">
                <FormItem class="mt-6" label="文章标题">
                    <Input placeholder="请输入标题" v-model="form.title"></Input>
                </FormItem>

                <FormItem label="文章状态">
                    <i-switch v-model="form.status" />
                    <span class="form-desc-middle">关闭后 APP中将不显示该文章</span>
                </FormItem>
            </div>

            <FormItem label="摘　　录">
                <Input :rows="3" placeholder="请输入摘录" type="textarea" v-model="form.excerpt"></Input>
            </FormItem>

            <FormItem label="活动时间">
                <Input placeholder="请输入活动有效时间" v-model="form.effective_date"></Input>
            </FormItem>

            <FormItem label="文章正文" v-if="visible">
                <tinymce-editor ref="editor" v-model="form.content"></tinymce-editor>
            </FormItem>

            <FormItem>
                <Button @click="confirm" type="primary">保存文章</Button>
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
            upload: {
                action: 'image/create',
            },
            default: {
                status: true,
                thumb: '',
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

        form: {
            get() {
                if (this.action == 'create') return this.default
                if (this.action == 'update') return this.config.data
            },
            set() {},
        },
    },

    methods: {
        upload_id(name) {
            return this.action + '_artilce_' + name
        },

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

<style lang="less" scoped>
.upload-position {
    position: absolute;
    right: 16px;

    &.second {
        top: 66px;
    }
}

.form-padding {
    padding-right: 220px;
}
</style>


