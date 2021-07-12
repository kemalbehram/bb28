<template>
    <div class="drawer-common-code">
        <span v-if="lotto.win_extend.code_str">{{lotto.win_extend.code_str}}</span>
        <span v-if="lotto.win_extend.code_he">{{lotto.win_extend.code_he}}</span>
        <span class="font-16" v-if="lotto.win_extend.code_bos">{{lotto.win_extend.code_bos}}</span>
        <span class="font-16" v-if="lotto.win_extend.code_sod">{{lotto.win_extend.code_sod}}</span>
    </div>
</template>

<script>
export default {
    props: {
        lotto: Object
    }
}
</script>