<template>
    <Card class="full-height" dis-hover>
        <div class="flex-center position-ref full-height">
            <div class="code">{{code}}</div>·
            <div class="message">正在开发中...</div>
        </div>
    </Card>
</template>

<script>
export default {
    computed: {
        code() {
            return this.$route.meta.title
        }
    }
}
</script>

<style lang="less" scoped>
/deep/ .ivu-card-body {
    height: 100%;
}
.full-height {
    height: 100%;
    color: #636b6f;
    margin: 0;
}

.flex-center {
    align-items: center;
    display: flex;
    justify-content: center;
}

.position-ref {
    position: relative;
}

.code {
    font-size: 18px;
    padding: 0 15px 0 15px;
    text-align: center;
}

.message {
    font-size: 18px;
    text-align: center;
    padding: 10px;
}
</style>

