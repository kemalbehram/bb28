<template>
    <div class="inline-block">
        <Upload ref="upload" :headers="getHeader()" :show-upload-list="false" :default-file-list="defaultList" :on-success="handleSuccess" :format="['jpg','jpeg','png']" :max-size="2048" :on-format-error="handleFormatError" :on-exceeded-size="handleMaxSize" multiple type="drag" action="/api/admin/image/upload" class="wrap">
            <div class="icon">
                <Icon type="ios-camera" size="20"></Icon>
            </div>
        </Upload>
    </div>
</template>

<script>
import { getToken } from '@/libs/util'

export default {
    props: {
        defaultList: Array
    },
    data() {
        return {
            fileList: {}
        }
    },

    mounted() {
        this.fileList = this.$refs.upload.fileList
    },
    methods: {
        handleSuccess(res, file) {
            file.url = res.data.url
            file.name = res.data.name
            this.$emit('result')
        },
        handleFormatError(file) {
            console.log('format error')
        },
        handleMaxSize(file) {
            console.log('max size error')
        },

        getHeader() {
            var token = 'Bearer ' + getToken()
            return {
                Authorization: token
            }
        }
    }
}
</script>

<style scoped>
.inline-block {
    display: inline-block;
}
.wrap {
    display: inline-block;
    width: 98px !important;
}
.icon {
    width: 98px;
    height: 73px;
    line-height: 73px;
}
</style>

