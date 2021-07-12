<template>
    <div class="relative">
        <lett-loading :visible="loading" @click="getIndex" />

        <Table :columns="tableColumns" :data="items" :height="300 | table_height" border>
            <span @click="$store.dispatch('onBetLogGroup', row.id)" class="pointer" slot="id" slot-scope="{row}" v-if="$parent.$parent.config.value.model=== 'group'">{{row.id}}</span>
            <span @click="$store.dispatch('onBetLogGet', row.id)" class="pointer" slot="id" slot-scope="{row}" v-else>{{row.id}}</span>

            <table-user :data="row.user" slot="user" slot-scope="{row}" v-if="row.user" />

            <template slot="lotto" slot-scope="{row}">
                <span class="color-black">{{row.lotto_title}}</span>
                <span class="color-black mr-6">-{{row.play_type | play_type}}</span>
                <span class="custom-tag" v-if="assign">{{row.short_id}}</span>
                <span @click="$store.dispatch('onLottoGet',[row.lotto_id,row.lotto_name])" class="custom-tag pointer" v-else>{{row.short_id}}</span>
            </template>

            <template slot="bonus" slot-scope="{row}">
                <span class="color-green font-13" v-if="row.status == 1">待开奖</span>
                <span :class="amountClass(row.bonus)" v-if="row.status == 2">{{row.bonus}}</span>
                <span class="color-red font-13" v-if="row.status == 3">取消投注</span>
                <span class="color-red font-13" v-if="row.status == 4">投注异常</span>
            </template>

            <span :class="amountClass(row.profit)" slot="profit" slot-scope="{row}">{{row.profit}}</span>

            <table-date :date="row.created_at" class="color-black" slot="created_at" slot-scope="{row}" />

            <template slot="action" slot-scope="{row}">
                <Button @click="$store.dispatch('onBetLogGroup', row.id)" size="small" type="primary" v-if="$parent.$parent.config.value.model=== 'group'">详情</Button>
                <Button @click="$store.dispatch('onBetLogGet', row.id)" size="small" type="primary" v-else>详情</Button>
            </template>
        </Table>
        <lett-page :page="page" @on-change="onPage" />
    </div>
</template>

<script>
import JsMixins from '@/components-ue/table/table.js'
export default {
    mixins: [JsMixins],
    props: {
        assign: {
            default: null,
            type: [String, Number],
        },

        tableColumns: Array,
    },

    methods: {
        getIndex(page = 1, params = {}) {
            if (this.assign) {
                params.user_id = this.assign
            } else {
                params.user = true
            }
            params.model = this.$parent.$parent.config.value.model
            const api = 'lotto/bet-log/index'
            return this.$dataware.index(api, this, page, params)
        },

        amountClass(amount) {
            if (parseFloat(amount) > 0) {
                return 'color-red'
            }
            if (parseFloat(amount) < 0) {
                return 'color-black'
            }
            return 'color-sub'
        },
    },
}
</script>

<style lang="less" scoped>
.bet-status {
    background: @white;
    margin-left: 6px !important;
    &.status-1 {
        border-color: @green;
        color: @green;
    }

    &.status-3 {
        border-color: @red;
        color: @red;
    }
}
</style>