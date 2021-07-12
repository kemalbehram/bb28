<template>
    <div class="conetnt bg-white">
        <div v-if="content.code">
            <div class="action">
                <div class="action-title">恭喜发财,大吉大利</div>
                <div class="pb-10" v-if="detail.code==200">
                    <van-button @click="receiveBag" type="primary">立即领取</van-button>
                </div>
                <div class="pb-10" v-else>
                    <span class="font-18 color-red">{{content.message}}</span>
                </div>
            </div>

            <div class="line">
                <span>已领取{{content.data.logs.length}}个</span>
                <span>{{content.data.received}}/{{content.data.total}}</span>
            </div>
            <div>
                <div v-if="content.data.logs.length>0">
                    <!-- <van-cell :key="index" :value="'￥'+item.amount" v-for="(item,index) in content.data.logs">
                        <template #title>
                            <div class="display-flex">
                                <van-image :src="item.user.avatar" height="30" round width="30" />
                                <span class="custom-title pl-10">{{item.user.nickname}}</span>
                            </div>
                        </template>
                    </van-cell>-->
                </div>

                <van-empty description="还没有人领取红包哦" v-else />
            </div>
        </div>
        <van-loading type="spinner" v-else />
    </div>
</template>

<script>
export default {
    props: {
        detail: {
            type: Object,
            default: {},
        },
        index: {
            type: Number,
            default: 0,
        },
    },
    computed: {
        content() {
            return this.detail
        },
    },
    methods: {
        receiveBag() {
            let param = {}
            param.id = this.content.data.id
            setTimeout(() => {
                this.$post('red-bag/chatReceive', param).then((res) => {
                    // this.$toast.clear()
                    if (res.code == 200) {
                        this.$emit('success', this.index)
                        this.content.data.logs.unshift({
                            amount: res.data.amount,
                            user: this.$store.state.user.info,
                        })
                        this.content.code = 400
                        this.content.message = '您已领取过该红包'
                    }
                })
            }, 1000)
        },
    },
}
</script>

<style lang="less" scoped>
.conetnt {
    width: 100%;
    height: 100%;
}
.line {
    height: 25px;
    width: 100%;
    background: #f3f3f3;
    line-height: 25px;
    display: flex;
    justify-content: space-between;
    padding: 0 15px;
    padding-left: 20px;
    color: @desc;
}
.action {
    text-align: center;
    &-title {
        padding: 10px 0;
    }
}
.display-flex {
    align-items: center;
}
</style>