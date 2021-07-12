<template>
    <div @click="$store.dispatch('onMemberInfo', data.id)" class="table-pointer">
        <Avatar :src="data.avatar" class="mr-10" shape="circle" size="22" v-if="avatar" />
        <span class="mr-6 font-14">{{data.nickname}}</span>
        <Tag color="red" v-if="data.disable">禁登</Tag>
        <Tag color="red" v-if="data.bet === false">禁投</Tag>
        <Tag color="red" v-if="data.transfer === false">禁转</Tag>
        <Tag color="volcano" v-if="data.trial">试玩</Tag>
    </div>
</template>

<script>
export default {
    props: {
        data: Object,
        avatar: {
            default: true,
            type: Boolean
        }
    }
}
</script>