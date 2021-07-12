<template>
    <Card dis-hover>
        <table-header title="用户查询">
            <div class="header-intro">请输入用户ID查询指定用户</div>
            <Input @on-search="onInput" class="lett-search" search style="width:300px" v-model="input">
                <Select @on-change="onQuery" placeholder="最近查询" slot="prepend" style="width:100px" v-model="select">
                    <Option :key="index" :value="item" v-for="(item ,index) in query_log">{{ item }}</Option>
                </Select>
                <Button @click="onInput" icon="ios-search" slot="append"></Button>
            </Input>
        </table-header>
        <data-content :params="params" :top="this" class="member-info mb-10" ref="content" />
    </Card>
</template>

<script>
import dataContent from '../drawer/member-info/single-page'
export default {
    name: 'memberSingle',
    components: {
        dataContent
    },

    data() {
        return {
            params: {
                id: 100000
            },
            input: null,
            select: null,
            query_log: []
        }
    },

    created() {
        this.params.id = this.$store.state.config.user_query_default
    },

    methods: {
        getData() {
            if (!this.params.id) {
                return this.$Message.info('请输入要查询的用户ID')
            }
            this.$refs.content.getData(true)
        },

        onInput() {
            this.params.id = this.input
            this.getData()
        },

        onQuery(id) {
            if (id) {
                this.params.id = id
                this.getData()
            }
            this.input = null
        },

        setQueryLog(id) {
            if (this.query_log.indexOf(id) === -1) {
                this.query_log.unshift(id)
            }
        }
    }
}
</script>

<style lang="less" scoped>
.header-intro {
    color: @sub;
    margin-top: -4px;
}

/deep/.ivu-select-item {
    text-align: left;
}

.member-info {
    min-height: calc(100vh - 250px);
}
</style>