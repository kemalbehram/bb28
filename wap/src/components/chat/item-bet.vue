<template>
    <div :class="position" class="clear-both chat-item-bet">
        <comm-avatar :class="position" :src="avatar" />
        <div class="chat-item-bet--container">
            <div class="chat-item-bet--header van-hairline--bottom">
                <span class="nickname" v-html="data.nickname" />
                <span class="date" v-html="data.created_at.slice(5)" />
            </div>

            <div>
                {{details}}
                <!-- <div :class="itemClass(index)" :key="detail.id" class="chat-item-bet--item" v-for="(detail,index) in data.details">
                    <span class="title" v-html="detail.title" />
                    <span class="amount" v-html="detail.amount" />
                </div>-->
            </div>

            <!-- <div @click="showMore" class="event-more" v-if="more === false && data.details.length > 4">已隐藏{{data.details.length - 4}}条 点击查看所有</div> -->

            <!-- <div class="chat-item-bet--footer van-hairline--top">
                第{{data.lotto_id}}期
                <br />
                共{{data.details.length}}注 合计{{data.total}}元
                <span @click="betRepeat()" class="button pointer">跟投</span>
            </div>-->
        </div>
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
    },

    data() {
        return {
            more: false,
        }
    },
    computed: {
        position() {
            if (this.opposite === true) return 'right'
        },
        details() {
            let bet_str = ''
            data.details.forEach((item, index) => {
                bet_str += item.place.split('-')[0] + item.amount + '/'
            })
            return bet_str
        },
    },

    methods: {
        betRepeat() {
            this.$emit('bet-repeat', this.data)
        },

        itemClass(index) {
            if (index >= 4 && this.more === false) {
                return 'display-none'
            }
        },

        showMore() {
            this.more = true
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
        line-height: 24px;
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

    &--header {
        margin-bottom: 8px;
        padding-bottom: 8px;
        position: relative;
        line-height: 22px;
        // border-bottom: 1px solid @line-light;
        .nickname {
            color: #666869;
        }
        .date {
            color: #666869;
            position: absolute;
            right: 0;
            font-size: 13px;
        }
    }

    &--footer {
        color: #666869;
        margin-top: 8px;
        padding-top: 8px;
        position: relative;
        line-height: 22px;
        .button {
            position: absolute;
            right: 0;
            display: inline-block;
            padding: 0 10px;
            border-radius: 4px;
            color: @sub;
            background: rgba(255, 255, 255, 0.6);
            font-size: 12px;
            line-height: 22px;
            bottom: 2px;
            // border: 1px solid @line;
        }
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
    padding: 6px 64px 10px 46px;
    &--container {
        vertical-align: text-top;
        min-height: 82px;
        background: @bg-shading;
        position: relative;
        border-radius: 4px;
        font-size: 14px;
        line-height: 22px;
        padding: 10px 14px;
        float: left;
        width: 100%;
        // border: 1px solid @line;
    }
    &--container::before {
        content: '';
        width: 0;
        height: 0;
        border: 5px solid;
        position: absolute;
        left: -10px;
        top: 14px;
        border-color: transparent @bg transparent transparent;
    }
}
//右边开始
.right {
    &.chat-item-bet {
        padding: 8px 46px 8px 64px;
    }

    .chat-item-bet {
        // &--avatar {
        //     right: 0;
        //     left: auto;
        // }
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
</style>