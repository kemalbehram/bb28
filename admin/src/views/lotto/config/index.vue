<template>
    <Card dis-hover title="游戏设置">
        <Button @click="updateData()" size="small" slot="extra" type="primary">保存设置</Button>
        <lett-loading :visible="loading" @click="getData()" />
        <grid-form :gutter="40" left="13" right="11">
            <Form :label-width="74" class="pt-20" label-position="right" slot="left">
                <FormItem label="当前设置">
                    <Select @on-change="getData" v-model="lotto">
                        <Option :key="item.name" :value="item.name" v-for="item in this.$store.state.config.lotto_items">{{item.title}} / {{item.name}}</Option>
                    </Select>
                </FormItem>

                <FormItem class="mt-20" label="提前封盘">
                    <Input type="number" v-model="form.stop_ahead">
                        <span slot="append">秒</span>
                    </Input>
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

                <FormItem class="mb-10" label="投注限额">
                    <FormItem :key="key" :label="key | play_type" class="mb-10 bet-limit" v-for="(type,key) in form.type_option">
                        <div class="display-flex relative">
                            <Input class="mr-16" placeholder="单期最高中间金额" type="number" v-model="type.min">
                                <span slot="prepend">最低下注</span>
                            </Input>
                            <Input class="mr-16" placeholder="单期最高中间金额" type="number" v-model="type.max">
                                <span slot="prepend">最高下注</span>
                            </Input>
                            <Input placeholder="单期最高中间金额" type="number" v-model="type.win">
                                <span slot="prepend">最高中奖</span>
                            </Input>
                        </div>
                    </FormItem>
                </FormItem>

                <FormItem label="游戏暂停">
                    <div class="form-item-lotto-disable">
                        <DatePicker class="width-100" format="yyyy-MM-dd HH:mm" placeholder="选择暂停时间 暂停时间内将不可投注" type="datetimerange" v-model="form.disable.time" />
                        <Tooltip content="开启后将在设置的时间内暂停游戏" placement="top-start">
                            <i-switch v-model="form.disable.status" />
                        </Tooltip>
                    </div>

                    <Input :rows="4" class="mt-16" placeholder="请输入游戏暂停原因" type="textarea" v-model="form.disable.content"></Input>
                </FormItem>

                <FormItem label="显示游戏">
                    <i-switch v-model="form.visible" />
                    <span class="form-desc-middle">关闭后该游戏将不再前端列表中显示</span>
                </FormItem>

                <FormItem label="热门游戏">
                    <i-switch v-model="form.hot" />
                    <span class="form-desc-middle">开启后该游戏路由将直接在PC端顶部展示</span>
                </FormItem>

                <FormItem label="游戏官网">
                    <Input v-model="form.web_site" />
                </FormItem>

                <FormItem label="游戏规则">
                    <Input :rows="12" placeholder="请输入游戏规则" type="textarea" v-model="form.lotto_rule"></Input>
                </FormItem>
            </Form>

            <!-- <div class="relative mt-20 mb-20" slot="right" v-if="form.bet_places.length > 0">
                <Table :columns="columns" :data="form.bet_places" border class="table-form-content" height="800" />
            </div>-->

            <!-- <div class="no-select-tips" slot="right" v-else>请选择设置游戏</div> -->
        </grid-form>
    </Card>
</template>

<script>
export default {
    name: 'lottoSetting',
    data() {
        return {
            lotto: null,
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
                bet_places: [],
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
                    key: 'odds',
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
            this.$get('/lotto/' + this.lotto + '/config/get').then((res) => {
                if (res.code === 200) {
                    this.form = res.data
                }
            })
        },

        updateData() {
            this.form.method = 'combinationUpdate'
            this.$post('/lotto/' + this.lotto + '/config/update', this.form)
        },
    },
}
</script>

<style lang="less" scoped>
/deep/.ivu-input-group-append {
    width: 64px;
    color: @desc;
}

.table-form-content {
    /deep/ .ivu-table th {
        height: 36px;
    }
    /deep/.ivu-table td {
        height: 36px !important;
        font-size: 13px;
    }
}

.no-select-tips {
    margin: 20px 0;
    line-height: 800px;
    text-align: center;
    border: 1px solid @line;
    color: @desc;
    border-radius: 2px;
}

.form-item-lotto-disable {
    display: flex;
    .ivu-switch {
        // margin-top: 5px;
        margin-left: 10px;
    }
}

.bet-limit {
    /deep/ .ivu-form-item-label {
        text-align: left;
        width: 60px !important;
    }
    .ivu-input-group {
        width: auto;
    }
}
</style>
