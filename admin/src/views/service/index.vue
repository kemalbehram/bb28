<template>
    <div>
        <div class="chat-message">
            <Card class="chat-message-left" dis-hover title="最近消息">
                <lett-loading :visible="loading" />
                <div class="chat-message-contact-wrap">
                    <div :class="current.target.id === item.target.id && 'bg-light'" :key="index" @click="getRecord(item.target.id, true)" class="chat-message-contact" v-for="(item, index) in sort">
                        <Avatar :src="item.target.avatar" class="chat-message-contact__avatar" size="44" />
                        <div class="chat-message-contact__nickname">{{item.target.nickname}}</div>
                        <div class="chat-message-contact__dot" v-if="item.unread" />
                        <template v-if="Object.values(item.record).length > 0">
                            <div class="chat-message-contact__time">{{$moment(item.record[item.record.length - 1].created_at).format('MM-DD HH:mm')}}</div>
                            <div class="chat-message-contact__newest text-ell-1">{{item.record[item.record.length - 1].message}}</div>
                        </template>

                        <template v-else>
                            <div class="chat-message-contact__time">NO DATE</div>
                            <div class="chat-message-contact__newest text-ell-1">NULL</div>
                        </template>
                    </div>
                </div>
            </Card>
            <message-content :current="current" ref="content" />
        </div>
        <!-- {{$store.state.service.active}}
        {{$store.state.service.lasts}}-->
    </div>
</template>

<script>
import messageContent from './content'
export default {
    components: {
        messageContent,
    },
    data() {
        return {
            loading: false,
            message: null,
            lasts: this.$store.state.service.lasts,
        }
    },

    created() {
        this.getIndex()
    },

    computed: {
        sort() {
            var temp = Object.values(this.$store.state.service.lasts)
            var sort = temp.sort(function (a, b) {
                return a.last_at - b.last_at
            })
            return sort.reverse()
        },

        current() {
            if (this.active) {
                let temp = JSON.parse(
                    JSON.stringify(this.$store.state.service.lasts[this.active])
                )
                var record = {}

                if (temp.record.length == 0) {
                    temp.record = record
                    return temp
                }

                temp.record.forEach((value) => {
                    var time = this.$moment(value.created_at).format(
                        'YYYY年MM月DD日'
                    )
                    record.hasOwnProperty(time) || (record[time] = [])
                    record[time].push(value)
                })

                temp.record = record
                return temp
            } else {
                return { target: {}, record: [] }
            }
        },

        active: {
            get: function () {
                return this.$store.state.service.active
            },
            set: function (value) {
                this.$store.state.service.active = value
            },
        },
    },

    watch: {
        '$store.state.service.user_new'(value) {
            if (value === true) {
                this.getIndex()
            }
        },

        '$store.state.service.modal'(value) {
            if (value === true) {
                this.updateRead(this.current.target.id)
            }
        },
    },

    methods: {
        scrollBottom() {
            this.$nextTick(() => {
                let msg = document.getElementById('chat-window')
                msg.scrollTop = msg.scrollHeight
            })
        },

        updateRead(id) {
            var form = { id }
            this.$post('/service/read', form, false).then((res) => {
                if (res.code !== 200) return false
            })
        },

        getIndex() {
            this.loading = true
            this.$get('/service/last').then((res) => {
                if (res.code !== 200) {
                    return (this.loading = res.message)
                }

                this.loading = false
                this.$store.state.service.lasts = res.data.items
                this.$store.state.service.new = res.data.new
                this.getRecord(res.data.latest)
            })
        },

        getRecord(id, read) {
            this.active = id
            this.$store.state.service.lasts[id].unread = false
            if (read === true) {
                this.updateRead(id)
            }
            var params = { id }
            this.scrollBottom()
            this.$refs.content.loading = false
            if (this.$store.state.service.lasts[id].has_get === true)
                return false

            this.$refs.content.loading = true
            this.$get('/service/record', params, false).then((res) => {
                if (res.code !== 200)
                    return (this.$refs.content.loading = res.message)
                var target = res.data.target
                this.$store.state.service.lasts[target.id].has_get = true
                this.$store.state.service.lasts[target.id].record =
                    res.data.items
                this.scrollBottom()
                this.$refs.content.loading = false
            })
        },
    },
}
</script>

<style lang="less" scope>
.chat-message {
    display: flex;
    &-left {
        background: #fff;
        width: 300px;
        /deep/.ivu-card-body {
            padding: 0 0 0 0;
        }
    }

    &-contact {
        border-bottom: 1px solid @line-light;
        position: relative;
        padding: 14px 16px 14px;
        cursor: pointer;
        &-wrap {
            height: 640px;
            overflow: scroll;
            overflow-x: hidden;
        }

        &__dot {
            position: absolute;
            width: 8px;
            height: 8px;
            border-radius: 100%;
            background: @red;
            left: 13px;
            top: 12px;
        }
        &__nickname {
            padding-left: 52px;
            margin-bottom: 2px;
        }

        &__avatar {
            position: absolute !important;
        }

        &__time {
            position: absolute;
            right: 16px;
            top: 12px;
            font-size: 13px;
            color: @sub;
        }

        &__newest {
            color: @desc;
            font-size: 13px;
            padding-left: 52px;
        }
    }
}
</style>
