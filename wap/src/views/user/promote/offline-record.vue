<template>
    <div class="relative">
        <userHeader title="下线记录" />
        <div class>
            <van-grid :border="false" :column-num="3">
                <van-grid-item>
                    <van-field :value="type" @click="showPicker = true" clickable name="picker" placeholder="选择类型" readonly />
                </van-grid-item>

                <van-grid-item>
                    <van-button @click="showTimePick('start')" plain style="border:none;font-size:12px">
                        <b>
                            {{start}}
                            <van-icon name="arrow-down" />
                        </b>
                    </van-button>
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
        </div>
        <van-list :error-text="errorText" :error.sync="error" :finished="finished" @load="getIndex" class="reset mb-14" v-if="items.length > 0" v-model="loading">
            <van-cell :key="index" v-for="(item,index) in items">
                <template #label>
                    <div class="cell-label">
                        <span class="custom-title">{{item.created_at}}</span>
                        <span class="custom-title">{{item.extend.balance}}</span>
                    </div>
                </template>
                <template #title>
                    <span class="custom-title">UID:{{item.user_id}}</span>
                </template>

                <template #default>
                    <span class="color-red custom-content">+ {{item.amount}}</span>
                </template>
            </van-cell>
        </van-list>
        <van-popup :style="{ height: '80%' }" position="bottom" v-model="showTime">
            <van-datetime-picker :min-date="minDate" @cancel="showTime = false" @confirm="getCurrentTime" title="选择年月日" type="date" />
        </van-popup>
        <van-popup :style="{ height: '70%' }" position="bottom" v-model="showPicker">
            <van-picker :columns="columns" @cancel="showPicker = false" @confirm="onConfirm" show-toolbar />
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
            showPicker: false,
            start: '',
            end: '',
            value: 'all',
            type: '总盈亏',
            choose_time: '',
            columns: [
                //'', '', ''
                { text: '总盈亏', value: 'all' },
                { text: '总提成', value: 'room' },
                { text: '注册盈亏', value: 'register' },
            ],
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
            this.page.current = 0
            this.getIndex()
        },
        onConfirm(value) {
            this.value = value.value
            this.type = value.text
            this.page.current = 0
            this.items = []
            this.getIndex()
            this.showPicker = false
        },
        getIndex() {
            let params = {
                start: this.start,
                end: this.end,
                type: this.value,
            }
            this.$getList('/offline/log', params).then((res) => {
                if (res.data.page.total === 0) {
                    this.errorText = '暂无相关记录 请重新查询'
                }
            })
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
.container {
    padding: 10px 0px;
    background: @white;
    color: #575c73;
    .title {
        padding-left: 16px;
    }
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