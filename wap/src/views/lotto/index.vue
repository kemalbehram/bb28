<template>
    <div class="room-content" v-if="this.lottory_name_array.indexOf(this.$route.params.name) != -1">
        <UserHeader title="房间选择" />
        <room-list :lottoName="this.$route.params.name" />
        <!-- <van-icon @click="toBack()" name="arrow-left" size="20px" />
        <van-tabs class="room-tab" v-model="active">
            <van-tab title="传统玩法">
                <van-grid :border="false" :column-num="2" class="item-list mt-10" gutter="10" v-if="getRoomList.length>0">
                    <van-grid-item :key="index" class="room-item" v-for="(item,index) in getRoomList" v-if="item.is_open">
                        <van-image :src="ruleIcon" @click="showRule(item.rules)" class="rule-icon" width="18" />

                        <div @click="toLotto(item.id)" class="desc">
                            <p class="font-16">{{item.name}}</p>
                            <span class="font-12">大小 {{item.dx}}</span>
                            <span class="font-12">组合 {{item.zh}}</span>
                            <p>
                                <van-image :src="userIcon" width="14" />
                                {{item.onlinePeople}} 在线
                            </p>
                        </div>
                        <van-image :src="item.img_url" />
                    </van-grid-item>
                </van-grid>
            </van-tab>
            <van-tab title="聊天玩法">
                <van-grid :border="false" :column-num="2" class="item-list mt-10" gutter="10" v-if="getRoomList.length>0">
                    <van-grid-item :key="index" class="room-item" v-for="(item,index) in getRoomList" v-if="item.is_open">
                        <van-image :src="ruleIcon" @click="showRule(item.rules)" class="rule-icon" width="18" />

                        <div @click="toChatLotto(item.id)" class="desc">
                            <p class="font-16">{{item.name}}</p>
                            <span class="font-12">大小 {{item.dx}}</span>
                            <span class="font-12">组合 {{item.zh}}</span>
                            <p>
                                <van-image :src="userIcon" width="14" />
                                {{item.onlinePeople}} 在线
                            </p>
                        </div>
                        <van-image :src="item.img_url" />
                    </van-grid-item>
                </van-grid>
            </van-tab>
        </van-tabs>
        <van-dialog title="游戏/返水规则" v-model="dialogShow">
            <div class="content" v-html="rule"></div>
        </van-dialog>-->
    </div>
    <div class="room-content" v-else>
        <van-empty description="暂无该彩票" image="error">
            <van-button @click="toBack()" class="bottom-button" round type="danger">返回</van-button>
        </van-empty>
    </div>
</template>

<script>
import UserHeader from '../../components/user-header'
import roomList from '_c/room-list'
import RoomList from '../../components/room-list.vue'
export default {
    name: 'room',
    data() {
        return {
            lottory_name_array: ['de28', 'ca28', 'tw28', 'cw28', 'bit28'],
            active: 0,
        }
    },
    components: {
        UserHeader,
        roomList,
    },

    created() {
        this.lottory_name = this.$route.params.name
    },
    methods: {
        toBack() {
            this.$router.back(-1)
        },
    },
}
</script>
<style lang="less" scoped>
.room-content {
    height: 100%;
    background-color: white;
    position: relative;
    .room-item {
        img {
            width: 46px;
        }
        .lotto-info {
            width: 250px;
            display: flex;
            .info {
                span {
                    display: block;
                }
                .online {
                    color: #aeaeae;
                    font-size: 12px;
                    /deep/.van-icon {
                        vertical-align: text-top;
                    }
                }
            }
        }
    }

    // /deep/.van-icon {
    //     position: absolute;
    //     top: 2%;
    //     left: 3%;
    //     z-index: 2400;
    // }
    // /deep/.van-tabs__wrap {
    //     position: relative;
    //     z-index: 2000;
    // }
    // .item-list {
    //     position: relative;
    //     /deep/.van-grid-item {
    //         padding: 0px 2px;
    //         height: 110px;
    //     }
    //     .rule-icon {
    //         position: absolute;
    //         left: 80%;
    //         top: 10%;
    //         z-index: 100;
    //     }
    //     .room-item {
    //         /deep/.van-grid-item__content {
    //             background-color: white;
    //             border-radius: 10px;
    //             background: linear-gradient(to bottom, #7953cd, #5527a4);
    //             position: relative;
    //             .desc {
    //                 position: absolute;
    //                 top: -6%;
    //                 z-index: 99;
    //                 left: 6%;
    //                 color: white;
    //                 width: 90%;
    //                 p {
    //                     height: 14px;
    //                     /deep/.van-image {
    //                         height: 14px;
    //                     }
    //                 }
    //                 span {
    //                     display: block;
    //                 }
    //             }
    //         }
    //     }
    // }
    /deep/.van-dialog {
        overflow: auto;
        font-size: 12px;
        height: 85vh;
        top: 50%;
        .van-dialog__header {
            padding-top: 9px;
        }
        .van-dialog__content {
            height: 85%;
            overflow: auto;
        }
        .van-dialog__footer {
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .content {
            padding: 0 10px;
        }
    }
}
</style>