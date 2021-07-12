<template>
    <div class="rebate-content">
        <userHeader title="返水规则" />
        <rule :child="false" />
    </div>
</template>
<script>
import userHeader from '@/components/user-header'
import rule from './rule'
export default {
    components: {
        userHeader,
        rule,
    },
}
</script>
