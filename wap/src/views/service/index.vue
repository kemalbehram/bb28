<template>
    <div class="chat-message-container">
        <van-nav-bar :border="false" class="shadow-bottom" fixed title="在线客服">
            <van-icon @click="$store.dispatch('toBack')" class="nav-bar-left" name="arrow-left" size="22" slot="left" />
            <!-- <p @click="$refs.qq.show = true" name="bars" size="22" slot="right">客服QQ</p> -->
        </van-nav-bar>

        <div class="chat-bar shadow-top">
            <van-field :border="false" class="chat-bar-content" placeholder="请输入聊天内容" v-model="message">
                <van-uploader :after-read="sendImage" class="chat-bar-button" slot="label">
                    <van-button :border="false" :loading="loading_image" class="chat-bar-button send-image" loading-type="spinner">图片</van-button>
                </van-uploader>

                <van-button :loading="loading_send" @click="sendMessage" class="chat-bar-button send-message" color="#ff6f64" loading-type="spinner" slot="button">发送</van-button>
            </van-field>
        </div>

        <items ref="chat" />

        <!-- <service-qq ref="qq" /> -->
    </div>
</template>


<script>
import items from './items'
import lrz from 'lrz'
// import serviceQq from '_c/service-qq'
export default {
    components: {
        items,
        // serviceQq
    },

    data() {
        return {
            message: '',
            loading: false,
            loading_send: false,
            loading_image: false,
            finished: false,
            error: false,
            errorText: '请求失败，点击重新加载',
            page: {
                current: 1,
            },
            items: [],
        }
    },
    activated() {
        this.$store.commit('read')
    },
    methods: {
        sendMessage() {
            this.message = this.message.replace(/(^\s*)|(\s*$)/g, '')
            if (!this.message) return false

            this.loading_send = true
            var form = { message: this.message }

            this.message = ''
            this.$refs.chat.scroll = true
            this.$post('service/send', form, false, false).then((res) => {
                this.loading_send = false
                if (res.code !== 200) return this.$notify(res.message)
            })
        },

        sendImage(file) {
            var that = this
            this.$refs.chat.scroll = true
            lrz(file.content, { width: 1000, height: 1000, quality: 0.9 })
                .then(function (res) {
                    let params = { file: res.base64, type: 'base64' }
                    that.loading_image = true
                    that.$post('/service/image', params, false, false).then((res) => {
                        that.loading_image = false
                        if (res.code !== 200) return that.$notify(res.message)
                    })
                })
                .catch(function (error) {
                    return that.$notify('图片转换失败 请重试')
                })
        },

        getIndex() {
            var params = {}
            params.page = this.page.current + 1
            this.loading = true
            this.$get('service/index', params).then((res) => {
                this.loading = false
                var item = res.data.items.reverse()
                this.$store.state.push.items = item.concat(this.$store.state.push.items)
                if (res.data.page.current >= res.data.page.last) {
                    this.finished = true
                }
                if (res.data.page.current == 1) {
                    this.$store.dispatch('chatScrollBottom')
                }
                this.page = res.data.page
            })
        },
    },
}
</script>
<style lang="less" scoped>
.chat-bar {
    position: fixed;
    bottom: 0;
    width: 100%;
    overflow: hidden;
    max-width: 768px;
    height: 50px;
    z-index: 2;
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
        // padding-right: 70px;
    }

    /deep/.van-field__label {
        max-width: 66px;
    }

    &-button {
        line-height: 36px;
        height: 36px;
        width: 60px;
        font-size: 12px;
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

.chat-message-container {
    height: 100vh;
    background: @white;
    position: absolute;
    width: 100%;
    padding-bottom: env(safe-area-inset-bottom);
}

.chat-message-input {
    position: fixed;
    bottom: 0px;
    width: 100%;
    z-index: 10;
}
.shadow-bottom {
    color: @white;
}
/deep/.van-field__left-icon {
    display: none;
}
/deep/.van-nav-bar__title {
    color: @white;
}
</style>