<template>
    <div class="relative">
        <userHeader title="提成记录">
            <h2 @click="showPicker" name="action">记录筛选</h2>
        </userHeader>
        <van-grid :column-num="1">
            <van-grid-item>
                <div>提成收益</div>
                <span class="money-amount">¥{{amount}}</span>
            </van-grid-item>
        </van-grid>
        <van-list :error-text="errorText" :error.sync="error" :finished="finished" @load="getIndex" class="reset mb-14" v-if="items.length > 0" v-model="loading">
            <van-cell :key="index" v-for="(item,index) in items">
                <template #label>
                    <div class="cell-label">
                        <span class="custom-title">{{item.created_at}}</span>
                        <span class="custom-title">{{item.extend.balance}}</span>
                    </div>
                </template>
                <template #title>
                    <span class="custom-title">编号:{{item.id}}</span>
                </template>

                <template #default>
                    <span class="color-red custom-content">+ {{item.amount}}</span>
                </template>
            </van-cell>
        </van-list>
        <van-popup :style="{ height: '80%' }" position="bottom" v-model="$store.state.config.show_pop">
            <picker @success="success" />
        </van-popup>
    </div>
</template>
<script>
import userHeader from '@/components/user-header'
import picker from './picker'
export default {
    components: {
        userHeader,
        picker,
    },
    data() {
        return {
            start: '',
            end: '',
            loading: false,
            finished: false,
            error: false,
            errorText: '请求失败，点击重新加载',
            page: {
                current: 0,
            },
            items: [],
            amount: 0,
            game: '',
            room: '',
        }
    },
    created() {
        this.getIndex()
    },
    methods: {
        getIndex() {
            let params = {
                start: this.start,
                end: this.end,
                game: this.game,
                room: this.room,
            }
            this.$getList('/offline/rebate', params).then((res) => {
                if (res.data.page.total === 0) {
                    this.errorText = '暂无相关记录 请重新查询'
                }
                this.amount = res.data.amount
            })
        },
        showPicker() {
            this.$store.state.config.show_pop = true
        },
        success(param) {
            this.start = param.start
            this.end = param.end
            this.game = param.game
            this.room = param.room
            this.page.current = 0
            this.items = []
            this.amount = 0
            this.$store.state.config.show_pop = false
            this.getIndex()
        },
    },
}
</script>
<style lang="less" scoped>
.money-amount {
    color: #e4593e;
    line-height: 20px;
}
/deep/.van-grid-item__content {
    padding: 7px;
}
.cell-label {
    width: 200%;
    display: flex;
    justify-content: space-between;
}
.reset {
    position: fixed;
    width: 100%;
    overflow-y: scroll;
    height: calc(100% - 6vh - 53px);
}
</style>