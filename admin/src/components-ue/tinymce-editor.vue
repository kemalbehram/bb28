<template>
    <div :id="element">
        <editor :disabled="disabled" :init="init" @onClick="onClick" v-model="content"></editor>
    </div>
</template>
<script>
import tinymce from 'tinymce/tinymce'
import Editor from '@tinymce/tinymce-vue'
import 'tinymce/themes/silver'
import 'tinymce/icons/default'
// 编辑器插件plugins
// 更多插件参考：https://www.tiny.cloud/docs/plugins/
import 'tinymce/plugins/image' // 插入上传图片插件
// import 'tinymce/plugins/media' // 插入视频插件
import 'tinymce/plugins/table' // 插入表格插件
import 'tinymce/plugins/code' // 插入编辑源码插件
// import 'tinymce/plugins/lists' // 列表插件
// import 'tinymce/plugins/wordcount' // 字数统计插件
export default {
    components: {
        Editor,
    },
    props: {
        element: {
            type: String,
            default: 'tinymce-editor',
        },
        value: {
            type: String,
            default: '',
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        plugins: {
            type: [String, Array],
            // default: 'lists image media table wordcount'
            default: 'image',
        },
        toolbar: {
            type: [String, Array],
            default: 'undo redo |  formatselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | lists image media table code | removeformat',
        },
    },
    data() {
        return {
            init: {
                selector: '#' + this.element,
                language_url: 'https://ooo28-public.oss-accelerate.aliyuncs.com/tinymce/langs/zh_CN.js',
                language: 'zh_CN',
                skin_url: 'https://ooo28-public.oss-accelerate.aliyuncs.com/tinymce/skins/ui/oxide',
                height: 400,
                plugins: this.plugins,
                toolbar: this.toolbar,
                branding: false,
                menubar: false,
                // 此处为图片上传处理函数，这个直接用了base64的图片形式上传图片，
                // 如需ajax上传可参考https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_handler
                images_upload_handler: (blobInfo, success, failure) => {
                    const img = 'data:image/jpeg;base64,' + blobInfo.base64()
                    const form = { file: img }
                    this.$post('/image/create', form).then((res) => {
                        if (res.code === 200) {
                            success(res.data.url)
                        }
                    })
                },
            },
            content: this.value,
        }
    },
    // mounted() {
    //     var vm = this
    //     tinymce.init(vm.init)
    // },
    methods: {
        // 添加相关的事件，可用的事件参照文档=> https://github.com/tinymce/tinymce-vue => All available events
        // 需要什么事件可以自己增加
        onClick(e) {
            this.$emit('onClick', e, tinymce)
        },
        // 可以添加一些自己的自定义事件，如清空内容
        clear() {
            this.content = ''
        },
    },
    watch: {
        value(newValue) {
            this.content = newValue
        },
        content(newValue) {
            this.$emit('input', newValue)
        },
    },
}
</script>
