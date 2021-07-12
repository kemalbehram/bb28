<template>
    <div class="relative">
        <userHeader title="数据报表" />
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

            <!-- <van-cell-group class="group">
                <van-cell :icon="item.icon" :value="item.amount" v-for="(item,index) in group1">

                    <template #title>
                        <span class="custom-title">{{item.title}}</span>
                    </template>
                </van-cell>
            </van-cell-group>-->

            <van-cell-group class="group">
                <van-cell :icon="item.icon" :value="item.amount" v-for="(item,index) in group2">
                    <!-- 使用 title 插槽来自定义标题 -->
                    <template #title>
                        <span class="custom-title">{{item.title}}</span>
                    </template>
                </van-cell>
            </van-cell-group>

            <!-- <van-cell-group class="group">
                <van-cell :icon="item.icon" :value="item.amount" v-for="(item,index) in group3">

                    <template #title>
                        <span class="custom-title">{{item.title}}</span>
                    </template>
                </van-cell>
            </van-cell-group>-->

            <!-- <div class="intro">
                <p>投注量-中奖量=虚业绩</p>
                <p>虚业绩-(投注提成+返水金额+推荐收益+业务收益+二级提成)=实际业绩</p>
                <p>广告位+主推收益=业务收益</p>
            </div>-->
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
            // group1: [
            //     {
            //         icon: 'gold-coin-o',
            //         title: '推荐收益',
            //         amount: '0.000',
            //     },
            //     {
            //         icon: 'gold-coin-o',
            //         title: '业务收益',
            //         amount: '0.000',
            //     },
            //     {
            //         icon: 'gold-coin-o',
            //         title: '实际业绩',
            //         amount: '0.000',
            //     },
            // ],
            group2: [
                {
                    icon: 'gold-coin-o',
                    title: '所有下线数量',
                    amount: '0',
                },
                {
                    icon: 'gold-coin-o',
                    title: '所有下线总投注金额',
                    amount: '0.000',
                },
                {
                    icon: 'gold-coin-o',
                    title: '所有下线总中奖金额',
                    amount: '0.000',
                },
                {
                    icon: 'gold-coin-o',
                    title: '所有下线总投注提成',
                    amount: '0.000',
                },
                // {
                //     icon: 'gold-coin-o',
                //     title: '下线返水',
                //     amount: '0.000',
                // },
                // {
                //     icon: 'gold-coin-o',
                //     title: '活动金额',
                //     amount: '0.000',
                // },
            ],
            // group3: [
            //     {
            //         icon: 'gold-coin-o',
            //         title: '二级下线数量',
            //         amount: '0.000',
            //     },
            //     {
            //         icon: 'gold-coin-o',
            //         title: '二级投注金额量',
            //         amount: '0.000',
            //     },
            //     {
            //         icon: 'gold-coin-o',
            //         title: '二级投注提成',
            //         amount: '0.000',
            //     },
            // ],
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
        getTotal() {
            let param = {
                start: this.start,
                end: this.end,
            }
            this.$get('/offline/statis', param).then((res) => {
                if (res.code !== 200) return this.$notify(res.message)
                for (var i = 0; i < res.data.group1.length; i++) {
                    this.group1[i].amount = res.data.group1[i]
                }
                for (var i = 0; i < res.data.group2.length; i++) {
                    this.group2[i].amount = res.data.group2[i]
                }
                for (var i = 0; i < res.data.group3.length; i++) {
                    this.group3[i].amount = res.data.group3[i]
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
.group {
    margin-bottom: 8px;
}
.van-cell {
    padding: 8px 16px;
}
.van-cell__value {
    color: #e4593e;
}
.intro {
    text-align: center;
    font-size: 12px;
    color: #575c73;
}
</style>