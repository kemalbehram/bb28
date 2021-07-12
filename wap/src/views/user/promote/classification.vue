<template>
    <div class="relative">
        <userHeader title="分类统计" />
        <div>
            <van-grid :border="false" :column-num="3">
                <van-grid-item>
                    <van-field :value="game_name" @click="showPicker = true" clickable name="picker" placeholder="选择游戏" readonly />
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
            <div class="container">
                <h1 class="title">{{game_name}}</h1>
                <van-cell-group>
                    <van-cell :border="false" :title="item.title" :value="item.value" v-for="(item,index) in group" />
                </van-cell-group>
            </div>
        </div>
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
            game_name: '28游戏',
            start: '',
            end: '',
            value: 'game28',
            choose_time: '',
            columns: [{ text: '28游戏', value: 'game28' }],
            group: [
                {
                    title: '游戏人数',
                    value: 0,
                },
                {
                    title: '投注金额',
                    value: 0,
                },
                {
                    title: '中奖金额',
                    value: 0,
                },
                {
                    title: '投注提成',
                    value: 0,
                },
                {
                    title: '下线返水',
                    value: 0,
                },
                {
                    title: '盈亏情况',
                    value: 0,
                },
            ],
        }
    },
    created() {
        this.getToday()
        this.getTotal()
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
            this.getTotal()
        },
        onConfirm(value) {
            this.value = value.value
            this.game_name = value.text
            this.showPicker = false
            this.getTotal()
        },
        getTotal() {
            let param = {
                start: this.start,
                end: this.end,
                game: this.value,
            }
            this.$get('/offline/class-statis', param).then((res) => {
                if (res.code !== 200) return this.$notify(res.message)
                for (var i = 0; i < res.data.length; i++) {
                    this.group[i].value = res.data[i]
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
</style>