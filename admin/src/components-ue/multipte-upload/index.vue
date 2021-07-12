<template>
    <div class="relative">
        <div class="upload-list" v-for="(item ,index ) in fileList">
            <template v-if="item.status === 'finished'">
                <div class="relative">
                    <img :src="item.url" @click="handleView(index)">
                    <div class="icon-trash">
                        <Icon type="ios-trash-outline" @click="handleRemove(item)" />
                    </div>
                </div>
            </template>
            <template v-else>
                <Progress class="pl-10 pr-10" v-if="item.showProgress" :percent="item.percentage" hide-info></Progress>
            </template>
        </div>

        <child-upload ref="upload" :defaultList="defaultList" @result="getResult" />
        <child-pop :visible.sync="modal.visible" :current="modal.current" :items="modal.items" />

    </div>
</template>
<script>
import childUpload from './upload'
import childPop from './pop'
export default {
    components: {
        childUpload,
        childPop
    },

    props: {
        defaultList: Array
    },

    data() {
        return {
            modal: {
                current: 0,
                visible: false,
                items: []
            },
            fileList: [],
            result: []
        }
    },
    methods: {
        handleView(index) {
            this.getResult()
            this.modal.current = index
            this.modal.visible = true
            this.modal.items = this.result
        },
        handleRemove(file) {
            const fileList = this.$refs.upload.fileList
            this.$refs.upload.fileList.splice(fileList.indexOf(file), 1)
            this.getResult()
        },
        getResult() {
            var temp = []
            this.fileList.forEach(item => {
                item.url && temp.push(item.url)
            })
            this.result = temp
            this.$emit('result', temp)
        }
    },
    mounted() {
        this.fileList = this.$refs.upload.fileList
    }
}
</script>
<style scope>
.upload-list {
    display: inline-block;
    width: 100px;
    height: 75px;
    text-align: center;
    line-height: 75px;
    border: 1px solid #f2f4f5;
    border-radius: 2px;
    overflow: hidden;
    background: #fff;
    position: relative;
    margin-right: 4px;
}
.upload-list img {
    width: 100%;
    height: 100%;
    vertical-align: baseline;
    object-fit: cover;
    cursor: pointer;
}

.icon-trash {
    position: absolute;
    top: 0;
    right: 0;
    height: 20px;
    width: 20px;
    color: #fff;
    line-height: 18px;
    font-size: 12px;
    background-color: rgba(0, 0, 0, 0.7);
    cursor: pointer;
}

.upload-list-cover {
    display: none;
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0, 0, 0, 0.6);
}
.upload-list:hover .upload-list-cover {
    display: block;
}
.upload-list-cover i {
    color: #fff;
    font-size: 20px;
    cursor: pointer;
    margin: 0 2px;
}
</style>
