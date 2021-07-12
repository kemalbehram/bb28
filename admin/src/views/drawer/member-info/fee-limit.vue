<template>
    <div class="stats-table-wrap item-6">
        <template v-if="data.fee_model === 'bet'">
            <div class="stats-table-item">
                <h5 class="amount" v-html="data.bet_usable" />
                <h6 class="title">剩余流水</h6>
            </div>

            <div class="stats-table-item">
                <h5 class="amount" v-html="data.free_trans" />
                <h6 class="title">免提额度</h6>
            </div>
        </template>

        <template v-if="data.fee_model === 'base'">
            <div class="stats-table-item">
                <h5 class="amount">{{data.bet_rate | currency}}</h5>
                <h6 class="title">流水倍数</h6>
            </div>

            <div class="stats-table-item">
                <h5 class="amount">{{data.bet_need | currency}}</h5>
                <h6 class="title">还需流水</h6>
            </div>

            <div class="stats-table-item">
                <h5 class="amount">{{data.fee_limit | currency}}</h5>
                <h6 class="title">最近充值</h6>
            </div>
        </template>

        <div class="stats-table-item">
            <h5 class="amount">{{data.model_title }}</h5>
            <h6 class="title">当前模式</h6>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        data: Object,
        wallet: Object,
    },
}
</script>