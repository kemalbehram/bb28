<template>
    <div>
        <userHeader title="帐户明细">
            <h2 @click="$store.state.config.show_pop=!$store.state.config.show_pop" name="action">记录筛选</h2>
        </userHeader>
        <!-- <div class="holder"></div> -->
        <van-grid :column-num="2">
            <van-grid-item>
                <div>充值通过</div>
                <span class="money-amount">¥{{recharge_amount}}</span>
            </van-grid-item>
            <!-- <div class="van-hairline--right"></div> -->
            <van-grid-item>
                <div>提现通过</div>
                <span class="money-amount">¥{{withdraw_amount}}</span>
            </van-grid-item>
        </van-grid>

        <van-list :finished="finished" :immediate-check="false" @load="onLoad" class="log-item" finished-text="没有更多了" v-model="loading">
            <van-cell :key="index" :label="item.created_at" v-for="(item,index) in itemList">
                <template #title>
                    <span class="custom-title">{{item.type==0?'充值':'提现'}}</span>
                </template>

                <template #default>
                    <span :class="item.amount >0 ? 'color-red' :''" class="custom-content">{{item.amount}}</span>
                    <div>
                        <span v-if="item.status==1">审核中</span>
                        <span class="color-green" v-else-if="item.status==2">通过</span>
                        <span class="color-red" v-else-if="item.status==3">拒绝</span>
                    </div>
                </template>
            </van-cell>
        </van-list>

        <van-popup :style="{ height: '80%' }" position="bottom" v-model="show_pop">
            <!-- <van-datetime-picker :max-date="maxDate" :min-date="minDate" @cancel="showPicker = false;" @confirm="getCurrentTime" title="选择年月日" type="date" v-model="currentDate" /> -->
            <picker :field="field" :option="option1" @success="success" />
        </van-popup>
    </div>
</template>
<script>
import userHeader from '@/components/user-header'
import picker from './picker'
import { Notify } from 'vant'
export default {
    name: 'userAccount',
    components: {
        userHeader,
        picker,
    },
    data() {
        return {
            minDate: new Date(2020, 0, 1),
            maxDate: new Date(2021, 10, 1),
            currentDate: new Date(),
            start: '',
            end: '',
            itemList: [],
            loading: false,
            finished: false,
            page: 1, //请求第几页
            total: 0, //总共的数据条数
            dateSearch: false,
            recharge_amount: 0,
            withdraw_amount: 0,
            value: 'recharge',
            option1: [
                { text: '全部', value: 0 },
                { text: '充值', value: 'recharge' },
                { text: '提现', value: 'withdraw' },
            ],
        }
    },
    created() {
        this.$nextTick(function () {
            if (this.field != '') {
                this.value = this.field
            }
            this.getToday()
            this.getWalletLog()
            // this.getTotalChange()
        })
    },
    computed: {
        field() {
            return this.$route.query.field
        },
        show_pop: {
            get() {
                return this.$store.state.config.show_pop
            },
            set() {},
        },
    },
    watch: {
        field(newval, oldval) {
            if (newval != oldval) {
                this.value = this.field
                this.page = 0
                this.itemList = []
                this.value = newval
                this.getWalletLog()
                // this.getTotalChange()
            }
        },
    },
    methods: {
        onLoad() {
            this.page++
            this.getWalletLog()
        },
        showTimePick() {
            this.$store.state.config.show_pop = true
        },
        getCurrentTime(value) {
            let nowMonth = value.getMonth() + 1
            this.nowDate = value.getFullYear() + '-' + nowMonth + '-' + value.getDate()
            this.$store.state.config.show_pop = false
            this.dateSearch = true
            this.page = 0
            this.itemList = []
            this.getWalletLog()
            // this.getTotalChange()
        },
        getToday() {
            var date = new Date()
            let nowMonth = date.getMonth() + 1
            this.nowDate = date.getFullYear() + '-' + nowMonth + '-' + date.getDate()
            console.log(this.nowDate)
        },
        getWalletLog() {
            let params = {
                page: this.page,
                start: this.start,
                end: this.end,
                type: this.value,
            }
            // let request_path = ''
            // request_path = this.value == 'withdraw' ? this.withdraw_url : this.recharge_url
            this.$get('recharge/index', params).then((res) => {
                if (res.code !== 200) return (this.loading = res.message)
                this.loading = false
                this.total = res.data.page.total
                // console.log(res.data)
                this.recharge_amount = res.data.recharge_amount
                this.withdraw_amount = res.data.withdraw_amount
                let rows = res.data.items

                if (this.page != 1 && this.total < res.data.page.current * 10) {
                    // Notify('加载完毕')
                }
                if (rows == null || rows.length === 0) {
                    // 加载结束
                    this.finished = true

                    if (this.page == 1) {
                        return
                    }
                }

                // 将新数据与老数据进行合并
                this.itemList = this.itemList.concat(rows)

                //如果列表数据条数>=总条数，不再触发滚动加载
                if (this.itemList.length >= this.total) {
                    this.finished1 = true
                }
            })
        },
        // getTotalChange() {
        //     let params = {
        //         // page: this.page,
        //         start: this.start,
        //         end: this.end,
        //         field: this.value,
        //     }
        //     this.$get('/wallet/totalChange', params).then((res) => {
        //         if (res.code !== 200) return (this.loading = res.message)
        //         this.income = res.data.income
        //         this.expense = res.data.expense
        //     })
        // },

        success(param) {
            this.page = 0
            this.itemList = []
            this.start = param.start
            this.end = param.end
            this.value = param.value
            this.$store.state.config.show_pop = false
            this.getWalletLog()
            // this.getTotalChange()
        },
    },
}
</script>
<style lang="less" scoped>
/deep/.van-grid {
    flex-wrap: nowrap;
}
/deep/.van-button {
    padding: 0 6px;
}
.dropdown-menu {
    /deep/.van-dropdown-menu__item {
        width: 100px;
    }
}
.money {
    span {
        display: block;
    }
}
.van-hairline--right {
    width: 1px;
}
.money-amount {
    color: #e4593e;
    line-height: 20px;
}
.holder {
    margin-top: 6vh;
}
.log-item {
    overflow: scroll;
    height: calc(100% - 6vh - 71px);
    position: fixed;
    width: 100%;
}
</style>