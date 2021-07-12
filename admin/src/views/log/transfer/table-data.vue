<template>
    <div class="relative">
        <lett-loading :visible="loading" @click="getIndex" />

        <Table :columns="columns" :data="items" :height="300 | table_height" border>
            <table-user :avatar="false" :data="row.drawee_user" slot="drawee" slot-scope="{row}" />
            <table-user :avatar="false" :data="row.payee_user" slot="payee" slot-scope="{row}" />
            <template slot="created_at" slot-scope="{row}">
                <Button class="mr-6" disabled size="small" v-if="row.canceled_at">已撤销</Button>
                <table-date :date="row.created_at" class="color-black" />
            </template>
            <template slot="award_agent_base" slot-scope="{row}">
                <template v-if="row.award_agent_refer">{{parseFloat(row.award_agent_refer) + parseFloat(row.award_agent_base) | currency}}</template>
                <template v-else-if="row.award_agent_base">{{row.award_agent_base}}</template>
                <span class="color-desc" v-else>0.000</span>
            </template>
            <template slot="award_user_base" slot-scope="{row}">
                <span v-html="row.award_user_base" v-if="row.award_user_base"></span>
                <span class="color-desc" v-else>0.000</span>
            </template>
            <template slot="transfer_fee" slot-scope="{row}">
                <span class="color-red" v-html="row.transfer_fee" v-if="row.transfer_fee"></span>
                <span class="color-desc" v-else>0.000</span>
            </template>
            <template slot="extend" slot-scope="{row}">
                <template v-if="row.extend">
                    <span class="custom-tag bg-none mr-10">{{row.extend.model_title}}</span>
                    <span class="custom-tag bg-none mr-10">
                        <span class="color-desc">当前流水</span>
                        {{row.extend.bet_usable}}
                    </span>
                    <span class="custom-tag bg-none mr-10" v-if="row.extend.fee_limit">
                        <span class="color-desc">最近充值</span>
                        {{row.extend.fee_limit}}
                    </span>
                    <span class="custom-tag bg-none mr-10" v-if="row.extend.bet_rate">
                        <span class="color-desc">流水倍数</span>
                        {{row.extend.bet_rate}}
                    </span>
                    <span class="custom-tag bg-none" v-if="row.extend.bet_usable_new">
                        <span class="color-desc">剩余流水</span>
                        {{row.extend.bet_usable_new }}
                    </span>
                </template>
            </template>
        </Table>
        <lett-page :page="page" @on-change="onPage" />
    </div>
</template>

<script>
import JsMixins from '@/components-ue/table/table.js'
export default {
    mixins: [JsMixins],
    data() {
        return {
            dataware: {
                index: {
                    api: '/transfer/index',
                },
            },
            columns: [
                {
                    title: '流水ID',
                    width: 120,
                    key: 'id',
                    align: 'left',
                },
                {
                    title: '付款用户',
                    slot: 'drawee',
                    width: 160,
                },
                {
                    title: '收款用户',
                    slot: 'payee',
                    width: 160,
                },
                {
                    title: '金额',
                    key: 'amount',
                    align: 'right',
                    width: 120,
                },

                {
                    title: '代理利润',
                    slot: 'award_agent_base',
                    align: 'right',
                    width: 120,
                },
                {
                    title: '笔笔返',
                    slot: 'award_user_base',
                    align: 'right',
                    width: 120,
                },
                {
                    title: '手续费',
                    slot: 'transfer_fee',
                    align: 'right',
                    width: 120,
                },
                {
                    title: '其它',
                    slot: 'extend',
                    align: 'left',
                },
                {
                    title: '创建时间',
                    slot: 'created_at',
                    width: 190,
                    align: 'right',
                },
            ],
        }
    },
}
</script>