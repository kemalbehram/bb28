<template>
    <div class="lotto-header">
        <count-down :lotto="lotto" @finish="finishCountDown()" class="lotto-header-item flex-1" />
        <div class="lotto-header-item balane font-12">
            <span>账户余额</span>
            <span>{{this.$store.state.user.wallet.balance}}</span>
        </div>
    </div>
</template>
<script>
import countDown from './count-down'
export default {
    components: {
        countDown,
    },
    props: {
        lotto: {
            default: function () {
                return this.$parent
            },
            type: Object,
        },
    },
    data() {
        return {}
    },
}
</script>
<style lang="less" scoped>
.lotto-header {
    position: fixed;
    width: 100%;
    z-index: 20;
    top: 46px;
    height: 36px;
    background: white;
    display: flex;
    border-bottom: 1px solid #f5f5f5;
    max-width: 768px;
    &-item {
        height: 32px;
        width: 51%;
        text-align: center;
        padding-top: 4px;
        line-height: 32px;
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        * {
            padding: 0 5px;
        }
    }
    &-item:first-child::after {
        content: ' ';
        position: absolute;
        height: 30px;
        width: 1px;
        background: #f3f2f4;
        right: -1px;
        top: 38%;
        margin-top: -10px;
    }
    .balane {
        span {
            display: block;
        }
        span:nth-child(2) {
            color: red;

            font-size: 14px;
        }
    }
}
</style>