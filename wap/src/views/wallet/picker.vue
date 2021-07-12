<template>
    <div>
        <userHeader :close="true" title="帐户明细">
            <h2 @click="confirm" name="action">确认选择</h2>
        </userHeader>
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
                <van-button @click="showTimePick('end')" plain style="border:none;font-size:12px">
                    <b>
                        {{end}}
                        <van-icon name="arrow-down" />
                    </b>
                </van-button>
            </van-grid-item>
            <van-grid-item>
                <van-dropdown-menu class="dropdown-menu">
                    <van-dropdown-item :options="option" @change="changeType" v-model="value" />
                </van-dropdown-menu>
            </van-grid-item>
            <van-popup :style="{ height: '80%' }" position="bottom" v-model="showTime">
                <van-datetime-picker :min-date="minDate" @cancel="showTime = false" @confirm="getCurrentTime" title="选择年月日" type="date" />
            </van-popup>
        </van-grid>
    </div>
</template>
<script>
import userHeader from '@/components/user-header'
export default {
    components: {
        userHeader,
        // picker,
    },
    props: {
        option: {
            default: [],
            type: Array,
        },
        field: {
            default: '',
            type: String,
        },
    },
    data() {
        return {
            minDate: new Date(2019, 1, 1),
            showTime: false,
            start: '',
            end: '',
            value: 0,
            choose_time: '',
            fromPath: '',
        }
    },
    created() {
        this.getToday()
    },
    watch: {
        field(val) {
            if (val != undefined) {
                this.value = val
            }
        },
    },

    methods: {
        showTimePick(value) {
            this.choose_time = value
            this.showTime = true
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
        changeType() {},
        getToday() {
            var date = new Date()
            let nowMonth = date.getMonth() + 1
            let day = date.getFullYear() + '-' + nowMonth + '-' + date.getDate()
            this.start = day
            this.end = day
        },
        confirm() {
            let param = {
                start: this.start,
                end: this.end,
                value: this.value,
            }
            this.$emit('success', param)
        },
    },
}
</script>
<style lang="less" scoped>
/deep/.dropdown-menu .van-dropdown-menu__bar {
    box-shadow: 0px !important;
}
</style>