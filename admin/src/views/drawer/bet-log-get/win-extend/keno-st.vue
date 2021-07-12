<template>
    <div class="drawer-common-code">
        <span v-if="lotto.win_ext_st.code_str">{{lotto.win_ext_st.code_str}}</span>
        <span v-if="lotto.win_ext_st.code_he">{{lotto.win_ext_st.code_he}}</span>
    </div>
</template>

<script>
export default {
    props: {
        lotto: Object
    }
}
</script>