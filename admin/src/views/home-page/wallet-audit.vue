<template>
    <div class="table mt-10">
        <lett-loading :visible="loading" />
        <div class="header-action">
            <div>
                <Icon color="#FFF" size="16" type="logo-yen" />
                <span class="color-white">上下分审核</span>
            </div>
            <div>
                <Icon @click="refresh" class="cursor" color="#FFF" size="20" title="刷新" type="md-refresh" />
            </div>
        </div>
        <div class="wallet">
            <div :key="index" class="wallet-list" v-for="(item,index) in $store.state.home.audit">
                <div class="wallet-list--info">
                    <table-user :data="item.user" slot="nickname" />
                    <span class="color-green" v-if="item.type=='0'">充值</span>
                    <span class="color-red" v-else>提现</span>
                    <span class="pl-10">{{item.amount}}</span>
                </div>
                <div>
                    <!-- <Button @click="remarks(item,index)" size="small" type="primary">备注</Button> -->
                    <Button @click="pass(item,index)" size="small" type="success">通过</Button>
                    <Button @click="refuse(item,index)" size="small" type="warning">拒绝</Button>
                </div>
                <br />
                <div class="add-info">
                    <span>真实姓名：{{item.user.real_name ? item.user.real_name :'空'}}</span>
                    <span>
                        备注：{{item.user.username ? item.user.username :'空'}}
                        <Button @click="showUserName(item,index)" size="small" type="success">修改</Button>
                    </span>
                </div>
            </div>
        </div>
        <Modal :footer-hide="true" @on-visible-change="changeShow" draggable title="审核" v-model="block_modal" width="1000px">
            <audit-item :audit_info="update_audit" @on-search="onSearch" @success="success" ref="table" v-if="update_audit" />
        </Modal>
    </div>
</template>

<script>
import JsMixins from '@/components-ue/table/table-index.js'
import auditItem from './audit'
export default {
    components: {
        auditItem,
    },
    mixins: [JsMixins],
    data() {
        return {
            // items: [],
            block_modal: false,
            update_audit: null,
            config: {
                value: {},
            },
            choose_index: null,
            loading: false,
        }
    },
    created() {
        this.getAudit()
        const timer = setInterval(() => {
            this.getAudit()
        }, 30000)
        this.$once('hook:beforeDestroy', () => {
            clearInterval(timer)
        })
    },

    methods: {
        refresh() {
            this.getAudit()
        },
        getAudit() {
            this.$get('/home/get-wallet').then((res) => {
                this.$store.state.home.audit = res.data.items
            })
        },
        refuse(item, index) {
            let str = ''
            str += item.type == 0 ? '充值' : '提现'
            str += item.amount + '元'
            this.$dialog
                .confirm({
                    title: '确认拒绝这笔订单？',
                    message: str,
                })
                .then(() => {
                    let param = {
                        status: 3,
                        id: item.id,
                        remark: '您的' + item.type == 0 ? '充值' : '提现' + '申请未通过',
                    }
                    let url = item.type == 0 ? '/recharge/update' : '/withdraw/update'
                    this.loading = true
                    this.$post(url, param).then((res) => {
                        if (res.code == 200) {
                            this.$store.state.home.audit.splice(index, 1)
                        }
                        this.loading = false
                    })
                })
                .catch(() => {
                    // on cancel
                })
        },
        pass(item, index) {
            this.update_audit = item
            this.choose_index = index
            this.block_modal = true
        },
        changeShow(status) {
            if (status == false) {
                this.$refs.table.getIndex()
            }
        },
        success() {
            this.update_audit = null
            this.block_modal = false
            this.$store.state.home.audit.splice(this.choose_index, 1)
            this.choose_index = null
        },
        showUserName(userInfo, index) {
            let username = userInfo.user.username
            let user_id = userInfo.user_id
            this.$Modal.confirm({
                render: (h) => {
                    return h('Input', {
                        props: {
                            value: username,
                            autofocus: true,
                            placeholder: '输入用户备注',
                        },
                        on: {
                            input: (val) => {
                                username = val
                            },
                        },
                    })
                },
                onOk: () => {
                    let param = {
                        username: username,
                        user_id: user_id,
                    }
                    this.updateUserName(param, index)
                },
            })
        },

        updateUserName(Param, index) {
            this.$post('/member/username-update', Param).then((res) => {
                if (res.code == '200') {
                    this.$store.state.home.audit[index].user.username = Param.username
                }
            })
        },
    },
}
</script>

<style lang="less" scoped>
.table {
    background: @white;
    .header-action {
        height: 40px;
        padding: 0 10px;
        background: @blue;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .wallet {
        height: 478px;
        overflow-y: scroll;
    }
    .wallet-list {
        padding: 0 10px;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #eaecef;
        &--info {
            display: flex;
            align-items: center;
        }
        .add-info {
            display: flex;
            justify-content: space-around;
            width: 100%;
            span {
                display: inline-block;
            }
        }

        button {
            margin: 0 3px;
        }
    }
}
.cursor {
    cursor: pointer;
}
</style>