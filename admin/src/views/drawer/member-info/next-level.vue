<template>
    <Drawer :closable="false" :footer-hide="true" @on-visible-change="onVisible" class="drawer-with-action" title="下级用户" v-model="visible" width="840">
        <table-header class="mb-0" slot="header" title="下级用户">
            <div class="color-sub font-14">
                <b class="color-red mr-6">{{current.nickname}}</b>
                <span>{{excerpt}}</span>
            </div>
        </table-header>

        <lett-loading :visible="loading" @click="getIndex()" />
        <Table :columns="columns" :data="items" :height="230 | table_height" border>
            <div @click="getIndex(row.user_id)" class="pointer" slot="user" slot-scope="{row}">
                <Avatar :src="row.user.avatar" class="mr-10" shape="circle" size="22" />
                <span>{{row.user.nickname}}</span>
            </div>
            <template slot="created_at" slot-scope="{row}">{{$moment(row.created_at).format('MM-DD HH:mm:ss')}}</template>
        </Table>
    </Drawer>
</template>

<script>
export default {
    data() {
        return {
            visible: false,
            loading: false,
            query: {},
            columns: [
                {
                    title: '下级用户',
                    slot: 'user',
                    align: 'left'
                },
                {
                    title: '下级ID',
                    key: 'user_id',
                    align: 'left'
                },

                {
                    title: '时间',
                    slot: 'created_at',
                    align: 'center',
                    width: 120
                }
            ],
            items: [],
            current: {},
            excerpt: '数据加载中'
        }
    },

    computed: {
        member() {
            return this.$parent.data
        }
    },

    methods: {
        getIndex(id) {
            this.query.user_id = this.$parent.data.id
            if (id) this.query.user_id = id

            var api = '/member/next-level'

            this.$get(api, this.query).then(res => {
                this.loading = true
                if (res.code !== 200) {
                    return (this.loading = res.message)
                }
                this.loading = false
                this.items = res.data.items
                this.excerpt = res.data.excerpt
                this.current = res.data.current
            })
        },

        onVisible(visible) {
            if (visible === false) {
                return false
            }

            return this.getIndex()
        }
    }
}
</script>


<style lang="less" scoped>
.drawer-with-action {
    /deep/.ivu-drawer-header {
        border: 0;
    }

    /deep/.ivu-drawer-body {
        padding-top: 0px;
    }
}
</style>
