<template>
    <div class="content">
        <div class="content-line" v-if="the_lotto.win_extend">
            <span>{{lotto_name}}</span>
            <div class="content-line--code pl-6">
                <span class="code number color-white">{{the_lotto.win_extend.code_arr[0]}}</span>
                <span class="code color-red">+</span>
                <span class="code number color-white">{{the_lotto.win_extend.code_arr[1]}}</span>
                <span class="code color-red">+</span>
                <span class="code number color-white">{{the_lotto.win_extend.code_arr[2]}}</span>
                <span class="code color-red">=</span>
                <span class="code number color-white">{{the_lotto.win_extend.code_he}}</span>
            </div>
            <div class="content-line--result pl-6">
                <div class="bs lotto-result color-white">{{the_lotto.win_extend.code_bos}}</div>
                <div class="sd lotto-result color-white ml-3">{{the_lotto.win_extend.code_sod}}</div>
            </div>
            <div class="pl-6 flex-content" v-if="the_next_lotto">
                <span>距</span>
                <span class="color-red pl-6 pr-6">{{the_next_lotto.id}}</span>
                <span>期开奖时间:</span>
                <span class="pl-6 pr-4 color-red" v-if="the_next_lotto.open_count_down <=0">开奖中</span>
                <span class="pl-6 pr-4 color-red" v-else>
                    <van-count-down :time="the_next_lotto.open_count_down*1000" @finish="endDown" class="font-16 color-red pt-3" format="ss秒" />
                </span>
            </div>
            <div @click="show_lotto=!show_lotto" class="pl-6 cursor" id="container">
                <span>更多</span>
                <Icon size="17" type="md-arrow-dropup" v-if="show_lotto" />
                <Icon size="17" type="md-arrow-dropdown" v-else />
                <van-popover :actions="lotto_items" @select="chooseLotto" class="popover-lotto" placement="top" theme="dark" trigger="click" v-model="show_lotto"></van-popover>
            </div>
            <div class="pl-10">
                <Button @click="toHref(web_site)" type="info" v-if="web_site">查看官网</Button>
                <Button @click="toHistoryLotto" class="ml-10" type="success">历史开奖</Button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            show_lotto: false,
            lotto_name: '',
            the_lotto: {},
            the_next_lotto: {},
            web_site: '',
            form: {
                lotto_name: 'ca28',
            },
        }
    },
    computed: {
        lotto_items() {
            let items = []
            let values = Object.values(this.$store.state.config.lotto_items)

            values.forEach((item, index) => {
                items.push({ text: item.title, value: item.name, web_site: item.web_site })
            })
            return items
        },
    },

    created() {
        this.lotto_name = this.lotto_items[0].text
        this.web_site = this.lotto_items[0].web_site
        this.getLotto()
    },
    watch: {
        the_lotto(newVal, oldVal) {
            console.log(this.the_lotto, this.the_next_lotto)
            if (newVal == oldVal || newVal.lotto_name != oldVal.lotto_name || this.the_next_lotto.open_count_down <= 0) {
                return
            }
            console.log('还是触发了更新')
            this.$store.state.home.update_lotto = newVal.lotto_name
            this.$store.state.home.changed++
        },
    },
    methods: {
        endDown() {
            this.the_next_lotto.open_count_down = 0
            this.getLotto()
        },
        getLotto() {
            this.$get('/home/get-lotto', this.form).then((res) => {
                if (res.code == 200) {
                    if (res.data.items[0].win_extend == null || res.data.items[1].open_count_down < 0) {
                        let that = this
                        setTimeout(function () {
                            that.getLotto()
                        }, 3000)
                        return
                    }
                    this.the_lotto = res.data.items[0]
                    this.the_next_lotto = res.data.items[1]
                }
            })
        },
        // sleep(time) {
        //     return new Promise((resolve) => setTimeout(resolve, time))
        // },
        sleep(time) {
            var startTime = new Date().getTime() + parseInt(time, 10)
            while (new Date().getTime() < startTime) {}
        },
        chooseLotto(item) {
            this.lotto_name = item.text
            this.web_site = item.web_site
            this.form.lotto_name = item.value
            this.getLotto()
        },
        toHref(link) {
            window.open(link)
        },
        toHistoryLotto() {
            this.$router.push({ path: '/lotto/data' })
        },
    },
}
</script>

<style lang="less" scoped>
.content {
    height: 45px;
    background: #e1e3e6;
    position: fixed;
    width: 100%;
    bottom: 0;
    left: 257px;
    &-line {
        height: 100%;
        display: flex;
        font-size: 16px;
        align-items: center;
        padding-left: 20px;
        &--code {
            display: flex;
        }
        &--result {
            display: flex;
            .bs {
                width: 25px;
                height: 25px;
                border-radius: 1px;
                background: #3c8dbc;
                text-align: center;
                line-height: 25px;
            }
            .sd {
                width: 25px;
                height: 25px;
                border-radius: 1px;
                background: #018001;
                text-align: center;
                line-height: 25px;
            }
        }
        .number {
            width: 25px;
            height: 25px;
            display: block;
            line-height: 25px;
            text-align: center;
            border-radius: 25px;
            background: #ff7f50;
        }
    }
}
.cursor {
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
.flex-content {
    display: flex;
}
.choose-item {
    position: absolute;
    top: -100px;
    width: 80px;
    background: #e1e3e6;
    height: 95px;
}
</style>