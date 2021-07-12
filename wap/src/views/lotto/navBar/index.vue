<template>
    <div class="relative" lett-name="nav-bar">
        <van-nav-bar :border="false" @click-left="$store.dispatch('toBack')" fixed left-arrow>
            <van-dropdown-menu :overlay="false" slot="title">
                <van-dropdown-item :title="title || '加载中'" ref="roomitem">
                    <van-grid :border="false" :column-num="3" gutter="12">
                        <van-grid-item :key="index" @click="toLotto(item.id)" v-for="(item, index) in getRoomList" v-if="item.is_open">
                            <div class="lotto-item-content">
                                <p class="name">{{item.name}}</p>
                                <span>{{item.dx}}/{{item.zh}}</span>
                            </div>
                        </van-grid-item>
                    </van-grid>
                </van-dropdown-item>
            </van-dropdown-menu>

            <van-icon @click="$refs.action.show = true" name="bars" size="22" slot="right" />
        </van-nav-bar>

        <lotto-setting ref="setting" />
        <nav-action ref="action" />
        <lottoRule :data="lotto.config" ref="rule" />
    </div>
</template>

<script>
import lottoSetting from './lotto-setting'
import navAction from './nav-action'
import lottoRule from './lotto-rule'
export default {
    name: 'lottoHeader',
    props: {
        lotto: {
            default: function () {
                return this.$parent
            },
            type: Object,
        },
        lotto_name: {
            type: String,
        },
        room_id: {
            type: Number,
        },
    },
    data() {
        return {}
    },
    components: {
        lottoSetting,
        navAction,
        lottoRule,
    },

    computed: {
        items() {
            return Object.values(this.$store.state.config.lotto_items)
        },

        title() {
            // console.log('compute' + this.lotto_name);
            let data = this.$store.state.config.lotto_items

            return data[this.lotto_name].subtitle + '-房间' + this.room_id
        },
        getRoomList() {
            let roomList = []
            Object.values(this.$store.state.config.play_types).forEach((item, index) => {
                if (item.name.indexOf('room') != -1) {
                    const data = {}
                    data.name = item.title
                    data.id = item.id
                    data.rules = '<p>游戏规则游戏规则</p>'
                    data.onlinePeople = 888
                    data.img_url = 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/room_bg.png'
                    data.dx = item.subtitle.dx
                    data.zh = item.subtitle.zh1 + '/' + item.subtitle.zh2
                    data.is_open = item.is_open
                    roomList.push(data)
                }
            })

            return roomList
        },
    },

    methods: {
        toLotto(room_id) {
            this.lotto.room_id = room_id
            let params = {
                name: this.$route.name == 'lottoChatRoom' ? 'lottoChatRoom' : 'lottoTraRoom',
                params: {
                    name: this.lotto_name,
                    room_id: room_id,
                },
            }

            this.$refs.roomitem.toggle()

            return this.$router.push(params)
        },
        goback() {
            return this.$router.push({
                name: 'lottoRoom',
                params: {
                    name: this.$route.params.name,
                },
            })
        },
    },
}
</script>

<style lang="less" scoped>
/deep/ .van-nav-bar {
    z-index: 24 !important;
    // background: linear-gradient(90deg, #4f8dfe, #34bcfe);
    background: #ff6a00;
    color: @white;
    .van-icon {
        color: @white;
    }
}

/deep/.van-dropdown-menu {
    height: 43px;
    /deep/.van-dropdown-menu__bar {
        height: 45px !important;
    }
    // background: @black;
    color: @white;
    &__bar {
        background: transparent;
        box-shadow: none;
    }
    &__title {
        color: @white;
        padding-right: 14px;
    }
    &__title::after {
        display: none;
    }
    &__title::after {
        right: 0;
    }

    &::after {
        display: none;
    }
}
/deep/.van-dropdown-item {
    z-index: 3000;
}
/deep/.van-grid {
    background: #444954;
    padding: 18px 4px 16px;
    &-item {
        height: 50px;
        &__content {
            padding: 10px 0 !important;
            border-radius: 4px;
            background-color: @white;
            overflow: hidden;
            position: relative;
        }

        .lotto-item-content {
            width: 100%;
            text-align: center;
            border-radius: 6px;
            background: none;

            p {
                color: @black;
                height: 0;
                margin-top: 8px;
            }
            span {
                color: red;
                font-size: 12px;
            }
        }
        &__text {
            color: #24936e;
            line-height: 30px;
            font-size: 14px;
        }
    }
}
</style>