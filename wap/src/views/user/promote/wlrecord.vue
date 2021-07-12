<template>
    <div class="relative">
        <userHeader title="输赢记录" />
        <div>
            <van-grid :border="false" :column-num="3">
                <van-grid-item>
                    <van-button @click="showTimePick('start')" plain style="border:none;font-size:12px">
                        <b>
                            {{start}}
                            <van-icon name="arrow-down" />
                        </b>
                    </van-button>
                </van-grid-item>
                <van-grid-item>
                    <span>至</span>
                </van-grid-item>
                <van-grid-item>
                    <van-button @click="showTimePick('end')" plain style="border:none;font-size:12px">
                        <b>
                            {{end}}
                            <van-icon name="arrow-down" />
                        </b>
                    </van-button>
                </van-grid-item>
            </van-grid>

            <van-list :error-text="errorText" :error.sync="error" :finished="finished" @load="getIndex" class="reset mb-14" v-if="items.length > 0" v-model="loading">
                <van-cell :key="index" v-for="(item,index) in items">
                    <template #label>
                        <div class="cell-label">
                            <span class="custom-title">流水:{{item.id}}</span>
                            <span class="custom-title">盈亏:{{item.bonus-item.total}}</span>
                        </div>
                    </template>
                    <template #title>
                        <span class="custom-title">UID:{{item.user_id}}</span>
                    </template>

                    <template #default>
                        <span class="color-red custom-content">{{item.lotto_title}} - {{item.lotto_id}}期</span>
                    </template>
                </van-cell>
            </van-list>
        </div>

        <van-popup :style="{ height: '80%' }" position="bottom" v-model="showTime">
            <van-datetime-picker :min-date="minDate" @cancel="showTime = false" @confirm="getCurrentTime" title="选择年月日" type="date" />
        </van-popup>
    </div>
</template>
<script>
import userHeader from '@/components/user-header'
export default {
    components: {
        userHeader,
    },
    data() {
        return {
            minDate: new Date(2019, 1, 1),
            showTime: false,
            start: '',
            end: '',
            choose_time: '',
            loading: false,
            finished: false,
            error: false,
            errorText: '请求失败，点击重新加载',
            page: {
                current: 0,
            },
            items: [],
        }
    },
    created() {
        this.getToday()
        this.getIndex()
    },
    methods: {
        showTimePick(value) {
            this.choose_time = value
            this.showTime = true
        },
        getToday() {
            var date = new Date()
            let nowMonth = date.getMonth() + 1
            let day = date.getFullYear() + '-' + nowMonth + '-' + date.getDate()
            this.start = day
            this.end = day
        },
        getCurrentTime(time) {
            let nowMonth = time.getMonth() + 1
            if (this.choose_time == 'start') {
                this.start = time.getFullYear() + '-' + nowMonth + '-' + time.getDate()
            } else {
                this.end = time.getFullYear() + '-' + nowMonth + '-' + time.getDate()
            }
            this.showTime = false
        },
        getIndex() {
            let params = {
                start: this.start,
                end: this.end,
            }
            this.$getList('/wallet/offline-win-lose', params).then((res) => {
                // this.amount = res.data.amount
                if (res.data.page.total === 0) {
                    this.errorText = '暂无相关记录 请重新查询'
                }
            })
        },
    },
    watch: {
        //
        start(newval, oldval) {
            if (oldval != '') {
                this.page.current = 0
                this.items = []
                this.getIndex()
            }
        },
        end(newval, oldval) {
            if (oldval != '') {
                this.page.current = 0
                this.items = []
                this.getIndex()
            }
        },
    },
}
</script>
<style lang="less" scoped>
/deep/.van-grid-item__content {
    padding: 0px;
}
.van-grid {
    border-bottom: 1px solid #e4e4e4;
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
    height: calc(100% - 6vh - 45px);
}
</style>