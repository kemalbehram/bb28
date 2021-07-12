<template>
    <lett-skeleton :params="skeleton" :row="6" @on-default="getIndex" class="list-position" type="log-item">
        <!-- {{$parent.active}} -->
        <van-list :error-text="errorText" :error.sync="error" :finished="finished" @load="getIndex" class="reset mb-14" v-model="loading">
            <bet-log-items :items="items" />
        </van-list>
    </lett-skeleton>
</template>


<script>
import betLogItems from './items'
export default {
    props: {
        status: {
            default: null,
            type: [String, Number]
        }
    },
    components: {
        betLogItems
    },

    data() {
        return {
            loading: false,
            finished: false,
            error: false,
            errorText: '请求失败，点击重新加载',
            page: {
                current: 0
            },
            items: []
        }
    },

    computed: {
        skeleton() {
            if (this.loading === true && this.page.current === 0) {
                return true
            }

            if (this.loading === false && this.page.total === 0) {
                return '暂无相关记录 请重新查询'
            }
        }
    },

    created() {
        this.getIndex()
    },

    methods: {
        getIndex() {
            var params = { status: this.status }
            this.$getList('/wallet/bet-log', params).then(res => {
                if (res.data.page.total === 0) {
                    this.errorText = '暂无相关记录 请重新查询'
                }
            })
        }
    }
}
</script>

<style lang="less" scoped>
.list-position {
    position: absolute;
    top: 64px;
    width: 100%;
}
</style>