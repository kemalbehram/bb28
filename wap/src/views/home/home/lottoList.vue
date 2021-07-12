<template>
    <div class="lotto-list">
        <div class="service">
            <van-cell icon="service" is-link label="点击即可在线咨询" to="/service">
                <!-- 使用 right-icon 插槽来自定义右侧图标 -->
                <template #title>在线客服</template>
                <template #icon>
                    <van-image class="mr-10" fit="contain" height="45px" round src="http://ooo28-public.oss-accelerate.aliyuncs.com/yl28/icon/server.png" width="45px" />
                </template>
            </van-cell>
            <van-cell icon="refund-o" is-link label="快来抢红包吧" to="/chatroom">
                <!-- 使用 right-icon 插槽来自定义右侧图标 -->
                <template #title>红包聊天室</template>
                <template #icon>
                    <van-image class="mr-10" fit="contain" height="45px" round src="http://ooo28-public.oss-accelerate.aliyuncs.com/yl28/icon/redmoney.png" width="45px" />
                </template>
            </van-cell>
        </div>
        <van-collapse v-model="activeNames">
            <van-collapse-item :key="index" :name="index" v-for="(item,index) in lotto">
                <template #title>
                    <div class="content-flex">
                        <!-- <van-icon class="icon-title" name="question-o" /> -->
                        <van-image :src="item.room_icon" class="mr-10" fit="contain" height="45px" round width="45px" />
                        <div>
                            <span class="icon-title">{{item.subtitle}}</span>
                            <span class="icon-title intro font-12">{{item.intro}}</span>
                        </div>
                    </div>
                </template>
                <room-list :lottoName="item.name" />
            </van-collapse-item>
        </van-collapse>
    </div>
</template>
<script>
import roomList from '_c/room-list'
export default {
    data() {
        return {
            activeNames: ['0'],
        }
    },
    components: {
        roomList,
    },
    computed: {
        lotto() {
            return this.$store.state.config.lotto_items
        },
        getRoomList() {
            let roomList = []
            Object.values(this.$store.state.config.play_types).forEach((item, index) => {
                if (item.name.indexOf('room') != -1) {
                    const data = {}
                    data.name = item.title
                    data.id = item.id
                    data.rules = item.rules
                    data.onlinePeople = this.randomNum(10, 200)
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
}
</script>
<style lang="less" scoped>
.service {
    background: white;
}
.icon-title {
    vertical-align: middle;
}
.content-flex {
    display: flex;
    span {
        display: block;
    }
    .intro {
        color: #969799;
    }
}
// .lotto-list {
//     position: fixed;
//     width: 100%;
//     top: 44px;
//     z-index: 20;
// }
</style>