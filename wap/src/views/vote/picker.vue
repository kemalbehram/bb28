<template>
    <div>
        <userHeader :close="true" title="帐户明细">
            <h2 @click="confirm" name="action">确认选择</h2>
        </userHeader>
        <van-grid :border="false" :column-num="2">
            <van-grid-item>
                <van-button @click="showTimePick('start')" plain style="border:none;font-size:12px">
                    <b>
                        {{start}}
                        <van-icon name="arrow-down" />
                    </b>
                </van-button>
            </van-grid-item>
            <van-grid-item>
                <van-button @click="showTimePick('end')" plain style="border:none;font-size:12px">
                    <b>
                        {{end}}
                        <van-icon name="arrow-down" />
                    </b>
                </van-button>
            </van-grid-item>
        </van-grid>

        <div class="choose-room">
            <van-button :class="index===choose_room?' choosed':''" @click="choose(item.name,item.room,index)" class="condition" size="small" type="default" v-for="(item,index) in condition">{{item.game}}-{{item.title}}</van-button>
        </div>
        <van-popup :style="{ height: '80%' }" position="bottom" v-model="showTime">
            <van-datetime-picker :min-date="minDate" @cancel="showTime = false" @confirm="getCurrentTime" title="选择年月日" type="date" />
        </van-popup>
    </div>
</template>
<script>
import userHeader from '@/components/user-header'
export default {
    components: {
        userHeader,
    },
    data() {
        return {
            minDate: new Date(2019, 1, 1),
            showTime: false,
            start: '',
            end: '',
            game: '',
            room: '',
            choose_time: '',
            choose_room: '',
        }
    },
    created() {
        this.getToday()
    },
    computed: {
        condition() {
            let arr = []
            let game = Object.values(this.$store.state.config.lotto_items)
            let room = this.$store.state.config.play_types
            game = Array.from(game)

            game.forEach(function (value, key) {
                value.types.forEach(function (val, kkk) {
                    arr.push({ game: value.title, name: value.name, room: val, title: room[val].title })
                })
            })
            return arr
        },
    },
    methods: {
        showTimePick(value) {
            this.choose_time = value
            this.showTime = true
        },
        getToday() {
            var date = new Date()
            let nowMonth = date.getMonth() + 1
            let day = date.getFullYear() + '-' + nowMonth + '-' + date.getDate()
            this.start = day
            this.end = day
        },
        getCurrentTime(time) {
            let nowMonth = time.getMonth() + 1
            if (this.choose_time == 'start') {
                this.start = time.getFullYear() + '-' + nowMonth + '-' + time.getDate()
            } else {
                this.end = time.getFullYear() + '-' + nowMonth + '-' + time.getDate()
            }
            this.showTime = false
        },
        choose(game, room, index) {
            if (this.choose_room === index) {
                this.game = ''
                this.room = ''
                this.choose_room = ''
                return
            }
            this.game = game
            this.room = room
            this.choose_room = index
        },
        confirm() {
            let param = {
                start: this.start,
                end: this.end,
                game: this.game,
                room: this.room,
            }
            this.$emit('success', param)
        },
    },
}
</script>
<style lang="less" scoped>
.choose-room {
    padding: 5px 15px;
    .condition {
        margin: 5px;
    }
    .choosed {
        background: @blue-common;
        color: @white;
    }
}
</style>