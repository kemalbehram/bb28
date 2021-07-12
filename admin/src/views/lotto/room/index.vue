<template>
    <Card dis-hover title="房间设置">
        <Button @click="updateData()" size="small" slot="extra" type="primary">保存设置</Button>
        <lett-loading :visible="loading" @click="getData()" />
        <grid-form :gutter="40" left="13" right="11">
            <Form :label-width="74" class="pt-20" label-position="right" slot="left">
                <FormItem label="当前设置">
                    <Select @on-change="getData" v-model="room">
                        <Option :key="item.name" :value="item.name" v-for="item in this.$store.state.config.play_types">{{item.title}}</Option>
                    </Select>
                </FormItem>

                <FormItem class="mt-20" label="标题" v-show="form.name !== 'number' && form.name !=='special'">
                    <Input type="text" v-model="form.title"></Input>
                </FormItem>
                <FormItem class="mt-20" label="副标题" v-show="form.name != 'number' && form.name !='special'">
                    <Input type="text" v-model="form.subtitle"></Input>
                </FormItem>

                <!-- <FormItem label="投注限额">
                    <div class="display-flex">
                        <Input placeholder="单注最低限额" type="number" v-model="form.bet_quota.min" />
                        <span class="pl-10 pr-10">-</span>
                        <Input placeholder="单注最高限额" type="number" v-model="form.bet_quota.max" />
                    </div>
                </FormItem>

                <FormItem label="最高中奖">
                    <Input placeholder="单期最高中间金额" type="number" v-model="form.bet_quota.win" />
                </FormItem>-->

                <FormItem label="是否打开" v-show="form.name != 'number' && form.name !='special'">
                    <i-switch v-model="form.is_open" />
                    <span class="form-desc-middle">关闭后该房间将不再前端列表中显示</span>
                </FormItem>
                <FormItem label="总投注/最高">
                    <Input placeholder="最高限额" type="number" v-model="form.bet_limit.total" />
                </FormItem>
                <FormItem label="投注/最低">
                    <Input placeholder="最低限额" type="number" v-model="form.bet_limit.min" />
                </FormItem>
                <FormItem label="大小单双">
                    <Input placeholder="限额" type="number" v-model="form.bet_limit.dxds" />
                </FormItem>

                <Divider>大小单双总投赔率设置信息</Divider>
                <div class="display-flex" v-for="(item,index) in form.single_limit">
                    <FormItem label="投注大于">
                        <Input placeholder="限额" type="number" v-model="form.single_limit[index].bet_amount">
                            <span slot="append">时 13/14</span>
                        </Input>
                    </FormItem>
                    <FormItem>
                        <Input placeholder="限额" type="number" v-model="form.single_limit[index].odds">
                            <span slot="append">倍数</span>
                        </Input>
                    </FormItem>
                    <FormItem>
                        <i-switch v-model="form.single_limit[index].status">
                            <span slot="open">开</span>
                            <span slot="close">关</span>
                        </i-switch>
                    </FormItem>
                </div>

                <div class="display-flex" v-for="(item,index) in form.single_condition">
                    <FormItem :label="'出'+item.title+'时'">
                        <Input placeholder="赔率" type="number" v-model="form.single_condition[index].odds" />
                    </FormItem>
                    <FormItem>
                        <i-switch v-model="form.single_condition[index].status">
                            <span slot="open">开</span>
                            <span slot="close">关</span>
                        </i-switch>
                    </FormItem>
                </div>

                <FormItem label="组合">
                    <Input placeholder="限额" type="number" v-model="form.bet_limit.combo" />
                </FormItem>

                <Divider>组合总投赔率设置</Divider>

                <div class="display-flex" v-for="(item,index) in form.comb_limit">
                    <FormItem label="投注大于">
                        <Input placeholder="限额" type="number" v-model="form.comb_limit[index].bet_amount">
                            <span slot="append">时 13/14</span>
                        </Input>
                    </FormItem>
                    <FormItem>
                        <Input placeholder="限额" type="number" v-model="form.comb_limit[index].odds">
                            <span slot="append">倍数</span>
                        </Input>
                    </FormItem>
                    <FormItem>
                        <i-switch v-model="form.comb_limit[index].status">
                            <span slot="open">开</span>
                            <span slot="close">关</span>
                        </i-switch>
                    </FormItem>
                </div>

                <div class="display-flex" v-for="(item,index) in form.comb_condition">
                    <FormItem :label="'出'+item.title+'时'">
                        <Input placeholder="赔率" type="number" v-model="form.comb_condition[index].odds" />
                    </FormItem>
                    <FormItem>
                        <i-switch v-model="form.comb_condition[index].status">
                            <span slot="open">开</span>
                            <span slot="close">关</span>
                        </i-switch>
                    </FormItem>
                </div>

                <Divider>其他限额信息</Divider>

                <FormItem label="极大极小">
                    <Input placeholder="限额" type="number" v-model="form.bet_limit.jdjx" />
                </FormItem>
                <FormItem label="0/27">
                    <Input placeholder="限额" type="number" v-model="form.bet_limit.z_27" />
                </FormItem>
                <FormItem label="1/26">
                    <Input placeholder="限额" type="number" v-model="form.bet_limit.o_26" />
                </FormItem>
                <FormItem label="2/25">
                    <Input placeholder="限额" type="number" v-model="form.bet_limit.t_25" />
                </FormItem>
                <FormItem label="其他数字">
                    <Input placeholder="限额" type="number" v-model="form.bet_limit.o_num" />
                </FormItem>
                <FormItem label="对子">
                    <Input placeholder="限额" type="number" v-model="form.bet_limit.duizi" />
                </FormItem>
                <FormItem label="顺子">
                    <Input placeholder="限额" type="number" v-model="form.bet_limit.sunzi" />
                </FormItem>
                <FormItem label="豹子">
                    <Input placeholder="限额" type="number" v-model="form.bet_limit.baozi" />
                </FormItem>
                <FormItem label="龙虎豹">
                    <Input placeholder="限额" type="number" v-model="form.bet_limit.lhb" />
                </FormItem>

                <FormItem label="龙虎豹">
                    <Input placeholder="限额" type="number" v-model="form.bet_limit.lhb" />
                </FormItem>

                <FormItem label="房间规则" v-show="form.name != 'number' && form.name !='special'">
                    <tinymce-editor element="notice-editor" plugins="table code" ref="editor" v-model="form.rules"></tinymce-editor>
                </FormItem>

                <Divider orientation="center">下线返水</Divider>

                <div :key="index" class="display-flex" v-for="(item,index) in form.agent_rebate">
                    <FormItem label="金额">
                        <Input placeholder="有效负流水金额" type="number" v-model="form.agent_rebate[index].agent_money" />
                    </FormItem>
                    <FormItem class="pr-10" label="注数">
                        <Input placeholder="有效下注注数" type="number" v-model="form.agent_rebate[index].agent_amount" />
                    </FormItem>
                    <FormItem class="pr-10" label="返比">
                        <Input placeholder="百分比" type="number" v-model="form.agent_rebate[index].agent_son" />
                    </FormItem>
                    <Icon @click="remove(index)" class="pointer" size="30" type="ios-backspace-outline" />
                    <!-- <FormItem label="流水返水/二级下线">
                        <Input placeholder="百分比" type="number" v-model="form.agent_rebate[index].agent_grand" />
                    </FormItem>-->
                </div>
                <div class="text-center">
                    <Button @click="addCondition" icon="md-add" type="primary">添加条件</Button>
                </div>
            </Form>

            <div class="relative mt-20 mb-20" slot="right" v-if="form.place.length>0">
                <Table :columns="columns" :data="form.place" border class="table-form-content">
                    <template slot="odds" slot-scope="{row,index}">
                        <Input type="text" v-model="form['place'][index]['odds']"></Input>
                    </template>
                </Table>
            </div>

            <div class="no-select-tips" slot="right" v-else>请选择房间</div>
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
