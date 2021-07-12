<template>
    <div class="chat-content">
        <Button @click="robotReceive" class="robot-get" type="warning">机器人抢红包（针对未过期的所有红包，点一次，机器人抢一次）</Button>
        <Row style="height:800px;">
            <lett-loading :visible="loading" />
            <Col span="14">
                <Card :bordered="false" dis-hover title="聊天记录">
                    <div class="chat-contents" id="chat-content" v-if="getChats">
                        <div :key="index" class="chat-item" v-for="(item,index) in getChats">
                            <Avatar :src="item.user ? item.user.avatar :''" class="mr-10" shape="circle" size="30" />

                            <div class="nickname">{{item.user ? item.user.nickname : ''}} {{item.created_at}}</div>
                            <span class="chat-info" v-if="item.type == 1">{{item.message}}</span>
                            <span class="red-bag-info" v-else>
                                <Poptip placement="right" transfer v-if="item.red_bag_id" width="350">
                                    <img :src="red_bag_bg" @click="getDetail(item.red_bag_id,id)" alt class="pointer" srcset width="100px" />

                                    <div slot="content">
                                        <div class="max-height" v-if="detail.code">
                                            <div style="text-align:center">
                                                <span class="color-red" v-if="detail.code==400">{{detail.message}}</span>
                                                <div>已领取：{{detail.data.received}}</div>
                                            </div>
                                            <table style="text-align:center" v-if="detail.data.logs.length>0">
                                                <thead>
                                                    <tr>
                                                        <th width="60">玩家</th>
                                                        <th width="60">领取金额</th>
                                                        <th width="120">领取时间</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr :key="r_index" v-for="(r_item,r_index) in detail.data.logs">
                                                        <td width="60">
                                                            <table-user :data="r_item.user" />
                                                        </td>
                                                        <td width="60">{{r_item.amount}}</td>
                                                        <td width="120">{{r_item.created_at}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div v-else>还没有人领取红包</div>
                                        </div>
                                    </div>
                                </Poptip>
                                <button @click="redCancel(item.red_bag_id,item.id)" class="red-cancel">撤回</button>
                                <b>{{ item.redbag ? item.redbag.total :''}}</b>
                            </span>
                        </div>
                    </div>
                </Card>
            </Col>
            <Col span="10">
                <Card :bordered="false" class="operate" dis-hover title="操作">
                    <div class="award">
                        <Form :label-width="80" :model="awardForm">
                            <FormItem label="红包金额">
                                <Input placeholder="请输入红包金额" v-model="awardForm.total"></Input>
                            </FormItem>
                            <FormItem label="红包类型">
                                <RadioGroup v-model="awardForm.type">
                                    <Radio label="turnover">流水红包</Radio>
                                    <Radio label="unique">指定红包</Radio>
                                </RadioGroup>
                            </FormItem>
                            <FormItem label="流水按" v-if="awardForm.type=='turnover'">
                                <RadioGroup v-model="awardForm.condition">
                                    <Radio label="yesterday">按昨日</Radio>
                                    <Radio label="today">按今日</Radio>
                                </RadioGroup>
                            </FormItem>
                            <FormItem label="满足流水" v-if="awardForm.type=='turnover'">
                                <Input placeholder="流水金额" v-model="awardForm.bet_amount"></Input>
                            </FormItem>
                            <FormItem label="红包个数" v-if="awardForm.type=='turnover'">
                                <Input placeholder="请输入红包个数" v-model="awardForm.quantity"></Input>
                            </FormItem>
                            <FormItem label="充值金额" v-if="awardForm.type=='turnover'">
                                <Input placeholder="当天充值金额限制" v-model="awardForm.recharge_amount"></Input>
                            </FormItem>
                            <FormItem label="用户ID" v-else>
                                <Input placeholder="请输入收红包的用户ID" v-model="awardForm.receive_id"></Input>
                            </FormItem>
                            <FormItem label="过期时间">
                                <DatePicker @on-change="confirmTime" placeholder="Select date and time" style="width: 290px" type="datetime"></DatePicker>
                            </FormItem>
                            <FormItem>
                                <Button @click="submitForm" type="primary">发送</Button>
                            </FormItem>
                        </Form>

                        <!-- <h4 class="ml-10">快捷红包(默认1小时过期)</h4>
                        <Row>
                            <Col class="red-bag-list" span="12">
                                <span>¥:5000</span>
                                <img :src="red_bag_bg" @click="sendRedBag(5000,10,'turnover')" alt width="130px" />
                                <p class>普通红包</p>
                                <p>个数:10</p>
                            </Col>
                            <Col class="red-bag-list" span="12">
                                <span>¥:1000</span>
                                <img :src="red_bag_bg" @click="sendRedBag(1000,10,'turnover')" alt width="130px" />
                                <p class>普通红包</p>
                                <p>个数:10</p>
                            </Col>
                            <Col class="red-bag-list" span="12">
                                <span>¥:100</span>
                                <img :src="red_bag_bg" @click="sendRedBag(100,10,'turnover')" alt width="130px" />
                                <p class>普通红包</p>
                                <p>个数:10</p>
                            </Col>
                            <Col class="red-bag-list" span="12">
                                <span>¥:10</span>
                                <img :src="red_bag_bg" @click="sendRedBag(10,1,'normal')" alt width="130px" />
                                <p class>普通红包</p>
                                <p>个数:1</p>
                            </Col>
                        </Row>-->
                    </div>
                    <div class="send-content">
                        <Input class="mr-6" placeholder="请输入聊天内容..." style="width: 320px" v-model="chatValue" />

                        <Button @click="sendMessage" class type="info">发送</Button>
                    </div>
                </Card>
            </Col>
        </Row>
    </div>
</template>
<script>
export default {
    data() {
        return {
            loading: false,
            chatValue: '',
            awardForm: {
                total: 0,
                type: 'unique',
                quantity: 0,
                condition: 'yesterday',
                expired_at: '',
                bet_amount: 0,
                recharge_amount: 0,
            },
            red_bag_bg: require('../../assets/images/hongbao.png'),
            redBagList: [],
            isLoad: false, //是否加载更多
            isFinish: false, //是否加载完成
            page: 1,
            ruleValidate: {
                total: [{ required: true, message: '金额不能为空', trigger: 'blur' }],
                quantity: [{ required: true, message: '数量不能为空', trigger: 'blur' }],
                expired_at: [
                    {
                        required: true,
                        message: '请选择过期时间',
                        trigger: 'blur',
                    },
                ],
            },
            detail: {},
        }
    },
    created() {
        this.getIndex(false), this.getRedBag()
    },
    computed: {
        getChats() {
            return this.$store.state.chat.chat_contents
        },
    },
    mounted() {
        document.getElementById('chat-content').addEventListener('scroll', this.chatScroll)
        this.$store.dispatch('chatsScrollBottom')
    },

    methods: {
        getDetail(id, data_id) {
            this.$get('/red-bag/get', { id: id }, false, false).then((res) => {
                if (res.code != 200 && res.code != 400) {
                    let temp = []
                    for (var i = 0; i < this.$store.state.chat.chat_contents.length; i++) {
                        if (this.$store.state.chat.chat_contents[i]['id'] != data_id) {
                            temp.push(this.$store.state.chat.chat_contents[i])
                        }
                    }
                    this.$store.state.chat.chat_contents = temp
                    return this.$Message.warning('该红包已撤回')
                }
                this.detail = res
            })
        },
        chatScroll() {
            var dom = document.getElementById('chat-content')
            var scrollTop = dom.scrollTop
            if (scrollTop == 0) {
                this.getIndex(true)
            }
        },
        getIndex(loadding, cancel = false) {
            let param = {}
            if (loadding) {
                param.page = ++this.page
            } else {
                param.page = 1
            }
            this.loading = true
            this.$get('chat/index', param).then((res) => {
                if (res.code !== 200) {
                    return (this.loading = res.message)
                }
                if (res.data.items.length == 0) {
                    return this.$Message.warning('聊天记录已完毕')
                }
                var item = res.data.items.reverse()
                if (cancel) {
                    this.$store.state.chat.chat_contents = item
                } else {
                    this.$store.state.chat.chat_contents = item.concat(this.$store.state.chat.chat_contents)
                }
            })
        },
        getRedBag() {
            this.$get('red-bag/index').then((res) => {
                if (res.code !== 200) {
                    return (this.loading = res.message)
                }
                this.redBagList = res.data.items
            })
        },
        sendMessage() {
            if (this.chatValue == '') {
                return this.$Message.warning('聊天记录不能为空')
            }
            let param = {
                message: this.chatValue,
            }
            this.$post('chat/send', param).then((res) => {})
        },
        sendRedBag(price, number, type) {
            this.$Modal.confirm({
                title: '快捷红包',
                content: '确定发送金额为' + price + '的红包吗',
                onOk: function () {
                    let param = {
                        total: price,
                        quantity: number,
                        type: type,
                    }
                    this.$post('chat/redBagSend', param).then((res) => {
                        // return this.$Message.success(res.message)
                    })
                },
            })
        },
        confirmTime(e) {
            this.awardForm.expired_at = e
        },
        submitForm() {
            // || this.awardForm.quantity == 0
            if (this.awardForm.total == 0 || this.awardForm.expired_at == '') {
                return this.$Message.warning('参数错误！')
            }

            this.$post('chat/redBagSend', this.awardForm).then((res) => {
                // return this.$Message.success(res.message)
                // this.getIndex()
            })
        },
        redCancel(id, chat_id) {
            let param = { id, chat_id }
            this.$post('chat/redBagCancel', param).then((res) => {
                this.getIndex(false, true)
            })
        },
        robotReceive() {
            this.$post('chat/robotReceive').then((res) => {
                console.log(res)
            })
        },
    },
}
</script>
<style lang="less" scoped>
/deep/.ivu-card {
    height: 677px;
}
.chat-contents {
    padding: 10px 12px;
    margin: 0 auto;
    height: 688px;
    overflow: scroll;
    position: relative;

    .chat-item {
        position: relative;
        margin-top: 10px;
        .chat-common-avatar {
            vertical-align: bottom;
            width: 38px;
            height: 38px;
            padding-left: 10px;
        }

        .nickname {
            font-size: 10px;
            position: absolute;
            top: -5%;
            left: 10%;
        }
        .chat-info {
            padding: 5px 10px;
            background: #d4b79a;
            color: #fff;
            max-width: 60%;
            word-wrap: break-word;
            position: relative;
            margin-left: 10%;
            display: table;
            margin-top: -2%;
            border-radius: 3px;
        }

        .red-bag-info {
            padding: 5px 10px;
            color: #fff;
            max-width: 60%;
            word-wrap: break-word;
            position: relative;
            margin-left: 5%;
            display: table;
            margin-top: -2%;
            border-radius: 3px;
            b {
                position: absolute;
                top: 30%;
                left: 39%;
                transform: translate(-50%, -30%);
                color: #f9e704;
            }
            .red-cancel {
                border: unset;
                color: white;
                padding: 2px 5px;
                background: #7f7f7f;
                border-radius: 3px;
            }
        }

        .chat-info::before {
            content: '';
            position: absolute;
            border-top: 3px solid transparent;
            border-right: 4px solid #d4b79a;
            border-bottom: 3px solid transparent;
            left: -4px;
            top: 11px;
        }
    }
}
.operate {
    position: relative;
    height: 700px;
    /deep/.ivu-card-body {
        height: 100%;
    }
    .send-content {
        position: absolute;
        bottom: 0%;
    }
}
.red-bag-list {
    text-align: center;
    position: relative;
    cursor: pointer;
    span {
        font-size: 16px;
        position: absolute;
        left: 50%;
        top: 30%;
        transform: translate(-50%, -50%);

        color: #f9e704;
    }
    p {
        height: 20px;
    }
}
.max-height {
    max-height: 250px;
    overflow-y: scroll;
}
</style>