<template>
    <div class="drawer-common-code">
        <span v-if="lotto.open_code">{{lotto.open_code}}</span>
    </div>
</template>

<script>
export default {
    props: {
        lotto: Object,
    },
}
</script>