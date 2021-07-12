<template>
    <div>
        <userHeader title="代理通知" />
        <van-cell-group>
            <van-cell v-for="(item,index) in group">
                <template #title>{{index+1}}.{{item.title}}:{{item.value}}</template>
                <template #default>
                    <van-icon @click="copy(item.value)" color="#4F8DFE" name="coupon-o" size="20" />
                </template>
            </van-cell>
        </van-cell-group>
    </div>
</template>
<script>
import userHeader from '@/components/user-header'
import { Toast } from 'vant'
export default {
    components: {
        userHeader,
    },
    data() {
        return {
            group: [],
        }
    },
    methods: {
        copy(contanct) {
            this.copyText(contanct)
            Toast.success('复制成功')
        },
        copyText(text) {
            var tag = document.createElement('input')
            tag.setAttribute('id', 'cp_hgz_input')
            tag.value = text
            document.getElementsByTagName('body')[0].appendChild(tag)
            document.getElementById('cp_hgz_input').select()
            document.execCommand('copy')
            document.getElementById('cp_hgz_input').remove()
        },
        getNotice() {
            this.$get('/offline/agent-notice').then((res) => {
                this.group = res.data.notice
            })
        },
    },
    created() {
        this.getNotice()
    },
}
</script>
<style lang="less" scoped>
.van-cell__title {
    flex: 2;
}
</style>