<template>
    <div class="win-code-content" v-if="code">
        <span class="win-code-item">{{code.code_arr[0]}}</span>
        <span>+</span>
        <span class="win-code-item">{{code.code_arr[1]}}</span>
        <span>=</span>
        <span class="win-code-item red">{{code.code_gyh.gyh}}</span>
        <span class="win-code-item">{{code.code_gyh.bos}}</span>
        <span class="win-code-item red">{{code.code_gyh.sod}}</span>
    </div>

    <div class="win-code-content" v-else>
        <span class="win-code-item">{{openning | pre_zero}}</span>
        <span>+</span>
        <span class="win-code-item">{{openning | pre_zero}}</span>
        <span>=</span>
        <span class="win-code-item red">{{openning | pre_zero}}</span>
        <span class="win-code-item">{{code_bos[extend]}}</span>
        <span class="win-code-item red">{{code_sod[extend]}}</span>
    </div>
</template>

<script>
export default {
    props: {
        data: Object
    },

    data() {
        return {
            openning: 0,
            extend: 0,
            code_bos: ['大', '小'],
            code_sod: ['单', '双']
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
            this.extend -= 1
            if (this.openning < 0) this.openning = 10
            if (this.extend < 0) this.extend = 1
        }, 50)
    }
}
</script>
