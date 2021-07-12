<template>
    <Card dis-hover title="下分限制">
        <Button @click="updateData()" size="small" slot="extra" type="primary">保存设置</Button>
        <lett-loading :visible="loading" @click="getData()" />
        <grid-form :gutter="40" left="13" right="11">
            <Form :label-width="74" class="pt-20" label-position="right" slot="left">
                <FormItem label="当前房间">
                    <Select @on-change="getData" v-model="room">
                        <Option :key="item.name" :value="item.name" v-for="item in this.$store.state.config.play_types">{{item.title}}</Option>
                    </Select>
                </FormItem>

                <FormItem class="mt-20" label="当日流水">
                    <Input type="text" v-model="form.recharge_limit.amount"></Input>(0为不限制)
                </FormItem>
                <FormItem class="mt-20" label="当日投注数">
                    <Input type="text" v-model="form.recharge_limit.count"></Input>(0为不限制)
                </FormItem>
            </Form>
        </grid-form>
    </Card>
</template>

<script>
import TinymceEditor from '@/components-ue/tinymce-editor'
export default {
    name: 'lottoSetting',
    components: {
        TinymceEditor,
    },
    data() {
        return {
            room: null,
            loading: false,
            form: {
                bet_quota: {
                    min: null,
                    max: null,
                },

                disable: {
                    content: '',
                    time: ['', ''],
                },
                place: [],
                odds: [],
                single_limit: [],
                single_condition: [],
                comb_limit: [],
                comb_condition: [],
                bet_limit: {
                    total: 0,
                    dxds: 0,
                    combo: 0,
                    jdjx: 0,
                    z_27: 0,
                    o_26: 0,
                    t_25: 0,
                    o_num: 0,
                    duizi: 0,
                    sunzi: 0,
                    baozi: 0,
                    lhb: 0,
                },
                recharge_limit: {},
                // agent_rebate: {
                //     agent_son: 0,
                //     agent_money: 0,
                //     agent_amount: 0,
                // },
                agent_rebate: [],
            },

            columns: [
                {
                    title: '名称',
                    key: 'name',
                    align: 'left',
                    sortable: true,
                },

                {
                    title: '键',
                    key: 'place',
                    align: 'left',
                    sortable: true,
                },
                {
                    title: '标赔',
                    slot: 'odds',
                    align: 'right',
                    sortable: true,
                },
            ],

            dataware: {
                update: {
                    visible: false,
                    data: {},
                    fields: ['ratio', 'odds'],
                },
            },
        }
    },

    methods: {
        getData() {
            this.$get('/lotto/' + this.room + '/room/get').then((res) => {
                if (res.code === 200) {
                    this.form = res.data
                }
                console.log(this.form)
            })
        },

        updateData() {
            this.$post('/lotto/' + this.room + '/room/update', this.form)
        },
        addCondition() {
            this.form.agent_rebate.push({
                agent_son: null,
                agent_money: null,
                agent_amount: null,
            })
        },
        remove(index) {
            this.form.agent_rebate.splice(index, 1)
        },
    },
}
</script>

<style lang="less" scoped>
.ivu-form-item {
    flex: 1;
}
.text-center {
    text-align: center;
}
</style>
