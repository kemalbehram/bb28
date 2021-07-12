<template>
    <div>
        <van-cell :key="index" class="room-item" title="单元格" v-for="(item,index) in getRoomList" v-if="item.is_open" value="内容">
            <template #title>
                <div @click="toChatLotto(item.id,index)" class="lotto-info">
                    <div class="img">
                        <img :src="room_icon" alt />
                    </div>
                    <div class="info">
                        <span class="ml-16">
                            {{item.name}} (
                            <b class="color-red">{{item.dx}}/{{item.zh}}</b>)
                        </span>
                        <span class="ml-16 online">
                            <van-icon color="#AEAEAE" name="contact" size="14" />
                            {{item.onlinePeople}}人在线
                        </span>
                    </div>
                </div>
            </template>

            <template #default>
                <span @click="showRule(item.rules)" class="pt-10 font-12">赔率说明</span>
            </template>
        </van-cell>
        <van-popup :style="{ height: '70%' }" closeable position="bottom" v-model="dialogShow">
            <div class="content" v-html="rule"></div>
        </van-popup>
    </div>
</template>
<script>
export default {
    props: {
        lottoName: String,
    },
    data() {
        return {
            room_icon: '',
            dialogShow: false,
            rule: '',
        }
    },
    created() {
        this.room_icon = this.$store.state.config.lotto_items[this.lottoName].room_icon
    },
    methods: {
        showRule(e) {
            this.dialogShow = true
            this.rule = e
        },
        toLotto(room_id) {
            return this.$router.push({
                name: 'lottoTraRoom',
                params: {
                    name: this.lottoName,
                    room_id: room_id,
                },
            })
        },
        toChatLotto(room_id, index) {
            if (this.getRoomList[index]['is_open'] === false) {
                return this.$notify('该房间维护中。请到其他房间下注')
            }

            return this.$router.push({
                name: 'lottoChatRoom',
                params: {
                    name: this.lottoName,
                    room_id: room_id,
                },
            })
        },
        randomNum(minNum, maxNum) {
            switch (arguments.length) {
                case 1:
                    return parseInt(Math.random() * minNum + 1, 10)
                    break
                case 2:
                    return parseInt(Math.random() * (maxNum - minNum + 1) + minNum, 10)
                    break
                default:
                    return 0
                    break
            }
        },
    },
    computed: {
        getRoomList() {
            let roomList = []

            let play_types = this.$store.state.config.lotto_items[this.lottoName]['types']
            play_types.forEach((item, index) => {
                const data = {}
                data.name = this.$store.state.config.play_types[item].title
                data.id = this.$store.state.config.play_types[item].id
                data.rules = this.$store.state.config.play_types[item].rules
                data.onlinePeople = this.randomNum(10, 200)
                data.dx = this.$store.state.config.play_types[item].subtitle.dx
                data.zh = this.$store.state.config.play_types[item].subtitle.zh1 + '/' + this.$store.state.config.play_types[item].subtitle.zh2
                data.is_open = this.$store.state.config.play_types[item].is_open
                roomList.push(data)
            })
            // console.log(roomList)
            return roomList
        },
    },
}
</script>
<style lang="less" scoped>
img {
    width: 46px;
    height: 46px;
}
.lotto-info {
    // width: 250px;
    display: flex;
    .info {
        min-width: 50vw;
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
.content {
    padding: 35px 20px;
}
</style>