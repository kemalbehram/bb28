<template>
    <div class="trend">
        <UserHeader title="开奖走势" />
        <div class="tab pt-16">
            <span :class="current_tab == 'history' ? 'active':''" @click="current_tab ='history'">历史开奖</span>
            <span :class="current_tab == 'trend' ? 'active':''" @click="current_tab ='trend'">开奖走势</span>
        </div>
        <div class="condition">
            <div @click="showPicker1 = !showPicker1">
                {{start_date != '' ? start_date : '开始日期'}}
                <van-icon name="arrow-down" size="14" />
            </div>
            <div @click="showPicker2 = !showPicker2">
                {{end_date != '' ? end_date : '结束日期'}}
                <van-icon name="arrow-down" size="14" />
            </div>
            <div @click="search">
                <van-icon class="mr-2" name="search" size="14" />搜索历史
            </div>
        </div>
        <van-popup position="bottom" v-model="showPicker1">
            <van-datetime-picker :max-date="maxDate" :min-date="minDate" @cancel="showPicker1 = false" @confirm="onConfirm" title="开始日期" type="date" v-model="currentDate" />
        </van-popup>

        <van-popup position="bottom" v-model="showPicker2">
            <van-datetime-picker :max-date="maxDate" :min-date="minDate" @cancel="showPicker2 = false" @confirm="onConfirm2" title="结束日期" type="date" v-model="currentDate" />
        </van-popup>
        <van-loading type="spinner" v-if="loading" />
        <div class="list mt-10" v-else>
            <div class="card-item" v-for="(item,index) in list" v-if="current_tab=='history'">
                <div class="open-code left">
                    <span class="code-blue">{{item.win_extend.code_arr[0]}}</span>
                    <span>+</span>
                    <span class="code-blue">{{item.win_extend.code_arr[1]}}</span>
                    <span>+</span>
                    <span class="code-blue">{{item.win_extend.code_arr[2]}}</span>
                    <span>=</span>
                    <span class="code-red">{{item.win_extend.code_he}}</span>
                </div>
                <div class="sub-info right font-12">
                    <span>{{item.win_extend.code_bos}}{{item.win_extend.code_sod}}{{item.win_extend.code_hu ? '/虎' :''}}{{item.win_extend.code_long ? '/龙' :''}}{{item.win_extend.code_bao ? '/豹' :''}}{{item.win_extend.code_dui ? '/对子' :''}}{{item.win_extend.code_baozi ? '/豹子' :''}}{{item.win_extend.code_shunzi ? '/顺子' :''}}</span>
                </div>
                <div class="left font-12 lotto-id">
                    <span>第{{item.id}}期开奖</span>
                </div>
                <div class="right font-12 lotto-time">
                    <span>{{item.lotto_at | date_lint}}</span>
                </div>
            </div>

            <div class="table" v-if="current_tab=='trend'">
                <div class="thead font-12 pb-10">
                    <div>期号</div>
                    <div>和值</div>
                    <div>大</div>
                    <div>小</div>
                    <div>单</div>
                    <div>双</div>
                </div>
                <div class="item font-12" v-for="(item,index) in list">
                    <div class="lotto-id">{{item.id}}</div>
                    <div>
                        <span class="sub">{{item.win_extend.code_he}}</span>
                    </div>
                    <div>
                        <span class="red" v-if="item.win_extend.code_bos=='大'">{{item.win_extend.code_bos}}</span>
                        <span class="sub" v-else>-</span>
                    </div>
                    <div>
                        <span class="blue" v-if="item.win_extend.code_bos=='小'">{{item.win_extend.code_bos}}</span>
                        <span class="sub" v-else>-</span>
                    </div>
                    <div>
                        <span class="red" v-if="item.win_extend.code_sod=='单'">{{item.win_extend.code_sod}}</span>
                        <span class="sub" v-else>-</span>
                    </div>
                    <div>
                        <span class="blue" v-if="item.win_extend.code_sod=='双'">{{item.win_extend.code_sod}}</span>
                        <span class="sub" v-else>-</span>
                    </div>
                </div>
            </div>
        </div>

        <van-pagination :items-per-page="15" :total-items="300" @change="onPageChange" class="mt-10" mode="simple" v-if="list.length>0" v-model="page.current" />
    </div>
</template>
<script>
import { Notify } from 'vant'
import UserHeader from '../../../components/user-header'
export default {
    components: {
        UserHeader,
    },
    data() {
        return {
            list: [],
            current_tab: 'history',
            showPicker1: false,
            showPicker2: false,
            minDate: new Date(2020, 0, 1),
            maxDate: new Date(2025, 10, 1),
            currentDate: new Date(),
            start_date: '',
            end_date: '',
            page: {
                current: 1,
            },
            loading: true,
        }
    },
    created() {
        this.getIndex()
    },
    methods: {
        onConfirm(value) {
            let nowMonth = value.getMonth() + 1
            this.start_date = value.getFullYear() + '-' + nowMonth + '-' + value.getDate()

            this.showPicker1 = false
        },
        onConfirm2(value) {
            let nowMonth = value.getMonth() + 1
            this.end_date = value.getFullYear() + '-' + nowMonth + '-' + value.getDate()
            this.showPicker2 = false
        },
        search() {
            console.log('search')
            if (this.start_date == '' && this.end_date == '') {
                return Notify('请选择日期')
            }
            this.loading = true
            this.getIndex()
        },
        getIndex() {
            var api = '/lotto/' + this.$route.params.name + '/history-log'
            let params = {}
            params.page = this.page.current
            params.start_date = this.start_date
            params.end_date = this.end_date
            this.$get(api, params).then((res) => {
                if (res.code !== 200) return false
                ;(this.list = res.data.items), (this.page = res.data.page)
                this.loading = false
            })
        },
        onPageChange() {
            // this.loading = true
            this.getIndex(this.page.current)
        },
    },
}
</script>
<style lang="less" scoped>
.trend {
    height: 100%;
    position: fixed;
    width: 100%;
}
.tab {
    background: @white;
    text-align: center;
    color: #7b7b7b;
    span {
        display: inline-block;
        padding: 6px 20px;
        border: 1px solid @orange;
        font-weight: 400;
        border-radius: 3px;
        // box-sizing: border-box;
    }
    span:first-child {
        border-top-right-radius: 0px;
        border-bottom-right-radius: 0px;
    }
    span:nth-child(2) {
        border-top-left-radius: 0px;
        border-bottom-left-radius: 0px;
    }
    .active {
        background: @orange;
        // border: none;
        color: @white;
    }
}
.condition {
    display: flex;
    justify-content: center;
    background: @white;
    padding-top: 10px;
    padding-bottom: 20px;
    div {
        margin: 0px 6%;
        font-size: 12px;
        color: #afafaf;
        /deep/.van-icon {
            vertical-align: sub;
        }
    }
}
.list {
    // -webkit-overflow-scrolling: touch;
    overflow: scroll;
    height: calc(100% - 6vh - 160px);
    // height: 1440px;
    .card-item {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        width: 96%;
        margin: 10px auto;
        border-radius: 5px;
        background: @white;
        .left {
            width: 60%;
        }
        .right {
            width: 40%;
        }
        .open-code {
            display: flex;
            justify-content: center;
            align-items: center;
            span {
                margin: 10px 3px;
                color: @sub;
                font-size: 13px;
                // padding: 0 2px;
            }
            .code-red {
                width: 26px;
                height: 26px;
                display: inline-block;
                background: @orange;
                text-align: center;
                border-radius: 26px;
                line-height: 26px;
                color: white;
            }
            .code-blue {
                width: 26px;
                height: 26px;
                display: inline-block;
                background: #6fb3f6;
                text-align: center;
                border-radius: 26px;
                line-height: 26px;
                color: white;
            }
        }
        .sub-info {
            text-align: right;
            span {
                display: inline-block;
                color: @sub;
                margin: 14px 20px 0px 0px;
            }
        }
        .lotto-id {
            padding-bottom: 10px;
            span {
                padding-left: 12%;
                color: #aeaeae;
            }
        }
        .lotto-time {
            text-align: right;
            span {
                padding-right: 15%;
                color: #aeaeae;
            }
        }
    }
}
/deep/.van-pagination {
    width: 96%;
    margin: 0 auto;
    padding-bottom: 10px;
    /deep/.van-pagination__item {
        border-radius: 3px;
    }
}
/deep/.van-loading {
    text-align: center;
    height: 80vh;
    line-height: 80vh;
    background: white;
}
.table {
    .thead,
    .item {
        display: flex;
        justify-content: space-around;

        div {
            display: inline-block;
            text-align: center;
            width: 16.6%;
        }
    }

    .item {
        background: @white;
        padding: 12px 0px;
        border-bottom: 1px solid #f7f7f7;
        display: flex;
        justify-content: center;
        align-content: center;
        align-items: center;
        span {
            background: #f0eeef;
            /* padding: 5px 10px; */
            border-radius: 3px;
            width: 25px;
            display: inline-block;
            text-align: center;
            height: 25px;
            line-height: 25px;
        }
        .red {
            background: #f66f6f;
            color: @white;
        }
        .blue {
            background: #6fb3f6;
            color: @white;
        }
        .lotto-id {
            line-height: 26px;
        }
    }
}
</style>