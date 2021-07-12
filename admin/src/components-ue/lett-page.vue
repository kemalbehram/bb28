<template>
    <div class="page-wrap">
        <Page :current="page.current" :page-size="page.limit" :total="page.total" @on-change="changePage" show-elevator></Page>
    </div>
</template>

<script>
export default {
    props: {
        page: Object
    },

    methods: {
        changePage: function(page) {
            this.$emit('on-change', page)
        }
    }
}
</script>

<style scoped>
.page-wrap {
    margin: 20px 0 6px;
    overflow: hidden;
}
.page-wrap .ivu-page {
    float: right;
}
</style>
