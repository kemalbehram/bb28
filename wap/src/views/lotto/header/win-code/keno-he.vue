<template>
    <div class="win-code-content color-white font-14" v-if="code">
        <template v-if="code.code_arr">
            <span class="win-code-item">{{code.code_arr[0]}}</span>
            <span>+</span>
            <span class="win-code-item">{{code.code_arr[1]}}</span>
            <span>+</span>
            <span class="win-code-item">{{code.code_arr[2]}}</span>
            <span>=</span>
            <span class="win-code-item red">{{code.code_he | pre_zero}}</span>
        </template>
    </div>
    <div class="win-code-content color-white font-14" v-else>
        <span class="win-code-item">{{openning | pre_zero}}</span>
        <span>+</span>
        <span class="win-code-item">{{openning | pre_zero}}</span>
        <span>+</span>
        <span class="win-code-item">{{openning | pre_zero}}</span>
        <span>=</span>
        <span class="win-code-item red">{{openning | pre_zero}}</span>
    </div>
</template>

<script>
export default {
    props: {
        data: Object,
        items: Array,
    },

    data() {
        return {
            openning: 0,
            show_table: false,
        }
    },

    computed: {
        code() {
            return this.data.win_extend
        },
    },

    created() {
        setInterval(() => {
            this.openning -= 1
            if (this.openning < 0) {
                this.openning = 27
            }
        }, 50)
    },
}
</script>
<style lang="less" scoped>
.win-code-item {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: red;
    display: inline-block;
    line-height: 20px;
}
</style>
