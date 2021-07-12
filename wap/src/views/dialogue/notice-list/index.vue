<template>
    <div class>
        <userHeader title="公告中心" />
        <div class="fix-content">
            <van-collapse v-if="items.length>0" v-model="activeNames">
                <van-collapse-item :key="index" :name="index" :title="item.title" v-for="(item,index) in items">
                    <div v-html="item.content"></div>
                </van-collapse-item>
            </van-collapse>
        </div>
    </div>
</template>
<script>
import userHeader from '@/components/user-header'
export default {
    components: {
        userHeader,
    },
    data() {
        return {
            items: [],
            activeNames: ['0'],
        }
    },
    created() {
        this.getIndex()
    },
    methods: {
        getIndex() {
            this.$get('/article/1001/index', {}, false).then((res) => {
                if (res.code === 200) {
                    this.items = res.data.items
                }
            })
        },
    },
}
</script>
<style lang="less" scoped>
.fix-content {
    height: 100%;
    position: fixed;
    overflow-y: scroll;
    width: 100%;
}
</style>