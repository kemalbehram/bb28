<template>
    <Card :bordered="bordered" dis-hover>
        <table-header-new :config.sync="config" @on-search="onSearch" title="所有下注">
            <Select @on-change="onSearch" size="small" v-model="config.value.lotto_name">
                <Option value="all">所有游戏</Option>
                <Option :key="item.name" :value="item.name" v-for="item in this.$store.state.config.lotto_items">{{item.title}}</Option>
            </Select>

            <Select @on-change="onSearch" class="ml-6" size="small" v-model="config.value.room">
                <Option value="all">所有房间</Option>
                <Option value="room1">房间一(1.8/4.25/4.65)</Option>
                <Option value="room2">房间二(1.88/4.25/4.65)</Option>
                <Option value="room3">房间三(2.0/4.25/4.65)</Option>
                <Option value="room4">房间四(2.0/4.25/5)</Option>
                <Option value="room5">房间五(2.8/6.0/6.0)</Option>
                <Option value="room6">房间六(3.2/6.5/7.0)</Option>
                <Option value="room7">房间七(2.3/4.5/5.5)</Option>
                <Option value="room8">房间八(2.3/4.8/5.9)</Option>
            </Select>
            <Select @on-change="onSearch" class="ml-6" size="small" v-model="config.value.status">
                <Option value="0">所有状态</Option>
                <Option value="1">待开奖</Option>
                <Option value="2">已开奖</Option>
                <Option value="2-1">已中奖</Option>
                <Option value="3">取消投注</Option>
            </Select>

            <template v-if="assign === null">
                <Select @on-change="onSearch" class="ml-6" size="small" v-model="config.value.trial">
                    <Option value="0">正常投注</Option>
                    <Option value="all">所有投注</Option>
                    <Option value="1">试玩投注</Option>
                </Select>
            </template>

            <Select @on-change="onSearch" class="ml-6" size="small" v-model="config.value.model">
                <Option value="group">单期模式</Option>
                <Option value="detail">明细模式</Option>
            </Select>

            <template v-if="assign === null">
                <Button @click="onReset" class="ml-10" size="small" type="primary">初始</Button>
                <Button @click="onSearch" class="ml-6" size="small" type="primary">
                    <Checkbox @on-change="autoLoad" size="small"></Checkbox>
                    <span class="font-13">刷新</span>
                </Button>
            </template>
        </table-header-new>

        <table-data :assign="assign" :table-columns="columns" @on-search="onSearch" ref="table" />
    </Card>
</template>

<script>
import tableData from './table-data'
import JsMixins from '@/components-ue/table/table-index.js'
export default {
    mixins: [JsMixins],
    name: 'lottoData',
    components: { tableData },
    props: {
        assign: {
            default: null,
            type: [String, Number],
        },

        columns: {
            default: function () {
                return [
                    {
                        title: '流水ID',
                        width: 120,
                        slot: 'id',
                        align: 'left',
                    },
                    {
                        title: '用户',
                        slot: 'user',
                        align: 'left',
                        minWidth: 100,
                    },
                    {
                        title: '游戏',
                        slot: 'lotto',
                        align: 'left',
                        width: 280,
                    },
                    {
                        title: '投注',
                        key: 'total',
                        align: 'right',
                    },

                    {
                        title: '中奖',
                        align: 'right',
                        slot: 'bonus',
                    },

                    {
                        title: '盈亏',
                        align: 'right',
                        slot: 'profit',
                    },
                    {
                        title: '下注时间',
                        align: 'right',
                        slot: 'created_at',
                        minWidth: 30,
                    },

                    {
                        title: '操作',
                        slot: 'action',
                        width: 140,
                        align: 'center',
                    },
                ]
            },

            type: Array,
        },
    },

    computed: {
        bordered() {
            return this.assign ? false : true
        },
    },
    data() {
        return {
            config: {
                value: {
                    status: '0',
                    trial: '0',
                    model: 'group',
                    lotto_name: 'all',
                    room: 'all',
                },
                fields: [
                    {
                        value: 'id',
                        label: '投注ID',
                    },
                    {
                        value: 'user_id',
                        label: '用户ID',
                        default: true,
                    },
                ],
            },
        }
    },
}
</script>