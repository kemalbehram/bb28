<template>
    <div :class="position" class="clear-both chat-item-bet">
        <comm-avatar :class="position" :content="data" :font="(data.user_id==undefined && data.id_hash==undefined) || data.user_id==10000 || data.wechat==true" :src="avatar" />

        <div class="chat-item-bet--container" v-if="data.cat ==1">
            <div :class="(data.user_id===$store.state.user.info.id || data.id_hash===$store.state.user.info.id_hash)?'roverd':''" class="chat-item-bet--header font-15">
                <span class="nickname" v-html="data.nickname" />
                <!-- {{data.id}}-{{data.room}} -->
                <span :class="(data.user_id===$store.state.user.info.id || data.id_hash===$store.state.user.info.id_hash)?'self':''" class="date" v-html="data.created_at.substring(11)" v-if="block_time" />
            </div>

            <span :class="(data.user_id===$store.state.user.info.id || data.id_hash===$store.state.user.info.id_hash)?'color-white':''" class="bg-white pl-10 detail font-15 color-black">{{details}}</span>
        </div>
        <div class="chat-item-bet--container" v-else-if="data.cat == 3">
            <div class="chat-item-bet--header font-15 color-black">
                <span class="nickname color-red weight-none" v-html="data.nickname" />
                <span :class="(data.user_id===$store.state.user.info.id || data.id_hash===$store.state.user.info.id_hash)?'self':''" class="date" v-html="data.created_at.substring(11)" v-if="block_time" />
                <div class="mt-6 inline-block">
                    <span class="bg-white block pd-12">{{data.details}}</span>
                </div>
            </div>
        </div>
        <div class="chat-item-bet--container" v-else-if="data.cat == 2">
            <div class="chat-item-bet--header font-15 color-black">
                <span class="nickname color-red weight-none" v-html="data.nickname" />
                <span :class="(data.user_id===$store.state.user.info.id || data.id_hash===$store.state.user.info.id_hash)?'self':''" class="date" v-html="data.created_at.substring(11)" v-if="block_time" />
            </div>
            <div class="head font-15 pt-10 pl-12 pr-12 color-black">
                <span>第{{data.lotto_id}}期开奖结果</span>
            </div>
            <div class="open-content pb-10 pl-12 pr-12 color-black">
                <span class="win-code-item color-white bg-blue">{{data.details.open_code[0]}}</span>
                <span>+</span>
                <span class="win-code-item color-white bg-blue">{{data.details.open_code[1]}}</span>
                <span>+</span>
                <span class="win-code-item color-white bg-blue">{{data.details.open_code[2]}}</span>
                <span>=</span>
                <span class="win-code-item color-white bg-red">{{data.details.code_he}}</span>
                <span style="color:black;font-size:12px;display:initial;margin-left:5px;">{{data.details.code_bos}}{{data.details.code_sod}}</span>
            </div>
        </div>
        <div class="chat-item-bet--container" v-else-if="data.cat == 6">
            <div class="chat-item-bet--header font-15 color-black">
                <span class="nickname color-red" v-html="data.nickname" />
                <span :class="(data.user_id===$store.state.user.info.id || data.id_hash===$store.state.user.info.id_hash)?'self':''" class="date" v-html="data.created_at.substring(11)" v-if="block_time" />
            </div>

            <div class="bet-content font-15 pd-12 color-black">
                <span>第{{data.lotto_id}}期投注账单</span>
                <div @click="showDetail(item.type,item.room,item.user_id,item.lotto_id)" v-for="(item,index) in data.details">{{item.nickname}}({{item.user_id | hidden_user}})</div>
            </div>
        </div>
        <div class="chat-item-bet--container" v-else-if="data.cat == 7">
            <div class="chat-item-bet--header font-15 color-black">
                <div :class="(data.user_id===$store.state.user.info.id || data.id_hash===$store.state.user.info.id_hash)?'roverd':''" class="chat-item-bet--header font-15">
                    <span class="nickname" v-html="data.nickname" />
                    <span :class="(data.user_id===$store.state.user.info.id || data.id_hash===$store.state.user.info.id_hash)?'self':''" class="date" v-html="data.created_at.substring(11)" v-if="block_time" />
                </div>
                <div class="mt-6 inline-block">
                    <span class="bg-red">{{data.details.content}}</span>
                    <!-- <span :class="(data.user_id===$store.state.user.info.id || data.id_hash===$store.state.user.info.id_hash)?'self':''" class="date" v-html="data.created_at.substring(11)" v-if="block_time" /> -->
                </div>
            </div>
        </div>
        <div class="chat-item-bet--container" v-else-if="data.cat == 8">
            <div class="chat-item-bet--header font-15 color-sub">
                <span class="nickname color-red" v-html="data.nickname" />
                <span :class="(data.user_id===$store.state.user.info.id || data.id_hash===$store.state.user.info.id_hash)?'self':''" class="date" v-html="data.created_at.substring(11)" v-if="block_time" />
            </div>
            <span class="bg-white font-15 stop color-black" v-html="data.details.content"></span>
        </div>
        <div class="chat-item-bet--container" v-else>
            <div class="chat-item-bet--header font-15 color-sub">
                <span class="nickname color-red" v-html="data.nickname" v-if="data.cat == 4" />
                <span class="nickname color-red" v-else v-html="data.nickname" />
                <span :class="(data.user_id===$store.state.user.info.id || data.id_hash===$store.state.user.info.id_hash)?'self':''" class="date" v-html="data.created_at.substring(11)" v-if="block_time" />
            </div>
            <span class="bg-white font-15 stop color-black" v-html="data.details"></span>
        </div>
        <van-popup v-if="player_details && detail_show" v-model="detail_show">
            <div class="player_detail">
                <div class="head">
                    <span>{{player_details.nickname}}({{player_details.user_id | hidden_user}})下注详情</span>
                    <span>{{player_details.total}}</span>
                </div>
                <div :key="index" class="content" v-for="(item,index) in player_details.details">
                    <span>{{item.name}}</span>
                    <span>{{item.amount}}</span>
                </div>
            </div>
        </van-popup>
    </div>
</template>

<script>
import commAvatar from './comm-avatar'
export default {
    components: {
        commAvatar,
    },
    props: {
        data: {},
        opposite: false,
        avatar: '',
        room: 0,
        show: '',
        lotto: {},
    },

    data() {
        return {
            more: false,
            detail_show: false,
            player_details: {},
        }
    },
    computed: {
        position() {
            if (this.opposite === true) return 'right'
        },
        details() {
            let bet_str = ''

            this.data.details.forEach((item, index) => {
                if (this.isNumber(item.name)) {
                    bet_str += ' ' + item.name + '-' + parseInt(item.amount) + ' '
                } else {
                    bet_str += item.name + '' + parseInt(item.amount)
                }
                // bet_str += item.title.split('-')[1] + '' + parseInt(item.amount)
            })
            return bet_str
        },
        block_time() {
            let user = this.$store.state.user
            if (user != undefined) {
                // console.log(user.option)
                return user.option.block_time
            }
            return false
        },
    },
    methods: {
        betRepeat() {
            this.$emit('bet-repeat', this.data)
        },
        isNumber(val) {
            var regPos = /^\d+(\.\d+)?$/ //非负浮点数
            var regNeg = /^(-(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*)))$/ //负浮点数
            if (regPos.test(val) || regNeg.test(val)) {
                return true
            } else {
                return false
            }
        },
        itemClass(index) {
            if (index >= 4 && this.more === false) {
                return 'display-none'
            }
        },

        showMore() {
            this.more = true
        },
        showDetail(type, room, user_id, lotto_id) {
            var api = '/lotto/' + this.$route.params.name + '/show-item'
            let param = {}
            param.type = type
            param.room = room
            param.user_id = user_id
            param.lotto_id = lotto_id
            this.$get(api, param).then((res) => {
                this.detail_show = true
                this.player_details = res.data
                // console.log(this.player_details)
            })
        },
    },
}
</script>

<style lang="less" scoped>
/deep/[class*='van-hairline']::after {
    border-color: @line;
}
</style>

<style lang="less" scoped>
.chat-item-bet {
    font-size: 13px;
    // font-family: @font-lett;
    &--item {
        position: relative;
        // line-height: 24px;
        // color: @red;
        .title {
            font-size: 13px;
        }
        .amount {
            position: absolute;
            right: 0;
            font-size: 13px;
        }
    }
    .detail {
        // padding-top: 3px;
        display: inline-block;
        word-break: break-all;
        line-height: initial;
    }
    &--header {
        // margin-bottom: 8px;

        position: relative;
        line-height: 22px;
        padding-bottom: 2px;
        // border-bottom: 1px solid @line-light;
        .nickname {
            color: #161616;
        }
        .date {
            color: #888888;
            position: relative;
            padding: 0 10px;
            font-size: 12px;
            font-weight: 400;
        }
    }

    &--footer {
        color: @sub;
        width: 80%;
        padding-bottom: 3px;
        span {
            margin-bottom: 3px;
        }
        // margin-top: 8px;
        // padding-top: 8px;
        border-bottom-left-radius: 3px;
        border-bottom-right-radius: 3px;
        position: relative;
        line-height: 22px;
        .button {
            position: absolute;
            right: 5px;
            display: inline-block;
            padding: 0 6px;
            border-radius: 4px;
            color: @sub;
            background: rgba(194, 192, 192, 0.2);
            font-size: 12px;
            line-height: 22px;
            bottom: 0px;

            // border: 1px solid @line;
        }
    }
}
.count-down {
    text-align: center;
    padding-right: 40px;
    span {
        background: #c8cdd0;
        color: #f9f9f9;
        padding: 2px 4px;
        border-radius: 3px;
    }
}
.event-more {
    text-align: center;
    background: rgba(255, 255, 255, 0.6);
    border-radius: 24px;
    // border: 1px solid @line;
    line-height: 24px;
    color: #666869;
    font-size: 13px;
    cursor: pointer;
    margin: 0 -2px;
}
</style>

<style lang="less" scoped>
.chat-item-bet {
    position: relative;
    padding: 6px 0px 0px 40px;
    &--container {
        vertical-align: text-top;
        // min-height: 82px;
        // background: @bg-shading;
        position: relative;
        border-radius: 4px;
        font-size: 14px;
        line-height: 22px;
        // padding: 0px 14px;
        float: left;
        width: 90%;
        .bg-white {
            background: @white;
        }
        .block {
            display: block;
        }
        .bg-blue {
            background: #6fb3f6;
            color: white;
        }
        .bg-red {
            background: #f66f6f;
            color: white;
        }
        span {
            padding: 10px 12px;
            border-radius: 3px;
        }
        .stop {
            color: #333333;
            padding: 10px 12px;
            display: inline-block;
            text-align: center;
            p {
                line-height: 10px;
            }
        }
        .nickname {
            padding: 0;
        }
        .head {
            // width: 75%;
            text-align: center;
            color: #333333;
            background: @white;
            border-top-right-radius: 4px;
            border-top-left-radius: 4px;
        }
        .open-content {
            background: @white;
            text-align: center;
            // width: 75%;
            padding: 5px 0;
            span {
                padding: unset;
                width: 18px !important;
                height: 18px !important;
                // padding: 0 4px;
                border-radius: 50% !important;
                font-size: 12px;
                display: inline-block;
                line-height: 18px;
            }
        }

        .bet-content {
            background: @white;
            // width: 60%;
            border-radius: 4px;
            // padding-bottom: 6px;

            div {
                background: #f3f3f7;
                width: 90%;
                margin: 0 auto;
                padding: 2px 0 2px 6px;
                border-bottom: 1px solid #e4e4e4;
                position: relative;
            }
            div:last-child {
                border: unset;
            }
            div::after {
                content: '';
                position: absolute;
                width: 0;
                height: 0;
                border-top: 5px solid transparent;
                border-bottom: 5px solid transparent;
                border-left: 5px solid #00082b;
                right: 6%;
                top: 25%;
            }
        }
    }
}
/deep/.van-popup {
    padding: 5px 10px;
    border-radius: 4px;
    width: 55%;
    font-size: 12px;
    .player_detail {
        span {
            display: inline-block;
        }
        .head,
        .content {
            display: flex;

            padding: 5px 0;
        }
        .head {
            justify-content: space-around;
            border-bottom: 1px solid #efefef;
        }
        .content {
            justify-content: space-between;
            border-bottom: 1px solid #e4e4e4;
        }
        .content:last-child {
            border-bottom: unset;
        }
    }
}
//右边开始
.right {
    &.chat-item-bet {
        padding: 0 46px 0 64px;
        color: @white;
    }
    .chat-item-bet--container {
        width: 100% !important;
        text-align: end;
    }

    .detail {
        border-top-left-radius: 3px;
        border-top-right-radius: 3px;
        background: @green;
        // padding-top: 3px;
        display: inline-block;
        word-break: break-all;
        line-height: initial;
    }
    // .event-more {
    //     background: @green;
    //     border-radius: 0;
    // }
    .chat-item-bet {
        &--header {
            text-align: right;
        }
        &--footer {
            width: 90%;
            background: @green;
            color: @white;
            padding-bottom: 3px;
            span {
                margin-bottom: 3px;
                background: rgba(255, 255, 255, 0.6);
            }
        }
        &--avatar {
            right: 15px !important;
            left: auto;
        }
        &--container {
            float: right;
        }

        &--container::before {
            right: -10px;
            left: auto;
            border-color: transparent transparent transparent @bg;
        }
    }
}
.pd-12 {
    // font-weight: bolder;
    padding: 10px 12px;
}
.color-black {
    color: #333333;
    // font-weight: bolder;
}
.roverd {
    display: flex;
    align-items: center;
    flex-direction: row-reverse;
}
.inline-block {
    display: inline-block;
}
.weight {
    // font-weight: bolder;
}
.weight-none {
    font-weight: 400;
}
</style>