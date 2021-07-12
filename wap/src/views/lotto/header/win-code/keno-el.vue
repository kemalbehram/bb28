<template>
    <div class="win-code-content" v-if="code">
        <template v-if="code.code_arr">
            <span class="win-code-item">{{code.code_arr[0]}}</span>
            <span>+</span>
            <span class="win-code-item">{{code.code_arr[1]}}</span>
            <span>=</span>
            <span class="win-code-item red">{{code.code_he | pre_zero}}</span>
        </template>
    </div>

    <div class="win-code-content" v-else>
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
        data: Object
    },

    data() {
        return {
            openning: 0
        }
    },

    computed: {
        code() {
            return this.data.win_ext_el
        }
    },

    created() {
        setInterval(() => {
            this.openning -= 1
            if (this.openning < 0) {
                this.openning = 27
            }
        }, 50)
    }
}
</script>