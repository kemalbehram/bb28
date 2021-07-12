<template>
    <div>
        <userHeader class="flex-header" title="红包聊天室" />
        <!-- <van-loading color="#ff6f64" type="spinner" v-show="page_loading" /> -->
        <div :class="{anim:animate==true}" class="chat-content" id="chat-content">
            <!-- <van-pull-refresh @refresh="onRefresh" loosing-text="释放加载更多聊天" pulling-text="下拉即可加载" v-model="loading"> -->
            <div :class="item.user.id == $store.state.user.info.id ?'chat-item right' :'chat-item'" :key="index" v-for="(item,index) in getChats">
                <van-image :src="item.user.avatar" class="chat-common-avatar" heigth="38" radius="38" v-if="item.user.id==100000 || item.user.wechat" />
                <div :class="item.user.id | sub_last" class="chat-common-avatar" v-else>
                    <span>{{item.user.nickname | cut_name}}</span>
                </div>

                <div :class="item.user.id==$store.state.user.info.id?'self':''" class="name-info">
                    <div :class="item.user.id==100000 ? 'nickname-red' : '' " class="nickname">{{item.user.nickname}}</div>
                    <div class="chat-time pl-10">{{item.created_at | date_lint}}</div>
                </div>
                <div class="chat-info" v-if="item.type == 1">
                    <span class="font-16">{{item.message}}</span>
                </div>
                <span @click="showDetail(item.redbag.id,index)" class="red-bag-info" v-else>
                    <!-- <span @click="receiveBag(item.redbag.id,index)" class="red-bag-info" v-else> -->
                    <img :src="red_bag_bg" alt srcset style="display:inline-block;height:85px;" v-if="item.has_received===false" />
                    <img :src="rb_recived" alt srcset style="display:inline-block;height:85px;" v-else />
                    <!-- <b>红包</b> -->
                </span>
            </div>
            <!-- </van-pull-refresh> -->
        </div>
        <van-popup class="overflow" close-icon="close" closeable get-container="#chat-content" round v-model="show">
            <red-bad-detail :detail="detail" :index="choose_index" @success="modifyStatus" />
        </van-popup>
        <!-- <div class="chat-bar shadow-top">
            <van-field :border="false" class="chat-bar-content" placeholder="请输入聊天内容" v-model="message">
                <van-button :loading="loading_send" @click="sendMessage" class="chat-bar-button send-message font-12" color="#ff6f64" loading-type="spinner" slot="button">发送</van-button>
            </van-field>
        </div>-->
    </div>
</template>
<script>
// import Echo from 'laravel-echo'
// import LaravelEcho from '../../libs/laravel-echo'
import userHeader from '@/components/user-header'
import redBadDetail from './red-bad-detail'
export default {
    data() {
        return {
            message: '',
            loading: false,
            loading_send: false,
            finished: false,
            chat_items: [],
            page: {
                current: 1,
            },
            red_bag_bg: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/icon/red_ico.png',
            rb_recived: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/icon/red_over_ico.png',
            page_loading: true,
            animate: false,
            show: false,
            detail: {},
            choose_index: null,
        }
    },
    components: {
        userHeader,
        redBadDetail,
    },
    created() {
        setTimeout(() => {
            this.page_loading = false
        }, 1000)
        // this.getIndex()
    },
    methods: {
        showDetail(id, index) {
            this.$get('/red-bag/detail', { id: id }).then((res) => {
                this.detail = res
                this.choose_index = index
                this.show = true
                if (res.code != 200) {
                    console.log(res)
                    console.log(res.message)
                    return this.$notify(res.message)
                }
            })
        },
        sendMessage() {
            var form = { message: this.message }
            if (this.message == '') return this.$notify('请添加发送内容')
            this.loading_send = true
            this.$post('chat/send', form, false, false).then((res) => {
                this.loading_send = false
                this.message = ''
                this.$store.dispatch('chatsScrollBottom')
                return this.$notify(res.message)
            })
        },
        getIndex() {
            var params = {}
            params.page = this.page.current + 1
            this.loading = true
            this.$get('chat/index', params).then((res) => {
                this.loading = false
                this.finished = true
                this.page_loading = false //

                var item = res.data.items.reverse()
                this.$store.state.chat.chat_contents = item.concat(this.$store.state.chat.chat_contents)
                this.page = res.data.page
            })
        },
        getUnix: function () {
            //
            var date = new Date()
            return date.getTime()
        },

        //获取今天0点0分0秒的时间戳
        getTodayUnix: function () {
            var date = new Date()
            date.setHours(0)
            date.setMinutes(0)
            date.setSeconds(0)
            date.setMilliseconds(0)
            return date.getTime()
        },

        //获取今年1月1日0时0分0秒的时间戳
        getYearUnix: function () {
            var date = new Date()
            date.setMonth(0)
            date.setDate(1)
            date.setHours(0)
            date.setMinutes(0)
            date.setSeconds(0)
            date.setMilliseconds(0)
            return date.getTime()
        },
        getLastDate: function (time) {
            var date = new Date(time)
            var month = date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1
            var day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate()
            return date.getFullYear() + '-' + month + '-' + day
        },
        modifyStatus(value) {
            this.getChats[value].has_received = true
        },
        receiveBag(id, index) {
            // this.$toast.loading({
            //     message: '领取中',
            //     className: 'bg-black',
            //     loadingType: 'spinner',
            //     duration: 0,
            //     getContainer: '#chat-content',
            // })
            let param = {}
            param.id = id
            setTimeout(() => {
                this.$post('red-bag/chatReceive', param).then((res) => {
                    this.$toast.clear()
                    if (res.code == 200) {
                        this.getChats[index].has_received = true
                    }
                })
            }, 1000)
            // this.$post('')
        },
    },
    watch: {
        getChats(val) {
            this.animate = true
            let el = document.getElementById('chat-content')
            el.scrollTo({
                top: el.scrollHeight,
                behavior: 'smooth',
            })
        },
        show(val) {
            if (val == false) {
                this.detail = {}
            }
        },
    },
    computed: {
        getOnlineUsers() {
            return this.$store.state.chat.online_user.length
        },
        getChats() {
            return this.$store.state.chat.chat_contents
        },
        getFormatTime: function () {
            return function (timestamp) {
                var arr = timestamp.split(/[- : \/]/)

                var date = new Date(arr[0], arr[1] - 1, arr[2], arr[3], arr[4], arr[5])

                timestamp = date.getTime()

                var now = this.getUnix()
                var today = this.getTodayUnix()
                var year = this.getYearUnix()
                var timer = (now - timestamp) / 1000
                var tip = ''

                if (timer <= 0) {
                    tip = '刚刚'
                } else if (Math.floor(timer / 60) <= 0) {
                    tip = '刚刚'
                } else if (timer < 3600) {
                    tip = Math.floor(timer / 60) + '分钟前'
                } else if (timer >= 3600 && timestamp - today >= 0) {
                    tip = Math.floor(timer / 3600) + '小时前'
                } else if (timer / 86400 <= 31) {
                    tip = Math.ceil(timer / 86400) + '天前'
                } else {
                    tip = this.getLastDate(timestamp)
                }
                return tip
            }
        },
    },
}
</script>
<style lang="less" scoped>
/deep/.van-loading {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
.flex-header {
    position: fixed;
    top: 0;
    width: 100%;
}
.chat-content {
    padding: 10px 12px;
    margin: 0 auto;
    height: calc(100% - 6vh);
    // overflow-y: scroll;
    overflow: auto;
    // -webkit-overflow-scrolling: touch !important;
    // overflow-y: scroll !important;
    // overflow-x: scroll !important;
    position: fixed;
    bottom: 0px;
    // top: 50px;
    padding-bottom: 5px;
    // padding-top: 40px;
    /deep/.van-pull-refresh {
        -webkit-overflow-scrolling: touch !important;
        overflow-y: scroll !important;
        overflow-x: scroll !important;
    }

    .chat-item {
        position: relative;
        margin-top: 20px;

        .chat-common-avatar {
            vertical-align: bottom;
            width: 38px;
            height: 38px;
            border-radius: 38px;
            // background: grey;
            text-align: center;
            line-height: 38px;
            font-size: 18px;
            color: @white;
        }
        .name-info {
            display: flex;
            position: absolute;
            top: 0;
            left: 50px;
            align-items: center;
        }
        // .chat-time {
        //     text-align: center;
        //     color: #888888;
        //     margin-top: 10px;
        // }
        // .nickname {
        //     font-size: 10px;
        //     position: absolute;
        //     top: 24%;
        //     left: 16%;
        // }
        .nickname-red {
            color: @red;
        }
        .chat-info {
            color: #fff;
            max-width: 60%;
            position: relative;
            margin-left: 50px;
            margin-top: -10px;

            span {
                display: inline-block;
                background: @white;
                color: @black;
                padding: 10px 12px;
                word-break: break-all;
                border-radius: 3px;
            }
        }
        .red-bag-info {
            // padding: 5px 10px;
            color: #fff;
            max-width: 60%;
            word-wrap: break-word;
            position: relative;
            margin-left: 8%;
            display: block;
            margin-top: -11px;
            left: 10px;
            border-radius: 3px;
            img {
                width: 100%;
            }
            b {
                position: absolute;
                top: 30%;
                left: 50%;
                transform: translate(-50%, -30%);
                color: #f9e704;
            }
        }
        // .chat-info::before {
        //     content: '';
        //     position: absolute;
        //     border-top: 3px solid transparent;
        //     border-right: 4px solid #d4b79a;
        //     border-bottom: 3px solid transparent;
        //     left: -4px;
        //     top: 11px;
        // }
    }
    .right {
        text-align: right;
        .nickname {
            right: 16%;
        }
        .chat-info {
            margin-left: 25%;
            span {
                background: @green;
            }
        }
        .chat-common-avatar {
            display: inline-block;
        }
        .chat-info::before {
            display: none;
        }
        // .chat-info::after {
        //     content: '';
        //     position: absolute;
        //     border-top: 3px solid transparent;
        //     border-left: 4px solid @green;
        //     border-bottom: 3px solid transparent;
        //     right: -4px;
        //     top: 11px;
        // }
        // display: flex;
        // justify-content: right;
    }
}
.chat-bar {
    position: fixed;
    bottom: 0;
    width: 100%;
    overflow: hidden;
    max-width: 768px;
    height: 50px;
    z-index: 2;
    padding-bottom: env(safe-area-inset-bottom);

    &-content {
        background-color: @white;
        padding: 7px 6px;
        height: 50px;
    }

    /deep/.van-field__control {
        line-height: 36px;
        // color: #fff;
        background: @bg;
        border-radius: 2px 0 0 2px;
        padding-left: 10px;
        padding-right: 70px;
    }

    /deep/.van-field__label {
        max-width: 66px;
    }

    &-button {
        line-height: 36px;
        height: 36px;
        width: 60px;

        &.send-message {
            position: absolute;
            right: 0;
            top: 0;
        }

        &.send-image {
            background: @bg;
            border-color: @bg;
            color: @sub;
        }
    }
}
.self {
    right: 50px;
    flex-direction: row-reverse;
    div:last-child {
        margin-right: 10px;
    }
}
.anim {
    transition: all 0.5s;
    margin-top: -30px;
}
.color-0 {
    background: #fb6262;
}
.color-1 {
    background: rgb(149, 149, 221);
}
.color-2 {
    background: rgb(199, 199, 97);
}
.color-3 {
    background: rgb(63, 172, 63);
}
.color-4 {
    background: rgb(209, 177, 116);
}
.color-5 {
    background: rgb(201, 136, 147);
}
.color-6 {
    background: rgb(128, 100, 100);
}
.color-7 {
    background: rgb(166, 226, 76);
}
.color-8 {
    background: #8f7957;
}
.color-9 {
    background: #4b9f9f;
}
.overflow {
    height: calc(80% - 40px);
    width: 80%;
    // margin-top: 20px;
}
</style>