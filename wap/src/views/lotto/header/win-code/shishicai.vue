<template>
    <div class="win-code-content" v-if="code">
        <template v-if="code.code_arr">
            <span class="win-code-item red">{{code.code_arr[0]}}</span>
            <span class="win-code-item">{{code.code_arr[1]}}</span>
            <span class="win-code-item red">{{code.code_arr[2]}}</span>
            <span class="win-code-item">{{code.code_arr[3]}}</span>
            <span class="win-code-item red">{{code.code_arr[4]}}</span>
        </template>
    </div>

    <div class="win-code-content" v-else>
        <span class="win-code-item red">{{openning}}</span>
        <span class="win-code-item">{{openning}}</span>
        <span class="win-code-item red">{{openning}}</span>
        <span class="win-code-item">{{openning}}</span>
        <span class="win-code-item red">{{openning}}</span>
    </div>
</template>

<script>
export default {
    props: {
        data: Object,
        items: Array
    },

    data() {
        return {
            openning: 0,
            show_table: false
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
                this.openning = 9
            }
        }, 50)
    }
}
</script>
