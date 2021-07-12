<template>
    <van-popup @closed="popClose" @open="getBetLog" class="popup-container" position="right" v-model="visible">
        <div class="record-header van-hairline--bottom">
            <!-- <div @click="visible = false" class="record-header-back">LOG</div> -->
            <div class="record-header-title">最近投注记录</div>
            <div class="display-flex head-info">
                <p class="record-header-desc">
                    <span>总资产：</span>
                    <span class="color-red">{{$store.state.user.wallet.total}}</span>
                </p>
                <div class="button">
                    <van-button @click="visible = false" color="red" hairline size="small" type="default">关闭</van-button>
                    <van-button class="ml-10" color="red" size="default" to="/vote" type="small">所有记录</van-button>
                </div>
            </div>
        </div>
        <bet-log-items :items="items" :room="room" :show-icon="false" ref="logItem" />
    </van-popup>
</template>

<script>
import BetLogItems from '@/views/wallet/bet-log/items'
export default {
    components: {
        BetLogItems
    },
    props: {
        room: 0
    },
    data() {
        return {
            loading: false,
            items: [],
            visible: false
        }
    },

    methods: {
        getBetLog() {
            // this.data.lotto.length === 0 && (this.loading = true)
            this.loading = true
            var api = '/wallet/bet-log'
            this.$get(api).then(res => {
                if (res.code !== 200) return (this.loading = res.message)
                this.loading = false
                this.items = res.data.items.slice(0, 7)
            })
        },

        popClose() {
            if (this.$refs.logItem) {
                this.$refs.logItem.collapse = null
            }
        }
    }
}
</script>

<style lang="less" scoped>
.popup-container {
    width: 80%;
    background: @bg;
    height: 100%;
    z-index: 2500 !important;
}
.record-header {
    height: 78px;
    background: #fff;
    margin-bottom: 12px;
    line-height: 24px;
    padding: 16px 16px 10px;
    position: relative;
    font-size: 16px;
    overflow: hidden;
    .head-info {
        justify-content: space-between;
        .button {
            margin-top: 10px;
        }
    }
    &-title {
        font-size: 16px;
        margin-bottom: 2px;
    }
    &-desc {
        font-size: 14px;
        line-height: 22px;
        color: @sub;
    }
}

.record-content {
    position: absolute !important;
    width: 100%;
    height: calc(100% - 150px);
    overflow: scroll;

    /deep/.log-item {
        padding: 12px 16px 10px;
    }

    /deep/.van-collapse-item__content {
        padding: 0 12px 16px;
    }
}
</style>