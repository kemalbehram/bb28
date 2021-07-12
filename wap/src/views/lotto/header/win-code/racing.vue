<template>
    <div class="win-code-content" v-if="code">
        <div v-if="code.code_arr">
            <span class="win-code-item" v-for="item in code.code_arr" v-html="item" />
        </div>
    </div>

    <div class="win-code-content" v-else>
        <div>
            <span class="win-code-item" v-for="item in 10">{{openning | pre_zero}}</span>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        data: Object
    },

    data() {
        return {
            openning: 0
        }
    },

    computed: {
        code() {
            return this.data.win_extend
        }
    },

    created() {
        setInterval(() => {
            this.openning -= 1
            if (this.openning < 0) {
                this.openning = 10
            }
        }, 50)
    }
}
</script>

<style lang="less" scoped>
.win-code-content {
    position: relative;
    height: 26px;
    background: @yellow;
    border-radius: 26px;
    padding: 0 6px;
    display: inline-block;
    background: radial-gradient(#f1d79f, #ffbf33);
}

.win-code-item {
    width: auto;
    height: 26px;
    line-height: 26px;
    border-radius: 26px;
    background: none;
    margin-right: 2px !important;
    font-size: 14px;
}
</style>