<template>
    <Card class="chat-message-right" dis-hover>
        <lett-loading :visible="loading" />
        <p @click="$store.dispatch('onMemberInfo', current.target.id)" class="pointer" slot="title">{{current.target.nickname || '加载中'}}</p>

        <div class="chat-message-content" id="chat-window">
            <div :key="time" v-for="(group,time) in current.record" v-if="current.has_get">
                <chat-item-time>{{time}}</chat-item-time>

                <template v-for="item in group">
                    <chat-item-image :avatar="getAvatar(item.from,current.target.avatar)" :data="item" :key="item.id" :opposite="item.from === 100000" v-if="item.message_type === 'image'" />
                    <chat-item-text :avatar="getAvatar(item.from,current.target.avatar)" :data="item" :key="item.id" :opposite="item.from === 100000" v-else />
                </template>
            </div>
        </div>

        <div class="chat-message-textarea clear-both">
            <Input :rows="4" @on-enter="sendMessage" maxlength="100" placeholder="Enter something..." type="textarea" v-model="message" />
            <input @change="changeFile" accept="image/*" class="mt-16" name="image" ref="input" type="file" />
            <Button @click="sendMessage" class="chat-message-textarea__button" type="primary">发送消息</Button>
        </div>
    </Card>
</template>

<script>
export default {
    props: {
        current: Object,
    },

    data() {
        return {
            loading: true,
            message: '',
        }
    },

    methods: {
        getAvatar(id, avatar) {
            if (id === 100000) {
                return this.$store.state.config.service_avatar
            } else {
                return avatar
            }
        },
        sendMessage() {
            if (!this.message) {
                return false
            }

            var message = {
                to: this.current.target.id,
                message: this.message,
                from: 10000000,
                created_at: this.$moment().format('YYYY-MM-DD HH:mm:ss'),
                last_at: new Date().getTime() / 1000,
            }

            this.$store.state.service.lasts[message.to].unread = false
            // this.$store.state.service.lasts[message.to].record.push(message)
            this.$parent.scrollBottom()
            this.message = ''

            this.$store.state.service.lasts[message.to].last_at =
                message.last_at
            // return false
            this.$post('/service/send', message, false).then((res) => {
                if (res.code !== 200) return false
                // this.$store.state.service.lasts[res.data.to].last_at =
                //     res.data.last_at
                // this.$store.state.service.lasts[res.data.to].record.push(res.data)
                // this.$parent.scrollBottom()
                // this.message = ''
            })
        },

        changeFile(file) {
            if (!file.target.files[0]) return false
            this.loading = true
            var forms = new FormData()
            forms.append('to', this.current.target.id)
            var configs = {
                headers: { 'Content-Type': 'multipart/form-data' },
            }
            forms.append('file', file.target.files[0])

            if (this.extend != undefined) {
                var extend = Object.keys(this.extend)
                extend.forEach((item) => {
                    forms.append(item, this.extend[item])
                })
            }

            this.$post('/service/image', forms, false).then((res) => {
                this.loading = false
                if (res.code !== 200) {
                    this.$Message.warning(res.message)
                }
            })

            this.$refs.input.value = null
        },
    },
}
</script>

<style lang="less" scoped>
.chat-message {
    &-right {
        flex: 1;
        background: @white;
        margin-left: 10px;
    }

    &-content {
        height: 450px;
        overflow: scroll;
        overflow-x: hidden;
    }

    &-textarea {
        padding: 16px 0 0;

        &__button {
            margin-top: 14px;
            float: right;
        }
    }
}
</style>
