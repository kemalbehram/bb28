<template>
    <div class="lotto-chat">
        <!-- <van-pull-refresh @refresh="onRefresh"  v-model="isLoading"> -->
        <!-- <div class="lotto-chat-content"> -->
        <div :class="{anim:animate==true}" class="lotto-chat-window" id="lotto-chat-window" v-if="items" v-swipedown="(e)=>touch('toTop')" v-swipeup="(e)=>touch('toDwon')">
            <div class="unread-botton" v-if="unread>0">
                <van-button @click="toBottom" hairline plain round size="small" type="info">{{unread}}条未读消息</van-button>
            </div>
            <chat-item-notice>欢迎光临{{$store.state.config.web_title}}</chat-item-notice>

            <template v-for="(item ,index ) in items">
                <chat-item-bet :avatar="item.avatar" :data="item" :lotto="lotto" :opposite="item.nickname=== $store.state.user.info.nickname" :room="lotto.room_id" @bet-repeat="betRepeat" />
            </template>
            <!-- </div> -->
        </div>

        <van-popup position="bottom" style="height:80%" v-model="show_bet">
            <bet-place :lotto="lotto" />
        </van-popup>
        <!-- </van-pull-refresh> -->
    </div>
</template>
<script>
import chatItemNotice from './item-notice'
import chatItemBet from './item-bet'
import { Notify } from 'vant'

export default {
    components: {
        chatItemNotice,
        chatItemBet,
    },
    props: {
        lotto: Object,
    },

    data() {
        return {
            message: '',
            show_bet: false,
            isLoading: false,
            scroll: true,
            animate: false,
            unread: 0,
        }
    },

    computed: {
        config() {
            return this.$store.state.config.lotto_tools
        },

        has_tool() {
            var result = []
            this.lotto.current_type.tools.forEach((element) => {
                let data = this.config[element].action
                result.push.apply(result, data)
            })
            return result
        },

        items: {
            get() {
                // console.log(this.$store.state.lotto[this.lotto.lotto_name])
                // console.log(this.$store.state.lotto[this.lotto.lotto_name])
                let list = this.$store.state.lotto[this.lotto.lotto_name]
                return list[this.lotto.playing]
            },
            set(value) {
                return value
            },
        },
    },
    created() {
        this.scrollBottom()
    },
    watch: {
        items(newVal, oldVal) {
            if (this.scroll == true && this.unread == 0) {
                this.scrollBottom()
            } else {
                this.unread++
            }
        },
    },

    methods: {
        touch(type) {
            let el = document.getElementById('lotto-chat-window')
            let toBottom = el.scrollHeight - el.scrollTop
            if (toBottom > 1000) {
                this.scroll = false
                // console.log('不滑了')
            } else {
                if (toBottom < 1000) {
                    // console.log('又滑了')
                    this.scroll = true
                    this.unread = 0
                }
            }
        },
        toBottom() {
            this.scroll = true
            this.unread = 0
            this.scrollBottom()
        },
        scrollBottom() {
            this.animate = true
            this.$nextTick(() => {
                let el = document.getElementById('lotto-chat-window')
                el.scrollTo({
                    top: el.scrollHeight,
                    behavior: 'smooth',
                })
            })
        },

        betRepeat(data) {
            this.lotto.clearBet()
            data.details.forEach((element) => {
                this.lotto.checked[element.place] = parseFloat(element.amount)
            })

            this.lotto.betConfirmPre()
        },

        betPrefix(params) {
            let prefix = this.lotto.playing
            var place = []
            let has = Object.keys(this.lotto.place)
            params.forEach((element) => {
                element = this.$options.filters.pre_zero(element)
                let code = prefix + '_' + element
                if (has.includes(code)) place.push(code)
            })
            return place
        },

        onMessage() {
            let str = this.message.trim()
            let format = str.trim().split(/\s+/)
            var code_arr = []
            //判断汉字
            if (/.*[\u4e00-\u9fa5]+.*$/.test(format[0])) {
                this.has_tool.forEach((element) => {
                    if (element.title === format[0]) {
                        code_arr = element.place
                    }
                })
            }
            //判断数字，小写逗号
            if (/^[0-9,]*$/.test(format[0])) {
                code_arr = format[0].trim().split(',')
            }

            //判断数字，大写逗号
            if (/^[0-9，]*$/.test(format[0])) {
                code_arr = format[0].trim().split('，')
            }

            if (code_arr.length === 0) return false
            var place = this.betPrefix(code_arr)

            if (place.length === 0) return false
            this.lotto.toolBet(place, parseFloat(format[1]))
            this.lotto.betConfirmPre()
            this.$refs.message.blur()
            this.message = ''
        },
        onRefresh() {
            var api = '/lotto/' + this.$route.params.name + '/fresh-chat'
            let param = {}
            this.scroll = false
            if (this.$store.state.lotto[this.$route.params.name][this.lotto.playing].length == 0) {
                return Notify('已拉到底！')
            }
            param.id = this.$store.state.lotto[this.$route.params.name][this.lotto.playing][0].id

            param.room = this.$route.params.room_id
            param.type = this.$route.params.name
            this.$get(api, param).then((res) => {
                if (res.code !== 200) return false
                if (res.data.items.length != 0) {
                    var list = []
                    list = [] = this.$store.state.lotto[this.$route.params.name][this.lotto.playing].reverse()
                    list = [] = this.$store.state.lotto[this.$route.params.name][this.lotto.playing].concat(res.data.items)
                    this.$store.state.lotto[this.$route.params.name][this.lotto.playing] = list.reverse()
                } else {
                    return Notify('已拉到底！')
                }
            })
            this.isLoading = false
        },
    },
}
</script>

<style lang="less" scoped>
.lotto-chat {
    position: relative;
    height: 100vh;
    background: #ebebeb;
    overflow: hidden;
    .lotto-chat-window {
        height: calc(100% - 180px);
        overflow: auto;
        padding: 0px 15px;
        position: fixed;
        bottom: 50px;
        width: 100%;
        background: #ebebeb;
    }
}
.lotto-chat::-webkit-scrollbar {
    display: none; /* Chrome Safari */
}
.lotto-chat-content::-webkit-scrollbar {
    display: none; /* Chrome Safari */
}

.lotto-chat-window::-webkit-scrollbar {
    display: none; /* Chrome Safari */
}
.anim {
    transition: all 0.5s;
    margin-top: -30px;
}
.unread-botton {
    position: fixed;
    background: none;
    right: 10px;
    margin-top: 5px;
    z-index: 2;
}
</style>
