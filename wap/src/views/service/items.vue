<template>
    <div :class="{anim:animate==true}" class="chat-message-content" id="chat-window" v-swipedown="(e)=>touch('toTop')" v-swipeup="(e)=>touch('toDwon')">
        <van-pull-refresh @refresh="$parent.getIndex" loosing-text="释放加载更多聊天" pulling-text="下拉即可加载" v-model="$parent.loading">
            <div slot="loading">数据加载中</div>
            <div :key="time" v-for="(group,time) in current.record">
                <chat-item-time :time="time" />

                <template v-for="item in group">
                    <chat-item-image :avatar="getAvatar(item.from)" :data="item" :key="item.id" :opposite="item.from !== 100000" :wechat="item.wechat" v-if="item.message_type === 'image'" />
                    <chat-item-text :avatar="getAvatar(item.from)" :data="item" :key="item.id" :opposite="item.from !== 100000" :wechat="item.wechat" v-else />
                </template>
            </div>
        </van-pull-refresh>
    </div>
</template>

<script>
import chatItemTime from '_c/chat/item-time'
import chatItemText from '_c/chat/item-text'
import chatItemImage from '_c/chat/item-image'

export default {
    components: {
        chatItemTime,
        chatItemText,
        chatItemImage,
    },
    data() {
        return {
            animate: false,
            scroll: true,
        }
    },
    computed: {
        current() {
            let temp = JSON.parse(JSON.stringify(this.$store.state.push.items))
            var record = {}
            temp.forEach((value) => {
                var time = value.created_at.substring(5, 7) + '月' + value.created_at.substring(8, 10) + '日'
                record.hasOwnProperty(time) || (record[time] = [])
                record[time].push(value)
            })
            temp.record = record
            return temp
        },
    },
    watch: {
        'current.record'() {
            if (this.scroll == false) {
                return
            }
            this.scrollBottom()
        },
    },
    created() {
        this.scrollBottom()
    },
    methods: {
        getAvatar(id) {
            if (id === 100000) {
                return 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/service_logo.png'
            } else {
                return this.$store.state.user.info.avatar
            }
        },
        scrollBottom() {
            this.animate = true
            this.$nextTick(() => {
                let el = document.getElementById('chat-window')
                console.log(el.scrollHeight)
                el.scrollTo({
                    top: el.scrollHeight,
                    behavior: 'smooth',
                })
            })
        },
        touch(type) {
            let el = document.getElementById('chat-window')
            let toBottom = el.scrollHeight - el.scrollTop
            if (toBottom > 1000) {
                console.log('不滑了')
                this.scroll = false
            } else {
                if (toBottom < 1000) {
                    console.log('又滑了')
                    this.scroll = true
                }
            }
        },
    },
}
</script>

<style lang="less" scoped>
.chat-message-content {
    // padding: 50px 12px 60px;
    // height: 100%;
    // overflow: scroll;
    // background: @white;
    overflow: auto;
    position: fixed;
    width: 100%;
    padding: 0 12px;
    height: calc(100% - 96px);
    bottom: 50px;
    background: #fff;
    z-index: 9999;
}
.anim {
    transition: all 0.5s;
    margin-top: -30px;
}
/deep/.van-pull-refresh {
    user-select: all !important;
}
/deep/.van-pull-refresh__head {
    font-size: 13px;
}
</style>