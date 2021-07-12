<template>
    <div class="stats-table-wrap item-6">
        <div :key="item.field" class="stats-table-item" v-for="(item,index) in fields">
            <router-link :to="item.route" class="route-link" v-if="item.route">详情</router-link>
            <div :class="item.class" class="amount">
                <span v-html="stats[item.field]"></span>
                <span class="ml-6 mr-6" v-if="item.fie_2">/</span>
                <span :class="item.cla_2" v-if="item.fie_2">{{stats[item.fie_2]}}</span>
            </div>
            <div class="title" v-html="item.title"></div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        fields: Array,
        stats: Object,
    },
}
</script>