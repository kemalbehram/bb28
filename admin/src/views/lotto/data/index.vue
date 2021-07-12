<template>
    <Card dis-hover>
        <table-header-new :config.sync="config" @on-search="onSearch" title="开奖数据">
            <Select @on-change="onChangeLotto" size="small" v-model="lotto">
                <Option :key="item.name" :value="item.name" v-for="item in this.$store.state.config.lotto_items">{{item.title}}</Option>
            </Select>

            <Select @on-change="onSearch" class="ml-6" size="small" v-if="lotto" v-model="config.value.status">
                <Option value="0">所有状态</Option>
                <Option value="2">已开奖</Option>
                <Option value="1">待开奖</Option>
                <Option value="3">异常数据</Option>
            </Select>

            <Button @click="onReset" class="ml-10" size="small" type="primary">初始</Button>
            <Button @click="onSearch" class="ml-6" size="small" type="primary">
                <Checkbox @on-change="autoLoad" size="small"></Checkbox>
                <span class="font-13">刷新</span>
            </Button>
        </table-header-new>

        <table-data :lotto="lotto" @on-search="onSearch" ref="table" />
    </Card>
</template>

<script>
import tableData from './table-data'
import JsMixins from '@/components-ue/table/table-index.js'
export default {
    mixins: [JsMixins],
    name: 'lottoData',
    components: { tableData },

    data() {
        return {
            config: {
                value: {
                    status: '0',
                },
                fields: [
                    {
                        value: 'id',
                        label: '期号',
                        default: true,
                    },
                ],
            },
            lotto: 'ca28',
            loading: false,
            setting: {
                api: '',
                visible: false,
            },
            query: { status: '0', id: null },
        }
    },

    methods: {
        onChangeLotto(lotto) {
            this.lotto = lotto
            setTimeout(() => {
                this.onSearch()
            }, 100)
        },
    },
}
</script>