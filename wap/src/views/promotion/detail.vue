<template>
    <div>
        <userHeader :close="true" title="活动详情" />
        <div class="title" v-html="article.title" />
        <div class="content" v-html="article.content" />
        <div class="time">发布于：{{article.created_at}}</div>
    </div>
</template>
<script>
import userHeader from '@/components/user-header'
export default {
    components: {
        userHeader,
    },
    props: {
        article: {
            default: null,
            type: Object,
        },
    },
}
</script>
<style lang="less" scoped>
.content {
    padding: 20px;
}
.title {
    font-weight: bolder;
    text-align: center;
    font-size: 20px;
    padding-top: 10px;
}
.time {
    padding: 20px;
    text-align: right;
    color: #575c73;
}
</style>